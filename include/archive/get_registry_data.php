<?php
 	require_once("db_mysqli.php");

 	$data = array();
 	$error = '';

	$row_id = 0;
	if (isset($_POST['row_id']))
		$row_id = $_POST['row_id'];

    $sql = "
    	SELECT s.stock_id,
    		CONCAT(p.code, ' - ', g.code, ' - ', d.code) AS code,
			CONCAT(p.description, ' ', g.description, ' ', d.description) AS description
    	FROM stock s
		LEFT JOIN product p ON (p.product_id = s.product_id)
		LEFT JOIN glaze g ON (g.glaze_id = s.glaze_id)
		LEFT JOIN design d ON (d.design_id = s.design_id)
    	WHERE s.stock_id = $row_id";

    $qry = $conn->Query($sql);

	if (!$qry) {

		$error = $conn->error."\n".$sql;

	}
	else {

		if ($qry->num_rows > 0) {

			$obj = $qry->fetch_object();

			$data['stock_id'] = $obj->stock_id;
			$data['code'] = $obj->code;
			$data['description'] = htmlentities($obj->description);

		} // if num_rows

	} // if (!qry)

	echo json_encode($data);

?>