<?php
include("../connection.php");

$mode = isset($_POST['mode']) ? $_POST['mode'] : null;

if ($mode == 'qualification') {
	$cou_name = isset($_POST['cou_name']) ? $_POST['cou_name'] : null;

	if (empty($cou_name)) {
		echo "Email or password is empty";
		exit;
	}

	$sql = "INSERT INTO course_tbl (cou_name)
    VALUES ('$cou_name')" or die(mysqli_error($myConn));
    if (mysqli_query($myConn, $sql)) { 
    	echo 1;
    } else {
	    echo "Error: " . $sql . ":-" . mysqli_error($myConn);
	}
	mysqli_close($myConn);
	
	exit;
}

echo "No operation specified";
exit;