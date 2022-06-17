<?php


/**
 * Tämä on täällä lähinnä placeholderina. Tarkoituksena on käyttää worder-projektin concepts-taulua hyväksi.
 * Tässä on vain se ongelma, että (a) conceptit on kiinnitetty grammariin. Tämä ei sikäli ole ongelma, että
 * grammar valikko on templatessa ja löytyy session variablesta, mutta taulujen linkitys sotii hieman sitä
 * periaatetta vastaan, että modulit pitäisivät olla itsenäisiä. (b) knowledgebase on omana moduulinaan vaikka
 * se ei varsinaisesti toimi standalonena lainkaan. Harkinnassa on, että tämä olisi worder-modulin alimoduli.
 * Kiinnitys grammariin tarvitaan kuitenkin jokatapauksessa, käsitteet pitää olla yhtenäiset tekoälyn ja 
 * kielellisen ulkomuodon kanssa. 
 * 
 * Vastaavasti linkki on myös toiseen suuntaan, worderin lausegeneraattori käyttänee knowledgebasen tauluja
 * ja funktioita 'oikeellisten' lauseiden generointiin. Tämä puoltaisi sitä, että oikeastaan kyse pitäisi olla
 * worderin-tauluista. Onkohan täällä mitään muuta modulia toteutettu niin, että itse tietokanta on jonkin muun
 * modulin alaisuudessa. Ehkä admin moduli on tällainen. Kysehän on pelkästään nimeämiskäytännöstä, mitään
 * frameworkin pakottamaa rajoitetta tässä ei ole. Worderiin vaan alkaa syntyä aika hurja määrä tauluja, että 
 * olisi ehkä syytä jakaa tätä osiin.
 * 
 * @author Pete
 *
 */

class ConceptsController extends AbstractController {
	
	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
		//return array('menu.css', 'main.css','mytheme/jquery-ui.css','worder.css','yritys.css','prism.css','chosen.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}
		
	
	public function indexAction() {
		$this->registry->template->show('system/error','unknown');
	}
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************

	
	
}
?>