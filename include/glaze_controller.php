<?php
	require_once("db_mysqli.php");
	require_once("glaze.inc.php"); 	

	$glaze = new glaze();
	$glaze->conn = $conn;	

	$data = array();


	if ((isset($_POST['action'])) && ($_POST['action'] == 'get'))
	{
		if (isset($_POST['row_id']))
			$row_id = $_POST['row_id'];

		$glaze->glaze_id = $row_id;

		$glaze->get();


		$data['glaze_id'] = $glaze->glaze_id;
		$data['code'] = $glaze->code;
		$data['description'] = $glaze->description;

	}
	if ((isset($_POST['action'])) && ($_POST['action'] == 'edit'))
	{

		if ((isset($_POST['glaze_id'])) && ($_POST['glaze_id'] != '__NEW_')) {
			/*
				edit
			*/
			$row_id = $_POST['glaze_id'];
	 	
		 	$glaze->glaze_id = $row_id;
		 	$glaze->code = $_POST['glaze_code'];
		 	$glaze->description = $_POST['glaze_description'];

		 	$glaze->update();

			$data['error'] = $glaze->data['error'];
			$data['msg'] = $glaze->data['msg'];

		}
		else {
			/*
				insert
			*/
		 	$glaze->code = $_POST['glaze_code'];
		 	$glaze->description = $_POST['glaze_description'];

			$glaze->insert();

			$data['error'] = $glaze->data['error'];
			$data['msg'] = $glaze->data['msg'];

		}

	}
	elseif ((isset($_POST['action'])) && ($_POST['action'] == 'delete'))
	{

		if (isset($_POST['glaze_id'])) {

			/*
				delete
			*/
			$row_id = $_POST['glaze_id'];

			$glaze = new glaze();
			$glaze->conn = $conn;

		 	$glaze->glaze_id = $row_id;

		 	$glaze->delete();

		 	$data = $glaze->data;

		}
	}
	elseif ((isset($_GET['action'])) && ($_GET['action'] == 'get_data'))
	{
		$draw = 1;
		if (isset($_GET['draw']))
			$glaze->draw = (int) ($_GET['draw']);

		$start = 0;
		if (IsSet($_GET['start']))
			$glaze->start = $_GET['start'];

		$length = 50;
		if (isset($_GET['length']))
			$glaze->length = $_GET['length'];


		$order_column = 0;
		$order_dir = 'ASC';
		if (isset($_GET['order'])) {
			$order_column = $_GET['order'][0]['column'];
			$order_dir = $_GET['order'][0]['dir'];
		}


		$glaze->order_column = $order_column;
		$glaze->order_dir = $order_dir;


		$search=false;
		$sql_where='';
		$sql_join = "";


		if (isset($_GET['search'])) {
			$glaze->search = $_GET['search'];
		}

		$glaze->get_data();
		
	 	$data = $glaze->data;
	}

	echo json_encode($data);

?>