<?php

/**
 * Kontrollereiden yläluokka
 *
 * @author Petri Uusitalo
 * @copyright Babelsoft Oy, 2016
 *
 */
abstract class AbstractController {

	protected $registry;
	protected $session;
	
	function __construct($registry) {
		$this->registry = $registry;
	}
	

	/**
	 *
	 *
	 * NOTE: This could be located in mudule-class, but currently desided that this is more convenient inside
	 * controller because hasAccess function uses these extensively. 
	 * 
	 * NOTE: This could be static, if PHP allows, test this
	 * 
	 * NOTE: Should probably return 'all' in when module is global system module
	 *
	 * @param integer $scope	1 - minimal functionality, 2 - basic functionality, 3 - full functionality
	 */
	//abstract function getRoles($scope);
	
	

	/**
	 * Returns menu-action -pair which are available for users with given profile.
	 * 
	 * NOTE: this should be updated somehow when user loses profiles, this functionality should be
	 * included in profile-remove-action.
	 * 
	 * NOTE: This could be static, if PHP allows, test this
	 * 
	 * @param integer $scope
	 * @param string $profile
	 */
	// 1.12.2019 Siirretään moduleen tämä
	//abstract function getMenuActions($scope, $profile);
	
	
	
	
	// override if needs module spesific stylesheets
	// tämä funktio saatetaan pystyä korvaamaan kokonaan uiComponenttien omilla css-asetuksilla (tulisi siis automatic), ellei kyseessä ole sitten lib-luokka
	// täällä olisi siis css-luokat, jotka tulevat ui-luokkien tarvitsemien lisäksi, näitä pitäisi pyrkiä välttämään
	public function getCSSFiles() {
		return null;
	}
	
	
	// override if needs module spesific javascript files
	public function getJSFiles() {
		return null;
	}
	

	
	/**
	 * Palauttaa numeron joka ilmaisee onko parametrina annetulla käyttäjällä oikeudet parametrina 
	 * annetun toiminnon suorittamiseen. Mahdolliset arvot ovat seuraavat:
	 * 
	 * TODO: tähän pitäisi todennäkäisesti ottaa huomioon jonkinlainen autentikointi systeemi. Pitäisi
	 * tarkistaa tietyn käyttäjäryhmän oikeudet ja/tai pitäisi sallia joissakin tapauksissa global 
	 * access.
	 * 
	 * (1) Toiminto on sallittu
	 * (2) Toiminto ei sallittu, redirect toi login ruudulle
	 * (3) Jokin muu virheilmoitus.... ??
	 * 
	 */
	// 1.12.2019 Poisettu, siirretään hasAccess-toiminnallisuus moduleen
	//abstract function hasAccess($action, $profiles, $scope);
	

	/**
	 * DEPRECATED: templaten määrittäminen on siirretty muualle. Todennäkäisesti tämä hoidetaan
	 * antamalla request parametri.
	 * 
	 */
	function getTemplate($action) {
		return null;
	}
	
	
	/**
	 * This action is by default acceccible for all scopes
	 * 
	 * TODO: Poistetaan kun ollaan testailtu/viitteet poistettu kaikkialta
	 * 
	 */
	abstract function indexAction();
	
	
	/**
	 * Tämä ehkä staattinen
	 * 
	 */
	function getSettingsMenu() {
		die('controller:getSettingsMenu not implemented');
	}
}


?>