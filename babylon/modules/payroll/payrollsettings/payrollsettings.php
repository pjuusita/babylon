<?php


echo "<h1>Palkanlaskennan asetukset</h1>";





$section = new UISection("Palkanlaskennan kirjanpitotilit", '700px');
$section->setOpen(true);
$section->editable(true);
$section->setWidths("50%","50%","0%");
$section->setUpdateAction(UIComponent::ACTION_FORWARD, 'payroll/payrollsettings/updatesettings', 'systemID');

$column = new UISelectField("Palkojen maksuvelkatili", "payablesaccountID", "payablesaccountID", $registry->accounts, 'fullname');
$column->setPredictive(true);
$section->addField($column);



$section->setData($registry->settings);
$section->show();



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

//$field = new UIDateField("Alkaa", "startdate", 'startdate');
//$insertagreementsection->addField($field);

//$field = new UIDateField("Loppuu", "enddate", 'enddate');
//$insertagreementsection->addField($field);

$insertagreementsection->show();



$editagreementsection = new UISection("Työehtosopimuksen muokkaus");
$editagreementsection->setDialog(true);
$editagreementsection->setMode(UIComponent::MODE_INSERT);
$editagreementsection->setSaveAction(UIComponent::ACTION_FORWARD, 'payroll/payrollsettings/updatelabouragreement',"labouragreementID");

$field = new UITextField("Nimike", "Nimike", 'name');
$editagreementsection->addField($field);

$field = new UITextField("Lyhenne", "abbreviation", 'abbreviation');
$editagreementsection->addField($field);

//$field = new UIDateField("Alkaa", "startdate", 'startdate');
//$editagreementsection->addField($field);

//$field = new UIDateField("Loppuu", "enddate", 'enddate');
//$editagreementsection->addField($field);

$editagreementsection->show();



$table = new UITableSection("Työehtosopimukset", '700px');
$table->setOpen(true);
$table->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertagreementsection->getID(), "Lisää uusi");
$table->addButton($button);
$table->setLineAction(UIComponent::ACTION_FORWARD,"payroll/labouragreements/showlabouragreement","labouragreementID");

$nimicolumn = new UISortColumn("#", "labouragreementID", "labouragreementID");
$table->addColumn($nimicolumn);

$nimicolumn = new UISortColumn("Nimi", "name", "");
$table->addColumn($nimicolumn);

$nimicolumn = new UISortColumn("Lyhenne", "abbreviation", "");
$table->addColumn($nimicolumn);

//$startcolumn = new UISortColumn("Alkaa", "startdate", "");
//$startcolumn->setFormatter(Column::COLUMNTYPE_DATE);
//$table->addColumn($startcolumn);

//$endcolumn = new UISortColumn("Loppuu", "enddate", "");
//$endcolumn->setFormatter(Column::COLUMNTYPE_DATE);
//$table->addColumn($endcolumn);

$table->setData($registry->labouragreements);
$table->show();





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

foreach($registry->labouragreements as $index => $labouragreement) {
	$field = new UIBooleanField($labouragreement->abbreviation, "labouragreement-" . $labouragreement->labouragreementID, "labouragreement-" . $labouragreement->labouragreementID);
	$edittitlesection->addField($field);
}

$edittitlesection->show();




$table = new UITableSection("Työtehtävänimikkeet","700px");
$table->setOpen(true);
$table->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $inserttitlesection->getID(), "Lisää uusi");
$table->addButton($button);
$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $edittitlesection->getID(),"worktitleID");


$column = new UISortColumn("#", "worktitleID", "worktitleID");
$table->addColumn($column);

$column = new UISortColumn("Nimike", "name", 'name');
$table->addColumn($column);

$column = new UISortColumn("Työehtosopimukset", "agreements", 'agreements');
$table->addColumn($column);

$table->setData($registry->worktitles);
$table->show();









//---------------------------------------------------------------------------------------------
// 		Palkkalajit
//---------------------------------------------------------------------------------------------




$insertsalarytypesection = new UISection("Palkkalajin lisäys");
$insertsalarytypesection->setDialog(true);
$insertsalarytypesection->setMode(UIComponent::MODE_INSERT);
$insertsalarytypesection->setSaveAction(UIComponent::ACTION_FORWARD, 'payroll/payrollsettings/insertsalarytype');

$field = new UITextField("Nimike", "Nimike", 'name');
$insertsalarytypesection->addField($field);

/*
foreach($registry->labouragreements as $index => $labouragreement) {
	$field = new UIBooleanField($labouragreement->abbreviation, "labouragreement-" . $labouragreement->labouragreementID, "labouragreement-" . $labouragreement->labouragreementID);
	$insertsalarytypesection->addField($field);
}
*/

/*
$field = new UISelectField("Kulutili","expenceaccountID","expenceaccountID",$registry->accounts, 'fullname');
$insertsalarytypesection->addField($field);

$field = new UISelectField("Velkatili","payableaccountID","payableaccountID",$registry->accounts, 'fullname');
$insertsalarytypesection->addField($field);

$field = new UISelectField("Yksikkö","unitID","unitID",$registry->units, 'name');
$insertsalarytypesection->addField($field);

$field = new UISelectField("Tulolaji","salarycategoryID","salarycategoryID",$registry->salarycategories, 'abbreviation');
$insertsalarytypesection->addField($field);
*/
$insertsalarytypesection->show();


/*
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
*/



$table = new UITableSection("Palkkalajit","700px");
$table->setOpen(true);
$table->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsalarytypesection->getID(), "Lisää uusi");
$table->addButton($button);
$table->setLineAction(UIComponent::ACTION_FORWARD,"payroll/payrollsettings/showsalarytype","salarytypeID");

$column = new UISortColumn("Numero", "number", 'number');
$table->addColumn($column);

$column = new UISortColumn("Nimike", "name", 'name');
$table->addColumn($column);

$column = new UISelectColumn("Yksikkö", "sign", "unitID", $registry->units);
$table->addColumn($column);

$column = new UISortColumn("Työehtosopimukset", "agreements", 'agreements');
$table->addColumn($column);


//$column = new UISortColumn("#", "salarytypeID", "salarytypeID");
//$table->addColumn($column);

//$column = new UISortColumn("Nimike", "name", 'name');
//$table->addColumn($column);

$column = new UISelectColumn("Kulutili", "number", "expenceaccountID", $registry->accounts);
$table->addColumn($column);

$column = new UISelectColumn("Velkatili", "number", "payableaccountID", $registry->accounts);
$table->addColumn($column);

//$column = new UISelectColumn("Yksikkö", "sign", "unitID", $registry->units);
//$table->addColumn($column);

//$column = new UISelectColumn("Tulolaji", "abbreviation", "salarycategoryID", $registry->salarycategories);
//$table->addColumn($column);

$table->setData($registry->salarytypes);
$table->show();







//---------------------------------------------------------------------------------------------
// 		Palkan sivukulut ja vähennykset
//---------------------------------------------------------------------------------------------


$deductions = array();
$row = new Row();
$row->deduction = 0;
$row->name = "Sivukulu";
$deductions[0] = $row;
$row = new Row();
$row->deduction = 1;
$row->name = "Vähennys";
$deductions[1] = $row;


$insertdeductionsection = new UISection("Palkkakulun lisäys");
$insertdeductionsection->setDialog(true);
$insertdeductionsection->setMode(UIComponent::MODE_INSERT);
//$insertdeductionsection->setSaveAction(UIComponent::ACTION_FORWARD, 'hr/workers/insertdeduction&workerID=' . $registry->worker->workerID);


$field = new UISelectField("Palkkalaji","salarytypeID","salarytypeID",$registry->salarytypes, 'fullname');
$insertdeductionsection->addField($field);

//$field = new UISelectField("Tulolaji","salarycategoryID","salarycategoryID",$registry->salarycategories, 'name');
//$insertdeductionsection->addField($field);

$field = new UIDateField("Alkaa", "startdate", 'startdate');
$insertdeductionsection->addField($field);

$field = new UIDateField("Loppuu", "enddate", 'enddate');
$insertdeductionsection->addField($field);

$field = new UITextField("Prosentti", "percent", 'percent');
$insertdeductionsection->addField($field);

//$field = new UISelectField("Vähennystyyppi","deduction","deduction",$deductions, 'name');
//$insertdeductionsection->addField($field);

$insertdeductionsection->show();


$editdeductionsection = new UISection("Palkkansaajan vähennyksen muokkaus");
$editdeductionsection->setDialog(true);
$editdeductionsection->setMode(UIComponent::MODE_INSERT);

$editdeductionsection->setSaveAction(UIComponent::ACTION_FORWARD, 'payroll/payrollsettings/updatedeductionaccount',"deductionID");

$field = new UITextField("Vähennys","name","name");
$editdeductionsection->addField($field);

$field = new UISelectField("Kulutili","expenceaccountID","expenceaccountID",$registry->accounts, 'fullname');
$field->setPredictable(true);
$editdeductionsection->addField($field);

$field = new UISelectField("Velkatili","deptaccountID","deptaccountID",$registry->accounts, 'fullname');
$field->setPredictable(true);
$editdeductionsection->addField($field);


//$field = new UISelectField("Palkkalaji","salarytypeID","salarytypeID",$registry->salarytypes, 'fullname');
//$editdeductionsection->addField($field);

//$field = new UISelectField("Tulolaji","salarycategoryID","salarycategoryID",$registry->salarycategories, 'name');
//$editdeductionsection->addField($field);

//$field = new UIDateField("Alkaa", "startdate", 'startdate');
//$editdeductionsection->addField($field);

//$field = new UIDateField("Loppuu", "enddate", 'enddate');
//$editdeductionsection->addField($field);

//$field = new UITextField("Prosentti", "percent", 'percent');
//$editdeductionsection->addField($field);

//$field = new UISelectField("Vähennystyyppi","deduction","deduction",$deductions, 'name');
//$editdeductionsection->addField($field);

$editdeductionsection->show();


$sidecosts = array();
$deductions = array();
$counter1 = 0;

foreach($this->registry->deductions as $index => $deduction) {
	if ($deduction->payertype == 2) {
		$deductions[$index] = $deduction;
	} 
	
	if ($deduction->payertype == 1) {
		$sidecosts[$index] = $deduction;
	}
	$counter1++;
}

echo "<br>deductions - " . count($deductions);
echo "<br>counter1 - " . $counter1;


$deductionsection = new UITableSection('Vähennykset palkasta','700px');
$deductionsection->setOpen(true);
$deductionsection->setFramesVisible(true);

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertdeductionsection->getID(), "Lisää uusi");
//$deductionsection->addButton($button);

$deductionsection->setLineAction(UIComponent::ACTION_OPENDIALOG, $editdeductionsection->getID(),"deductionID");

//$column = new UISortColumn("#", "deductionID", "deductionID");
//$deductionsection->addColumn($column);

/*
$payers = array();
$row = new Row();
$row->payer = 1;
$row->name = "Palkansaaja";
$payers[1] = $row;
$row = new Row();
$row->payer = 2;
$row->name = "Työnantaja";
$payers[2] = $row;

*/

//$column = new UISelectColumn("Vähennys", "name", "salarycategoryID", $this->registry->salarycategories);
//$deductionsection->addColumn($column);

$column = new UISortColumn("Palkasta vähennettävä erä", "name", "name");
$deductionsection->addColumn($column);

$column = new UISelectColumn("Kulutili", "number", "expenceaccountID", $registry->accounts);
$deductionsection->addColumn($column);

$column = new UISelectColumn("Velkatili", "number", "deptaccountID", $registry->accounts);
$deductionsection->addColumn($column);


$column = new UIHiddenColumn("deductionID", "deductionID", "deductionID");
$deductionsection->addColumn($column);


//$column = new UISortColumn("Palkkalaji", "salarycategoryID", "salarycategoryID");
//$deductionsection->addColumn($column);

//$column = new UISelectColumn("Tulolaji", "abbreviation", "salarycategoryID", $registry->salarycategories);
//$deductionsection->addColumn($column);

/*
$startcolumn = new UISortColumn("Alkaa", "startdate", "");
$startcolumn->setFormatter(Column::COLUMNTYPE_DATE);
$deductionsection->addColumn($startcolumn);

$endcolumn = new UISortColumn("Loppuu", "enddate", "");
$endcolumn->setFormatter(Column::COLUMNTYPE_DATE);
$deductionsection->addColumn($endcolumn);
*/

/*
$column = new UISortColumn("%", "percent", "percent");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$deductionsection->addColumn($column);
*/


//$column = new UISortColumn("Lisäehdot", "info", "info");
//$deductionsection->addColumn($column);

//$column = new UISelectColumn("Tapa", "name", "deduction", $deductions);
//$deductionsection->addColumn($column);

$deductionsection->setData($deductions);
$deductionsection->show();










$insertexpensesection = new UISection("Työantajan palkan sivukulun muokkaus");
$insertexpensesection->setDialog(true);
$insertexpensesection->setMode(UIComponent::MODE_INSERT);
$insertexpensesection->setUpdateAction(UIComponent::ACTION_FORWARD, 'payroll/payrollsettings/updateexpenseaccount', 'deductionID');

$field = new UITextField("Kulu","name","name");
$insertexpensesection->addField($field);

$field = new UISelectField("Kulutili","expenceaccountID","expenceaccountID",$registry->accounts, 'fullname');
$field->setPredictable(true);
$insertexpensesection->addField($field);

$field = new UISelectField("Velkatili","deptaccountID","deptaccountID",$registry->accounts, 'fullname');
$field->setPredictable(true);
$insertexpensesection->addField($field);

//$field = new UISelectField("Tulolaji","salarycategoryID","salarycategoryID",$registry->salarycategories, 'name');
//$editdeductionsection->addField($field);

//$field = new UISelectField("Tulolaji","salarycategoryID","salarycategoryID",$registry->salarycategories, 'name');
//$editdeductionsection->addField($field);

/*
$field = new UIDateField("Alkaa", "startdate", 'startdate');
$insertexpensesection->addField($field);

$field = new UIDateField("Loppuu", "enddate", 'enddate');
$insertexpensesection->addField($field);
*/

$insertexpensesection->show();




$deductionsection = new UITableSection('Palkan sivukulut','700px');
$deductionsection->setOpen(true);
$deductionsection->setFramesVisible(true);

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertdeductionsection->getID(), "Lisää uusi");
//$deductionsection->addButton($button);
$deductionsection->setLineAction(UIComponent::ACTION_OPENDIALOG, $insertexpensesection->getID(),"deductionID");

$column = new UISortColumn("Kuluerä", "name", "name");
$deductionsection->addColumn($column);

$column = new UISelectColumn("Kulutili", "number", "expenceaccountID", $registry->accounts);
$deductionsection->addColumn($column);

$column = new UISelectColumn("Velkatili", "number", "deptaccountID", $registry->accounts);
$deductionsection->addColumn($column);

$column = new UIHiddenColumn("deductionID", "deductionID", "deductionID");
$deductionsection->addColumn($column);


/*
$column = new UISelectColumn("Palkkalaji", "name", "salarycategoryID", $this->registry->salarycategories);
$deductionsection->addColumn($column);

$startcolumn = new UISortColumn("Alkaa", "startdate", "");
$startcolumn->setFormatter(Column::COLUMNTYPE_DATE);
$deductionsection->addColumn($startcolumn);

$endcolumn = new UISortColumn("Loppuu", "enddate", "");
$endcolumn->setFormatter(Column::COLUMNTYPE_DATE);
$deductionsection->addColumn($endcolumn);

$column = new UISortColumn("%", "percent", "percent");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$deductionsection->addColumn($column);


$column = new UISortColumn("Lisäehdot", "info", "info");
$deductionsection->addColumn($column);

//$column = new UISelectColumn("Tapa", "name", "deduction", $deductions);
//$deductionsection->addColumn($column);
*/
$deductionsection->setData($sidecosts);
$deductionsection->show();




