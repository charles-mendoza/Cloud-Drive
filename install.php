<?php

$str = file_get_contents(__DIR__.'/includes/config.php');
$dbDone = strpos($str, "{db_server}") == false;

if (isset($_POST['action'])) {
  switch ($_POST['action']) {
    case 'database':
      $mysqli = new mysqli($_POST['db_server'], $_POST['db_username'], $_POST['db_password'], $_POST['db_name']);
      if (!$mysqli->connect_error) {

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

        $str = file_get_contents(__DIR__.'/includes/config.php');
        $str = str_replace('if (DB_SERVER == "{db_server}") { header("location: install"); exit; }', '', $str);
        $str = str_replace("{db_server}", $_POST['db_server'], $str);
        $str = str_replace("{db_username}", $_POST['db_username'], $str);
        $str = str_replace("{db_password}", $_POST['db_password'], $str);
        $str = str_replace("{db_name}", $_POST['db_name'], $str);
        $fp = fopen('includes/config.php', 'wb');
        fwrite($fp, $str);
        fclose($fp);

        $dbDone = true;
      }
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
                  <button class="btn btn-round btn-success ml-3" name="action" value="database" type="submit" id="btn-db">SAVE</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      <?php } else { ?>
        <div class="col-md-4 ml-auto mr-auto" id="admin-setup">
          <div class="card card-login">
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
                  <button class="btn btn-round btn-success ml-3" name="action" value="admin" type="submit" id="btn-admin">Create Admin</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      <?php } ?>
      </div>
    </div>
  </div>
</body>
</html>