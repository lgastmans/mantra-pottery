<?php
 	require_once("db_mysqli.php");

 	$data = array();
 	$error = '';

	$row_id = 0;

	if (isset($_POST['stock_id'])) {

		/*
			delete
		*/
		$row_id = $_POST['stock_id'];

		$sql = "
			SELECT stock_id
			FROM stock
			WHERE stock_id = $row_id
		";

		$qry = $conn->Query($sql);

		if ($qry->num_rows > 0) {

		    $sql = "
		    	DELETE FROM stock_registry
		    	WHERE stock_id = $row_id";

		    $qry = $conn->Query($sql);

			if (!$qry) {

				$data['error'] = true;
				$data['msg'] = addslashes($conn->error); //$sql;

			}
			else {

			    $sql = "
			    	DELETE FROM stock
			    	WHERE stock_id = $row_id";
			    	
			    $qry = $conn->Query($sql);

				if (!$qry) {

					$data['error'] = true;
					$data['msg'] = addslashes($conn->error); //$sql;

				}
				else {

					$data['error'] = false;
					$data['msg'] = 'deleted successfully';

				} // if (!qry)
			}

		}
		else {

			$data['error'] = true;
			$data['msg'] = 'Cannot delete: stock entry not found.';

		}
	}

	echo json_encode($data);

?>