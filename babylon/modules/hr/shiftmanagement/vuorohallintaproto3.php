<?php 

// Korvattu UI Table --> UITableSection
$taskTable 			= new UITableSection("Tasks");

$taskColumn 		= new UIColumn("Task","name");
$processColumn 		= new UIColumn("Process","process");
$assignerColumn		= new UIColumn("Assigner","assigner");
$assignedColumn 	= new UIColumn("Assignee","assigned");
$typeColumn			= new UIColumn("Type","type");
$stageColumn		= new UIColumn("Stage","stage");
$stateColumn		= new UIColumn("State","state");
$assignDateColumn 	= new UIColumn("Date","assigndate");

$taskTable->addColumn($taskColumn);
$taskTable->addColumn($processColumn);
$taskTable->addColumn($assignerColumn);
$taskTable->addColumn($assignedColumn);
$taskTable->addColumn($typeColumn);
$taskTable->addColumn($stageColumn);
$taskTable->addColumn($stateColumn);
$taskTable->addColumn($assignDateColumn);

$taskTable->setData($this->registry->tasks);

$taskTable->show();

$billingStages 	= $this->registry->billingStages;
$billingTasks	= $this->registry->tasks;
 
$taskOverview = new UIProcessTable('Taskoverview');
$taskOverview->setTasks($billingTasks);
$taskOverview->setStages($billingStages);
$taskOverview->show();

?>