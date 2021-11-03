<?php
 	require_once("db_mysqli.php");
 	require_once("glaze.inc.php");


	$row_id = 0;
	if (isset($_POST['row_id']))
		$row_id = $_POST['row_id'];


	$glaze = new glaze();
	$glaze->conn = $conn;

	$glaze->glaze_id = $row_id;

	$glaze->get();


	$data['glaze_id'] = $glaze->glaze_id;
	$data['code'] = $glaze->code;
	$data['description'] = $glaze->description;


	echo json_encode($data);

?>