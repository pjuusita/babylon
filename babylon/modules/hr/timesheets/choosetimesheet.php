<?php

//*******************************************************************************************************************************************
//*** TUNTILISTAN VALINTA
//***
//*******************************************************************************************************************************************

$timesheets			= $this->registry->timesheets;

$limit = 50;
$offset = 0;
$faststep = 50;
$pagingAction = "hr/timesheets/choosetimesheetwithlimit";
$itemcount	= 100;

$paginator			= new UIPaginator($limit,$offset,$faststep,$itemcount,$pagingAction);
$paginator->setFastFind(true);
$paginator->setPagingStyle("pagenumber");

// Korvattu UI Table --> UITableSection
$timesheettable			= new UITableSection("Tuntilistat");
$timesheettable->setData($timesheets);

$employeeIDColumn		= new UISortColumn("TyantekijaID","employeeID");
$employeeIDColumn->setLink("hr/timesheets/showtimesheet","timesheetID");

$employeeNameColumn		= new UISortColumn("Nimi","employeename");
$employeeNameColumn->setLink("hr/timesheets/showtimesheet","timesheetID");

$startDateColumn		= new UISortColumn("Alkaa","startdate");
$endDateColumn			= new UISortColumn("Loppuu","enddate");

$timesheettable->addColumn($employeeIDColumn);
$timesheettable->addColumn($employeeNameColumn);
$timesheettable->addColumn($startDateColumn);
$timesheettable->addColumn($endDateColumn);

$insertAction 			= "hr/timesheets/showinserttimesheet"; 

$timesheettable->addButton("Uusi tuntilista",$insertAction);

$paginator->show();
$timesheettable->show();


?>