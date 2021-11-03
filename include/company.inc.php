<?php


class company {
 
	public $company_id;
	public $legal_name;
	public $trade_name;
	public $branch;
	public $address;
	public $phone;
	public $gstin;

	public $data = array();

	public $conn;

	function __construct()
	{
		unset($this->data);

		$this->company_id = 0;
		$this->legal_name = '';
		$this->trade_name = '';
		$this->branch = '';
		$this->address = '';
		$this->phone = '';
		$this->gstin = '';

		$this->data['error'] = false;
		$this->data['msg'] = 'init';

	} // construct



	public function get() 
	{
	    $sql = "
	    	SELECT *
	    	FROM company
		";
	    $qry = $this->conn->Query($sql);

		if (!$qry) {
			
			$this->data['error'] = true;
			$this->data['msg'] = $conn->error;

			$this->company_id = 0;
			$this->legal_name = '';
			$this->trade_name = '';
			$this->branch = '';
			$this->address = '';
			$this->phone = '';
			$this->gstin = '';

			//$error = $conn->error."\n".$sql;

		}
		else {

			if ($qry->num_rows > 0) {

				$obj = $qry->fetch_object();

				$this->company_id = $obj->company_id;
				$this->legal_name = $obj->legal_name;
				$this->trade_name = $obj->trade_name;
				$this->branch = $obj->branch;
				$this->address = $obj->address;
				$this->phone = $obj->phone;
				$this->gstin = $obj->gstin;

			} // if num_rows

		} // if (!qry)

		return true;

	} // get



/*
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
*/


	public function update() 
	{

	    $sql = "
	    	UPDATE company
	    	SET 
	    		legal_name = '".mysqli_real_escape_string($this->conn, $this->legal_name)."',
				trade_name = '".mysqli_real_escape_string($this->conn, $this->trade_name)."',
				branch = '".mysqli_real_escape_string($this->conn, $this->branch)."',
				address = '".mysqli_real_escape_string($this->conn, $this->address)."',
				phone = '".mysqli_real_escape_string($this->conn, $this->phone)."',
				gstin = '".mysqli_real_escape_string($this->conn, $this->gstin)."'
	    	WHERE company_id = ".$this->company_id;
	    $qry = $this->conn->Query($sql);

		if (!$qry) {

			$this->data['error'] = true;
			$this->data['msg'] = $this->conn->error;

		}
		else{

			$this->data['error'] = false;
			$this->data['msg'] = 'successfully updated'.$sql;

		} // if (!qry)

		return true;

	} // update


/*
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

*/




} // class product