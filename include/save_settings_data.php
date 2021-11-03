<?php
 	require_once("db_mysqli.php");
 	require_once("functions.inc.php");
 	require_once("company.inc.php");

 	$data = array();
 	$error = '';

	/*
		update
	*/

	if (!is_numeric($_POST['minimum_quantity'])) {

		$data['error'] = true;
		$data['msg'] = "The quantity field is not numeric";

		die(json_encode($data));
	}


    $sql = "
    	UPDATE settings
    	SET 
    		minimum_quantity = '".mysqli_real_escape_string($conn, $_POST['minimum_quantity'])."'
    ";

    $qry = $conn->Query($sql);

	if (!$qry) {

		$data['error'] = true;
		$data['msg'] = $conn->error; //$sql;

	}
	else {

		$data['error'] = false;
		$data['msg'] = 'Settings saved successfully.';

	} // if (!qry)


	$company = new Company();
	$company->conn = $conn;
	$company->company_id = $_POST["company_id"];
	$company->legal_name = $_POST["legal_name"];
	$company->trade_name = $_POST["trade_name"];
	$company->branch = $_POST["branch"];
	$company->address = $_POST["address"];
	$company->phone = $_POST["phone"];
	$company->gstin = $_POST["gstin"];

	$company->update();

	$data['error'] = $company->data['error'];
	$data['msg'] = $company->data['msg'];

	echo json_encode($data);

?>