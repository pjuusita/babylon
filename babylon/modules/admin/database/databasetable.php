<?php


echo "<a href='".getUrl('admin/database/showdatabasetables')."'>Palaa tietokantalistaan</a><br>";
echo "<h1>Taulu: " . $registry->table->name . "</h1>";


$perustiedotsection = new UISection("Taulu - " . $registry->table->name);
$perustiedotsection->setData($registry->table);
$perustiedotsection->setOpen(true);
$perustiedotsection->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/database/updatetable', 'tableID');

$nimifield = new UITextField("Nimi", "name", 'Name');
$perustiedotsection->addField($nimifield);

//$field = new UILineField();
//$perustiedotsection->addField($field);

$field = new UITextAreaField("Kuvaus","description","description");
$perustiedotsection->addField($field);


//$tableexistsfield = new UIBooleanField("Datan sijainti", "tableexists", 'Tableexists');
	//$tableexistsfield->setBooleanStrings("Arvot systeemitaulussa", "Oma tietokantataulu");
	//$tablenamefield = new UITextField("Luokkanimi", "classname", 'Classname');
//$perustiedotsection->addField($tableexistsfield);
//$perustiedotsection->addField($tablenamefield);
$perustiedotsection->show();


// Sarakkeet taulu

// TODO: Updateta UITableSectioniksi
$columnstable = new UITableSection("Sarakkeet", "600px");
$columnstable->setOpen(true);
$columnstable->editable(true);
$columnstable->setFramesVisible(true);
//$columnstable->setFramesVisible(false);
//$columnstable->innerTable(true);
$columnstable->setLineaction(UIComponent::ACTION_FORWARD, "admin/database/showtablecolumn", 'columnID');
$button = new UIButton(UIComponent::ACTION_FORWARD, "admin/database/showinsertcolumn&tableid=".$registry->table->getID() ,"Lisää sarake");
$columnstable->addButton($button);


$columnnameColumn = new UISortColumn("Sarakenimi", "columnname", 'Columnname');
$nameColumn = new UISortColumn("Nimi", "name", 'Name');
$variablecolumn = new UISortColumn("Muuttujanimi", "variablename", 'Variablename');

$typecolumn = new UISelectColumn("Tyyppi", "name", "type", $registry->columntypes);


$obligatorycolumn = new UIBooleanColumn("Pakollisuus", "obligatory", "Obligatory", $registry->obligatoryvalues);
$referencecolumn = new UISortColumn("Viitetaulu", "referencetableID", "ReferencetableID");
$editablecolumn = new UISortColumn("Muokattava", "editable", "editable", "Editable");		// muuta integer fieldiksi
$minvaluecolumn = new UISortColumn("Minimiarvo", "min", "min", "Min");		// muuta integer fieldiksi
$maxvaluecolumn = new UISortColumn("Maksimiarvo", "max", "max", "Max");	// muuta integer fieldiksi
$defaultvaluecolumn = new UISortColumn("Oletusarvo", "defaultvalue", "defaultvalue", "Defaultvalue");
$tablevisibilitycolumn = new UISortColumn("Taulunäkyvyys", "tablevisibility", "tablevisibility", "tablevisibility");
$sectionvisibilitycolumn = new UISortColumn("Sectionnäkyvyys", "sectionvisibility", "sectionvisibility", "Sectionvisibility");
$logvaluecolumn = new UISortColumn("Logvalue", "logvalue", "logvalue", "Logvalue");
$sortcolumn = new UISortColumn("Sortorder", "sortorder", "sortorder", "Sortorder");

$columnstable->addColumn($columnnameColumn);
$columnstable->addColumn($nameColumn);
$columnstable->addColumn($variablecolumn);
$columnstable->addColumn($typecolumn);
$columnstable->addColumn($obligatorycolumn);
$columnstable->addColumn($referencecolumn);
$columnstable->addColumn($editablecolumn);
$columnstable->addColumn($minvaluecolumn);
$columnstable->addColumn($maxvaluecolumn);
$columnstable->addColumn($defaultvaluecolumn);
$columnstable->addColumn($tablevisibilitycolumn);
$columnstable->addColumn($sectionvisibilitycolumn);
$columnstable->addColumn($logvaluecolumn);
$columnstable->addColumn($sortcolumn);
$columnstable->setData($registry->table->getColumnsSorted());
$columnstable->show();

/*
$columnsSection = new UISection("Sarakkeet");
$columnsSection->editable(false);
$columnsSection->setOpen(true);

// tämä voisi olla dialog nappula...
$button = new UIButton(UIComponent::ACTION_FORWARD, "admin/database/showinsertcolumn&tableid=".$registry->table->getID() ,"Lisää sarake");
$columnsSection->addButton($button);

$columnsSection->addField($columnstable);

$columnstable->setData($registry->table->getColumnsSorted());
$columnsSection->show();
*/



$managementSection = new UISection("Hallinta");
$managementSection->editable(false);

// tämä action ei taida olla jsonaction
$button = new UIButton(UIComponent::ACTION_FORWARD, "admin/database/removetable&id=".$registry->table->getID(), "Poista taulu");
$managementSection->addButton($button);

$managementSection->show();

?>