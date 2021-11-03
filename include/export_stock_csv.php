<?php
  	require_once("session.php");
  	require_once("functions.inc.php");
  	require_once("db_mysqli.php");

	header("Content-Type: application/text; name=mantra-pottery.csv");
	header("Content-Transfer-Encoding: binary");
	header("Content-Disposition: attachment; filename=mantra-pottery.csv");
	header("Expires: 0");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");

	$sql = $_SESSION['inventory_sql'];

	$pos = strrpos($sql, 'LIMIT');
	if ($pos !== false) {
		$sql = substr($sql, 0, $pos);
	}
	

	$qry = $conn->Query($sql);


	$filter = 'none';
	if ($_SESSION['inventory_filter'] =='__BELOW_MIN_')
		$filter = 'stock below minimum';
	if ($_SESSION['inventory_filter'] =='__NONE_ZERO_')
		$filter = 'non-zero stock';
	elseif ($_SESSION['inventory_filter'] =='__ZERO_')
		$filter = 'zero stock';

	$search = 'none';
	if (isset($_SESSION['inventory_search']))
		$search = $_SESSION['inventory_search'];


//die($sql.":".$search);


	$str_current = '';

  	if (!$qry) {
		$error = $conn->error;
		die($error);
	}
	else {
		echo "\"Mantra Pottery\"\n";
		echo "\"Date:\",\"".date('d-m-Y')."\"\n";
		echo "\"Filter:\",\"$filter\"\n";
		echo "\"Search:\",\"$search\"\n\n";

		echo "\"Code\",\"Description\",\"Stock\",\"Height (cm)\",\"Width (cm)\",\"Weight (gm)\",\"Volume (cm3)\"\n";

		while( $obj = $qry->fetch_object() ) {

			$str_current .= "\"".$obj->code."\",".
				"\"".str_replace(PHP_EOL, '', $obj->description)."\",".
				"\"".$obj->current_stock."\",".
				"\"".number_format($obj->height,3,'.','')."\",".
				"\"".number_format($obj->width,3,'.','')."\",".
				"\"".number_format($obj->weight,3,'.','')."\",".
				"\"".number_format($obj->volume,3,'.','')."\"".
				"\n";

		}		

		echo $str_current;
		
	}
?>