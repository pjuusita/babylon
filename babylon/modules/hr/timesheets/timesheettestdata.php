<?php


/**
 * 	Maaritellaan seuraavat taulut
 * 	- $projects - nama on arvot, jotka pitaisi nakya alasvetovalikkona
 *  - 
 *  - $salarytypes - nama ovat sarakkeet jotka pitaisi nakya tuntilistalla numererisina kenttina
 * 
 */

$tasks = array();

$task = new Row();
$task->taskID = 1;
$task->name = "Varastomies";
$tasks[] = $task;

$task = new Row();
$task->taskID = 2;
$task->name = "Keraily";
$tasks[] = $task;

$task = new Row();
$task->taskID = 3;
$task->name = "Hyllytys";
$tasks[] = $task;

$task = new Row();
$task->taskID = 4;
$task->name = "Toimistoapulainen";
$tasks[] = $task;

$task = new Row();
$task->taskID = 5;
$task->name = "Rakennusmies";
$tasks[] = $task;

$task = new Row();
$task->taskID = 6;
$task->name = "Muurari";
$tasks[] = $task;

$shifts = array();

$shift = new Row();
$shift->shiftID = 1;						// Varastoalan aamuvuoro
$shift->name = "Aamuvuoro";
$shift->agreementID = 1;					// Varastoalan TES (aamuvuoro)
$shifts[] = $shift;

$shift = new Row();
$shift->shiftID = 2;						// Varastoalan iltavuoro
$shift->name = "Iltavuoro";
$shift->agreementID = 2;					// Varastoalan TES (iltavuoro)
$shifts[] = $shift;

$shift = new Row();
$shift->shiftID = 3;						// Varastoalan yavuoro
$shift->name = "Yavuoro";
$shift->agreementID = 3;					// Varastoalan TES (yavuoro)
$shifts[] = $shift;



$projects = array();

$project = new Row();
$project->projectID = 1;
$project->name = "Halli A";
$project->agreementID = 1;					// Varastoalan TES (aamuvuoro)
$project->tasks = array(1,2,3);				// Varastomies, Keraily, Hyllytys
$project->shifts = array(1,2,3);			// Aamu, ilta, ya
$projects[] = $project;

$project = new Row();
$project->projectID = 2;
$project->name = "Rakennustyamaa";
$project->agreementID = 2;					// Rakennusalan tes (aamuvuoro)
$project->tasks = array(5,6);				// Rakennusmies, Muurari
$project->shifts = array(4,5);				// Aamu, ilta
$projects[] = $project;

$project = new Row();
$project->projectID = 3;
$project->name = "Toimisto";				
$project->agreementID = 3;					// Toimihenkilat TES (aamuvuoro)
$project->tasks = array(4);					// Toimistoapulainen
$project->shifts = array(6);				// Aamu
$projects[] = $project;





$salarytypes = array();

$salarytype = new Row();
$salarytype->rowID = 1;
$salarytype->name = "Perustunnit";
$salarytype->calculationmethod = "overlappinginterval";
$salarytypes[] = $salarytype;

$salarytype = new Row();
$salarytype->rowID = 2;
$salarytype->name = "Ylitya50";
$salarytype->calculationmethod = "totaltimeinterval";
$salarytypes[] = $salarytype;

$salarytype = new Row();
$salarytype->rowID = 3;
$salarytype->name = "Ylitya100";
$salarytype->calculationmethod = "totaltimeinterval";
$salarytypes[] = $salarytype;

$salarytype = new Row();
$salarytype->rowID = 4;
$salarytype->name = "Iltalisa";
$salarytype->calculationmethod = "overlappinginterval";
$salarytypes[] = $salarytype;

$salarytype = new Row();
$salarytype->rowID = 5;
$salarytype->name = "Yalisa";
$salarytype->calculationmethod = "overlappinginterval";
$salarytypes[] = $salarytype;

$salarytype = new Row();
$salarytype->rowID = 6;
$salarytype->name = "Sunnuntailisa";
$salarytype->calculationmethod = "overlappinginterval";
$salarytypes[] = $salarytype;


$laboragreements = array();

// Varastoalan TES (oletus, aamuvuoro)
$laboragreement = new Row();
$laboragreement->agreementID = 1;
$laboragreement->name = "Varastoalan TES";

$laboragreement->salaryranges = array();

$salaryrange = new Row();
$salaryrange->salarytype = 1;	// perustunnit
$salaryrange->days = array (0,1,2,3,4,5,6);
$salaryrange->starttime = 0.0;
$salaryrange->endtime = 8.0;
$laboragreement->salaryranges[$salaryrange->salarytype] = $salaryrange;

$salaryrange = new Row();
$salaryrange->days = array (0,1,2,3,4,5,6);
$salaryrange->salarytype = 2;	// ylitya50
$salaryrange->starttime = 8.0;
$salaryrange->endtime = 10.0;
$laboragreement->salaryranges[$salaryrange->salarytype] = $salaryrange;

$salaryrange = new Row();
$salaryrange->days = array (0,1,2,3,4,5,6);
$salaryrange->salarytype = 3;	// ylitya100
$salaryrange->starttime = 10.0;
$salaryrange->endtime = 24.0;
$laboragreement->salaryranges[$salaryrange->salarytype] = $salaryrange;

$salaryrange = new Row();
$salaryrange->days = array (0,1,2,3,4,5,6);
$salaryrange->salarytype = 4;	// iltalisa
$salaryrange->starttime = 16.0;
$salaryrange->endtime = 22.0;
$laboragreement->salaryranges[$salaryrange->salarytype] = $salaryrange;

$salaryrange = new Row();
$salaryrange->days = array (0,1,2,3,4,5,6);
$salaryrange->salarytype = 5;	// yalisa
$salaryrange->starttime = 22.0;
$salaryrange->endtime = 6.0;
$laboragreement->salaryranges[$salaryrange->salarytype] = $salaryrange;

$salaryrange = new Row();
$salaryrange->days = array (6);
$salaryrange->salarytype = 6;	// sunnunstailisa
$salaryrange->starttime = 0.0;
$salaryrange->endtime = 24.0;
$laboragreement->salaryranges[$salaryrange->salarytype] = $salaryrange;


// Varastoalan TES (iltavuoro)
$laboragreement = new Row();
$laboragreement->agreementID = 1;
$laboragreement->name = "Varastoalan TES";

$laboragreement->salaryranges = array();

$salaryrange = new Row();
$salaryrange->salarytype = 1;	// perustunnit
$salaryrange->days = array (0,1,2,3,4,5,6);
$salaryrange->starttime = 0.0;
$salaryrange->endtime = 8.0;
$laboragreement->salaryranges[] = $salaryrange;

$salaryrange = new Row();
$salaryrange->days = array (0,1,2,3,4,5,6);
$salaryrange->salarytype = 2;	// ylitya50
$salaryrange->starttime = 8.0;
$salaryrange->endtime = 10.0;
$laboragreement->salaryranges[] = $salaryrange;

$salaryrange = new Row();
$salaryrange->days = array (0,1,2,3,4,5,6);
$salaryrange->salarytype = 3;	// ylitya100
$salaryrange->starttime = 10.0;
$salaryrange->endtime = 24.0;
$laboragreement->salaryranges[] = $salaryrange;

$salaryrange = new Row();
$salaryrange->days = array (0,1,2,3,4,5,6);
$salaryrange->salarytype = 4;	// iltalisa
$salaryrange->starttime = 0.0;
$salaryrange->endtime = 22.0;
$laboragreement->salaryranges[] = $salaryrange;

$salaryrange = new Row();
$salaryrange->days = array (0,1,2,3,4,5,6);
$salaryrange->salarytype = 5;	// yalisa
$salaryrange->starttime = 22.0;
$salaryrange->endtime = 6.0;
$laboragreement->salaryranges[] = $salaryrange;

$salaryrange = new Row();
$salaryrange->days = array (6);
$salaryrange->salarytype = 6;	// sunnunstailisa
$salaryrange->starttime = 0.0;
$salaryrange->endtime = 24.0;
$laboragreement->salaryranges[] = $salaryrange;
$laboragreements[$laboragreement->agreementID] = $laboragreement;

// Varastoalan TES (yavuoro)
$laboragreement = new Row();
$laboragreement->agreementID = 1;
$laboragreement->name = "Varastoalan TES";

$laboragreement->salaryranges = array();

$salaryrange = new Row();
$salaryrange->salarytype = 1;	// perustunnit
$salaryrange->days = array (0,1,2,3,4,5,6);
$salaryrange->starttime = 0.0;
$salaryrange->endtime = 8.0;
$laboragreement->salaryranges[] = $salaryrange;

$salaryrange = new Row();
$salaryrange->days = array (0,1,2,3,4,5,6);
$salaryrange->salarytype = 2;	// ylitya50
$salaryrange->starttime = 8.0;
$salaryrange->endtime = 10.0;
$laboragreement->salaryranges[] = $salaryrange;

$salaryrange = new Row();
$salaryrange->days = array (0,1,2,3,4,5,6);
$salaryrange->salarytype = 3;	// ylitya100
$salaryrange->starttime = 10.0;
$salaryrange->endtime = 24.0;
$laboragreement->salaryranges[] = $salaryrange;

/*
// ilta lisaa ei esiinny yavuorossa
$salaryrange = new Row();
$salaryrange->days = array (0,1,2,3,4,5,6);
$salaryrange->salarytype = 4;	// iltalisa
$salaryrange->starttime = 0.0;
$salaryrange->endtime = 0.0;
$laboragreement->salaryranges[] = $salaryrange;
*/

$salaryrange = new Row();
$salaryrange->days = array (0,1,2,3,4,5,6);
$salaryrange->salarytype = 5;	// yalisa
$salaryrange->starttime = 0.0;
$salaryrange->endtime = 24.0;
$laboragreement->salaryranges[] = $salaryrange;

$salaryrange = new Row();
$salaryrange->days = array (6);
$salaryrange->salarytype = 6;	// sunnunstailisa
$salaryrange->starttime = 0.0;
$salaryrange->endtime = 24.0;
$laboragreement->salaryranges[] = $salaryrange;
$laboragreements[$laboragreement->agreementID] = $laboragreement;



?>