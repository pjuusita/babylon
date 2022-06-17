<?php


echo "<a href='".getUrl('crm/clients/showclients')."'>Palaa asiakkas-tauluun</a><br>";
echo "<h1>" . $registry->client->lastname . " " . $registry->client->firstname . "</h1>";

$section = new UISection('Henkilötiedot','600px');
$section->setOpen(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD, "crm/clients/updateclient", 'clientID');


$field = new UITextField("Etunimi", "firstname", 'Firstname');
$section->addField($field);

$field = new UITextField("Sukunimi", "lastname", 'Lastname');
$section->addField($field);


$field = new UITextField("Puhelinnumero", "phonenumber", 'Phonenumber');
$section->addField($field);

$field = new UISelectField("Titteli","jobtitleID","JobtitleID",$registry->jobtitles, "name");
$section->addField($field);

$field = new UISelectField("Yritys","companyID","CompanyID",$registry->companies, "name");
$section->addField($field);

$section->setData($registry->client);

$section->show();



// Tämä sectioni ainoastaan näkyvissä mikäli yksityisasiakas, mutta ehkä tämä voisi näkyä
// myös niiltä osin kuin kyseinen henkilö on ollut yhteyshenkilönä (tilaajana / hyväksyjänä?) asianomaisessa
// laskussa. Nämä samat tiedot näkyvät asiakkaan omassa intrassa (ja visioissa myös mobiili).



$table = new UITableSection("Laskut","600px");
$table->setOpen(true);
$table->setFramesVisible(true);
$table->setShowSumRow(true);

$table->setLineAction(UIComponent::ACTION_FORWARD,"sales/invoices/showinvoice","invoiceID");


$column = new UISortColumn("Laskupäivä", "invoicedate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

/*
if (count($this->registry->dimensions) > 0) {
	foreach($this->registry->dimensions as $index => $dimension) {
		$column = new UISelectColumn($dimension->abbreviation, "name", "dimension" . $dimension->dimensionID, $registry->dimensionvalues[$dimension->dimensionID]);
		$column->setUndefinedString("-");
		$table->addColumn($column);
	}
}
*/


$column = new UISortColumn("Eräpäivä", "duedate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

//$column = new UISortColumn("Asiakas", "description");
//$table->addColumn($column);

//$column = new UISortColumn("Viite", "referencenumber");
//$table->addColumn($column);

$column = new UISortColumn("Brutto", "grossamount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$table->addColumn($column);

$column = new UISortColumn("Netto", "netamount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$table->addColumn($column);

//$column = new UISortColumn("Tila", "state");
//$table->addColumn($column);

$column = new UISelectColumn("Tila", null, "state", $registry->invoicestates);
$table->addColumn($column);

$column = new UISortColumn("Maksamatta", "unpaidamount");
$table->addColumn($column);


$table->setData($registry->invoices);
$table->show();




$statementrowstable = new UITableSection("Maksusuoritukset","600px");
$statementrowstable->setOpen(true);
$statementrowstable->setFramesVisible(true);
$statementrowstable->setShowSumRow(true);


$column = new UISortColumn("Päiväys", "entrydate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$statementrowstable->addColumn($column);


$column = new UISortColumn("Määrä", "amount", "amount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$statementrowstable->addColumn($column);

/*
$column = new UISortColumn("Saldo", "total", "total");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$statementrowstable->addColumn($column);
*/

$column = new UISelectColumn("Tila", null, "status", $registry->statementrowstatuses);
$statementrowstable->addColumn($column);

$column = new UISortColumn("ReceiptID", "receiptID", "receiptID");
$statementrowstable->addColumn($column);

$statementrowstable->setData($registry->bankstatementrows);
$statementrowstable->show();



$pricelistSection = new UISection("Hinnasto");
$pricelistSection->editable(false);
$pricelistSection->setDebug(true);

$pricelistSection->show();




// ---------------------------------------------------------------------------------------------------
// Hallinta
// ---------------------------------------------------------------------------------------------------

$managementSection = new UISection("Hallinta");
$managementSection->editable(false);
$managementSection->setDebug(true);

$button = new UIButton(UIComponent::ACTION_FORWARD, "crm/clients/removeclient&id=".$registry->client->clientID, "Poista");
$managementSection->addButton($button);

$managementSection->show();




?>