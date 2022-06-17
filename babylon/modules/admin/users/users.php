<?php

// Tänne sortteeraus käyttäjäryhmän perusteella...



$insertsection = new UISection("Uuden käyttäjän lisäys");
$insertsection->setDialog(true);

$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/users/adduser');

$field = new UITextField("Sähköpostiosoite", "email", 'email');
$insertsection->addField($field);

$field = new UITextField("Etunimi", "firstname", 'firstname');
$insertsection->addField($field);

$field = new UITextField("Sukunimi", "lastname", 'lastname');
$insertsection->addField($field);

$field = new UITextField("Puhelinnumero", "phonenumber", 'phonenumber');
$insertsection->addField($field);

$field = new UISelectField("Käyttäjäryhmä","usergroupID","usergroupID",$registry->usergroups, 'name');
$insertsection->addField($field);

$insertsection->show();




$table = new UITableSection("Käyttäjät", "600px");
$table->setLineAction(UIComponent::ACTION_FORWARD, 'admin/users/showuser','itemID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää käyttäjä");
$table->addButton($button);

$column = new UISortColumn("Username", "username", "admin/users&sort=username");
$table->addColumn($column);

$column = new UISortColumn("Etunimi", "firstname", "admin/users&sort=firstnimi");
$table->addColumn($column);

$column = new UISortColumn("Sukunimi", "lastname", "admin/users&sort=lastname");
$table->addColumn($column);

$column = new UISelectColumn("Käyttäjäryhmä", "name", "usergroupID", $registry->usergroups);
$table->addColumn($column);

$column = new UIHiddenColumn("itemID", "itemID");
$table->addColumn($column);

$table->setData($this->registry->users);
$table->show();



?>