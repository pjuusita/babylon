<?php



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

echo "		console.log(' fNames - '+fNames.get(59));";
echo "		console.log(' fAbbs - '+fAbbs.get(59));";
echo "		console.log(' cAbbs - '+cAbbs.get(110));";
echo "		console.log(' aNames - '+aNames.get(18));";
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




$section = new UISection('Resultrule','600px');
$section->setUpdateAction(UIComponent::ACTION_FORWARD, 'worder/rules/updaterule&type=2', 'ruleID');

$field = new UITextField("Name", "name", "Name");
$section->addField($field);

$field = new UISelectField("Wordclass", "wordclassID", "wordclassID", $this->registry->wordclasses, 'name');
$section->addField($field);

$field = new UISelectField("Language", "languageID", "languageID", $this->registry->languages, 'name');
$section->addField($field);

$field = new UISelectField("Status", "status", "Status", $this->registry->statuses);
$section->addField($field);

$field = new UITextAreaField("Description","description","Description");
$section->addField($field);

$section->setData($registry->rule);
$section->show();



// ---------------------------------------------------------------------------------------------------
// Edit Result Feature
// ---------------------------------------------------------------------------------------------------

$editdialog = new UISection('Edit Result Feature xxx','600px');
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



$adddialog = new UISection('Add Result Feature','600px');
$adddialog->setDialog(true);
$adddialog->setMode(UIComponent::MODE_INSERT);
$adddialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/rules/insertresultfeature&type=2&ruleID=' . $registry->rule->ruleID);

$features = array();
foreach($registry->semanticfeatures as $index => $feature) {
	if ($feature->parentID == 0) {
		$ftemp = $feature->getCopy();
		$ftemp->name = "Shared " . $feature->name;
		$features[$feature->featureID] = $ftemp;
	}
}
foreach($registry->features as $index => $feature) {
	if ($feature->parentID == 0) {
		$features[$feature->featureID] = $feature;
	}
}


$featurefield = new UISelectField("Feature","featureID","featureID",$features, "name");
$featurefield->setOnChange("featurechanged()");
$adddialog->addField($featurefield);

$valuefield	= new UISelectField("Value","featureID","valueID",$selectedfeatures, "name");
$valuefield->setDisabled(true);
$adddialog->addField($valuefield);

$adddialog->show();


echo "<script>";
echo "	function featurechanged() {";

echo "	 		var featurefield = '#" . $featurefield->getEditFieldID() ."';";
echo "	 		var featurevalue = $(featurefield).val();";

echo "	 		var valuefield = '#" . $valuefield->getEditFieldID() ."';";
echo "			$(valuefield).empty();";

echo "			switch(featurevalue) {";

foreach($features as $featureID => $parentfeature) {
	echo "			case '" . $parentfeature->featureID . "':";
	echo "				console.log('featureselected x1 - " . $parentfeature->featureID . "');";
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
	echo "			console.log('featureselected x2 - " . $parentfeature->featureID . "');";
	echo "			$(valuefield).addClass('uitextfield');";
	echo "			$(valuefield).removeAttr('disabled');";
	echo "			$(valuefield).removeClass('uitextfield-disabled');";
	foreach($selectedfeatures as $index => $feature) {
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

$column = new UIHiddenColumn("FeatureID","featureID");
$section->addColumn($column);

$column = new UIHiddenColumn("ValueID","valueID");
$section->addColumn($column);

$section->setData($registry->resultfeatures);
$section->setTableHeaderVisible(true);
$section->show();



// ---------------------------------------------------------------------------------------------------
// Hallinta
// ---------------------------------------------------------------------------------------------------

$managementSection = new UISection("Hallinta");
$managementSection->editable(false);
$managementSection->setDebug(true);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/rules/copyrule&id=".$registry->rule->getID(), "Kopioi sääntö");
$managementSection->addButton($button);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/rules/removerule&id=".$registry->rule->getID(), "Poista sääntö");
$managementSection->addButton($button);

$managementSection->show();



?>