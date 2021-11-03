<?php
 	require_once("db_mysqli.php");

 	$data = array();
 	$error = '';

	$row_id = 0;

	if (isset($_POST['image_id'])) {

		/*
			delete
		*/
		$row_id = $_POST['image_id'];

		$sql = "
			SELECT *
			FROM stock_images
			WHERE id = $row_id
		";

		$qry = $conn->Query($sql);

		if ($qry->num_rows > 0) {

			$obj = $qry->fetch_object();

			if( unlink("../images/".$obj->filename)) {

			    $sql = "
			    	DELETE FROM stock_images
			    	WHERE id = $row_id";

			    $qry = $conn->Query($sql);

				if (!$qry) {

					$data['error'] = true;
					$data['msg'] = addslashes($conn->error); //$sql;

				}
				else {

					$data['error'] = false;
					$data['msg'] = 'image removed successfully';

				}

			}
			else {

				$data['error'] = true;
				$data['msg'] = 'Could not delete the image file '.$obj->filename;

			}

		}		
		else {

			$data['error'] = true;
			$data['msg'] = 'Cannot delete: image not found';

		}
	}

	echo json_encode($data);

?>