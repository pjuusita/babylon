<?php

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
define ("APPLICATIONKEY", 'BABYLONJSON');
define ("DEV", false);
define ("ROOTPHP", 'index.php');
define ("SITEPATH", 'demo');
define ("MYPATH_SEPARATOR", '/');
define ("INSTALLPATH", 'https://www.babelsoft.fi/demo');		// tämä on serveri path josta modulit ladataan
define ("MENUPRESENT", false);
define ("NOFRAMESHANDLER", 'noframes.php');
define ("SAVEROOT", '/home/babelsoftf/domains/babelsoft.fi/upload/');
define ("PDFGEN", false);



include 'app/init.php';
init('babelsoftf_main');
$_SESSION['systemID'] = 1;
$registry->router = new router($registry, SITE_PATH);
$registry->router->load();


//echo "<br>database - " . $_SESSION['database'];

?>