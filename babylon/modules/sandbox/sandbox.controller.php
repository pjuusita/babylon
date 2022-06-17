<?php


class SandboxController extends AbstractController {

	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css','section.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
	
		
	public function indexAction() {
		//$this->registry->template->header = 'Sandbox';
		//$this->registry->template->show('sandbox','index');
		$this->registry->template->show('system/error','unknown');
	}	
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	
}
