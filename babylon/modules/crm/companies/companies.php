<?php


$insertsection = new UISection('Yrityksen lisäys','500px');
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'crm/companies/insertcompany');

$field = new UITextField("Nimi", "name", 'name');
$insertsection->addField($field);

$field = new UITextField("Y-tunnus", "businesscode", 'businesscode');
$insertsection->addField($field);

if (count($registry->groups) > 0) {
	$field = new UISelectField("Asiakasryhmä", "name", 'groupID', $registry->groups, 'name');
	$insertsection->addField($field);
}


$insertsection->show();




$table = new UITableSection("Asiakasyritykset","600px");
$table->setOpen(true);
$table->setFramesVisible(false);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Lisää yritys');
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"crm/companies/showcompany","companyID");

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addpersondialog->getID(), 'LisÃ¤Ã¤ henkilÃ¤');
//$table->addButton($button);

$column = new UISortColumn("Nimi", "name", 'crm/companies/showcompanies&sort=nimi');
$table->addColumn($column);

$column = new UISortColumn("Y-tunnus", "businesscode", 'crm/companies/showcompanies&sort=ytunnus');
$table->addColumn($column);

if (count($registry->groups) > 0) {
	$column= new UISelectColumn("Asiakasryhmä", "name", "groupID", $registry->groups);
	$table->addColumn($column);
}

$column= new UISelectColumn("Kotimaa", "name", "countryID", $registry->countries);
$table->addColumn($column);

$table->setData($registry->companies);
$table->show();


?>

