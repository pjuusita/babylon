<?php



// TODO: Tänne pitäisi lisätä myös ostovelat ja velat henkilöstölle tiedot...

$insertsection = new UISection("Lainan lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/assets/insertasset');
	
$nimifield = new UITextField("Nimi", "Nimi", 'name');
$insertsection->addField($nimifield);

$insertsection->show();




$section = new UITableSection("Lainat", "600px");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), 'Lisää tuotantotekijä');
$section->addButton($button);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'accounting/assets/showliability', 'liabilityID', UIComponent::ACTION_FORWARD);

$column = new UIColumn("Nimi", "name");
$section->addColumn($column);

$section->setData($registry->liabilities);
$section->show();


?>