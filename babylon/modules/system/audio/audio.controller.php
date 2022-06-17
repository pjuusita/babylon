<?php


class AudioController extends AbstractController {

	
	public function getCSSFiles() {
		return array('testcss.php','menu.css','chosen.css','style2.css','prism.css');
		//return array('menu.css','testcss.php','chosen.css');
		return array();		
	}
	
	
	public function getJSFiles() {
		return array();
		//return array('jquery-3.2.1.min.js','jquery-ui.js','chosen.jquery.js','init.js');
	}
	
	
	public function indexAction() {
		//$this->showaudiocaptureAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	

	public function showaudiocaptureAction() {

		$this->registry->template->show('system/audio','audio');
		
	}
	
	

	public function index2Action() {
	
		$this->registry->template->show('system/audio','audio2');
	
	}
	
	

}
