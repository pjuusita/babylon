<?php




$insertsection = new UISection("Pelaajan lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);

$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'wordino/players/insertplayer');

$nimifield = new UITextField("Nimi", "name", 'name');
$insertsection->addField($nimifield);

$nimifield = new UITextField("Kuvaus", "description", 'description');
$insertsection->addField($nimifield);

$field = new UISelectField("Sourcelanguage","languageID","sourcelanguageID", $registry->languages, "name");
$insertsection->addField($field);

$field = new UISelectField("Targetlanguage","languageID","targetlanguageID", $registry->languages, "name");
$insertsection->addField($field);

$insertsection->show();




$table = new UITableSection("Pelaajat", "600px");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD, 'wordino/players/showplayer','playerID');


$wordclassIDcolumn = new UISortColumn("PlayerID", "playerID", "worder/roles/showrole");
$wordclassIDcolumn->setColumnType(Column::COLUMNTYPE_INTEGER);
$table->addColumn($wordclassIDcolumn);

$nimicolumn = new UISortColumn("Nimi", "name", "wordino/players/showplayer&sort=nimi");
$nimicolumn->setColumnType(Column::COLUMNTYPE_STRING);
$table->addColumn($nimicolumn);



$table->setData($this->registry->players);
$table->show();


?>