<?php



echo "<a href='".getUrl('admin/timesheettemplates/showtimesheettemplates')."'>Palaa tauluun</a><br>";
echo "<h1>" . $registry->timesheettemplate->name . "</h1>";

$section = new UISection("Dokumenttipohja");
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'system/doctemplates/updatetemplate', 'doctemplateID');
$section->setOpen(true);

$namefield = new UITextField("Templaten nimi", "name", 'Name');

$section->addField($namefield);
$section->setData($registry->timesheettemplate);
$section->show();


// Korvattu UI Table --> UITableSection
$columnstable = new UITableSection("", "100%");
$columnstable->setData($registry->elements);
$columnstable->innerTable(true);
$columnstable->setLineaction(UIComponent::ACTION_FORWARD, "admin/timesheettemplates/showelement", 'ElementID');

foreach($registry->columns as $index => $column) {
	if ($column->type == 2) {
		$uicolumn = UIColumn::createUIColumn($column,null);
		$columnstable->addColumn($uicolumn);
	}
}

foreach($registry->columns as $index => $column) {
	if (($column->type != 2) && ($column->type != 1)) {
		$uicolumn = UIColumn::createUIColumn($column,null);
		$columnstable->addColumn($uicolumn);
	}
	
	if ($column->type == 1) {
		
		echo "<br>referencetable name muutettu referencetableID";
		exit();
			
		$referencetableID = $column->referencetableID;
		$uicolumn = UIColumn::createUIColumn($column,$registry->$referencetableID);
		$columnstable->addColumn($uicolumn);
		
	}
}

$columnsSection = new UISection("Sarakkeet");
$columnsSection->editable(false);
$columnsSection->setOpen(true);
$columnsSection->addButton("Lisaa sarake","admin/timesheettemplates/shownewtimesheetelement&id=" . $registry->timesheettemplate->templateID);

$columnsSection->addField($columnstable);
$columnsSection->show();


$managementSection = new UISection("Hallinta");
$managementSection->editable(false);
$managementSection->addButton("Poista dokumenttipohja","admin/timesheettemplates/removeelement&id=" . $registry->timesheettemplate->templateID);
$managementSection->show();


?>

