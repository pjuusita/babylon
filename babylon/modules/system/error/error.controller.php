<?php

/**
 * Settingscontrollerissa hallitaan jarjestelman asetuksia. Periaatteessa jokaista kaytassa olevaa modulia varten on oma
 * sectioninsa, jokaiselta modulilta tulisi siis saada tarpeelliset asetukset.
 * 
 * Perusasetuksia on muutamia, ainakin ulkoasuun ja logoon liittyvia asetuksia, mutta periaatteessa namakin voisivat tulla
 * suoraan kaytettavissa olevista oletusmoduleista. Lahinna kai niin, etta system module on automaattisesti kaytassa, myas admin
 * moduli on todennakaisesti automaattisesti kaytassa. Osa moduleista on tosin pelkastaan hallinta kayttaan, esim. database.
 * 
 *
 */
class ErrorController extends AbstractController {


	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
	
	
	public function indexAction() {
		//$this->registry->message = "Tuntematon toiminto";	
		//$this->registry->template->show('system/error','error');
		$this->registry->template->show('system/error','unknown');
	}

	
	public function norightserrorAction() {
		$this->registry->message = "Ei oikeutta toimintoon";	
		$this->registry->template->show('system/error','error');
	}

	
	
}

?>
