<?php

// upload directory
define('UPLOAD_DIR', 'uploads/');

// max file name length in explorer
define('FILENAME_MAX', '13');

// database credentials
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'cdrive_db');

$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
	die("ERROR: ".$mysqli->error);
}

if (session_id() == '') {
	session_start();
}

?>