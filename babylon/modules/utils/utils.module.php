<?php

/**
 *  [12.10.2021] Onkohan tämä koko utils module tarpeellinen, ei näitä ainakaan kovin paljon käytetä. Jos tämä 
 *  poistetaan niin upload pitää tsekata, että on toteutettu jotenkin muuten.
 *  
 *  Utils moduli sisaltaa oiekastaan ainoastaan kirjastofunktionaalisuutta. Pitaa ehka miettia voisiko nama
 *  siirtaa muualle, esim lib-hakemiston alihakemistoihin.
 *  
 *  
 */
class UtilsModule {


	public function getDefaultName() {
		return "[1]Utils[2]Kirjastofunktiot";
	}
	
	
	public function getAccessRights() {
		return array();	
	}

	public function getMenu($accessrights) {
		return array();	
	}
	
	public function hasAccessRight($action) {
		return true;
	}

	public function hasAccess($accesskey) {
		return true;
	}
	
	
}


?>