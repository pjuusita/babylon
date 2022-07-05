<?php


$boardfilter = new UIFilterBox();
$boardfilter->addSelectFilter($this->registry->languageID, $registry->languages, "worder/lessons/showlessons", "", "languageID", "name");
$boardfilter->setEmptySelect(false);
		
$taskfilter = new UIFilterBox();
$taskfilter->addSelectFilter($this->registry->generatorID, $registry->generators, "worder/lessons/showlessons", "", "generatorID", "name");

$statefilter = new UIFilterBox();
$statefilter->addSelectFilter($this->registry->stateID, $registry->states, "worder/lessons/showlessons", "", "stateID");

//$taskfilter->setEmptySelect(false);




$insertsection = new UISection("Oppitunnin lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setInsertAction(UIComponent::ACTION_FORWARD, 'worder/lessons/insertlesson');

$field = new UISelectField("Kieli","languageID","languageID",$registry->languages, 'name');
$insertsection->addField($field);

$nimifield = new UITextField("Nimike", "name", 'name');
$insertsection->addField($nimifield);

//$field = new UITextField("Lyhyt kuvaus", "shortdesc", 'shortdesc');
//$insertsection->addField($field);

$row = new Row();
$row->languageID = $registry->languageID;
$insertsection->setData($row);
$insertsection->show();



$languagessection = new UISection('Kielten näkyvyys','500px');
$languagessection->setDialog(true);
$languagessection->setMode(UIComponent::MODE_EDIT);
$languagessection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/lessons/updateactivelanguages');

$row = new Row();

foreach($this->registry->languages as $index => $language) {
	
	$field = new UIBooleanField($language->name, 'language-' . $language->languageID, 'language-' . $language->languageID);
	$languagessection->addField($field);
	if (isset($this->registry->activelanguages[$language->languageID])) {
		$var = 'language-' . $language->languageID;
		$row->$var = 0;
	}
}

foreach($this->registry->activelanguages as $index => $languageID) {
	$var = 'language-' . $languageID;
	$row->$var = 1;
}
$languagessection->setData($row);
$languagessection->show();





echo "<table style='width:700px;'>";
echo "	<tr>";
echo "		<td rowspan=4 style='width:70%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;padding-right:22px;'>";
$boardfilter->show();
echo "		</td>";
echo "	</tr>";
echo "	<tr>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;padding-right:22px;'>";
$taskfilter->show();
echo "		</td>";
echo "	</tr>";
echo "	<tr>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;padding-right:22px;'>";
$statefilter->show();
echo "		</td>";
echo "	</tr>";



echo "	<tr>";
echo "		<td style='width:40%;text-align:right;padding-right:22px;'>";
echo "<label style='display:block;width:200px;'><input type='checkbox' onChange=\"showallclicked()\" class='uitextfield' style='vertical-align:middle;' id='showcountscheckbox'> show counts</label>";
echo "		</td>";
echo "	</tr>";

echo "<script>";
echo "	function showallclicked() {";
echo "		console.log('showallclicked');";
echo "		if ($('#showcountscheckbox').is(':checked')) {";
echo "			window.location = '" . getUrl('worder/lessons/showlessons') . "&lessoncounts=1';";
echo "		} else {";
echo "			window.location = '" . getUrl('worder/lessons/showlessons') . "&lessoncounts=0';";
echo "		}";
echo "	}";
echo "</script>";



$table = new UITableSection("Lessons", "700px");
$table->setSettingsAction($languagessection);
$table->setLineAction(UIComponent::ACTION_FORWARD,"worder/lessons/showlesson","lessonID");
$table->setLineBackground("color");
$table->showRowNumbers(true);

$button = new UIButton(UIComponent::ACTION_FORWARD,  'worder/lessons/lessonresort&languageID=' . $this->registry->languageID, 'Resort');
$table->addButton($button);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$table->addButton($button);



$lessonIDcolumn = new UISortColumn("ID", "lessonID", "worder/lessons/showlesson", null, "10%");
$table->addColumn($lessonIDcolumn);


foreach($this->registry->activelanguages as $index => $languageID) {
	$language = $this->registry->languages[$languageID];
	$var = "name" . $languageID;
	$column = new UIMultilangColumn($language->name, "name", $languageID);
	$table->addColumn($column);
}



if ($this->registry->showcounts == true) {

	$column = new UISortColumn("N#", "subscount", "worder/lessons/showlesson&sort=nimi", null, "10%");
	$table->addColumn($column);
	
	$column = new UISortColumn("V#", "verbcount", "worder/lessons/showlesson&sort=nimi", null, "10%");
	$table->addColumn($column);
	
	$column = new UISortColumn("A#", "adjcount", "worder/lessons/showlesson&sort=nimi", null, "10%");
	$table->addColumn($column);
	
	$column = new UISortColumn("o#", "othercount", "worder/lessons/showlesson&sort=nimi", null, "10%");
	$table->addColumn($column);
	
	$column = new UISortColumn("Ttl.", "totalcount", "worder/lessons/showlesson&sort=nimi", null, "10%");
	$table->addColumn($column);
	
	$column = new UISortColumn("Obj.", "objectivecount", "worder/lessons/showlesson&sort=nimi", null, "10%");
	$table->addColumn($column);
	
	//$column = new UISortColumn("Sort", "sortorder", "worder/lessons/showlesson&sort=nimi", null, "10%");
	//$table->addColumn($column);
	
	//$column = new UISortColumn("Active", "active", "worder/lessons/showlesson&sort=nimi", null, "10%");
	//$table->addColumn($column);

	//$column = new UISortColumn("State", "taskstate", "worder/lessons/showlesson&sort=nimi", null, "10%");
	//$table->addColumn($column);
	
	if ($registry->generatorID > 0) {
		$colors = array();
		$colors[0] = "#fffdd0";
		$colors[1] = "#90ee90";
		
		$column = new UIBallColumn("State", "taskstate", "taskstate", $colors);
		$table->addColumn($column);
	}
	
	
	//echo "<br><br>Active: " . $this->registry->activecount;
	//echo "<br><br>Finished: " . $this->registry->finished;
	//echo "<br>Unfinished: " . $this->registry->unfinished;
} else {
	$column = new UISortColumn("Sortordere", "sortorder");
	$table->addColumn($column);
	
	//echo "<br><br>Active: " . $this->registry->activecount;
	//echo "<br><br>Finished: " . $this->registry->finished;
	//echo "<br>Unfinished: " . $this->registry->unfinished;
}
$table->setData($registry->lessons);
$table->show();


echo "<script type=\"text/javascript\">";
echo "	$(document).ready(function() {";
echo "		console.log('document ready');";

echo "		$('#sectiontbody" . $table->getID() . "').sortable({";
echo "			start: function (e, ui) {";
echo "				console.log('start drag');";
echo "			},";
echo "			stop: function (e, ui) {";
echo "				console.log('end drag');";
echo "				console.log('elementid - '+ui.item[0].id);";
echo "				var elementName = '#'+ui.item[0].id + '-" . $lessonIDcolumn->getID() . "';"; 
echo "				var lessonID = $('#'+ui.item[0].id + '-" . $lessonIDcolumn->getID() . "').val();";
echo "				console.log('lessonID - '+ lessonID);"; 
echo "				var prev = ui.item[0].previousSibling;";
echo "				var next = ui.item[0].nextSibling;";
echo "				console.log('prev ID - '+ prev.id);";
echo "				console.log('next ID - '+next.id);";
echo "				if (typeof prev.id == 'undefined') {";
echo "					if (typeof next.id == 'undefined') {";
echo "						console.log('both undefined');";
echo "						return;";
echo "					};";
echo "					console.log('sibling undefined');";
echo "					var next = ui.item[0].nextSibling;";
echo "					var nextID = $('#'+next.id + '-" . $lessonIDcolumn->getID() . "').val();";
echo "					console.log('nextID - '+nextID);";
echo "					console.log('" . getUrl('worder/lessons/lessondragdrop') . "&languageID=" . $this->registry->languageID . "&lessonID='+lessonID+'&previousID='+nextID);";
echo "					window.location = '" . getUrl('worder/lessons/lessondragdrop') . "&languageID=" . $this->registry->languageID . "&lessonID='+lessonID+'&previousID='+nextID;";
echo "				} else {";
echo "					var prevID = $('#'+prev.id + '-" . $lessonIDcolumn->getID() . "').val();";
echo "					console.log('prevID - '+ prevID);";
echo "					console.log('" . getUrl('worder/lessons/lessondragdrop') . "&languageID=" . $this->registry->languageID . "&lessonID='+lessonID+'&previousID='+prevID);";
echo "					window.location = '" . getUrl('worder/lessons/lessondragdrop') . "&languageID=" . $this->registry->languageID . "&lessonID='+lessonID+'&previousID='+prevID;";
echo "				}";
echo "				console.dir(e);";
echo "				console.dir(ui);";
echo "			}";
echo "		});";
echo "		console.log('sortable');";
echo "	})";
echo "</script>";






?>