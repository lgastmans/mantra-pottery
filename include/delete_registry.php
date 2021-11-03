<?php
 	require_once("db_mysqli.php");
 	require_once("functions.inc.php");

 	$data = array();
 	$error = '';

	$row_id = 0;

	if (isset($_POST['stock_registry_id'])) {

		/*
			delete
		*/
		$row_id = $_POST['stock_registry_id'];

		$sql = "
			SELECT stock_id
			FROM stock_registry
			WHERE stock_registry_id = $row_id
		";

	    $qry = $conn->Query($sql);

	    if (!$qry) {

			$data['error'] = true;
			$data['msg'] = addslashes($conn->error); //$sql;

	    }
	    else {

	    	if ($qry->num_rows > 0) {

	    		$obj = $qry->fetch_object();

	    		$cur_stock_id = $obj->stock_id;

			    $sql = "
			    	DELETE FROM stock_registry
			    	WHERE stock_registry_id = $row_id";

			    $qry = $conn->Query($sql);

				if (!$qry) {

					$data['error'] = true;
					$data['msg'] = addslashes($conn->error); //$sql;

				}
				else {
					$sql = "
		                SELECT sr.quantity, sr.date, sr.comment
		                FROM stock_registry sr
		                WHERE stock_id = $cur_stock_id
		                ORDER BY sr.`date` DESC
		                LIMIT 1
		            ";
				    $qry = $conn->Query($sql);

				    if ($qry->num_rows > 0) {
				    	$obj = $qry->fetch_object();
						$data['data']['quantity'] = $obj->quantity;
						$data['data']['date'] = set_formatted_date($obj->date);
						$data['data']['comment'] = $obj->comment;
				    }
				    else {
						$data['data']['quantity'] = 0;
						$data['data']['date'] = 'n/a';
						$data['data']['comment'] = 'n/a';

				    }


					$data['error'] = false;
					$data['msg'] = 'deleted successfully';

				}
			}
			else {

				$data['error'] = true;
				$data['msg'] = "Registry entry not found";

			}
		}
	}

	echo json_encode($data);

?>