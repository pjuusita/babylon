<?php

// Siirretty clienttitles module tanne uudelle nimella


class JobtitlesController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css', 'main.css','mytheme/jquery-ui.css','yritys.css','petestyle.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
	
	
	public function indexAction() {
		//$this->showtitlesAction();
		$this->registry->template->show('system/error','unknown');
	}


	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	

	public function showtitlesAction () {
		$this->registry->jobtitles = Table::load('crm_jobtitles');
		$this->registry->template->show('crm/jobtitles','jobtitles');
	}
	

	public function insertjobtitleAction() {
		$comments = false;
		$values = array();
		$values['Name'] = $_GET['name'];
		$rowID = Table::addRow("crm_jobtitles", $values, $comments);
		if ($comments == false) redirecttotal('crm/jobtitles/showtitles',null);
	}


	// Pitäisi olla ehkä tarkistus tähän -- heitä työlistalle
	public function deletetitteliAction () {

	}

	
	// TODO
	public function changetittelinimiAction () {
	
	}


}

?>
