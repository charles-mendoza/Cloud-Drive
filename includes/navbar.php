<nav class="navbar navbar-transparent navbar-color-on-scroll <?php if (!isset($_SESSION['logged_in'])) echo "fixed-top" ?> navbar-expand-lg" color-on-scroll="100" id="sectionsNav">
  <div class="container">
    <div class="navbar-translate">
      <a class="navbar-brand" href="index.php"><b><i class="fa fa-cloud"></i> DRIVE</b></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="sr-only">Toggle navigation</span>
        <span class="navbar-toggler-icon"></span>
        <span class="navbar-toggler-icon"></span>
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ml-auto">
        <?php if (basename($_SERVER['PHP_SELF']) == 'trash.php') { ?>
        <li class="nav-item">
          <button class="btn btn-disabled" type="button" id="btnEmptyTrash">Empty Trash</button>
        </li>
        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
        <?php } ?>
        <li class="nav-item">
          <a class="nav-link" rel="tooltip" title="" data-placement="bottom" href="index.php" data-original-title="Home">
            <i class="material-icons">home</i>
          </a>
        </li>
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']['usergroup'] == 1) { ?>
        <li class="nav-item">
          <form class="form-upload" id="form-upload" method="post" action="action.php" enctype="multipart/form-data">
            <label class="m-0 p-0" for="file-input">
              <a class="nav-link" rel="tooltip" title="" data-placement="bottom" data-original-title="Upload">
                <i class="material-icons">cloud_upload_alt</i>
              </a>
            </label>
            <input type="text" name="action" value="upload">
            <input id="file-input" type="file" name="files[]" multiple="multiple"/>
          </form>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="trash" rel="tooltip" title="" data-placement="bottom" href="trash.php" data-original-title="Trash">
            <i class="fa fa-trash"></i>
          </a>
        </li>
        <?php } if (isset($_SESSION['logged_in'])) { ?>
        <li class="nav-item">
          <a class="nav-link" rel="tooltip" title="" data-placement="bottom" href="logout.php" data-original-title="Logout">
            <i class="fa fa-sign-out"></i>
          </a>
        </li>
        <?php } else { ?>
        <li class="nav-item">
          <a class="nav-link" rel="tooltip" title="" data-placement="bottom" href="login.php" data-original-title="Login">
            <i class="fa fa-sign-in"></i>
          </a>
        </li>
        <?php } if (!isset($_SESSION['logged_in'])) { ?>
        <li class="nav-item">
          <a class="nav-link" rel="tooltip" title="" data-placement="bottom" href="signup.php" data-original-title="Sign Up">
            <i class="material-icons">account_box</i>
          </a>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>