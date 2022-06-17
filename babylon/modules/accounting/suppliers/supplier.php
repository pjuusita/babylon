<?php



echo "<a href='".getUrl('accounting/suppliers/showsuppliers')."'>Palaa listaan</a><br>";
echo "<h1>" . $registry->supplier->name . "</h1>";


$section = new UISection("Perustiedot", "700px");
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'accounting/suppliers/updatesupplier', 'supplierID');

$nimifield = new UITextField("Nimi", "name", 'name');
$section->addField($nimifield);

$nimifield = new UITextField("IBAN", "iban", 'iban');
$section->addField($nimifield);

$nimifield = new UITextField("Viitenumero", "reference", 'referencenumber');
$section->addField($nimifield);

$field = new UISelectField("Kotimaa","countryID","countryID", $registry->countries, "name");
$section->addField($field);

$paymenttimefield = new UITextField("Maksuaika", "paymenttime", 'paymenttime');
$section->addField($paymenttimefield);

$field = new UISelectField("Maksutapa","paymentmethodID","paymentmethodID", $registry->paymentmethods, "name");
$section->addField($field);

$section->setData($registry->supplier);
$section->show();



$table = new UITableSection("Saldo", "700px");
$table->setFramesVisible(true);
$table->setOpen(true);
$table->setShowSumRow(true);

$table->setLineAction(UIComponent::ACTION_FORWARD,"accounting/receipts/showreceipt","receiptID");

$column = new UISortColumn("R#", "receiptID");
$table->addColumn($column);

$column = new UISortColumn("Date", "entrydate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

$column = new UISortColumn("Text", "text");
$table->addColumn($column);

$column = new UISortColumn("Summa", "amount");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$column = new UISortColumn("Saldo", "saldo");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$table->setData($registry->entries);
$table->show();



/*
$table = new UITableSection("Saldo receipts", "700px");
$table->setFramesVisible(true);
$table->setOpen(true);
$table->setShowSumRow(true);

$table->setLineAction(UIComponent::ACTION_FORWARD,"accounting/receipts/showreceipt","receiptID");

$column = new UISortColumn("R#", "receiptID");
$table->addColumn($column);

$column = new UISortColumn("Date", "receiptdate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

$column = new UISortColumn("Text", "text");
$table->addColumn($column);

$column = new UISortColumn("debet", "debet");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$column = new UISortColumn("credit", "credit");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$column = new UISortColumn("Saldo", "saldo");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$table->setData($registry->receipts);
$table->show();
*/


//echo "<br>periodID - " . $registry->periodID;

$table = new UITableSection("Ostot", "700px");
$table->setFramesVisible(true);
$table->setOpen(true);
//$section->setOpen(true);
$table->setShowSumRow(true);

$table->addTopSelection($registry->periodID, "periodID", $registry->periods, "fullname", "accounting/suppliers/showsupplier&id=" . $registry->supplier->supplierID);

$table->setLineAction(UIComponent::ACTION_FORWARD,"accounting/purchases/showpurchase","purchaseID");

$column = new UISortColumn("#", "purchaseID");
$table->addColumn($column);

$column = new UISortColumn("#", "receiptnumber");
$table->addColumn($column);

$column = new UISortColumn("Laskupäivä", "purchasedate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

$column = new UISortColumn("Eräpäivä", "duedate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

$column = new UISortColumn("ALV", "vatamount");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$column = new UISortColumn("Brutto", "grossamount");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$column = new UILinkColumn("Tosite", "file", "file","accounting/purchases/download");
$table->addColumn($column);

$column = new UISortColumn("Tila", "statestr");
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$table->setData($registry->invoices);
$table->show();





$insertdefaultrow = new UISection("Oletusrivin lisäys");
$insertdefaultrow->setDialog(true);
$insertdefaultrow->setMode(UIComponent::MODE_INSERT);
$insertdefaultrow->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/suppliers/insertdefaultrow&supplierID=' . $registry->supplier->supplierID);

$field = new UISelectField("Kustannuspaikka","costpoolID","costpoolID",$registry->costpools, "name");
$insertdefaultrow->addField($field);

$field = new UISelectField("Arvonlisävero","vatID","vatID",$registry->vats, "short");
$insertdefaultrow->addField($field);

$field = new UISelectField("Kirjanpitotili","accountID","accountID",$registry->accounts, "fullname");
$insertdefaultrow->addField($field);

$insertdefaultrow->show();



$editdefaultrow = new UISection("Oletusrivin muokkaus");
$editdefaultrow->setDialog(true);
$editdefaultrow->setMode(UIComponent::MODE_INSERT);
$editdefaultrow->setUpdateAction(UIComponent::ACTION_FORWARD, 'accounting/suppliers/updatedefaultrow&supplierID=' . $registry->supplier->supplierID, "rowID");

$field = new UISelectField("Kustannuspaikka","costpoolID","costpoolID",$registry->costpools, "name");
$editdefaultrow->addField($field);

$field = new UISelectField("Arvonlisävero","vatID","vatID",$registry->vats, "short");
$editdefaultrow->addField($field);

$field = new UISelectField("Kirjanpitotili","accountID","accountID",$registry->accounts, "fullname");
$editdefaultrow->addField($field);

$editdefaultrow->show();






$invoicerowstable = new UITableSection("Oletusrivit", "700px");
$invoicerowstable->setOpen(true);
$invoicerowstable->setFramesVisible(true);



$invoicerowstable->setDeleteAction(UIComponent::ACTION_FORWARD, 'accounting/suppliers/removedefaultrow&supplierID=' . $registry->supplier->supplierID, 'rowID');

$invoicerowstable->setLineAction(UIComponent::ACTION_OPENDIALOG, $editdefaultrow->getID(), "rowID");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertdefaultrow->getID(), "Lisää oletusrivi");
$invoicerowstable->addButton($button);

$column = new UISortColumn("#", "rowID", "rowID");
$invoicerowstable->addColumn($column);

$column = new UISelectColumn("Kustannuspaikka", "name", "costpoolID", $registry->costpools);
$invoicerowstable->addColumn($column);

$column = new UISelectColumn("ALV", "short", "vatID", $registry->vats);
$invoicerowstable->addColumn($column);

$column = new UISelectColumn("Kirjanpitotili", "fullname", "accountID", $registry->accounts);
$invoicerowstable->addColumn($column);

$invoicerowstable->setData($registry->defaultrows);
$invoicerowstable->show();



?>