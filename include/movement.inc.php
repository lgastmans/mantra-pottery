<?php

require_once("functions.inc.php");
require_once("session.php");
require_once("stock.inc.php");
require_once("company.inc.php");

require_once("../fpdf/fpdf.php");


/*

	class movement

	class movement_items

*/



class PDF extends FPDF
{
	
	function Header()
	{
	    $this->SetFont('Arial','B',10);
	    // Move to the right
	    $this->Cell(80);

    	if ($_SESSION['movement_type'] == 1)
    		$this->Cell(30,10,' Receipt',0,0,'C');
    	else
    		$this->Cell(30,10,' Delivery ',0,0,'C');
	    $this->Ln(10);

	    $this->SetFont('Arial','B',14);
	    $this->Cell(80);
	    $this->Cell(30,10,$this->company->legal_name,0,0,'C');
	    $this->Ln(5);

		$this->SetFont('Arial','',12);
	    $this->Cell(80);
	    $this->Cell(30,10,$this->company->trade_name." - ".$this->company->branch,0,0,'C');
	    $this->Ln(5);
	    $this->Cell(80);
	    $this->Cell(30,10,"GSTIN: ".$this->company->gstin,0,0,'C');
	    $this->Ln(5);
	    $this->Cell(80);
	    $this->Cell(30,10,$this->company->address,0,0,'C');
	    $this->Ln(5);
	    $this->Cell(80);
	    $this->Cell(30,10,$this->company->phone,0,0,'C');
	    $this->Ln(5);

	    $this->Ln(5);
	}

	// Page footer
	function Footer()
	{
	    // Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',10);
	    // Page number
	    $this->Cell(0,10,'footer text',0,0,'C');
	}
}






class movement {
 
	public $stock_movement_id;
	public $source_id;
	public $reference;
	public $movement_type;
	public $date;
	public $status;

	public $source_name;
	public $source_address;
	public $source_phone;
	public $source_gstin;

	public $items = array();

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

		$this->stock_movement_id = 0;
		$this->source_id = 0;
		$this->reference = '';
		$this->movement_type = 0;
		$this->date = '';
		$this->status = 0;

		$this->source_name='';
		$this->source_address='';
		$this->source_phone='';
		$this->source_gstin='';

		unset($this->items);


		$this->data['error'] = false;
		$this->data['msg'] = 'init';

		$this->draw;
		$this->start;
		$this->length;
		$this->order_column;
		$this->order_dir;
		$this->search;
	}


	public function get() 
	{
	    $sql = "
	    	SELECT *
	    	FROM stock_movement sm
	    	LEFT JOIN source s ON (s.source_id = sm.source_id)
	    	WHERE stock_movement_id = ".$this->stock_movement_id;
	    $qry = $this->conn->Query($sql);

		if (!$qry) {
			
			$this->data['error'] = true;
			$this->data['msg'] = $conn->error;

			$this->stock_movement_id = 0;
			$this->source_id = 0;
			$this->reference = '';
			$this->movement_type = 0;
			$this->date = '';
			$this->status = 0;

			$this->source_name='';
			$this->source_address='';
			$this->source_phone='';
			$this->source_gstin='';

			unset($this->items);

			return false;

		}
		else {

			if ($qry->num_rows > 0) {

				$obj = $qry->fetch_object();

				$this->source_id = $obj->source_id;
				$this->reference = $obj->reference;
				$this->movement_type = $obj->movement_type;
				$this->date = $obj->date;
				$this->status = $obj->status;

				$this->source_name = $obj->name;
				$this->source_address = $obj->address;
				$this->source_phone = $obj->phone;
				$this->source_gstin = $obj->gstin;


				$this->data['error'] = false;
				$this->data['msg'] = "Movement data loaded";

				return true;

			} // if num_rows
			else 
			{

				$this->data['error'] = true;
				$this->data['msg'] = "Movement not found";

				return false;
			}

		} // if (!qry)

	} // get


	public function insert()
	{

		$sql = "
			INSERT INTO stock_movement
			(
				source_id,
				reference,
				movement_type,
				`date`,
				status
			)
			VALUES (
				'".mysqli_real_escape_string($this->conn, $this->source_id)."',
				'".mysqli_real_escape_string($this->conn, $this->reference)."',
				'".mysqli_real_escape_string($this->conn, $this->movement_type)."',
				'".mysqli_real_escape_string($this->conn, $this->date)."',
				'".mysqli_real_escape_string($this->conn, $this->status)."'
			)
		";

		$qry = $this->conn->Query($sql);

		if (!$qry) {

			$this->data['error'] = true;
			$this->data['msg'] = $this->conn->error; //.":".$sql;

			return false;
		}
		else {
			$this->stock_movement_id = $this->conn->insert_id;

			$this->data['error'] = false;
			$this->data['msg'] = "insert_id: ".$this->conn->insert_id;
		}

		return true;

	} // insert


	public function cancel()
	{

		/*
			abort if status is already set to cancelled
		*/
		$abort = true;

		$sql = "
			SELECT *
			FROM stock_movement
			WHERE stock_movement_id = ".$this->stock_movement_id;

		$qry = $this->conn->Query($sql);

		if (!$qry) {

			$this->data['error'] = true;
			$this->data['msg'] = $this->conn->error; //.":".$sql;

			return false;
		}
		else {

			if ($qry->num_rows > 0)
			{
				$obj = $qry->fetch_object();

				if ($obj->status==_movement_status_cancelled_) 
				{

					$this->data['error'] = true;
					$this->data['msg'] = "This entry is already marked cancelled.";

				}
				else
				{
					$abort = false;

					$this->data['error'] = false;
					$this->data['msg'] = "Entry can be cancelled";

				}
			}
			else
			{
				$this->data['error'] = true;
				$this->data['msg'] = "Entry with id ".$this->stock_movement_id." not found";
			}
		}


		if (!$abort)
		{

			/*
				set the details of the stock movement
			*/
			$this->get();

			/*
				set whether stock should be added or deducted
			*/
			$action = _movement_type_receive_;
			if ($this->movement_type == _movement_type_receive_)
				$action = _movement_type_deliver_;

			/*
				get the items
			*/		
			$item = new movement_items();
			$item->conn = $this->conn;
			$item->stock_movement_id = $this->stock_movement_id;
			$item->get_all();

			/*
				for each item, update the current stock
				(registry gets updated automatically)
			*/
			if ((isset($item->items)) || (!empty($item->items))) {

				$stock = new Stock();
				$stock->conn = $this->conn;

				foreach ($item->items as $row)
				{

					$stock->stock_id = $row['stock_id'];
					$stock->update_current_stock($row['quantity'], $action, $this->reference, _registry_type_cancelled_);

				}
			}

			/*
				mark the stock movement status as "cancelled"
			*/
			$sql = "
				UPDATE stock_movement
				SET	status = '"._movement_status_cancelled_."'
				WHERE stock_movement_id = ".$this->stock_movement_id;

			$qry = $this->conn->Query($sql);

			if (!$qry) {

				$this->data['error'] = true;
				$this->data['msg'] = $this->conn->error; //.":".$sql;

				return false;
			}
			else {

				$this->data['error'] = false;
				$this->data['msg'] = "status set to cancelled ";
			}

		} // if (!$abort)

	}


	public function save()
	{

		/*
			insert row in rts table
		*/
		if ($this->insert()==false) {

			return $this->data;

		}


		/*
			insert row in rts items table
		*/
		$item = new movement_items();
		$item->conn = $this->conn;

		foreach ($this->items as $row) {

			$item->stock_movement_id = $this->stock_movement_id;
			$item->stock_id = $row['stock_id'];
			$item->quantity = $row['quantity'];

			$item->movement_reference = $this->reference;

			if ($this->movement_type == _movement_type_receive_)
				$item->registry_entry_type = _registry_type_received_;
			elseif ($this->movement_type == _movement_type_deliver_)
				$item->registry_entry_type = _registry_type_delivered_;

			$item->insert();

		}

		if ($this->data['error']==false) {

			$this->data['error'] = $item->data['error'];
			$this->data['msg'] = $item->data['msg'];

		}

		return $this->data;

	}

	
	public function delete()
	{

		if ($this->get())
		{

			/*
				delete corresponding items
			*/
			$item = new movement_items();
			$item->conn = $this->conn;
			$item->stock_movement_id = $this->stock_movement_id;			

			if ( $item->delete_all() ) {

				/*
					delete the stock_movement row
				*/
			    $sql = "
			    	DELETE FROM stock_movement
			    	WHERE stock_movement_id = ".$this->stock_movement_id;

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
			else
			{

				$this->data['error'] = $item->data['error'];
				$this->data['msg'] = $item->data['msg'];

			}
		}
		else {

			return false;

		}

		return true;

	}

	
	public function init_session_vars()
	{

		unset($_SESSION['movement_details']);
		unset($_SESSION['movement_items']);

		$_SESSION['movement_details'] = array();
		$_SESSION['movement_items'] = array();

		$this->data['error'] = false;
		$this->data['msg'] = "session variables initiated";

		return true;
	}


	public function view()
	{

		$company = new Company();
		$company->conn = $this->conn;
		$company->get();

		$this->get();

		$filename = "Mantra Pottery ".$this->reference.".pdf";


		$pdf = new PDF();
		$pdf->company = $company;


		$pdf->AliasNbPages();
		$pdf->AddPage();

	    define('LEN_SN', 10);
	    define('LEN_CODE', 30);
	    define('LEN_DESCRIPTION', 100);
	    define('LEN_QTY', 15);

//	    define('L_MARGIN',7);
//		$pdf->SetLeftMargin(L_MARGIN);

		$font_height = 11;

		$pdf->SetFont('Arial','',$font_height);

		$pdf->Cell(30,$font_height,'Reference:',0,0,'R');
		$pdf->Cell(40,$font_height,$this->reference,0,0,'L');
		$pdf->Ln(4);
		$pdf->Cell(30,$font_height,'Date:',0,0,'R');
		$pdf->Cell(40,$font_height,set_formatted_date($this->date),0,0,'L');
		$pdf->Ln(8);

		$pdf->Cell(30,$font_height,'Name:',0,0,'R');
		$pdf->Cell(40,$font_height,$this->source_name,0,0,'L');
		$pdf->Ln(4);
		$pdf->Cell(30,$font_height,'Address:',0,0,'R');
		$pdf->Cell(40,$font_height,$this->source_address,0,0,'L');
		$pdf->Ln(4);
		$pdf->Cell(30,$font_height,'Phone:',0,0,'R');
		$pdf->Cell(40,$font_height,$this->source_phone,0,0,'L');
		$pdf->Ln(4);
		$pdf->Cell(30,$font_height,'GSTIN:',0,0,'R');
		$pdf->Cell(40,$font_height,$this->source_gstin,0,0,'L');
		$pdf->Ln(20);


		$item = new movement_items();
		$item->conn = $this->conn;
		$item->stock_movement_id = $this->stock_movement_id;

		$item->get_all();
		

		$font_height = 9;
		$pdf->SetFont('Arial','B',$font_height);

	    $pdf->Cell(LEN_SN,$font_height,'sn',1,0,'L');
	    $pdf->Cell(LEN_CODE,$font_height,'code',1,0,'C');
	    $pdf->Cell(LEN_DESCRIPTION,$font_height,'description',1,0,'L');
	    $pdf->Cell(LEN_QTY,$font_height,'quantity',1,0,'R');
		$pdf->Ln($font_height);

		$font_height = 8;
		$pdf->SetFont('Arial','',$font_height);

		$h = 4;

		if ((isset($item->items)) || (!empty($item->items))) {

			foreach ($item->items as $row)
			{
			    
			    $pdf->Cell(LEN_SN,$h,$row['sn'],1,0,'R');
			    $pdf->Cell(LEN_CODE,$h,$row['code'],1,0,'C');
			    $pdf->Cell(LEN_DESCRIPTION,$h,$row['description'],1,0,'L');
			    $pdf->Cell(LEN_QTY,$h,$row['quantity'],1,0,'R');

				$pdf->Ln($h);
			}
		}
		else {

			$pdf->Cell(30,$font_height,'Items:',0,0,'R');
			$pdf->Cell(40,$font_height,"NONE FOUND",0,0,'L');
			$pdf->Ln(20);

		}





		$pdf->Output('I', $filename);

		$this->data['error'] = false;
		$this->data['msg'] = 'view';
	}


	public function get_data()
	{

		$rows = array();
		$recordsTotal = 0;
		$recordsFiltered = 0;
		$error = '';

		$sql_where='';
		$sql_join = " LEFT JOIN source s ON (m.source_id = s.source_id) ";

		$sql_limit = "LIMIT ".$this->start.", ".$this->length." ";

		if ($this->order_column==0)
			$this->order_column = 'reference';
		elseif ($this->order_column==1)
			$this->order_column = 's.name';
		elseif ($this->order_column==2)
			$this->order_column = '`date`';


		$this->order_dir = ($this->order_dir=='asc')?"ASC":"DESC";
		$sql_order = "ORDER BY ".$this->order_column." ".$this->order_dir;


		$this->search = $this->search['value'];

		$sql_where = "WHERE (movement_type = ".$_SESSION['movement_type'].") ";
		if ((!empty($this->search)) && (strlen($this->search)>1))
			$sql_where .= " AND ( (s.name LIKE '%".$this->search."%') OR (m.reference LIKE '%".$this->search."%') ) ";


		// get total number of records
		$sql_total = "SELECT m.stock_movement_id FROM stock_movement m $sql_join $sql_where";
		
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
			SELECT m.*, s.name
			FROM stock_movement m
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

					$rows[$i]['DT_RowId'] = $obj->stock_movement_id;
					$rows[$i]['source'] = $obj->name;
					$rows[$i]['reference'] = htmlentities($obj->reference);
					$rows[$i]['date'] = ($obj->date != '' ? set_formatted_date($obj->date) : '');
					$rows[$i]['status'] = ($obj->status==0 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Cancelled</span>');

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


class movement_items {

	public $stock_movement_item_id;
	public $stock_movement_id;
	public $stock_id;
	public $quantity;

	public $items = array();

	public $movement_reference;
	public $registry_entry_type;

	public $data = array();

	public $conn;


	function __construct()
	{

		$this->stock_movement_item_id = 0;
		$this->stock_movement_id = 0;
		$this->stock_id = 0;
		$this->quantity = 0;

		unset($this->items);

		$this->movement_reference = '';
		$this->registry_entry_type = 1;

		unset($this->data);

		$this->data['error'] = false;
		$this->data['msg'] = 'init';
	}


	public function get()
	{

	}


	public function get_all()
	{
		$sql = "
			SELECT *
			FROM stock_movement_items smi
			WHERE (smi.stock_movement_id = ".$this->stock_movement_id.")
		";

		$qry = $this->conn->Query($sql);

		//unset($this->items);

		if ($qry->num_rows > 0)
		{

			$stock = new Stock();
			$stock->conn = $this->conn;

			$i = 0;
			while ($obj = $qry->fetch_object())
			{
				$stock->stock_id = $obj->stock_id;
				$stock->get();

				$this->items[$i]['stock_movement_item_id'] = $obj->stock_movement_item_id;
				$this->items[$i]['stock_movement_id'] = $obj->stock_movement_id;
				$this->items[$i]['stock_id'] = $obj->stock_id;
				$this->items[$i]['quantity'] = $obj->quantity;

				$this->items[$i]['sn'] = ($i+1);
				$this->items[$i]['code'] = $stock->code;
				$this->items[$i]['description'] = $stock->description;


				$i++;
			}
		}
		else
			$this->items = 'No items: '.$sql;
	}


	public function insert()
	{

		$sql = "
			INSERT INTO stock_movement_items
			(
				stock_movement_id,
				stock_id,
				quantity
			)
			VALUES (
				'".mysqli_real_escape_string($this->conn, $this->stock_movement_id)."',
				'".mysqli_real_escape_string($this->conn, $this->stock_id)."',
				'".mysqli_real_escape_string($this->conn, $this->quantity)."'
			)
		";

		$qry = $this->conn->Query($sql);

		if (!$qry) {
			$this->data['error'] = true;
			$this->data['msg'] = $this->conn->error; //.":".$sql;

			return false;
		}
		else {
			$this->stock_movement_id = $this->conn->insert_id;


			$stock = new Stock();
			$stock->conn = $this->conn;
			$stock->stock_id = $this->stock_id;

			$stock->update_current_stock($this->quantity, $_SESSION['movement_type'], $this->movement_reference, $this->registry_entry_type);

			$this->data['error'] = $stock->data['error'];
			$this->data['msg'] = $stock->data['msg'];

		}

		return true;

	}

	public function update()
	{

	}

	public function delete_all()
	{

		/*
			delete the stock_movement items
		*/
	    $sql = "
	    	DELETE FROM stock_movement_items
	    	WHERE stock_movement_id = ".$this->stock_movement_id;

	    $qry = $this->conn->Query($sql);

		if (!$qry) {

			$this->data['error'] = true;
			$this->data['msg'] = addslashes($conn->error); //$sql;

			return false;

		}
		else {

			$this->data['error'] = false;
			$this->data['msg'] = 'successfully deleted';

		} // if (!qry)


		return true;

	}


}