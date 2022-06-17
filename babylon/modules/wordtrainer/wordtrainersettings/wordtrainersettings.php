<?php

// Ainakin tänne pitäisi lisätä dailygoal asetus

// Target ja source kielien valinta

// Käytettävissä olevat kielet, jotenkin täpällä näkyvissä



$section = new UISection("Opiskeluasetukset");
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD, 'wordtrainer/wordtrainersettings/updatesettings', 'systemID');

$field = new UISelectField("Päivätavoite", "dailygoal", 'dailygoal', $registry->dailygoals);
$section->addField($field);

$field = new UISelectField("Äidinkieli", "sourcelanguageID", 'sourcelanguageID', $registry->languages, "name");
$section->addField($field);

$field = new UISelectField("Opeteltava kieli", "targetlanguageID", 'targetlanguageID', $registry->languages, "name");
$section->addField($field);

$section->setData($registry->settings);
$section->show();


$insertsection = new UISection("Kielen lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'wordtrainer/wordtrainersettings/insertlanguage');

$field = new UITextField("Kielen nimi", "name", 'name');
$insertsection->addField($field);

$insertsection->show();




$table = new UITableSection("Kielet", '600px');
$table->setOpen(true);
$table->setFramesVisible(true);


$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää kieli");
$table->addButton($button);

$nimicolumn = new UISortColumn("LanguageID", "languageID", "");
$table->addColumn($nimicolumn);

$nimicolumn = new UISortColumn("Nimi", "name", "");
$table->addColumn($nimicolumn);

$nimicolumn = new UISortColumn("Aktiivinen", "active", "");
$table->addColumn($nimicolumn);

$table->setData($registry->languages);
$table->show();


?>