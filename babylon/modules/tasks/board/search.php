<?php


include ("header.php");



$projectfilter = new UIFilterBox();
$projectfilter->addSelectFilter($this->registry->projectID, $this->registry->projects, "tasks/board/search", "", "projectID", "name");
$projectfilter->setEmptySelect(false);

$statefilter = new UIFilterBox();
$statefilter->addSelectFilter($this->registry->stateID, $this->registry->states, "tasks/board/search", "", "stateID", "name");
$statefilter->setEmptySelect(true, "");

$labelfilter = new UIFilterBox();
$labelfilter->addSelectFilter($this->registry->labelID, $this->registry->labels, "tasks/board/search", "", "labelID", "name");
$labelfilter->setEmptySelect(true, "");


echo "<table style='width:700px;'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$projectfilter->show();
echo "		</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$statefilter->show();
echo "		</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$labelfilter->show();
echo "		</td>";
echo "	</tr>";

echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom;height:10px;'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
echo "		</td>";
echo "	</tr>";
echo "</table>";



$insertsection = new UISection("Tehtävän lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/board/inserttask');

$project = $registry->projects[$registry->projectID];
$field = new UIFixedTextField("Project", $registry->projectID, 'projectID', $registry->projectID);
$insertsection->addField($field);

//$field = new UIFixedTextField("Project", $project->name);
//$insertsection->addField($field);

$nimifield = new UITextField("Otsikko", "name", 'name');
$insertsection->addField($nimifield);

$field = new UISelectField("Alkutila","stateID","stateID",$registry->startstates, 'name');
$insertsection->addField($field);

$field = new UISelectField("Label","labelID","labelID",$registry->labels, 'name');
$insertsection->addField($field);

$insertsection->show();



$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää tehtävä");

echo "	<table style='width:700px'>";
echo "		<tr>";
echo "			<td class=pagetitle style='font-size:24px;font-weight:bold;text-align:right;'>";
$button->show();
echo "			</td>";
echo "		</tr>";
echo "	</table>";


echo "<table style='width:700px;border-collapse:separate; border-spacing: 0 3px; '>";

foreach($this->registry->tasks as $index => $task) {

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
	
	// Taskin numero
	if ($state->cancelledstate == 1) {
		echo "			<td class=" . $class . " style='width:10%;min-width:100px;text-decoration: line-through;'>";
		echo "" . $task->itemID . "</td>";
	} else {
		echo "			<td class=" . $class . " style='width:10%;min-width:100px;'>" . $task->itemID . "</td>";
	}
	
	// Nimi kenttä
	echo "			<td class=" . $class . "  style='width:50%;padding-top:0px;padding-bottom:0px;'>";
	echo "				<a class=a-taskboard href='". getUrl('tasks/board/showtask') . "&tasksource=search&id=" . $task->taskID . "'>";
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
echo "</table>";

echo "<script>";
echo "	function taskrowclicked(taskID) {";
echo "			window.location='".getUrl('tasks/board/showtask')."&tasksource=search&id='+taskID;";
echo "	}";
echo "</script>";



?>