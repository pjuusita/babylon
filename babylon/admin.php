<?php

	header('Content-Type: text/html; charset=utf-8');
	error_reporting(E_ALL ^ E_DEPRECATED);
	define ("DEV", false);
	define ("APPLICATIONKEY", 'BABYLONADMIN');
	
	define ("MASTERDB", 'babelsoftf_login');		// tämä voi olla ulkoinen tietokanta joissakin tapauksissa
	define ("MYPATH_SEPARATOR", '/');
	define ("SITEPATH", '/demo/');
	define ("SAVEROOT", '/home/babelsoftf/domains/babelsoft.fi/upload/');
	
	
	
	define ("FAVICON", 'icon_a.png');
	define ("ROOTPHP", 'admin.php');		// tämä pitäisi jotenkin napata kutsuvasta tiedostosta init-php:ssä, en tiedä onnistuuko
	define ("MENUPRESENT", false);
	define ("NOFRAMESHANDLER", 'noframes.php');
	define ("PDFGEN", false);
	
	include 'app/init.php';
	init(MASTERDB);
	
	$registry->router = new router($registry, SITE_PATH);
	
	$registry->router->load();
?>