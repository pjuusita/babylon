<?php



class SettingsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
	
	
	public function indexAction() {
		//$this->showprojectsAction();
		$this->registry->template->show('system/error','unknown');
	}	
	

	public function showsettingsAction() {
		
		updateActionPath("Books Settings");
		
		$this->registry->labels = Table::load("books_labels");
		//$this->registry->lists = Table::load("books_lists");
		
		$this->registry->template->show('books/settings','settings');
	}
	
	
	
	public function insertlabelAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		//$values['ColorID'] = $_GET['colorID'];
		$success = Table::addRow("books_labels", $values, false);
		
		redirecttotal('books/settings/showsettings',null);
	}
	
	
	public function updatelabelAction() {
	
		$labelID = $_GET['id'];
	
		$values = array();
		$values['Name'] = $_GET['name'];
		//$values['ColorID'] = $_GET['colorID'];
		
		//$color = Table::loadRow("system_colors",$_GET['colorID']);
		//$values['Colorcode'] = $color->normal;
		
		$success = Table::updateRow("books_labels", $values, $labelID, true);
		redirecttotal('books/settings/showsettings');
	}
	
	
	
		
}
