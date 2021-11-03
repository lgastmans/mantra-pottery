<?php
	require_once("db_mysqli.php");
	require_once("movement.inc.php");
	require_once("stock.inc.php");
	require_once("source.inc.php");

	$movement = new movement();
	$movement->conn = $conn;	

	$data = array();


	if ((isset($_POST['action'])) && ($_POST['action'] == 'edit'))
	{

		// $movement->code = $_POST['product_code'];

		if ((isset($_POST['stock_movement_id'])) && ($_POST['stock_movement_id'] != '__NEW_')) {
			/*
				edit
			*/
			$row_id = $_POST['stock_movement_id'];
	 	
		 	$movement->stock_movement_id = $row_id;

		 	$movement->update();

			$data['error'] = $movement->data['error'];
			$data['msg'] = $movement->data['msg'];

		}
		else {
			/*
				insert
			*/

			// $data['error'] = true;
			// $data['msg'] = "items array: ".empty($_SESSION['movement_items']);

			if (isset($_SESSION['movement_items'])) 
			{

				$movement->reference = $_POST['reference'];
				$movement->source_id = $_POST['source_id'];
				$movement->movement_type = $_SESSION['movement_type'];
				$movement->date = $_POST['date'];
				$movement->items = $_SESSION['movement_items'];

				
				if (!empty($_SESSION['movement_items']))
				{

					$source = new Source();
					$source->conn = $conn;
					$source->source_id = $_POST['source_id'];

					if ($source->get())
					{

						$movement->save();

						$data['error'] = $movement->data['error'];
						$data['msg'] = $movement->data['msg'];

					}
					else 
					{

						$data['error'] = $source->data['error'];
						$data['msg'] = $source->data['msg'];

					}
				}
				else 
				{

					$data['error'] = true;
					$data['msg'] = "Items list is empty";

				}
			}
			else
			{

				$data['error'] = true;
				$data['msg'] = "Items list is empty";

			}
		}

	}
	if ((isset($_GET['action'])) && ($_GET['action'] == 'view')) 
	{

		$row_id = $_GET['stock_movement_id'];
 	
	 	$movement->stock_movement_id = $row_id;

	 	$movement->view();

		$data['error'] = $movement->data['error'];
		$data['msg'] = $movement->data['msg'];

	}
	elseif ((isset($_POST['action'])) && ($_POST['action'] == 'cancel'))
	{

		$row_id = $_POST['stock_movement_id'];
 	
	 	$movement->stock_movement_id = $row_id;

		$movement->cancel();

		$data['error'] = $movement->data['error'];
		$data['msg'] = $movement->data['msg'];


	}
	elseif ((isset($_POST['action'])) && ($_POST['action'] == 'session_vars'))
	{

		$movement->init_session_vars();

		$data['error'] = $movement->data['error'];
		$data['msg'] = $movement->data['msg'];
		
	}
	elseif ((isset($_GET['action'])) && ($_GET['action'] == 'get_data'))
	{
		$draw = 1;
		if (isset($_GET['draw']))
			$movement->draw = (int) ($_GET['draw']);

		$start = 0;
		if (IsSet($_GET['start']))
			$movement->start = $_GET['start'];

		$length = 50;
		if (isset($_GET['length']))
			$movement->length = $_GET['length'];


		$order_column = 0;
		$order_dir = 'ASC';
		if (isset($_GET['order'])) {
			$order_column = $_GET['order'][0]['column'];
			$order_dir = $_GET['order'][0]['dir'];
		}


		$movement->order_column = $order_column;
		$movement->order_dir = $order_dir;


		$search=false;
		$sql_where='';
		$sql_join = "";


		if (isset($_GET['search'])) {
			$movement->search = $_GET['search'];
		}

		$movement->get_data();

	 	$data = $movement->data;

	}
	elseif ((isset($_POST['action'])) && ($_POST['action'] == 'check_session_var'))
	{
		/*
			search for stock_id in session array
		*/
		
		$found = false;
		foreach( $_SESSION['movement_items'] as $key=>$row) {
			if ($row['stock_id'] === $_POST['stock_id']) {
				$found = true;
				break;
			}
		}
		
		if ($found) {
			//array_delete($_SESSION['movement_items'], $key);
			//unset($_SESSION['movement_items'][$key]);

			$data['quantity'] = $_SESSION['movement_items'][$key]['quantity'];
			$data['error'] = false;
			$data['msg'] = 'found';

			array_splice($_SESSION['movement_items'],$key,1);
			
		}
		else {

			$data['quantity'] = 0;
			$data['error'] = false;
			$data['msg'] = 'NOT found ';

		}
		
	}	
	elseif ((isset($_POST['action'])) && ($_POST['action'] == 'update_session_var'))
	{
		
		$arr = array();

		if (!is_numeric($_POST['quantity'])) {

			$data['error'] = true;
			$data['msg'] = 'Invalid quantity';

		}
		elseif ((isset($_POST['stock_id'])) && (!empty($_POST['stock_id']))) 
		{

			$stock = new Stock();
			$stock->conn = $conn;
			$stock->stock_id = $_POST['stock_id'];
			$stock->get();

			if (!$stock->data['error'])
			{
				$arr['stock_id'] = $_POST['stock_id'];
				$arr['code'] = $_POST['code'];
				$arr['description'] = $_POST['description'];
				$arr['quantity'] = $_POST['quantity'];

				array_push($_SESSION['movement_items'], $arr);

				$data['error'] = false;
				$data['msg'] = 'item saved';
			}
			else {
				$data['error'] = true;
				$data['msg'] = 'Item not found';
			}
		}
		else {
			$data['error'] = true;
			$data['msg'] = 'Item not found';
		}
	}	
	elseif ((isset($_GET['action'])) && ($_GET['action'] == 'get_session_data'))
	{

		$draw = 1;
		if (isset($_GET['draw']))
			$draw = (int) ($_GET['draw']);

		$recordsTotal = count($_SESSION['movement_items']);
		$recordsFiltered = count($_SESSION['movement_items']);

		$rows = $_SESSION['movement_items'];

		$error = false;

		$data = array(
			'draw'				=> $draw,
			'recordsTotal'		=> $recordsTotal,
			'recordsFiltered'	=> $recordsFiltered,
			'data'				=> $rows,
			'error'				=> $error
		);		
	}
		
	echo json_encode($data);

?>