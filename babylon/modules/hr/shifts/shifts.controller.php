<?php
class ShiftsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css', 'testcss.php','mytheme/jquery-ui.css');
	}

	public function getJSFiles() { 
		return array('jquery.min.js', 'jquery-ui.js', 'jquery.ui.touch-punch.min.js','chosen.jquery.js','prism.js');
	}
	
	
	public function indexAction() {
		//$this->showshiftsAction();
		$this->registry->template->show('system/error','unknown');
	}
	
	
	
	public function peteproto01Action() {
		$this->registry->template->show('hr/shifts','peteproto01');
	}
	
	
	
	public function peteproto02Action() {
		$this->registry->template->show('hr/shifts','peteproto02');
	}
	

	public function peteproto03Action() {
		$this->registry->template->show('hr/shifts','peteproto03');
	}
	

	public function peteproto04Action() {
		$this->registry->template->show('hr/shifts','peteproto04');
	}
	

	public function shifthourimageAction() {
		
		$this->registry->width = $_GET['width'];
		$this->registry->height = $_GET['height'];
		$this->registry->hours = $_GET['hours'];
		
		
		$this->registry->template->show('hr/shifts','shifthourimage');
	}
	
	
	public function shifthourheaderAction() {
	
		$this->registry->width = $_GET['width'];
		$this->registry->height = $_GET['height'];
		$this->registry->hours = $_GET['hours'];
	
		$this->registry->template->show('hr/shifts','hourheader');
	}
}
?>