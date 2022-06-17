<?php



echo "<a href='".getUrl('payroll/payrollsettings/showsettings')."'>Palaa palkanlaskenta-asetuksiin</a><br>";
echo "<h1>" . $registry->labouragreement->name . "</h1>";

$section = new UISection('Työehtosopimuksen perustiedot','600px');
$section->setOpen(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD, "payroll/labouragreements/updatelabouragreement", 'labouragreementID');

$field = new UITextField("Nimi", "name", 'name');
$section->addField($field);

$field = new UITextField("Lyhenne", "abbreviation", 'abbreviation');
$section->addField($field);

$section->setData($registry->labouragreement);

$section->show();




$table = new UITableSection("Työehtosopimuskaudet", '600px');
$table->setOpen(true);
$table->setFramesVisible(true);
$table->show();




// Sopimuskaudet -- linkki sopimukseen -- ei tarvita ainakaan paikallinen / yleissitova?



$inserttitlesection = new UISection("Työtehtävänimikkeen lisäys");
$inserttitlesection->setDialog(true);
$inserttitlesection->setMode(UIComponent::MODE_INSERT);
$inserttitlesection->setSaveAction(UIComponent::ACTION_FORWARD, 'payroll/payrollsettings/insertlabouragreementworktitle&labouragreementID=' . $registry->labouragreement->labouragreementID);

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




$table = new UITableSection("Työtehtävänimikkeet","600px");
$table->setOpen(true);
$table->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $inserttitlesection->getID(), "Lisää uusi");
$table->addButton($button);
$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $edittitlesection->getID(),"worktitleID");


$column = new UISortColumn("#", "worktitleID", "worktitleID");
$table->addColumn($column);

$column = new UISortColumn("Nimike", "name", 'name');
$table->addColumn($column);

$table->setData($registry->worktitles);
$table->show();




//---------------------------------------------------------------------------------------------
// 			Palkkakaudet
//---------------------------------------------------------------------------------------------

$insertperiodsection = new UISection("Palkkakauden lisäys");
$insertperiodsection->setDialog(true);
$insertperiodsection->setMode(UIComponent::MODE_INSERT);
$insertperiodsection->setSaveAction(UIComponent::ACTION_FORWARD, 'payroll/payrollsettings/insertpayrollperiod&labouragreementID=' . $registry->labouragreement->labouragreementID);

//$field = new UISelectField("Työehtosopimus","labouragreementID","labouragreementID",$registry->labouragreements, 'name');
//$insertperiodsection->addField($field);

//$field = new UITextField("Nimike", "Nimike", 'name');
//$insertperiodsection->addField($field);

$field = new UIDateField("Alkaa", "startdate", 'startdate');
$insertperiodsection->addField($field);

$field = new UIDateField("Loppuu", "enddate", 'enddate');
$insertperiodsection->addField($field);

$field = new UIDateField("Maksupäivä", "paymentdate", 'paymentdate');
$insertperiodsection->addField($field);

// Tämä voisi olla sama kuin joko loppupäivä, tai maksupäivä
$field = new UIDateField("Kirjanpitopäivä", "bookkeepingdate", 'bookkeepingdate');
$insertperiodsection->addField($field);

$insertperiodsection->show();


$editperiodsection = new UISection("Palkkakauden muokkaus");
$editperiodsection->setDialog(true);
$editperiodsection->setMode(UIComponent::MODE_INSERT);
$editperiodsection->setSaveAction(UIComponent::ACTION_FORWARD, 'payroll/payrollsettings/updatepayrollperiod',"periodID");

$field = new UISelectField("Työehtosopimus","labouragreementID","labouragreementID",$registry->labouragreements, 'name');
$editperiodsection->addField($field);

//$field = new UITextField("Nimike", "Nimike", 'name');
//$editperiodsection->addField($field);

$field = new UIDateField("Alkaa", "startdate", 'startdate');
$editperiodsection->addField($field);

$field = new UIDateField("Loppuu", "enddate", 'enddate');
$editperiodsection->addField($field);

$field = new UIDateField("Maksupäivä", "paymentdate", 'paymentdate');
$editperiodsection->addField($field);

$field = new UIDateField("Kirjanpitopäivä", "bookkeepingdate", 'bookkeepingdate');
$editperiodsection->addField($field);

$editperiodsection->show();



$table = new UITableSection("Palkkakaudet", '600px');
$table->setOpen(true);
$table->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertperiodsection->getID(), "Lisää uusi");
$table->addButton($button);
$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editperiodsection->getID(),"periodID");

$column = new UISortColumn("#", "payrollperiodID", "payrollperiodID");
$table->addColumn($column);

$column = new UISortColumn("Alkaa", "startdate", "");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

$column = new UISortColumn("Loppuu", "enddate", "");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

$column = new UISortColumn("Maksupäivä", "paymentdate", "");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

$column = new UISortColumn("Kirjanpito", "bookkeepingdate", "");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

//$column = new UISelectColumn("Työehtosopimus", "abbreviation", "labouragreementID", $registry->labouragreements);
//$table->addColumn($column);

$table->setData($registry->payrollperiods);
$table->show();
// Palkkalajit

/*
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




$table = new UITableSection("Palkkatyypit","600px");
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
*/



?>