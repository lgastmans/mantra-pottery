<?php
 	require_once("db_mysqli.php");
 	require_once("session.php");

 	$data = array();
 	$error = '';

    $sql = "
    	SELECT *
    	FROM source s
    	WHERE type_id = ".$_SESSION['movement_type']."
    	ORDER BY name";
    $qry = $conn->Query($sql);

	if (!$qry) {

		$error = $conn->error."\n".$sql;

	}
	else {

		if ($qry->num_rows > 0) {

			$i=0;
			while( $obj = $qry->fetch_object() ) {

				$data[$i]['source_id'] = $obj->source_id;
				$data[$i]['name'] = $obj->name;
				$data[$i]['address'] = $obj->address;

				$i++;

			} // while

		} // if num_rows

	} // if (!qry)

	echo json_encode($data);

?>