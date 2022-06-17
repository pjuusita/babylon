<?php



	

	$section = new UISection("Myynnin yleisasetukset");
	$section->setOpen(true);
	$section->editable(true);
	$section->setUpdateAction(UIComponent::ACTION_FORWARD, 'sales/salessettings/updatesettings', 'systemID');
	
	$column = new UISelectField("Tuotenumero käytössä", "productnumberused", "productnumberused", $registry->usedselect);
	$section->addField($column);
	
	$section->setData($registry->settings);
	$section->show();
	


// ------------------------------------------------------------------------
//		Tuoteryhmät
// ------------------------------------------------------------------------


$insertproductgroupsection = new UISection("Tuoteryhmän lisäys");
$insertproductgroupsection->setDialog(true);
$insertproductgroupsection->setMode(UIComponent::MODE_INSERT);
$insertproductgroupsection->setSaveAction(UIComponent::ACTION_FORWARD, 'sales/salessettings/insertproductgroup');

$field = new UITextField("Nimi", "Nimi", 'name');
$insertproductgroupsection->addField($field);

$insertproductgroupsection->show();


$editproductgroupsection = new UISection("Tuoteryhmän muokkaus");
$editproductgroupsection->setDialog(true);
$editproductgroupsection->setMode(UIComponent::MODE_INSERT);
$editproductgroupsection->setUpdateAction(UIComponent::ACTION_FORWARD, 'sales/salessettings/updateproductgroup', 'productgroupID');


$field = new UITextField("Nimike", "Nimike", 'name');
$editproductgroupsection->addField($field);

$editproductgroupsection->show();



$table = new UITableSection("Tuoteryhmät", '600px');
$table->setOpen(true);
$table->setFramesVisible(true);

$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editproductgroupsection->getID(),"productgroupID");
$table->setDeleteAction(UIComponent::ACTION_FORWARD, 'sales/salessettings/removeproductgroup', 'productgroupID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertproductgroupsection->getID(), "Lisää uusi");
$table->addButton($button);

$nimicolumn = new UISortColumn("Nimi", "name", "");
$table->addColumn($nimicolumn);

$column = new UIHiddenColumn("productgroupID", "productgroupID", 'productgroupID');
$table->addColumn($column);


$table->setData($registry->productgroups);
$table->show();





// ------------------------------------------------------------------------
//		Yleiset myynnit
// ------------------------------------------------------------------------


$insertsaletypesection = new UISection("Yleiset myynnit");
$insertsaletypesection->setDialog(true);
$insertsaletypesection->setMode(UIComponent::MODE_INSERT);
$insertsaletypesection->setSaveAction(UIComponent::ACTION_FORWARD, 'sales/salessettings/insertsaletype');

$field = new UITextField("Nimi", "Nimi", 'name');
$insertsaletypesection->addField($field);

$field = new UISelectField("Myyntitili","salesaccountID","salesaccountID",$registry->accounts, "fullname");
$insertsaletypesection->addField($field);

$field = new UISelectField("Saamistili","receivablesaccountID","receivablesaccountID",$registry->accounts, "fullname");
$insertsaletypesection->addField($field);

$insertsaletypesection->show();


$editsaletypesection = new UISection("Yleisen myynnin muokkaus");
$editsaletypesection->setDialog(true);
$editsaletypesection->setMode(UIComponent::MODE_INSERT);
$editsaletypesection->setUpdateAction(UIComponent::ACTION_FORWARD, 'sales/salessettings/updatesaletype', 'saletypeID');

$field = new UITextField("Nimike", "Nimike", 'name');
$editsaletypesection->addField($field);

$field = new UISelectField("Myyntitili","salesaccountID","salesaccountID",$registry->accounts, "fullname");
$editsaletypesection->addField($field);

$field = new UISelectField("Saamistili","receivablesaccountID","receivablesaccountID",$registry->accounts, "fullname");
$editsaletypesection->addField($field);

$editsaletypesection->show();



$table = new UITableSection("Yleiset myynnit", '600px');
$table->setOpen(true);
$table->setFramesVisible(true);

$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editsaletypesection->getID(),"saletypeID");
$table->setDeleteAction(UIComponent::ACTION_FORWARD, 'sales/salessettings/removesaletype', 'saletypeID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsaletypesection->getID(), "Lisää uusi");
$table->addButton($button);


$nimicolumn = new UISortColumn("Nimi", "name", "");
$table->addColumn($nimicolumn);

$column = new UISelectColumn("Myyntitili", "fullname", "salesaccountID", $registry->accounts);
$table->addColumn($column);

$column = new UISelectColumn("Saamistili", "fullname", "receivablesaccountID", $registry->accounts);
$table->addColumn($column);

$nimicolumn = new UIHiddenColumn("saletypeID", "saletypeID", "");
$table->addColumn($nimicolumn);

$table->setData($registry->saletypes);
$table->show();





// ------------------------------------------------------------------------
//		Tositesarjat
// ------------------------------------------------------------------------


$insertunit = new UISection("Määrä yksikön lisäys");
$insertunit->setDialog(true);
$insertunit->setMode(UIComponent::MODE_INSERT);
$insertunit->setSaveAction(UIComponent::ACTION_FORWARD, 'sales/salessettings/insertunit');

$field = new UITextField("Nimike", "Nimike", 'name');
$insertunit->addField($field);

$field = new UITextField("Lyhenne", "sign", 'sign');
$insertunit->addField($field);

$insertunit->show();



$editunit = new UISection("Määrä yksikön muokkaus");
$editunit->setDialog(true);
$editunit->setMode(UIComponent::MODE_INSERT);
$editunit->setUpdateAction(UIComponent::ACTION_FORWARD, 'sales/salessettings/updateunit', 'unitID');

$field = new UITextField("Nimike", "Nimike", 'name');
$editunit->addField($field);

$field = new UITextField("Lyhenne", "sign", 'sign');
$editunit->addField($field);

$editunit->show();



$table = new UITableSection("Määrä yksiköt","600px");
$table->setOpen(true);
$table->setFramesVisible(true);

$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editunit->getID(),"unitID");
$table->setDeleteAction(UIComponent::ACTION_FORWARD, 'sales/salessettings/removeunit', 'unitID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertunit->getID(), "Lisää uusi yksikkö");
$table->addButton($button);

//$table->setLineAction((UIComponent::ACTION_OPENDIALOG, $editunit->getID(), "unitID");


$nimicolumn = new UISortColumn("Nimi", "name", "");
$table->addColumn($nimicolumn);

$nimicolumn = new UISortColumn("Lyhenne", "sign", "sign");
$table->addColumn($nimicolumn);

$column = new UIHiddenColumn("unitID", "unitID", 'unitID');
$table->addColumn($column);

$table->setData($registry->units);
$table->show();






// ------------------------------------------------------------------------
//		Käytettävissä olevat valuutat
// ------------------------------------------------------------------------


$insertcurrency= new UISection("Valuutan lisäys");
$insertcurrency->setDialog(true);
$insertcurrency->setMode(UIComponent::MODE_INSERT);
$insertcurrency->setSaveAction(UIComponent::ACTION_FORWARD, 'sales/salessettings/insertcurrency');

$field = new UITextField("Nimike", "Nimike", 'name');
$insertcurrency->addField($field);

$field = new UITextField("Sign", "sign", 'sign');
$insertcurrency->addField($field);

$insertcurrency->show();



$editcurrency = new UISection("Valuutan muokkaus");
$editcurrency->setDialog(true);
$editcurrency->setMode(UIComponent::MODE_INSERT);
$editcurrency->setUpdateAction(UIComponent::ACTION_FORWARD, 'sales/salessettings/updatecurrency', 'currencyID');

$field = new UITextField("Nimi", "Nimike", 'name');
$editcurrency->addField($field);

$field = new UITextField("Sign", "sign", 'sign');
$editcurrency->addField($field);

$editcurrency->show();



$table = new UITableSection("Valuuttat","600px");
$table->setOpen(true);
$table->setFramesVisible(true);

$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editcurrency->getID(),"currencyID");
$table->setDeleteAction(UIComponent::ACTION_FORWARD, 'sales/salessettings/removecurrency', 'currencyID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertcurrency->getID(), "Lisää uusi valuutta");
$table->addButton($button);

$column = new UISortColumn("Nimi", "name", 'name');
$table->addColumn($column);

$column = new UISortColumn("Rahayksikkö", "sign", 'sign');
$table->addColumn($column);

$column = new UIHiddenColumn("currencyID", "currencyID", 'currencyID');
$table->addColumn($column);

$table->setData($registry->currencies);
$table->show();

