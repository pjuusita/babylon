<?php

/**
 *  Tähän tullaan toteuttamaan ajastettujen operaatioiden hallinta. Tätä varmaan
 *  kutsutaan serverin cronjob-ista, ja tämä toiminto sitten suorittaa järjestelmän
 *  sisälle rakennetut ajastetut operaatiot.
 *  
 *  Jotkin erilliset toiminnot saattavat lisätä automaattisesti omia ajastettuja
 *  toimintojaan suoraan tietokantaan. Esimerkiksi tilastointiin ja/tai eri asioiden
 *  laskemiseen tarkoitetut operaatiot saattavat tehdä tallaisia. Query-moduli siis
 *  erityisesti.
 *
 */

class CronjobController extends AbstractController {


	
	public function getCSSFiles() {
		return array();
	}
	
	
	public function getJSFiles() {
		return array();
	}

	
	public function getTemplate($action) {
		return 'minimal';
	}
	
	
	public function indexAction() {
		//$this->showcomparedatabasesAction();
		$this->registry->template->show('system/error','unknown');
	}


	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	

	
}

?>
