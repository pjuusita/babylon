<?php

	$insertsection = new UISection("Käyttäjäryhmän lisäys");
	$insertsection->setDialog(true);
	$insertsection->setMode(UIComponent::MODE_INSERT);
	$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/usergroups/insertusergroup');
	
	$field = new UITextField("Ryhmän nimi", "name", 'name');
	$insertsection->addField($field);
	
	$insertsection->show();
	
	
	
	$section = new UITableSection("Käyttäjäryhmät", "600px");
	$section->setLineAction(UIComponent::ACTION_FORWARD, 'admin/usergroups/showusergroup','usergroupID');
	//$section->showTableHeader(false);
	
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
	$section->addButton($button);
	
	$column = new UISortColumn("", "name", "admin/usergroups/showusergroups");
	$column->setColumnType(Column::COLUMNTYPE_STRING);
	$section->addColumn($column);
	
	
	$section->setData($this->registry->usergroups);
	
	$section->show();
	
	


?>