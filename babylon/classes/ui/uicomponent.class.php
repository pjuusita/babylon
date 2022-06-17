<?php


abstract class UIComponent {
	
	
	// käytetään setLineAction
	/**
	 * Nappulan tai rivin painaminen siirtyy parametrina annetulle sivulle (window.location).
	 *
	 */
	const ACTION_FORWARD = 1;
	
	
	/**
	 * Nappulan tai rivin painaminen siirtyy parametrina annetulle sivulle (window.location).
	 *
	 */
	const ACTION_NONE = 0;
	
	
	/**
	 * Nappulan tai linen painaminen suorittaa javascript-funktion
	 *
	 */
	const ACTION_JAVASCRIPT = 2;
	
	
	/**
	 * Toimintaa en muista
	 *
	 * @var unknown
	 */
	const ACTION_FORWARD_INDEX = 3;
	
	/**
	 * Nappulan tai linen painaminen suorittaa avaa dialogin
	 *
	 */
	const ACTION_OPENDIALOG= 5;
	
	/**
	 * Nappulan tai rivin painaminen siirtyy parametrina annetulle sivulle (window.location).
	 *
	 */
	const ACTION_NEWWINDOW = 10;
	
	
	/**
	 * Nappulan action heittää errormessage popuppiin ilmoituksen
	 * 
	 */
	const ACTION_ERRORMESSAGE = 6;
	

	/**
	 * Nappulan action heittää successmessage popuppiin ilmoituksen
	 *
	 */
	const ACTION_SUCCESSMESSAGE = 7;
	
	

	/**
	 * Toimintaa en muista, tätä ei ole toteutettu eikä ole käytässä
	 *
	 * @var unknown
	 */
	const ACTION_CHECK = 8;
	
	
	
	
	/**
	 * Tätä käytetään ainakin onOpen actionina, eli kun joku avataan, niin katsotaan onko tämä actioni määritelty
	 *
	 */
	const ACTION_LOAD = 9;
	
	
	/**
	 * Ei ole ehkä käytässä
	 *
	 */
	const ACTION_JSON = 99;
	
	
	const VALIGN_TOP = 31;
	const VALIGN_BOTTOM = 30;
	
	
	// sectionin default moodi, näkyy muokkaa nappula
	// mikäli dialogi, näkyy sulje ja muokkaa nappulat, muokkaa -> näkyy peruuta ja tallenna.
	const MODE_SHOW = 50;			

	// tablen defaultmoodi, tablen edit moodi on sellainen että rivit ovat avoimena
	// Muokkaa nappulaa ei näkyvissä, jos dialogi sulje nappula näkyvissä
	// Käytetään esim. dialogissa silloin kuin muokkausoikeuksia ei ole
	const MODE_NOEDIT = 51;
	
	// Näkvissä ainoastaan tallenna ja sulje/peruuta-nappulat. Sulje dialogissa, muuten peruuta
	const MODE_EDIT = 53;
	
	// Tämä on UITableSectionissa käytetty, yhden rivin valittava/värjäävä
	// - lineactionilla voi käsitellä clikkauksen.
	const MODE_LINESELECT = 56;	
	
	const MODE_INSERT = 54;
	
	const MODE_CUSTOM = 55;		// sisältä on täysin custom, sisältä haetaan erillisellä funktiolla
	
	
	public static $counter = 0;
	
	
	public $componentID;
	
	
	/**
	 * Counterin alustus randomisoidaan, koska contentloadin kohdalla vanhat elementtien
	 * id-numerot jäävät joskus vanhat voimaan. Tämä aiheuttaa joskus reload tarpeen contenttiin.
	 * Tämä on korjattu nyt toistaiseksi niin, että id-numerot tulevat satunnaisesta alkuindeksistä
	 * jolloin päällekkäisten elementID-arvojen todennäköisyys pienenee. 
	 * 
	 */
	function __construct() {
		if (self::$counter == 0) {
			self::$counter = rand(100000,1000000);
		}
		$this->componentID	= self::$counter;
		self::$counter++;
	}
	
	
	function getID() {
		return $this->componentID;
	}
	
	
	/**
	 * Palauttaa css-tiedoston, joka sisältää kaikki tarvittavat css-elmentit. Tämä saattaa olla tarpeeton, jos
	 * tiedostot kuitenkin bindataan vasta myähemmin ja css-tiedoton versioita voi olla useampia.
	 * 
	 * Tätä voitaisiin ehkä käyttää siihen, että tarkistetaan onko kaikissa tiedostoissa, jotka
	 * käyttävät kyseistä luokkaa includetettu kyseinen css-tiedosto. Tämä tarkistus pitäisi mahdollisesti
	 * suorittaa kyseisen luokan konstruktorissa. Tätä varten tarttee jonkun globaalin muuttujan, joko DEV
	 * tai sitten joku lisä vipu. 
	 * 
	 * Toteutus voisi olla staticci
	 * 
	 */
	//abstract public function setCSSFile();

	
	/**
	 * Palauttaa kaikki luokan käyttämät css-elementit, joiden pitäisi läytyä sitten css-tiedostosta.
	 * Tätä käytetään jonkinlaiseen käyttäliittymäkomponenttien tarkistukseen, että mitään ei ole unohtunut
	 * määrittää, tai turhien poistoon.
	 * 
	 * Tarkistus voitaisiin suorittaa teemojen osiossa. Joko automaattisesti tai manuaalisesti. 
	 * 
	 * Lisäksi voitaisiin ehkä tarkistaa, että componenteilla ei ole käytässä samannimisiä css-elementtejä,
	 * samoja ei tulisi sallia, koska tuotantoversiossa kaikki css:t lykätään samaan css-tiedostoon tehokkuuden
	 * nimissä.
	 * 
	 * Ongelma: Useampi uiComponent voi käyttää samaa css-tiedostoa.
	 * 
	 * Toteutus voisi olla staticci
	 */
	//abstract public function getCSSElements();
}
?>