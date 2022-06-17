<?php


class StructureController extends AbstractController {

	
	
	public function getCSSFiles() {
		return array('menu.css', 'main.css','mytheme/jquery-ui.css','yritys.css','prism.css','chosen.css');
	}
	

	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','jquery.imagemapster.js','chosen.jquery.js','prism.js');
	}
	
	
	public function indexAction() {
		//$this->registry->loadParams();
		//$this->registry->template->header = 'Hallit';
		//$this->registry->template->show('warehouse/structure','warehousetab');
		$this->registry->template->show('system/error','unknown');
	}
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showhallsAction() {
		
	}
	
	
	
	public function showtabAction() {
		
		$this->registry->loadParams();
		$tabID = $_GET['tab'];
		
		if ($tabID == 1) {
			$this->registry->hall = WarehouseHall::loadHall(1);
			$this->registry->zones = WarehouseZone::loadZonesFromHall(1);
			$this->registry->template->show('warehouse/structure','warehousemap');
		}
		
		if ($tabID == 2) {
			$this->registry->template->show('warehouse/structure','warehouseracks');
		}

		if ($tabID == 3) {
			$this->registry->template->show('warehouse/structure','warehouserows');
		}
		
		if ($tabID == 4) {
			$this->registry->template->show('warehouse/structure','warehousecolumns');
		}
		
		if ($tabID == 5) {
			$this->registry->halls = WarehouseHall::loadHalls();
			$this->registry->template->show('warehouse/structure','warehousehalls');
		}
	}
	
	
	public function mapimageAction() {
		
		$hallID = $_GET['hallid'];
		$this->registry->hallID = $hallID;
		
		$shade = $_GET['shade'];
		$this->registry->shade = $shade;
		
		$this->registry->hall = WarehouseHall::loadHall(1);
		$this->registry->zones = WarehouseZone::loadZonesFromHall($hallID);
		$this->registry->template->show('warehouse/structure','mapimage');
	}
	
	
	public function emapimageAction() {
	
		$hallID = $_GET['hallid'];
		$this->registry->hallID = $hallID;
	
		$shade = $_GET['shade'];
		$this->registry->shade = $shade;
	
		$this->registry->hall = WarehouseHall::loadHall(1);
		$this->registry->zones = WarehouseZone::loadZonesFromHall($hallID);
		$this->registry->template->show('warehouse/structure','mapimage2');
	}
	
}

?>
