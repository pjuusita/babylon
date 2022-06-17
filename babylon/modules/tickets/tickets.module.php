<?php

/**
 *  Placeholder.
 *  
 *  Tähän on tarkoitus toteuttaa jonkinlainen virheraporttien käsittelytyökalu. Ticketit tulevat 
 *  tyypillisesti järjestelmän ulkopuolelta, joko automaattisesti, esim. webformilta. Tai sitten
 *  mobiilipelistä, tai niitä voi joku palveluhenkilö kirjata manuaalisesti. Tai ne voidaan jopa
 *  napata sähköpostista.
 *  
 *  Täällä käsitellään nämä; selvitetään, vastataan tai siirretään jonkun toisen tehtäväksi. 
 *  Mahdollisesti voidaan myöhemmin linkittää myös ticketti jotenkin johonkin yksittäiseen 
 *  ulkopuoliseen actioniin, samaan tyyliin kuin taskeissakin on tarkoitus. Tyypillisesti tämä
 *  kuitenkin on manuaalisesti suoritettava selvitys/tehtävä, joka vain kirjataan tehdyksi.
 *  
 *  Keskusteluhistoria jää talteen tickettiin. Tickettejä voidaan myöhemmin selailla eri tavalla
 *  filtteröiden, esim. raportoijan tai käsittelijän mukaan. Ticketti voidaan jaotella jotenkin
 *  osa-alueisiin, tarkentaen tarvittaessa. Tiketti menee ehkä suoraan ao. osa-alueen vastuuhenkilön
 *  käsittelyyn.
 * 
 *
 */

class TicketsModule extends AbstractModule {
	
	
	public function getDefaultName() {
		return "Tikettien hallinta";
	}
	
	

	public function getAccessRights() {
		return array();
	}
	
	
	
	public function getMenu($userrights) {
		$menuitems = array();
		return $menuitems;
	}
	

	
	public function hasAccessRight($action) {
		return true;
	}
	
	
	
	public function hasAccess($accesskey) {
		return false;
	}
	
}


?>