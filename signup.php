<?php
include('includes/config.php');

if (isset($_SESSION['logged_in'])) {
  header("location: index.php");
  exit();
}

include('includes/header.php');
?>
<div class="page-header">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-6 ml-auto mr-auto">
        <div class="card card-login">
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