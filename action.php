<?php

include('includes/config.php');

if (!isset($_POST['action'])) {
	header('location: error/403.html');
	exit;
}

switch ($_POST['action']) {

case 'login':

	// escape user inputs for security
	$id = mysqli_real_escape_string($mysqli, $_POST['id']);
	$password = mysqli_real_escape_string($mysqli, $_POST['password']);

	$hQuery = $mysqli->query("SELECT * FROM user WHERE id='$id'");
	if (!$hQuery) {
		die("ERROR: ".$mysqli->error);
	}

	if ($hQuery->num_rows > 0) {
		$row = $hQuery->fetch_assoc();

		$password = md5(md5($password).$row['salt']);
		if ($password == $row['password']) {
			$_SESSION['logged_in'] = $row;
			header("location: index.php");
		} else {
			header("location: login.php?error=invalid-user");
		}
	} else {
		header("location: login.php?error=invalid-user");
	}

	break;

case 'signup':

	// escape user inputs for security
	$id = mysqli_real_escape_string($mysqli, $_POST['id']);
	$first_name = mysqli_real_escape_string($mysqli, $_POST['first_name']);
	$last_name = mysqli_real_escape_string($mysqli, $_POST['last_name']);
	$password = mysqli_real_escape_string($mysqli, $_POST['password']);

	if ($password != $_POST['password_confirm']) {
		header("location: signup.php?error=password");
		exit;
	}

	$hQuery = $mysqli->query("SELECT * FROM user WHERE id='$id'");
	if (!$hQuery) {
		die("ERROR: ".$mysqli->error);
	}

	if ($hQuery->num_rows == 0) {
		$salt = uniqid(mt_rand(), true);
		$password = md5(md5($password).$salt);
		$hQuery = $mysqli->query("INSERT INTO user VALUES('$id', '$first_name', '$last_name', '$password', '$salt', '2')");
		if (!$hQuery) {
			die("ERROR: ".$mysqli->error);
		}
		header("location: login.php?message=signup-success");
	} else {
		header("location: signup.php?error=user-exists");
	}

	break;

}

// if not logged in and user level is not admin, throw error 401 (unauthorized)
if (!isset($_SESSION['logged_in']) && $_SESSION['logged_in']['usergroup'] != 1) {
	header('location: error/401.html');
	exit;
}

// file action handler
switch ($_POST['action']) {

case 'upload':
	
	// upload multiple files
	foreach($_FILES['files']['tmp_name'] as $key=>$tmp_name) {

		$temp = $_FILES['files']['tmp_name'][$key];
		$file = $_FILES['files']['name'][$key];
		$name = pathinfo($file, PATHINFO_FILENAME);
		$ext = '.'.pathinfo($file, PATHINFO_EXTENSION);
		
		if(empty($temp))
			break;

		// handle duplicate file name
		if (file_exists(UPLOAD_DIR.$file)) {
			$i = 1;
			while (file_exists(UPLOAD_DIR.$name.' ('.$i.')'.$ext)) {
				$i++;
			}
			$name = $name.' ('.$i.')';
		}
		$file = $name.$ext;

		// insert into db
		$hQuery = $mysqli->query("INSERT INTO file (name, extension) VALUES('$name', '$ext')");
		if (!$hQuery) {
			die("ERROR: ".$mysqli->error);
		}

		move_uploaded_file($temp, UPLOAD_DIR.$file);
	}

	break;

case 'rename':
	
	$file = $_POST['file'];
	$name = $_POST['new_name'];

	$hQuery = $mysqli->query("SELECT * FROM file WHERE id='$file'");
	if (!$hQuery) {
		die("ERROR: ".$mysqli->error);
	}
	$row = $hQuery->fetch_assoc();

	if (empty($name)) {
		echo $row['name'];
		exit;
	}

	if ($name != $row['name']) {
		// handle duplicate file name
		if (file_exists(UPLOAD_DIR.$name.$row['extension'])) {
			$i = 1;
			while (file_exists(UPLOAD_DIR.$name.' ('.$i.')'.$row['extension'])) {
				$i++;
			}
			$name = $name.' ('.$i.')';
		}

		// rename file in db
		$hQuery = $mysqli->query("UPDATE file SET name='$name' WHERE id='$file'");
		if (!$hQuery) {
			die("ERROR: ".$mysqli->error);
		}

		// rename physical file
		rename(UPLOAD_DIR.$row['name'].$row['extension'], UPLOAD_DIR.$name.$row['extension']);
	}

	// return renamed file
	echo $name;

	break;

case 'restore':
	
	$file = $_POST['file'];

	$hQuery = $mysqli->query("UPDATE file SET in_trash=0 WHERE id='$file'");
	if (!$hQuery) {
		die("ERROR: ".$mysqli->error);
	}

	break;

case 'delete':
	
	$file = $_POST['file'];

	$hQuery = $mysqli->query("SELECT * FROM file WHERE id='$file'");
	if (!$hQuery) {
		die("ERROR: ".$mysqli->error);
	}
	$row = $hQuery->fetch_assoc();

	if ($row['in_trash'] == 1)
	{
		// delete file from db
		$hQuery = $mysqli->query("DELETE FROM file WHERE id='$file'");
		if (!$hQuery) {
			die("ERROR: ".$mysqli->error);
		}

		// delete physical file
		unlink(UPLOAD_DIR.$row['name'].$row['extension']) or die("ERROR: Couldn't delete file.");

	} else {

		$hQuery = $mysqli->query("UPDATE file SET in_trash=1 WHERE id='$file'");
		if (!$hQuery) {
			die("ERROR: ".$mysqli->error);
		}
	}

	break;

case 'empty_trash':
	
	// find all files in trash
	$hQuery = $mysqli->query("SELECT * FROM file WHERE in_trash=1");
	if (!$hQuery) {
		die("ERROR: ".$mysqli->error);
	}

	// delete all files in trash
	while ($row = $hQuery->fetch_assoc()) {
		unlink(UPLOAD_DIR.$row['name'].$row['extension']) or die("ERROR: Couldn't delete file.");
	}

	// delete all trash files in db
	$hQuery = $mysqli->query("DELETE FROM file WHERE in_trash=1");
	if (!$hQuery) {
		die("ERROR: ".$mysqli->error);
	}

	header("location: trash.php");
	exit;
}

?>