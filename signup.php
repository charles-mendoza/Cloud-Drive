<?php

include('includes/config.php');

if (isset($_SESSION['logged_in'])) {
  header("location: index.php");
  exit();
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Cloud Drive - Sign Up</title>
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
        <div class="col-lg-5 col-md-6 ml-auto mr-auto">
          <div class="card card-login" style="min-height:470px">
            <form class="form" role="form" method="post" action="action.php">
              <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger alert-block">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong><?php if ($_GET['error'] == "password") echo "Password mismatch."; else if ($_GET['error'] == "user-exists") echo "User already exists.";  ?></strong>
                </div>
                <?php } ?>
              <div class="card-body">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="material-icons">face</i>
                    </span>
                  </div>
                  <input type="text" id="first_name" name="first_name" class="form-control" placeholder="First Name" required autofocus>
                  &nbsp&nbsp&nbsp
                  <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Last Name" required>
                </div>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="fa fa-id-card"></i>
                    </span>
                  </div>
                  <input id="id" type="text" class="form-control" name="id" pattern="[0-9]+" minlength="4" maxlength="9" placeholder="ID Number" required>
                </div>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="material-icons">lock_outline</i>
                    </span>
                  </div>
                  <input id="password" name="password" pattern=".{6,}" type="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="input-group pl-5">
                  &nbsp&nbsp<input id="password_confirm" pattern=".{6,}" name="password_confirm" type="password" class="form-control" placeholder="Confirm Password" required>
                </div>
                <div class="col-md-5 ml-auto mr-auto mt-5 text-center">
                  <button class="btn btn-round btn-success" name="action" value="signup" type="submit">Sign Up</button>
                </div>
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