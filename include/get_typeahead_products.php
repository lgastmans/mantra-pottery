<?php
 	require_once("db_mysqli.php");

 	$data = array();
 	$error = '';

    $sql = "
    	SELECT *
    	FROM product p
    	ORDER BY description";
    $qry = $conn->Query($sql);

	if (!$qry) {

		$error = $conn->error."\n".$sql;

	}
	else {

		if ($qry->num_rows > 0) {

			$i=0;
			while( $obj = $qry->fetch_object() ) {

				$data[$i]['product_id'] = $obj->product_id;
				$data[$i]['code'] = $obj->code;
				$data[$i]['description'] = $obj->description;
				$data[$i]['height'] = $obj->height;
				$data[$i]['width'] = $obj->width;
				$data[$i]['weight'] = $obj->weight;
				$data[$i]['volume'] = $obj->volume;

				$i++;

			} // while

		} // if num_rows

	} // if (!qry)

	echo json_encode($data);

?>