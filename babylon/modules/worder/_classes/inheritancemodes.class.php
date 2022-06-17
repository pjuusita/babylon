<?php

/**
 * Tätä luokkaa käytetään componenttien ja argumenttien periytymiseen concept-hirarchystsä.
 * 
 * Modet ovat:
 * 		- Inheritable -- normaali tapaus, elementti on itsellä ja periytyy kaikille lapsille
 * 		- For childs -- periytyvä elementti annetaan kaikille lapsille, mutta ei ole voimassa itsellä
 * 		- Single -- elementti on voimassa itsellä, ei kopioidu lapsille
 * 		- Negate -- poistaa vanhemmalta perityn elementin voimassaolon, poistaa sen myös ko elementin periytymisen lapsille (ei toteutettu toistaiseksi)
 * 
 * TODO: Poista taulu worder_inheritancemodes
 *
 */
class InheritanceModes {
    
    
	// Nämä on testausta varten, vakioita rulejen tekoon
	// Varmaan osa näistä pitäisi siirtää tietokantaan
	
	const INHERITABLE = 1;
	const FOR_CHILDS = 2;
	const SINGLE = 3;
	const NEGATE = 4;
	const INHERITED = 5;
	
	// TODO: Tietokannassa ollessa tässä oli se hyvä puoli, että silloin nämä olisi ollut helppo käsitellä
	// multilangeina, nyt nämä ovat vain stringejä
    static function getInheritanceModes() {
        
    	$row = new Row();
    	$row->rowID = 1;
    	$row->name = "Inheritable";
    	$rows[1] = $row;
    	
    	$row = new Row();
    	$row->rowID = 2;
    	$row->name = "For children";
    	$rows[2] = $row;
    	 
    	$row = new Row();
    	$row->rowID = 3;
    	$row->name = "Single";
    	$rows[3] = $row;
    	 
    	$row = new Row();
    	$row->rowID = 4;
    	$row->name = "Negate";
    	$rows[4] = $row;
    	
    	$row = new Row();
    	$row->rowID = 5;
    	$row->name = "Inherited";
    	$rows[5] = $row;
    	
    	return $rows;
    }
   
}
