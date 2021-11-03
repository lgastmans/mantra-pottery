<?php
 	require_once("db_mysqli.php");

 	$data = array();
 	$error = '';

	$row_id = 0;
	if (isset($_POST['row_id']))
		$row_id = $_POST['row_id'];

    $sql = "
    	SELECT *
    	FROM product
    	WHERE product_id = $row_id";
    $qry = $conn->Query($sql);

	if (!$qry) {

		$error = $conn->error."\n".$sql;

	}
	else {

		if ($qry->num_rows > 0) {

			$obj = $qry->fetch_object();

			$data['product_id'] = $obj->product_id;
			$data['code'] = $obj->code;
			$data['description'] = $obj->description;

		} // if num_rows

	} // if (!qry)

	echo json_encode($data);

?>