<?php
	header('Content-Type: application/json; charset=utf-8');

	require_once("db_mysqli.php");
	require_once("functions.inc.php");
	require_once("session.php");

	
	$sql = "SELECT * FROM settings LIMIT 1";
	$qry = $conn->Query($sql);
	$obj = $qry->fetch_object();
	$minimum_quantity = $obj->minimum_quantity;


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
		$order_column = 'p.description';
	elseif ($order_column==1)
		$order_column = 'description';

	$order_dir = ($order_dir=='asc')?"ASC":"DESC";

	$sql_order = "ORDER BY $order_column $order_dir";


	$search=false;
	$sql_where='';
	$sql_having='';
	$sql_join = "
		LEFT JOIN product p ON (p.product_id = s.product_id)
		LEFT JOIN glaze g ON (g.glaze_id = s.glaze_id)
		LEFT JOIN design d ON (d.design_id = s.design_id)
	";


	if (isset($_GET['search'])) {

		$search = $_GET['search']['value'];

		if ((!empty($search)) && (strlen($search)>1)) {

			$sql_where = "
				WHERE ( 
					(p.code LIKE '%$search%') OR (p.description LIKE '%$search%') 
					OR (g.code LIKE '%$search%') OR (g.description LIKE '%$search%') 
					OR (d.code LIKE '%$search%') OR (d.description LIKE '%$search%') 
				)
			";

		}
	}

	
	if (!empty($_SESSION['inventory_filter'])) {

		if ($_SESSION['inventory_filter'] =='__ALL_')
			$sql_having = '';

		if ($_SESSION['inventory_filter'] =='__BELOW_MIN_')
			$sql_having = "
				AND (current_stock < $minimum_quantity)
			";

		if ($_SESSION['inventory_filter'] =='__NONE_ZERO_')
			$sql_having = " 
	            AND (current_stock > 0)
	  		";

		elseif ($_SESSION['inventory_filter'] =='__ZERO_')
			$sql_having = " 
	            AND (current_stock = 0)
	  		";
	}


	// get total number of records
	$sql_total = "SELECT s.stock_id FROM stock s $sql_join $sql_where $sql_having";
	$qry = $conn->Query($sql_total);
	if ($qry) {
		$recordsTotal = $qry->num_rows;
		$recordsFiltered = $qry->num_rows;
	}
	else {
		$error = $conn->error."\n".$sql;
	}

	$sql = "
		SELECT s.*, 
			CONCAT(p.code, ' - ', g.code, ' - ', d.code) AS code,
			CONCAT(p.description, ' ', g.description, ' ', d.description) AS description,
            (
            	SELECT filename
            	FROM stock_images si
            	WHERE (si.stock_id = s.stock_id)
            	LIMIT 1
            ) as image
		FROM stock s
		$sql_join 
		$sql_where
		$sql_having
		$sql_order
		$sql_limit
	";

	$_SESSION['inventory_sql'] = $sql;
	$_SESSION['inventory_search'] = $_GET['search']['value'];

	$qry = $conn->Query($sql);

	if (!$qry) {
		$error = $conn->error."\n".$sql;
	}
	else {

		if ($qry->num_rows > 0) {

			$i=0;
			while( $obj = $qry->fetch_object() ) {

				$rows[$i]['DT_RowId'] = $obj->stock_id;
				$rows[$i]['code'] = $obj->code;
				$rows[$i]['description'] = htmlentities($obj->description);
				$rows[$i]['stock'] = $obj->current_stock;
				$rows[$i]['image'] = $obj->image;

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