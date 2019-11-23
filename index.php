<?php
include('includes/config.php');
include('includes/header.php'); ?>
<div class="page-header" id="page-header">
<div class="container text-center"><?php

$loggedIn = isset($_SESSION['logged_in']);

if ($loggedIn) {

  echo '<h2 class="text-muted" id="no-files" style="display:none">No Files</h2>';

  $hQuery = $mysqli->query("SELECT * FROM file WHERE in_trash=0");
  if ($mysqli->connect_error) {
    die("ERROR: ".$mysqli->error);
  }

  if ($hQuery->num_rows > 0) {
    // list all files not in trash
    echo '<div class="row ml-auto mr-auto">';
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
    echo '</div>';
  }
} else {
  echo '<h2><a class="text-muted" href="login">Login to View Files</a></h2>';
} ?>
</div>
</div>
<?php if ($loggedIn) { ?>
  <ul id="context-menu" class="dropdown-menu" role="menu" style="display:none">
    <li><a id="download">Download</a></li>
    <?php
    if ($loggedIn && $_SESSION['logged_in']['usergroup'] == 1) {
      echo '<li><a data-toggle="modal" data-target="#modal-rename">Rename</a></li>';
      echo '<li><a>Delete</a></li>';
    }
    ?>
  </ul>
  <form class="d-none" id="file-action-form" method="POST" action="action.php">
    <input type="text" name="action" id="file-action">
    <input type="text" name="file" id="file-id">
    <input type="text" name="new_name" id="file-rename">
  </form>
  <div class="modal fade" id="modal-rename">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="text" class="d-none" id="rename-id">
          <input type="text" class="form-control" id="newName" autofocus>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" id="btnRename">Rename</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modal-upload">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" type="button" data-dismiss="modal" aria-label="Close">Close</button>
        </div>
      </div>
    </div>
  </div>
<?php } include('includes/footer.php'); ?>