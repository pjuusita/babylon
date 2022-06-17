<?php




class ElementsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//$this->showelementsAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	

	public function showelementsAction() {
	
		
	}
	
}
