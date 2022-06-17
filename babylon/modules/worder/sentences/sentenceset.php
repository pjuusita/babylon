<?php


echo "<h1>SentenceSet: " . $this->registry->set->name . "</h1>";


$sentencesetsection = new UISection("Set","800px");
$sentencesetsection->setOpen(true);
$sentencesetsection->editable(true);
$sentencesetsection->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/sentences/updatesentenceset', 'setID');

$field = new UIFixedTextField("SetID", $registry->set->setID);
$sentencesetsection->addField($field);

$field = new UISelectField("Language","languageID","languageID",$registry->languages, "name");
$sentencesetsection->addField($field);

$field = new UITextField("Name","name","name");
$sentencesetsection->addField($field);

$field = new UISelectField("Parent", "parentID", "parentID", $this->registry->sentencesets, 'name');
$field->setPredictable(true);
$sentencesetsection->addField($field);

$field = new UITextAreaField("Kuvaus", "description", 'description');
$sentencesetsection->addField($field);

$sentencesetsection->setData($registry->set);
$sentencesetsection->show();






$addworddialog = new UISection('Add word','500px');
$addworddialog->setDialog(true);
$addworddialog->setMode(UIComponent::MODE_INSERT);

$addworddialog->setInsertAction(UIComponent::ACTION_FORWARD, "worder/sentences/insertsentence&lang=" . $this->registry->languageID . "&setID=" . $this->registry->setID);

$field = new UITextField("Sanaluokka", "wordclass", 'wordclass');
$addworddialog->addField($field);

$field = new UITextField("Perusmuoto", "lemma", 'lemma');
$addworddialog->addField($field);

$addworddialog->show();




function conceptSearchDiv() {

	global $registry;

	echo "	<table style='width:100%'>";
	echo "					<tr>";
	echo "						<td style='padding-right:5px;'>";
	echo "<input class=uitextfield  id=conceptsearchfield type='text' style='width:100%;' type='text' value=''>";
	echo " 						</td>";

	echo "					<td style='padding-right:5px'>";
	echo "						<div>";
	echo "							<button  class=section-button  onclick='conceptsearchbuttonpressed()'>Etsi</button>";
	echo "						</div>";
	echo "					</td>";

	echo "	<script>";
	echo "		$('#conceptsearchfield').keypress(function (e) {";
	echo "			if (e.which == 13) {";
	echo "				conceptsearchbuttonpressed();";
	echo "			};";
	echo "		})";
	echo "	</script>";

	echo "		</tr>";
	echo "		<tr>";
	echo "			<td colspan=2>";

	echo "				<div id=conceptsearchloadingdiv style='display:none;height:100%;width:100%;'>";
	echo "					<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
	echo "				</div>";

	echo "				<div id=conceptsearchloadeddiv style='display:none;height:100%;width:570px;overflow:hidden'>";
	echo "					<div style='overflow-y:scroll;max-height:200px;width:570px;'>";
	//echo "				<div id=resultpanel style='width:100%;height:50px;background-color:pink;'>result</div>";
	echo "					<table id=conceptsearchresulttable style='width:550px;height:50px;table-layout:fixed;'>";
	echo "						<tr><td>Empty</td></tr>";
	echo "					</table>";
	echo "					</div>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	echo "	</table>";

	echo "	<script>";
	echo "		function addItem(conceptID) {";
	echo "			alert('addconcept button pressed');";
//	echo "			window.location = '" . getUrl("worder/groups/insertconcept") . "&groupid=" . $registry->group->wordgroupID . "&conceptID='+conceptID;";
	echo "		}";
	echo "	</script>";



	echo "	<script>";
	echo "		function conceptsearchbuttonpressed() {";
	//echo "			console.log('search button pressed');";
	echo "			var searh = $('#conceptsearchfield').val();";
	echo "			if (searh == '') {";
	echo "				alert('ei saa olla tyhjä');";
	echo "			}";
	echo "			$('#conceptsearchloadingdiv').show();";
	echo "			$('#conceptsearchloadeddiv').hide();";

	echo "			$.getJSON('" . getUrl('worder/groups/searchwords') . "&search='+searh,'',function(data) {";
	//echo "					console.log('data.length - '+data.length);";
	echo "					$('#conceptsearchloadingdiv').hide();";
	echo "					$('#conceptsearchloadeddiv').show();";
	echo "					$('#conceptsearchresulttable tr').remove();";
	echo "					$.each(data, function(index) {";
	//echo "						console.log('row - '+data[index].conceptID+' - '+data[index].name);";
	echo "						var row = '<tr>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordclassID+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].conceptID+'</td>'";
	echo "							+ '<td style=\"width:300px;overflow:hidden;\" title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/concepts/showconcept') . "&id='+data[index].conceptID+'\">'+data[index].name+'</a></td>'";
	echo "							+ '<td><button onclick=\"addItem(\''+data[index].conceptID+'\')\">lisää</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#conceptsearchresulttable').append(row);";
	echo "					});";
	echo "			}); ";
	//echo " 			console.log('finish');";
	echo "		}";
	echo "	</script>";

}


function wordSearchDiv() {

	global $registry;

	echo "	<table style='width:100%'>";
	echo "					<tr>";
	echo "						<td style='padding-right:5px;'>";
	echo "							<input class=uitextfield  id=wordsearchfield type='text' style='width:100%;' type='text' value=''>";
	echo " 						</td>";

	echo "					<td style='padding-right:5px'>";
	echo "						<div>";
	echo "							<button  class=section-button  onclick='wordsearchbuttonpressed()'>Etsi</button>";
	echo "						</div>";
	echo "					</td>";

	echo "	<script>";
	echo "		$('#wordsearchfield').keypress(function (e) {";
	echo "			if (e.which == 13) {";
	echo "				wordsearchbuttonpressed();";
	echo "			};";
	echo "		})";
	echo "	</script>";

	echo "		</tr>";
	echo "		<tr>";
	echo "			<td colspan=2>";

	echo "				<div id=wordsearchloadingdiv style='display:none;height:100%;width:100%;'>";
	echo "					<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
	echo "				</div>";

	echo "				<div id=wordsearchloadeddiv style='display:none;height:100%;width:570px;overflow:hidden'>";
	echo "					<div style='overflow-y:scroll;max-height:200px;width:570px;'>";
	echo "					<table id=wordsearchresulttable style='width:550px;height:50px;table-layout:fixed;'>";
	echo "						<tr><td>Empty</td></tr>";
	echo "					</table>";
	echo "					</div>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	echo "	</table>";

	echo "	<script>";
	echo "		function addItem(conceptID) {";
	echo "			alert('addconcept button pressed');";
	echo "		}";
	echo "	</script>";



	echo "	<script>";
	echo "		function wordsearchbuttonpressed() {";
	echo "			var searh = $('#wordsearchfield').val();";
	echo "			if (searh == '') {";
	echo "				alert('ei saa olla tyhjä');";
	echo "			}";
	echo "			$('#wordsearchloadingdiv').show();";
	echo "			$('#wordsearchloadeddiv').hide();";
	echo "			console.log('" . getUrl('worder/words/searchwordsJSON') . "&lang=" . $registry->languageID . "&search='+searh);";
	
	echo "			$.getJSON('" . getUrl('worder/words/searchwordsJSON') . "&lang=" . $registry->languageID . "&search='+searh,'',function(data) {";
	echo "					$('#wordsearchloadingdiv').hide();";
	echo "					$('#wordsearchloadeddiv').show();";
	echo "					$('#wordsearchresulttable tr').remove();";
	echo "					$.each(data, function(index) {";
	echo "						var row = '<tr>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordclassID+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordID+'</td>'";
	echo "							+ '<td style=\"width:300px;overflow:hidden;\" title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/concepts/showconcept') . "&id='+data[index].wordID+'\">'+data[index].name+'</a></td>'";
	echo "							+ '<td><button onclick=\"addItem(\''+data[index].wordID+'\')\">lisää</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#wordsearchresulttable').append(row);";
	echo "					});";
	echo "			}); ";
	echo "		}";
	echo "	</script>";

}


$searchconceptdialog = new UISection("Etsi käsite","600px");
$searchconceptdialog->setDialog(true);

$searchconceptdialog->setCustomContent('conceptSearchDiv');
$searchconceptdialog->show();



$searchworddialog = new UISection("Etsi sana","600px");
$searchworddialog->setDialog(true);

$searchworddialog->setCustomContent('wordSearchDiv');
$searchworddialog->show();




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


$insertsection = new UISection('Add sentence','500px');
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
//$insertsection->setInsertAction(UIComponent::ACTION_FORWARD, "worder/sentences/insertsentence&lang="+$registry->languageID);

$insertsection->setInsertAction(UIComponent::ACTION_FORWARD, "worder/sentences/insertsentence&source=set&languageID=" . $this->registry->set->languageID . "&setID=" . $this->registry->set->setID);

$field = new UITextField("Lause", "sentence", 'sentence');
$insertsection->addField($field);

$correctselection = array();
$row = new Row();
$row->correctness = 0;
$row->name = "malformed";
$correctselection[0] = $row;
$row = new Row();
$row->correctness = 1;
$row->name = "well-formed";
$correctselection[1] = $row;

$field = new UISelectField("Correctness","correctness","correctness",$correctselection, "name");
$insertsection->addField($field);

$row = new Row();
$row->correctness = 1;

$insertsection->setData($row);
$insertsection->show();




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
	
	
	echo "		<tr>";
	echo "			<td style='padding-right:5px'>";
	echo "				<div>";
	echo "					<button  class=section-button  onclick='addsearchedsentencefailbuttonpressed()'>Lisää väärä</button>";
	echo "					<button  class=section-button  onclick='addsearchedsentencecorrectbuttonpressed()'>Lisää correct</button>";
	echo "				</div>";
	echo "			</td>";
	
	echo "		</tr>";
	echo "	</table>";


	echo "	<script>";
	echo "		function addSentenceSetSentence(sentenceID) {";
	echo "			window.location = '" . getUrl("worder/sentences/insertsentencesetsentence") . "&sentenceID='+sentenceID+'&setID=" . $registry->set->setID . "';";
	echo "		}";
	echo "	</script>";


	echo "	<script>";
	echo "		function addsearchedsentencefailbuttonpressed(sentenceID) {";
	echo "			console.log('search button pressed');";
	echo "			var search = $('#searchsentencefield').val();";
	echo "			if (search == '') {";
	echo "				alert('ei saa olla tyhjä 2');";
	echo "				return;";
	echo "			}";
	echo "			window.location = '" . getUrl("worder/sentences/insertsentence") . "&source=set&correctness=0&sentence='+search+'&languageID=" . $registry->set->languageID . "&setID=" . $registry->set->setID . "';";
	echo "		}";
	echo "	</script>";

	
	echo "	<script>";
	echo "		function addsearchedsentencecorrectbuttonpressed(sentenceID) {";
	echo "			console.log('search button pressed');";
	echo "			var search = $('#searchsentencefield').val();";
	echo "			if (search == '') {";
	echo "				alert('ei saa olla tyhjä 2');";
	echo "				return;";
	echo "			}";
	echo "			window.location = '" . getUrl("worder/sentences/insertsentence") . "&source=set&correctness=1&sentence='+search+'&languageID=" . $registry->set->languageID . "&setID=" . $registry->set->setID . "';";
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
	echo "			var languageID = " . $registry->set->languageID . ";";
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
	echo "							+ '<td><button onclick=\"addSentenceSetSentence(\''+data[index].sentenceID+'\')\">lisää</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#searchsentenceresulttable').append(row);";
	echo "					});";
	echo "			}); ";
	echo " 			console.log('finish');";
	echo "		}";
	echo "	</script>";

}



$searchsection = new UISection("Etsi lause","500px");
$searchsection->setDialog(true);
$searchsection->setMode(UIComponent::MODE_INSERT);
//$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/lessons/insertlesson');

$searchsection->setCustomContent('sentenceSearchDiv');
$searchsection->show();


$table = new UITableSection("Lauseet", "800px");
$table->setFramesVisible(true);
$table->setSettingsAction($languagessection);
$table->setLineAction(UIComponent::ACTION_FORWARD,"worder/sentences/showsentence","sentenceID");
$table->showLineNumbers(true);

$table->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/sentences/removesentencefromset&setID=' . $registry->set->setID, 'sentenceID');

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $copydialog->getID(), 'Copy set');
//$table->addButton($button);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $searchsection->getID(), 'Add sentence');
$table->addButton($button);

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), 'Add sentence');
//$table->addButton($button);

$column = new UISortColumn("ID", "sentenceID");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$table->addColumn($column);

$column = new UISortColumn("Sentence", "sentence");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$table->addColumn($column);

$column = new UISelectColumn("Correctness", "name", "correctness", $correctselection);
$table->addColumn($column);


/*
foreach($this->registry->activelanguages as $index => $languageID) {
	$language = $this->registry->languages[$languageID];

	$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "sentenceID", "worder/sentences/translatesentenceJSON&targetlanguageID=" . $language->languageID);
	$column->setTitle($language->shortname);
	$table->addColumn($column);
}
*/

/*
 $column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "sentenceID", "worder/sentences/translatesentenceJSON");
 $column->setTitle("Translate");
 $table->addColumn($column);
 */


$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, 'sentenceID', "worder/sentences/movesentence&dir=up&setID=" . $registry->set->setID, "30px");			
$column->setIcon("fa fa-chevron-up");
$table->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, 'sentenceID', "worder/sentences/movesentence&dir=down&setID=" . $registry->set->setID, "30px");	
$column->setIcon("fa fa-chevron-down");
$table->addColumn($column);


$table->setData($this->registry->sentences);
$table->show();




if ($this->registry->set->rulesetID == 0) {
	
	$title = "Rules";
	if (DEV == true) if (isset($registry->ruleset->setID)) $title = "Rules";
	$rulesection = new UISection($title,"800px");
	$rulesection->editable(false);
	$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/sentences/addrulesetforsentenceset&noframes=1&setID=". $this->registry->set->setID, "Lisää ruleset");
	$rulesection->addButton($button);
	$rulesection->show();
	
} else {

	$title = "Rules";
	if (DEV == true) $title = "Rules (" . $registry->ruleset->setID . ")";
	$rulesdialog = new UISection($title,'800px');
	$rulesdialog->setMode(UIComponent::MODE_INSERT);
	$rulesdialog->setCustomContent('rulesDiv');
	$rulesdialog->show();

	
	
	echo "	<script>";
	echo "		function checkboxclicked(checkbox, ruleID) {";
	echo "			if (checkbox.checked == true) {";
	echo "				console.log('checked - '+ruleID);";
	//echo "			var url = '" .  getUrl("admin/usergroups/updatedimensionvisibility") . "&usergroupID=" . $usergroupID . "&dimensionID='+dimensionID+'&dimensionvalueID='+dimensionvalueID+'&accesslevel=1';";
	echo "				$.getJSON('" . getUrl('worder/rules/checkruleJSON') . "&setID=" .  $this->registry->ruleset->setID . "&ruleID='+ruleID,'',function(data) {";
	echo "					console.log('return - '+data);";
	echo "				});";
	//echo "			console.log('ulr - '+url);";
	//echo "				window.location = url;";
	echo "			} else {";
	echo "				console.log('unchecked - '+ruleID);";
	echo "				$.getJSON('" . getUrl('worder/rules/uncheckruleJSON') . "&setID=" .  $this->registry->ruleset->setID . "&ruleID='+ruleID,'',function(data) {";
	echo "					console.log('return - '+data);";
	echo "				});";
	//echo "			var url = '" .  getUrl("admin/usergroups/updatedimensionvisibility") . "&usergroupID=" . $usergroupID . "&dimensionID='+dimensionID+'&dimensionvalueID='+dimensionvalueID+'&accesslevel=0';";
	//echo "			console.log('ulr - '+url);";
	//echo "			window.location = url;";
	echo "			}";
	echo "		}";
	echo "	</script>";
	

}

function rulesDiv() {

	global $registry;
	$maxlevel = 5;

	echo "	<table style='width:100%'>";

	echo "		<tr>";
	for($counter = 0;$counter < $maxlevel; $counter++) {
		echo "			<td style='width:30px;'>";
		echo "			</td>";
	}
	echo "			<td>";
	echo "			</td>";
	echo "		</tr>";

	foreach($registry->languages as $index => $language) {
	
		echo "		<tr>";
		echo "			<td colspan=" . $maxlevel . " style='padding-right:5px;'>";
		echo "<b>" . $language->name . "</b>";
		echo "			</td>";
		echo "		</tr>";
	
		foreach($registry->rules as $index => $rule) {
				
			if ($rule->languageID == $language->languageID) {
	
				if ($rule->parentID == 0) {
					echo "		<tr>";
					echo "			<td style='padding-right:5px;'>";
					echo "			</td>";
					echo "			<td style='padding-right:5px;'>";
					echo "<input type=checkbox>";
					echo "			</td>";
					echo "			<td colspan=" . ($maxlevel-1) . " style='padding-right:5px;'>";
					echo $rule->ruleID . " - " . $rule->name;
					echo "			</td>";
					echo "		</tr>";
	
					subrules(2, $maxlevel, $rule, $registry->rules);
				}
			}
		}
	}
	echo "	</table>";
}


function subrules($level, $maxlevel, $parent, $rules) {

	foreach($rules as $index => $rule) {

		if ($rule == null) {
			echo "<br>Rule null";
		}

		if ($rule->parentID == null) {
			echo "<br>ParentID null - " . $rule->ruleID;
		}

		if ($rule->parentID == $parent->ruleID) {
			echo "		<tr>";
			for($counter = 0;$counter < $level; $counter++) {
				echo "			<td style='padding-right:5px;'>";
				echo "			</td>";
			}
			echo "			<td style='padding-right:5px;'>";
			if ($rule->selected == 1) {
				echo "<input type=checkbox checked onchange='checkboxclicked(this, " . $rule->ruleID . ")'>";
			} else {
				echo "<input type=checkbox onchange='checkboxclicked(this, " . $rule->ruleID . ")'>";
			}
			echo "			</td>";
			echo "			<td colspan=" . ($maxlevel-$level) . " style='padding-right:5px;'>";
			if ($rule->status == 0) {
				echo $rule->ruleID . " - " . $rule->name . " (disabled)";
			} else {
				echo $rule->ruleID . " - " . $rule->name;
			}
			echo "			</td>";
			echo "		</tr>";
				
			if ($level < $maxlevel) {
				subrules($level + 1, $maxlevel, $rule, $rules);
			}
		}
	}
}


$copydialog = new UISection('Copy Set to Another Grammar','500px');
$copydialog->setDialog(true);
$copydialog->setMode(UIComponent::MODE_INSERT);

$copydialog->setInsertAction(UIComponent::ACTION_FORWARD, "worder/sentences/copyset&setID=" . $this->registry->set->setID);

$grammarfield = new UISelectField("Grammar","grammarID","grammarID",$registry->grammars, "name");
$grammarfield->setOnChange("grammarchanged()");
$copydialog->addField($grammarfield);

$languagefield = new UISelectField("Language","languageID","languageID",$registry->grammars, "name");
//$languagefield->setDisabled(true);
$copydialog->addField($languagefield);

$copydialog->show();




echo "<script>";
echo "	function grammarchanged() {";

echo "		console.log('grammarchanged');";

echo "	 	var grammarfieldID 		 	 = '#".$grammarfield->getEditFieldID()."';";
echo "	 	var languagefieldID 		 	 = '#".$languagefield->getEditFieldID()."';";
echo "	 	var grammarID = $(grammarfieldID).val();";

echo "		if (grammarfieldID == 0) {";
echo "	 		$(languagefieldID).empty();";
echo "			$(languagefieldID).attr('disabled', 'disabled');";
echo "			$(languagefieldID).addClass('uitextfield-disabled');";
echo "			$(languagefieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";

echo "		console.log('" . getUrl('worder/grammars/getgrammarlanguagesJSON') . "&grammarID='+grammarID);";

echo "		$.getJSON('" . getUrl('worder/grammars/getgrammarlanguagesJSON') . "&grammarID='+grammarID,'',function(data) {";

echo "			console.log('getgrammarlanguagesJSON');";

echo "	 		$(languagefieldID).empty();";
echo "			$(languagefieldID).removeAttr('disabled');";
echo "			$(languagefieldID).addClass('uitextfield');";
echo "			$(languagefieldID).removeClass('uitextfield-disabled');";
echo "			$(languagefieldID).append($('<option>', {value:0, text:''}));";

echo "			$.each(data, function(index) {";
echo "				$(languagefieldID).append($('<option>', {value:data[index].languageID, text:data[index].name}));";
echo "			});";
echo "		}); ";

echo "	}";
echo "</script>";




$managementSection = new UISection("Hallinta","800px");
$managementSection->editable(false);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $copydialog->getID(), 'Copy set');
$managementSection->addButton($button);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/sentences/removesentenceset&setID=". $this->registry->set->setID, "Poista setti");
$managementSection->addButton($button);

$managementSection->show();



?>