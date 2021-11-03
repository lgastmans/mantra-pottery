<?php
 	require_once("db_mysqli.php");

 	$data = array();
 	$error = '';

	$row_id = 0;

	if ((isset($_POST['design_id'])) && ($_POST['design_id'] != '__NEW_')) {

		/*
			edit
		*/
		$row_id = $_POST['design_id'];

	    $sql = "
	    	UPDATE design
	    	SET 
	    		code = '".mysqli_real_escape_string($conn, $_POST['design_code'])."',
				description = '".mysqli_real_escape_string($conn, $_POST['design_description'])."'
	    	WHERE design_id = $row_id";
	    $qry = $conn->Query($sql);

		if (!$qry) {

			$data['error'] = true;
			$data['msg'] = $conn->error; //$sql;

		}
		else {

			$data['error'] = false;
			$data['msg'] = '';

		} // if (!qry)

	}
	else {
		/*
			insert
		*/

		$sql = "
			INSERT INTO design
			(
				code,
				description
			)
			VALUES (
				'".mysqli_real_escape_string($conn, $_POST['design_code'])."',
				'".mysqli_real_escape_string($conn, $_POST['design_description'])."'
			)
		";

		$qry = $conn->Query($sql);

		if (!$qry) {
			$data['error'] = true;
			$data['msg'] = $conn->error; //.":".$sql;
		}
		else {
			$data['error'] = false;
			$data['msg'] = 'Inserted: '.$conn->insert_id;
		}

	}


	echo json_encode($data);

?>