<?php


// [15.10.2021] Kopioitu projects/project.php

$section = new UISection("Projekti");
$section->setOpen(true);
$section->editable(true);

$field = new UIFixedTextField("ProjectID", $registry->project->projectID);
$section->addField($field);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'tasks/projects/updateproject', 'projectID');

$field = new UITextField("Nimike", "name", 'name');
$section->addField($field);

$field = new UITextField("Lyhenne", "prefix", 'prefix');
$section->addField($field);

$section->setData($registry->project);
$section->show();



$insertsection = new UISection("Uuden jäsenen lisäys tiimiin");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/projects/insertmember&ProjectID=' . $this->registry->project->projectID);

// Tähän voisi laittaa käyttäjäryhmävalikon, joka heittää käyttäjäpudotusvalikkoon halutun henkilön... jos käyttäjiä on enemmän...

$field = new UISelectField("Käyttäjä","userID","UserID", $registry->users, "username");
$insertsection->addField($field);

$insertsection->show();





$table = new UITableSection("Jäsenet", "600px");
$table->setOpen(true);
$table->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$table->addButton($button);

$column = new UISortColumn("Nimi", "name", "tasks/projects/showteams&sort=nimi", null, "90%");
$table->addColumn($column);

$column = new UISortColumn("Username", "username", "tasks/projects/showteams&sort=nimi", null, "90%");
$table->addColumn($column);

// Tähän poisto ikoni

$table->setData($registry->members);
$table->show();



$insertlabelsection = new UISection("Uuden labelin lisäys");
$insertlabelsection->setDialog(true);
$insertlabelsection->setMode(UIComponent::MODE_INSERT);
$insertlabelsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/projects/insertlabel&projectID=' . $this->registry->project->projectID);

$field = new UITextField("Name", "name", 'name');
$insertlabelsection->addField($field);

$field = new UITextField("Lyhenne", "short", 'short');
$insertlabelsection->addField($field);

$field = new UIColorField("Väri","colorID","colorID",$registry->colors);
$insertlabelsection->addField($field);

$insertlabelsection->show();



$editlabelsection = new UISection("Labelin muokkaus");

$editlabelsection->setDialog(true);
$editlabelsection->setMode(UIComponent::MODE_EDIT);
$editlabelsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/projects/updatelabel&projectID=' . $this->registry->project->projectID, 'labelID');

$field = new UITextField("Name", "name", 'name');
$editlabelsection->addField($field);

$field = new UITextField("Lyhenne", "short", 'short');
$editlabelsection->addField($field);

$field = new UIColorField("Väri","colorID","colorID",$registry->colors);
$editlabelsection->addField($field);

$editlabelsection->show();


$section = new UITableSection("Labels", "600px");		// labels
$section->setOpen(true);
$section->setFramesVisible(true);
$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editlabelsection->getID(),"labelID");

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'tasks/projects/removelabel&projectID=' . $registry->project->projectID, 'labelID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertlabelsection->getID(), "Lisää");
$section->addButton($button);

$column = new UISortColumn("#", "labelID", "tasks/projects/showprojects", null, "10%");
$section->addColumn($column);

$column = new UISortColumn("Nimi", "name", "tasks/projects/showprojects&sort=nimi");
$section->addColumn($column);

$column = new UISortColumn("Lyhenne", "short", "tasks/projects/showprojects&sort=nimi");
$section->addColumn($column);

$column = new UIColorColumn("Taustaväri", "colorID", "colorID", $registry->colors);
$section->addColumn($column);

// TODO: add deleteaction, jonka pitää tarkistaa onko tehtävätyyppi jossain käytössä

$section->setData($this->registry->labels);
$section->show();



$editprioritysection = new UISection('Prioriteetin muokkaus','500px');
$editprioritysection->setDialog(true);
$editprioritysection->setMode(UIComponent::MODE_EDIT);
$editprioritysection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/projects/updatepriority&projectID=' . $registry->project->projectID, 'priorityID');

$field = new UITextField("Name", "name", 'name');
$editprioritysection->addField($field);

$field = new UIColorField("Väri","colorID","colorID",$registry->colors);
$editprioritysection->addField($field);

$editprioritysection->show();


$insertprioritysection = new UISection("Prioriteetin lisäys");
$insertprioritysection->setDialog(true);
$insertprioritysection->setMode(UIComponent::MODE_INSERT);
$insertprioritysection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/projects/insertpriority&projectID=' . $registry->project->projectID);

$nimifield = new UITextField("Nimike", "Nimike", 'name');
$insertprioritysection->addField($nimifield);

$field = new UIColorField("Väri","colorID","colorID",$registry->colors);
$insertprioritysection->addField($field);

$insertprioritysection->show();


$table = new UITableSection("Prioriteetit", "600px");
$table->setMode(UIComponent::MODE_EDIT);
$table->setFramesVisible(true);
//$table->setTableHeaderVisible(false);
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertprioritysection->getID(), "Lisää");
$table->addButton($button);
$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editprioritysection->getID(),"priorityID");

$column = new UISortColumn("#", "priorityID", "", null, "10%");
$table->addColumn($column);

$column = new UISortColumn("Nimi", "name", "");
$table->addColumn($column);

$column = new UIColorColumn("Väri", "colorID", "colorID", $registry->colors);
$table->addColumn($column);

$table->setData($registry->priorities);
$table->show();


/*
$section = new UISection("Etapit");				// sprints
$section->show();
*/

//$section = new UISection("Työvaiheet");			// stages
//$section->show();


$editstatesection = new UISection('Työvaiheen muokkaus','500px');
$editstatesection->setDialog(true);
$editstatesection->setMode(UIComponent::MODE_EDIT);
$editstatesection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/projects/updatestate&projectID='. $registry->project->projectID, 'stateID');

$field = new UITextField("Name", "name", 'name');
$editstatesection->addField($field);

$field = new UIBooleanField("Alkutila","startstate","startstate");
$editstatesection->addField($field);

$field = new UIBooleanField("Backlog","backlogstate","backlogstate");
$editstatesection->addField($field);

$field = new UIBooleanField("Valmis","completedstate","completedstate");
$editstatesection->addField($field);

$field = new UIBooleanField("Peruutettu","cancelledstate","cancelledstate");
$editstatesection->addField($field);

//$field = new UISelectField("Tyyppi","startstate","startstate",$registry->statetypes, 'name');
//$editstatesection->addField($field);

$editstatesection->show();




$insertsection = new UISection("Työvaiheen lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/projects/insertstate&projectID='. $registry->project->projectID);

$nimifield = new UITextField("Nimike", "Nimike", 'name');
$insertsection->addField($nimifield);



//$field = new UISelectField("Projekti","projectID","projectID",$registry->projects, "name");
//$insertsection->addField($field);

$insertsection->show();



$table = new UITableSection("Työvaiheet", "600px");
$table->setFramesVisible(true);
$table->setTableHeaderVisible(true);
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$table->addButton($button);
$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editstatesection->getID(),"stateID");

$column = new UISortColumn("#", "stateID", "tasks/projects/showstages", null, "10%");
$table->addColumn($column);

$column = new UISortColumn("Nimi", "name", "tasks/projects/showstages&sort=nimi");
$table->addColumn($column);

$column = new UIBooleanColumn("Startstate", "startstate", "tasks/projects/showstages&sort=nimi");
$column->setAlign(Column::ALIGN_CENTER);
$table->addColumn($column);

$column = new UIBooleanColumn("Backlog", "backlogstate", "tasks/projects/showstages&sort=nimi");
$column->setAlign(Column::ALIGN_CENTER);
$table->addColumn($column);

$column = new UIBooleanColumn("Completed", "completedstate", "tasks/projects/showstages&sort=nimi");
$column->setAlign(Column::ALIGN_CENTER);
$table->addColumn($column);

$column = new UIBooleanColumn("Cancelled", "cancelledstate", "tasks/projects/showstages&sort=nimi");
$column->setAlign(Column::ALIGN_CENTER);
$table->addColumn($column);

$table->setData($this->registry->states);
$table->show();




$inserttransitionsection = new UISection("Työvaihesiirymän lisäys");
$inserttransitionsection->setDialog(true);
$inserttransitionsection->setMode(UIComponent::MODE_INSERT);
$inserttransitionsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/projects/inserttransition&projectID='. $registry->project->projectID);

$field = new UITextField("Nimike", "name", 'name');
$inserttransitionsection->addField($field);

$field = new UISelectField("Lähdetila","startstateID","startstateID",$registry->states, 'name');
$inserttransitionsection->addField($field);

$field = new UISelectField("Kohdetila","targetstateID","targetstateID",$registry->states, 'name');
$inserttransitionsection->addField($field);

$inserttransitionsection->show();



$edittransitionsection = new UISection('Työvaihesiirymän muokkaus','500px');
$edittransitionsection->setDialog(true);
$edittransitionsection->setMode(UIComponent::MODE_EDIT);
$edittransitionsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/projects/updatetransition&projectID=' . $registry->project->projectID, 'transitionID');

$field = new UITextField("Nimike", "name", 'name');
$edittransitionsection->addField($field);

$field = new UISelectField("Lähdetila","startstateID","startstateID",$registry->states, 'name');
$edittransitionsection->addField($field);

$field = new UISelectField("Kohdetila","targetstateID","targetstateID",$registry->states, 'name');
$edittransitionsection->addField($field);

$edittransitionsection->show();



$table = new UITableSection("Työvaihesiirtymät", "600px");
$table->setFramesVisible(true);
$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $edittransitionsection->getID(),"stateID");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $inserttransitionsection->getID(), "Lisää");
$table->addButton($button);

$column = new UISortColumn("#", "transitionID", "tasks/projects/showproject", null, "10%");
$table->addColumn($column);

$column = new UISelectColumn("Alkutila", "name", "startstateID", $registry->states);
$table->addColumn($column);

$column = new UISelectColumn("Kohdetila", "name", "targetstateID", $registry->states);
$table->addColumn($column);

$column = new UISortColumn("Nimi", "name", "tasks/projects/showproject");
$table->addColumn($column);

$table->setData($this->registry->transitions);
$table->show();




$insertboardsection = new UISection("Uuden boardin lisäys");
$insertboardsection->setDialog(true);
$insertboardsection->setMode(UIComponent::MODE_INSERT);
$insertboardsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/projects/insertboard&projectID=' . $this->registry->project->projectID);

$field = new UITextField("Nimike", "name", 'name');
$insertboardsection->addField($field);

$insertboardsection->show();



$editboardsection = new UISection('Boardin muokkaus','500px');
$editboardsection->setDialog(true);
$editboardsection->setMode(UIComponent::MODE_EDIT);
$editboardsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/projects/updateboard&projectID=' . $registry->project->projectID, 'boardID');

$field = new UITextField("Nimike", "name", 'name');
$editboardsection->addField($field);

$editboardsection->show();



$table = new UITableSection("Boardit", "600px");
$table->setFramesVisible(true);
$table->setTableHeaderVisible(false);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertboardsection->getID(), "Lisää");
$table->addButton($button);
$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editboardsection->getID(),"boardID");


$column = new UISortColumn("#", "boardID", "tasks/tasks/temp", null, "10%");
$table->addColumn($column);

$column = new UISortColumn("Nimi", "name", "tasks/workflow/showstages&sort=nimi");
$table->addColumn($column);

$table->setData($registry->boards);
$table->show();






$insertboardcolumnsection = new UISection("Uuden boardin sarakken lisäys");
$insertboardcolumnsection->setDialog(true);
$insertboardcolumnsection->setMode(UIComponent::MODE_INSERT);
$insertboardcolumnsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/projects/insertboardcolumn&projectID=' . $registry->project->projectID);


$field = new UISelectField("Boardi","boardID","boardID",$registry->boards, 'name');
$insertboardcolumnsection->addField($field);

$field = new UITextField("Nimike", "name", 'name');
$insertboardcolumnsection->addField($field);

$insertboardcolumnsection->show();



$editboardcolumnsection = new UISection('Boardin sarakkeen muokkaus','500px');
$editboardcolumnsection->setDialog(true);
$editboardcolumnsection->setMode(UIComponent::MODE_EDIT);
$editboardcolumnsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/projects/updateboardcolumn&projectID=' . $registry->project->projectID, 'boardcolumnID');

$field = new UITextField("Nimike", "name", 'name');
$editboardcolumnsection->addField($field);

$editboardcolumnsection->show();




$table = new UITableSection("Boardin sarakkeet", "600px");
$table->setFramesVisible(true);
$table->setTableHeaderVisible(false);
$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editboardcolumnsection->getID(),"boardcolumnID");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertboardcolumnsection->getID(), "Lisää");
$table->addButton($button);

$column = new UISortColumn("#", "boardcolumnID", "tasks/tasks/temp", null, "10%");
$table->addColumn($column);

$column = new UISelectColumn("Board", "name", "boardID", $registry->boards);
$table->addColumn($column);

$column = new UISortColumn("Nimi", "name", "tasks/workflow/showstages&sort=nimi");
$table->addColumn($column);

$table->setData($this->registry->boardcolumns);
$table->show();





$insertboardcolumnmappingsection = new UISection("Uuden sarakkentila");
$insertboardcolumnmappingsection->setDialog(true);
$insertboardcolumnmappingsection->setMode(UIComponent::MODE_INSERT);
$insertboardcolumnmappingsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/projects/insertstatemapping&projectID=' . $registry->project->projectID);


$field = new UISelectField("Boardi","boardID","boardID",$registry->boards, 'name');
$insertboardcolumnmappingsection->addField($field);

$field = new UISelectField("Tila","stateID","stateID",$registry->states, 'name');
$insertboardcolumnmappingsection->addField($field);

$field = new UISelectField("Sarake","boardcolumnID","boardcolumnID",$registry->boardcolumns, 'name');
$insertboardcolumnmappingsection->addField($field);

$insertboardcolumnmappingsection->show();



$editboardcolumnmappingsection = new UISection('Boardin muokkaus','500px');
$editboardcolumnmappingsection->setDialog(true);
$editboardcolumnmappingsection->setMode(UIComponent::MODE_EDIT);
$editboardcolumnmappingsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/projects/updatestatecolumnmappings&projectID=' . $registry->project->projectID, 'rowID');

$field = new UISelectField("Board","boardID","boardID",$registry->boards, 'name');
$editboardcolumnmappingsection->addField($field);

$field = new UISelectField("Sarake","boardcolumnID","boardcolumnID",$registry->boardcolumns, 'name');
$editboardcolumnmappingsection->addField($field);

$field = new UISelectField("Tila","stateID","stateID",$registry->states, 'name');
$editboardcolumnmappingsection->addField($field);

$editboardcolumnmappingsection->show();





$table = new UITableSection("Boardin saraketilat", "600px");
$table->setFramesVisible(true);
$table->setTableHeaderVisible(false);
$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editboardcolumnmappingsection->getID(),"rowID");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertboardcolumnmappingsection->getID(), "Lisää");
$table->addButton($button);

$column = new UISortColumn("#", "rowID");
$table->addColumn($column);

$column = new UISelectColumn("Board", "name", "boardID", $registry->boards);
$table->addColumn($column);

$column = new UISelectColumn("Sarake", "name", "boardcolumnID", $registry->boardcolumns);
$table->addColumn($column);

$column = new UISelectColumn("Tila", "name", "stateID", $registry->states);
$table->addColumn($column);

$table->setData($this->registry->statecolumnmappings);
$table->show();



$section = new UISection("Hallinta");
$section->show();




?>

