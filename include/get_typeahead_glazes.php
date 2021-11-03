<?php
 	require_once("db_mysqli.php");

 	$data = array();
 	$error = '';

    $sql = "
    	SELECT *
    	FROM glaze g
    	ORDER BY description";
    $qry = $conn->Query($sql);

	if (!$qry) {

		$error = $conn->error."\n".$sql;

	}
	else {

		if ($qry->num_rows > 0) {

			$i=0;
			while( $obj = $qry->fetch_object() ) {

				$data[$i]['glaze_id'] = $obj->glaze_id;
				$data[$i]['code'] = $obj->code;
				$data[$i]['description'] = $obj->description;

				$i++;

			} // while

		} // if num_rows

	} // if (!qry)

	echo json_encode($data);

?>