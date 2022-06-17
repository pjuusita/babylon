<?php



$section = new UITableSection("Ryhmien sanat","800px");
$section->setOpen(true);
//$section->editable(true);
//$section->setFramesVisible(true);

//$button = new UIButton(UIComponent::ACTION_FORWARD, 'worder/words/addfailforms&lang=' . $registry->language->languageID . '&wordid=' . $registry->word->wordID, 'Lisää väärä muoto');
//$section->addButton($button);

//$button = new UIButton(UIComponent::ACTION_FORWARD, 'worder/words/checkallforms&lang=' . $registry->language->languageID . '&wordid=' . $registry->word->wordID, 'Tsekkaa kaikki');
//$section->addButton($button);

$column = new UISimpleColumn("ID", 0);
$section->addColumn($column);

$column = new UISimpleColumn("Käsite", 1);
$section->addColumn($column);

$column = new UISimpleColumn("G1", 2);
$section->addColumn($column);

$column = new UISimpleColumn("G2", 3);
$section->addColumn($column);

$column = new UISimpleColumn("G3", 4);
$section->addColumn($column);

//$column = new UISimpleColumn("RowID", 4);
//$section->addColumn($column);

$section->setData($registry->table);
$section->show();

echo "<br>Count 1 - " . $registry->count1;
echo "<br>Count 2 - " . $registry->count2;
echo "<br>Count 3 - " . $registry->count3;

?>