<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Login page for CV Bina Padi Sabatang accounting system">
  <meta name="author" content="">

  <title>Login - CV Bina Padi Sabatang</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Anime.js for animations -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
  
  <style>
    /* Full viewport height */
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      overflow-x: hidden;
    }
    
    /* Formal color scheme */
    body {
      background: #f8f9fa;
      font-family: 'Nunito', sans-serif;
      color: #3a3b45;
    }
    
    /* Half screen layout */
    .half-screen {
      height: 100vh;
      display: flex;
    }
    
    .half-left {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #2e59d9;
      color: white;
      padding: 2rem;
      position: relative;
      overflow: hidden;
    }
    
    .half-right {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      background: white;
    }
    
    /* Card styles */
    .form-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      padding: 2.5rem;
      width: 100%;
      max-width: 450px;
      color: #3a3b45;
    }
    
    .info-panel {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 16px;
      padding: 2.5rem;
      width: 100%;
      max-width: 500px;
      color: white;
      text-align: center;
    }
    
    /* Floating animation */
    .floating-element {
      animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0px); }
    }
    
    /* Formal text styles */
    .formal-text {
      color: #2e59d9;
      font-weight: 700;
    }
    
    /* Feature cards */
    .feature-card-formal {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 12px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      transition: all 0.3s ease;
      text-align: left;
    }
    
    .feature-card-formal:hover {
      transform: translateY(-5px);
      background: rgba(255, 255, 255, 0.25);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    /* Icon circle */
    .icon-circle-formal {
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      background: white;
      color: #2e59d9;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      margin-right: 1rem;
    }
    
    /* Form controls */
    .form-control-formal {
      background: #f8f9fc;
      border: 1px solid #e3e6f0;
      border-radius: 10px;
      color: #3a3b45;
      padding: 0.8rem 1.2rem;
      transition: all 0.3s ease;
    }
    
    .form-control-formal:focus {
      background: white;
      border-color: #4e73df;
      box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
      color: #3a3b45;
      outline: none;
    }
    
    .form-control-formal::placeholder {
      color: #858796;
    }
    
    /* Button */
    .btn-formal {
      background: #4e73df;
      border: none;
      border-radius: 10px;
      padding: 0.8rem;
      font-weight: 600;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      color: white;
    }
    
    .btn-formal:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
      background: #2e59d9;
    }
    
    .btn-formal:active {
      transform: translateY(0);
    }
    
    /* Particles background */
    .particles-container {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: -1;
    }
    
    .particle {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      animation: particle-float linear infinite;
    }
    
    @keyframes particle-float {
      to {
        transform: translateY(-100px) rotate(360deg);
        opacity: 0;
      }
    }
    
    /* Responsive adjustments */
    @media (max-width: 991.98px) {
      .half-screen {
        flex-direction: column;
        height: auto;
      }
      
      .half-left, .half-right {
        height: auto;
        padding: 2rem 1rem;
      }
      
      .half-left {
        border-right: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.18);
      }
    }
    
    @media (max-width: 575.98px) {
      .form-card, .info-panel {
        padding: 1.5rem;
        margin: 0 1rem;
      }
      
      .feature-card-formal {
        padding: 1rem;
      }
    }
  </style>
</head>

<body>
  <!-- Animated particles background -->
  <div class="particles-container" id="particles"></div>
  
  <div class="half-screen">
    <!-- Left side - Information Panel -->
    <div class="half-left">
      <div class="info-panel floating-element">
        <h1 class="h2 formal-text mb-4">CV Bina Padi Sabatang</h1>
        <p class="opacity-75 mb-5">Sistem Akuntansi Terpadu Generasi Baru</p>
        
        <div class="features-container">
          <div class="feature-card-formal d-flex align-items-start">
            <div class="icon-circle-formal">
              <i class="fas fa-calculator fa-lg"></i>
            </div>
            <div>
              <h5 class="font-weight-bold text-white">Manajemen Keuangan</h5>
              <p class="text-white opacity-75 small mb-0">Catat semua transaksi keuangan secara rapi dan terstruktur</p>
            </div>
          </div>
          
          <div class="feature-card-formal d-flex align-items-start">
            <div class="icon-circle-formal">
              <i class="fas fa-chart-line fa-lg"></i>
            </div>
            <div>
              <h5 class="font-weight-bold text-white">Laporan Keuangan</h5>
              <p class="text-white opacity-75 small mb-0">Generate laporan keuangan secara otomatis dan profesional</p>
            </div>
          </div>
          
          <div class="feature-card-formal d-flex align-items-start">
            <div class="icon-circle-formal">
              <i class="fas fa-lock fa-lg"></i>
            </div>
            <div>
              <h5 class="font-weight-bold text-white">Keamanan Terjamin</h5>
              <p class="text-white opacity-75 small mb-0">Data keuangan Anda aman dan terlindungi dengan teknologi terbaru</p>
            </div>
          </div>
        </div>
        
        <div class="mt-4">
          <div class="d-flex justify-content-center">
            <div class="bg-white opacity-25 rounded-circle p-2 mr-2"></div>
            <div class="bg-white opacity-50 rounded-circle p-2 mr-2"></div>
            <div class="bg-white opacity-75 rounded-circle p-2"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right side - Login Form -->
    <div class="half-right">
      <div class="form-card" id="loginCard">
        <div class="text-center mb-4">
          <div class="icon-circle-formal mx-auto mb-3" style="width: 70px; height: 70px; background: #4e73df; color: white;">
            <i class="fas fa-lock fa-2x"></i>
          </div>
          <h1 class="h4 text-gray-900 font-weight-bold mb-2">Selamat Datang Kembali!</h1>
          <p class="text-gray-600">Silakan masuk ke akun Anda</p>
        </div>
        
        <?php if(isset($_GET['pesan'])): ?>
        <?php if($_GET['pesan'] == "gagal"): ?>
        <div class="alert alert-danger" role="alert">
          Login gagal! Email atau password salah.
        </div>
        <?php elseif($_GET['pesan'] == "belum_login"): ?>
        <div class="alert alert-warning" role="alert">
          Silakan login terlebih dahulu!
        </div>
        <?php endif; ?>
        <?php endif; ?>
        
        <form class="user" action="proses-login.php" method="post" id="loginForm">
          <div class="form-group mb-3">
            <input type="email" name="email" class="form-control form-control-user form-control-formal" id="exampleInputEmail" aria-describedby="emailHelp" value="admin@gmail.com" placeholder="Alamat Email..." autocomplete="off">
          </div>
          <div class="form-group mb-3">
            <input type="password" name="pass" class="form-control form-control-user form-control-formal" id="exampleInputPassword" value="admin" placeholder="Kata Sandi..." autocomplete="off">
          </div>
          <div class="form-group d-flex justify-content-between align-items-center mb-4">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="customCheck">
              <label class="custom-control-label text-gray-600" for="customCheck">Ingat saya</label>
            </div>
            <a class="text-primary small" href="#">Lupa Kata Sandi?</a>
          </div>
          <input type="submit" name="submit" class="btn btn-user btn-block btn-formal font-weight-bold py-3" value="MASUK">
        </form>
        
        <hr class="my-4">
        <div class="text-center">
          <span class="text-gray-600 small">© 2026 CV Bina Padi Sabatang. Hak Cipta Dilindungi.</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Animation script using Anime.js -->
  <script>
    // Create animated particles
    function createParticles() {
      const container = document.getElementById('particles');
      const particleCount = 30;
      
      for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.classList.add('particle');
        
        // Random properties
        const size = Math.random() * 20 + 5;
        const posX = Math.random() * 100;
        const posY = Math.random() * 100;
        const duration = Math.random() * 10 + 10;
        const delay = Math.random() * 5;
        
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${posX}%`;
        particle.style.top = `${posY}%`;
        particle.style.animationDuration = `${duration}s`;
        particle.style.animationDelay = `${delay}s`;
        particle.style.opacity = Math.random() * 0.5 + 0.1;
        
        container.appendChild(particle);
      }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
      // Create animated particles
      createParticles();
      
      // Animate the left panel elements
      anime({
        targets: '.half-left .info-panel',
        translateX: [-50, 0],
        opacity: [0, 1],
        duration: 1000,
        delay: 200,
        easing: 'easeOutQuart'
      });

      // Animate the feature cards
      const featureCards = document.querySelectorAll('.feature-card-formal');
      featureCards.forEach((card, index) => {
        anime({
          targets: card,
          translateX: [-50, 0],
          opacity: [0, 1],
          duration: 800,
          delay: 400 + (index * 200),
          easing: 'easeOutQuart'
        });
      });

      // Animate the login form
      anime({
        targets: '.half-right .form-card',
        translateX: [50, 0],
        opacity: [0, 1],
        duration: 1200,
        delay: 300,
        easing: 'easeOutQuart'
      });

      // Animate form elements inside the card
      anime({
        targets: '.form-card .form-group',
        translateX: [-30, 0],
        opacity: [0, 1],
        duration: 600,
        delay: (el, i) => 800 + (i * 150),
        easing: 'easeOutQuart'
      });

      // Animate the submit button
      anime({
        targets: '.btn-formal',
        scale: [0.8, 1],
        opacity: [0, 1],
        duration: 700,
        delay: 1400,
        easing: 'easeOutBack'
      });

      // Form submission animation
      const form = document.getElementById('loginForm');
      if (form) {
        form.addEventListener('submit', function(e) {
          anime({
            targets: '#loginCard',
            scale: [1, 0.98],
            duration: 300,
            direction: 'alternate',
            loop: 1,
            easing: 'easeInOutSine'
          });
        });
      }

      // Floating animation for elements
      anime({
        targets: '.floating-element',
        translateY: [
          { value: -10, duration: 3000, delay: 0 },
          { value: 10, duration: 3000, delay: 0 }
        ],
        direction: 'alternate',
        loop: true,
        easing: 'easeInOutSine'
      });
    });
  </script>
</body>
</html>