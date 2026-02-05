<?php
session_start();
require '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_jurnal'])) {
    $id_jurnal = (int)$_POST['id_jurnal'];
    
    $query = "
        SELECT 
            jl.*,
            coa.nama_akun
        FROM journal_lines jl
        JOIN chart_of_accounts coa ON jl.id_akun = coa.id_akun
        WHERE jl.id_jurnal = ?
        ORDER BY jl.id_line ASC
    ";
    
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_jurnal);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $lines = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $lines[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($lines);
    exit;
}

// If not a valid request, return empty array
header('Content-Type: application/json');
echo json_encode([]);
exit;
?>