<?php


class IndustriesController extends AbstractController {


	
	public function getCSSFiles() {
		return array('menu.css', 'main.css','mytheme/jquery-ui.css','yritys.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
	
	
	public function indexAction() {
		//$this->showToimialatAction();
		$this->registry->template->show('system/error','unknown');
	}


	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	// TODO: Muuta nimi
	public function showToimialatAction() {
		$this->registry->loadParams();
		//$this->registry->toimialat = Toimialaluokka::loadToimialaluokat();
		$this->registry->template->show('crm/industries','industriestable');
	}
}
?>