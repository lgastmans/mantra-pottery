<?php
 	require_once("db_mysqli.php");

 	$data = array();
 	$error = '';

	$row_id = 0;

	if ((isset($_POST['stock_id'])) && ($_POST['stock_id'] != '__NEW_')) {

		/*
			edit
		*/
		$row_id = $_POST['stock_id'];

	    $sql = "
	    	UPDATE stock
	    	SET 
	    		product_id = '".mysqli_real_escape_string($conn, $_POST['product_id'])."',
	    		glaze_id = '".mysqli_real_escape_string($conn, $_POST['glaze_id'])."',
	    		design_id = '".mysqli_real_escape_string($conn, $_POST['design_id'])."',
	    		height = '".mysqli_real_escape_string($conn, $_POST['stock_height'])."',
	    		width = '".mysqli_real_escape_string($conn, $_POST['stock_width'])."',
	    		weight = '".mysqli_real_escape_string($conn, $_POST['stock_weight'])."',
	    		volume = '".mysqli_real_escape_string($conn, $_POST['stock_volume'])."'
	    	WHERE stock_id = $row_id";

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
			INSERT INTO stock
			(
				product_id,
				glaze_id,
				design_id,
				height,
				width,
				weight,
				volume
			)
			VALUES (
				'".mysqli_real_escape_string($conn, $_POST['product_id'])."',
				'".mysqli_real_escape_string($conn, $_POST['glaze_id'])."',
				'".mysqli_real_escape_string($conn, $_POST['design_id'])."',
				'".mysqli_real_escape_string($conn, $_POST['stock_height'])."',
				'".mysqli_real_escape_string($conn, $_POST['stock_width'])."',
				'".mysqli_real_escape_string($conn, $_POST['stock_weight'])."',
				'".mysqli_real_escape_string($conn, $_POST['stock_volume'])."'
			)
		";

		$qry = $conn->Query($sql);

		if (!$qry) {
			$data['error'] = true;
			$data['msg'] = $conn->error; //.":".$sql;
		}
		else {

			$stock_id = $conn->insert_id;

			$sql = "
				INSERT INTO stock_registry
				(
					stock_id,
					quantity,
					`date`,
					comment
				)
				VALUES (
					'".$stock_id."',
					'0',
					'".date('Y-m-j')."',
					'Stock product created'
				)
			";

			$qry = $conn->Query($sql);

			if (!$qry) {

				$data['error'] = true;
				$data['msg'] = $conn->error.":".$sql;

			}
			else {
				$data['error'] = false;
				$data['msg'] = '';
			}
		}

	}


	echo json_encode($data);

?>