<?php



echo "<a href='".getUrl('accounting/assets/showliabilities')."'>Palaa Lainat-listaan</a><br>";
echo "<br>";

$section = new UISection("Velan tiedot", "700px");
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'accounting/assets/updateliability', 'liabilityID');
$section->setWidths("20%","49%","31%");

$field = new UITextField("Name", "name", 'name');
$section->addField($field);

$section->setData($registry->liability);

$section->show();




$table = new UITableSection("Tasearvo", "700px");
$table->setFramesVisible(true);
$table->setOpen(true);
$table->setShowSumRow(true);

$table->addTopSelection($registry->periodID, "periodID", $registry->periods, "fullname", "accounting/assets/showasset&id=" . $registry->asset->assetID);

// TODO: tiikausittain...

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
$table->show();




// TODO: tämä suoritukset on ehkä huono nimi, ideana, että tässä näkyy lyhennykset, mutta koska kyseessä on
//		 yleiset velat, niin nimitetään tätä suoritukset nimellä

$table = new UITableSection("Suoritukset", "700px");
$table->setFramesVisible(true);
$table->setOpen(true);
$table->setShowSumRow(true);

$table->addTopSelection($registry->periodID, "periodID", $registry->periods, "fullname", "accounting/assets/showasset&id=" . $registry->asset->assetID);

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
$table->show();








?>