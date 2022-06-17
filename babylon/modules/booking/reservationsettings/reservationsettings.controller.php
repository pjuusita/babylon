<?php



class ReservationsettingsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}


	public function indexAction() {
		//$this->showsettingsAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showsettingsAction() {
		
		$this->registry->objects = Table::load('reservation_objects');
		$this->registry->template->show('reservation/reservationsettings','reservationobjects');
	}
	
	

	
	// TODO: This is Copy from Original source - edit or delete
	public function updatesettingsAction() {
	
		if (isset($_GET['productnumberused'])) {
			$settings = Table::load('system_settings');
	
			$rows = Table::load('system_settings'," WHERE name='system_settings_productnumberused'");
			if ($rows == null) {
				echo "<br>nullliiii";
			}
	
			if ($rows == null) {
				$insertarray = array();
				$insertarray['Name'] = 'system_settings_productnumberused';
				$insertarray['Value'] = $_GET['productnumberused'];
				$success = Table::addRow('system_settings',$insertarray,true);
			} else {
				$row = Table::loadRow('system_settings',"name='system_settings_productnumberused'", true);
				$insertarray = array();
				$insertarray['Name'] = 'system_settings_productnumberused';
				$insertarray['Value'] = $_GET['productnumberused'];
				$success = Table::updateRow('system_settings',$insertarray, $row->settingID, true);
			}
		}
		redirecttotal('sales/salessettings/showsettings', null);
	}
	
	
	
	public function insertobjectAction() {
		$values = array();
		$values['Name'] = $_GET['name'];
		$success = Table::addRow("reservation_objects", $values, false);
		redirecttotal('reservation/reservationsettings/showsettings',null);
	}
	

	public function updateobjectAction() {
	
		$objectID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$success = Table::updateRow('reservation_objects', $values, $objectID, false);
		redirecttotal('reservation/reservationsettings/showsettings',null);
	}
	

	// TODO: Vuoroa ei saisi poistaa ennenkuin kaikki varatut vuorot irtautetaan
	public function removeobjectAction() {
		$objectID = $_GET['id'];
		$success = Table::deleteRow("reservation_objects", $objectID, false);
		redirecttotal('reservation/reservationsettings/showsettings',null);
	}
	
	
	
}

?>
