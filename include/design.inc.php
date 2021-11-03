<?php


class design {
 
	public $design_id;
	public $code;
	public $description;

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

		$this->design_id = 0;
		$this->code = '';
		$this->description = '';

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
	    	FROM design
	    	WHERE design_id = ".$this->design_id;
	    $qry = $this->conn->Query($sql);

		if (!$qry) {
			
			$this->data['error'] = true;
			$this->data['msg'] = $conn->error;

			$this->design_id = 0;
			$this->code = '';
			$this->description = '';

			//$error = $conn->error."\n".$sql;

		}
		else {

			if ($qry->num_rows > 0) {

				$obj = $qry->fetch_object();

				$this->design_id = $obj->design_id;
				$this->code = $obj->code;
				$this->description = $obj->description;

			} // if num_rows

		} // if (!qry)

		return true;

	} // get



	public function insert()
	{
		$sql = "
			INSERT INTO design
			(
				code,
				description
			)
			VALUES (
				'".mysqli_real_escape_string($this->conn, $this->code)."',
				'".mysqli_real_escape_string($this->conn, $this->description)."'
			)
		";

		$qry = $this->conn->Query($sql);

		if (!$qry) {
			$this->data['error'] = true;
			$this->data['msg'] = $this->conn->error; //.":".$sql;
		}
		else {
			$this->design_id = $this->conn->insert_id;

			$this->data['error'] = false;
			$this->data['msg'] = "insert id: ".$this->conn->insert_id;
		}

		return true;

	} // insert



	public function update() 
	{

	    $sql = "
	    	UPDATE design
	    	SET 
	    		code = '".mysqli_real_escape_string($this->conn, $this->code)."',
				description = '".mysqli_real_escape_string($this->conn, $this->description)."'
	    	WHERE design_id = ".$this->design_id;
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
			SELECT design_id
			FROM stock
			WHERE design_id = ".$this->design_id;

		$qry = $this->conn->Query($sql);

		if ($qry->num_rows > 0) {

			$this->data['error'] = true;
			$this->data['msg'] = 'Cannot delete: this design is present in stock';

		}		
		else {

		    $sql = "
		    	DELETE FROM design
		    	WHERE design_id = ".$this->design_id;

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
			$sql_where = "WHERE ( (d.code LIKE '%".$this->search."%') OR (d.description LIKE '%".$this->search."%') ) ";


		// get total number of records
		$sql_total = "SELECT d.design_id FROM design d $sql_join $sql_where";
		
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
			SELECT d.*
			FROM design d
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

					$rows[$i]['DT_RowId'] = $obj->design_id;
					$rows[$i]['code'] = $obj->code;
					$rows[$i]['description'] = htmlentities($obj->description);

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


} // class design