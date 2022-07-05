<?php

$viewwidth = "850px";

//echo "<a href='".getUrl('worder/lessons/showlessons')."'>Palaa lessonlistalle</a><br>";


$section = new UISection('Kielten näkyvyys','500px');
$section->setDialog(true);
$section->setMode(UIComponent::MODE_EDIT);
$section->setSaveAction(UIComponent::ACTION_FORWARD, 'crm/companies/insertcompany');

foreach($this->registry->languages as $index => $language) {
	$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/lessons/activatelanguage&languageID=" . $language->languageID . "&lessonID=" . $registry->lesson->lessonID , $language->name);
	$section->addButton($button);
}

$section->show();

echo "<table style='width:" . $viewwidth . "'>";
echo " 	<tr>";
echo "		<td>";
echo "<h1>" . parseMultilangString($this->registry->lesson->name,1) . "</h1>";
echo "		</td>";
echo "		<td>";
/*
echo "<div id=container style='height:34px;'>";
echo "<button id='button-add1' class=section-button-header style='margin-left:4px;width:26px;height:26px;margin-bottom:2px;float:right;'><i class='fa fa-plus' onclick='addbuttonclicked(1)'></i></button>";

foreach($this->registry->activelanguages as $index => $languageID) {
	$language = $this->registry->languages[$languageID];
	echo "<div id='langdiv-" . $languageID . "' class=div-item style='margin-left:4px;width:48px;height:20px;padding-top:2px;float:right;text-align:center;'>";
	echo "" . strtoupper($language->shortname);
	echo "</div>";	
	
	echo "<script>";
	echo "  $('#langdiv-" . $languageID . "').click(function () {";
	echo "		window.location = '" . getUrl("worder/lessons/deactivatelanguage") . "&languageID=" . $languageID . "&lessonID=" . $registry->lesson->lessonID . "';";
	echo "	});";
	echo "</script>";
}
echo "</div>";
*/
echo "		</td>";
echo "	</tr>";
echo "</table>";

echo "<script>";
echo "  $('#button-add1').click(function () {";
echo "  	$('#sectiondialog-" . $section->getID() . "').dialog('open');";
echo "	});";
echo "</script>";


//echo "<br>name - " . $registry->lesson->name;
//foreach($registry->names as $index => $name) {
//	echo "<br> name - " . $index . " - " . $name;
//}

// ---------------------------------------------------------------------------------------------------
// Add Language & RuleSet Dialog
// ---------------------------------------------------------------------------------------------------

$addlanguagesection = new UISection("Add Language");
$addlanguagesection->setDialog(true);
$addlanguagesection->setMode(UIComponent::MODE_INSERT);
$addlanguagesection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/lessons/insertlessondata&lessonID=' .  $registry->lesson->lessonID);

$languageselectfield = new UISelectField("Language","languageID","languageID",$registry->languages, "name");
$languageselectfield->setOnChange("languageselectfieldchanged()");
$addlanguagesection->addField($languageselectfield);

$lessonnamefield = new UITextField("Name", "name", 'name');
$addlanguagesection->addField($lessonnamefield);

$rulesetselectfield = new UISelectField("RuleSet","setID","setID",null, "name");
//$rulesetselectfield->setPredictable(true);
$rulesetselectfield->setDisabled(true);

$addlanguagesection->addField($rulesetselectfield);

$addlanguagesection->show();



echo "<script>";
echo "	function languageselectfieldchanged() {";

echo "		console.log('languageselectfieldchanged');";

echo "	 	var languagefield 		 	 = '#".$languageselectfield->getEditFieldID()."';";
echo "	 	var lessonnamefield 		 = '#".$lessonnamefield->getEditFieldID()."';";
echo "	 	var rulesetfield			 = '#".$rulesetselectfield->getEditFieldID()."';";
echo "	 	var languageID = $(languagefield).val();";

echo "		if (languageID == 0) {";
echo "	 		$(rulesetfield).empty();";
echo "			$(rulesetfield).attr('disabled', 'disabled');";
echo "			$(rulesetfield).addClass('uitextfield-disabled');";
echo "			$(rulesetfield).removeClass('uitextfield');";
echo "	 		$(lessonnamefield).empty();";
echo "			return;";
echo "		}";

echo "		console.log('" . getUrl('worder/rules/getlanguagerulesetsJSON') . "&languageID='+languageID);";

echo "		$.getJSON('" . getUrl('worder/rules/getlanguagerulesetsJSON') . "&languageID='+languageID,'',function(data) {";

echo "			console.log('getlanguagerulesetsJSON');";


echo "	 		$(rulesetfield).empty();";
echo "			$(rulesetfield).removeAttr('disabled');";
echo "			$(rulesetfield).addClass('uitextfield');";
echo "			$(rulesetfield).removeClass('uitextfield-disabled');";
echo "			$(rulesetfield).append($('<option>', {value:0, text:''}));";

echo "			$.each(data, function(index) {";
echo "				console.log(''+data[index].setID+' - '+data[index].name);";
echo "				$(rulesetfield).append($('<option>', {value:data[index].setID, text:data[index].name}));";
echo "			});";

echo "		}); ";

echo "	}";
echo "</script>";




$editlessondatasection = new UISection("Edit Name");
$editlessondatasection->setDialog(true);
$editlessondatasection->setMode(UIComponent::MODE_EDIT);
$editlessondatasection->setUpdateAction(UIComponent::ACTION_FORWARD, 'worder/lessons/updatelessondata&lessonID=' .  $registry->lesson->lessonID, "rowID");

//$languageselectfield = new UISelectField("Language","languageID","languageID",$registry->languages, "name");
//$languageselectfield->setOnChange("updatelanguageselectfieldchanged()");
//$editlessondatasection->addField($languageselectfield);

$lessonnamefield = new UITextField("Name", "name", 'name');
$editlessondatasection->addField($lessonnamefield);

//$rulesetselectfield = new UISelectField("RuleSet","setID","setID",null, "name");
//$rulesetselectfield->setPredictable(true);
//$editlessondatasection->setDisabled(true);
//$editlessondatasection->addField($rulesetselectfield);

$editlessondatasection->show();



echo "<script>";
echo "	function updatelanguageselectfieldchanged() {";

echo "		console.log('languageselectfieldchanged');";

echo "	 	var languagefield 		 	 = '#".$languageselectfield->getEditFieldID()."';";
echo "	 	var lessonnamefield 		 = '#".$lessonnamefield->getEditFieldID()."';";
echo "	 	var rulesetfield			 = '#".$rulesetselectfield->getEditFieldID()."';";
echo "	 	var languageID = $(languagefield).val();";

echo "		if (languageID == 0) {";
echo "	 		$(rulesetfield).empty();";
echo "			$(rulesetfield).attr('disabled', 'disabled');";
echo "			$(rulesetfield).addClass('uitextfield-disabled');";
echo "			$(rulesetfield).removeClass('uitextfield');";
echo "	 		$(lessonnamefield).empty();";
echo "			return;";
echo "		}";

echo "		console.log('" . getUrl('worder/rules/getlanguagerulesetsJSON') . "&languageID='+languageID);";

echo "		$.getJSON('" . getUrl('worder/rules/getlanguagerulesetsJSON') . "&languageID='+languageID,'',function(data) {";

echo "			console.log('getlanguagerulesetsJSON');";


echo "	 		$(rulesetfield).empty();";
echo "			$(rulesetfield).removeAttr('disabled');";
echo "			$(rulesetfield).addClass('uitextfield');";
echo "			$(rulesetfield).removeClass('uitextfield-disabled');";
echo "			$(rulesetfield).append($('<option>', {value:0, text:''}));";

echo "			$.each(data, function(index) {";
echo "				console.log(''+data[index].setID+' - '+data[index].name);";
echo "				$(rulesetfield).append($('<option>', {value:data[index].setID, text:data[index].name}));";
echo "			});";

echo "		}); ";

echo "	}";
echo "</script>";






$section = new UITableSection("Languages",$viewwidth);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

//$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/lessons/showlesson', 'lessonID');
//$editlessondatasection
$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editlessondatasection->getID(), "rowID");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addlanguagesection->getID(), 'Lisää');
$section->addButton($button);



$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/lessons/removelessondata&lessonID=' . $registry->lesson->lessonID, 'rowID');

$column = new UISortColumn("#","rowID","RowID");
$section->addColumn($column);

$column = new UISelectColumn("Language", "name", "languageID", $registry->languages);
$section->addColumn($column);

$column = new UISortColumn("Name", "name");
$section->addColumn($column);

$column = new UISortColumn("Ruleset", "rulesetID");
$section->addColumn($column);

$column = new UISelectColumn("Ruleset", "name", "rulesetID", $registry->rulesets);
$section->addColumn($column);

$section->setData($registry->lessondata);
$section->show();



$section = new UISection("Description", $viewwidth);
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/lessons/updatelesson', 'lessonID');

/*
foreach($this->registry->activelanguages as $index => $languageID) {
	$language = $this->registry->languages[$languageID];
	$var = "name" . $languageID;
	$field = new UITextField("Nimike-" . $language->shortname,$var,$var);
	$field->setnotsettedtext("<font style='color:red;font-style:italic;'>puuttuu</font>");
	$section->addField($field);
}

//$field = new UITextField("Nimike", "name", 'name');
//$section->addField($field);

$field = new UISelectField("Kieli","languageID","languageID",$registry->languages, 'name');
$section->addField($field);
*/

$field = new UITextAreaField("Lyhyt","shortdesc","shortdesc");
$section->addField($field);

$field = new UITextAreaField("Kuvaus","description","description");
$section->addField($field);


/*
$field = new UITextField("Ruleset", "rulesetID", 'rulesetID');
$section->addField($field);

$field = new UITextField("Level", "level", 'level');
$section->addField($field);

$field = new UIBooleanField("Active", "active", 'active');
$section->addField($field);

$field = new UISelectField("Difficulty","difficultylevel","difficultylevel",$registry->difficultylevels, 'name');
$section->addField($field);
*/



$field = new UISelectField("State","state","state", $registry->lessonstates);
$section->addField($field);

$section->setData($registry->lesson);
$section->show();





function wordSearchDiv() {

	global $registry;

	echo "	<table style='width:100%'>";
	echo "		<tr>";

	echo "			<td style='padding-right:5px;'>";
	echo "				<input class=uitextfield  id=searchfield type='text' style='width:100%;' type='text' value=''>";
	echo " 			</td>";

	echo "			<td style='padding-right:5px'>";
	echo "				<div>";
	echo "					<button  class=section-button  onclick='searchbuttonpressed()'>Etsi</button>";
	echo "				</div>";
	echo "			</td>";

	echo "	<script>";
	echo "		$('#searchfield').keypress(function (e) {";
	echo "			if (e.which == 13) {";
	echo "				searchbuttonpressed();";
	echo "			};";
	echo "		})";
	echo "	</script>";

	echo "		</tr>";


	echo "		<tr>";
	echo "			<td colspan=2>";

	echo "				<div id=searchloadingdiv style='display:none;height:100%;width:100%;'>";
	echo "					<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
	echo "				</div>";

	echo "				<div id=searchloadeddiv style='display:none;height:100%;width:100%;overflow:hidden'>";
	echo "					<div style='overflow-y:scroll;max-height:200px;'>";
	//echo "				<div id=resultpanel style='width:100%;height:50px;background-color:pink;'>result</div>";
	echo "					<table id=searchresulttable style='width:100%;height:50px;'>";
	echo "						<tr><td>Empty</td></tr>";
	echo "					</table>";
	echo "					</div>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	echo "	</table>";


	echo "	<script>";
	echo "		function addItem(conceptID) {";
	//echo "			var languageID = $('#languagefield').val();";
	echo "			window.location = '" . getUrl("worder/lessons/insertconcept") . "&conceptID='+conceptID+'&lessonID=" . $registry->lesson->lessonID . "';";
	echo "		}";
	echo "	</script>";


	echo "	<script>";
	echo "		function searchbuttonpressed() {";
	//echo "			console.log('search button pressed');";
	echo "			var searh = $('#searchfield').val();";
	echo "			if (searh == '') {";
	echo "				alert('ei saa olla tyhjä 2');";
	echo "			}";
	echo "			$('#searchloadingdiv').show();";
	echo "			$('#searchloadeddiv').hide();";
	echo "			var languageID = " . $registry->lesson->languageID . ";";
	//echo "			console.log('languageid -'+languageID+'-');";
	echo "			console.log('" . getUrl('worder/words/searchconcepts') . "&search='+searh);";

	echo "			$.getJSON('" . getUrl('worder/words/searchconcepts') . "&search='+searh+'&languageID='+languageID,'',function(data) {";
	//echo "					console.log('data.length - '+data.length);";
	echo "					$('#searchloadingdiv').hide();";
	echo "					$('#searchloadeddiv').show();";
	echo "					$('#searchresulttable tr').remove();";
	echo "					$.each(data, function(index) {";
	//echo "						console.log('row - '+data[index].conceptID+' - '+data[index].name);";
	echo "						var row = '<tr>'";
	//echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordclassID+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].conceptID+'</td>'";
	echo "							+ '<td title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/words/showword') . "&lang='+languageID+'&id='+data[index].wordID+'\">'+data[index].name+'</a></td>'";
	echo "							+ '<td><button onclick=\"addItem(\''+data[index].conceptID+'\')\">lisää</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#searchresulttable').append(row);";
	echo "					});";
	echo "			}); ";
	////echo " 			console.log('finish');";
	echo "		}";
	echo "	</script>";

}









// ---------------------------------------------------------------------------------------------------
// Lesson Prerequisites
// ---------------------------------------------------------------------------------------------------

$addlessonprerequisitesection = new UISection("Add Lesson Prerequisite");
$addlessonprerequisitesection->setDialog(true);
$addlessonprerequisitesection->setMode(UIComponent::MODE_INSERT);
$addlessonprerequisitesection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/lessons/insertlessonprerequisite&lessonID=' .  $registry->lesson->lessonID);

$field = new UISelectField("Lesson","lessonID","parentID",$registry->lessons, "name");
$field->setPredictable(true);
$addlessonprerequisitesection->addField($field);

$addlessonprerequisitesection->show();



$section = new UITableSection("Prerequisite Lessons",$viewwidth);
//$section->setOpen(true);
//$section->editable(true);
//$section->showLineNumbers(true);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/lessons/showlesson', 'lessonID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addlessonprerequisitesection->getID(), 'Lisää');
$section->addButton($button);
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/lessons/removeprerequisitelesson&lessonID=' . $registry->lesson->lessonID, 'lessonID');

$column = new UISortColumn("#","lessonID","LessonID");
$section->addColumn($column);

$column = new UIMultilangColumn("Lesson", "name", 1);
$section->addColumn($column);

$column = new UISortColumn("Short description", "shortdesc");
$section->addColumn($column);

$section->setData($registry->prelessons);
$section->show();



// ---------------------------------------------------------------------------------------------------
// Sanat
// ---------------------------------------------------------------------------------------------------


$searchsection = new UISection("Etsi käsite","500px");
$searchsection->setDialog(true);
$searchsection->setMode(UIComponent::MODE_INSERT);
//$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/lessons/insertlesson');

$searchsection->setCustomContent('wordSearchDiv');
$searchsection->show();



$orderlist = array();
foreach($registry->concepts as $index => $concept) {
	if (isset($orderarray[$concept->sortorder])) echo "<br>Sort fails - " . $concept->conceptID . " - " . $concept->sortorder;
	$orderlist[$concept->sortorder] = $concept->sortorder;
}


$section = new UITableSection("Words",$viewwidth);
$section->setOpen(true);
$section->editable(true);
$section->showLineNumbers(true);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/concepts/showconcept', 'conceptID');


$button = new UIButton(UIComponent::ACTION_FORWARD,  'worder/lessons/wordsresort&lessonID=' . $registry->lesson->lessonID, 'Resort');
$section->addButton($button);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $searchsection->getID(), 'Lisää käsite');
$section->addButton($button);

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/lessons/removewordfromlesson&lessonID=' . $registry->lesson->lessonID, 'conceptID');

foreach($this->registry->activelanguages as $index => $languageID) {
	$language = $this->registry->languages[$languageID];
	$column = new UISortColumn($language->name,$language->name,$language->name);
	$section->addColumn($column);
}

//$column = new UISortColumn("Sana","lemma","lemma");
//$section->addColumn($column);

$column = new UISortColumn("Käsite","name","Name");
//$column->setWidth("200px");
$section->setColumnWidth(3,'200px');
$section->addColumn($column);

$column = new UISelectColumn("Wordclass", "name", "wordclassID", $registry->wordclasses);
$section->addColumn($column);

$conceptcolumn = new UISortColumn("cID","conceptID");
$section->addColumn($conceptcolumn);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "conceptID", "worder/lessons/moveconcept&dir=up&lessonID=" . $registry->lesson->lessonID);
$column->setIcon("fa fa-chevron-up");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "conceptID", "worder/lessons/moveconcept&dir=down&lessonID=" . $registry->lesson->lessonID);
$column->setIcon("fa fa-chevron-down");
$section->addColumn($column);


$section->setData($registry->concepts);
$section->show();



echo "<script type=\"text/javascript\">";
echo "	var sortmutex = 0;";
echo "	$(document).ready(function() {";
echo "		console.log('document ready');";
echo "		$('#sectiontbody" . $section->getID() . "').sortable({";
echo "			start: function (e, ui) {";
echo "				console.log('start drag');";
echo "			},";
echo "			stop: function (e, ui) {";
echo "				if (sortmutex == 1) {";
echo "					console.log('end drag, but already processing');";
echo "					return;";
echo "				}";
echo "				console.log('end drag');";
echo "				console.log('elementid - '+ui.item[0].id);";
echo "				var elementName = '#'+ui.item[0].id + '-" . $conceptcolumn->getID() . "';";
echo "				var conceptID = $('#'+ui.item[0].id + '-" . $conceptcolumn->getID() . "').val();";
echo "				console.log('conceptID - '+ conceptID);";
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
echo "					var nextID = $('#'+next.id + '-" . $conceptcolumn->getID() . "').val();";
echo "					console.log('nextID - '+nextID);";
echo "					console.log('" . getUrl('worder/lessons/lessonworddragdrop') . "&lessonID=" . $registry->lesson->lessonID  . "&languageID=" . $this->registry->languageID . "&conceptID='+conceptID+'&previousID='+nextID);";
echo "					if (sortmutex == 0) {";
echo "						sortmutex = 1;";
echo "						window.location = '" . getUrl('worder/lessons/lessonworddragdrop') . "&lessonID=" . $registry->lesson->lessonID  . "&languageID=" . $this->registry->languageID . "&conceptID='+conceptID+'&previousID='+nextID;";
echo "					}";
echo "				} else {";
echo "					var prevID = $('#'+prev.id + '-" . $conceptcolumn->getID() . "').val();";
echo "					console.log('prevID - '+ prevID);";
echo "					console.log('" . getUrl('worder/lessons/lessonworddragdrop') . "&lessonID=" . $registry->lesson->lessonID . "&languageID=" . $this->registry->languageID . "&conceptID='+conceptID+'&previousID='+prevID);";
echo "					if (sortmutex == 0) {";
echo "						sortmutex = 1;";
echo "						window.location = '" . getUrl('worder/lessons/lessonworddragdrop') . "&lessonID=" . $registry->lesson->lessonID . "&languageID=" . $this->registry->languageID . "&conceptID='+conceptID+'&previousID='+prevID;";
echo "					}";
echo "				}";
echo "				console.dir(e);";
echo "				console.dir(ui);";
echo "			}";
echo "		});";
echo "		console.log('sortable');";

echo "	})";
echo "</script>";






// TODO: tämä pitäisi muuttaa search kentäksi... oikeastaan tämä pitäisi yhdistää kaksi nappulaa yhdeksi
//		 add existiing objective ja add new objective yhdeksi nappulaksi.

$insertsection = new UISection("Adding Existing Objective");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/lessons/addlessonobjective&lessonID=' . $registry->lesson->lessonID);

$field = new UISelectField("Objective","objectiveID","objectiveID", $registry->objectives, "name");
$field->setPredictable(true);
$insertsection->addField($field);

$insertsection->show();




$newobjectivesection = new UISection("Create New Objective");
$newobjectivesection->setDialog(true);
$newobjectivesection->setMode(UIComponent::MODE_INSERT);
$newobjectivesection->setSaveAction(UIComponent::ACTION_NEWWINDOW, 'worder/objectives/createobjective&lessonID=' . $registry->lesson->lessonID);

$field = new UITextField("Name", "name", 'name');
$newobjectivesection->addField($field);

$field = new UISelectField("Wordclass","wordclassID","wordclassID", $registry->wordclasses, "name");
$newobjectivesection->addField($field);

$newobjectivesection->show();






$objectvesentencesection = new UISection("Generating Sentences from Objective",$viewwidth);
$objectvesentencesection->setDialog(true);
$objectvesentencesection->setMode(UIComponent::MODE_INSERT);
$objectvesentencesection->setCustomContent('gemerateObjectiveSentencePreDiv');
$objectvesentencesection->show();


function gemerateObjectiveSentencePreDiv() {

	global $registry;

	echo "	<table style='width:100%'>";
	
	echo "		<tr>";
	echo "			<td style='padding-right:5px;'>";
	echo "<b>Objective:";
	echo " 			</td>";
	
	echo "			<td >";
	echo "<div id=objectivefieldpre style='width:100%;'>objectivename</div>";
	echo " 			</td>";
	
	echo "			<td style='padding-right:5px'>";
	echo "			</td>";
	echo "		</tr>";
	
	
	echo "		<tr>";
	echo "			<td style='padding-right:5px;'>";
	echo "				<div id=linkfieldpre style='width:100%;'></div>";
	//echo "				<input class=uitextfield  id=objectiveidfieldpre type='text' style='width:40px' type='text' value=''>";
	echo " 			</td>";

	echo "			<td >";
	//echo "				<div id=linkfieldpre style='width:100%;'></div>";
	echo " 			</td>";

	echo "			<td style='padding-right:5px'>";
	echo "				<div style='text-align:right;'>";
	echo "				<input id=lessonidfieldpre type=hidden style='width:200px;'>";
	echo "				<input id=languageidfieldpre type=hidden style='width:200px;'>";
	echo "				<input id=objectiveidfieldpre type=hidden style='width:200px;'>";
	
	//echo "					<button  class=section-button  onclick='generateobjectivebuttonpressed()'>Generate</button>";
	//echo "					<button  class=section-button  onclick='objectivesdialogclosebuttonpressedpre()'>Close</button>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";

	echo "		<tr>";
	echo "			<td colspan=3>";

	echo "				<div id=generateloadingdivpre style='display:none;height:100%;width:100%;'>";
	echo "Loading..";
	echo "				</div>";

	echo "				<div id=generateloadeddivpre style='display:none;height:100%;width:100%;overflow:hidden'>";
	echo "					<div style='overflow-y:scroll;max-height:200px;'>";
	echo "					<table id=generateresulttablepre style='width:100%;height:50px;'>";
	echo "						<tr><td>Empty</td></tr>";
	echo "					</table>";
	echo "					</div>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	
	echo "		<tr>";
	echo "			<td style='padding-right:5px;'>";
	echo " 			</td>";
	echo "			<td >";
	echo " 			</td>";
	echo "			<td style='padding-right:5px'>";
	echo "				<div style='text-align:right;'>";
	echo "					<button  class=section-button  onclick='objectivesentencesdialoguncheckbuttonpressed()'>UnCheck</button>";
	echo "					<button  class=section-button  onclick='objectivesentencesdialogcheckbuttonpressed()'>Check</button>";
	echo "					<button  class=section-button  onclick='objectivesentencesdialogclose()'>Close</button>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	
	
	echo "	</table>";

	
	echo "	<script>";
	echo "		function getObjectiveName(objectiveID) {";
	foreach($registry->lessonobjectives as $index => $objective) {
		echo "		if (objectiveID == '" . $objective->objectiveID . "') return '" . $objective->name . "';";
	}
	echo "			return 'unknown';";
	echo "		}";
	echo "	</script>";
	
	
	echo "	<script>";
	echo "		function opengenerateobjectivesdialog(languageID, objectiveID) {";
	//echo "			console.log('generate objective');";
	echo "			$('#generateloadingdivpre').show();";
	echo "			$('#generateloadeddivpre').hide();";
	echo "			console.log('" . getUrl('worder/lessons/generateobjectivesentence') . "&include=1&languageID='+languageID+'&lessonID=" . $registry->lesson->lessonID . "&objectiveID='+objectiveID);";
	echo "			var url = '" . getUrl('worder/lessons/generateobjectivesentence') . "&include=1&languageID='+languageID+'&comments=1&lessonID=" . $registry->lesson->lessonID . "&objectiveID='+objectiveID;";
	echo "			$('#linkfieldpre').html('<a target=\"_blank\" href=\"'+url+'\">link</a>');";
	
	echo "			var objectivename = getObjectiveName(objectiveID);";
	echo "			console.log('objectivename - '+objectivename+' - '+objectiveID);";
	echo "			$('#objectivefieldpre').html(objectivename);";
	
	echo "			$('#lessonidfieldpre').val(" . $registry->lesson->lessonID . ");";
	echo "			$('#languageidfieldpre').val(languageID);";
	echo "			$('#objectiveidfieldpre').val(objectiveID);";
	
	echo "			$.getJSON('" . getUrl('worder/lessons/generateobjectivesentence') . "&include=1&languageID='+languageID+'&lessonID=" . $registry->lesson->lessonID . "&objectiveID='+objectiveID,'',function(data) {";
	//echo "					console.log('data.length - '+data.length);";
	echo "					$('#generateresulttablepre').empty();";
	echo "					$('#generateloadingdivpre').hide();";
	echo "					$('#generateloadeddivpre').show();";
	echo "					var counter = 1;";
	echo "					$.each(data, function(index) {";
	echo "						console.log('row - '+data[index]);";
	
	echo "						var row = '<tr>'";
	//echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+counter+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index]+'</td>'";
	//echo "							+ '<td style=\"padding-right:10px;\">'+data[index].conceptID+'</td>'";
	//echo "							+ '<td title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/words/showword') . "&lang='+languageID+'&id='+data[index].wordID+'\">'+data[index].name+'</a></td>'";
	echo "							+ '<td><button onclick=\"addSentenceToLesson(\''+data[index]+'\')\">lisää</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#generateresulttablepre').append(row);";
	echo "						counter++;";
	echo "					});";
	echo "			}); ";
	echo "		}";
	echo "	</script>";
	
	
	echo "	<script>";
	echo "		function addSentenceToLesson(sentence, objectiveID) {";
	echo "			var objectiveID = $('#objectiveidfieldpre').val();";
	echo "			console.log('objectiveID pre - '+objectiveID);";
	echo "			window.location = '" . getUrl("worder/lessons/insertlessonsentence") . "&sentence='+sentence+'&objectiveID='+objectiveID+'&lessonID=" . $registry->lesson->lessonID . "';";
	echo "		}";
	echo "	</script>";
}


echo "<script>";
echo "		function objectivesentencesdialogcheckbuttonpressed() {";
echo "			var languageID = $('#languageidfieldpre').val();";
echo "			var objectiveID = $('#objectiveidfieldpre').val();";
echo "			url = '" . getUrl("worder/lessons/checklessonobjective") . "&lessonID=" . $registry->lesson->lessonID . "&languageID='+languageID+'&objectiveID='+objectiveID;";
echo "			console.log(url);";
echo "			window.location = '" . getUrl("worder/lessons/checklessonobjective") . "&lessonID=" . $registry->lesson->lessonID . "&languageID='+languageID+'&objectiveID='+objectiveID;";
echo "  		$('#sectiondialog-" . $objectvesentencesection->getID() . "').dialog('close');";
echo "		};";
echo "	</script>";



echo "<script>";
echo "		function objectivesentencesdialoguncheckbuttonpressed() {";
echo "			var objectiveID = $('#objectiveidfieldpre').val();";
echo "			var languageID = $('#languageidfieldpre').val();";
echo "			url = '" . getUrl("worder/lessons/unchecklessonobjective") . "&lessonID=" . $registry->lesson->lessonID . "&languageID='+languageID+'&objectiveID='+objectiveID;";
echo "			console.log(url);";
echo "			window.location = '" . getUrl("worder/lessons/unchecklessonobjective") . "&lessonID=" . $registry->lesson->lessonID . "&languageID='+languageID+'&objectiveID='+objectiveID;";
echo "  		$('#sectiondialog-" . $objectvesentencesection->getID() . "').dialog('close');";
echo "		};";
echo "	</script>";


echo "<script>";
echo "		function objectivesentencesdialogclose() {";
echo "  		$('#sectiondialog-" . $objectvesentencesection->getID() . "').dialog('close');";
echo "		};";
echo "	</script>";


foreach($this->registry->activelanguages as $index => $languageID) {
	$language = $this->registry->languages[$languageID];
	echo "	<script>";
	echo "		function " . $language->shortname . "generateclicked(value) {";
	echo "			console.log('" . $language->shortname . " - '+value);";
	echo "  		$('#sectiondialog-" . $objectvesentencesection->getID() . "').dialog('open');";
	echo "			opengenerateobjectivesdialog(" . $languageID . ", value);";
	echo "		};";
	echo "	</script>";
}



$table = new UITableSection("Objectives",$viewwidth);
$table->setOpen(true);
$table->editable(true);
$table->setFramesVisible(true);
$table->showTableHeader(false);
$table->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$table->setLineAction(UIComponent::ACTION_FORWARD, 'worder/objectives/showobjective&sourcelessonID=' . $registry->lesson->lessonID, 'objectiveID');
$table->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/lessons/removelessonobjective&lessonID=' . $registry->lesson->lessonID, 'objectiveID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), 'Add existing objective');
$table->addButton($button);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $newobjectivesection->getID(), 'Add new objective');
$table->addButton($button);

$column = new UISortColumn("#","objectiveID","objectiveID");
//$table->setColumnWidth(1,'100px');
$table->addColumn($column);

$column = new UISortColumn("Objective","name","name");
//$table->setColumnWidth(2,'640px');
$table->addColumn($column);

$rowcolumn = new UISortColumn("Args","argumentcount","argumentcount");
$table->addColumn($rowcolumn);


$column = new UISortColumn("UsedIn","parents","parents");
$table->addColumn($column);


$column = new UISelectColumn("Wordclass", "name", "wordclassID", $registry->wordclasses);
$table->addColumn($column);

//$column = new UISelectColumn("Stage", "name", "stage", $registry->stages);
//$table->addColumn($column);

//$column = UIBallColumn("x", "checked");
//$table->addColumn($column);

//$column = new UIColorColumn("State", "colorID", "colorID", $registry->colors);
//$table->addColumn($column);

//$column = new UIHiddenColumn("rowID", "rowID", "rowID");
//$table->addColumn($column);

$column = new UIHiddenColumn("argumentID", "argumentID", "argumentID");
$table->addColumn($column);

$rowcolumn = new UIHiddenColumn("RowID","rowID","rowID");
$table->addColumn($rowcolumn);



foreach($this->registry->activelanguages as $index => $languageID) {	
	$language = $this->registry->languages[$languageID];
	$column = new UIButtonColumn(UIComponent::ACTION_JAVASCRIPT, "objectiveID", $language->shortname . "generateclicked");
	$column->setColorVariable('color'.$languageID);
	$column->setWidth("50px");
	$column->setTitle($language->shortname);
	$table->addColumn($column);	
}

/*
$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "rowID", "worder/lessons/moveobjective&dir=up&lessonID=" . $registry->lesson->lessonID);
$column->setIcon("fa fa-chevron-up");
$table->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "rowID", "worder/lessons/moveobjective&dir=down&lessonID=" . $registry->lesson->lessonID);
$column->setIcon("fa fa-chevron-down");
$table->addColumn($column);
*/

$table->setData($registry->lessonobjectives);
$table->show();




echo "<script type=\"text/javascript\">";
echo "	var sortmutex = 0;";
echo "	$(document).ready(function() {";
echo "		console.log('document ready');";
echo "		$('#sectiontbody" . $table->getID() . "').sortable({";
echo "			start: function (e, ui) {";
echo "				console.log('start drag');";
echo "			},";
echo "			stop: function (e, ui) {";
echo "				if (sortmutex == 1) {";
echo "					console.log('end drag, but already processing');";
echo "					return;";
echo "				}";
echo "				console.log('end drag');";
echo "				console.log('elementid - '+ui.item[0].id);";
echo "				var elementName = '#'+ui.item[0].id + '-" . $rowcolumn->getID() . "';";
echo "				var conceptID = $('#'+ui.item[0].id + '-" . $rowcolumn->getID() . "').val();";
echo "				console.log('conceptID - '+ conceptID);";
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
echo "					var nextID = $('#'+next.id + '-" . $rowcolumn->getID() . "').val();";
echo "					console.log('nextID - '+nextID);";
echo "					console.log('" . getUrl('worder/lessons/lessonobjectivedragdrop') . "&lessonID=" . $registry->lesson->lessonID  . "&languageID=" . $this->registry->languageID . "&currentID='+conceptID+'&previousID='+nextID);";
echo "					if (sortmutex == 0) {";
echo "						sortmutex = 1;";
echo "						window.location = '" . getUrl('worder/lessons/lessonobjectivedragdrop') . "&lessonID=" . $registry->lesson->lessonID  . "&languageID=" . $this->registry->languageID . "&currentID='+conceptID+'&previousID='+nextID;";
echo "					}";
echo "				} else {";
echo "					var prevID = $('#'+prev.id + '-" . $rowcolumn->getID() . "').val();";
echo "					console.log('prevID - '+ prevID);";
echo "					console.log('" . getUrl('worder/lessons/lessonobjectivedragdrop') . "&lessonID=" . $registry->lesson->lessonID . "&languageID=" . $this->registry->languageID . "&currentID='+conceptID+'&previousID='+prevID);";
echo "					if (sortmutex == 0) {";
echo "						sortmutex = 1;";
echo "						window.location = '" . getUrl('worder/lessons/lessonobjectivedragdrop') . "&lessonID=" . $registry->lesson->lessonID . "&languageID=" . $this->registry->languageID . "&currentID='+conceptID+'&previousID='+prevID;";
echo "					}";
echo "				}";
echo "				console.dir(e);";
echo "				console.dir(ui);";
echo "			}";
echo "		});";
echo "		console.log('sortable');";

echo "	})";
echo "</script>";






function sentenceSearchDiv() {

	global $registry;

	echo "	<table style='width:100%'>";
	echo "		<tr>";

	echo "			<td style='padding-right:5px;'>";
	echo "				<input class=uitextfield  id=searchsentencefield type='text' style='width:100%;' type='text' value=''>";
	echo " 			</td>";

	echo "			<td style='padding-right:5px'>";
	echo "				<div>";
	echo "					<button  class=section-button  onclick='searchsentencebuttonpressed()'>Etsi</button>";
	echo "				</div>";
	echo "			</td>";

	echo "	<script>";
	echo "		$('#searchsentencefield').keypress(function (e) {";
	echo "			if (e.which == 13) {";
	echo "				searchsentencebuttonpressed();";
	echo "			};";
	echo "		})";
	echo "	</script>";

	echo "		</tr>";


	echo "		<tr>";
	echo "			<td colspan=2>";

	echo "				<div id=searchsentenceloadingdiv style='display:none;height:100%;width:100%;'>";
	echo "					<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
	echo "				</div>";

	echo "				<div id=searchsentenceloadeddiv style='display:none;height:100%;width:100%;overflow:hidden'>";
	echo "					<div style='overflow-y:scroll;max-height:200px;'>";
	//echo "				<div id=resultpanel style='width:100%;height:50px;background-color:pink;'>result</div>";
	echo "					<table id=searchsentenceresulttable style='width:100%;height:50px;'>";
	echo "						<tr><td>Empty</td></tr>";
	echo "					</table>";
	echo "					</div>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	echo "	</table>";


	echo "	<script>";
	echo "		function addSentence(sentenceID) {";
	echo "			window.location = '" . getUrl("worder/lessons/insertsentence") . "&sentenceID='+sentenceID+'&lessonID=" . $registry->lesson->lessonID . "';";
	echo "		}";
	echo "	</script>";


	echo "	<script>";
	echo "		function searchsentencebuttonpressed() {";
	echo "			console.log('search button pressed');";
	echo "			var searh = $('#searchsentencefield').val();";
	echo "			if (searh == '') {";
	echo "				alert('ei saa olla tyhjä 2');";
	echo "			}";
	echo "			$('#searchsentenceloadingdiv').show();";
	echo "			$('#searchsentenceloadeddiv').hide();";
	//echo "			var languageID = $('#languagefield').val();";
	echo "			var languageID = " . $registry->lesson->languageID . ";";
	echo "			console.log('languageid -'+languageID+'-');";
	echo "			console.log('" . getUrl('worder/sentences/searchsentences') . "&search='+searh+'&languageID='+languageID);";

	echo "			$.getJSON('" . getUrl('worder/sentences/searchsentences') . "&search='+searh+'&languageID='+languageID,'',function(data) {";
	echo "					$('#searchsentenceloadingdiv').hide();";
	echo "					$('#searchsentenceloadeddiv').show();";
	echo "					$('#searchsentenceresulttable tr').remove();";
	echo "					$.each(data, function(index) {";
	echo "						console.log('row - '+data[index].sentenceID+' - '+data[index].sentence);";
	echo "						var row = '<tr>'";
	//echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].sentenceID+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].sentence+'</td>'";
	//echo "							+ '<td title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/words/showword') . "&lang='+languageID+'&id='+data[index].wordID+'\">'+data[index].name+'</a></td>'";
	echo "							+ '<td><button onclick=\"addSentence(\''+data[index].sentenceID+'\')\">lisää</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#searchsentenceresulttable').append(row);";
	echo "					});";
	echo "			}); ";
	echo " 			console.log('finish');";
	echo "		}";
	echo "	</script>";

}




$addleveldialog = new UISection("Add Lesson Level");
$addleveldialog->setDialog(true);
$addleveldialog->setMode(UIComponent::MODE_INSERT);
$addleveldialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/lessons/insertlessonlevel&lessonID=' .  $registry->lesson->lessonID);

$levels = array();
$level = new Row();
$level->level = 1;
$level->name = "Level 1";
$levels[1] = $level;

$level = new Row();
$level->level = 2;
$level->name = "Level 2";
$levels[2] = $level;

$level = new Row();
$level->level = 3;
$level->name = "Level 3";
$levels[3] = $level;

$level = new Row();
$level->level = 4;
$level->name = "Level 4";
$levels[4] = $level;

$level = new Row();
$level->level = 5;
$level->name = "Level 5";
$levels[5] = $level;


$field = new UISelectField("Level","level","level",$levels, "name");
$addleveldialog->addField($field);

$field = new UITextField("Experience", "experience", 'experience');
$addleveldialog->addField($field);

$field = new UITextField("Stage-1 weight", "stage1weight", 'stage1weight');
$addleveldialog->addField($field);

$field = new UITextField("Stage-2 weight", "stage2weight", 'stage2weight');
$addleveldialog->addField($field);

$field = new UITextField("State-3 weight", "stage3weight", 'stage3weight');
$addleveldialog->addField($field);

$field = new UITextField("State-4 weight", "stage4weight", 'stage4weight');
$addleveldialog->addField($field);


$field = new UITextField("Stage-1 recap", "stage1recap", 'stage1recap');
$addleveldialog->addField($field);

$field = new UITextField("Stage-2 recap", "stage2recap", 'stage2recap');
$addleveldialog->addField($field);

$field = new UITextField("State-3 recap", "stage3recap", 'stage3recap');
$addleveldialog->addField($field);

$field = new UITextField("State-4 recap", "stage4recap", 'stage4recap');
$addleveldialog->addField($field);


$field = new UITextField("Stage-1 words", "stage1newcount", 'stage1newcount');
$addleveldialog->addField($field);

$field = new UITextField("Stage-2 words", "stage2newcount", 'stage2newcount');
$addleveldialog->addField($field);

$field = new UITextField("State-3 words", "stage3newcount", 'stage3newcount');
$addleveldialog->addField($field);

$field = new UITextField("State-4 words", "stage4newcount", 'stage4newcount');
$addleveldialog->addField($field);

$field = new UITextField("Paid", "paidcontent", 'paidcontent');
$addleveldialog->addField($field);

$addleveldialog->show();




$editleveldialog = new UISection("Edit Lesson Level");
$editleveldialog->setDialog(true);
$editleveldialog->setMode(UIComponent::MODE_INSERT);
//$editleveldialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/lessons/insertlessonlevel&lessonID=' .  $registry->lesson->lessonID);
$editleveldialog->setUpdateAction(UIComponent::ACTION_FORWARD, 'worder/lessons/updatelessonlevel&lessonID=' . $registry->lesson->lessonID, "rowID");

$field = new UISelectField("Level","level","level",$levels, "name");
$editleveldialog->addField($field);

$field = new UITextField("Experience", "experience", 'experience');
$editleveldialog->addField($field);

$field = new UITextField("Stage-1 weight", "stage1weight", 'stage1weight');
$editleveldialog->addField($field);

$field = new UITextField("Stage-2 weight", "stage2weight", 'stage2weight');
$editleveldialog->addField($field);

$field = new UITextField("State-3 weight", "stage3weight", 'stage3weight');
$editleveldialog->addField($field);

$field = new UITextField("State-4 weight", "stage4weight", 'stage4weight');
$editleveldialog->addField($field);


$field = new UITextField("Stage-1 recap", "stage1recap", 'stage1recap');
$editleveldialog->addField($field);

$field = new UITextField("Stage-2 recap", "stage2recap", 'stage2recap');
$editleveldialog->addField($field);

$field = new UITextField("State-3 recap", "stage3recap", 'stage3recap');
$editleveldialog->addField($field);

$field = new UITextField("State-4 recap", "stage4recap", 'stage4recap');
$editleveldialog->addField($field);


$field = new UITextField("Stage-1 words", "stage1newcount", 'stage1newcount');
$editleveldialog->addField($field);

$field = new UITextField("Stage-2 words", "stage2newcount", 'stage2newcount');
$editleveldialog->addField($field);

$field = new UITextField("State-3 words", "stage3newcount", 'stage3newcount');
$editleveldialog->addField($field);

$field = new UITextField("State-4 words", "stage4newcount", 'stage4newcount');
$editleveldialog->addField($field);

$field = new UITextField("Paid", "paidcontent", 'paidcontent');
$editleveldialog->addField($field);

$editleveldialog->show();



$section = new UITableSection("Levels",$viewwidth);
//$section->setOpen(true);
//$section->editable(true);
//$section->showLineNumbers(true);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editleveldialog->getID(), "rowID");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addleveldialog->getID(), 'Lisää');
$section->addButton($button);
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/lessons/removeprerequisitelesson&lessonID=' . $registry->lesson->lessonID, 'rowID');


$column = new UISortColumn("#", "rowID");
$section->addColumn($column);

$column = new UISortColumn("Lvl","level","level");
$section->addColumn($column);

$column = new UISortColumn("Exp", "experience");
$section->addColumn($column);

$column = new UISortColumn("W1", "stage1weight");
$section->addColumn($column);
$column = new UISortColumn("R1", "stage1recap");
$section->addColumn($column);
$column = new UISortColumn("N1", "stage1newcount");
$section->addColumn($column);

$column = new UISortColumn("W2", "stage2weight");
$section->addColumn($column);
$column = new UISortColumn("R2", "stage2recap");
$section->addColumn($column);
$column = new UISortColumn("N2", "stage2newcount");
$section->addColumn($column);

$column = new UISortColumn("W3", "stage3weight");
$section->addColumn($column);
$column = new UISortColumn("R3", "stage3recap");
$section->addColumn($column);
$column = new UISortColumn("N3", "stage3newcount");
$section->addColumn($column);

$column = new UISortColumn("W4", "stage4weight");
$section->addColumn($column);
$column = new UISortColumn("R4", "stage4recap");
$section->addColumn($column);
$column = new UISortColumn("N4", "stage4newcount");
$section->addColumn($column);

$column = new UISortColumn("Paid", "paidcontent");
$section->addColumn($column);

$section->setData($registry->levels);
$section->show();



// Tämä on toistaiseksi turha, esimerkkilauseet generoidaan objektiivien avulla
// Lisääminen lauseen kautta on hankalampi toteuttaa, kun mukaan pitäisi saada myös
// objectiveID linkitys. En tiedä onko tämä täysin tarpeellinen, jättää vain 
// objectivesentencechecks-taulun objectiveID nollaksi.

/*
$searchsection = new UISection("Etsi lause","500px");
$searchsection->setDialog(true);
$searchsection->setMode(UIComponent::MODE_INSERT);
//$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/lessons/insertlesson');

$searchsection->setCustomContent('sentenceSearchDiv');
$searchsection->show();
*/


// ---------------------------------------------------------------------------------------------------
// Lauseet
// ---------------------------------------------------------------------------------------------------

$sentencessection = new UITableSection("Sentences",$viewwidth);
$sentencessection->setOpen(true);
$sentencessection->editable(true);
$sentencessection->showLineNumbers(true);
$sentencessection->setFramesVisible(true);
$sentencessection->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$sentencessection->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/lessons/removesentence&lessonID=' . $registry->lesson->lessonID, 'sentenceID');

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $searchsection->getID(), 'Etsi lause');
//$sentencessection->addButton($button);

$column = new UISortColumn("ID", "sentenceID", "sentenceID");
$sentencessection->addColumn($column);

$column = new UISortColumn("LinkID", "linkID", "linkID");
$sentencessection->addColumn($column);

$column = new UISelectColumn("Language", "name", "languageID", $registry->languages);
$sentencessection->addColumn($column);

$column = new UISortColumn("#O", "objectiveID", "objectiveID");
$sentencessection->addColumn($column);

//$column = new UISelectColumn("Objective", "name", "objectiveID", $registry->objectives,"","200px");
//$section->addColumn($column);

$column = new UISortColumn("Lause", "sentence", "sentence");
$sentencessection->addColumn($column);


foreach($this->registry->activelanguages as $index => $languageID) {
	$language = $this->registry->languages[$languageID];
	$column = new UIButtonColumn(UIComponent::ACTION_JAVASCRIPT, "linkID", $language->shortname . "translateobjectivesentence");
	$column->setColorVariable('color'.$languageID);
	$column->setWidth("50px");
	$column->setTitle($language->shortname);
	$sentencessection->addColumn($column);
}


$sentencessection->setData($registry->sentences);
$sentencessection->show();



$translatesentencesection = new UISection("Lesson Sentence Translation",$viewwidth);
$translatesentencesection->setDialog(true);
$translatesentencesection->setMode(UIComponent::MODE_INSERT);
$translatesentencesection->setCustomContent('generateTranslationDialog');

$translatesentencesection->show();


function generateTranslationDialog() {

	global $registry;

	echo "	<table style='width:100%'>";
	echo "		<tr>";
	echo "			<td style='padding-right:5px;'></td>";
	echo "			<td>";
	echo "				<div id=linkfieldtranslation style='width:100%;'></div>";
	echo " 			</td>";
	echo "			<td style='padding-right:5px'>";
	echo "				<input id=linkidfieldtranslation type=hidden style='width:200px;'>";
	echo "				<input id=languageidfieldtranslation type=hidden style='width:200px;'>";
	echo "			</td>";
	echo "		</tr>";

	echo "		<tr>";
	echo "			<td style='padding-right:5px;'>Translate</td>";
	echo "			<td style='width:300px;'>";
	echo "				<span style='white-space:nowrap;'><span id=sourcelanguagefieldtranslation style='width:100%;'></span> - <span id=targetlanguagefieldtranslation style='width:100%;'></span>";
	echo " 			</td>";
	echo "			<td style='padding-right:5px'></td>";
	echo "		</tr>";

	echo "		<tr>";
	echo "			<td style='padding-right:5px;'>Sentence</td>";
	echo "			<td>";
	echo "				<div id=sentencefieldtranslation style='width:100%;'></div>";
	echo " 			</td>";
	echo "			<td style='padding-right:5px'>";
	echo "			</td>";
	echo "		</tr>";

	echo "		<tr>";
	echo "			<td colspan=3>";
	echo "				<div id=generateloadingdivtranslation style='display:none;height:100%;width:100%;'>";
	echo "Loading..";
	echo "				</div>";
	echo "				<div id=generateloadeddivtranslation style='display:none;height:100%;width:100%;overflow:hidden'>";
	echo "					<div style='overflow-y:scroll;max-height:200px;'>";
	echo "					<table id=generateresulttabletranslation style='width:100%;height:50px;'>";
	echo "						<tr><td>Empty</td></tr>";
	echo "					</table>";
	echo "					</div>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";

	echo "		<tr>";
	echo "			<td style='padding-right:5px;'>";
	echo " 			</td>";

	echo "			<td>";
	echo " 			</td>";

	echo "			<td style='padding-right:5px;padding-top:10px;'>";
	echo "				<div style='text-align:right;'>";
	echo "					<button  class=section-button  onclick='objectivesdialoguncheckbuttonpressedtranslation()'>UnCheck</button>";
	echo "					<button  class=section-button  onclick='objectivesdialogcheckbuttonpressedtranslation()'>Check</button>";
	echo "					<button  class=section-button  onclick='objectivesdialogclosebuttonpressedtranslation()'>Close</button>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	echo "	</table>";
}


echo "<script>";
echo "		function objectivesdialogcheckbuttonpressedtranslation() {";
echo "			var linkID = $('#linkidfieldtranslation').val();";
echo "			console.log('checkbutton - '+linkID);";
echo "			var languageID = $('#languageidfieldtranslation').val();";
echo "			url = '" . getUrl("worder/lessons/checklessonsentence") . "&linkID='+linkID+'&languageID='+languageID+'&lessonID=" . $registry->lesson->lessonID . "';";
echo "			console.log(url);";
echo "			window.location = '" . getUrl("worder/lessons/checklessonsentence") . "&linkID='+linkID+'&languageID='+languageID+'&lessonID=" . $registry->lesson->lessonID . "';";
echo "  		$('#sectiondialog-" . $translatesentencesection->getID() . "').dialog('close');";
echo "		};";
echo "	</script>";


echo "<script>";
echo "		function objectivesdialoguncheckbuttonpressedtranslation() {";
echo "			var linkID = $('#linkidfieldtranslation').val();";
echo "			console.log('uncheckbutton - '+linkID);";
echo "			var languageID = $('#languageidfieldtranslation').val();";
echo "			url = '" . getUrl("worder/lessons/unchecklessonsentence") . "&linkID='+linkID+'&languageID='+languageID+'&lessonID=" . $registry->lesson->lessonID . "';";
echo "			console.log(url);";
echo "			window.location = '" . getUrl("worder/lessons/unchecklessonsentence") . "&linkID='+linkID+'&languageID='+languageID+'&lessonID=" . $registry->lesson->lessonID . "';";
echo "  		$('#sectiondialog-" . $translatesentencesection->getID() . "').dialog('close');";
echo "		};";
echo "	</script>";


echo "<script>";
echo "		function objectivesdialogclosebuttonpressedtranslation() {";
echo "  		$('#sectiondialog-" . $translatesentencesection->getID() . "').dialog('close');";
echo "		};";
echo "	</script>";





echo "<script>";
echo "		function getsentencesourcelanguage(linkID) {";
echo "			console.log('search linkID - '+linkID);";
foreach($registry->sentences as $index => $sentence) {
	$language = $registry->languages[$sentence->languageID];
	echo "		console.log('...link - " . $sentence->languageID . " - " . $sentence->linkID . "');";
	echo "   	if(linkID == '" . $sentence->linkID . "') {";
	echo "			console.log('linkfound - " . $language->name . "');";
	echo "			return '" . $language->name . "';";
	echo "		}";
}
echo "		};";
echo "</script>";



foreach($this->registry->activelanguages as $index => $languageID) {
	$language = $this->registry->languages[$languageID];
	echo "<script>";
	echo "		function " . $language->shortname . "translateobjectivesentence(linkID) {";

	echo "			console.log('translatesentence - '+linkID);";
	echo "			console.log('targetlanguage - " . $languageID . "');";

	echo "			var loc = '" . getUrl("worder/sentences/translateobjectivesentenceJSON") . "&linkID='+linkID+'&targetlanguageID=" . $languageID . "';";
	echo "			console.log('loc - '+loc);";
	echo "			$('#linkidfieldtranslation').val(linkID);";
	echo "			$('#languageidfieldtranslation').val(" . $languageID . ");";

	echo "			$('#targetlanguagefieldtranslation').html('" . $language->name . "');";
	echo "			var sourcelanguage = getsentencesourcelanguage(linkID);";
	echo "			$('#sourcelanguagefieldtranslation').html(sourcelanguage);";

	echo "			console.log('generate objectives');";
	echo "			$('#generateloadingdivtranslation').show();";
	echo "			$('#generateloadeddivtranslation').hide();";
	echo "  		$('#sectiondialog-" . $translatesentencesection->getID() . "').dialog('open');";
	echo "			$('#sentencefieldtranslation').html('');";

	echo "			$.getJSON(loc,'',function(data) {";
	echo "				console.log('data.resultcount - '+data.resultcount);";
	echo "				$('#generateloadingdivtranslation').hide();";
	echo "				$('#generateloadeddivtranslation').show();";
	echo "				var str = '';";
	echo "				var first = 0;";
	echo "				$.each(data.results, function(index) {";
	echo "					if (first == 0) {";
	echo "						first = 1;";
	echo "					} else {";
	echo "					str = str + ',';";
	echo "					}";
	echo "					str = str + data.results[index].value;";
	echo "				});";
	echo "				console.log('str - '+str);";
	echo "				console.log('source - '+data.source);";
	echo "				$('#sentencefieldtranslation').html(data.source);";
	echo "					$('#generateresulttabletranslation').empty();";
	echo "					var counter = 1;";
	echo "					$.each(data.results, function(index) {";
	echo "						var row = '<tr>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+counter+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data.results[index].value+'</td>'";
	echo "							+ '</tr>';";
	echo "						$('#generateresulttabletranslation').append(row);";
	echo "						counter++;";
	echo "					});";
	echo "			}); ";

	echo "		};";
	echo "</script>";
}





// ---------------------------------------------------------------------------------------------------
// Lauseet
// ---------------------------------------------------------------------------------------------------


showTasksSection($registry, $viewwidth, "Lesson Tasks");

/*
$alltasks = array();
foreach($registry->system->minitasks as $index => $task) {
	$task->stateID = $task->state;
	$alltasks[$index] = $task;
}
foreach($registry->system->tasks as $index => $task) {
	$alltasks[$index] = $task;
}

$section = new UITableSection("Tasks",$viewwidth);
$section->setOpen(true);
$section->editable(true);
$section->showLineNumbers(true);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'tasks/tasks/showtask', 'taskID');


//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $searchsection->getID(), 'Etsi lause');
//$section->addButton($button);
//$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/lessons/removesentence&lessonID=' . $registry->lesson->lessonID, 'sentenceID');

$column = new UISortColumn("ID", "taskID", "taskID");
$section->addColumn($column);

$column = new UISortColumn("ID", "minitaskID", "minitaskID");
$section->addColumn($column);

$column = new UISortColumn("Name", "name", "name");
$section->addColumn($column);

$column = new UISortColumn("State", "stateID", "stateID");
$section->addColumn($column);
//$column = new UISelectColumn("State", "name", "stateID", $registry->taskstates);
//$section->addColumn($column);

$column = new UISortColumn("Checked", "endtime", "endtime");
$column->setFormatter(Column::COLUMNTYPE_DATETIME);
$section->addColumn($column);

$section->setData($alltasks);
$section->show();
*/

// ---------------------------------------------------------------------------------------------------
// Hallinta
// ---------------------------------------------------------------------------------------------------

$managementSection = new UISection("Management", $viewwidth);
$managementSection->editable(false);
$managementSection->setDebug(true);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/lessons/removelesson&lessonID=".$registry->lesson->lessonID, "Poista lesson");
$managementSection->addButton($button);

$managementSection->show();


