<?php

	// Taa on siirretty utils/section.php toiminnaksi, voidaan ehka poistaa...

	echo "<a href='".getUrl('admin/doctemplates/showdoctemplate&id=' . $registry->element->doctemplateID )."'>Palaa tauluun</a><br>";
	echo "<h1></h1>";
	
	$section = new UISection("Uuden dokumenttipohjaelementti lisays");
	$section->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/doctemplates/updateelement', 'docelementID', false);
	
	$section->setData($registry->element);
	$section->setOpen(true);
	
	
	foreach($registry->columns as $index => $column) {
		if (($column->type != 2) && ($column->type != 1)) {
			$field = UIField::createUIField($column,null);
	
			$section->addField($field);
		}
	
		/*
		if ($column->type == 1) {
			echo "<br>referencetable name muutettu referencetableID";
			exit();
		
			$referencetableID = $column->referencetableID;
			echo "<br>Referencetable - " . $column->name . " - " . $column->type . " - " . $referencetableID;
			$field = UIField::createUIField($column, $registry->$referencetable);
			$section->addField($field);
		}
		*/
	}
	$section->show();
	
?>