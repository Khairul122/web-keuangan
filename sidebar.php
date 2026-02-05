  <div id="wrapper">

      <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

          <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
              <div class="sidebar-brand-icon rotate-n-15">
                  <i class="fas fa-seedling"></i>
              </div>
              <div class="sidebar-brand-text mx-3">CV Bina Padi Sabatang</div>
          </a>

          <hr class="sidebar-divider my-0">

          <li class="nav-item active">
              <a class="nav-link" href="index.php">
                  <i class="fas fa-fw fa-tachometer-alt"></i>
                  <span>Dashboard</span></a>
          </li>

          <?php if ($_SESSION['level'] === 'admin'): ?>
          <hr class="sidebar-divider">

          <div class="sidebar-heading">
              Transaksi
          </div>

          <li class="nav-item">
              <a class="nav-link collapsed" href="pendapatan.php">
                  <i class="fas fa-fw fa-arrow-up"></i>
                  <span>Pendapatan</span>
              </a>
          </li>

          <li class="nav-item">
              <a class="nav-link collapsed" href="pengeluaran.php">
                  <i class="fas fa-fw fa-arrow-down"></i>
                  <span>Pengeluaran</span>
              </a>
          </li>

          <li class="nav-item">
              <a class="nav-link collapsed" href="hutang.php">
                  <i class="fas fa-fw fa-hand-holding-usd"></i>
                  <span>Hutang</span>
              </a>
          </li>

          <li class="nav-item">
              <a class="nav-link collapsed" href="arus-kas-auto.php">
                  <i class="fas fa-fw fa-money-bill-wave"></i>
                  <span>Arus Kas</span>
              </a>
          </li>

          <li class="nav-item">
              <a class="nav-link collapsed" href="neraca-saldo-auto.php">
                  <i class="fas fa-fw fa-balance-scale"></i>
                  <span>Neraca Saldo</span>
              </a>
          </li>

          <li class="nav-item">
              <a class="nav-link collapsed" href="laba-rugi-auto.php">
                  <i class="fas fa-fw fa-chart-line"></i>
                  <span>Laba Rugi</span>
              </a>
          </li>

          <hr class="sidebar-divider">

          <div class="sidebar-heading">
              Jurnal
          </div>

          <li class="nav-item">
              <a class="nav-link collapsed" href="jurnal-umum.php">
                  <i class="fas fa-fw fa-book-open"></i>
                  <span>Jurnal Umum</span>
              </a>
          </li>

          <hr class="sidebar-divider">

          <div class="sidebar-heading">
              Master Data
          </div>

          <li class="nav-item">
              <a class="nav-link collapsed" href="coa.php">
                  <i class="fas fa-fw fa-book"></i>
                  <span>COA</span>
              </a>
          </li>

          <hr class="sidebar-divider">

          <div class="sidebar-heading">
              Karyawan
          </div>

          <li class="nav-item">
              <a class="nav-link collapsed" href="karyawan.php">
                  <i class="fas fa-fw fa-users"></i>
                  <span>Karyawan</span>
              </a>
          </li>
          <?php endif; ?>

          <?php if ($_SESSION['level'] === 'pemilik'): ?>
          <hr class="sidebar-divider">

          <div class="sidebar-heading">
              Laporan
          </div>

          <li class="nav-item">
              <a class="nav-link" href="laporan.php">
                  <i class="fas fa-fw fa-file-pdf"></i>
                  <span>Export Laporan</span></a>
          </li>
          <?php endif; ?>

          <hr class="sidebar-divider d-none d-md-block">

          <div class="text-center d-none d-md-inline">
              <button class="rounded-circle border-0" id="sidebarToggle"></button>
          </div>

      </ul>

      <div id="content-wrapper" class="d-flex flex-column">