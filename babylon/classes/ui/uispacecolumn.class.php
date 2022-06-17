<?php


/**
 *  Tämä luokka on tarkoitettu siihen, että UITablea voidaan käyttää yksinkertaisena taulukkona, jonka arvot läytyy
 *  suoraan taulukon indeksinä (ovat kaikki stringejä, myähemmin voidaan mahdollisesti käyttää omia formattereita tähän).
 *  
 *  Tämä voidaan mahdollisesti korvata täydentää esimerkiksi UISortColumn-luokan sisälle niin, että haettaisiin
 *  taulukon indeksin avulla, mutta tämä on vielä toistaiseksi epäselvää miten tämä kannattaisi toteuttaa.
 * 
 * 
 *
 */

class UISpaceColumn extends UIColumn {

	public $iconsize = 32;
	public $width = '';
	
	public function __construct($width) {
		parent::__construct("", "");
		$this->width = $width;
	}
	
	
}
?>