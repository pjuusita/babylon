<?php

/**
 * Tyahakemus
 * 
 * @author pjuusita
 *
 */
class ApplicationController extends AbstractController {


	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
		//return array('menu.css', 'main.css','mytheme/jquery-ui.css','yritys.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
	
	
	public function indexAction() {
		//echo "<br>Application - index action";		
		$this->registry->template->show('system/error','unknown');
	}
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	

}

?>
