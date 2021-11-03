<?php


class product {
 
	public $product_id;
	public $code;
	public $description;
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

	
	function __construct()
	{
		unset($this->data);

		$this->product_id = 0;
		$this->code = '';
		$this->description = '';
		$this->height = '';
		$this->width = '';
		$this->weight = '';
		$this->volume = '';

		$this->data['error'] = false;
		$this->data['msg'] = 'init';

		$this->draw;
		$this->start;
		$this->length;
		$this->order_column;
		$this->order_dir;
		$this->search;
		
	} // construct



	public function get() 
	{
	    $sql = "
	    	SELECT *
	    	FROM product
	    	WHERE product_id = ".$this->product_id;
	    $qry = $this->conn->Query($sql);

		if (!$qry) {
			
			$this->data['error'] = true;
			$this->data['msg'] = $conn->error;

			$this->product_id = 0;
			$this->code = '';
			$this->description = '';
			$this->height = '';
			$this->width = '';
			$this->weight = '';
			$this->volume = '';

			//$error = $conn->error."\n".$sql;

		}
		else {

			if ($qry->num_rows > 0) {

				$obj = $qry->fetch_object();

				$this->product_id = $obj->product_id;
				$this->code = $obj->code;
				$this->description = $obj->description;
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
			INSERT INTO product
			(
				code,
				description,
				height,
				width,
				weight,
				volume
			)
			VALUES (
				'".mysqli_real_escape_string($this->conn, $this->code)."',
				'".mysqli_real_escape_string($this->conn, $this->description)."',
				'".mysqli_real_escape_string($this->conn, $this->height)."',
				'".mysqli_real_escape_string($this->conn, $this->width)."',
				'".mysqli_real_escape_string($this->conn, $this->weight)."',
				'".mysqli_real_escape_string($this->conn, $this->volume)."'
			)
		";

		$qry = $this->conn->Query($sql);

		if (!$qry) {
			$this->data['error'] = true;
			$this->data['msg'] = $this->conn->error; //.":".$sql;
		}
		else {
			$this->product_id = $this->conn->insert_id;

			$this->data['error'] = false;
			$this->data['msg'] = "insert id: ".$this->conn->insert_id;
		}

		return true;

	} // insert



	public function update() 
	{

	    $sql = "
	    	UPDATE product
	    	SET 
	    		code = '".mysqli_real_escape_string($this->conn, $this->code)."',
				description = '".mysqli_real_escape_string($this->conn, $this->description)."',
				height = '".mysqli_real_escape_string($this->conn, $this->height)."',
				width = '".mysqli_real_escape_string($this->conn, $this->width)."',
				weight = '".mysqli_real_escape_string($this->conn, $this->weight)."',
				volume = '".mysqli_real_escape_string($this->conn, $this->volume)."'
	    	WHERE product_id = ".$this->product_id;
	    $qry = $this->conn->Query($sql);

		if (!$qry) {

			$this->data['error'] = true;
			$this->data['msg'] = $this->conn->error;

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
			SELECT product_id
			FROM stock
			WHERE product_id = ".$this->product_id;

		$qry = $this->conn->Query($sql);

		if ($qry->num_rows > 0) {

			$this->data['error'] = true;
			$this->data['msg'] = 'Cannot delete: this product is present in stock';

		}		
		else {

		    $sql = "
		    	DELETE FROM product
		    	WHERE product_id = ".$this->product_id;

		    $qry = $this->conn->Query($sql);

			if (!$qry) {

				$this->data['error'] = true;
				$this->data['msg'] = addslashes($conn->error); //$sql;

			}
			else {

				$this->data['error'] = false;
				$this->data['msg'] = 'successfully deleted';

			} // if (!qry)
		}

		return true;

	} // delete



	public function get_typeahead()
	{
	    $sql = "
	    	SELECT *
	    	FROM product p
	    	ORDER BY description";
	    $qry = $this->conn->Query($sql);

		if (!$qry) {

			$this->data['error'] = true;
			$this->data['msg'] = $this->conn->error;
			//$error = $conn->error."\n".$sql;

		}
		else {

			if ($qry->num_rows > 0) {

				$i=0;
				while( $obj = $qry->fetch_object() ) {

					$data[$i]['product_id'] = $obj->product_id;
					$data[$i]['code'] = $obj->code;
					$data[$i]['description'] = $obj->description;

					$i++;

				} // while

			} // if num_rows
		}
		
		return true;

	} // typeahead



	public function get_data()
	{

		$rows = array();
		$recordsTotal = 0;
		$recordsFiltered = 0;
		$error = '';

		$sql_where='';
		$sql_join = "";

		$sql_limit = "LIMIT ".$this->start.", ".$this->length." ";

		if ($this->order_column==0)
			$this->order_column = 'code';
		elseif ($this->order_column==1)
			$this->order_column = 'description';


		$this->order_dir = ($this->order_dir=='asc')?"ASC":"DESC";
		$sql_order = "ORDER BY ".$this->order_column." ".$this->order_dir;


		$this->search = $this->search['value'];

		if ((!empty($this->search)) && (strlen($this->search)>1))
			$sql_where = "WHERE ( (p.code LIKE '%".$this->search."%') OR (p.description LIKE '%".$this->search."%') ) ";


		// get total number of records
		$sql_total = "SELECT p.product_id FROM product p $sql_join $sql_where";
		
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
			SELECT p.*
			FROM product p
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

					$rows[$i]['DT_RowId'] = $obj->product_id;
					$rows[$i]['code'] = $obj->code;
					$rows[$i]['description'] = htmlentities($obj->description);
					$rows[$i]['height'] = htmlentities($obj->height);
					$rows[$i]['width'] = htmlentities($obj->width);
					$rows[$i]['weight'] = htmlentities($obj->weight);
					$rows[$i]['volume'] = htmlentities($obj->volume);

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


} // class product