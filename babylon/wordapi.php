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
	if ($_GET['rt'] == 'getlanguages') {
		Router::executeAction('worder/wordapi/getsourcelanguages', SITE_PATH, $registry);
		exit;
	}
	if ($_GET['rt'] == 'getessentials') {
		Router::executeAction('worder/wordapi/getessentials', SITE_PATH, $registry);
		exit;
	}
	
	if ($_GET['rt'] == 'getlessonlist') {
		Router::executeAction('worder/wordapi/getlessonlist', SITE_PATH, $registry);
		exit;
	}
	
	if ($_GET['rt'] == 'getlesson') {
		Router::executeAction('worder/wordapi/getlesson', SITE_PATH, $registry);
		exit;
	}
	
	if ($_GET['rt'] == 'getsentencesets') {
		Router::executeAction('worder/wordapi/getsentencesets', SITE_PATH, $registry);
		exit;
	}
	
	if ($_GET['rt'] == 'getsentences') {
		Router::executeAction('worder/wordapi/getsentences', SITE_PATH, $registry);
		exit;
	}
	
	
	
	if ($_GET['rt'] == 'getconcepts') {
		Router::executeAction('worder/wordapi/getconcepts', SITE_PATH, $registry);
		exit;
	}

	if ($_GET['rt'] == 'getwords') {
		Router::executeAction('worder/wordapi/getwords', SITE_PATH, $registry);
		exit;
	}
	
	if ($_GET['rt'] == 'getobjectives') {
		Router::executeAction('worder/wordapi/getobjectives', SITE_PATH, $registry);
		exit;
	}
	
	if ($_GET['rt'] == 'getlessonsinfo') {
		Router::executeAction('worder/wordapi/getlessoninfo', SITE_PATH, $registry);
		exit;
	}
	
	if ($_GET['rt'] == 'getessentials') {
		Router::executeAction('worder/wordapi/getessentials', SITE_PATH, $registry);
		exit;
	}
	
	if ($_GET['rt'] == 'getwordforms') {
		Router::executeAction('worder/wordapi/getwordforms', SITE_PATH, $registry);
		exit;
	}
	
	
	if ($_GET['rt'] == 'getrules') {
		
		$sourceID = $_GET['sID'];
		$targetID = $_GET['tID'];
		$sourceSetID = 84;
		$targetSetID = 84;
		//Router::executeAction('worder/wordapi/getessentials', SITE_PATH, $registry);
		//Router::executeAction('worder/wordapi/getrulesJSON&grammarID=' . $_SESSION['grammarID'] . "&sourceID" . $sourceID  . "&targetID" . $targetID . "&targetsetID=" . $targetSetID . "&sourcesetID=" . $sourceSetID, SITE_PATH, $registry);
		Router::executeAction('worder/rules/getrulesJSON', SITE_PATH, $registry);
		exit;
	}
	
	echo "<br>Server Error: Unknown route - " . $_GET['rt'];	
?>