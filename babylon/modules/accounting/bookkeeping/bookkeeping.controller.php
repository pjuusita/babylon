<?php



class BookkeepingController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}


	public function indexAction() {
		//$this->registry->template->show('accounting/bookkeeping','index');
		$this->registry->template->show('system/error','unknown');
	}
	
	
	public function showbookkeepingAction() {
		//$this->registry->template->show('accounting/bookkeeping','index');
		//$this->registry->template->show('system/error','unknown');
		updateActionPath("Tulot/Menot");
		
		$this->registry->template->show('accounting/bookkeeping','incomesexpenses');
	}
	
}

?>
