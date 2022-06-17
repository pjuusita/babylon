<?php



/**
 * Tämä clients-toiminnallisuus on hieman päällekkäinen crm-toiminnallisuuden kanssa. Tätä sivua käytetään
 * ainakin silloin kun crm-moduli ei ole aktivoituna. Tämä käyttää samoja tauluja kuin crm, mutta käsittelyä
 * on hieman yksinkertaistettu. Lineactioni menee crm-moduliin, mikäli se on aktiivinen.
 *
 */
class ClientsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}


	public function indexAction() {
		//$this->showclientsAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showclientsAction() {
	
		$comments = false;
		
		// Ladataan rivit sekä henkilöt taulusta, että yritystaulusta, henkilöt taulusta ainoastaan henkilöt, jotka eivät ole kiinnitetty yritykseen.
		// Tai ehkä henkilölle pitäisi asettaa täppä henkilöasiakas myöskin olemaan (erikoistapauksissahan henkilö voi olla sekä yrityksen nimissä
		// tilaava, että tehdä myös henkilökohtaisia ostoja, eli toimisi molemmissa rooleissa).
		
		
		
		
		
		
		$this->registry->template->show('sales/clients','clients');
	}
	

}

?>
