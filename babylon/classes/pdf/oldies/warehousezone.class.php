<?php


class WarehouseZone {
	
	public $zoneID;
	public $hallID;
	public $type;
	public $cluster;
	
	public $name;
	public $abbreviation;
	public $description;

	public $posX;
	public $posY;
	
	public $length;
	public $height;
	public $width;
	
	public $added;
	public $adderID;
	public $removed;
	public $removerID;



	public function __construct($row) {

		if ($row != null) {

			$this->zoneID = $row['ZoneID'];
			$this->hallID = $row['HallID'];
			$this->type = $row['Type'];
			$this->cluster = $row['Cluster'];
			
			$this->name = $row['Name'];
			$this->abbreviation = $row['Abbreviation'];
			$this->description = $row['Description'];

			$this->posX= $row['PosX'];
			$this->posY = $row['PosY'];
				
			$this->length = $row['Length'];
			$this->height = $row['Height'];
			$this->width = $row['Width'];
						
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


	
	public static function loadZonesFromHall($hallID) {
	
		global $mysqli;
		$items = array();
		
		$sql = "SELECT * FROM warehouse_zones WHERE HallID='" . $hallID . "' AND Removed='0000-00-00 00:00:00'";
		$result = $mysqli->query($sql);
		if (!$result) die('load ZonesFromHall failed: ' . $mysqli->connect_error);
	
		while($row = $result->fetch_array()) {
			$item = new WarehouseZone($row);
			$items[$item->zoneID] = $item;
		}
	
		return $items;
	}
}


?>