<?php


	define('_movement_type_receive_',1);
	define('_movement_type_deliver_',2);


	define('_movement_status_active_',0);
	define('_movement_status_cancelled_',1);


	define('_registry_type_received_', 1);
	define('_registry_type_delivered_', 2);
	define('_registry_type_cancelled_', 3);


	function set_mysql_date($str_date, $str_separator='-') {

		$str_new_date = substr($str_date, 0, 10);
		$arr_date = explode($str_separator, $str_new_date);

		return $arr_date[2].$str_separator.$arr_date[1].$str_separator.$arr_date[0];

	}



	function set_formatted_date($str_date, $str_separator='-', $display_time=false) {
		
		try {
			$date = new DateTime($str_date);
		} catch (Exception $e) {
			return htmlentities($e->getMessage());
		}

		if ($display_time)
			return $date->format('M j, Y h:i A');
		else
			return $date->format('M j, Y');

	}



	function get_receive_reference()
	{
		global $conn;

		$ref = false;

		$qry = $conn->Query("LOCK TABLE reference_receive READ");

		if (!$qry) {

			return $ref;
			//echo "error main: ".$conn->error;

		}
		else {

		    $sql = "
				SELECT *
		    	FROM reference_receive
		    ";
		    $qry = $conn->Query($sql);

		    if ($qry->num_rows > 1) {

		    	/*
					fetch re-usable number
		    	*/
		    	
		    	return "fetch re-usable number";

		    }
		    else {

		    	/*
					fetch the next number
		    	*/
		    	
		    	$sql = "SELECT MAX(`reference`)+1 AS `reference` FROM reference_receive ";
		    	$qry = $conn->Query($sql);

		    	if (!$qry) {

		    		//echo "error: ".$conn->error;
		    		return $ref;

		    	}
		    	else {

		    		$obj = $qry->fetch_object();
		    		$ref = $obj->reference;

		    	}	    	
		    }

		    $qry = $conn->Query("UNLOCK TABLES");
		}

		return $ref;

	}



?>