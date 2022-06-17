<?php



$section = new UISection("Palkkalaji");
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'payroll/payrollsettings/updatesalarytype', 'salarytypeID');

$numberfield = new UITextField("Numero", "number", 'number');
$section->addField($numberfield);

$field = new UITextField("Nimike", "name", 'name');
$section->addField($field);

$field = new UISelectField("Yksikkö","unitID","unitID",$registry->units, 'name');
$section->addField($field);

$field = new UISelectField("Ryhmittely","salarycategoryID","salarycategoryID",$registry->salarycategories, 'name');
$section->addField($field);

$registerfield = new UISelectField("Tulolaji","incomeregistercodeID","incomeregistercodeID",$registry->incomeregistercodes, 'fullname');
$registerfield->setOnChange("incomeregistercodechanged()");
$section->addField($registerfield);

$section->setData($registry->salarytype);
$section->show();


echo "<script>";
echo "	function incomeregistercodechanged() {";

echo "	 	var numberfieldID 		 	 = '#".$numberfield->getEditFieldID()."';";
echo "	 	var registerfieldID 		 = '#".$registerfield->getEditFieldID()."';";

echo "	 	var number = $(numberfieldID).val();";
echo "	 	var code = $(registerfieldID).val();";

//echo "		alert('jejeeea - ' + number + ' - ' + code );";
echo "		if (number == '') {";
echo "	 		$(numberfieldID).val(code+'0');";
echo "		}";
echo "	}";
echo "</script>";


$insertsection = new UISection("Toimalan linkitys palkkalajille");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'payroll/payrollsettings/insertlabouragreementtosalarytype&salarytypeID=' . $registry->salarytype->salarytypeID);

$field = new UISelectField("Työehtosopimus","labouragreementID","labouragreementID",$registry->labouragreements, 'name');
$insertsection->addField($field);

$insertsection->show();




$section = new UITableSection("Työehtosopimukset", '600px');
$section->setOpen(true);
$section->setFramesVisible(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'payroll/payroll/updatesalarytype', 'salarytypeID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Lisää työehtosopimus');
$section->addButton($button);

$column = new UISelectColumn("Työehtosopimus", "name", "labouragreementID", $registry->labouragreements);
$section->addColumn($column);

$section->setData($registry->labouragreementlinks);
$section->show();



$section = new UISection("Laskenta-asetukset");
$section->setOpen(true);
$section->editable(false);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'payroll/payroll/updatesalarytype', 'salarytypeID');


$field = new UIBooleanField("Ennakonpidätys", "witholdingtax", "witholdingtax");
$section->addField($field);

$field = new UIBooleanField("Eläkevakuutus", "pensioninsurance", "pensioninsurance");
$section->addField($field);

$field = new UIBooleanField("Tapaturmavakuutus", "accidentinsurance", "accidentinsurance");
$section->addField($field);

$field = new UIBooleanField("Työttömyysvakuutus", "unemploymentinsurance", "unemploymentinsurance");
$section->addField($field);

$field = new UIBooleanField("Sairausvakuutus", "sicknessinsurance", "sicknessinsurance");
$section->addField($field);

$section->setData($registry->salarytype);
$section->show();



$section = new UISection("Taulukkopalkat");
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'payroll/payroll/updatesalarytype', 'salarytypeID');

//$section->setData($registry->salarytype);
$section->show();




$section = new UISection("Kirjanpidon asetukset");
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'payroll/payrollsettings/updatesalarytypeaccount', 'salarytypeID');


$field = new UISelectField("Kulutili","expenceaccountID","expenceaccountID",$registry->accounts, 'fullname');
$field->setPredictable(true);
$section->addField($field);


$field = new UISelectField("Velkatili","payableaccountID","payableaccountID",$registry->accounts, 'fullname');
$field->setPredictable(true);
$section->addField($field);


$section->setData($registry->salarytype);
$section->show();






$section = new UISection("Hallinta");
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'payroll/payroll/updatesalarytype', 'salarytypeID');

//$section->setData($registry->salarytype);
$section->show();




?>