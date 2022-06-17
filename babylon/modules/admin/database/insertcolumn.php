<?php

echo "<a href='".getUrl('admin/database/showdatabasetables')."'>Palaa tietokantalistaan</a>";

	$insertSection = new UIInsertSection("Uusi Sarake");
	$insertSection->setOpen(true);
	$insertSection->setInsertAction('admin/database/insertcolumn&tableid=' . $registry->tableID, true);
	$insertSection->setSuccessAction('admin/database/showdatabasetables');		// TODO: Funktiota muutettu
	
	$tablenameField = new UIFixedTextField("Table",$registry->table->name);
	$insertSection->addField($tablenameField);
	
	$variablenameField = new UITextField("Muuttujannimi", "variablename", 'Variablename');
	$insertSection->addField($variablenameField);
	
	$columnnameField = new UITextField("Sarakkeennimi", "columnname", 'Columnname');
	$insertSection->addField($columnnameField);
	
	$nameField = new UITextField("Otsikko", "name", 'Name');
	$insertSection->addField($nameField);
	
	$typeselect = new UISelectField("Tyyppi", "type", 'Type',$this->registry->columnstypes);
	$insertSection->addField($typeselect);
	$typeselect->setOnChange("typechanged()");
	
	$referenceselect = new UISelectField("Viitetaulu", "referencetableID", 'ReferencetableID', $this->registry->tables);
	$insertSection->addField($referenceselect);
	
	$minvalue = new UITextField("Minimi", "min", 'Min');
	$insertSection->addField($minvalue);
	
	$maxvalue = new UITextField("Maksimi", "max", 'Max');
	$insertSection->addField($maxvalue);
	
	$defaultvalue = new UITextField("Oletusarvo", "defaultvalue", 'Defaultvalue');
	$insertSection->addField($defaultvalue);
	
	
	
	echo "<script>";
	echo "	function typechanged() {";
	
	echo "	 	var typefieldID 		 	 = '#".$typeselect->getEditFieldID()."';";
	echo "	 	var referencefieldID 		 	 = '#".$referenceselect->getEditFieldID()."';";
	echo "	 	var minvaluefieldID 		 	 = '#".$minvalue->getEditFieldID()."';";
	echo "	 	var maxvaluefieldID 		 	 = '#".$maxvalue->getEditFieldID()."';";
	echo "	 	var defaultvaluefieldID 		 	 = '#".$defaultvalue->getEditFieldID()."';";
	
	echo "	 		var type = $(typefieldID).val();";
	//echo "			alert('type is '+type);";
	
	echo "			$(referencefieldID).removeAttr('disabled');";
	echo "			$(referencefieldID).removeClass('uitextfield-disabled');";
	echo "			$(referencefieldID).addClass('uitextfield');";
	
	echo "			$(minvaluefieldID).removeAttr('disabled');";
	echo "			$(minvaluefieldID).removeClass('uitextfield-disabled');";
	echo "			$(minvaluefieldID).addClass('uitextfield');";
	
	echo "			$(maxvaluefieldID).removeAttr('disabled');";
	echo "			$(maxvaluefieldID).removeClass('uitextfield-disabled');";
	echo "			$(maxvaluefieldID).addClass('uitextfield');";
	
	echo "			$(defaultvaluefieldID).removeAttr('disabled');";
	echo "			$(defaultvaluefieldID).removeClass('uitextfield-disabled');";
	echo "			$(defaultvaluefieldID).addClass('uitextfield');";
	
	echo "			switch(type) {";
	
	echo "				case '1':";			// table reference
	
	echo "					$(referencefieldID).removeAttr('disabled');";
	echo "					$(referencefieldID).addClass('uitextfield');";
	echo "					$(defaultvaluefieldID).removeClass('uitextfield-disabled');";
	
	echo "					$(defaultvaluefieldID).attr('disabled', 'disabled');";
	echo "					$(defaultvaluefieldID).addClass('uitextfield-disabled');";
	echo "					$(defaultvaluefieldID).removeClass('uitextfield');";
	
	echo "					$(minvaluefieldID).attr('disabled', 'disabled');";
	echo "					$(minvaluefieldID).addClass('uitextfield-disabled');";
	echo "					$(minvaluefieldID).removeClass('uitextfield');";
	
	echo "					$(maxvaluefieldID).attr('disabled', 'disabled');";
	echo "					$(maxvaluefieldID).addClass('uitextfield-disabled');";
	echo "					$(maxvaluefieldID).removeClass('uitextfield');";
	
	
	echo "					break;";
	
	echo "				case '16':";			// table reference
	
	echo "					$(referencefieldID).removeAttr('disabled');";
	echo "					$(referencefieldID).addClass('uitextfield');";
	echo "					$(defaultvaluefieldID).removeClass('uitextfield-disabled');";
	
	echo "					$(defaultvaluefieldID).attr('disabled', 'disabled');";
	echo "					$(defaultvaluefieldID).addClass('uitextfield-disabled');";
	echo "					$(defaultvaluefieldID).removeClass('uitextfield');";
	
	echo "					$(minvaluefieldID).attr('disabled', 'disabled');";
	echo "					$(minvaluefieldID).addClass('uitextfield-disabled');";
	echo "					$(minvaluefieldID).removeClass('uitextfield');";
	
	echo "					$(maxvaluefieldID).attr('disabled', 'disabled');";
	echo "					$(maxvaluefieldID).addClass('uitextfield-disabled');";
	echo "					$(maxvaluefieldID).removeClass('uitextfield');";
	
	
	echo "					break;";
	
	echo "				case '2':";			// table reference
	
	echo "					$(referencefieldID).attr('disabled', 'disabled');";
	echo "					$(referencefieldID).addClass('uitextfield-disabled');";
	echo "					$(referencefieldID).removeClass('uitextfield');";
	
	echo "					$(defaultvaluefieldID).attr('disabled', 'disabled');";
	echo "					$(defaultvaluefieldID).addClass('uitextfield-disabled');";
	echo "					$(defaultvaluefieldID).removeClass('uitextfield');";
	
	echo "					$(minvaluefieldID).attr('disabled', 'disabled');";
	echo "					$(minvaluefieldID).addClass('uitextfield-disabled');";
	echo "					$(minvaluefieldID).removeClass('uitextfield');";
	
	echo "					$(maxvaluefieldID).attr('disabled', 'disabled');";
	echo "					$(maxvaluefieldID).addClass('uitextfield-disabled');";
	echo "					$(maxvaluefieldID).removeClass('uitextfield');";
	
	
	echo "					break;";
	
	echo "				default:";			// table reference
	
	echo "					$(referencefieldID).attr('disabled', 'disabled');";
	echo "					$(referencefieldID).addClass('uitextfield-disabled');";
	echo "					$(referencefieldID).removeClass('uitextfield');";
	
	echo "					$(defaultvaluefieldID).addClass('uitextfield');";
	echo "					$(minvaluefieldID).addClass('uitextfield');";
	echo "					$(maxvaluefieldID).addClass('uitextfield');";
	echo "					$(defaultvaluefieldID).addClass('uitextfield');";
	echo "					break;";
	echo "			}";
	
	//echo "		alert('jejeeea');";
	echo "	}";
	echo "</script>";
	
	$insertSection->show();
	

?>