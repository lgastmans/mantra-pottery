<?php
	require_once("include/session.php");
	require_once("include/db_mysqli.php");

	require_once('include/functions.inc.php');
/*
		https://stackoverflow.com/questions/4340793/how-to-find-gaps-in-sequential-numbering-in-mysql
*/

	$ref = get_receive_reference();

	echo "> ".$ref;




			// while( $obj = $qry->fetch_object() ) {

			// 	$data[$i]['stock_id'] = $obj->stock_id;
			// 	$data[$i]['code'] = $obj->code;
			// 	$data[$i]['description'] = $obj->description;

			// 	$i++;

			// } // while



/*
  $_SESSION['test'] = 'This is a test';

  print_r($_SESSION['test']);

  unset($_SESSION['test']);

  print_r($_SESSION['test']);
*/

?>