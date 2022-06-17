<?php


class FrontpageController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','babylon.css','section.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//$this->registry->template->header = 'Main';
		//$this->registry->template->show('worder/frontpage','index');
		$this->registry->template->show('system/error','unknown');
	}	
	
	
}
