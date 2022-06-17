<?php

//echo "<br>windowID:" . $_SESSION['windowID'];
//echo "<br>";

//echo "" . $_SERVER['PHP_SELF'];
//echo "<br>" . $_SERVER['QUERY_STRING'];
//echo "<br>";

// [15.10.2021] Kopioitu projects/tasks.php

$projectfilter = new UIFilterBox();
$projectfilter->addSelectFilter($this->registry->projectID, $this->registry->projects, "tasks/tasks/showtasks", "Kaikki","projectID", "name");
$projectfilter->setEmptySelect(false);

$statefilter = new UIFilterBox();
$statefilter->addSelectFilter($this->registry->stateID, $this->registry->states, "tasks/tasks/showtasks", "", "stateID", "name");
$statefilter->setEmptySelect(true, "");

$userfilter = new UIFilterBox();
$userfilter->addSelectFilter($this->registry->userID, $this->registry->users, "tasks/tasks/showtasks", "", "userID", "name");
$userfilter->setEmptySelect(false);

$labelfilter = new UIFilterBox();
$labelfilter->addSelectFilter($this->registry->labelID, $this->registry->labels, "tasks/tasks/showtasks", "", "labelID", "name");
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

/*
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$userfilter->show();
echo "		</td>";
echo "	</tr>";

*/
echo "</table>";


$insertsection = new UISection("Tehtävän lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/tasks/inserttask');

$nimifield = new UITextField("Otsikko", "name", 'name');
$insertsection->addField($nimifield);

// TODO: Pitää tsekata onko ko. käyttäjällä oikeuksia lisätä useampaan projektiin, jos vain yksi niin fixed
$field = new UISelectField("Projekti","projectID","projectID", $registry->projects, "name");
$insertsection->addField($field);

$field = new UISelectField("Label","labelID","labelID", $registry->labels, "name");
$insertsection->addField($field);

$field = new UISelectField("Prioriteetti","priorityID","priorityID", $registry->priorities, "name");
$insertsection->addField($field);

$field = new UISelectField("Alkutila","stateID","stateID", $registry->states, "name");
$insertsection->addField($field);


// TODO: Pitää tsekata onko ko. käyttäjällä oikeuksia lisätä taskeja muille kuin itselleen...
// TODO: käyttäjiin pitäisi oikeastaan pompsahtaa vain ko. projektin memberit..
//$field = new UISelectField("Käyttäjä","userID","userID", $registry->users, "username");
//$insertsection->addField($field);

// TODO: oletukseksi tulee ensimmäinne startstate, jos jätetään tyhjäksi, niin 
// TODO: ehkä state pitää piilottaa ja aina oletus startstateen.
//$field = new UISelectField("State","stateID","stateID", $registry->states, "name");
//$insertsection->addField($field);

$row = new Row();
$row->projectID = $this->registry->projectID;
if (isset($this->registry->labelID)) $row->labelID = $this->registry->labelID;
if (isset($this->registry->stateID)) $row->stateID = $this->registry->stateID;
$insertsection->setData($row);
$insertsection->show();


// TODO: UIItemtable addButton ei toimi



$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää tehtävä");

echo "	<table style='width:700px'>";
echo "		<tr>";
echo "			<td class=pagetitle style='font-size:24px;font-weight:bold;text-align:right;text-align:left;'>";
echo "<font style='font-size:24px;font-weight:bold;vertical-align:bottom;'>Tehtävät</font>";
echo "			</td>";
echo "			<td class=pagetitle style='font-size:24px;font-weight:bold;text-align:right;vertical-align:bottom;'>";
$button->show();
echo "			</td>";
echo "		</tr>";
echo "	</table>";


echo "<table style='width:700px;border-collapse:separate; border-spacing: 0 3px; '>";
echo "<tbody id='tasksbody'>";

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
	echo "		<tr id=taskIDtr-" . $task->taskID . " class=" . $rowclass . " onclick='taskrowclicked(" . $task->taskID . ")'>";

	echo "			<td class=" . $class . " style='width:10%;min-width:35px;'>" . $task->taskID;
	echo "<input id='taskIDtr-" . $task->taskID . "id' type='hidden' value='" . $task->taskID . "'>";
	echo "			</td>";
	
	// Taskin numero
	if ($state->cancelledstate == 1) {
		echo "			<td class=" . $class . " style='width:10%;min-width:100px;text-decoration: line-through;'>";
		echo "" . $task->itemID . "</td>";
	} else {
		echo "			<td class=" . $class . " style='width:10%;min-width:100px;'>" . $task->itemID . "</td>";
	}
	
	if ($task->priorityID == 0) {
		echo "<td class=" . $class . "  style='min-width:30px;'>";
		echo "<div style='width:20px;'>aa</div>";
		echo "</td>";
	} else {
		$priority = $this->registry->priorities[$task->priorityID];
		$color = $this->registry->colors[$priority->colorID];
		echo "<td class=" . $class . "  style='min-width:30px;'>";
		echo "<div style='width:14px;height:14px;background-color:#" . $color->normal . ";border-radius: 7px;-moz-border-radius: 7px;'></div>";
		echo "</td>";
	}

	// Nimi kenttä
	echo "			<td class=" . $class . "  style='width:50%;padding-top:0px;padding-bottom:0px;'>";
	echo "				<a class=a-taskboard href='". getUrl('tasks/tasks/showtask') . "&tasksource=search&id=" . $task->taskID . "'>";
	echo "					<div style='width:100%;height:100%;'>";
	echo "						<div style='width:335px;overflow:hidden;height:18px;padding-top:3px;padding-bottom:3px;'>";
	echo "						" . $task->name . "";
	echo "						</div>";
	echo "					</div>";
	echo "				</a>";
	echo "			</td>";

	$state = $registry->states[$task->stateID];
	echo "			<td class=" . $class . "  style='width:15%;'><div style='width:70px;overflow:hidden;white-space:nowrap;'>" . $state->short . "</div></td>";
	echo "			<td class=" . $class . " style='min-width:140px;'>";
	$labelcount = 0;
	foreach($this->registry->tasklabels as $index => $labellink) {
		if ($labellink->taskID == $task->taskID) {
			$label = $this->registry->labels[$labellink->labelID];
			$margin = "";
			if ($labelcount > 0) $margin = "margin-top:1px;";
			echo "<div class=taskboard-label style='float:right;" . $margin . "text-align:center;width:50px;background-color:#" . $label->colorcode . ";'>" . $label->short . "</div>";
			$labelcount++;
		}
	}
	echo "			</td>";
	//echo "			<td class=" . $class . " style='width:10%;text-align:right;padding-right:10px;'>10</td>";
	echo "		</tr>";
}
echo "</tbody>";
echo "</table>";

echo "<script>";
echo "	function taskrowclicked(taskID) {";
echo "			window.location='".getUrl('tasks/tasks/showtask')."&tasksource=search&id='+taskID;";
echo "	}";
echo "</script>";




echo "<script type=\"text/javascript\">";
echo "	$(document).ready(function() {";
echo "		console.log('document ready');";

/*
 echo "		$('#sectiontable" . $table->getID() . "').tableDnD({";
 echo "			onDrop: function(table, row) {";
 echo "				console.log('droppi');";
 echo "			}";
 echo "		});";
 */

echo "		$('#tasksbody').sortable({";
echo "			start: function (e, ui) {";
echo "				console.log('start drag');";
////echo "				console.dir(e);";
////echo "				console.dir(ui);";
echo "			},";
echo "			stop: function (e, ui) {";
echo "				console.log('end drag');";
echo "				console.log('-------------------');";

echo "				console.log('elementid - '+ui.item[0].id);";
echo "				var currentID = $('#'+ui.item[0].id + 'id').val();";

echo "				console.log('currentID - '+currentID);";

echo "				var prev = ui.item[0].previousSibling;";
echo "				var next = ui.item[0].nextSibling;";

echo "				if (next == null) {";						// Siirretään listan viimeiseksi
echo "					console.log('next is null');";
echo "					console.log('fff prev ID - '+prev.id);";
echo "					var prevID = $('#'+prev.id + 'id').val();";
echo "					console.log('fff prevID - '+prevID);";
echo "					window.location = '" . getUrl('tasks/tasks/tasklistdragdropdown') . "&projectID=" . $this->registry->projectID . "&stateID=" . $this->registry->stateID . "&labelID=" . $this->registry->labelID . "&currentID='+currentID+'&previousID='+prevID;";
echo "					return;";
echo "				} else {";
echo "					console.log('bbb next ID - '+next.id);";
echo "				}";

echo "				if (typeof prev.id == 'undefined') {";		// siirretään listan ylimmäiseksi

echo "					var nextID = $('#'+next.id + 'id').val();";
echo "					console.log('ddd nextID - '+nextID);";
echo "					window.location = '" . getUrl('tasks/tasks/tasklistdragdropdown') . "&projectID=" . $this->registry->projectID . "&stateID=" . $this->registry->stateID . "&labelID=" . $this->registry->labelID . "&currentID='+currentID+'&previousID='+nextID;";
//echo "					window.location = '" . getUrl('tasks/tasks/tasklistdragdropdown') . "&projectID=" . $this->registry->projectID . "&stateID=" . $this->registry->stateID . "&labelID=" . $this->registry->labelID . "&currentID='+currentID+'&previousID='+nextID;";
echo "					return;";
echo "				} else {";
echo "					var prevID = $('#'+prev.id + 'id').val();";
echo "					console.log('eee prevID - '+ prevID);";
echo "					window.location = '" . getUrl('tasks/tasks/tasklistdragdropdown') . "&projectID=" . $this->registry->projectID . "&stateID=" . $this->registry->stateID . "&labelID=" . $this->registry->labelID . "&currentID='+currentID+'&previousID='+prevID;";
echo "				}";
echo "			}";
echo "		});";
echo "		console.log('sortable');";

echo "	})";
echo "</script>";


?>