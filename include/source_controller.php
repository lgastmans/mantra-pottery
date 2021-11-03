<?php
	require_once("db_mysqli.php");
	require_once("source.inc.php"); 	

	$source = new source();
	$source->conn = $conn;	

	$data = array();


	if ((isset($_POST['action'])) && ($_POST['action'] == 'get'))
	{
		if (isset($_POST['row_id']))
			$row_id = $_POST['row_id'];

		$source->source_id = $row_id;

		$source->get();

		$data['source_id'] = $source->source_id;
		$data['type_id'] = $source->type_id;
		$data['name'] = $source->name;
		$data['address'] = $source->address;
		$data['phone'] = $source->phone;
		$data['gstin'] = $source->gstin;

	}
	if ((isset($_POST['action'])) && ($_POST['action'] == 'edit'))
	{

		if ((isset($_POST['source_id'])) && ($_POST['source_id'] != '__NEW_')) {
			/*
				edit
			*/
			$row_id = $_POST['source_id'];
	 	
		 	$source->source_id = $row_id;
		 	$source->type_id = $_POST['type_id'];
		 	$source->name = $_POST['source_name'];
		 	$source->address = $_POST['source_address'];
		 	$source->phone = $_POST['source_phone'];
		 	$source->gstin = $_POST['source_gstin'];

		 	$source->update();

			$data['error'] = $source->data['error'];
			$data['msg'] = $source->data['msg'];

		}
		else {
			/*
				insert
			*/
			$source->type_id = $_POST['type_id'];
		 	$source->name = $_POST['source_name'];
		 	$source->address = $_POST['source_address'];
		 	$source->phone = $_POST['source_phone'];
		 	$source->gstin = $_POST['source_gstin'];

			$source->insert();

			$data['error'] = $source->data['error'];
			$data['msg'] = $source->data['msg'];

		}

	}
	elseif ((isset($_POST['action'])) && ($_POST['action'] == 'delete'))
	{

		if (isset($_POST['source_id'])) {

			/*
				delete
			*/
			$row_id = $_POST['source_id'];

			$source = new source();
			$source->conn = $conn;

		 	$source->source_id = $row_id;

		 	$source->delete();

		 	$data = $source->data;

		}
	}
	elseif ((isset($_GET['action'])) && ($_GET['action'] == 'get_data'))
	{
		$draw = 1;
		if (isset($_GET['draw']))
			$source->draw = (int) ($_GET['draw']);

		$start = 0;
		if (IsSet($_GET['start']))
			$source->start = $_GET['start'];

		$length = 50;
		if (isset($_GET['length']))
			$source->length = $_GET['length'];


		$order_column = 0;
		$order_dir = 'ASC';
		if (isset($_GET['order'])) {
			$order_column = $_GET['order'][0]['column'];
			$order_dir = $_GET['order'][0]['dir'];
		}


		$source->order_column = $order_column;
		$source->order_dir = $order_dir;


		$search=false;
		$sql_where='';
		$sql_join = "";


		if (isset($_GET['search'])) {
			$source->search = $_GET['search'];
		}

		$source->get_data();
		
	 	$data = $source->data;
	}

	echo json_encode($data);

?>