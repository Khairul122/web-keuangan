<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Login</title>

  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">


  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body style="background: linear-gradient(90deg, #FC466B 0%, #3F5EFB 100%);">

  <div class="container">

    <div class="row justify-content-center">

      <div class="col-xl-5 col-lg-6 col-md-4">

        <div class="card o-hidden border-0 shadow-lg my-5">

          <div class="p-5">
            <div class="text-center">
              <h1 class="h4 text-gray-900 mb-4">CV Bina Padi Sabatang </h1>
            </div>
            <form class="user" action="proses-login.php" method="post">
              <div class="form-group">
                <input type="email" name="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" value="admin@gmail.com" placeholder="Email...">
              </div>
              <div class="form-group">
                <input type="password" name="pass" class="form-control form-control-user" id="exampleInputPassword" value="admin" placeholder="Password..">
              </div>
              <div class="form-group">
                <div class="custom-control custom-checkbox small">
                  <input type="checkbox" class="custom-control-input" id="customCheck">
                  <label class="custom-control-label" for="customCheck">Ingat akun</label>
                </div>
              </div>
              <input type="submit" name="submit" class="btn btn-primary btn-user btn-block" value="Masuk">
            </form>
            <br>


            <hr>
          </div>

        </div>
      </div>

    </div>

  </div>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>