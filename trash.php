<?php
include('includes/config.php');

// if not admin, throw error 401 (unauthorized)
if ($_SESSION['logged_in']['usergroup'] != 1) {
  header('location: error/401.html');
  exit;
}

include('includes/header.php');

$hQuery = $mysqli->query("SELECT * FROM file WHERE in_trash=1");
if ($mysqli->connect_error) {
  die("ERROR: ".$mysqli->error);
}

if ($hQuery->num_rows > 0) {

  echo '<div class="container overflow-auto" style="min-height:74vh">';
  echo '<div class="row ml-auto mr-auto">';

  // list all files in trash
  while ($row = $hQuery->fetch_assoc()) {
    echo '<div class="file-col" id="file-'.$row['id'].'-col">';
    echo '<i class="fa fa-file-text" id="file-'.$row['id'].'"></i>';
    $file = $row['name'].$row['extension'];
    $file = strlen($file) > FILENAME_MAX ? substr($file,0,FILENAME_MAX-3).'...' : $file;
    echo '<p id="file-'.$row['id'].'">'.$file.'</p>';
    echo '<input class="d-none" type="text" value="'.$row['name'].'" id="file-'.$row['id'].'-name">';
    echo '<input class="d-none" type="text" value="'.$row['extension'].'" id="file-'.$row['id'].'-ext">';
    echo '</div>';
  }
  echo '</div></div>';
  ?>
  <ul id="context-menu" class="dropdown-menu" role="menu" style="display:none">
    <li><a href="#">Restore</a></li>
    <li><a href="#">Delete</a></li>
  </ul>
  <form class="d-none" id="file-action-form" method="POST" action="action.php">
    <input type="text" name="action" id="file-action">
    <input type="text" name="file" id="file-id">
    <input type="text" name="new_name" id="file-rename">
  </form>
  <?php
} else {
  echo '<div class="page-header"><div class="container text-center"><h2 class="text-muted">Trash is Empty</h2></div></div>';
}
include('includes/footer.php'); ?>