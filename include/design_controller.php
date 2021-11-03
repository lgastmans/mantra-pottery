<?php
	require_once("db_mysqli.php");
	require_once("design.inc.php"); 	

	$design = new design();
	$design->conn = $conn;	

	$data = array();


	if ((isset($_POST['action'])) && ($_POST['action'] == 'get'))
	{
		if (isset($_POST['row_id']))
			$row_id = $_POST['row_id'];

		$design->design_id = $row_id;

		$design->get();


		$data['design_id'] = $design->design_id;
		$data['code'] = $design->code;
		$data['description'] = $design->description;

	}
	if ((isset($_POST['action'])) && ($_POST['action'] == 'edit'))
	{

		if ((isset($_POST['design_id'])) && ($_POST['design_id'] != '__NEW_')) {
			/*
				edit
			*/
			$row_id = $_POST['design_id'];
	 	
		 	$design->design_id = $row_id;
		 	$design->code = $_POST['design_code'];
		 	$design->description = $_POST['design_description'];

		 	$design->update();

			$data['error'] = $design->data['error'];
			$data['msg'] = $design->data['msg'];

		}
		else {
			/*
				insert
			*/
		 	$design->code = $_POST['design_code'];
		 	$design->description = $_POST['design_description'];

			$design->insert();

			$data['error'] = $design->data['error'];
			$data['msg'] = $design->data['msg'];

		}

	}
	elseif ((isset($_POST['action'])) && ($_POST['action'] == 'delete'))
	{

		if (isset($_POST['design_id'])) {

			/*
				delete
			*/
			$row_id = $_POST['design_id'];

			$design = new design();
			$design->conn = $conn;

		 	$design->design_id = $row_id;

		 	$design->delete();

		 	$data = $design->data;

		}
	}
	elseif ((isset($_GET['action'])) && ($_GET['action'] == 'get_data'))
	{
		$draw = 1;
		if (isset($_GET['draw']))
			$design->draw = (int) ($_GET['draw']);

		$start = 0;
		if (IsSet($_GET['start']))
			$design->start = $_GET['start'];

		$length = 50;
		if (isset($_GET['length']))
			$design->length = $_GET['length'];


		$order_column = 0;
		$order_dir = 'ASC';
		if (isset($_GET['order'])) {
			$order_column = $_GET['order'][0]['column'];
			$order_dir = $_GET['order'][0]['dir'];
		}


		$design->order_column = $order_column;
		$design->order_dir = $order_dir;


		$search=false;
		$sql_where='';
		$sql_join = "";


		if (isset($_GET['search'])) {
			$design->search = $_GET['search'];
		}

		$design->get_data();

	 	$data = $design->data;
	}

	echo json_encode($data);

?>