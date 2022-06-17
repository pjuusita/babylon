<?php


class WarehouseHall {
	
	public $hallID;
	public $companyID;
	public $name;
	public $abbreviation;
	
	public $height;
	public $length;
	public $width;
	
	public $streetaddress;
	public $mailingaddress;
	public $postalcode;
	public $postalplace;
	
	public $added;
	public $adderID;
	public $removed;
	public $removerID;



	public function __construct($row) {

		if ($row != null) {

			$this->hallID = $row['HallID'];
			$this->companyID = $row['CompanyID'];
			$this->name = $row['Name'];
			$this->abbreviation = $row['Abbreviation'];

			$this->length = $row['Length'];
			$this->height = $row['Height'];
			$this->width = $row['Width'];

			$this->streetaddress = $row['Streetaddress'];
			$this->mailingaddress = $row['Mailingaddress'];
			$this->postalcode = $row['Postalcode'];
			$this->postalplace = $row['Postalplace'];
						
			$this->added = $row['Added'];
			$this->adderID = $row['AdderID'];
			$this->removed = $row['Removed'];
			$this->removerID = $row['RemoverID'];
		}
	}


	public function getID() {
		return $this->hallID;
	}


	public function getName() {
		return $this->name;
	}
	
	
	public static function loadHalls() {
	
		global $mysqli;
		$items = array();
		
		$sql = "SELECT * FROM warehouse_halls WHERE Removed='0000-00-00 00:00:00' ORDER BY Name";
		$result = $mysqli->query($sql);
		if (!$result) die('loadHalls failed: ' . $mysqli->connect_error);
	
		while($row = $result->fetch_array()) {
			$hall = new WarehouseHall($row);
			$items[$hall->hallID] = $hall;
		}
	
		return $items;
	}
	

	public static function loadHall($hallID) {

		global $mysqli;
		$sql = "SELECT * FROM warehouse_halls WHERE HallID='" . $hallID . "'";
		$result = $mysqli->query($sql);
		if (!$result) die('loadHall failed: ' . $mysqli->connect_error);
		$row = $result->fetch_array();
		$item = new WarehouseHall($row);
		return $item;
	}
	
}


?>