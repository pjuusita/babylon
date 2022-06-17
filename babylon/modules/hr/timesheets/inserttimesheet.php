<?php

//*******************************************************************************************************************************************
//*** TUNTILISTARIVIEN LISÄYS
//***
//*******************************************************************************************************************************************

$employees				 = $this->registry->employees;

$newTimeSheetSection 	= new UIInsertSection("Uudet tuntilistarivit");
$newTimeSheetSection->setOpen(true);
$newTimeSheetSection->setInsertAction('hr/timesheets/createtimesheetrows',true);

$employee	 			= new UISelectField("Tyantekija","employeeID","employeeID",$employees);
$startDate				= new UIDateField("Alkaa","","startdate");
$endDate				= new UIDateField("Loppuu","","enddate");

$newTimeSheetSection->addField($employee);
$newTimeSheetSection->addField($startDate);
$newTimeSheetSection->addField($endDate);

$newTimeSheetSection->show();


?>