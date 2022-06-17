<?php

	// TODO: Puuttuu oikeuksien hallinta kokonaan

	// Tätä funktiota kutsutaan globaalien json-kyselyiden tekemiseen, tällähetkellä
	// globaalina tietokantana on babelsoftf_main, joka sisältää ns. mallitietokantarakenteen
	// tämän kautta kyselyille ei ole käyttäoikeustarkistusta toistaiseksi, pitäisi mahdollisesti
	// rajoittaa admin käyttäjälle. Loppupeleissä rajoitteeseen pitäisi lisäksi sisällyttää
	// rajoite siihen, että vain tiettyjä actioneita voidaan kysyä.
	//
	// Ehkä johonkin pitäisi antaa global access, ehkä controllerin has-access funktioon, mutta sen 
	// toteutus on vielä vaiheessa.
	//
	// En osaa sanoa onko tässä tietoturva ongelma, en tiedä toimiiko kutsu jos käyttäjä ei ole
	// kirjautunut sisään. Jokatapauksessa jonkinlainen autentikointi systeemi tarvitaan.
	// (tällähetkellä accessia käsittelee teoriassa controller kohtainen hasAccess-luokka.
	//
	define ("APPLICATIONKEY", 'BABYLONLOGIN');
	//define ("APPLICATIONKEY", 'BABYLONJSON');
	define ("DEV", false);
	define ("ROOTPHP", 'pdf.php');
	define ("SITEPATH", 'demo');
	define ("MYPATH_SEPARATOR", '/');
	define ("MENUPRESENT", false);
	define ("NOFRAMESHANDLER", 'noframes.php');
	define ("PDFGEN", true);
	define ("SAVEROOT", '/home/babelsoftf/domains/babelsoft.fi/upload/');
		
	
	
	
	include 'app/init.php';
		
	//echo "<br>Database - " . $_SESSION['database'];
	
	if (isset($_SESSION['database'])) {
		init($_SESSION['database']);
	} else {
		//echo "<br>No database";
		init('babelsoftf_main');
	}
	
	$registry->router = new router($registry, SITE_PATH);
	
	$registry->router->load();
?>