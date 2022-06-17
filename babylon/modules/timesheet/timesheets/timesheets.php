<?php




$insertsection = new UISection('Tuntilistan lisäys','500px');
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'timesheet/timesheets/createtimesheet');

$field = new UISelectField("Työntekijä", "fullname", 'workerID', $registry->workers, 'fullname');
$insertsection->addField($field);

$field = new UISelectField("Tyyppi", "type", 'typeID', $registry->timesheettypes, 'fullname');
$insertsection->addField($field);

$field = new UISelectField("Työntekijä", "fullname", 'workerID', $registry->workers, 'fullname');
$insertsection->addField($field);

$insertsection->show();




$table = new UITableSection("Tuntilistat","600px");
$table->setOpen(true);
$table->setFramesVisible(false);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Lisää tuntilista');
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"timesheet/timesheet/showtimesheets","timesheetID");

$column= new UISelectColumn("Työntekijä", "fullname", "workerID", $registry->workers);
$table->addColumn($column);

$column = new UISortColumn("Vuosi", "year", 'timesheet/timesheet/shottimesheets&sort=nimi');
$table->addColumn($column);

$column = new UISortColumn("Kuukausi", "month", 'timesheet/timesheet/shottimesheets&sort=nimi');
$table->addColumn($column);

$column = new UISortColumn("Viikko", "week", 'timesheet/timesheet/shottimesheets&sort=nimi');
$table->addColumn($column);

$column = new UISortColumn("Alkupäivä", "startdate", 'timesheet/timesheet/shottimesheets&sort=nimi');
$table->addColumn($column);

$column = new UISortColumn("Loppupäivä", "enddate", 'timesheet/timesheet/shottimesheets&sort=nimi');
$table->addColumn($column);

$column = new UISortColumn("Tila", "tila", 'timesheet/timesheet/shottimesheets&sort=nimi');
$table->addColumn($column);

$table->setData($registry->companies);
$table->show();