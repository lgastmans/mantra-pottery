<?php

require_once("functions.inc.php");

class source {
 
	public $source_id;
	public $type_id;
	public $name;
	public $address;
	public $phone;
	public $gstin;

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

		$this->source_id = 0;
		$this->type_id = 1;
		$this->name = '';
		$this->address = '';
		$this->phone = '';
		$this->gstin = '';

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
	    	FROM source
	    	WHERE source_id = ".$this->source_id;
	    $qry = $this->conn->Query($sql);

		if (!$qry) {
			
			$this->data['error'] = true;
			$this->data['msg'] = $conn->error;

			$this->source_id = 0;
			$this->type_id = 1;
			$this->name = '';
			$this->address = '';
			$this->phone = '';
			$this->gstin = '';

			//$error = $conn->error."\n".$sql;

			return false;

		}
		else {

			if ($qry->num_rows > 0) {

				$obj = $qry->fetch_object();

				$this->source_id = $obj->source_id;
				$this->type_id = $obj->type_id;
				$this->name = $obj->name;
				$this->address = $obj->address;
				$this->phone = $obj->phone;
				$this->gstin = $obj->gstin;

				$this->data['error'] = false;
				$this->data['msg'] = "Source data loaded";

			} // if num_rows
			else
			{

				$this->data['error'] = true;
				$this->data['msg'] = "Source not found";

				return false;

			}

		} // if (!qry)

		return true;

	} // get



	public function insert()
	{
		$sql = "
			INSERT INTO source
			(
				type_id,
				name,
				address,
				phone,
				gstin
			)
			VALUES (
				'".mysqli_real_escape_string($this->conn, $this->type_id)."',
				'".mysqli_real_escape_string($this->conn, $this->name)."',
				'".mysqli_real_escape_string($this->conn, $this->address)."',
				'".mysqli_real_escape_string($this->conn, $this->phone)."',
				'".mysqli_real_escape_string($this->conn, $this->gstin)."'
			)
		";

		$qry = $this->conn->Query($sql);

		if (!$qry) {
			$this->data['error'] = true;
			$this->data['msg'] = $this->conn->error; //.":".$sql;
		}
		else {
			$this->source_id = $this->conn->insert_id;

			$this->data['error'] = false;
			$this->data['msg'] = "insert id: ".$this->conn->insert_id;
		}

		return true;

	} // insert



	public function update() 
	{

	    $sql = "
	    	UPDATE source
	    	SET
	    		type_id = '".mysqli_real_escape_string($this->conn, $this->type_id)."',
	    		name = '".mysqli_real_escape_string($this->conn, $this->name)."',
				address = '".mysqli_real_escape_string($this->conn, $this->address)."',
				phone = '".mysqli_real_escape_string($this->conn, $this->phone)."',
				gstin = '".mysqli_real_escape_string($this->conn, $this->gstin)."'
	    	WHERE source_id = ".$this->source_id;
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
			SELECT source_id
			FROM stock_movement
			WHERE source_id = ".$this->source_id;

		$qry = $this->conn->Query($sql);

		if ($qry->num_rows > 0) {

			$this->data['error'] = true;
			$this->data['msg'] = 'Cannot delete: this source is present in stock movement';

		}		
		else {

		    $sql = "
		    	DELETE FROM source
		    	WHERE source_id = ".$this->source_id;

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



	public function get_data()
	{

		$rows = array();
		$recordsTotal = 0;
		$recordsFiltered = 0;
		$error = '';

		$sql_where = "";
		$sql_join = "";

		$sql_limit = "LIMIT ".$this->start.", ".$this->length." ";

		if ($this->order_column==0)
			$this->order_column = 'name';
		elseif ($this->order_column==1)
			$this->order_column = 'address';
		elseif ($this->order_column = 'phone')
			$this->order_column = 'phone';


		$this->order_dir = ($this->order_dir=='asc')?"ASC":"DESC";
		$sql_order = "ORDER BY ".$this->order_column." ".$this->order_dir;


		$this->search = $this->search['value'];

		if ((!empty($this->search)) && (strlen($this->search)>1))
			$sql_where = "WHERE ( (s.name LIKE '%".$this->search."%') OR (s.address LIKE '%".$this->search."%') ) ";


		// get total number of records
		$sql_total = "SELECT s.source_id FROM source s $sql_join $sql_where";
		
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
			SELECT *
			FROM source s
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

					$rows[$i]['DT_RowId'] = $obj->source_id;
					$rows[$i]['name'] = $obj->name;
					$rows[$i]['address'] = htmlentities($obj->address);
					$rows[$i]['phone'] = htmlentities($obj->phone);
					$rows[$i]['type'] = ($obj->type_id==_movement_type_receive_ ? "supplier" : "client");
					$rows[$i]['gstin'] = $obj->gstin;

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


} // class glaze