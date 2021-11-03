<?php
 	require_once("functions.inc.php");

 	require_once("movement.inc.php");

/*

	class stock

	class stock_registry

	class stock_images

*/


class stock {
 
	public $stock_id;
	public $product_id;
	public $glaze_id;
	public $design_id;
	public $current_stock;

	public $code = '';
	public $description = '';

	public $product_description;
	public $glaze_description;
	public $design_description;
	public $height;
	public $width;
	public $weight;
	public $volume;

	public $data = array();

	public $conn;

	public $draw;
	public $start;
	public $length;
	public $order_column;
	public $order_dir;
	public $search;
	public $columns;

	public $inventory_filter;
	/*
		these two variables are saved to a 
		session variable for the csv export
	*/
	public $inventory_sql;
	public $inventory_search;


	function __construct()
	{
		unset($this->data);

		$this->stock_id = 0;
		$this->product_id = 0;
		$this->glaze_id = 0;
		$this->design_id = 0;
		$this->current_stock = 0;

		$this->code = '';
		$this->description = '';

		$this->product_description = '';
		$this->glaze_description = '';
		$this->design_description = '';
		$this->height = 0;
		$this->width = 0;
		$this->weight = 0;
		$this->volume = 0;

		$this->data['error'] = false;
		$this->data['msg'] = 'init';

		$this->draw;
		$this->start;
		$this->length;
		$this->order_column;
		$this->order_dir;
		$this->search;
		$this->columns;

		$this->inventory_filter='';
		$this->inventory_sql='';
		$this->inventory_search='';
		
	} // construct


	public function get() 
	{
	    $sql = "
	    	SELECT s.*, 
	    		p.height, p.width, p.weight, p.volume,
	    		p.description AS product_description, 
	    		g.description AS glaze_description, 
	    		d.description AS design_description,
				CONCAT_WS('-', p.code, g.code, d.code) AS code,
				CONCAT(p.description, ' ', g.description, ' ', d.description) AS description
	    	FROM stock s
			LEFT JOIN product p ON (p.product_id = s.product_id)
			LEFT JOIN glaze g ON (g.glaze_id = s.glaze_id)
			LEFT JOIN design d ON (d.design_id = s.design_id)
	    	WHERE stock_id = ".$this->stock_id;

	    $qry = $this->conn->Query($sql);

		if (!$qry) {
			
			$this->data['error'] = true;
			$this->data['msg'] = $conn->error;

			$this->stock_id = 0;
			$this->product_id = 0;
			$this->glaze_id = 0;
			$this->design_id = 0;
			$this->current_stock = 0;

			$this->code = '';
			$this->description = '';

			$this->product_description = '';
			$this->glaze_description = '';
			$this->design_description = '';
			$this->height = 0;
			$this->width = 0;
			$this->weight = 0;
			$this->volume = 0;

		}
		else {

			if ($qry->num_rows > 0) {

				$obj = $qry->fetch_object();

				$this->stock_id = $obj->stock_id;
				$this->product_id = $obj->product_id;
				$this->glaze_id = $obj->glaze_id;
				$this->design_id = $obj->design_id;
				$this->current_stock = $obj->current_stock;

				$this->code = $obj->code;
				$this->description = $obj->description;

				$this->product_description = $obj->product_description;
				$this->glaze_description = $obj->glaze_description;
				$this->design_description = $obj->design_description;
				$this->height = $obj->height;
				$this->width = $obj->width;
				$this->weight = $obj->weight;
				$this->volume = $obj->volume;


			} // if num_rows

		} // if (!qry)

		return true;

	} // get



	public function insert()
	{

		$sql = "
			INSERT INTO stock
			(
				product_id,
				glaze_id,
				design_id,
				current_stock
			)
			VALUES (
				'".mysqli_real_escape_string($this->conn, $this->product_id)."',
				'".mysqli_real_escape_string($this->conn, $this->glaze_id)."',
				'".mysqli_real_escape_string($this->conn, $this->design_id)."',
				'0'
			)
		";

		$qry = $this->conn->Query($sql);

		if (!$qry) {
			$this->data['error'] = true;
			$this->data['msg'] = $this->conn->error; //.":".$sql;
		}
		else {
			$this->stock_id = $this->conn->insert_id;

			/*
				add a zero stock entry in the registry 
				for the newly created stock product
			*/
			$stock_registry = new stock_registry();
			$stock_registry->conn = $this->conn;
			$stock_registry->stock_id = $this->stock_id;
			$stock_registry->quantity = 0;
			$stock_registry->date = date('Y-m-d H:i:s');
			$stock_registry->comment = "Stock product created";
			$stock_registry->entry_type = 1;

			$stock_registry->update();

			if ($stock_registry->data['error']) {

				$this->data['error'] = true;
				$this->data['msg'] = $stock_registry->data['msg'];

			}
			else {
				$this->data['error'] = false;
				$this->data['msg'] = "insert id: ".$this->conn->insert_id;
			}
		}

		return true;

	} // insert



	public function update() 
	{

	    $sql = "
	    	UPDATE stock
	    	SET 
	    		product_id = '".mysqli_real_escape_string($this->conn, $this->product_id)."',
				glaze_id = '".mysqli_real_escape_string($this->conn, $this->glaze_id)."',
				design_id = '".mysqli_real_escape_string($this->conn, $this->design_id)."'
	    	WHERE stock_id = ".$this->stock_id;
	    $qry = $this->conn->Query($sql);

		if (!$qry) {

			$this->data['error'] = true;
			$this->data['msg'] = $this->conn->error.":".$sql;

		}
		else{

			$this->data['error'] = false;
			$this->data['msg'] = 'successfully updated';

		} // if (!qry)

		return true;

	} // update



	public function delete()
	{

		$sql = "
			SELECT stock_id
			FROM stock
			WHERE stock_id = ".$this->stock_id;

		$qry = $this->conn->Query($sql);

		if ($qry->num_rows > 0) {


			/*
				check if this stock_id has corresponding entries in stock_movement
				with status NOT cancelled
			*/
			$sql = "
				SELECT sm.reference 
				FROM `stock_movement_items` smi
				INNER JOIN stock_movement sm ON (sm.stock_movement_id = smi.stock_movement_id) AND (sm.status != 1)
				WHERE stock_id = ".$this->stock_id;

			$qry = $this->conn->Query($sql);

			if ($qry->num_rows > 0) {

				$obj = $qry->fetch_object();

				$this->data['error'] = true;
				$this->data['msg'] = "This stock entry cannot be removed (".$obj->reference.")";

				return false;
			}

			
			$qry = $this->conn->Query("START TRANSACTION");


			/*
				delete corresponding stock_movement entries
			*/
			$sql = "
				SELECT sm.stock_movement_id
				FROM `stock_movement_items` smi
				INNER JOIN stock_movement sm ON (sm.stock_movement_id = smi.stock_movement_id)
				WHERE stock_id = ".$this->stock_id;

			$qry = $this->conn->Query($sql);

			$stock_movement = new movement();
			$stock_movement->conn = $this->conn;

			while($obj = $qry->fetch_object())
			{

				$stock_movement->stock_movement_id = $obj->stock_movement_id;

				$stock_movement->delete();

				if ($stock_movement->data['error'])
				{

					$this->data['error'] = true;
					$this->data['msg'] = $stock_movement->data['msg'];

					$qry = $this->conn->Query("ROLLBACK");

					return false;

				}

			}


			/*
				delete corresponding stock_registry entries
			*/
			$stock_registry = new stock_registry();
			$stock_registry->conn = $this->conn;

			$stock_registry->stock_id = $this->stock_id;

			$stock_registry->purge();

			
			if ($stock_registry->data['error']) {
				$this->data['error'] = true;
				$this->data['msg'] = $stock_registry->data['msg'];

				$qry = $this->conn->Query("ROLLBACK");

				return false;
			}
			else
				$this->data['msg'] .= $stock_registry->data['msg'];


			/*
				delete corresponding images
			*/
			$stock_images = new stock_images();
			$stock_images->conn = $this->conn;

			$stock_images->stock_id = $this->stock_id;

			$stock_images->purge();

			if ($stock_images->data['error']) {
				$this->data['error'] = true;
				$this->data['msg'] = $stock_images->data['msg'];

				$qry = $this->conn->Query("ROLLBACK");

				return false;
			}
			else
				$this->data['msg'] .= $stock_images->data['msg'];


			/*
				delete stock product
			*/
		    $sql = "
		    	DELETE FROM stock
		    	WHERE stock_id = ".$this->stock_id;

		    $qry = $this->conn->Query($sql);

			if (!$qry) {

				$this->data['error'] = true;
				$this->data['msg'] = addslashes($conn->error); //$sql;

				$qry = $this->conn->Query("ROLLBACK");

			}
			else {

				$this->data['error'] = false;
				$this->data['msg'] .= 'successfully deleted';


				$qry = $this->conn->Query("COMMIT");

			} // if (!qry)


		}		
		else {

			$this->data['error'] = true;
			$this->data['msg'] = 'Stock entry not found';

		}

		return true;

	} // delete


	public function update_current_stock($quantity, $movement_type=_movement_type_receive_, $movement_reference='', $registry_type=_registry_type_received_)
	{

	    $sql = "
	    	SELECT *
	    	FROM stock s
	    	WHERE stock_id = ".$this->stock_id;

	    $qry = $this->conn->Query($sql);

	    if (!$qry) {

			$this->data['error'] = true;
			$this->data['msg'] = $this->conn->error;

	    }
	    else {

	    	$obj = $qry->fetch_object();

	    	$comment = '';

	    	if ($movement_type == _movement_type_receive_) 
	    	{
	    		$current_stock = $obj->current_stock + $quantity;
			}
	    	elseif ($movement_type == _movement_type_deliver_)
	    	{
	    		$current_stock = $obj->current_stock - $quantity;
			}
    		else {
	    		$current_stock = $obj->current_stock;
				$comment = "error - ";
			}


	    	if (!empty($movement_reference))
	    		$comment .= $movement_reference;


		    $sql = "
		    	UPDATE stock
		    	SET 
		    		current_stock = '".mysqli_real_escape_string($this->conn, $current_stock)."'
		    	WHERE stock_id = ".$this->stock_id;

		    $qry = $this->conn->Query($sql);

			if (!$qry) {

				$this->data['error'] = true;
				$this->data['msg'] = $this->conn->error.":".$sql;

			}
			else
			{

				$stock_registry = new stock_registry();
				$stock_registry->conn = $this->conn;
				$stock_registry->stock_id = $this->stock_id;
				$stock_registry->quantity = $quantity;
				$stock_registry->date = date('Y-m-d H:i:s');
				$stock_registry->comment = $comment;
				$stock_registry->entry_type = $registry_type;

				$stock_registry->update();

				$this->data['error'] = $stock_registry->data['error'];
				$this->data['msg'] = $stock_registry->data['msg'];

			} // if (!qry)

		}

		return true;

	}


	public function get_data()
	{

		$rows = array();
		$recordsTotal = 0;
		$recordsFiltered = 0;
		$error = '';


		/*
			get the minimum stock limit 
			from the settings table
		*/
		$sql = "SELECT * FROM settings LIMIT 1";
		$qry = $this->conn->Query($sql);
		$obj = $qry->fetch_object();
		$minimum_quantity = $obj->minimum_quantity;


		$sql_limit = "LIMIT ".$this->start.", ".$this->length." ";


		if ($this->order_column==0)
			$this->order_column = 'p.description';
		elseif ($this->order_column==1)
			$this->order_column = 'description';

		$this->order_dir = ($this->order_dir=='asc')?"ASC":"DESC";
		$sql_order = "ORDER BY ".$this->order_column." ".$this->order_dir;


		$search=false;
		$sql_where='';
		$sql_having='';
		$sql_join = "
			LEFT JOIN product p ON (p.product_id = s.product_id)
			LEFT JOIN glaze g ON (g.glaze_id = s.glaze_id)
			LEFT JOIN design d ON (d.design_id = s.design_id)
		";

/*
		$this->search = $this->search['value'];

		if ((!empty($this->search)) && (strlen($this->search)>1)) {
			$sql_where = "
				WHERE ( 
					(p.code LIKE '%".$this->search."%') OR (p.description LIKE '%".$this->search."%') 
					OR (g.code LIKE '%".$this->search."%') OR (g.description LIKE '%".$this->search."%') 
					OR (d.code LIKE '%".$this->search."%') OR (d.description LIKE '%".$this->search."%') 
				)
			";
		}
*/

		if (isset($this->columns)) 
		{
			if (!empty($this->columns[0]['search']['value'])) 
			{

				$arr = explode('-', $this->columns[0]['search']['value']);

				$sql_where = " WHERE ";

				for ($i=0;$i<count($arr);$i++) 
				{
					if ($i==0)
						$sql_where .= " ( (p.code LIKE '%".$arr[$i]."%') OR (g.code LIKE '%".$arr[$i]."%') OR (d.code LIKE '%".$arr[$i]."%') )";
					else
						$sql_where .= " AND ( (p.code LIKE '%".$arr[$i]."%') OR (g.code LIKE '%".$arr[$i]."%') OR (d.code LIKE '%".$arr[$i]."%') )";
				}

			}
			elseif (!empty($this->columns[1]['search']['value'])) 
			{
				$arr = explode(' ', $this->columns[1]['search']['value']);

				$sql_where = " WHERE ";

				for ($i=0;$i<count($arr);$i++) 
				{
					if ($i==0)
						$sql_where .= " ( (p.description LIKE '%".$arr[$i]."%') OR (g.description LIKE '%".$arr[$i]."%') OR (d.description LIKE '%".$arr[$i]."%') )";
					else
						$sql_where .= " AND ( (p.description LIKE '%".$arr[$i]."%') OR (g.description LIKE '%".$arr[$i]."%') OR (d.description LIKE '%".$arr[$i]."%') )";
				}
			}
		}


		if (!empty($this->inventory_filter)) {

			if ($this->inventory_filter =='__ALL_')
				$sql_where .= '';

			if ($this->inventory_filter =='__BELOW_MIN_')
				if (!empty($sql_where))
					$sql_where .= " AND (current_stock < $minimum_quantity) ";
				else
					$sql_where = " WHERE (current_stock < $minimum_quantity) ";

			if ($this->inventory_filter =='__NONE_ZERO_')
				if (!empty($sql_where))
					$sql_where .= " AND (current_stock > 0) ";
				else
					$sql_where = " WHERE (current_stock > 0) ";

			elseif ($this->inventory_filter =='__ZERO_')
				if (!empty($sql_where))
					$sql_where .= " AND (current_stock = 0) ";
				else
					$sql_where = " WHERE (current_stock = 0) ";

		}


		$sql_total = "SELECT s.stock_id FROM stock s $sql_join $sql_where $sql_having";
		
		$qry = $this->conn->Query($sql_total);

		if ($qry) {
			$recordsTotal = $qry->num_rows;
			$recordsFiltered = $qry->num_rows;
		}
		else {
			$this->data['error'] = true;
			$this->data['msg'] = $this->conn->error;
		}

		$sql = "
			SELECT s.*, p.height, p.width, p.weight, p.volume,
				CONCAT_WS('-', p.code, g.code, d.code) AS code,
				CONCAT(p.description, ' ', g.description, ' ', d.description) AS description,
	            (
	            	SELECT filename
	            	FROM stock_images si
	            	WHERE (si.stock_id = s.stock_id)
	            	LIMIT 1
	            ) as image
			FROM stock s
			$sql_join 
			$sql_where
			$sql_having
			$sql_order
			$sql_limit
		";


		$this->inventory_sql = $sql;
		$this->inventory_search = $_GET['search']['value'];


		$qry = $this->conn->Query($sql);
		if (!$qry) {
			$this->data['error'] = true;
			$this->data['msg'] = $this->conn->error;
		}
		else {

			if ($qry->num_rows > 0) {

				$i=0;
				while( $obj = $qry->fetch_object() ) {

					$rows[$i]['DT_RowId'] = $obj->stock_id;
					$rows[$i]['code'] = $obj->code;
					$rows[$i]['description'] = htmlentities($obj->description);
					$rows[$i]['stock'] = $obj->current_stock;
					$rows[$i]['height'] = $obj->height;
					$rows[$i]['width'] = $obj->width;
					$rows[$i]['weight'] = $obj->weight;
					$rows[$i]['volume'] = $obj->volume;
					$rows[$i]['image'] = $obj->image;

					$i++;
				}
			}
		}

		$this->data = array(
			'draw'				=> $this->draw,
			'recordsTotal'		=> $recordsTotal,
			'recordsFiltered'	=> $recordsFiltered,
			'data'				=> $rows,
			'error'				=> $error
		);

	} // get_data

}


/*


								class stock_registry
								

*/
class stock_registry {
 
	public $stock_registry_id;
	public $stock_id;
	public $quantity;
	public $date;
	public $comment;
	public $entry_type;

	public $data = array();

	public $conn;

	public $draw;
	public $start;
	public $length;
	public $order_column;
	public $order_dir;
	public $search;	

	function __construct()
	{
		unset($this->data);

		$this->data['error'] = false;
		$this->data['msg'] = 'init';
		
		$this->stock_registry_id = 0;
		$this->stock_id = 0;
		$this->quantity = 0;
		$this->date = '';
		$this->comment = '';
		$this->entry_type = 1;

	}


	public function update()
	{

		$sql = "
			INSERT INTO stock_registry
			(
				stock_id,
				quantity,
				`date`,
				comment,
				entry_type
			)
			VALUES (
				'".mysqli_real_escape_string($this->conn, $this->stock_id)."',
				'".mysqli_real_escape_string($this->conn, $this->quantity)."',
				'".mysqli_real_escape_string($this->conn, $this->date)."',
				'".mysqli_real_escape_string($this->conn, $this->comment)."',
				'".mysqli_real_escape_string($this->conn, $this->entry_type)."'
			)
		";

		$qry = $this->conn->Query($sql);

		if (!$qry) {
			$this->data['error'] = true;
			$this->data['msg'] = $this->conn->error; //.":".$sql;
		}
		else {
			$this->stock_registry_id = $this->conn->insert_id;

			$this->data['error'] = false;
			$this->data['msg'] = "insert id: ".$this->conn->insert_id;
		}

		return true;		
	}

	public function purge()
	{
		$sql = "
			SELECT stock_registry_id
			FROM stock_registry
			WHERE stock_id = ".$this->stock_id;

		$qry = $this->conn->Query($sql);

		if ($qry->num_rows > 0) {

		    $sql = "
		    	DELETE FROM stock_registry
		    	WHERE stock_id = ".$this->stock_id;

		    $qry = $this->conn->Query($sql);

			if (!$qry) {

				$this->data['error'] = true;
				$this->data['msg'] = addslashes($conn->error);

			}
			else {

				$this->data['error'] = false;
				$this->data['msg'] = 'successfully purged stock_registry';

			} // if (!qry)
		}

		return true;

	} // purge


	public function get_data()
	{

		$rows = array();
		$recordsTotal = 0;
		$recordsFiltered = 0;
		$error = '';

		$sql_where=" WHERE sr.stock_id = ".$this->stock_id;
		$sql_join = "";

		$sql_limit = "LIMIT ".$this->start.", ".$this->length." ";

		if ($this->order_column==0)
			$this->order_column = 'date';

		$this->order_dir = ($this->order_dir=='asc')?"ASC":"DESC";
		$sql_order = "ORDER BY ".$this->order_column." ".$this->order_dir;

		$this->search = $this->search['value'];

		// if ((!empty($this->search)) && (strlen($this->search)>1))
		// 	$sql_where = "WHERE ( (g.code LIKE '%".$this->search."%') OR (g.description LIKE '%".$this->search."%') ) ";



		// get total number of records
		$sql_total = "SELECT sr.stock_registry_id FROM stock_registry sr $sql_join $sql_where";
		
		$qry = $this->conn->Query($sql_total);

		if ($qry) {
			$recordsTotal = $qry->num_rows;
			$recordsFiltered = $qry->num_rows;
		}
		else {
			$this->data['error'] = true;
			$this->data['msg'] = $this->conn->error;
			//$error = $this->conn->error;//."\n".$sql;
		}

		$sql = "
			SELECT sr.*
			FROM stock_registry sr
			$sql_join 
			$sql_where
			$sql_order
			$sql_limit
		";

		$qry = $this->conn->Query($sql);
		if (!$qry) {
			$this->data['error'] = true;
			$this->data['msg'] = $this->conn->error;
//			$error = $this->conn->error;//."\n".$sql;
		}
		else {

			if ($qry->num_rows > 0) {

				$i=0;
				while( $obj = $qry->fetch_object() ) {

					$rows[$i]['DT_RowId'] = $obj->stock_registry_id;
					$rows[$i]['date'] = set_formatted_date($obj->date,'-',true);
					$rows[$i]['quantity'] = $obj->quantity;
					$rows[$i]['comment'] = htmlentities($obj->comment);

					if ($obj->entry_type == _registry_type_received_)
						$rows[$i]['entry_type'] = 'received';
					elseif ($obj->entry_type == _registry_type_delivered_)
						$rows[$i]['entry_type'] = 'delivered';
					elseif ($obj->entry_type == _registry_type_cancelled_)
						$rows[$i]['entry_type'] = 'cancelled';

					$i++;
				}
			}
		}

		$this->data = array(
			'draw'				=> $this->draw,
			'recordsTotal'		=> $recordsTotal,
			'recordsFiltered'	=> $recordsFiltered,
			'data'				=> $rows,
			'error'				=> $error
		);

	} // get_data		

}


/*


								class stock_images


*/
class stock_images {

	public $id;
	public $stock_id;
	public $filename;
	public $files; /* this is the file data ($_FILES) passed for the image upload */
	public $data = array();

	public $conn;


	function __construct()
	{
		unset($this->data);

		$this->data['error'] = false;
		$this->data['msg'] = 'init';

		$this->id = 0;
		$this->stock_id = 0;
		$this->filename = 0;
	}


	public function get_data()
	{

	    $sql = "
	    	SELECT s.stock_id,
	    		CONCAT_WS(p.code, g.code, d.code) AS code,
				CONCAT(p.description, ' ', g.description, ' ', d.description) AS description
	    	FROM stock s
			LEFT JOIN product p ON (p.product_id = s.product_id)
			LEFT JOIN glaze g ON (g.glaze_id = s.glaze_id)
			LEFT JOIN design d ON (d.design_id = s.design_id)
	    	WHERE s.stock_id = ".$this->stock_id;

	    $qry = $this->conn->Query($sql);

		if (!$qry) {

			$this->data['error'] = true;
			$this->data['msg'] = addslashes($this->conn->error);

		}
		else {

			$obj = $qry->fetch_object();

			$this->data['title'] = htmlentities($obj->description);
			$this->data['stock_id'] = $this->stock_id;

		    $sql = "
		    	SELECT *
		    	FROM stock_images si
		    	WHERE si.stock_id = ".$this->stock_id;

		    $qry = $this->conn->Query($sql);

			if (!$qry) {

				$this->data['error'] = true;
				$this->data['msg'] = addslashes($this->conn->error);

			}
			else {

				if ($qry->num_rows > 0) {

					while ($obj = $qry->fetch_object()) {

						$this->data['images'][$obj->id]['id'] = $obj->id;
						$this->data['images'][$obj->id]['filename'] = $obj->filename;
					}

				} // if num_rows

			} // if (!qry)

		} // if (!qry)
		
		return true;

	}



	public function upload() 
	{


	}



	public function delete()
	{

		$sql = "
			SELECT *
			FROM stock_images
			WHERE id = ".$this->id;

		$qry = $this->conn->Query($sql);

		if ($qry->num_rows > 0) {
			
			$obj = $qry->fetch_object();

			if(!unlink("../images/".$obj->filename)) {

				$this->data['msg'] .= ' Could not delete the image file '.$obj->filename;

			}
			else
				$this->data['msg'] .= ' Image '.$obj->filename.' deleted ';

		    $sql = "
		    	DELETE FROM stock_images
		    	WHERE id = ".$this->id;

		    $qry = $this->conn->Query($sql);

			if (!$qry) {

				$this->data['error'] = true;
				$this->data['msg'] = addslashes($this->conn->error);

			}
			else {

				$this->data['error'] = false;
				$this->data['msg'] .= ' Successfully delete the stock image';

			} // if (!qry)
		}

		return true;

	}


	public function purge()
	{
		$sql = "
			SELECT *
			FROM stock_images
			WHERE stock_id = ".$this->stock_id;

		$qry = $this->conn->Query($sql);

		if ($qry->num_rows > 0) {
			
			while ($obj = $qry->fetch_object()) {

				if(!unlink("../images/".$obj->filename)) {

					$this->data['msg'] .= ' Could not delete the image file '.$obj->filename;

				}

			}

		    $sql = "
		    	DELETE FROM stock_images
		    	WHERE stock_id = ".$this->stock_id;

		    $qry = $this->conn->Query($sql);

			if (!$qry) {

				$this->data['error'] = true;
				$this->data['msg'] = addslashes($this->conn->error);

			}
			else {

				$this->data['error'] = false;
				$this->data['msg'] .= ' Successfully purged stock_images';

			} // if (!qry)
		}

		return true;
	}

}


?>