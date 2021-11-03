<?php
	require_once("db_mysqli.php");
	require_once("product.inc.php"); 	

	$product = new product();
	$product->conn = $conn;	

	$data = array();


	if ((isset($_POST['action'])) && ($_POST['action'] == 'get'))
	{
		if (isset($_POST['row_id']))
			$row_id = $_POST['row_id'];

		$product->product_id = $row_id;

		$product->get();


		$data['product_id'] = $product->product_id;
		$data['code'] = $product->code;
		$data['description'] = $product->description;
		$data['height'] = $product->height;
		$data['width'] = $product->width;
		$data['weight'] = $product->weight;
		$data['volume'] = $product->volume;

	}
	if ((isset($_POST['action'])) && ($_POST['action'] == 'edit'))
	{

		$product->code = $_POST['product_code'];
		$product->description = $_POST['product_description'];
		$product->height = $_POST['product_height'];
		$product->width = $_POST['product_width'];
		$product->weight = $_POST['product_weight'];
		$product->volume = $_POST['product_volume'];

		if ((isset($_POST['product_id'])) && ($_POST['product_id'] != '__NEW_')) {
			/*
				edit
			*/
			$row_id = $_POST['product_id'];
	 	
		 	$product->product_id = $row_id;

		 	$product->update();

			$data['error'] = $product->data['error'];
			$data['msg'] = $product->data['msg'];

		}
		else {
			/*
				insert
			*/
			$product->insert();

			$data['error'] = $product->data['error'];
			$data['msg'] = $product->data['msg'];

		}

	}
	elseif ((isset($_POST['action'])) && ($_POST['action'] == 'delete'))
	{

		if (isset($_POST['product_id'])) {

			/*
				delete
			*/
			$row_id = $_POST['product_id'];

		 	$product->product_id = $row_id;

		 	$product->delete();

		 	$data = $product->data;

		}
	}
	elseif ((isset($_GET['action'])) && ($_GET['action'] == 'get_data'))
	{
		$draw = 1;
		if (isset($_GET['draw']))
			$product->draw = (int) ($_GET['draw']);

		$start = 0;
		if (IsSet($_GET['start']))
			$product->start = $_GET['start'];

		$length = 50;
		if (isset($_GET['length']))
			$product->length = $_GET['length'];


		$order_column = 0;
		$order_dir = 'ASC';
		if (isset($_GET['order'])) {
			$order_column = $_GET['order'][0]['column'];
			$order_dir = $_GET['order'][0]['dir'];
		}


		$product->order_column = $order_column;
		$product->order_dir = $order_dir;


		$search=false;
		$sql_where='';
		$sql_join = "";


		if (isset($_GET['search'])) {
			$product->search = $_GET['search'];
		}

		$product->get_data();

	 	$data = $product->data;

	}
	elseif ((isset($_POST['action'])) && ($_POST['action'] == 'get_typeahead'))
	{

		$product->get_typeahead();
	 	$data = $product->data;

	}

	echo json_encode($data);

?>