<?php



class SalessettingsController extends AbstractController {

	
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
		
		
		$settings = Table::load('system_settings');
		
		//var_dump($this->registry->settings);
		
		$settingsrow = new Row();
		$settingsrow->systemID = 1;
		foreach($settings as $index => $row) {
			//echo "<br>name - " . $row->name;
			if ($row->name == 'system_settings_productnumberused') {
				//echo "<br>Found";
				if ($row->value == "") {
					$settingsrow->productnumberused = 0;
				} else {
					$settingsrow->productnumberused = $row->value;
				}
			}
		}
		$this->registry->settings = $settingsrow;
		$usedselect = array();
		$usedselect[0] = "Ei käytössä";
		$usedselect[1] = "Käytössä";
		$this->registry->usedselect = $usedselect;
		
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}	
		
		$this->registry->currencies = Table::load('system_currencies');
		$this->registry->saletypes = Table::load('sales_saletypes');
		$this->registry->units = Table::load('system_units');
		$this->registry->productgroups = Table::load('sales_productgroups');
		$this->registry->template->show('sales/salessettings','salessettings');
	}
	
	

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
	

	public function insertcurrencyAction() {
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Sign'] = $_GET['sign'];
		$success = Table::addRow("system_currencies", $values, false);
		redirecttotal('sales/salessettings/showsettings',null);
	}
	

	public function updatecurrencyAction() {
	
		$currencyID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Sign'] = $_GET['sign'];
		$success = Table::updateRow('system_currencies', $values, $currencyID, false);
		redirecttotal('sales/salessettings/showsettings',null);
	}
	

	public function removecurrencyAction() {
		$currencyID = $_GET['id'];
		$success = Table::deleteRow("system_currencies", $currencyID, false);
		redirecttotal('sales/salessettings/showsettings',null);
	}
	
	
	public function insertunitAction() {
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Sign'] = $_GET['sign'];
		$success = Table::addRow("system_units", $values, false);
		redirecttotal('sales/salessettings/showsettings',null);
	}
	
	
	public function updateunitAction() {
		$unitID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Sign'] = $_GET['sign'];
		$success = Table::updateRow('system_units', $values, $unitID, false);
		redirecttotal('sales/salessettings/showsettings',null);
	}
	
	
	public function removeunitAction() {
		$unitID = $_GET['id'];
		$success = Table::deleteRow("system_units", $unitID, false);
		redirecttotal('sales/salessettings/showsettings',null);
	}
	
	
	public function insertproductgroupAction() {
		$values = array();
		$values['Name'] = $_GET['name'];
		$success = Table::addRow("sales_productgroups", $values, false);
		redirecttotal('sales/salessettings/showsettings',null);
	}
	
	
	public function updateproductgroupAction() {
		$productgroupID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$success = Table::updateRow('sales_productgroups', $values, $productgroupID, false);
		redirecttotal('sales/salessettings/showsettings',null);
	}
	
	
	public function removeproductgroupAction() {
		$productgroupID = $_GET['id'];
		$success = Table::deleteRow("sales_productgroups", $productgroupID, false);
		redirecttotal('sales/salessettings/showsettings',null);
	}
		
	
	public function insertsaletypeAction() {
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['SalesaccountID'] = $_GET['salesaccountID'];
		$values['ReceivablesaccountID'] = $_GET['receivablesaccountID'];
		$success = Table::addRow("sales_saletypes", $values, false);
		redirecttotal('sales/salessettings/showsettings',null);
	}
	
	
	public function updatesaletypeAction() {
		$saletypeID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['SalesaccountID'] = $_GET['salesaccountID'];
		$values['ReceivablesaccountID'] = $_GET['receivablesaccountID'];
		$success = Table::updateRow('sales_saletypes', $values, $saletypeID, false);
		redirecttotal('sales/salessettings/showsettings',null);
	}
	
	
	public function removesaletypeAction() {
		$unitID = $_GET['id'];
		$success = Table::deleteRow("sales_saletypes", $unitID, false);
		redirecttotal('sales/salessettings/showsettings',null);
	}
	
}

?>
