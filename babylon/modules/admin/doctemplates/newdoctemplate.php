<?php



echo "<a href='".getUrl('admin/doctemplates/showdoctemplates')."'>Palaa tauluun</a><br>";
echo "<h1></h1>";

$insertsection = new UIInsertSection("Uuden dokumenttipohjan lisays");
$insertsection->setInsertAction('admin/doctemplates/inserttemplate');
//$insertsection->setSuccessAction('admin/doctemplates/showdoctemplates');	// TODO: funktiota muutettu

$namefield = new UITextField("Templaten nimi", "name", 'Name');
$orientationfield = new UISelectField("Orientaatio", "orientation", 'Orientation', $registry->orientations);

$insertsection->addField($namefield);
$insertsection->addField($orientationfield);
$insertsection->show();

