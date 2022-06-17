<?php

	// Tähän pitäisi jotain access controllia laittaa
		
	header('Content-Type: text/html; charset=utf-8');
	error_reporting(E_ALL ^ E_DEPRECATED);
	define ("DEV", false);
	
	include 'app/init.php';
	
	$_SESSION['grammarID'] = 1;
	$_SESSION['systemID'] = 5;
	
	//echo "<br>Jeeje";
	//init();
	
	// TODO: Tarkista versionumero, mukana tai saadaan serialista
	// TODO: Tarkista serialID, nämä pitäisi tallentaa kantaan erikseen
	// TODO: Voitaisiin tallentaa ja tsekata IP-osoite
	// 			- voidaan sulkea jotain IP:itä tarvittaessa, palvelunestohyökkäys
	//			- voidaan rajoittaa tarvittaessa pollaavia kyselyitä
	// 			- voidaan rajoittaa kyselyitä maakohtaisesti jos tarpeen
	// TODO: Pitäisi tarkistaa sekä userID ja salasana ehkä sessionID:riittäisi
	// TODO: Logita kysely
	// TODO: nykytilanne tallennetaan ehkä amazon serviseen, usean laitteen synkkaus
	
	
	$mysqli = ConnectDatabase("babelsoftf_wordino");
	if ($_GET['rt'] == 'getenglishnouns') {
		Router::executeAction('worder/wikiapi/getenglishnouns', SITE_PATH, $registry);
		exit;
	}
	
	
	echo "<br>Server Error: Unknown route - " . $_GET['rt'];	
?>