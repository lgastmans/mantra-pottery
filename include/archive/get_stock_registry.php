<?php

	header('Content-Type: application/json; charset=utf-8');

 	require_once("db_mysqli.php");
 	require_once("functions.inc.php");

 	$rows = array();
	$recordsTotal = 0;
	$recordsFiltered = 0;
	$error = '';


	$row_id = 0;
	if (isset($_GET['row_id']))
		$row_id = $_GET['row_id'];


	$draw = 1;
	if (isset($_GET['draw']))
		$draw = (int) ($_GET['draw']);

	$start = 0;
	if (IsSet($_GET['start']))
		$start = $_GET['start'];

	$length = 50;
	if (isset($_GET['length']))
		$length = $_GET['length'];
	if ($length>=0)
		$sql_limit = "LIMIT $start, $length";
	else
		$sql_limit = "";

	$order_column = 0;
	$order_dir = 'ASC';
	if (isset($_GET['order'])) {
		$order_column = $_GET['order'][0]['column'];
		$order_dir = $_GET['order'][0]['dir'];
	}
	if ($order_column==0)
		$order_column = 'date';

	$order_dir = ($order_dir=='asc')?"ASC":"DESC";

	$sql_order = "ORDER BY `$order_column` $order_dir";


	$search=false;
	$sql_where=" WHERE sr.stock_id = $row_id";
	$sql_join = "";

/*
	if (isset($_GET['search'])) {

		$search = $_GET['search']['value'];

		if ((!empty($search)) && (strlen($search)>1)) {

			$sql_where = "
				WHERE ( (p.code LIKE '%$search%') OR (p.description LIKE '%$search%') )
			";

		}
	}
*/

	// get total number of records
	$sql_total = "SELECT sr.stock_registry_id FROM stock_registry sr $sql_join $sql_where";
	$qry = $conn->Query($sql_total);
	if ($qry) {
		$recordsTotal = $qry->num_rows;
		$recordsFiltered = $qry->num_rows;
	}
	else {
		$error = $conn->error."\n".$sql;
	}

	$sql = "
		SELECT sr.*
		FROM stock_registry sr
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

				$rows[$i]['DT_RowId'] = $obj->stock_registry_id;
				$rows[$i]['quantity'] = $obj->quantity;
				$rows[$i]['date'] = set_formatted_date($obj->date);
				$rows[$i]['comment'] = htmlentities($obj->comment);

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