<?php
	header('Content-Type: application/json; charset=utf-8');

 	require_once("db_mysqli.php");


 	$rows = array();
	$recordsTotal = 0;
	$recordsFiltered = 0;
	$error = '';

	$draw = 1;
	if (isset($_GET['draw']))
		$draw = (int) ($_GET['draw']);

	$start = 0;
	if (IsSet($_GET['start']))
		$start = $_GET['start'];

	$length = 50;
	if (isset($_GET['length']))
		$length = $_GET['length'];
	$sql_limit = "LIMIT $start, $length";	

	$order_column = 0;
	$order_dir = 'ASC';
	if (isset($_GET['order'])) {
		$order_column = $_GET['order'][0]['column'];
		$order_dir = $_GET['order'][0]['dir'];
	}
	if ($order_column==0)
		$order_column = 'code';
	elseif ($order_column==1)
		$order_column = 'description';

	$order_dir = ($order_dir=='asc')?"ASC":"DESC";

	$sql_order = "ORDER BY $order_column $order_dir";


	$search=false;
	$sql_where='';
	$sql_join = "";


	if (isset($_GET['search'])) {

		$search = $_GET['search']['value'];

		if ((!empty($search)) && (strlen($search)>1)) {

			$sql_where = "
				WHERE ( (p.code LIKE '%$search%') OR (p.description LIKE '%$search%') )
			";

		}
	}


	// get total number of records
	$sql_total = "SELECT p.product_id FROM product p $sql_join $sql_where";
	$qry = $conn->Query($sql_total);
	if ($qry) {
		$recordsTotal = $qry->num_rows;
		$recordsFiltered = $qry->num_rows;
	}
	else {
		$error = $conn->error."\n".$sql;
	}

	$sql = "
		SELECT p.*
		FROM product p
		$sql_join 
		$sql_where
		$sql_order
		$sql_limit
	";

	$qry = $conn->Query($sql);
	if (!$qry) {
		$error = $conn->error."\n".$sql;
	}
	else {

		if ($qry->num_rows > 0) {

			$i=0;
			while( $obj = $qry->fetch_object() ) {

				$rows[$i]['DT_RowId'] = $obj->product_id;
				$rows[$i]['code'] = $obj->code;
				$rows[$i]['description'] = htmlentities($obj->description);

				$i++;
			}
		}
	}

	$data = array(
		'draw'				=> $draw,
		'recordsTotal'		=> $recordsTotal,
		'recordsFiltered'	=> $recordsFiltered,
		'data'				=> $rows,
		'error'				=> $error
	);
	
	
	echo json_encode($data);

?>