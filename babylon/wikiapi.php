<?php

	// Tähän pitäisi jotain access controllia laittaa
		
	header('Content-Type: text/html; charset=utf-8');
	error_reporting(E_ALL ^ E_DEPRECATED);
	define ("DEV", false);
	
	include 'app/init.php';
	
	$_SESSION['grammarID'] = 1;
	$_SESSION['systemID'] = 5;
	$_SESSION['userID'] = 999;
	
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
	
	$route = $_GET['rt'];
	$index = strpos($route, '?');
	if ($index != false) {
	    $route = substr($route, 0, $index);
	}
	
	
	
	// toteutus ok
	$mysqli = ConnectDatabase("babelsoftf_wordino");
	if ($route == 'getenglishnouns') {
		Router::executeAction('worder/wikiapi/getenglishnouns', SITE_PATH, $registry);
		exit;
	}
	
	$mysqli = ConnectDatabase("babelsoftf_wordino");
	if ($route == 'getwordclassconcepts') {
	    Router::executeAction('worder/wikiapi/getwordclassconcepts', SITE_PATH, $registry);
	    exit;
	}
	
	
	// toteutus ok, käytetään manuaalisesti
	if ($route == 'getnounconceptswithoutenglishform') {
	    Router::executeAction('worder/wikiapi/getnounconceptswithoutenglishform', SITE_PATH, $registry);
	    exit;
	}
	
	
	if ($route == 'getconceptswithoutenglishform') {
	    Router::executeAction('worder/wikiapi/getconceptswithoutenglishform', SITE_PATH, $registry);
	    exit;
	}
	
	
	// toteutus ok
	if ($route == 'getenglishnounswithdefinitions') {
	    Router::executeAction('worder/wikiapi/getenglishnounswithdefinitions', SITE_PATH, $registry);
	    exit;
	}
    	
	// toteutus ok
	if ($route == 'addconceptdefinition') {
	    Router::executeAction('worder/wikiapi/addconceptdefinition', SITE_PATH, $registry);
	    exit;
	}
	
	// toteutus ok
	if ($route == 'fetchwordforconcept') {
	    Router::executeAction('worder/wikiapi/fetchwordforconcept', SITE_PATH, $registry);
	    exit;
	}
	
	// toteutus ok, lessonID:t hardkoodattu
	if ($route == 'getenglishnounswithdefinitionsinlessons') {
	    Router::executeAction('worder/wikiapi/getenglishnounswithdefinitionsinlessons', SITE_PATH, $registry);
	    exit;
	}
	
	
	// työnalla
	if ($route == 'getspanishnounconcepts') {
	    Router::executeAction('worder/wikiapi/getspanishnounconcepts', SITE_PATH, $registry);
	    exit;
	}
	
	// toteutus työnalla
	if ($route == 'addspanishnounwordforconcept') {
	    Router::executeAction('worder/wikiapi/addspanishnounwordforconcept', SITE_PATH, $registry);
	    exit;
	}
	
	
	// toteutus työnalla
	if ($route == 'updateconceptsspanishstate') {
	    Router::executeAction('worder/wikiapi/updateconceptsspanishstate', SITE_PATH, $registry);
	    exit;
	}
	
	
	// -------------------------------------------------¨
	// Puutteellisemmat / testaamattomat kutsut
	// -------------------------------------------------¨
	
	
	
	// ei toteutettu
	if ($_GET['rt'] == 'getwordswithoutdescriptions') {
	    Router::executeAction('worder/wikiapi/getenglishnouns', SITE_PATH, $registry);
	    exit;
	}
	
	
	
	// Palauttaa käsitteet joilta puuttuu wiktionarystä napatut definitiot. Nämä valitaan english nounien joukosta.
	// Tätä käytetään definitioneiden läpikäynnissä.
	if ($_GET['rt'] == 'getundefinedenglishnouns') {
	    Router::executeAction('worder/wikiapi/getwiktionaryundefinednouns', SITE_PATH, $registry);
	    exit;
	}
	
	// Asettaa wiktionary definition ja shortdefinition
	if ($_GET['rt'] == 'setwiktionarydefinition') {
	    Router::executeAction('worder/wikiapi/setwiktionarydefinition', SITE_PATH, $registry);
	    exit;
	}
	
	
	echo "<br>Server Error: Unknown route - " . $_GET['rt'];	
?>