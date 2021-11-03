<?php
	require_once("db_mysqli.php");
	require_once("glaze.inc.php"); 	

 	$data = array();
 	$error = '';

	$row_id = 0;

	if (isset($_POST['glaze_id'])) {

		/*
			delete
		*/
		$row_id = $_POST['glaze_id'];

	
		$glaze = new glaze();
		$glaze->conn = $conn;

	 	$glaze->glaze_id = $row_id;

	 	$glaze->delete();

	}

	echo json_encode($data);

?>