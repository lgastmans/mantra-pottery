<?php
 	require_once("db_mysqli.php");

 	$data = array();
 	$error = '';

	$row_id = 0;
	if (isset($_POST['row_id']))
		$row_id = $_POST['row_id'];

    $sql = "
    	SELECT s.*, 
    		p.height, p.width, p.weight, p.volume,
    		p.description AS product_description, 
    		g.description AS glaze_description, 
    		d.description AS design_description
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
			$data['product_id'] = $obj->product_id;
			$data['product_description'] = $obj->product_description;
			$data['glaze_id'] = $obj->glaze_id;
			$data['glaze_description'] = $obj->glaze_description;
			$data['design_id'] = $obj->design_id;
			$data['design_description'] = $obj->design_description;
			$data['height'] = $obj->height;
			$data['width'] = $obj->width;
			$data['weight'] = $obj->weight;
			$data['volume'] = $obj->volume;
			
			// $data['code'] = $obj->code;
			// $data['description'] = $obj->description;

		} // if num_rows

	} // if (!qry)

	echo json_encode($data);

?>