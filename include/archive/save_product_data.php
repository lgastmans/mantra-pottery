<?php
 	require_once("db_mysqli.php");

 	$data = array();
 	$error = '';

	$row_id = 0;

	if ((isset($_POST['product_id'])) && ($_POST['product_id'] != '__NEW_')) {

		/*
			edit
		*/
		$row_id = $_POST['product_id'];

	    $sql = "
	    	UPDATE product
	    	SET 
	    		code = '".mysqli_real_escape_string($conn, $_POST['product_code'])."',
				description = '".mysqli_real_escape_string($conn, $_POST['product_description'])."'
	    	WHERE product_id = $row_id";
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
			INSERT INTO product
			(
				code,
				description
			)
			VALUES (
				'".mysqli_real_escape_string($conn, $_POST['product_code'])."',
				'".mysqli_real_escape_string($conn, $_POST['product_description'])."'
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