<?php


echo "<h1>Etusivu</h1>";

echo "<br>system/frontpage";
echo "<br>Database - " . $_SESSION['database'];
echo "<br>server - " . $_SERVER['SERVER_ADDR'];
echo "<br>server name - " . $_SERVER['SERVER_NAME'];
echo "<br>systemID - " . $_SESSION['systemID'];
echo "<br>template - " . $_SESSION['template'];
echo "<br>mastersystem - " . $_SESSION['mastersystemID'];
if (isset($_SESSION['companyID'])) {
	echo "<br>companyID - " . $_SESSION['companyID'];
} else {
	
}
echo "<br>usergroupID - " . $_SESSION['usergroupID'];
echo "<br>UserID - " . $_SESSION['userID'];
echo "<br>LanguageID - " . $_SESSION['languageID'];
echo "<br>Rootphp - " . ROOTPHP;
echo "<br>PDFHANDLER - " . PDFHANDLER;

?>