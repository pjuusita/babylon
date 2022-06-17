<?php

// pitäisikähän tämä lisätä omana luokkanaan, UIButton ehkä

/**
 * Tätä käytetään käsittääkseni pelkästään tietorakenteena. Kuitenkin käsittääkseni
 * melkeinpä täysin uilibin sisäisenä luokkana. Todennäkäisesti tällainen luokka
 * on kuitenkin tarpeen muutenkin.
 * 
 * TODO: siirrä sectionin sisäiseksi luokaksi, tai pitäisi siirtää uisection.class.php-tiedoston sisään ehkä, epäselvää tarvitaanko tätä
 * 
 * @author pjuusita
 *
 */
class UISectionButton extends UIComponent {


	public $type;
	public $link;
	public $title;
	public $tooltip;
	//public $cssclass;
	public $successaction;

	public function __construct() {
		parent::__construct();
		$this->type = 0;
		$this->link = 0;
		$this->title = 0;
		$this->tooltip = 0;
		//$this->cssclass = 0;
		$this->successaction = 0;
	}
}




?>