<?php



class WordsetsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}
	
	
	public function indexAction() {
		//$this->showsettingsAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showsettingsAction() {
	
		$this->registry->languages = Table::load('wordtrainer_languages');
		$this->registry->template->show('wordtrainer/wordtrainersettings','wordtrainersettings');
	}
	
}

?>
