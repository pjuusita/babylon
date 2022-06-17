<?php
	header('Content-Type: text/html; charset=utf-8');
	error_reporting(E_ALL ^ E_DEPRECATED);
	
	
	define ("DEV", false);
	
	define ("APPLICATIONKEY", 'BABYLONLOGIN');
	define ("SITEPATH", '/demo/');
	define ("FAVICON", 'icon_w.png');
	define ("ROOTPHP", 'dev.php');		// tämä pitäisi jotenkin napata kutsuvasta tiedostosta init-php:ssä, en tiedä onnistuuko
	define ("MYPATH_SEPARATOR", '/');
	define ("REMOTE_INSTALLSERVER", 'https://www.babelsoft.fi/');
	define ("MENUPRESENT", true);
	define ("NOFRAMESHANDLER", 'noframes.php');
	define ("PDFGEN", false);
	
	
	include 'app/init.php';
		
	if (isset($_SESSION['database'])) {
		init($_SESSION['database']);
	} else {
		//echo "<br>No database";
		init();
	}
	
	$registry->router = new router($registry, SITE_PATH);
	
	$registry->router->load();

?>