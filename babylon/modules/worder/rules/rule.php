<?php






function cmpFeatures($feature1, $feature2) {
	if ($feature1->name < $feature2->name) return -1;
	return 1;
}


$tempfeatures = array();
foreach($registry->features as $index => $feature) {
	if ($feature->parentID == 0) {
		$features[$feature->featureID] = $feature;
		$tempfeatures[$feature->featureID] = $feature;
	}
}

usort($tempfeatures, "cmpFeatures");

$orderedfeatures = array();
foreach($tempfeatures as $index => $feature) {
	$orderedfeatures[$feature->featureID] = $feature;
}




echo "<a href='".getUrl('worder/rules/showrules')."'>Takaisin</a><br>";
echo "<h1>" . $registry->rule->name . "</h1>";
echo "<canvas id='gamescreen' width=1000 height=250 style='border:1px solid black;margin-bottom:5px;'></canvas>";



echo "<script>";

echo "	let fAbbs = new Map();";
foreach( $registry->features as $index => $feature) {
	echo "		fAbbs.set(" . $feature->featureID . ",'" . $feature->abbreviation . "');";
}

echo "	let fNames = new Map();";
foreach( $registry->features as $index => $feature) {
	echo "		fNames.set(" . $feature->featureID . ",'" . $feature->name . "');";
}

echo "	let cAbbs = new Map();";
foreach( $registry->components as $index => $component) {
	echo "		cAbbs.set(" . $component->componentID . ",'" . $component->abbreviation . "');";
}

echo "	let aNames = new Map();";
foreach( $registry->arguments as $index => $argument) {
	echo "		aNames.set(" . $argument->argumentID . ",'" . $argument->name . "');";
}


echo "	let wAbbs = new Map();";
foreach( $registry->wordclasses as $index => $wordclass) {
	echo "		wAbbs.set(" . $wordclass->wordclassID . ",'" . $wordclass->abbreviation . "');";
}

echo "	let wNames = new Map();";
foreach( $registry->wordclasses as $index => $wordclass) {
	echo "		wNames.set(" . $wordclass->wordclassID . ",'" . $wordclass->name . "');";
}

//echo "		console.log(' fNames - '+fNames.get(59));";
//echo "		console.log(' fAbbs - '+fAbbs.get(59));";
//echo "		console.log(' cAbbs - '+cAbbs.get(110));";
//echo "		console.log(' aNames - '+aNames.get(18));";
echo "</script>";



echo "<script>";
echo "	$(document).ready(function() {";

echo "		let canvas = document.getElementById('gamescreen');";
echo "		let ctx = canvas.getContext('2d');";

echo "		ctx.fillStyle = '#000';";
echo "		var fontHeight = '20';";
echo "		ctx.font = \"20px Arial\";";

echo "		loadRule('" . getUrl('worder/rules/getruleJSON') . "', " . $registry->rule->ruleID . ",'gamescreen');";


echo "	});";
echo "</script>";





$termvalues = array();
foreach($registry->ruleterms as $index => $term) {
	$row = new Row();
	$row->rowID = $term->position;
	$row->position = $term->position;
	$termvalues[$row->position] = $row;
	
	//echo "<br>Termposition - " . $term->position;
}




$section = new UISection('Perustiedot','600px');
$section->setUpdateAction(UIComponent::ACTION_FORWARD, 'worder/rules/updaterule&type=1', 'ruleID');

$field = new UITextField("Name", "name", "Name");
$section->addField($field);

//$field = new UIFixedTextField("Wordclass", $registry->wordclass->name, "WordclassID", $registry->wordclass->wordclassID);
//$section->addField($field);
$field = new UISelectField("Wordclass", "wordclassID", "wordclassID", $this->registry->wordclasses, 'name');
$section->addField($field);

$field = new UISelectField("Language", "languageID", "languageID", $this->registry->languages, 'name');
$section->addField($field);

$field = new UISelectField("Parent", "parentID", "parentID", $this->registry->rules, 'name');
$field->setPredictable(true);
$section->addField($field);

$field = new UITextField("ConceptID", "conceptID", "conceptID");
$section->addField($field);

$field = new UISelectField("Status", "status", "Status", $this->registry->statuses);
$section->addField($field);

$field = new UITextAreaField("Description","description","Description");
$section->addField($field);

$field = new UIBooleanField("Generate","generate","generate");
$section->addField($field);

$field = new UIBooleanField("Analyse","analyse","analyse");
$section->addField($field);

//$section->setWidths("25%","70%","5%");
$section->setData($registry->rule);
$section->show();




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
	echo "			window.location = '" . getUrl("worder/rules/addexistingssentence") . "&sentenceID='+sentenceID+'&ruleID=" . $registry->rule->ruleID . "';";
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
	echo "			var languageID = " . $registry->rule->languageID . ";";
	echo "			console.log('languageid -'+languageID+'-');";
	echo "			console.log('" . getUrl('worder/sentences/searchsentences') . "&search='+searh+'&languageID='+languageID);";

	echo "			$.getJSON('" . getUrl('worder/sentences/searchsentences') . "&search='+searh+'&languageID='+languageID,'',function(data) {";
	echo "					console.log('data.length aa - '+data.length);";
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



$searchsection = new UISection("Etsi lause","500px");
$searchsection->setDialog(true);
$searchsection->setMode(UIComponent::MODE_INSERT);

$searchsection->setCustomContent('sentenceSearchDiv');
$searchsection->show();





$insertsentencesection = new UISection("Lauseen lisäys");
$insertsentencesection->setDialog(true);
$insertsentencesection->setMode(UIComponent::MODE_INSERT);
$insertsentencesection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/rules/insertsentence&ruleID=' . $registry->rule->ruleID);

$field = new UITextField("Lause", "sentence", 'sentence');
$insertsentencesection->addField($field);

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
$insertsentencesection->addField($field);

$insertsentencesection->show();



// ---------------------------------------------------------------------------------------------------
// Lauseet
// ---------------------------------------------------------------------------------------------------

$section = new UITableSection("Esimerkkilauseet","600px");
$section->setOpen(true);
$section->editable(true);
$section->showLineNumbers(true);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $searchsection->getID(), 'Etsi lause');
$section->addButton($button);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsentencesection->getID(), 'Lisää lause');
$section->addButton($button);

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/rules/removerulesentence&ruleID=' . $registry->rule->ruleID, 'sentenceID');

$column = new UISortColumn("ID", "sentenceID", "sentenceID");
$section->addColumn($column);

$column = new UISortColumn("Lause", "sentence", "sentence");
$section->addColumn($column);

$section->setData($registry->sentences);
$section->show();




// ---------------------------------------------------------------------------------------------------
// Lisää argumentti dialog
// ---------------------------------------------------------------------------------------------------





$argumentsallowedlist = array();

$allowed = new Row();
$allowed->rowID = 0;
$allowed->name = "No arguments";
$argumentsallowedlist[0] = $allowed;

$allowed = new Row();
$allowed->rowID = 1;
$allowed->name = "Permitted";
$argumentsallowedlist[1] = $allowed;



$addtermdialog = new UISection('Add new term','600px');
$addtermdialog->setDialog(true);
$addtermdialog->setMode(UIComponent::MODE_INSERT);
$addtermdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/rules/insertterm&ruleID=' . $registry->rule->ruleID);

//$field = new UITextField("Position", "position", 'position');
//$addtermdialog->addField($field);

$argumentfield	= new UISelectField("Position","position","position",$argumentsallowedlist, "rowID");
$addtermdialog->addField($argumentfield);

function cmpArguments($argument1, $argument2) {
	if ($argument1->name < $argument2->name) return -1;
	return 1;
}

$temparguments = array();
foreach($registry->arguments as $index => $argument) {
	$temparguments[$argument->argumentID] = $argument;
}
usort($temparguments, "cmpArguments");
$orderdarguments = array();
foreach($temparguments as $index => $argument) {
	$orderdarguments[$argument->argumentID] = $argument;
}

$argumentfield	= new UISelectField("Argument","argumentID","argumentID",$orderdarguments, "name");
$addtermdialog->addField($argumentfield);

$posfield	= new UISelectField("Part of speech","wordclassID","wordclassID",$registry->wordclasses, "name");
//$posfield->setOnChange("poschanged()");
$addtermdialog->addField($posfield);

$column = new UISelectField("Arguments","rowID","argumensallowed",$argumentsallowedlist,"name");
$addtermdialog->addField($column);

$addtermdialog->show();




$editdialog = new UISection('Edit Term','600px');
$editdialog->setDialog(true);
$editdialog->setMode(UIComponent::MODE_EDIT);
$editdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/rules/updateterm&ruleID=' . $registry->rule->ruleID, 'rowID');

$field = new UITextField("Position", "position", 'position');
$editdialog->addField($field);

$argumentfield	= new UISelectField("Argument","argumentID","argumentID",$registry->arguments, "name");
$editdialog->addField($argumentfield);

$posfield	= new UISelectField("Part of speech","wordclassID","wordclassID",$registry->wordclasses, "name");
$editdialog->addField($posfield);

$column = new UISelectField("Arguments","argumentsallowed","argumentsallowed",$argumentsallowedlist,"name");
$editdialog->addField($column);

$field = new UITextField("ConceptID", "conceptID", 'conceptID');
$editdialog->addField($field);


$editdialog->show();



// ---------------------------------------------------------------------------------------------------
// Terms
// ---------------------------------------------------------------------------------------------------

$section = new UITableSection("Terms","600px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setTableHeaderVisible(false);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addtermdialog->getID(), 'Add new term');
$section->addButton($button);

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/rules/removeterm&ruleID=' . $registry->rule->ruleID, 'rowID');
$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editdialog->getID(),"rowID");

$column = new UISortColumn("Position", "position", "worder/rules/showrules&sort=nimi");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

$column = new UISelectColumn("Argument","name","argumentID",$registry->arguments);
$section->addColumn($column);

$column = new UISelectColumn("Part of speech","name","wordclassID",$registry->wordclasses);
$section->addColumn($column);

$column = new UISelectColumn("Arguments","name","argumentsallowed",$argumentsallowedlist);
$section->addColumn($column);

$column = new UISortColumn("ConceptID","conceptID");
$section->addColumn($column);

$column = new UIHiddenColumn("RowID","rowID");
$section->addColumn($column);

$section->setData($registry->ruleterms);
$section->setTableHeaderVisible(true);
$section->show();





// ---------------------------------------------------------------------------------------------------
// Lisää agreement dialog
// ---------------------------------------------------------------------------------------------------


$addagreementdialog = new UISection('Add Feature Agreement','600px');
$addagreementdialog->setDialog(true);
$addagreementdialog->setMode(UIComponent::MODE_INSERT);
$addagreementdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/rules/insertfeatureagreement&ruleID=' . $registry->rule->ruleID);

$selectedfeatures = array();
foreach($registry->features as $index => $feature) {
	if (($registry->rule->languageID == $feature->languageID) && ($feature->parentID == 0)) {
		$selectedfeatures[$feature->featureID] = $feature;
	}
}

$field	= new UISelectField("Feature","featureID","featureID",$selectedfeatures, "name");
$addagreementdialog->addField($field);

$positionfield	= new UISelectField("Position1","position","position1",$termvalues, "position");
$addagreementdialog->addField($positionfield);

$positionfield	= new UISelectField("Position2","position","position2",$termvalues, "position");
$addagreementdialog->addField($positionfield);

$addagreementdialog->show();





$editdialog = new UISection('Edit Feature Agreement','600px');
$editdialog->setDialog(true);
$editdialog->setMode(UIComponent::MODE_EDIT);
$editdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/rules/updatefeatureagreement&ruleID=' . $registry->rule->ruleID, 'rowID');

$selectedfeatures = array();
foreach($registry->features as $index => $feature) {
	//echo "<br>RuleLanguage - " . $registry->rule->languageID . "... feature " . $feature->name . ", lang - " . $feature->languageID . "-" . $feature->parentID;
	if (($registry->rule->languageID == $feature->languageID) && ($feature->parentID == 0)) {
		//echo " -- select";
		$selectedfeatures[$feature->featureID] = $feature;
	}
}

$field	= new UISelectField("Feature","featureID","featureID",$selectedfeatures, "name");
$editdialog->addField($field);

$positionfield	= new UISelectField("Position1","position","position1",$termvalues, "position");
$editdialog->addField($positionfield);

$positionfield	= new UISelectField("Position2","position","position2",$termvalues, "position");
$editdialog->addField($positionfield);

$editdialog->show();




// Agreementit
$section = new UITableSection("Feature Agreements","600px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setTableHeaderVisible(false);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addagreementdialog->getID(), 'Add new agreement');
$section->addButton($button);
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/rules/removeagreement&ruleID=' . $registry->rule->ruleID, 'rowID');
$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editdialog->getID(),"rowID");

$column = new UISelectColumn("Feature","name","featureID",$registry->features);
$section->addColumn($column);

$column = new UISortColumn("Position1", "position1", "worder/rules/showrules&sort=nimi");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

$column = new UISortColumn("Position2", "position2", "worder/rules/showrules&sort=nimi");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

$column = new UIHiddenColumn("RowID","rowID");
$section->addColumn($column);

$section->setData($registry->featureagreements);
$section->setTableHeaderVisible(true);
$section->show();





// ---------------------------------------------------------------------------------------------------
// Feature constraints
// ---------------------------------------------------------------------------------------------------

//echo "<br>LanguageID = " . $registry->rule->languageID;

$addconstraintdialog = new UISection('Add Feature Constraint','600px');
$addconstraintdialog->setDialog(true);
$addconstraintdialog->setMode(UIComponent::MODE_INSERT);
$addconstraintdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/rules/insertfeatureconstraint&ruleID=' . $registry->rule->ruleID);

$positionfield	= new UISelectField("Position","position","position",$termvalues, "position");
$addconstraintdialog->addField($positionfield);


$parentfeatures = array();
foreach($orderedfeatures as $index => $feature) {
	//echo "<br>Feature - " . $feature->name . " -- " . $feature->languageID;
	if (($registry->rule->languageID == $feature->languageID) && ($feature->parentID == 0)) {
		$parentfeatures[$feature->featureID] = $feature;
	}
}
$featurefield	= new UISelectField("Feature","featureID","featureID",$parentfeatures, "name");
$featurefield->setOnChange("constraintfeaturechanged()");
$addconstraintdialog->addField($featurefield);

$selectedfeatures = array();
foreach($registry->features as $index => $feature) {
	if (($registry->rule->languageID == $feature->languageID) && ($feature->parentID != 0)) {
		$selectedfeatures[$feature->featureID] = $feature;
	}
}


$operatorfield	= new UISelectField("Operator",null,"operator",$registry->operators);
$addconstraintdialog->addField($operatorfield);


$valuefield	= new UISelectField("Value","featurevalueID","featurevalueID",$selectedfeatures, "name");
$valuefield->setDisabled(true);
$addconstraintdialog->addField($valuefield);

$addconstraintdialog->show();



echo "<script>";
echo "	function constraintfeaturechanged() {";

echo "		console.log('constraintfeaturechanged');";
echo "	 	var featurefield = '#" . $featurefield->getEditFieldID() ."';";
echo "	 	var featurevalue = $(featurefield).val();";
echo "	 	var valuefield = '#" . $valuefield->getEditFieldID() ."';";

echo "		switch(featurevalue) {";

foreach($parentfeatures as $featureID => $parentfeature) {
	echo "		case '" . $parentfeature->featureID . "':";
	echo "			$(valuefield).empty();";
	echo "			$(valuefield).addClass('uitextfield');";
	echo "			$(valuefield).removeAttr('disabled');";
	echo "			$(valuefield).removeClass('uitextfield-disabled');";
	foreach($registry->features as $index => $feature) {
		if ($feature->parentID == $parentfeature->featureID) {
			echo "			$(valuefield).append('<option value=" . $feature->featureID . ">" . $feature->name . "</option>');";
		}
	}
	echo "			break;";
}
echo "		}";
echo "	}";
echo "</script>";






$editdialog = new UISection('Edit Feature Constraint','600px');
$editdialog->setDialog(true);
$editdialog->setMode(UIComponent::MODE_EDIT);
$editdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/rules/updatefeatureconstraint&ruleID=' . $registry->rule->ruleID, 'rowID');

$positionfield = new UITextField("Position", "position", 'position');
$editdialog->addField($positionfield);

$parentfeatures = array();
foreach($orderedfeatures as $index => $feature) {
	if (($registry->rule->languageID == $feature->languageID) && ($feature->parentID == 0)) {
		$parentfeatures[$feature->featureID] = $feature;
	}
}
$editfeaturefield	= new UISelectField("Feature","featureID","featureID",$parentfeatures, "name");
$editfeaturefield->setOnChange("constraintfeatureeditchanged()");
$editdialog->addField($editfeaturefield);

$selectedfeatures = array();
foreach($registry->features as $index => $feature) {
	if (($registry->rule->languageID == $feature->languageID) && ($feature->parentID != 0)) {
		$selectedfeatures[$feature->featureID] = $feature;
	}
}


$editoperatorfield	= new UISelectField("Operator",null,"operator",$registry->operators);
$editdialog->addField($editoperatorfield);


$editvaluefield	= new UISelectField("Value","featurevalueID","featurevalueID",$selectedfeatures, "name");
$editdialog->addField($editvaluefield);

$editdialog->show();



echo "	<script>";
echo "		function setValue_" . $editdialog->getID(). "(fieldname, value) {";
echo "			console.log('setting value - ' + fieldname + '='+value);";
echo "			console.log('id - " . $editfeaturefield->getID() . " - " .  $editvaluefield->setValueJSFunction()  . "');";


echo "			if (fieldname == 'position') {";
echo "				console.log('- position - '+value);";
echo "				" . $positionfield->setValueJSFunction() . "(value);";
echo "			}";


echo "			if (fieldname == 'rowID') {";
echo "				console.log('- rowID - '+value);";
echo "				$('#sectionidvalue-".$editdialog->getID()."').val(value);";
echo "			}";

echo "			if (fieldname == 'featureID') {";
echo "				console.log('- featureID - '+value);";
echo "				" . $editfeaturefield->setValueJSFunction() . "(value);";
echo "			}";


echo "			if (fieldname == 'operator') {";
echo "				console.log('- operator - '+value);";
echo "				" . $editoperatorfield->setValueJSFunction() . "(value);";
echo "			}";


echo "			if (fieldname == 'featurevalueID') {";
echo "				console.log('- valueID - '+value);";
//echo "				" . $defaultfield->setValueJSFunction() . "(value);";
echo "				var featureID = $('#editfield-" . $editfeaturefield->getID() . "').val();";
echo "			 	var defaultfieldID = '#".$editvaluefield->getEditFieldID()."';";
echo "				$.getJSON('" . getUrl('worder/features/getfeaturesvalues') . "&featureID='+featureID,'',function(data) {";
echo "			 		$(defaultfieldID).empty();";
echo "					$(defaultfieldID).removeAttr('disabled');";
echo "					$(defaultfieldID).addClass('uitextfield');";
echo "					$(defaultfieldID).removeClass('uitextfield-disabled');";
echo "					$(defaultfieldID).append($('<option>', {value:0, text:''}));";
echo "					$.each(data, function(index) {";
echo "						if (data[index].featureID == value) {";
//echo "							console.log('selected xx - '+data[index].name);";
echo "							$(defaultfieldID).append($('<option >', {selected:'selected', value:data[index].featureID, text:data[index].name}));";
echo "						} else {";
echo "							$(defaultfieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "						}";
echo "					});";
echo "				}); ";
echo "			}";

echo "		}";
echo "	</script>";





echo "<script>";
echo "	function constraintfeatureeditchanged() {";

echo "		console.log('constraintfeatureeditchanged');";
echo "	 	var featurefield = '#" . $editfeaturefield->getEditFieldID() ."';";
echo "	 	var featurevalue = $(featurefield).val();";
echo "	 	var valuefield = '#" . $editvaluefield->getEditFieldID() ."';";

echo "		switch(featurevalue) {";

foreach($parentfeatures as $featureID => $parentfeature) {
	echo "		case '" . $parentfeature->featureID . "':";
	echo "			$(valuefield).empty();";
	echo "			$(valuefield).addClass('uitextfield');";
	echo "			$(valuefield).removeAttr('disabled');";
	echo "			$(valuefield).removeClass('uitextfield-disabled');";
	foreach($registry->features as $index => $feature) {
		if ($feature->parentID == $parentfeature->featureID) {
			echo "			$(valuefield).append('<option value=" . $feature->featureID . ">" . $feature->name . "</option>');";
		}
	}
	echo "			break;";
}
echo "		}";
echo "	}";
echo "</script>";


// Constraints

$section = new UITableSection("Feature Constraints","600px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setTableHeaderVisible(false);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addconstraintdialog->getID(), 'Add new constraint');
$section->addButton($button);
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/rules/removeconstraint&ruleID=' . $registry->rule->ruleID, 'rowID');
$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editdialog->getID(),"rowID");

$column = new UISortColumn("Position", "position", "worder/rules/showrules&sort=nimi");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

$column = new UISelectColumn("Feature","name","featureID",$registry->features);
$section->addColumn($column);

$column = new UISelectColumn("Operator",null,"operator",$registry->operators);
$section->addColumn($column);

$column = new UISelectColumn("Value","name","featurevalueID",$registry->features);
$section->addColumn($column);

$column = new UIHiddenColumn("RowID","rowID");
$section->addColumn($column);

$section->setData($registry->featureconstraints);
$section->setTableHeaderVisible(true);
$section->show();









// ---------------------------------------------------------------------------------------------------
// Lisää Component Requirements dialog
// ---------------------------------------------------------------------------------------------------


$adddialog = new UISection('Add component requirement','600px');
$adddialog->setDialog(true);
$adddialog->setMode(UIComponent::MODE_INSERT);
$adddialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/rules/insertcomponentrequirement&ruleID=' . $registry->rule->ruleID);

$positionfield	= new UISelectField("Position","position","position",$termvalues, "position");
$adddialog->addField($positionfield);

//$field = new UITextField("Position", "position", 'position');
//$adddialog->addField($field);


$field	= new UISelectField("Component","componentID","componentID",$registry->components, "name");
$adddialog->addField($field);


$presences = array();

$obligatory = new Row();
$obligatory->name = "Obligatory";
$obligatory->presence = 1;
$presences[1] = $obligatory;

$obligatory = new Row();
$obligatory->name = "Absent";
$obligatory->presence = 2;
$presences[2] = $obligatory;

$field	= new UISelectField("Presence","presence","presence",$presences, "name");
$adddialog->addField($field);

$adddialog->show();




$editdialog = new UISection('Edit Component Requirement','600px');
$editdialog->setDialog(true);
$editdialog->setMode(UIComponent::MODE_EDIT);
$editdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/rules/updatecomponentrequirement&ruleID=' . $registry->rule->ruleID, 'rowID');

$field = new UITextField("Position", "position", 'position');
$editdialog->addField($field);

$field	= new UISelectField("Component","componentID","componentID",$registry->components, "name");
$editdialog->addField($field);

$field	= new UISelectField("Presence","presence","presence",$presences, "name");
$editdialog->addField($field);

$editdialog->show();



// Constraints

$section = new UITableSection("Component Requirements","600px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setTableHeaderVisible(false);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $adddialog->getID(), 'Add new component');
$section->addButton($button);
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/rules/removecomponentrequirement&ruleID=' . $registry->rule->ruleID, 'rowID');
$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editdialog->getID(),"rowID");

$column = new UISortColumn("Position", "position", "worder/rules/showrules&sort=nimi");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

$column = new UISelectColumn("Component","name","componentID",$registry->components);
$section->addColumn($column);

$column = new UISelectColumn("Presence","name","presence",$presences);
$section->addColumn($column);

$column = new UIHiddenColumn("RowID","rowID");
$section->addColumn($column);

$section->setData($registry->componentrequirements);
$section->setTableHeaderVisible(true);
$section->show();






// ---------------------------------------------------------------------------------------------------
// Lisää Component Requirements dialog
// ---------------------------------------------------------------------------------------------------

$editdialog = new UISection('Edit Result Feature','600px');
$editdialog->setDialog(true);
$editdialog->setMode(UIComponent::MODE_EDIT);
$editdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/rules/updateresultfeature&ruleID=' . $registry->rule->ruleID, 'rowID');

$semanticfeatures = array();
foreach($registry->features as $index => $feature) {
	if ($feature->parentID == 0) {
		$semanticfeatures[$feature->featureID] = $feature;
	}
}

$editfeaturefield	= new UISelectField("Feature","featureID","featureID",$semanticfeatures, "name");
$editfeaturefield->setOnChange("editresultfeaturechanged()");
$editdialog->addField($editfeaturefield);

$selectedfeatures = array();
foreach($registry->features as $index => $feature) {
	if ($feature->parentID != 0) {
		$selectedfeatures[$feature->featureID] = $feature;
	}
}

$editvaluefield	= new UISelectField("Value","featureID","valueID",$selectedfeatures, "name");
$editdialog->addField($editvaluefield);

$editdialog->show();



echo "	<script>";
echo "		function setValue_" . $editdialog->getID(). "(fieldname, value) {";
echo "			console.log('setting value - ' + fieldname + '='+value);";
echo "			console.log('id - " . $editfeaturefield->getID() . " - " .  $editvaluefield->setValueJSFunction()  . "');";

echo "			if (fieldname == 'rowID') {";
echo "				console.log('- rowID - '+value);";
echo "				$('#sectionidvalue-".$editdialog->getID()."').val(value);";
//echo "				" . $editfeaturefield->setValueJSFunction() . "(value);";
echo "			}";

echo "			if (fieldname == 'featureID') {";
echo "				console.log('- featureID - '+value);";
echo "				" . $editfeaturefield->setValueJSFunction() . "(value);";
echo "			}";

echo "			if (fieldname == 'valueID') {";
echo "				console.log('- valueID - '+value);";
//echo "				" . $defaultfield->setValueJSFunction() . "(value);";
echo "				var featureID = $('#editfield-" . $editfeaturefield->getID() . "').val();";
echo "			 	var defaultfieldID = '#".$editvaluefield->getEditFieldID()."';";
echo "				$.getJSON('" . getUrl('worder/features/getfeaturesvalues') . "&featureID='+featureID,'',function(data) {";
echo "			 		$(defaultfieldID).empty();";
echo "					$(defaultfieldID).removeAttr('disabled');";
echo "					$(defaultfieldID).addClass('uitextfield');";
echo "					$(defaultfieldID).removeClass('uitextfield-disabled');";
echo "					$(defaultfieldID).append($('<option>', {value:0, text:''}));";
echo "					$.each(data, function(index) {";
echo "						if (data[index].featureID == value) {";
//echo "							console.log('selected xx - '+data[index].name);";
echo "							$(defaultfieldID).append($('<option >', {selected:'selected', value:data[index].featureID, text:data[index].name}));";
echo "						} else {";
echo "							$(defaultfieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "						}";
echo "					});";
echo "				}); ";
echo "			}";

echo "		}";
echo "	</script>";





// ---------------------------------------------------------------------------------------------------
// Lisää Component Requirements dialog
// ---------------------------------------------------------------------------------------------------


$adddialog = new UISection('Add Result Feature','600px');
$adddialog->setDialog(true);
$adddialog->setMode(UIComponent::MODE_INSERT);
$adddialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/rules/insertresultfeature&ruleID=' . $registry->rule->ruleID);

$features = array();

$resultfeatures = array();
foreach($orderedfeatures as $index => $feature) {
	if ($feature->parentID == 0) {
		$features[$feature->featureID] = $feature;
		$resultfeatures[$feature->featureID] = $feature;
	}
}



$sources = array();
$source = new Row();
$source->rowID = 1;
$source->name = "Static";
$sources[1] = $source;
$source = new Row();
$source->rowID = 2;
$source->name = "from terms";
$sources[2] = $source;

$featurefield = new UISelectField("Target feature","featureID","featureID",$resultfeatures, "name");
$featurefield->setOnChange("resultfeaturechanged()");
$adddialog->addField($featurefield);

$sourcefield = new UISelectField("Source","sourceID","sourceID",$sources, "name");
$sourcefield->setOnChange("sourcefieldchanged()");
$sourcefield->setDisabled(true);
$adddialog->addField($sourcefield);


$positionfield = new UISelectField("Source position","position","position",$termvalues, "position");
$positionfield->setOnChange("positionfieldchanged()");
$positionfield->setHideFromView(true);
$positionfield->setDisabled(true);
$adddialog->addField($positionfield);


$selectedfeatures = array();
foreach($registry->semanticfeatures as $index => $feature) {
	if ($feature->parentID != 0) {
		$selectedfeatures[$feature->featureID] = $feature;
	}
}

$valuefield	= new UISelectField("Value","featureID","valueID",$selectedfeatures, "name");
$valuefield->setDisabled(true);
$valuefield->setHideFromView(true);
$valuefield->setOnChange("valuefieldchanged()");
$adddialog->addField($valuefield);

$adddialog->show();


echo "<script>";
echo "	function sourcefieldchanged() {";

echo "	 	var sourcefield = '#" . $sourcefield->getEditFieldID() ."';";
echo "	 	var sourcevalue = $(sourcefield).val();";

echo "		if (sourcevalue == 0) {";
echo "	 		var positionline = '#selectline-".$positionfield->getId()."';";
echo "	 		var valueline = '#selectline-".$valuefield->getId()."';";
echo "			$(positionline).hide();";
echo "			$(valueline).hide();";
echo "		}";

echo "		if (sourcevalue == 1) {";
echo "	 		var positionline = '#selectline-".$positionfield->getId()."';";
echo "	 		var valueline = '#selectline-".$valuefield->getId()."';";
echo "			$(positionline).hide();";
echo "			$(valueline).show();";

echo "	 		var positionfield = '#" . $positionfield->getEditFieldID() ."';";
echo "			$(positionfield).val('');";

echo "	 		var featurefield = '#" . $featurefield->getEditFieldID() ."';";
echo "	 		var featurevalue = $(featurefield).val();";

echo "	 		var valuefield = '#" . $valuefield->getEditFieldID() ."';";
echo "			$(valuefield).empty();";

echo "			switch(featurevalue) {";

foreach($features as $featureID => $parentfeature) {
	echo "			case '" . $parentfeature->featureID . "':";
	echo "				console.log('featureselected x3 - " . $parentfeature->featureID . "');";
	echo "				$(valuefield).addClass('uitextfield');";
	echo "				$(valuefield).removeAttr('disabled');";
	echo "				$(valuefield).removeClass('uitextfield-disabled');";
	echo "				$(valuefield).append('<option value=\'\'></option>');";
	foreach($registry->features as $index => $feature) {
		if ($feature->parentID == $parentfeature->featureID) {
			echo "		$(valuefield).append('<option value=" . $feature->featureID . ">" . $feature->name . "</option>');";
		}
	}
	foreach($registry->semanticfeatures as $index => $feature) {
		if ($feature->parentID == $parentfeature->featureID) {
			echo "		$(valuefield).append('<option value=" . $feature->featureID . ">" . $feature->name . "</option>');";
		}
	}
	echo "			break;";
}
echo "			}";
echo "		}";

echo "		if (sourcevalue == 2) {";
echo "	 		var positionline = '#selectline-".$positionfield->getId()."';";
echo "	 		var valueline = '#selectline-".$valuefield->getId()."';";
echo "			$(positionline).show();";
echo "			$(valueline).show();";

echo "	 		var positionfield = '#" . $positionfield->getEditFieldID() ."';";
echo "			$(positionfield).val('');";
echo "			$(positionfield).addClass('uitextfield');";
echo "			$(positionfield).removeAttr('disabled');";
echo "			$(positionfield).removeClass('uitextfield-disabled');";

echo "	 		var valuefield = '#" . $valuefield->getEditFieldID() ."';";
echo "			$(valuefield).empty();";
echo "			$(valuefield).addClass('uitextfield-disabled');";
echo "			$(valuefield).attr('disabled','disabled');";
echo "			$(valuefield).removeClass('uitextfield');";
echo "		}";
echo "	}";
echo "</script>";

echo "<script>";
echo "	function resultfeaturechanged() {";

echo "		console.log('resultfeaturechanged');";
echo "	 	var featurefield = '#" . $featurefield->getEditFieldID() ."';";
echo "	 	var featurevalue = $(featurefield).val();";

echo "	 	var sourcefield = '#" . $sourcefield->getEditFieldID() ."';";
echo "	 	$(sourcefield).val(0);";

echo "		if (featurevalue > 0) {";
echo "			$(sourcefield).addClass('uitextfield');";
echo "			$(sourcefield).removeAttr('disabled');";
echo "			$(sourcefield).removeClass('uitextfield-disabled');";

echo "		} else {"; 
echo "			$(sourcefield).addClass('uitextfield-disabled');";
echo "			$(sourcefield).attr('disabled','disabled');";
echo "			$(sourcefield).removeClass('uitextfield');";
echo "		}";

echo "	 	var positionline = '#selectline-".$positionfield->getId()."';";
echo "	 	var valueline = '#selectline-".$valuefield->getId()."';";
echo "		$(positionline).hide();";
echo "		$(valueline).hide();";
echo "	}";
echo "</script>";




echo "<script>";
echo "	function positionfieldchanged() {";

echo "	 	var valuefield = '#" . $valuefield->getEditFieldID() ."';";
echo "	 	var positionfield = '#" . $positionfield->getEditFieldID() ."';";
echo "	 	var positionvalue = $(positionfield).val();";
echo "	 	var positiontext = $('#" . $positionfield->getEditFieldID() ." option:selected').text();";
echo "		console.log('positionfieldchanged - positiontext '+positiontext);";
echo "		if (positionvalue == '') {";
echo "			console.log('positionfield empty ');";
echo "			$(valuefield).addClass('uitextfield');";
echo "			$(valuefield).removeAttr('disabled');";
echo "			$(valuefield).removeClass('uitextfield-disabled');";
echo "		} else {";
echo "			console.log('positionfield setted - '+positionvalue);";
echo "			$(valuefield).addClass('uitextfield');";
echo "			$(valuefield).removeAttr('disabled');";
echo "			$(valuefield).removeClass('uitextfield-disabled');";
echo "			$(valuefield).empty();";
echo "	 		var featurefield = '#" . $featurefield->getEditFieldID() ."';";
echo "	 		var featurevalue = $(featurefield).val();";
foreach($registry->ruleterms as $index => $term) {
	echo "		if (positiontext == " . $term->position . ") {";
	echo "			$(valuefield).empty();";
	echo "			console.log('term wordclass - " . $term->wordclassID . "');";
	foreach($registry->wordclassfeatures as $index => $wordclassfeature) {
		if ($wordclassfeature->wordclassID == $term->wordclassID) {
			$feature = $registry->features[$wordclassfeature->featureID];
			echo "			$(valuefield).append('<option value=" . $feature->featureID . ">" . $feature->name . "</option>');";
		}
	}
	echo "		}";
}
echo "		}";
echo "	}";
echo "</script>";



echo "<script>";
echo "	function valuefieldchanged() {";
echo "	 	var valuefield = '#" . $valuefield->getEditFieldID() ."';";
echo "	 	var positionfield = '#" . $positionfield->getEditFieldID() ."';";
echo "	 	var value = $(valuefield).val();";
echo "		console.log('valuefieldchanged');";
echo "		if (value != '') {";
//echo 	"		console.log('valuefield empty - '+value);";
//echo "			$(positionfield).addClass('uitextfield-disabled');";
//echo "			$(positionfield).attr('disabled','disabled');";
//echo "			$(positionfield).removeClass('uitextfield');";
echo "		} else {";
//echo 	"		console.log('valuefield setted - '+value);";
//echo "			$(positionfield).addClass('uitextfield');";
//echo "			$(positionfield).removeAttr('disabled');";
//echo "			$(positionfield).removeClass('uitextfield-disabled');";
echo "		}";
echo "	}";
echo "</script>";


echo "<script>";
echo "	function editresultfeaturechanged() {";

echo "		console.log('resultfeaturechanged');";
echo "	 	var featurefield = '#" . $editfeaturefield->getEditFieldID() ."';";
echo "	 	var featurevalue = $(featurefield).val();";
echo "	 	var valuefield = '#" . $editvaluefield->getEditFieldID() ."';";

echo "		switch(featurevalue) {";

foreach($semanticfeatures as $featureID => $parentfeature) {
	echo "		case '" . $parentfeature->featureID . "':";
	echo "			$(valuefield).empty();";
	echo "			console.log('featureselected x6 - " . $parentfeature->featureID . "');";
	echo "			$(valuefield).addClass('uitextfield');";
	echo "			$(valuefield).removeAttr('disabled');";
	echo "			$(valuefield).removeClass('uitextfield-disabled');";
	foreach($registry->features as $index => $feature) {
		if ($feature->parentID == $parentfeature->featureID) {
			echo "			$(valuefield).append('<option value=" . $feature->featureID . ">" . $feature->name . "</option>');";
		}
	}
	echo "			break;";
}
echo "		}";
echo "	}";
echo "</script>";


// Result features

$section = new UITableSection("Result Features","600px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setTableHeaderVisible(false);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);
$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editdialog->getID(),"rowID");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $adddialog->getID(), 'Add New Result feature');
$section->addButton($button);
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/rules/removeresultfeature&ruleID=' . $registry->rule->ruleID, 'rowID');

$allfeatures = array();
foreach($registry->features as $index => $feature) {
	$allfeatures[$feature->featureID] = $feature;
}
foreach($registry->semanticfeatures as $index => $feature) {
	$allfeatures[$feature->featureID] = $feature;
}


$column = new UISelectColumn("Feature","name","featureID",$allfeatures);
$section->addColumn($column);

$column = new UISelectColumn("Value","name","valueID",$allfeatures);
$section->addColumn($column);

$column = new UISortColumn("Position", "position", "worder/rules/showrules&sort=nimi");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

$column = new UIHiddenColumn("RowID","rowID");
$section->addColumn($column);

//$column = new UIHiddenColumn("FeatureID","featureID");
//$section->addColumn($column);

//$column = new UIHiddenColumn("ValueID","valueID");
//$section->addColumn($column);

$section->setData($registry->resultfeatures);
$section->setTableHeaderVisible(true);
$section->show();



// Result features

$section = new UITableSection("Sentencesets","600px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setTableHeaderVisible(false);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);
$section->setLineAction(UIComponent::ACTION_FORWARD, "worder/sentences/showsentenceset","setID");

$column = new UISortColumn("SetID", "setID");
$section->addColumn($column);

$column = new UISortColumn("Name", "name");
$section->addColumn($column);

$section->setData($registry->sentencesets);
$section->show();




// ---------------------------------------------------------------------------------------------------
// Hallinta
// ---------------------------------------------------------------------------------------------------

$managementSection = new UISection("Hallinta");
$managementSection->editable(false);
$managementSection->setDebug(true);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/rules/showreferences&id=".$registry->rule->getID(), "Näytä viitteet");
$managementSection->addButton($button);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/rules/copyrule&id=".$registry->rule->getID(), "Kopioi sääntö");
$managementSection->addButton($button);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/rules/removerule&id=".$registry->rule->getID(), "Poista sääntö");
$managementSection->addButton($button);

$managementSection->show();




?>
