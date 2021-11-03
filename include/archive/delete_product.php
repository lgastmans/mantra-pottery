<?php
 	require_once("db_mysqli.php");

 	$data = array();
 	$error = '';

	$row_id = 0;

	if (isset($_POST['product_id'])) {

		/*
			delete
		*/
		$row_id = $_POST['product_id'];

		$sql = "
			SELECT product_id
			FROM stock
			WHERE product_id = $row_id
		";

		$qry = $conn->Query($sql);

		if ($qry->num_rows > 0) {

			$data['error'] = true;
			$data['msg'] = 'Cannot delete: this product is present in stock';

		}		
		else {

		    $sql = "
		    	DELETE FROM product
		    	WHERE product_id = $row_id";
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