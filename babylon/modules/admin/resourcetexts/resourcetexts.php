<?php
	

	$filterbox = new UIFilterBox();
	$filterbox->addSelectFilter($this->registry->moduleID, $this->registry->modules, "admin/resourcetexts/showresourcetexts", "","moduleID", "name");
	
	
	echo "<table style='width:600px;'>";
	echo "	<tr>";
	echo "		<td style='width:60%;vertical-align:bottom'>";
	echo "		</td>";
	echo "		<td style='width:40%;text-align:right;'>";
	$filterbox->show();
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	
	
	//echo "<br>LanguageID - " . $registry->languageID;
	//echo "<br>LanguageID - " . $_SESSION['languageID'];
	
	$languagessection = new UISection('Kieli','500px');
	$languagessection->setDialog(true);
	$languagessection->setMode(UIComponent::MODE_EDIT);
	$languagessection->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/resourcetexts/showresourcetexts');
	
	$field= new UISelectField("Kieli","languageID","languageID", $registry->languages, "languagename");
	$languagessection->addField($field);
	
	$row = new Row();
	$row->languageID = $registry->languageID;
	$languagessection->setData($row);
	$languagessection->show();
	
	

	$insertsection = new UISection("Resurssitekstin lisäys");
	$insertsection->setDialog(true);
	$insertsection->setMode(UIComponent::MODE_INSERT);
	$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/resourcetexts/insertresourcetext');

	$field= new UISelectField("Module","moduleID","moduleID", $registry->modules, "name");
	$insertsection->addField($field);
	
	$nimifield = new UITextField("Key", "resourcekey", 'resourcekey');
	$insertsection->addField($nimifield);
	
	$valuefield = new UIMultiLangTextField("Value","value","value",$registry->languages);
	$insertsection->addField($valuefield);
	
	$row = new Row();
	$row->moduleID = $registry->moduleID;
	$insertsection->setData($row);
	$insertsection->show();
	
	



	$editsection = new UISection("Resurssitekstin muokkaus");
	
	$editsection->setDialog(true);
	$editsection->setMode(UIComponent::MODE_EDIT);
	$editsection->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/resourcetexts/updateresourcetext', 'stringID');
	
	$field= new UISelectField("Module","moduleID","moduleID", $registry->modules, "name");
	$editsection->addField($field);
	
	$nimifield = new UITextField("Key", "resourcekey", 'resourcekey');
	$editsection->addField($nimifield);
	
	//$nimifield = new UITextField("Value", "value", 'value');
	//$editsection->addField($nimifield);
	
	$valuefield = new UIMultiLangTextField("Value2","value","value",$registry->languages);
	$editsection->addField($valuefield);
	
	$row = new Row();
	$row->moduleID = $registry->moduleID;
	$editsection->setData($row);
	$editsection->show();
	
	
	
	
	
	if ($this->registry->moduleID != 0) {
		$table = new UITableSection("Resurssitekstit - " . $this->registry->module->name, "600px");
	} else {
		$table = new UITableSection("Resurssitekstit", "600px");
	}
	$table->showLineNumbers(true);
	$table->setSettingsAction($languagessection);
	$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editsection->getID(),"stringID");
	
	$nameColumn = new UISortColumn("ID", "stringID");
	$table->addColumn($nameColumn);
	
	$nameColumn = new UISortColumn("Key", "resourcekey");
	$table->addColumn($nameColumn);
	
	$language = $registry->languages[$registry->languageID];
	$column = new UIMultilangColumn($language->languagename, "value", $registry->languageID);
	$table->addColumn($column);
	
	//$nameColumn = new UISortColumn("Value - Suomi", "value");
	//$table->addColumn($nameColumn);
	
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
	$table->addButton($button);
	
	$table->setData($registry->resourcetexts);
	$table->show();
	
	echo "<br>count: " . count($registry->resourcetexts);
?>