<?php
$root_dir = dirname(dirname(__FILE__));
require_once $root_dir . '/koneksi.php';

function generateNomorJurnal($tanggal, $koneksi) {
    $date = date('Ymd', strtotime($tanggal));

    $sql = "SELECT nomor_jurnal FROM journal_entries
            WHERE nomor_jurnal LIKE 'JV-$date%'
            ORDER BY id_jurnal DESC LIMIT 1";
    $result = mysqli_query($koneksi, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $last_nomor = mysqli_fetch_assoc($result)['nomor_jurnal'];
        $last_urut = intval(substr($last_nomor, -4));
        $new_urut = $last_urut + 1;
    } else {
        $new_urut = 1;
    }

    $nomor_jurnal = 'JV-' . $date . '-' . str_pad($new_urut, 4, '0', STR_PAD_LEFT);
    return $nomor_jurnal;
}

function buatJurnalPemasukan($id_pemasukan, $koneksi) {
    $sql = "SELECT * FROM pemasukan WHERE id_pemasukan = $id_pemasukan";
    $result = mysqli_query($koneksi, $sql);

    if (!$result || mysqli_num_rows($result) == 0) {
        return ['success' => false, 'message' => 'Data pemasukan tidak ditemukan'];
    }

    $data = mysqli_fetch_assoc($result);

    if (empty($data['id_akun_kas'])) {
        $sql_default = "SELECT id_akun FROM chart_of_accounts WHERE nomor_akun = '1-1001' LIMIT 1";
        $res_default = mysqli_query($koneksi, $sql_default);
        if ($res_default) {
            $row = mysqli_fetch_assoc($res_default);
            $data['id_akun_kas'] = $row ? $row['id_akun'] : 78;
        } else {
            $data['id_akun_kas'] = 78;
        }
    }

    if (empty($data['id_akun_pendapatan'])) {
        $sql_default = "SELECT id_akun FROM chart_of_accounts WHERE nomor_akun = '4-1001' LIMIT 1";
        $res_default = mysqli_query($koneksi, $sql_default);
        if ($res_default) {
            $row = mysqli_fetch_assoc($res_default);
            $data['id_akun_pendapatan'] = $row ? $row['id_akun'] : 86;
        } else {
            $data['id_akun_pendapatan'] = 86;
        }
    }

    $tanggal = $data['tgl_pemasukan'];
    $jumlah = floatval($data['jumlah']);
    $sumber = mysqli_real_escape_string($koneksi, $data['sumber']);
    $id_akun_kas = $data['id_akun_kas'];
    $id_akun_pendapatan = $data['id_akun_pendapatan'];
    $id_user = 1;

    $nomor_jurnal = generateNomorJurnal($tanggal, $koneksi);

    mysqli_begin_transaction($koneksi);

    try {
        $sql_jurnal = "INSERT INTO journal_entries
                       (nomor_jurnal, tanggal, keterangan, id_ref_transaksi, tipe_ref_transaksi, id_user)
                       VALUES ('$nomor_jurnal', '$tanggal', 'Pemasukan: $sumber', $id_pemasukan, 'pemasukan', $id_user)";
        mysqli_query($koneksi, $sql_jurnal);
        $id_jurnal = mysqli_insert_id($koneksi);

        $sql_debit = "INSERT INTO journal_lines
                      (id_jurnal, id_akun, debit, kredit)
                      VALUES ($id_jurnal, $id_akun_kas, $jumlah, 0)";
        mysqli_query($koneksi, $sql_debit);

        $sql_kredit = "INSERT INTO journal_lines
                       (id_jurnal, id_akun, debit, kredit)
                       VALUES ($id_jurnal, $id_akun_pendapatan, 0, $jumlah)";
        mysqli_query($koneksi, $sql_kredit);

        mysqli_commit($koneksi);

        return [
            'success' => true,
            'message' => 'Jurnal berhasil dibuat',
            'nomor_jurnal' => $nomor_jurnal,
            'id_jurnal' => $id_jurnal
        ];

    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        return [
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}

function buatJurnalPengeluaran($id_pengeluaran, $koneksi) {
    $sql = "SELECT * FROM pengeluaran WHERE id_pengeluaran = $id_pengeluaran";
    $result = mysqli_query($koneksi, $sql);

    if (!$result || mysqli_num_rows($result) == 0) {
        return ['success' => false, 'message' => 'Data pengeluaran tidak ditemukan'];
    }

    $data = mysqli_fetch_assoc($result);

    if (empty($data['id_akun_kas'])) {
        $sql_default = "SELECT id_akun FROM chart_of_accounts WHERE nomor_akun = '1-1001' LIMIT 1";
        $res_default = mysqli_query($koneksi, $sql_default);
        if ($res_default) {
            $row = mysqli_fetch_assoc($res_default);
            $data['id_akun_kas'] = $row ? $row['id_akun'] : 78;
        } else {
            $data['id_akun_kas'] = 78;
        }
    }

    if (empty($data['id_akun_beban'])) {
        $sumber_lower = strtolower($data['sumber']);

        if (strpos($sumber_lower, 'gaji') !== false || strpos($sumber_lower, 'upah') !== false) {
            $sql_default = "SELECT id_akun FROM chart_of_accounts WHERE nomor_akun = '5-1001' LIMIT 1";
        } elseif (strpos($sumber_lower, 'listrik') !== false || strpos($sumber_lower, 'air') !== false) {
            $sql_default = "SELECT id_akun FROM chart_of_accounts WHERE nomor_akun = '5-1001' LIMIT 1";
        } elseif (strpos($sumber_lower, 'sewa') !== false || strpos($sumber_lower, 'kontrak') !== false) {
            $sql_default = "SELECT id_akun FROM chart_of_accounts WHERE nomor_akun = '5-1001' LIMIT 1";
        } elseif (strpos($sumber_lower, 'bensin') !== false || strpos($sumber_lower, 'transport') !== false) {
            $sql_default = "SELECT id_akun FROM chart_of_accounts WHERE nomor_akun = '5-1001' LIMIT 1";
        } else {
            $sql_default = "SELECT id_akun FROM chart_of_accounts WHERE nomor_akun = '5-1001' LIMIT 1";
        }

        $res_default = mysqli_query($koneksi, $sql_default);
        if ($res_default) {
            $row = mysqli_fetch_assoc($res_default);
            $data['id_akun_beban'] = $row ? $row['id_akun'] : 87;
        } else {
            $data['id_akun_beban'] = 87;
        }
    }

    $tanggal = $data['tgl_pengeluaran'];
    $jumlah = floatval($data['jumlah']);
    $sumber = mysqli_real_escape_string($koneksi, $data['sumber']);
    $id_akun_beban = $data['id_akun_beban'];
    $id_akun_kas = $data['id_akun_kas'];
    $id_user = 1;

    $nomor_jurnal = generateNomorJurnal($tanggal, $koneksi);

    mysqli_begin_transaction($koneksi);

    try {
        $sql_jurnal = "INSERT INTO journal_entries
                       (nomor_jurnal, tanggal, keterangan, id_ref_transaksi, tipe_ref_transaksi, id_user)
                       VALUES ('$nomor_jurnal', '$tanggal', 'Pengeluaran: $sumber', $id_pengeluaran, 'pengeluaran', $id_user)";
        mysqli_query($koneksi, $sql_jurnal);
        $id_jurnal = mysqli_insert_id($koneksi);

        $sql_debit = "INSERT INTO journal_lines
                      (id_jurnal, id_akun, debit, kredit)
                      VALUES ($id_jurnal, $id_akun_beban, $jumlah, 0)";
        mysqli_query($koneksi, $sql_debit);

        $sql_kredit = "INSERT INTO journal_lines
                       (id_jurnal, id_akun, debit, kredit)
                       VALUES ($id_jurnal, $id_akun_kas, 0, $jumlah)";
        mysqli_query($koneksi, $sql_kredit);

        mysqli_commit($koneksi);

        return [
            'success' => true,
            'message' => 'Jurnal berhasil dibuat',
            'nomor_jurnal' => $nomor_jurnal,
            'id_jurnal' => $id_jurnal
        ];

    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        return [
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}

function hapusJurnalTransaksi($id_transaksi, $tipe_transaksi, $koneksi) {
    $sql = "DELETE FROM journal_entries
            WHERE id_ref_transaksi = $id_transaksi
            AND tipe_ref_transaksi = '$tipe_transaksi'";

    if (mysqli_query($koneksi, $sql)) {
        return ['success' => true, 'message' => 'Jurnal berhasil dihapus'];
    } else {
        return ['success' => false, 'message' => 'Gagal menghapus jurnal: ' . mysqli_error($koneksi)];
    }
}

function getCOAOptions($koneksi, $jenis_akun = null, $selected = null) {
    $sql = "SELECT * FROM chart_of_accounts WHERE is_active = 1";

    if ($jenis_akun) {
        $sql .= " AND jenis_akun = '$jenis_akun'";
    }

    $sql .= " ORDER BY nomor_akun";

    $result = mysqli_query($koneksi, $sql);
    $options = '';

    while ($row = mysqli_fetch_assoc($result)) {
        $sel = ($selected == $row['id_akun']) ? 'selected' : '';
        $options .= "<option value='{$row['id_akun']}' $sel>
            {$row['nomor_akun']} - {$row['nama_akun']}
        </option>";
    }

    return $options;
}

function getNamaAkun($id_akun, $koneksi) {
    $sql = "SELECT nama_akun FROM chart_of_accounts WHERE id_akun = $id_akun";
    $result = mysqli_query($koneksi, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result)['nama_akun'];
    }

    return 'Unknown Account';
}

function buatJurnalHutang($id_hutang, $koneksi) {
    $sql = "SELECT * FROM hutang WHERE id_hutang = $id_hutang";
    $result = mysqli_query($koneksi, $sql);

    if (!$result || mysqli_num_rows($result) == 0) {
        return ['success' => false, 'message' => 'Data hutang tidak ditemukan'];
    }

    $data = mysqli_fetch_assoc($result);

    $sumber_lower = strtolower($data['alasan']);

    if (strpos($sumber_lower, 'aset') !== false ||
        strpos($sumber_lower, 'peralatan') !== false ||
        strpos($sumber_lower, 'perlengkapan') !== false ||
        strpos($sumber_lower, 'kendaraan') !== false ||
        strpos($sumber_lower, 'bangunan') !== false ||
        strpos($sumber_lower, 'tanah') !== false) {
        $sql_debit = "SELECT id_akun FROM chart_of_accounts WHERE nomor_akun = '1-1005' LIMIT 1";
    } else {
        $sql_debit = "SELECT id_akun FROM chart_of_accounts WHERE nomor_akun = '5-1001' LIMIT 1";
    }

    $res_debit = mysqli_query($koneksi, $sql_debit);
    $id_akun_debit = $res_debit ? mysqli_fetch_assoc($res_debit)['id_akun'] : 87;

    $sql_kredit = "SELECT id_akun FROM chart_of_accounts WHERE nomor_akun = '2-1001' LIMIT 1";
    $res_kredit = mysqli_query($koneksi, $sql_kredit);
    $id_akun_kredit = $res_kredit ? mysqli_fetch_assoc($res_kredit)['id_akun'] : 83;

    $tanggal = $data['tgl_hutang'];
    $jumlah = floatval($data['jumlah']);
    $alasan = mysqli_real_escape_string($koneksi, $data['alasan']);
    $id_user = $data['id_user'] ?? 1;

    $nomor_jurnal = generateNomorJurnal($tanggal, $koneksi);

    mysqli_begin_transaction($koneksi);

    try {
        $sql_jurnal = "INSERT INTO journal_entries
                       (nomor_jurnal, tanggal, keterangan, id_ref_transaksi, tipe_ref_transaksi, id_user)
                       VALUES ('$nomor_jurnal', '$tanggal', 'Hutang: $alasan', $id_hutang, 'hutang', $id_user)";
        mysqli_query($koneksi, $sql_jurnal);
        $id_jurnal = mysqli_insert_id($koneksi);

        $sql_debit_line = "INSERT INTO journal_lines
                      (id_jurnal, id_akun, debit, kredit)
                      VALUES ($id_jurnal, $id_akun_debit, $jumlah, 0)";
        mysqli_query($koneksi, $sql_debit_line);

        $sql_kredit_line = "INSERT INTO journal_lines
                       (id_jurnal, id_akun, debit, kredit)
                       VALUES ($id_jurnal, $id_akun_kredit, 0, $jumlah)";
        mysqli_query($koneksi, $sql_kredit_line);

        mysqli_commit($koneksi);

        return [
            'success' => true,
            'message' => 'Jurnal hutang berhasil dibuat',
            'nomor_jurnal' => $nomor_jurnal,
            'id_jurnal' => $id_jurnal
        ];

    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        return [
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}

function hapusJurnalHutang($id_hutang, $koneksi) {
    $sql = "DELETE FROM journal_entries
            WHERE id_ref_transaksi = $id_hutang
            AND tipe_ref_transaksi = 'hutang'";

    if (mysqli_query($koneksi, $sql)) {
        return ['success' => true, 'message' => 'Jurnal hutang berhasil dihapus'];
    } else {
        return ['success' => false, 'message' => 'Gagal menghapus jurnal hutang: ' . mysqli_error($koneksi)];
    }
}
?>