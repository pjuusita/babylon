<?php

sleep(1);
// Korvattu UI Table --> UITableSection
$table = new UITableSection("Ostokirjaukset");






$receptdatecolumn = new UISortColumn("Päiväys", "entrydate", "accounting/journal/showjournal&sort=entrydate");
$receptdatecolumn->setColumnType(Column::COLUMNTYPE_DATE);
$receptdatecolumn->setLink('accounting/journal/showentry','entryID');

$receiptnumbercolumn = new UISortColumn("Tositeumero", "receiptnumber", "accounting/journal/showjournal&sort=receiptnumber");
$receiptnumbercolumn->setColumnType(Column::COLUMNTYPE_STRING);

$suppliercolumn = new UISortColumn("Tositeumero", "receiptnumber", "accounting/journal/showjournal&sort=receiptnumber");
$suppliercolumn->setColumnType(Column::COLUMNTYPE_STRING);

$suppliercolumn = new UISelectColumn("Saaja", "supplierID", $this->registry->suppliers, "accounting/journal/showjournal");
$suppliercolumn->setColumnType(Column::COLUMNTYPE_INTEGER);


$table->addColumn($receptdatecolumn);
$table->addColumn($receiptnumbercolumn);
$table->addColumn($suppliercolumn);


$table->setData($this->registry->journalentries);
$table->show();



// tasta pitaa tehda compactlist tyyppinen ratkaisu
/*
$table = new UITableLevel2("Tilitapahtumat", "800px");

$receptdatecolumn = new UISortColumn("Paivays", "entrydate", "accounting/journal/showjournal&sort=entrydate");
$receptdatecolumn->setColumnType(Column::COLUMNTYPE_DATE);
$receptdatecolumn->setLink('accounting/journal/showentry','entryID');

$receiptnumbercolumn = new UISortColumn("Tositeumero", "receiptnumber", "accounting/journal/showjournal&sort=receiptnumber");
$receiptnumbercolumn->setColumnType(Column::COLUMNTYPE_STRING);

$suppliercolumn = new UISortColumn("Tositeumero", "receiptnumber", "accounting/journal/showjournal&sort=receiptnumber");
$suppliercolumn->setColumnType(Column::COLUMNTYPE_STRING);

$suppliercolumn = new UISelectColumn("Saaja", "supplierID", $this->registry->suppliers, "accounting/journal/showjournal");
$suppliercolumn->setColumnType(Column::COLUMNTYPE_INTEGER);


$table->addColumn($receptdatecolumn,1);
$table->addColumn($receiptnumbercolumn,1);
$table->addColumn($suppliercolumn,1);



$accountnumbercolumn = new UISelectColumn("Tilinro", "accountID", $this->registry->accountnumbers, "", 100);
$accountnamecolumn = new UISelectColumn("Tilinnimi", "accountID", $this->registry->accountnames,400);
$debetcolumn = new UISortColumn("Debet","debet","Debet",100);
$creditcolumn = new UISortColumn("Credit","credit","Credit",100);

$table->addColumn($accountnumbercolumn,2);
$table->addColumn($accountnamecolumn,2);
$table->addColumn($debetcolumn,2);
$table->addColumn($creditcolumn,2);

$button = new UIButton(UIComponent::ACTION_FORWARD, "accounting/journal/shownewentry", 'Lisaa uusi');
$table->addButton($button);


$table->setData($this->registry->journalentries);


$table->show();
*/

foreach($this->registry->journalentries as $index => $entry) {
	//echo "<br>Entry - " . $index;
	//echo "<br>childcount - " . count($entry->getChildren());
}




?>