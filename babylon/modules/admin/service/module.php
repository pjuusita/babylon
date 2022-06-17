<?php


echo "<a href='".getUrl('admin/service/showservices')."'>Palaa tauluun</a>";

$section = new UISection("Modulin asetukset");
$section->setOpen(true);
$section->setData($registry->column);
//$section->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/database/updatecolumn', 'columnID');

$variablenameField = new UITextField("Muuttujannimi", "variablename", 'Variablename');
$section->addField($variablenameField);

$columnnameField = new UITextField("Sarakkeennimi", "columnname", 'Columnname');
$section->addField($columnnameField);

$nameField = new UITextField("Otsikko", "name", 'Name');
$section->addField($nameField);

$typeselect = new UISelectField("Tyyppi", "type", 'Type',$this->registry->columnstypes);
$section->addField($typeselect);

$obligatoryselect = new UIBooleanField("Pakollinen", "obligatory", 'Obligatory',$this->registry->obligatoryvalues);
$section->addField($obligatoryselect);

// pitäisi esittää selectin sisällä referencetable namen
$referenceselect = new UISelectField("Viitetaulu", "referencetableID", 'referencetableID', $this->registry->tables);
$section->addField($referenceselect);

$minvalue = new UITextField("Minimi", "min", 'Min');
$section->addField($minvalue);

$maxvalue = new UITextField("Maksimi", "max", 'Max');
$section->addField($maxvalue);

$defaultvalue = new UITextField("Oletusarvo", "defaultvalue", 'Defaultvalue');
$section->addField($defaultvalue);

$section->show();

?>