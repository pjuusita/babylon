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

class UISimpleColumn extends UIColumn {

	public $iconsize = 32;
	public $width = '';
	public $dataindex;		// tän voisi poistaa, korvataan datavariablella
	public $columntype = 0;
	
	public function __construct($name, $dataindex, $columntype = 0) {
		parent::__construct($name, $dataindex);
		$this->dataindex = $dataindex;
		$this->columntype = $columntype;
	}
	
	
}
?>