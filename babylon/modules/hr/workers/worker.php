<?php


include_once('./modules/payroll/payroll.module.php');
include_once('./modules/workorders/workorders.module.php');



echo "<a href='".getUrl('hr/workers/showworkers')."'>Palaa työntekijät listaan</a><br>";
echo "<h1>" . $registry->worker->lastname . " " . $registry->worker->firstname . "</h1>";

$section = new UISection('Työntekijätiedot','600px');
$section->setOpen(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD, "hr/workers/updateworker", 'workerID');


$field = new UITextField("Etunimi", "firstname", 'firstname');
$section->addField($field);

$field = new UITextField("Sukunimi", "lastname", 'lastname');
$section->addField($field);

$field = new UITextField("Henkilötunnus", "identificationnumber", 'identificationnumber');
$section->addField($field);

$field = new UITextField("Puhelinnumero", "phonenumber", 'phonenumber');
$section->addField($field);

$field = new UITextField("Tilinumero", "bankaccountnumber", 'bankaccountnumber');
$section->addField($field);

$field = new UITextField("Email", "email", 'Email');
$section->addField($field);

$field = new UITextField("Postiosoite", "streetaddress", 'streetaddress');
$section->addField($field);

$field = new UITextField("Postipaikka", "city", 'city');
$section->addField($field);

$field = new UITextField("Postinumero", "postalcode", 'postalcode');
$section->addField($field);



/*
$field = new UISelectField("Titteli","jobtitleID","JobtitleID",$registry->jobtitles, "name");
$section->addField($field);

$field = new UISelectField("Yritys","companyID","CompanyID",$registry->companies, "name");
$section->addField($field);
*/

$section->setData($registry->worker);

$section->show();



/*
//
// Tehtävänimikkeet tulee työsopimuksesta, joitakin työtehtävänimikkeitä tarvitaan myös kiinnityksissä
// vuokratyövoiman kanssa, tämä olisi siis ehkä paremminkin nimike osaamiset, tai soveltuvuus eri 
// työtehtäviin. Se voisi olla ehkä sama nimike, mutta linkitys eri tauluun. Sitte palkanmaksua varten
// varmaankin tarvitaan tilauskiinnityksestä syntyvä 'tilapäinen työsuhde'.
//

$section = new UISection('Tehtävänimikkeet','600px');
$section->setOpen(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD, "crm/clients/updateclient", 'clientID');

/*
$field = new UITextField("Etunimi", "firstname", 'Firstname');
$section->addField($field);

$field = new UITextField("Sukunimi", "lastname", 'Lastname');
$section->addField($field);


$field = new UITextField("Puhelinnumero", "phonenumber", 'Phonenumber');
$section->addField($field);
*/

/*
 $field = new UISelectField("Titteli","jobtitleID","JobtitleID",$registry->jobtitles, "name");
 $section->addField($field);

 $field = new UISelectField("Yritys","companyID","CompanyID",$registry->companies, "name");
 $section->addField($field);

//$section->setData($registry->client);

$section->show();
 */



//---------------------------------------------------------------------------------------------
// 		Työehtosopimukset
//			
//		TODO:
//		- Pitänee lisätä työsuhteen tyyppi, määräaikainen
//		- Kokoaikainen, osa-aikainen (työtuntien määrä)
//---------------------------------------------------------------------------------------------



$insertworkcontractsection = new UISection("Työsopimuksen lisäys");
$insertworkcontractsection->setDialog(true);
$insertworkcontractsection->setMode(UIComponent::MODE_INSERT);
$insertworkcontractsection->setSaveAction(UIComponent::ACTION_FORWARD, 'hr/workers/insertworkcontract&workerID=' . $registry->worker->workerID);

$field = new UISelectField("Eläkevakuutus","pensioninsurancetypeID","pensioninsurancetypeID",$registry->pensioninsurancetypes);
$insertworkcontractsection->addField($field);

$field = new UISelectField("Työehtosopimus","labouragreementID","labouragreementID",$registry->labouragreements, 'name');
$insertworkcontractsection->addField($field);

$field = new UIDateField("Työsuhde alkaa", "startdate", 'startdate');
$insertworkcontractsection->addField($field);

$field = new UIDateField("Työsuhde loppuu", "enddate", 'enddate');
$insertworkcontractsection->addField($field);

$insertworkcontractsection->show();



$editdworkcontractsection = new UISection("Työsopimuksen muokkaus");
$editdworkcontractsection->setDialog(true);
$editdworkcontractsection->setMode(UIComponent::MODE_INSERT);
$editdworkcontractsection->setSaveAction(UIComponent::ACTION_FORWARD, 'hr/workers/updateworkcontract&workerID=' . $registry->worker->workerID,"workcontractID");

$field = new UISelectField("Eläkevakuutus","pensioninsurancetypeID","pensioninsurancetypeID",$registry->pensioninsurancetypes);
$editdworkcontractsection->addField($field);

$field = new UISelectField("Työehtosopimus","labouragreementID","labouragreementID",$registry->labouragreements, 'name');
$editdworkcontractsection->addField($field);

$field = new UIDateField("Työsuhde alkaa", "startdate", 'startdate');
$editdworkcontractsection->addField($field);

$field = new UIDateField("Työsuhde loppuu", "enddate", 'enddate');
$editdworkcontractsection->addField($field);

$editdworkcontractsection->show();





// Työsopimuksesta napataan ainakin palkkakaudet...
$workcontractssection = new UITableSection('Työsuhteet','600px');
$workcontractssection->setOpen(true);
$workcontractssection->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertworkcontractsection->getID(), "Lisää uusi");
$workcontractssection->addButton($button);
$workcontractssection->setLineAction(UIComponent::ACTION_OPENDIALOG, $editdworkcontractsection->getID(),"workcontractID");

$column = new UISortColumn("#", "workcontractID", "workcontractID");
$workcontractssection->addColumn($column);

$column = new UISelectColumn("Eläkevakuutus", null, "pensioninsurancetypeID", $registry->pensioninsurancetypes);
$workcontractssection->addColumn($column);

$column = new UISelectColumn("Työehtosopimus", "abbreviation", "labouragreementID", $registry->labouragreements);
$workcontractssection->addColumn($column);


$startcolumn = new UISortColumn("Alkaa", "startdate", "");
$startcolumn->setFormatter(Column::COLUMNTYPE_DATE);
$workcontractssection->addColumn($startcolumn);

$endcolumn = new UISortColumn("Loppuu", "enddate", "");
$endcolumn->setFormatter(Column::COLUMNTYPE_DATE);
$workcontractssection->addColumn($endcolumn);

$workcontractssection->setData($registry->workcontracts);
$workcontractssection->show();



//---------------------------------------------------------------------------------------------
// 		Palkkatiedot
//---------------------------------------------------------------------------------------------

$section = new UISection('Palkkatiedot','600px');
$section->setOpen(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD, "crm/clients/updateclient", 'clientID');
$section->show();



//---------------------------------------------------------------------------------------------
// 		Verokortit
//---------------------------------------------------------------------------------------------


$inserttaxcardsection = new UISection("Verokortin lisäys");
$inserttaxcardsection->setDialog(true);
$inserttaxcardsection->setMode(UIComponent::MODE_INSERT);
$inserttaxcardsection->setSaveAction(UIComponent::ACTION_FORWARD, 'hr/workers/inserttaxcard&workerID=' . $registry->worker->workerID);

$field = new UIDateField("Alkaa", "startdate", 'startdate');
$inserttaxcardsection->addField($field);

$field = new UIDateField("Loppuu", "enddate", 'enddate');
$inserttaxcardsection->addField($field);

$field = new UITextField("P1", "percent1", 'percent1');
$inserttaxcardsection->addField($field);

$field = new UITextField("P2", "percent2", 'percent2');
$inserttaxcardsection->addField($field);

$field = new UITextField("Raja", "taxlimit", 'taxlimit');
$inserttaxcardsection->addField($field);

$field = new UITextField("Aiemmat", "oldsalary", 'oldsalary');
$inserttaxcardsection->addField($field);

$inserttaxcardsection->show();


$editdtaxcardsection = new UISection("Verokortin muokkaus");
$editdtaxcardsection->setDialog(true);
$editdtaxcardsection->setMode(UIComponent::MODE_INSERT);
$editdtaxcardsection->setSaveAction(UIComponent::ACTION_FORWARD, 'hr/workers/updatetaxcard&workerID=' . $registry->worker->workerID,"taxcardID");

$field = new UIDateField("Alkaa", "startdate", 'startdate');
$editdtaxcardsection->addField($field);

$field = new UIDateField("Loppuu", "enddate", 'enddate');
$editdtaxcardsection->addField($field);

$field = new UITextField("P1", "percent1", 'percent1');
$editdtaxcardsection->addField($field);

$field = new UITextField("P2", "percent2", 'percent2');
$editdtaxcardsection->addField($field);

$field = new UITextField("Raja", "taxlimit", 'taxlimit');
$editdtaxcardsection->addField($field);

$field = new UITextField("Aiemmat", "oldsalary", 'oldsalary');
$editdtaxcardsection->addField($field);

$editdtaxcardsection->show();




// Työsopimuksesta napataan ainakin palkkakaudet...
$taxcardsection = new UITableSection('Verokortit','600px');
$taxcardsection->setOpen(true);
$taxcardsection->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $inserttaxcardsection->getID(), "Lisää uusi");
$taxcardsection->addButton($button);

$taxcardsection->setLineAction(UIComponent::ACTION_OPENDIALOG, $editdtaxcardsection->getID(),"taxcardID");

$column = new UISortColumn("#", "taxcardID", "taxcardID");
$taxcardsection->addColumn($column);

$startcolumn = new UISortColumn("Alkaa", "startdate", "");
$startcolumn->setFormatter(Column::COLUMNTYPE_DATE);
$taxcardsection->addColumn($startcolumn);

$endcolumn = new UISortColumn("Loppuu", "enddate", "");
$endcolumn->setFormatter(Column::COLUMNTYPE_DATE);
$taxcardsection->addColumn($endcolumn);

$column = new UISortColumn("P1", "percent1", "percent1");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$taxcardsection->addColumn($column);

$column = new UISortColumn("P2", "percent2", "percent2");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$taxcardsection->addColumn($column);

$column = new UISortColumn("Palkkaraja", "taxlimit", "taxlimit");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$taxcardsection->addColumn($column);

$column = new UISortColumn("Aiemmat", "oldsalary", "oldsalary");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$taxcardsection->addColumn($column);

$taxcardsection->setData($registry->taxcards);
$taxcardsection->show();




//if (PayrollModule::hasAccess(PayrollModule::ACCESSRIGHTKEY_PAYROLL)) {

	// TODO: Nimieksi pitäisi asettaa payrollperiodin name

	$paycheckssection = new UITableSection('Palkkalaskelmat','600px');
	$paycheckssection->setOpen(true);
	$paycheckssection->setFramesVisible(true);
	
	$startcolumn = new UISortColumn("Alkaa", "startdate", "");
	$startcolumn->setFormatter(Column::COLUMNTYPE_DATE);
	$paycheckssection->addColumn($startcolumn);
		
	$startcolumn = new UISortColumn("Alkaa", "startdate", "");
	$startcolumn->setFormatter(Column::COLUMNTYPE_DATE);
	$paycheckssection->addColumn($startcolumn);
	
	$startcolumn = new UISortColumn("Maksupäivä", "paymentdate", "");
	$startcolumn->setFormatter(Column::COLUMNTYPE_DATE);
	$paycheckssection->addColumn($startcolumn);
	
	$column = new UISortColumn("Brutto", "grossamount", "grossamount");
	$column->setAlign(Column::ALIGN_RIGHT);
	$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
	$paycheckssection->addColumn($column);
	
	
	$column = new UISortColumn("Netto", "netamount", "netamount");
	$column->setAlign(Column::ALIGN_RIGHT);
	$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
	$paycheckssection->addColumn($column);
	
	$paycheckssection->setData($registry->paychecks);
	$paycheckssection->show();
	
/*
} else {
	echo "	<script>";
	echo "		function debugMessageForWorker() {";
	echo "			console.log('No access to payroll-module');";
	echo "		}";
	echo "		$(document).ready(debugMessageForWorker)";
	echo "</script>";
}
*/

/*
if (WorkordersModule::hasAccess(WorkordersModule::ACCESSRIGHTKEY_WORKORDERS)) {
	
	$section = new UISection('Toimeksiantokiinnitykset','600px');
	$section->setOpen(true);
	$section->setUpdateAction(UIComponent::ACTION_FORWARD, "crm/clients/updateclient", 'clientID');
	
	/*
	 $field = new UITextField("Etunimi", "firstname", 'Firstname');
	 $section->addField($field);
	
	 $field = new UITextField("Sukunimi", "lastname", 'Lastname');
	 $section->addField($field);
	
	
	 $field = new UITextField("Puhelinnumero", "phonenumber", 'Phonenumber');
	 $section->addField($field);
	 * /
	
	/*
	 $field = new UISelectField("Titteli","jobtitleID","JobtitleID",$registry->jobtitles, "name");
	 $section->addField($field);
	
	 $field = new UISelectField("Yritys","companyID","CompanyID",$registry->companies, "name");
	 $section->addField($field);
	 * /
	
	//$section->setData($registry->client);
	
	$section->show();
	
	
	
	$section = new UISection('Toimeksiantokiinnitykset','600px');
	$section->setOpen(true);
	$section->setUpdateAction(UIComponent::ACTION_FORWARD, "crm/clients/updateclient", 'clientID');
	
	$section->show();
	
	
	
} else {
	echo "	<script>";
	echo "		function debugMessageForWorker() {";
	echo "			console.log('No access to workorders-module');";
	echo "		}";
	echo "		$(document).ready(debugMessageForWorker)";
	echo "</script>";
	//echo "<br>No access to ACCESSRIGHTKEY_WORKORDERS...";
}
*/

$section = new UISection('Käyttöoikeudet','600px');
$section->setOpen(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD, "crm/clients/updateclient", 'clientID');

$button = new UIButton(UIComponent::ACTION_FORWARD, "hr/workers/showworkers", "Myönnä käyttöoikeudet");
$section->addButton($button);

$section->show();




$managementSection = new UISection("Hallinta");
$managementSection->editable(false);
$managementSection->setDebug(true);


$button = new UIButton(UIComponent::ACTION_FORWARD, "hr/workers/showworkers", "Poista");
$managementSection->addButton($button);

$managementSection->show();



?>