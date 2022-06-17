<?php


class ProductsController extends AbstractController {

	
	
	
	public function getCSSFiles() {
		//return array('main.css','mytheme/jquery-ui.css','form.css');
		return array('menu.css', 'testcss.php','mytheme/jquery-ui-test.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
	
	
	public function indexAction() {
		//$this->showproductsAction();
		$this->registry->template->show('system/error','unknown');
	}
	 

	
	public function showproductsAction() {
		$this->registry->products = Table::load("sales_products");
		foreach($this->registry->products as $index => $product) {
			if ($product->productnumber == "") {
				$product->productnumber = "<i>Ei käytössä</i>";
			}
		}
		
		$this->registry->producttypes = $this->getProductTypes();
		
		
		$this->registry->vats = Table::load("system_vats");
		
		$this->registry->productgroups = Table::load("sales_productgroups");
		if (isModuleActive('accounting')) {
			$this->registry->accounts = Table::load('accounting_accounts');
			//echo "<br>Account module active";
			foreach($this->registry->accounts as $index => $account) {
				$account->fullname = $account->number . " " . $account->name;
			}
			
		} else {
			$this->registry->accounts = null;
		}
		
		$this->registry->productnumberused = Settings::getSetting('system_settings_productnumberused');
		$this->registry->units = Table::load("system_units");
		
		
		$this->registry->template->show('sales/products','products');
	}
	

	private function getProductTypes() {
		$producttypes = array();
		$producttypes[0] = "Tuote";
		$producttypes[1] = "Palvelu";
		return $producttypes;
	}
	
	public function showproductAction() {
		$productID = $_GET['id'];

		$this->registry->product = Table::loadRow("sales_products", $productID);
		
		if (isModuleActive('accounting')) {
			$this->registry->accounts = Table::load('accounting_accounts');
			//echo "<br>Account module active";
			foreach($this->registry->accounts as $index => $account) {
				$account->fullname = $account->number . " " . $account->name;
			}
				
		} else {
			$this->registry->accounts = null;
		}
		$this->registry->vats = Table::load("system_vats");
		$this->registry->productnumberused = Settings::getSetting('system_settings_productnumberused');
		
		$this->registry->producttypes = $this->getProductTypes();
		
		$this->registry->productgroups = Table::load("sales_productgroups");
		$this->registry->units = Table::load("system_units");
		
		$this->registry->template->show('sales/products','product');
	}
	
	
	public function insertproductAction() {
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Productnumber'] = $_GET['productnumber'];
		if (isset($_GET['productgroupID'])) $values['ProductgroupID'] = $_GET['productgroupID'];
		$values['AccountID'] = $_GET['accountID'];
		$values['VatID'] = $_GET['vatID'];
		$values['UnitID'] = $_GET['unitID'];
		$success = Table::addRow("sales_products", $values, false);
		redirecttotal('sales/products/showproducts',null);
	}
	
	
	
	public function updateproductAction() {
	
		$productID = $_GET['id'];
		
		$values = array();
		$values['Name'] = $_GET['name'];
		if (isset($_GET['productnumber'])) $values['Productnumber'] = $_GET['productnumber'];
		if (isset($_GET['productgroupID'])) $values['ProductgroupID'] = $_GET['productgroupID'];
		$values['Service'] = $_GET['service'];
		$values['VatID'] = $_GET['vatID'];
		$values['UnitID'] = $_GET['unitID'];
		$values['AccountID'] = $_GET['accountID'];
		$success = Table::updateRow("sales_products", $values, $productID);
		
		redirecttotal('sales/products/showproduct&id=' . $productID);
	}
	
}
