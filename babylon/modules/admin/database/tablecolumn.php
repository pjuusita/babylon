<?php


echo "<a href='".getUrl('admin/database/showdatabasetable&id=' . $registry->column->tableID )."'>Palaa taulun tietoihin</a>";

$section = new UISection("Taulun sarakkeen tiedot");
$section->setOpen(true);
$section->setData($registry->column);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/database/updatecolumn', 'columnID');

$variablenameField = new UITextField("Muuttujannimi", "variablename", 'variablename');
$section->addField($variablenameField);

$columnnameField = new UITextField("Sarakkeennimi", "columnname", 'columnname');
$section->addField($columnnameField);

$nameField = new UITextField("Otsikko", "name", 'name');
$section->addField($nameField);

$typeselect = new UISelectField("Tyyppi", "type", 'type',$this->registry->columnstypes);
$section->addField($typeselect);

$obligatoryselect = new UIBooleanField("Pakollinen", "obligatory", 'obligatory', $this->registry->obligatoryvalues);
$section->addField($obligatoryselect);


$referencetable = new UISelectField("Viitetaulu", "referencetableID", 'referencetableID', $this->registry->tables, 'name');
$section->addField($referencetable);

//$referencetext = new UITextField("Viitetaulu", "referencetableID", 'ReferencetableID', $this->registry->tables);
//$section->addField($referencetext);

$minvalue = new UITextField("Minimi", "min", 'min');
$section->addField($minvalue);

$maxvalue = new UITextField("Maksimi", "max", 'max');
$section->addField($maxvalue);

$defaultvalue = new UITextField("Oletusarvo", "defaultvalue", 'defaultvalue');
$section->addField($defaultvalue);

$defaultvalue = new UITextField("Taulunakyvyys", "tablevisibility", 'tablevisibility');
$section->addField($defaultvalue);

$defaultvalue = new UITextField("Sectionnakyvyys", "sectionvisibility", 'sectionvisibility');
$section->addField($defaultvalue);

$obligatoryselect = new UIBooleanField("Logitus", "logvalue", 'logvalue',$this->registry->logvalue);
$section->addField($obligatoryselect);

$field = new UITextField("Järjestys", "sortorder", 'sortorder');
$section->addField($field);

$section->show();


// ---------------------------------------------------------------------------------------------------
// Hallinta
// ---------------------------------------------------------------------------------------------------

$managementSection = new UISection("Hallinta");
$managementSection->editable(false);
$managementSection->setDebug(true);
$button = new UIButton(UIComponent::ACTION_FORWARD, "admin/database/removecolumn&id=".$registry->column->columnID . "&tableID=".$registry->column->tableID, "Poista sarake");
$managementSection->addButton($button);
$managementSection->show();




?>