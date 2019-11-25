<?php

$config = file_get_contents(__DIR__.'/includes/config.php');
$dbDone = strpos($config, "{db_server}") == false;

if (isset($_POST['action'])) {
  switch ($_POST['action']) {
    case 'database':
      $mysqli = mysqli_connect($_POST['db_server'], $_POST['db_username'], $_POST['db_password'], $_POST['db_name']);

      if (!$mysqli) {
        $db_error = true;
        $db_error_msg = "MySQL error: ".mysqli_connect_error();
        break;
      }

      if ($mysqli->query("SHOW DATABASES LIKE '".$_POST['db_name']."'")->num_rows == 0) {
        $db_error = true;
        $db_error_msg = "MySQL error: Unknown database '".$_POST['db_name']."'";
        break;
      }

      $q="DROP TABLE IF EXISTS `user`;";
      mysqli_query($mysqli,$q);

      $q="CREATE TABLE `user` (
          `id` int(11) NOT NULL,
          `password` varchar(100) NOT NULL,
          `salt` varchar(100) NOT NULL,
          `usergroup` int(11) NOT NULL DEFAULT '2',
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
      mysqli_query($mysqli,$q);

      $q="DROP TABLE IF EXISTS `usergroup`;";
      mysqli_query($mysqli,$q);

      $q="CREATE TABLE `usergroup` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `description` varchar(100) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
      mysqli_query($mysqli,$q);

      $q="INSERT INTO `usergroup` (`id`, `description`) VALUES
          (1, 'Administrator'),
          (2, 'Student');";
      mysqli_query($mysqli,$q);

      $q="DROP TABLE IF EXISTS `file`;";
      mysqli_query($mysqli,$q);

      $q="CREATE TABLE `file` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(260) NOT NULL,
          `extension` varchar(260) NOT NULL,
          `in_trash` tinyint(4) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
      mysqli_query($mysqli,$q);

      // change mysql connection variables
      $str = file_get_contents(__DIR__.'/includes/config.php');
      $str = str_replace('if (DB_SERVER == "{db_server}") { header("location: install"); exit; }', '', $str);
      $str = str_replace("{db_server}", $_POST['db_server'], $str);
      $str = str_replace("{db_username}", $_POST['db_username'], $str);
      $str = str_replace("{db_password}", $_POST['db_password'], $str);
      $str = str_replace("{db_name}", $_POST['db_name'], $str);
      $fp = fopen('includes/config.php', 'wb');
      fwrite($fp, $str);
      fclose($fp);
      chmod('includes/config.php', 0666);

      $dbDone = true;

      break;
    case 'admin':
      require('includes/config.php');
      $id = mysqli_real_escape_string($mysqli, $_POST['id']);
      $password = mysqli_real_escape_string($mysqli, $_POST['password']);
      $salt = uniqid(mt_rand(), true);
      $password = md5(md5($password).$salt);
      mysqli_query($mysqli, "INSERT INTO `user` VALUES('$id', '$password', '$salt', 1);");
      header("location: login");
      unlink(__FILE__);
      exit;
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Install</title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <link href="css/material-kit.css?v=2.0.4'" rel="stylesheet" />
</head>
<body class="login-page sidebar-collapse">
  <div class="page-header">
    <div class="container">
    	<div class="row">
        <?php if (!$dbDone) { ?>
        <div class="col-md-4 ml-auto mr-auto" id="db-setup">
          <div class="card card-login">
            <div class="card-header card-header-success text-center">
                <h4 class="card-title">DATABASE</h4>
            </div>
            <form class="form" role="form" action="install.php" method="POST" id="form-db-setup">
              <div class="card-body">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                    </span>
                  </div>
                  <input type="text" id="db_server" name="db_server" class="form-control" placeholder="Server" required autofocus>
                </div>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                    </span>
                  </div>
                  <input type="text" id="db_username" name="db_username" class="form-control" placeholder="Username" required>
                </div>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                    </span>
                  </div>
                  <input type="password" id="db_password" name="db_password" class="form-control" placeholder="Password">
                </div>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                    </span>
                  </div>
                  <input type="text" id="db_name" name="db_name" class="form-control" placeholder="Database" required>
                </div>
                <div class="mt-5 text-center">
                  <button class="btn btn-round btn-success ml-3" name="action" value="database" type="submit" id="btn-db">CONNECT</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <?php
          if (!empty($db_error_msg)) {
            echo '<div class="w-100"><div class="alert alert-danger">'.$db_error_msg.'</div></div>';
          }
        } else { ?>
          <div class="col-md-5 ml-auto mr-auto" id="error-checker">
          <div class="card">
            <div class="card-body pt-4">
              <?php

              $php_version = phpversion();
              if ($php_version < 5) {
                $error = true;
                $php_error = "PHP version is $php_version - too old!";
              }

              $_SESSION['install_session_check'] = 1;
              if(empty($_SESSION['install_session_check'])) {
                $error = true;
                $session_error = "Sessions must be enabled!";
              }

              $upload_dir = "uploads";
              if (!file_exists($upload_dir)) {
                if (!mkdir($upload_dir, 0777, true)) {
                  $error = true;
                  $folder_error = "Failed to create uploads folder!";
                }
              }

              if (empty($php_error)) {
                echo '<div class="alert alert-success">PHP '.$php_version.' - OK!</div>';
              } else {
                echo '<div class="alert alert-danger">'.$php_error.'</div>';
              }
              if (empty($session_error)) {
                echo '<div class="alert alert-success">Sessions - OK!</div>';
              } else {
                echo '<div class="alert alert-danger">'.$session_error.'</div>';
              }
              if (empty($session_error)) {
                echo '<div class="alert alert-success">Upload Folder - OK!</div>';
              } else {
                echo '<div class="alert alert-danger">'.$folder_error.'</div>';
              }
              ?>
              <div class="text-center">
                <button class="btn btn-round <?php if ($error) echo 'btn-disabled'; else echo 'btn-success'; ?>" onclick="nextStep()">NEXT</button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 ml-auto mr-auto" id="admin-setup" style="display:none">
          <div class="card card-login">
            <div class="card-header card-header-success text-center">
                <h4 class="card-title">ADMINISTRATOR</h4>
            </div>
            <form class="form" role="form" action="install.php" method="POST" id="form-admin-setup">
              <div class="card-body">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                    </span>
                  </div>
                  <input id="id" type="text" class="form-control" name="id" pattern="[0-9]+" minlength="4" maxlength="9" placeholder="ID Number" required autofocus>
                </div>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                    </span>
                  </div>
                  <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="mt-5 text-center">
                  <button class="btn btn-round btn-success ml-3" name="action" value="admin" type="submit" id="btn-admin">CREATE</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      <?php } ?>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    function nextStep() {
      document.getElementById("error-checker").style.display = "none";
      document.getElementById("admin-setup").style.display = "block";
    }
  </script>
</body>
</html>