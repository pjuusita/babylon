<?php


include ("header.php");



$boardfilter = new UIFilterBox();
$boardfilter->addSelectFilter($this->registry->boardID, $this->registry->boards, "tasks/board/showtaskboard", "", "boardID", "name");
$boardfilter->setEmptySelect(false);

$labelfilter = new UIFilterBox();
$labelfilter->addSelectFilter($this->registry->labelID, $this->registry->labels, "tasks/board/showtaskboard", "", "labelID", "name");
$labelfilter->setEmptySelect(true, "");

$insertsection = new UISection("Tehtävän lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/board/inserttask');

$project = $registry->projects[$registry->projectID];
$field = new UIFixedTextField("Project", $registry->projectID, 'projectID', $registry->projectID);
$insertsection->addField($field);

$nimifield = new UITextField("Otsikko", "name", 'name');
$insertsection->addField($nimifield);

$field = new UISelectField("Alkutila","stateID","stateID",$registry->startstates, 'name');
$insertsection->addField($field);

$field = new UISelectField("Label","labelID","labelID",$registry->labels, 'name');
$insertsection->addField($field);

$insertsection->show();

$comments = false;



if (count($this->registry->columns) == 1) {
	echo "<table style='width:700px;'>";
} else {
	echo "<table style='width:800px;'>";
}
echo "	<tr>";
echo "		<td rowspan=3 style='width:70%;vertical-align:bottom'>";
echo "<h1>" . $registry->board->name . "</h1>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$boardfilter->show();
echo "		</td>";
echo "	</tr>";
echo "		<td style='width:40%;text-align:right;'>";
$labelfilter->show();
echo "		</td>";
echo "	</tr>";
echo "	</tr>";
echo "		<td style='width:40%;text-align:right;padding-top:20px;'>";
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää tehtävä");
$button->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";

/*
if (count($this->registry->columns) == 1) {
	echo "<table style='width:700px;'>";
} else {
	echo "<table style='width:800px;'>";
}
echo "	<tr>";
echo "		<td style='width:70%;vertical-align:bottom'>";
echo "<h1>" . $registry->board->name . "</h1>";
echo "		</td>";
echo "			<td class=pagetitle style='width:30%;font-size:24px;font-weight:bold;text-align:right;vertical-align:bottom;'>";
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää tehtävä");
$button->show();
echo "			</td>";
echo "		</tr>";
echo "</table>";
*/


if (count($this->registry->columns) == 1) {

	$firstcolumn = null;
	foreach($this->registry->columns as $index => $column) {
		$firstcolumn = $column;
	}


	
	echo "<table style='width:700px;border-collapse:separate; border-spacing: 0 3px; '>";
	
	foreach($this->registry->tasks as $index => $task) {
	
		if (isset($this->registry->mapping[$task->stateID])) {
			$columnID = $this->registry->mapping[$task->stateID];
			//echo "<br>ThisID - " . $column->boardcolumnID;
			//echo "<br>ColumnID - " . $columnID;
			if ($columnID == $column->boardcolumnID) {
				$state = $registry->states[$task->stateID];
				//echo "<br>State - " . $state->name . " - " . $state->cancelledstate;
				if ($state->cancelledstate == 1) {
					$rowclass = "tasklist-row-cancelled";
					$class = "tasklist-item-cancelled";
				} elseif ($state->completedstate == 1) {
					$rowclass = "tasklist-row-cancelled";
					$class = "tasklist-item-cancelled";
				} else {
					$rowclass = "tasklist-row";
					$class = "tasklist-item";
				}
				
				echo "		<tr class=" . $rowclass . " onclick='taskrowclicked(" . $task->taskID . ")'>";
				if ($state->cancelledstate == 1) {
					echo "			<td class=" . $class . " style='width:10%;min-width:100px;text-decoration: line-through;'>" . $task->itemID . "</td>";
				} else {
					echo "			<td class=" . $class . " style='width:10%;min-width:100px;'>" . $task->itemID . "</td>";
				}
				
				echo "			<td class=" . $class . "  style='width:50%;padding-top:0px;padding-bottom:0px;'>";
				echo "				<a class=a-taskboard href='". getUrl('tasks/tasks/showtask') . "&tasksource=backlog&id=" . $task->taskID . "'>";
				echo "					<div style='width:100%;height:100%;'>";
				echo "						<div style='width:370px;overflow:hidden;height:18px;padding-top:3px;padding-bottom:3px;'>";
				echo "						" . $task->name . "";
				echo "						</div>";
				echo "					</div>";
				echo "				</a>";
				echo "			</td>";
	
				$state = $registry->states[$task->stateID];
				echo "			<td class=" . $class . "  style='width:15%;'><div style='width:90px;overflow:hidden;white-space:nowrap;'>" . $state->short . "</div></td>";
				echo "			<td class=" . $class . " style='width:15%;'>";
				foreach($this->registry->tasklabels as $index => $labellink) {
					if ($labellink->taskID == $task->taskID) {
						$label = $this->registry->labels[$labellink->labelID];
						echo "<div class=taskboard-label style='float:right;text-align:center;width:50px;background-color:#" . $label->colorcode . ";'>" . $label->short . "</div>";
					}
				}
				echo "			</td>";
				echo "			<td class=" . $class . " style='width:10%;text-align:right;padding-right:10px;'>10</td>";
				echo "		</tr>";
				
			}
		}
	}
	echo "</table>";
	

	echo "<script>";
	echo "	function taskrowclicked(taskID) {";
	echo "			window.location='".getUrl('tasks/tasks/showtask')."&tasksource=backlog&id='+taskID;";
	echo "	}";
	echo "</script>";
	
	
	
	/*
	$table = new UIItemTable($registry->board->name . ", " . strtolower($column->name), "600px");
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää tehtävä");
	$table->addButton($button);
	
	$table->setLineAction(UIComponent::ACTION_FORWARD,"tasks/board/showtask","taskID");
	
	
	$column = new UISortColumn("#", "itemID", "tasks/board/showtasks", "80px");
	$table->addColumn($column);
	
	$column = new UISortColumn("Nimi", "name", "tasks/board/showtasks&sort=nimi", "420px");
	$table->addColumn($column);
	
	$column = new UISelectColumn("Label", "name", "labelID", $registry->tasklabels, null, "100px");
	$table->addColumn($column);

	$selectedtasks = array();
	foreach($this->registry->tasks as $index => $task) {
		$selected = false;
		//echo "<br>State - " . $task->stateID . " - " . $task->taskID . " - " . $task->name;
		if (isset( $this->registry->mapping[$task->stateID])) {
			//echo "<br> - Mapping - " . $this->registry->mapping[$task->stateID];
			$columnID = $this->registry->mapping[$task->stateID];
			if ($columnID == $firstcolumn->boardcolumnID) {
				$selected = true;
			}
		}
		if ($selected == true) $selectedtasks[] = $task;
	}
	
	$table->setData($selectedtasks);
	$table->show();
	*/
	
	
	exit;
}


foreach($this->registry->mapping as $stateID => $columnID) {
	if ($comments) echo "<br>Map - " . $stateID . " - " . $columnID;
}

if (count($this->registry->states) == 0) {
	echo "<br>No states";
	exit;
}

foreach($this->registry->tasks as $index => $task) {
	
	//if ($comments) echo "<br>stateID - " . $task->stateID;
	//$comments = true;
	if (isset( $this->registry->mapping[$task->stateID])) {
		if ($comments) echo "<br>StateID - " . $task->stateID;
		$map = $this->registry->mapping[$task->stateID];
		if ($comments) echo "<br>ColumnID - " . $map;
		$column = $this->registry->columns[$map];
		if ($comments) echo "<br>Column - " . $task->name . " - " . $column->name;
	} else {
		$state = $this->registry->states[$task->stateID];
		if ($comments) echo "<br>Not in board - stateID - " . $task->stateID;		
		if ($comments) echo "<br>Not in board - " . $task->title . " - " . $state->name;		
	}
}




echo "<table>";
echo "	<tr class=section-header-open style='height:20px;font-size:20px;padding: 4px 4px 4px 14px'>";
foreach($this->registry->columns as $index => $column) {
	echo "		<td style='width:250px;border-bottom:1px thin solid;font-weight:bold;'>" . $column->name . "</td>";
}
echo "	</tr>";

echo "	<tr>";
foreach($this->registry->columns as $index => $column) {
	echo "		<td style='border-left:1px thin solid;border-right:1px thin solid;vertical-align:top;'>";

	//echo "	Jeejee";
	foreach($this->registry->tasks as $index => $task) {
		if (isset($this->registry->mapping[$task->stateID])) {
			$columnID = $this->registry->mapping[$task->stateID];
			//echo "<br>ThisID - " . $column->boardcolumnID;
			//echo "<br>ColumnID - " . $columnID;
			if ($columnID == $column->boardcolumnID) {
				echo "				<a class=a-taskboard href='". getUrl('tasks/tasks/showtask') . "&tasksource=taskboard&id=" . $task->taskID . "'>";
				
				echo "<div class=taskboard-item onclick='taskclicked(" . $task->taskID . ")'>";
				echo "<table cellpadding=0 cellspacing=0>";
				echo "<tr>";
				echo "	<td colspan=2 class=taskboard-title>" . $task->itemID . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "	<td colspan=2><div class=taskboard-content style='width:250px;'>" . $task->name . "</div></td>";
				echo "</tr>";
				echo "<tr>";
				echo "	<td>";

				if ($task->priorityID == 0) {
					echo "<div style='width:20px;'>-</div>";
				} else {
					$priority = $this->registry->priorities[$task->priorityID];
					$color = $this->registry->colors[$priority->colorID];
					echo "<div style='width:14px;height:14px;background-color:#" . $color->normal . ";border-radius: 7px;-moz-border-radius: 7px;'></div>";
				}
				echo "	</td>";
				echo "	<td>";
				
				foreach($this->registry->tasklabels as $index => $labellink) {
					if ($labellink->taskID == $task->taskID) {
						$label = $this->registry->labels[$labellink->labelID];
						echo "<div class=taskboard-label style='float:right;text-align:center;width:50px;background-color:#" . $label->colorcode . ";'>" . $label->short . "</div>";
					}
				}
				
				
				echo "</td>";
				echo "</tr>";
				echo "</table>";
				echo "</div>";
				echo "</a>";
				
				
				
			}
		}
		
	}
	echo "		</td>";
}
echo "	</tr>";

echo "<script>";
echo "	function taskclicked(taskID) {";
echo "			window.location='".getUrl('tasks/tasks/showtask')."&tasksource=taskboard&id='+taskID;";
echo "	}";
echo "</script>";



echo "</table>";


?>