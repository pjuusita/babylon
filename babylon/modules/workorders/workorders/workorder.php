<?php



echo "<a href='".getUrl('workorders/workorders/showorders')."'>Palaa toimeksiannot-tauluun</a><br>";
//echo "<h1>" . $registry->company->name . "/" . $registry->location->name . "</h1>";


$section = new UISection("Tilaustiedot");
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'workorder/workorder/updateworkorder', 'workorderID');


$field = new UISelectField("Yritys", "companyID", 'companyID', $registry->companies, 'name');
$section->addField($field);

$field = new UISelectField("Työkohde", "locationID", 'locationID', $registry->locations, 'name');
$section->addField($field);

$field = new UISelectField("Asiakas", "clientID", 'clientID', $registry->clients, 'fullname');
$section->addField($field);

$field = new UIDateField("Aloitus", "startdate", 'startdate');
$section->addField($field);

$section->setData($registry->order);
$section->show();




$insertworkordersection = new UISection('Työtehtävätilauksen lisäys','500px');
$insertworkordersection->setDialog(true);
$insertworkordersection->setMode(UIComponent::MODE_INSERT);
$insertworkordersection->setSaveAction(UIComponent::ACTION_FORWARD, 'workorders/workorders/insertworkerorder&workorderid=' . $this->registry->order->orderID,'workorderID');

$field = new UISelectField("Tehtävänimike", "worktitleID", 'worktitleID', $registry->worktitles, 'name');
$insertworkordersection->addField($field);

$field = new UIDateField("Aloitus", "startdate", 'startdate');
$insertworkordersection->addField($field);

$field = new UITextField("Lukumäärä", "count", 'count');
$insertworkordersection->addField($field);

$insertworkordersection->show();


$insertworkersection = new UISection('Työntekijän kiinnitys','500px');
$insertworkersection->setDialog(true);
$insertworkersection->setMode(UIComponent::MODE_INSERT);
$insertworkersection->setSaveAction(UIComponent::ACTION_FORWARD, 'workorders/workorders/bindworker&orderID=' . $this->registry->order->orderID);


$field = new UISelectField("Tehtävänimike", "workerorderID", 'workerorderID', $registry->ordertitles, 'title');
$insertworkersection->addField($field);

$field = new UISelectField("Työntekijät", "workerID", 'workerID', $registry->workers, 'fullname');
$insertworkersection->addField($field);


$field = new UIDateField("Aloitus", "startdate", 'startdate');
$insertworkersection->addField($field);


//$field = new UITextField("Ryhmän nimi", "name", 'name');
//$insertsection->addField($field);

$insertworkersection->show();



/*
$section = new UITreeSection("Kiinnitetyt työntekijät");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertworkordersection->getID() ,'Lisää uusi työtehtävätilaus');
$section->addButton($button);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertworkersection->getID() ,'Kiinnitä työntekijä');
$section->addButton($button);

$column = new UISortColumn("Lukumäärä", "workercount", "worder/concepts/");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

$column = new UISelectColumn("Tehtävänimike", "name", "worktitleID", $this->registry->worktitles);
$section->addColumn($column);

$section->setData($this->registry->workerorders);

$section->show();
*/


$section = new UITierTableSection("Kiinnitetyt työntekijät", "600px");
$section->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertworkordersection->getID(), "Lisää uusi työtehtävätilaus");
$section->addButton($button);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertworkersection->getID(), "Kiinnitä työntekijä");
$section->addButton($button);

$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $insertworkersection->getID(), 'workerorderID');


//$column = new UIHiddenColumn("ID", 'workflowID');
//$section->addColumn($column);

$column = new UISortColumn("Lukumäärä", "workercount", "worder/concepts/");
$section->addColumn($column);

$column = new UIHiddenColumn("ID", "workorderID");
$section->addColumn($column);


$column = new UISelectColumn("Tehtävänimike", "name", "worktitleID", $this->registry->worktitles);
$section->addColumn($column);


$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "workorderID", "workorders/workorders/removeworkorder" ,"5%");		// Toinen parametri workflowID tarvitaan taulussa mukana, hiddenininä jos ei muuten
$column->setIcon("fa fa-ban");
$section->addColumn($column);


/*
 $column = new UIButtonColumn(UIComponent::ACTION_OPENDIALOG, "featureID", "worder/features/setsortup");
 $column->setIcon("fa fa-edit");
 $section->addColumn($column);


 $column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "featureID", "worder/features/setsortdown");
 $column->setIcon("fa fa-chevron-down");
 $section->addColumn($column);

 $column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "featureID", "worder/features/setsortup");
 $column->setIcon("fa fa-chevron-up");
 $section->addColumn($column);
 */

$section->setData($registry->workerorders);

$subcolumns = array();
$subcolumns[] = new UISortColumn("Nimi", "fullname");
$subcolumns[] = new UIHiddenColumn("workerorderID", "workerorderID");
$subcolumns[] = new UIHiddenColumn("workerID", "workerID");

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "workerID", "workorders/workorders/setsortdown");
$column->setIcon("fa fa-chevron-down");
$subcolumns[] = $column;

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "workerID", "workorders/workorders/setsortup");
$column->setIcon("fa fa-chevron-up");
$subcolumns[] = $column;

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "workerID", "workorders/workorders/removeworker");
$column->setIcon("fa fa-ban");
$subcolumns[] = $column;

$section->setLevelData($registry->workerbindings, $subcolumns, "workerorderID", "workerorderID");
$section->setSubLevelLineAction(UIComponent::ACTION_OPENDIALOG, $insertworkersection->getID(), 'workerID');




$section->show();




$section = new UITreeSection("Tuntikirjaukset");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertworkordersection->getID() ,'Lisää tuntilista');
$section->addButton($button);

/*
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertworkersection->getID() ,'Kiinnitä työntekijä');
$section->addButton($button);

$column = new UISortColumn("Lukumäärä", "workercount", "worder/concepts/");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

$column = new UISelectColumn("Tehtävänimike", "name", "worktitleID", $this->registry->worktitles);
$section->addColumn($column);
*/

$section->setData($this->registry->workerorders);

$section->show();

// ---------------------------------------------------------------------------------------------------
// Hallinta
// ---------------------------------------------------------------------------------------------------

$managementSection = new UISection("Hallinta");
$managementSection->editable(false);
$managementSection->setDebug(true);

$button = new UIButton(UIComponent::ACTION_FORWARD, "workoders/workorders/showorder&id=".$registry->order->workorderID, "Aseta lopetusaika");
$managementSection->addButton($button);

$managementSection->show();





// toteuta savecallback
/*
$perustiedotsection = new UISection("Perustiedot");
$perustiedotsection->setData($registry->yritys);
$perustiedotsection->setOpen(true);
$perustiedotsection->setUpdateAction(UIComponent::ACTION_FORWARD,'crm/companies/updatecompany', 'yritysID');
	$nimifield = new UITextField("Yrityksen nimi", "nimi", 'Nimi');
	//$nimifield->setMaxValue('10');		 				// vanha maxvalue => 100
	//$nimifield->setNotEmptyFunctionality();  			// vanha 'notempty' => 'onkeyup'
	$nimifield->setSaveCallback('changepagetitle');   	// 'savecallback' => 'changepagetitle'

	$ytunnusfield = new UITextField("Y-tunnus", "ytunnus", "Ytunnus");
	$ytunnusfield->setMaxLength("9");
	$ytunnusfield->setMinLength("8");


	$clientgroupfield = new UISelectField("Asiakasryhma", "asiakasryhmaID", 'AsiakasryhmaID', $this->registry->asiakasryhmat);
	
	
$perustiedotsection->addField($nimifield);
$perustiedotsection->addField($ytunnusfield);
$perustiedotsection->addField($clientgroupfield);
$perustiedotsection->show();
*/


?>