<?php
 	require_once("db_mysqli.php");

 	$data = array();
 	$error = '';

    $sql = "
		SELECT s.stock_id,
			CONCAT_WS('-', p.code, g.code, d.code) AS code,
			CONCAT(p.description, ' ', g.description, ' ', d.description) AS description
    	FROM stock s
		LEFT JOIN product p ON (p.product_id = s.product_id)
		LEFT JOIN glaze g ON (g.glaze_id = s.glaze_id)
		LEFT JOIN design d ON (d.design_id = s.design_id)
    	ORDER BY description
    ";
    $qry = $conn->Query($sql);

	if (!$qry) {

		$error = $conn->error."\n".$sql;

	}
	else {

		if ($qry->num_rows > 0) {

			$i=0;
			while( $obj = $qry->fetch_object() ) {

				$data[$i]['stock_id'] = $obj->stock_id;
				$data[$i]['code'] = $obj->code;
				$data[$i]['description'] = $obj->description;

				$i++;

			} // while

		} // if num_rows

	} // if (!qry)

	echo json_encode($data);

?>