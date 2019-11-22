<?php include('includes/config.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Cloud Drive</title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="fonts/roboto.css" rel="stylesheet">
  <link href="fonts/robotoslab.css" rel="stylesheet">
  <link href="fonts/materialicons.css" rel="stylesheet">
  <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
  <!-- CSS Files -->
  <link href="css/material-kit.css?v=2.0.4'" rel="stylesheet" />
  <link href="css/app.css" rel="stylesheet" />
</head>
<body class="<?php if (!isset($_SESSION['logged_in'])) echo "login-page" ?> sidebar-collapse">
  <?php
  include('includes/navbar.php');
  if (isset($_SESSION['logged_in'])) {
    $COLMAX = 6;
    $nFiles = 40;
    $col = -1;
    if ($nFiles > 0) {
      echo '<div class="container overflow-auto" style="min-height:40em">';
      echo '<div class="row ml-auto mr-auto">';
      for ($i = 0; $i < $nFiles; $i++) {
          echo '<div class="file-col" id="file-'.$i.'-col">'; // id is from file id in database
          echo '<i class="fa fa-file-text" id="file-'.$i.'"></i>';
          $file = "konichiwa.txt";
          $file = strlen($file) > FILENAME_MAX ? substr($file,0,FILENAME_MAX-3).'...' : $file;
          echo '<p>'.$file.'</p>';
          echo '</div>';
          $col = $col < $COLMAX-1 ? $col+1 : 0;
      }

      $remaining = $COLMAX-($col+1);
      for ($i = 0; $i < $remaining; $i++) {
        echo '<div class="file-blank-col"></div>';
      }
      echo '</div></div>';
    } else {
      echo '<div class="page-header"><div class="container text-center"><h2 class="text-muted">No Files</h2></div></div>';
      ?>
      <script>
        document.body.classList.add("login-page");
        document.getElementById("sectionsNav").classList.add("fixed-top");
      </script>
      <?php
    }
  } else {
    echo '<div class="page-header"><div class="container text-center"><h2 class="text-muted">Login to Explore Files</h2></div></div>';
  }
  ?>
  <ul id="context-menu" class="dropdown-menu" role="menu" style="display:none" >
    <li><a href="#">Download</a></li>
    <li><a href="#">Rename</a></li>
    <li><a href="#">Delete</a></li>
  </ul>
  <form class="d-none" id="file-action-form">
    <input type="text" name="action" id="file-action">
    <input type="text" name="file" id="file-id">
    <input type="text" name="new_name" id="file-rename">
  </form>
  <?php include('includes/footer.php'); ?>
</body>
</html>