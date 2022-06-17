<?php


echo "<a href='".getUrl('crm/companies/showcompanies')."'>Palaa yritystauluun</a><br>";
echo "<h1>" . $registry->company->name . "</h1>";


$section = new UISection("Perustiedot");
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'crm/companies/updatecompany', 'companyID');


$field = new UITextField("Nimi","name","Name");
$section->addField($field);

$field = new UITextField("Y-Tunnus","businesscode","Businesscode");
$section->addField($field);

if (count($registry->groups) > 0) {
	$field = new UISelectField("Asiakasryhmä","groupID","GroupID", $registry->groups, "name");
	$section->addField($field);
}

$field = new UISelectField("Kotimaa","countryID","CountryID", $registry->countries, "name");
$section->addField($field);

// TODO: Tämä voisi olla jossain listassa, nimikkeellä oletuslaskutustapa?
$field = new UISelectField("Laskutustapa","invoicingmodeID","InvoicingmodeID", $registry->invoicingmodes, "name");
$section->addField($field );



// miten tarkastellaan onko jokin muu moduli käytössä, esim. laskutustieto kentät näkyy ainoastaan jos laskutusmoduli on käytössä

$section->setData($registry->company);
$section->show();


//echo "<br>company - " . $registry->company->countryID;


$section = new UISection("Henkilöt");
$section->setOpen(true);
$section->editable(true);
$section->show();


$section = new UISection("Työkohteet / Sijainnit");		// Tämä liittyy projektit modulin olemassoloon
$section->setOpen(true);
$section->editable(true);
$section->show();






$newaddress = new UISection("Laskutusosoitteen lisäys");
$newaddress->setDialog(true);
$newaddress->setMode(UIComponent::MODE_INSERT);
$newaddress->setSaveAction(UIComponent::ACTION_FORWARD, 'crm/companies/insertinvoiceaddress&companyID=' . $registry->company->companyID);

$field = new UITextField("Katuosoite", "streetaddress", 'streetaddress');
$newaddress->addField($field);

$field = new UITextField("Postitoimipaikka", "city", 'city');
$newaddress->addField($field);

$field = new UITextField("Postinumero", "postalcode", 'postalcode');
$newaddress->addField($field);

$field = new UITextField("Laskutusemail", "email", 'email');
$newaddress->addField($field);

//$field = new UITextField("Maa", "country", 'country');
//$newaddress->addField($field);

$newaddress->show();



if (count($registry->invoiceaddresses) == 0) {		// pelkkä lisäysnappi
	$section = new UISection("Laskutusosoite");		// Tämä liittyy laskutusmodulin olemassaoloon
	$section->setOpen(true);
	$section->editable(true);

	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $newaddress->getID(), "Lisää laskutusosoite");
	$section->addButton($button);
	
	$section->show();
	
} elseif (count($registry->invoiceaddresses) == 1)  {		// muokkaus section
	$section = new UISection("Laskutusosoite");		// Tämä liittyy laskutusmodulin olemassaoloon
	$section->setOpen(true);
	$section->editable(true);
	$field = new UITextField("Osoite","streetaddress","streetaddress");
	$section->addField($field);

	$field = new UITextField("Postitoimipaikka","city","city");
	$section->addField($field);
	
	$field = new UITextField("Postinumero","postalcode","postalcode");
	$section->addField($field);
	
	$field = new UITextField("Laskutusemail","email","email");
	$section->addField($field);
	
	$address = null;
	foreach($registry->invoiceaddresses as $index => $value) $address = $value;
	
	$section->setUpdateAction(UIComponent::ACTION_FORWARD,'crm/companies/updateinvoiceaddress&companyID=' . $registry->company->companyID, 'invoiceaddressID');
	
	// ei toistaiseksi toteutettu täysin, vaatii frameworkkiin korjauksia
	//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $newaddress->getID(), "Lisää laskutusosoite");
	//$section->addButton($button);
	
	$section->setData($value);
	$section->show();
	
} else {											// useampi, tauluna
	
	// Lis
	
}




$section = new UISection("Verkkolaskutusosoitteet");	// Tämä liittyy laskutusmodulin olemassaoloon
$section->setOpen(true);
$section->editable(true);
$section->show();



// ---------------------------------------------------------------------------------------------------
// Hallinta
// ---------------------------------------------------------------------------------------------------

$managementSection = new UISection("Hallinta");
$managementSection->editable(false);
$managementSection->setDebug(true);

$button = new UIButton(UIComponent::ACTION_FORWARD, "crm/companies/removecompany&id=".$registry->company->companyID, "Poista");
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