<?php


echo "<a href='".getUrl('accounting/assets/showassets')."'>Palaa Tuotantotekijät listaan</a><br>";
echo "<br>";

$section = new UISection("Omaisuuden tiedot", "700px");
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'accounting/assets/updateasset', 'assetID');
$section->setWidths("20%","49%","31%");

//$field = new UISelectField("Parent", "parentID", 'parentID', $registry->costpools, 'name');
//$field->setPredictive(true);
//$section->addField($field);

$field = new UITextField("Name", "name", 'name');
$section->addField($field);

//$column = new UISelectField("Kustannuspaikka", "costpoolID", "costpoolID", $registry->costpools, 'name');
//$column->setPredictive(true);
//$section->addField($column);

$column = new UISelectField("Yläluokka", "parentID", "parentID", $registry->assets, 'name');
$column->setPredictive(true);
$section->addField($column);


//$column = new UISelectField("Tasetili", "assetaccountID", "assetaccountID", $registry->accounts, 'fullname');
//$column->setPredictive(true);
//$section->addField($column);

//$column = new UISelectField("Tulotili", "incomeaccountID", "incomeaccountID", $registry->accounts, 'fullname');
//$column->setPredictive(true);
//$section->addField($column);

//$column = new UISelectField("Oletus ALV", "vatID", "vatID", $registry->vats, 'name');
//$section->addField($column);

$section->setData($registry->asset);

$section->show();




$table = new UITableSection("Tasearvo", "700px");
$table->setFramesVisible(true);
$table->setOpen(true);
$table->setShowSumRow(true);

$table->addTopSelection($registry->periodID, "periodID", $registry->periods, "fullname", "accounting/assets/showasset&id=" . $registry->asset->assetID);

// TODO: tiikausittain...

$column = new UISortColumn("E#", "entryID");
$table->addColumn($column);

$column = new UISortColumn("R#", "receiptID");
$table->addColumn($column);

$column = new UISortColumn("Date", "entrydate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

$column = new UISelectColumn("Kustannuspaikka", "name", "costpoolID", $registry->costpools);
$table->addColumn($column);

$column = new UISortColumn("Summa", "amount");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

//$column = new UISortColumn("Saldo", "saldo");
//$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
//$column->setAlign(Column::ALIGN_RIGHT);
//$table->addColumn($column);

$table->setData($registry->assetentries);
$table->show();






$table = new UITableSection("Kulut", "700px");
$table->setFramesVisible(true);
$table->setOpen(true);
$table->setShowSumRow(true);

$table->addTopSelection($registry->periodID, "periodID", $registry->periods, "fullname", "accounting/assets/showasset&id=" . $registry->asset->assetID);


$column = new UISortColumn("E#", "entryID");
$table->addColumn($column);

$column = new UISortColumn("R#", "receiptID");
$table->addColumn($column);

$column = new UISortColumn("Date", "entrydate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

$column = new UISelectColumn("Kustannuspaikka", "name", "costpoolID", $registry->costpools);
$table->addColumn($column);

$column = new UISortColumn("Summa", "amount");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

//$column = new UISortColumn("Saldo", "saldo");
//$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
//$column->setAlign(Column::ALIGN_RIGHT);
//$table->addColumn($column);

$table->setData($registry->expenseentries);

/*
$column = new UISortColumn("#", "receiptID");
$table->addColumn($column);

$column = new UISortColumn("Date", "date");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

$column = new UISortColumn("Text", "text");
$table->addColumn($column);

$column = new UISortColumn("Summa", "sum");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$column = new UISortColumn("Saldo", "saldo");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$table->setData($registry->receipts);
*/
$table->show();








?>