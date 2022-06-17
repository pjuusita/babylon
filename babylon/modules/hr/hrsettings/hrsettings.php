<?php


echo "<h1>Henkilöstön asetukset</h1>";




$inserttitlesection = new UISection("Työtehtävänimikkeen lisäys");
$inserttitlesection->setDialog(true);
$inserttitlesection->setMode(UIComponent::MODE_INSERT);
$inserttitlesection->setSaveAction(UIComponent::ACTION_FORWARD, 'payroll/payrollsettings/insertworktitle');

$field = new UITextField("Nimike", "Nimike", 'name');
$inserttitlesection->addField($field);

$inserttitlesection->show();



$edittitlesection = new UISection("Työtehtävänimikkeen muokkaus");
$edittitlesection->setDialog(true);
$edittitlesection->setMode(UIComponent::MODE_INSERT);
$edittitlesection->setSaveAction(UIComponent::ACTION_FORWARD, 'payroll/payrollsettings/updateworktitle',"worktitleID");

$field = new UITextField("Nimike", "Nimike", 'name');
$edittitlesection->addField($field);

$edittitlesection->show();




$table = new UITableSection("Työtehtävänimikkeet","700px");
$table->setOpen(true);
$table->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $inserttitlesection->getID(), "Lisää uusi");
$table->addButton($button);
$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $edittitlesection->getID(),"worktitleID");


$column = new UISortColumn("#", "worktitleID", "worktitleID");
$table->addColumn($column);

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Lisää uusi nimike');
//$table->addButton($button);


$column = new UISortColumn("Nimike", "name", 'name');
$table->addColumn($column);

$table->setData($registry->worktitles);
$table->show();






$insertsalarytypesection = new UISection("Palkkalajin lisäys");
$insertsalarytypesection->setDialog(true);
$insertsalarytypesection->setMode(UIComponent::MODE_INSERT);
$insertsalarytypesection->setSaveAction(UIComponent::ACTION_FORWARD, 'payroll/payrollsettings/insertsalarytype');

$field = new UITextField("Nimike", "Nimike", 'name');
$insertsalarytypesection->addField($field);

$field = new UISelectField("Kulutili","expenceaccountID","expenceaccountID",$registry->accounts, 'fullname');
$insertsalarytypesection->addField($field);

$field = new UISelectField("Velkatili","payableaccountID","payableaccountID",$registry->accounts, 'fullname');
$insertsalarytypesection->addField($field);

$field = new UISelectField("Yksikkö","unitID","unitID",$registry->units, 'name');
$insertsalarytypesection->addField($field);

$field = new UISelectField("Tulolaji","salarycategoryID","salarycategoryID",$registry->salarycategories, 'abbreviation');
$insertsalarytypesection->addField($field);

$insertsalarytypesection->show();



$editsalarytypesection = new UISection("Palkkalajin muokkaus");
$editsalarytypesection->setDialog(true);
$editsalarytypesection->setMode(UIComponent::MODE_INSERT);
$editsalarytypesection->setSaveAction(UIComponent::ACTION_FORWARD, 'payroll/payrollsettings/updatesalarytype',"salarytypeID");

$field = new UITextField("Nimike", "Nimike", 'name');
$editsalarytypesection->addField($field);

$field = new UISelectField("Kulutili","expenceaccountID","expenceaccountID",$registry->accounts, 'fullname');
$editsalarytypesection->addField($field);

$field = new UISelectField("Velkatili","payableaccountID","payableaccountID",$registry->accounts, 'fullname');
$editsalarytypesection->addField($field);

$field = new UISelectField("Yksikkö","unitID","unitID",$registry->units, 'name');
$editsalarytypesection->addField($field);

$field = new UISelectField("Tulolaji","salarycategoryID","salarycategoryID",$registry->salarycategories, 'abbreviation');
$editsalarytypesection->addField($field);

$editsalarytypesection->show();




$table = new UITableSection("Palkkalajit","700px");
$table->setOpen(true);
$table->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsalarytypesection->getID(), "Lisää uusi");
$table->addButton($button);
$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editsalarytypesection->getID(),"salarytypeID");

$column = new UISortColumn("#", "salarytypeID", "salarytypeID");
$table->addColumn($column);

$column = new UISortColumn("Nimike", "name", 'name');
$table->addColumn($column);

$column = new UISelectColumn("Kulutili", "fullname", "expenceaccountID", $registry->accounts);
$table->addColumn($column);

$column = new UISelectColumn("Velkatili", "fullname", "payableaccountID", $registry->accounts);
$table->addColumn($column);

$column = new UISelectColumn("Yksikkö", "sign", "unitID", $registry->units);
$table->addColumn($column);

$column = new UISelectColumn("Tulolaji", "abbreviation", "salarycategoryID", $registry->salarycategories);
$table->addColumn($column);

$table->setData($registry->salarytypes);
$table->show();


//---------------------------------------------------------------------------------------------
// 			Työehtosopimukset
//---------------------------------------------------------------------------------------------

$insertagreementsection = new UISection("Työehtosopimuksen lisäys");
$insertagreementsection->setDialog(true);
$insertagreementsection->setMode(UIComponent::MODE_INSERT);
$insertagreementsection->setSaveAction(UIComponent::ACTION_FORWARD, 'payroll/payrollsettings/insertlabouragreement');

$field = new UITextField("Nimike", "Nimike", 'name');
$insertagreementsection->addField($field);

$field = new UITextField("Lyhenne", "abbreviation", 'abbreviation');
$insertagreementsection->addField($field);

$field = new UIDateField("Alkaa", "startdate", 'startdate');
$insertagreementsection->addField($field);

$field = new UIDateField("Loppuu", "enddate", 'enddate');
$insertagreementsection->addField($field);

$insertagreementsection->show();



$editagreementsection = new UISection("Työehtosopimuksen muokkaus");
$editagreementsection->setDialog(true);
$editagreementsection->setMode(UIComponent::MODE_INSERT);
$editagreementsection->setSaveAction(UIComponent::ACTION_FORWARD, 'payroll/payrollsettings/updatelabouragreement',"labouragreementID");

$field = new UITextField("Nimike", "Nimike", 'name');
$editagreementsection->addField($field);

$field = new UITextField("Lyhenne", "abbreviation", 'abbreviation');
$editagreementsection->addField($field);

$field = new UIDateField("Alkaa", "startdate", 'startdate');
$editagreementsection->addField($field);

$field = new UIDateField("Loppuu", "enddate", 'enddate');
$editagreementsection->addField($field);

$editagreementsection->show();



$table = new UITableSection("Työehtosopimukset", '700px');
$table->setOpen(true);
$table->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertagreementsection->getID(), "Lisää uusi");
$table->addButton($button);
$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editagreementsection->getID(),"labouragreementID");

$nimicolumn = new UISortColumn("#", "labouragreementID", "labouragreementID");
$table->addColumn($nimicolumn);

$nimicolumn = new UISortColumn("Nimi", "name", "");
$table->addColumn($nimicolumn);

$nimicolumn = new UISortColumn("Lyhenne", "abbreviation", "");
$table->addColumn($nimicolumn);

$startcolumn = new UISortColumn("Alkaa", "startdate", "");
$startcolumn->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($startcolumn);

$endcolumn = new UISortColumn("Loppuu", "enddate", "");
$endcolumn->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($endcolumn);

$table->setData($registry->labouragreements);
$table->show();





