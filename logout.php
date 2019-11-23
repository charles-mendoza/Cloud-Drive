<?php 

session_start();

if (isset($_SESSION['logged_in'])) {
	session_destroy();
	session_unset();
}

header("location: .");

?>