<?php
 	require_once("db_mysqli.php");
 	require_once("glaze.inc.php");

 	$data = array();
 	$error = '';

	$row_id = 0;

	$glaze = new glaze();
	$glaze->conn = $conn;


	if ((isset($_POST['glaze_id'])) && ($_POST['glaze_id'] != '__NEW_')) {
		/*
			edit
		*/
		$row_id = $_POST['glaze_id'];
 	
	 	$glaze->glaze_id = $row_id;
	 	$glaze->code = $_POST['glaze_code'];
	 	$glaze->description = $_POST['glaze_description'];

	 	$glaze->update();

		$data['error'] = $glaze->data['error'];
		$data['msg'] = $glaze->data['msg'];

	}
	else {
		/*
			insert
		*/

	 	$glaze->code = $_POST['glaze_code'];
	 	$glaze->description = $_POST['glaze_description'];

		$glaze->insert();

		$data['error'] = $glaze->data['error'];
		$data['msg'] = $glaze->data['msg'];

	}


	echo json_encode($data);

?>