<?php
	require_once("db_mysqli.php");
	require_once("stock.inc.php");
	require_once("session.php");

	$stock = new stock();
	$stock->conn = $conn;

	$stock_registry = new stock_registry();
	$stock_registry->conn = $conn;

	$data = array();


	if ((isset($_POST['action'])) && ($_POST['action'] == 'get'))
	{
		if (isset($_POST['row_id']))
			$row_id = $_POST['row_id'];

		$stock->stock_id = $row_id;

		$stock->get();

		$data['stock_id'] = $stock->stock_id;
		$data['product_id'] = $stock->product_id;
		$data['glaze_id'] = $stock->glaze_id;
		$data['design_id'] = $stock->design_id;
		$data['current_stock'] = $stock->current_stock;

		$data['product_description'] = $stock->product_description;
		$data['glaze_description'] = $stock->glaze_description;
		$data['design_description'] = $stock->design_description;
		$data['height'] = $stock->height;
		$data['width'] = $stock->width;
		$data['weight'] = $stock->weight;
		$data['volume'] = $stock->volume;

	}
	if ((isset($_POST['action'])) && ($_POST['action'] == 'edit'))
	{

		$stock->stock_id = $_POST['stock_id'];
		$stock->product_id = $_POST['product_id'];
		$stock->glaze_id = $_POST['glaze_id'];
		$stock->design_id = $_POST['design_id'];

		if ((isset($_POST['stock_id'])) && ($_POST['stock_id'] != '__NEW_')) {
			/*
				edit
			*/
			$row_id = $_POST['stock_id'];
	 	
		 	$stock->stock_id = $row_id;

		 	$stock->update();

			$data['error'] = $stock->data['error'];
			$data['msg'] = $stock->data['msg'];

		}
		else {
			/*
				insert
			*/
			$stock->insert();

			$data['error'] = $stock->data['error'];
			$data['msg'] = $stock->data['msg'];

		}

	}
	elseif ((isset($_POST['action'])) && ($_POST['action'] == 'delete'))
	{

		if (isset($_POST['stock_id'])) {

			/*
				delete
			*/
			$row_id = $_POST['stock_id'];

		 	$stock->stock_id = $row_id;

		 	$stock->delete();

		 	$data = $stock->data;

		}
	}
	elseif ((isset($_POST['action'])) && ($_POST['action'] == 'upload_image'))
	{

		print_r($_POST);
		// if(isset($_FILES['file']['name'])){
		
		// 	$data['error'] = true;
		// 	$data['msg'] = "file name not set";

		// 	$stock_image = new stock_images();
		// 	$stock_image->conn = $conn;

		//  	$stock_image->files = $_FILES['file'];

		//  	$stock_image->upload();

		//  	$data = $stock_image->data;

		// }
		// else {

		// 	$data['error'] = true;
		// 	$data['msg'] = "file name not set";

		// }
	}	
	elseif ((isset($_POST['action'])) && ($_POST['action'] == 'delete_image'))
	{

		if (isset($_POST['image_id'])) {

			/*
				delete
			*/
			$row_id = $_POST['image_id'];

			$stock_image = new stock_images();
			$stock_image->conn = $conn;

		 	$stock_image->id = $row_id;

		 	$stock_image->delete();

		 	$data = $stock_image->data;

		}
	}	
	elseif ((isset($_POST['action'])) && ($_POST['action'] == 'get_images'))
	{

		if (isset($_POST['stock_id'])) {

			/*
				delete
			*/
			$stock_id = $_POST['stock_id'];

			$stock_image = new stock_images();
			$stock_image->conn = $conn;

		 	$stock_image->stock_id = $stock_id;

		 	$stock_image->get_data();

		 	$data = $stock_image->data;

		}
	}	
	elseif ((isset($_GET['action'])) && ($_GET['action'] == 'get_data'))
	{
		$draw = 1;
		if (isset($_GET['draw']))
			$stock->draw = (int) ($_GET['draw']);

		$start = 0;
		if (IsSet($_GET['start']))
			$stock->start = $_GET['start'];

		$length = 50;
		if (isset($_GET['length']))
			$stock->length = $_GET['length'];


		$order_column = 0;
		$order_dir = 'ASC';
		if (isset($_GET['order'])) {
			$order_column = $_GET['order'][0]['column'];
			$order_dir = $_GET['order'][0]['dir'];
		}


		$stock->order_column = $order_column;
		$stock->order_dir = $order_dir;


		$search=false;
		$sql_where='';
		$sql_join = "";


		if (isset($_GET['search'])) {
			$stock->search = $_GET['search'];
		}


		if (isset($_GET['columns']))
			$stock->columns = $_GET['columns'];


		$stock->inventory_filter = $_SESSION['inventory_filter'];

		$stock->get_data();

		$_SESSION['inventory_sql'] = $stock->inventory_sql;
		$_SESSION['inventory_search'] = $stock->inventory_search;

	 	$data = $stock->data;

	}
	elseif ((isset($_GET['action'])) && ($_GET['action'] == 'get_registry_data'))
	{

		$row_id = 0;
		if (isset($_GET['row_id']))
			$stock_registry->stock_id = $_GET['row_id'];

		$draw = 1;
		if (isset($_GET['draw']))
			$stock_registry->draw = (int) ($_GET['draw']);

		$start = 0;
		if (IsSet($_GET['start']))
			$stock_registry->start = $_GET['start'];

		$length = 50;
		if (isset($_GET['length']))
			$stock_registry->length = $_GET['length'];


		$order_column = 0;
		$order_dir = 'ASC';
		if (isset($_GET['order'])) {
			$order_column = $_GET['order'][0]['column'];
			$order_dir = $_GET['order'][0]['dir'];
		}


		$stock_registry->order_column = $order_column;
		$stock_registry->order_dir = $order_dir;


		$search=false;
		$sql_where='';
		$sql_join = "";


		if (isset($_GET['search'])) {
			$stock_registry->search = $_GET['search'];
		}

		$stock_registry->get_data();

	 	$data = $stock_registry->data;

	}





	echo json_encode($data);

?>