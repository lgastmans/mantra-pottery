<?php
 	require_once("db_mysqli.php");
 	require_once("functions.inc.php");

 	$data = array();
 	$error = '';

	$row_id = 0;

	if ((isset($_POST['stock_registry_id'])) && ($_POST['stock_registry_id'] != '__NEW_')) {

		/*
			edit
		*/
		$row_id = $_POST['stock_registry_id'];


		$date = date("Y-m-d H:i:s", strtotime($_POST['registry_date']." ".date('H:i:s')));

	    $sql = "
	    	UPDATE stock_registry
	    	SET 
	    		quantity = '".mysqli_real_escape_string($conn, $_POST['registry_quantity'])."',
	    		`date` = '".mysqli_real_escape_string($conn, $date)."',
	    		comment = '".mysqli_real_escape_string($conn, $_POST['registry_comment'])."'
	    	WHERE stock_registry_id = $row_id";

	    $qry = $conn->Query($sql);

		if (!$qry) {

			$data['error'] = true;
			$data['msg'] = $conn->error; //$sql;

		}
		else {

			$data['error'] = false;
			$data['msg'] = '';
			$data['data']['quantity'] = $_POST['registry_quantity'];
			$data['data']['date'] = set_formatted_date($_POST['registry_date']);
			$data['data']['comment'] = $_POST['registry_comment'];

		} // if (!qry)

	}
	else {
		/*
			insert

			checkdate(month, day, year)

		*/
		
		if (!is_numeric($_POST['registry_quantity'])) {

			$data['error'] = true;
			$data['msg'] = "The quantity field is not numeric";

			die(json_encode($data));
		}
		elseif (!isset($_POST['registry_date'])) {

			$data['error'] = true;
			$data['msg'] = "The date is not set";

			die(json_encode($data));
		}
		elseif (isset($_POST['registry_date'])) {

			$date_arr = explode("-",$_POST['registry_date']);

			if (count($date_arr) > 0) {

				if ((!is_numeric($date_arr[0])) || (!is_numeric($date_arr[1])) || (!is_numeric($date_arr[2]))) {
					$data['error'] = true;
					$data['msg'] = "The date is invalid (not numeric)";

					die(json_encode($data));
				}
				elseif (!checkdate($date_arr[1], $date_arr[2], $date_arr[0])) {

					$data['error'] = true;
					$data['msg'] = "The date is invalid (invalid numbers) ";

					die(json_encode($data));
				}
			}
			else {
				$data['error'] = true;
				$data['msg'] = "The date is invalid ";
			}
		}

		$date = date("Y-m-d H:i:s", strtotime($_POST['registry_date']." ".date('H:i:s'))); 

		$sql = "
			INSERT INTO stock_registry
			(
				stock_id,
				quantity,
				`date`,
				comment
			)
			VALUES (
				'".mysqli_real_escape_string($conn, $_POST['stock_id'])."',
				'".mysqli_real_escape_string($conn, $_POST['registry_quantity'])."',
				'".mysqli_real_escape_string($conn, $date)."',
				'".mysqli_real_escape_string($conn, $_POST['registry_comment'])."'
			)
		";

		$qry = $conn->Query($sql);

		if (!$qry) {
			$data['error'] = true;
			$data['msg'] = $conn->error; //.":".$sql;
		}
		else {
			$data['error'] = false;
			$data['msg'] = '';
			$data['data']['quantity'] = $_POST['registry_quantity'];
			$data['data']['date'] = set_formatted_date($_POST['registry_date']);
			$data['data']['comment'] = $_POST['registry_comment'];

		}

	}


	echo json_encode($data);

?>