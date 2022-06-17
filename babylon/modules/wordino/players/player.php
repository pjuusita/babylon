<?php


echo "<h1>" . $this->registry->player->name . "</h1>";

$section = new UISection("Pelaaja");
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/features/updateplayer', 'playerID');

$field = new UITextField("Nimi", "name", 'name');
$section->addField($field);

$field = new UITextField("Kuvaus", "description", 'description');
$section->addField($field);

$field = new UISelectField("Source","sourcelanguageID","languageID", $registry->languages, "name");
$section->addField($field);

$field = new UISelectField("Parent","targetlanguageID","languageID", $registry->languages, "name");
$section->addField($field);

$section->setData($this->registry->player);
$section->show();



?>