<?php
 	require_once("db_mysqli.php");

 	$data = array();
 	$error = '';

	$row_id = 0;

	if (isset($_POST['design_id'])) {

		/*
			delete
		*/
		$row_id = $_POST['design_id'];

		$sql = "
			SELECT design_id
			FROM stock
			WHERE design_id = $row_id
		";

		$qry = $conn->Query($sql);

		if ($qry->num_rows > 0) {

			$data['error'] = true;
			$data['msg'] = 'Cannot delete: this design is present in stock';

		}		
		else {

		    $sql = "
		    	DELETE FROM design
		    	WHERE design_id = $row_id";
		    $qry = $conn->Query($sql);

			if (!$qry) {

				$data['error'] = true;
				$data['msg'] = addslashes($conn->error); //$sql;

			}
			else {

				$data['error'] = false;
				$data['msg'] = '';

			} // if (!qry)
		}
	}

	echo json_encode($data);

?>