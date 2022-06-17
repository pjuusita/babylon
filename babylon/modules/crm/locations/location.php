<?php


//echo "<br>locationtype - " . $registry->location->locationtypeID;

echo "<a href='".getUrl('crm/locations/showlocations')."'>Palaa yritystauluun</a><br>";
echo "<h1>" . $registry->location->name . "</h1>";


$section = new UISection("Perustiedot");
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'crm/locations/updatelocation', 'locationID');

$field = new UITextField("Nimi", "name", 'name');
$section->addField($field);

$field = new UISelectField("Yritys", "companyID", 'companyID', $registry->companies, 'name');
$section->addField($field);

$field = new UITextField("Osoite", "streetaddress", 'Streetaddress');
$section->addField($field);

$field = new UITextField("Postinumero", "postalcode", 'Postalcode');
$section->addField($field);

$field = new UITextField("Postitoimipaikka", "city", 'City');
$section->addField($field);

$field = new UISelectField("Työkohdetyyppi", "locationtypeID", 'locationtypeID', $registry->locationtypes, 'name');
$section->addField($field);


//$field = new UISelectField("Työkohdetyyppi", "name", 'locationtypeID', $registry->locationtypes, 'name');
//$section->addField($field);

$section->setData($registry->location);
$section->show();



$section = new UISection("Toimeksiannot");
$section->setOpen(true);
$section->editable(true);
$section->show();

/*
$section = new UISection("Sijainnit");
$section->setOpen(true);
$section->editable(true);
$section->show();
*/


// ---------------------------------------------------------------------------------------------------
// Hallinta
// ---------------------------------------------------------------------------------------------------

$managementSection = new UISection("Hallinta");
$managementSection->editable(false);
$managementSection->setDebug(true);

$button = new UIButton(UIComponent::ACTION_FORWARD, "crm/locations/removelocation&id=".$registry->location->locationID, "Poista");
$managementSection->addButton($button);

$managementSection->show();





// toteuta savecallback
/*
$perustiedotsection = new UISection("Perustiedot");
$perustiedotsection->setData($registry->yritys);
$perustiedotsection->setOpen(true);
$perustiedotsection->setUpdateAction(UIComponent::ACTION_FORWARD,'crm/companies/updatecompany', 'yritysID');
	$nimifield = new UITextField("Yrityksen nimi", "nimi", 'Nimi');
	//$nimifield->setMaxValue('10');		 				// vanha maxvalue => 100
	//$nimifield->setNotEmptyFunctionality();  			// vanha 'notempty' => 'onkeyup'
	$nimifield->setSaveCallback('changepagetitle');   	// 'savecallback' => 'changepagetitle'

	$ytunnusfield = new UITextField("Y-tunnus", "ytunnus", "Ytunnus");
	$ytunnusfield->setMaxLength("9");
	$ytunnusfield->setMinLength("8");


	$clientgroupfield = new UISelectField("Asiakasryhma", "asiakasryhmaID", 'AsiakasryhmaID', $this->registry->asiakasryhmat);
	
	
$perustiedotsection->addField($nimifield);
$perustiedotsection->addField($ytunnusfield);
$perustiedotsection->addField($clientgroupfield);
$perustiedotsection->show();
*/


?>