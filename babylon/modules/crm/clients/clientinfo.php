<?php


echo "<h1>" . $registry->person->familyname . " " . $registry->person->firstname . "</h1>";

$section = new UISection('Henkilätiedot','600px');
$section->setOpen(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD, "crm/clients/updateclient", 'clientpersonID');


$field = new UITextField("Etunimi", "firstname", 'Firstname');
$section->addField($field);

$field = new UITextField("Sukunimi", "familyname", 'Familyname');
$section->addField($field);

$field = new UITextField("Puhelinnumero", "phonenumber", 'Phonenumber');
$section->addField($field);

$field = new UISelectField("Titteli","jobtitleID","JobtitleID",$registry->jobtitles, "title");
$section->addField($field);

$field = new UISelectField("Yritys","companyID","CompanyID",$registry->companies, "name");
$section->addField($field);

$section->setData($registry->person);

$section->show();



?>