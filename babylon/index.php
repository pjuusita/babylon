<?php

	header('Content-Type: text/html; charset=utf-8');
	error_reporting(E_ALL ^ E_DEPRECATED);
	define ("DEV", true);
	
	define ("APPLICATIONKEY", 'BABYLONLOGIN');
	define ("SITEPATH", '/demo/');
	define ("SAVEROOT", '/home/babelsoftf/domains/babelsoft.fi/upload/');
	
	define ("FAVICON", 'icon_w.png');
	define ("MYPATH_SEPARATOR", '/');
	//define ("REMOTE_INSTALLSERVER", 'localhost/');
	define ("REMOTE_INSTALLSERVER", 'https://www.babelsoft.fi/');
	
	// TODO: muuta rootphp indexhandleriksi tms. 
	define ("ROOTPHP", 'index.php');		// tämä pitäisi jotenkin napata kutsuvasta tiedostosta init-php:ssä, en tiedä onnistuuko
	define ("MENUPRESENT", true);
	define ("NOFRAMESHANDLER", 'noframes.php');
	define ("PDFHANDLER", 'pdf.php');
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