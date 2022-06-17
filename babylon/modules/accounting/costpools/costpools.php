<?php



$insertsection = new UISection("Kustannuspaikan lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/costpools/insertcostpool');

//$field = new UISelectField("Parent","costpoolID","costpoolID",$registry->costpools, "name");
//$field->setPredictive(true);
//$insertsection->addField($field);

$nimifield = new UITextField("Nimi", "Nimi", 'name');
$insertsection->addField($nimifield);


//$field = new UISelectField("ALV","vatID","vatID",$registry->vats, "name");
//$insertsection->addField($field);

$insertsection->show();


/*
$editsection = new UISection("Kustannuspaikan muokkaus");
$editsection->setDialog(true);
$editsection->setMode(UIComponent::MODE_INSERT);
$editsection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/costpools/updatecostpool', "costpoolID");

$nimifield = new UITextField("Nimi", "Nimi", 'name');
$editsection->addField($nimifield);

$field = new UISelectField("ALV","vatID","vatID",$registry->vats, "name");
$editsection->addField($field);

$field = new UISelectField("Tulotili","incomeaccountID","incomeaccountID",$registry->accounts, "fullname");
$field->setPredictive(true);
$editsection->addField($field);

$field = new UISelectField("Kulutili","expenseaccountID","expenseaccountID",$registry->accounts, "fullname");
$field->setPredictive(true);
$editsection->addField($field);

$field = new UISelectField("Tasetili (velka)","deptaccountID","deptaccountID",$registry->accounts, "fullname");
$field->setPredictive(true);
$editsection->addField($field);

$editsection->show();
*/


/*
$section = new UITreeSection("Kustannuspaikat", "600px");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), 'Lisää kustannuspaikka');
$section->addButton($button);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'accounting/costpools/showcostpool', 'costpoolID', UIComponent::ACTION_FORWARD);


//$column = new UIColumn("#", "accountID");
//$section->addColumn($column);

$column = new UIColumn("Nimi", "name");
$section->addColumn($column);


$column = new UISelectColumn("Incomeaccount", "name", "expenseaccountID", $this->registry->accounts);
$section->addColumn($column);


//$column = new UIColumn("Tilinumero", "number");
//$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "accountID", "accounting/accountchart/moveaccount");
$column->setIcon("fa fa-chevron-down");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "accountID", "accounting/accountchart/moveaccount");
$column->setIcon("fa fa-chevron-up");
$section->addColumn($column);

$section->setData($registry->costpools);
$section->show();
*/


$activecostpools = array();
$disabledcostpools = array();

foreach($registry->costpools as $index => $costpool) {
	if ($costpool->disabled == 0) {
		$activecostpools[$costpool->costpoolID] = $costpool;		
	} else {
		if ($costpool->disabled == 1) {
			$disabledcostpools[$costpool->costpoolID] = $costpool;		
		} else {
			echo "<br>Tuntematon costpool disabletila - '" . $costpool->disabled . "'";		
		}
	}
}

$table = new UITableSection("Kustannuspaikat", "1000px");
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD, 'accounting/costpools/showcostpool', 'costpoolID', UIComponent::ACTION_FORWARD);

$column = new UISortColumn("#", "costpoolID", "", null, "50px");
$table->addColumn($column);

$column = new UISortColumn("Nimi", "name", "", null, "90%");
$table->addColumn($column);

$column = new UISelectColumn("Tyyppi", null, "costpooltype", $registry->costpooltypes);
$table->addColumn($column);


$column = new UISelectColumn("Tili", "fullname", "expenseaccountID", $registry->accounts);
$table->addColumn($column);

$column = new UISelectColumn("ALV", "short", "vatID", $registry->vats);
$table->addColumn($column);

$table->setData($activecostpools);
$table->show();



$table = new UITableSection("Arkistoidut kustannuspaikat", "1000px");
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD, 'accounting/costpools/showcostpool', 'costpoolID', UIComponent::ACTION_FORWARD);

$column = new UISortColumn("#", "costpoolID", "", null, "50px");
$table->addColumn($column);

$column = new UISortColumn("Nimi", "name", "", null, "90%");
$table->addColumn($column);

$column = new UISelectColumn("Tyyppi", null, "costpooltype", $registry->costpooltypes);
$table->addColumn($column);


$column = new UISelectColumn("Tili", "fullname", "expenseaccountID", $registry->accounts);
$table->addColumn($column);

$column = new UISelectColumn("ALV", "short", "vatID", $registry->vats);
$table->addColumn($column);

$table->setData($disabledcostpools);
$table->show();




?>