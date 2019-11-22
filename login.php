<?php
include('includes/config.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Cloud Drive - Login</title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="fonts/roboto.css" rel="stylesheet">
  <link href="fonts/robotoslab.css" rel="stylesheet">
  <link href="fonts/materialicons.css" rel="stylesheet">
  <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
  <!-- CSS Files -->
  <link href="css/material-kit.css?v=2.0.4'" rel="stylesheet" />
</head>
<body class="login-page sidebar-collapse">
  <?php include('includes/navbar.php'); ?>
  <div class="page-header">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-6 ml-auto mr-auto">
          <div class="card card-login">
            <form class="form" role="form" method="post" action="action.php">
              <?php if (isset($_GET['error']) && $_GET['error'] == "invalid-user") { ?>
                <div class="alert alert-danger alert-block">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Invalid user.</strong>
                </div>
              <?php } else if (isset($_GET['message']) && $_GET['message'] == "signup-success") { ?>
                <div class="alert alert-success alert-block">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Successfully signed up! Please login.</strong>
                </div>
              <?php } ?>
              <div class="card-body">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="fa fa-id-card"></i>
                    </span>
                  </div>
                  <input id="id" type="text" class="form-control" name="id" pattern="[0-9]+" minlength="4" maxlength="9" placeholder="ID Number" required autofocus>
                </div>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="material-icons">lock_outline</i>
                    </span>
                  </div>
                  <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="col-md-5 ml-auto mr-auto mt-5 text-center">
                  <button class="btn btn-round btn-success ml-3" name="action" value="login" type="submit">Login</button>
                </div>
              </div>
              <div class="footer text-center">
                <a href="signup.php" class="btn btn-success btn-link btn-wd btn-lg">Create account</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include('includes/footer.php'); ?>
</body>
</html>