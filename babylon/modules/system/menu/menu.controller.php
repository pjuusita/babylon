<?php


class MenuController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php','chosen.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}
	
	
	public function getTemplate($action) {
		return 'minimal';
	}
	
	
	public function indexAction() {
		//$this->showaccountchartAction();
		$this->registry->template->show('system/error','unknown');
	}




	//******************************************************************************************************
	//***** ACCOUNTCHART ACTIONS
	//******************************************************************************************************
	
	
	
	/**
	 * TODO: 17.10.21	Poistetty käytöstä. Tämä oli muistaakseni käytössä kun nappuloissa
	 * 					oli erikseen 'open menu'-toiminto, eli oikeassa laidassa open ikoni
	 * 					josta saisi valikon auki ilman, että itse menun actionia suoritettaisiin
	 * 					Kyllä tämän voisi edelleen toteuttaa, mutta ei ole prioriteettina.
	 * 
	 * 
	 */
	/*
	public function openmenuAction() {
		
		$openmenuID = $_GET['openmenuid'];
		$_SESSION['menuopen_' . $openmenuID] = 2;
		
		//echo "<script>";
		//echo " alert('Setting openmenuID in controller - " . $closemenuID . "');";
		//echo "</script>";
	}
	*/

	/*
	public function closemenuAction() {
	
		
		$closemenuID = $_GET['closemenuid'];
		$_SESSION['menuopen_' . $closemenuID] = 0;
		
		//echo "<script>";
		//echo " alert('closemenuID in cotroller - " . $closemenuID . "');";
		//echo "</script>";
				
	}
	*/
	
	
	
	
}

?>