<?php

$viewwidth = "700px";

if ($registry->sourcelessonID > 0) {
	echo "<a href='".getUrl('worder/lessons/showlesson')."&id=" . $registry->sourcelessonID . "'>Palaa lessoniin</a><br>";
} else {
	echo "<a href='".getUrl('worder/objectives/showobjectives')."'>Palaa objectives-listaan</a><br>";
}




//echo "<br>stagecount - " . count($registry->stages);


$section = new UISection("Objective", $viewwidth);
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/objectives/updateobjective', 'objectiveID');

$field = new UITextField("Name", "name", "name");
$section->addField($field);

$field = new UISelectField("Wordclass","wordclassID","wordclassID",$registry->wordclasses, 'name');
$section->addField($field);



$field = new UISelectField("State","stateID","stateID",$registry->states, 'name');
$section->addField($field);


$field = new UISelectField("Stage","stage","stage",$registry->stages, 'name');
$section->addField($field);


//$field = new UISelectField("Parent","parentID","parentID",$registry->objectives, 'name');
//$field->setPredictable(true);
//$section->addField($field);

//$field = new UIFixedTextField("Lesson", parseMultilangString($this->registry->lesson->name,1) , "lessonID", $this->registry->lesson->lessonID);
//$section->addField($field);

$field = new UIFixedTextField("Language", $this->registry->language->name, "languageID", $this->registry->language->languageID);
$section->addField($field);

$field = new UITextField("Mincount", "mincount", "mincount");
$section->addField($field);

$field = new UITextAreaField("Kuvaus","description","description");
$section->addField($field);

$section->setData($registry->objective);
$section->show();




$addparentdialog = new UISection('Adding Parent','600px');
$addparentdialog->setDialog(true);
$addparentdialog->setMode(UIComponent::MODE_INSERT);
$addparentdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/objectives/insertparentobjective&objectiveID=' . $this->registry->objective->objectiveID);

$field = new UISelectField("Objective", "objectiveID", 'parentID', $registry->objectives, 'name');
$field->setPredictable(true);
$addparentdialog->addField($field);

$addparentdialog->show();



$section = new UITableSection("Objectives Hierarchy",$viewwidth);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);
$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/objectives/showobjective','parentID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addparentdialog->getID(), 'Add parent');
$section->addButton($button);
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/objectives/removeparentobjective&objectiveID=' . $this->registry->objective->objectiveID, 'parentID');

$column = new UISortColumn("#","linkID");
$section->addColumn($column);

$column = new UISortColumn("#","parentID");
$section->addColumn($column);

$column = new UISelectColumn("Objective", "name", "parentID", $registry->objectives);
$section->addColumn($column);

$section->setData($registry->parents);
$section->show();



// ---------------------------------------------------------------------------------------------------
// Prerequisite objectives
// ---------------------------------------------------------------------------------------------------


$addprerequisitedialog = new UISection('Adding Prerequisite','600px');
$addprerequisitedialog->setDialog(true);
$addprerequisitedialog->setMode(UIComponent::MODE_INSERT);
$addprerequisitedialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/objectives/insertobjectiveprerequisite&objectiveID=' . $this->registry->objective->objectiveID);

$field = new UISelectField("Objective", "objectiveID", 'prerequisiteID', $registry->objectives, 'name');
$field->setPredictable(true);
$addprerequisitedialog->addField($field);

$addprerequisitedialog->show();



$section = new UITableSection("Prerequisite Objectives",$viewwidth);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);
$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/objectives/showobjective','objectiveID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addprerequisitedialog->getID(), 'Add prerequisite');
$section->addButton($button);
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/objectives/removeobjectiveprerequisite&objectiveID=' . $this->registry->objective->objectiveID, 'objectiveID');

$column = new UISortColumn("#","objectiveID");
$section->addColumn($column);

$column = new UISelectColumn("Objective", "name", "objectiveID", $registry->objectives);
$section->addColumn($column);

$column = new UISelectColumn("Argument", "name", "argumentID", $registry->arguments);
$section->addColumn($column);

//$column = new UISortColumn("#","argumentID");
//$section->addColumn($column);

$section->setData($registry->prerequisites);
$section->show();




$addextensiondialog = new UISection('Adding Extension Objective','600px');
$addextensiondialog->setDialog(true);
$addextensiondialog->setMode(UIComponent::MODE_INSERT);
$addextensiondialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/objectives/insertobjectiveextension&objectiveID=' . $this->registry->objective->objectiveID);

$field = new UISelectField("Objective", "objectiveID", 'prerequisiteID', $registry->objectives, 'name');
$field->setPredictable(true);
$addextensiondialog->addField($field);

$addextensiondialog->show();



// TODO: tämä extension objectives on tarkoitettu siihen, että saadaan laajennettua requirementteja ja
//  	 inflectionset requirementteja. Tätä on tarkoitus käyttää erityisesti suomen kielen sanoissa, joissa
//		 jotkin taivutusmuodot sisältävät astevaihtelua, jota ei haluta esittää alkuvaiheessa muiden tuttujen
//		 sanojen yhteydessä vaan astevaihtelua opetellaan erillisellä objektivilla. Generoinnin säätö tähän
//		 on ehkä hieman hankala toteuttaa, eikä ole toistaiseksi vielä toteutettu. Hieman on myös epäselvää
//		 miten tämä koko extension hoidetaan, ei voida astevaihtelusta tehdä erikseen kaikille muille
//		 objektiiveille omia extensioitaan astevaihtelua varten, riittäisi jos se tehdään kertaalleen jossain.
//
$section = new UITableSection("Extension Objectives",$viewwidth);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);
$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/objectives/showobjective','objectiveID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addextensiondialog->getID(), 'Add Extension');
$section->addButton($button);
//$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/objectives/removeobjectiveprerequisite&objectiveID=' . $this->registry->objective->objectiveID, 'objectiveID');

$column = new UISortColumn("#","objectiveID");
$section->addColumn($column);

$column = new UISelectColumn("Objective", "name", "objectiveID", $registry->objectives);
$section->addColumn($column);

$section->setData(array());
$section->show();



// ---------------------------------------------------------------------------------------------------
// add Component 
// ---------------------------------------------------------------------------------------------------

$orcomponenentdialog = new UISection('Adding OR-Component','600px');
$orcomponenentdialog->setDialog(true);
$orcomponenentdialog->setMode(UIComponent::MODE_INSERT);
$orcomponenentdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/objectives/insertorcomponent&objectiveID=' . $this->registry->objective->objectiveID);

$field = new UISelectField("Component", "componentID", 'componentID', $registry->components, 'name');
$field->setPredictable(true);
$orcomponenentdialog->addField($field);

$orcomponenentdialog->show();


$andcomponenentdialog = new UISection('Adding AND-Component','600px');
$andcomponenentdialog->setDialog(true);
$andcomponenentdialog->setMode(UIComponent::MODE_INSERT);
$andcomponenentdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/objectives/insertandcomponent&objectiveID=' . $this->registry->objective->objectiveID);


$field = new UISelectField("Parent", "rowID", 'parentID', $registry->lessoncomponents, 'name');
$field->setPredictable(true);
$andcomponenentdialog->addField($field);

$field = new UISelectField("Component", "componentID", 'componentID', $registry->components, 'name');
$field->setPredictable(true);
$andcomponenentdialog->addField($field);

$andcomponenentdialog->show();



// ---------------------------------------------------------------------------------------------------
// Lesson component requirements 
// ---------------------------------------------------------------------------------------------------


$section = new UITableSection("Component requirements",$viewwidth);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/components/showcomponent','componentID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $andcomponenentdialog->getID(), 'Lisää AND-component');
$section->addButton($button);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $orcomponenentdialog->getID(), 'Lisää OR-component');
$section->addButton($button);

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/objectives/removecomponentrequirement&objectiveID=' . $this->registry->objective->objectiveID, 'rowID');

$column = new UISortColumn("#","rowID");
$section->addColumn($column);

$column = new UISortColumn("Components", "name");
$section->addColumn($column);

$column = new UIHiddenColumn("ComponentID","componentID");
$section->addColumn($column);

$section->setData($registry->componentrequirements);
$section->show();





// ---------------------------------------------------------------------------------------------------
// Feature Requirements
// ---------------------------------------------------------------------------------------------------

$addfeaturerequirementdialog = new UISection('Add Objective Feature Requirement','600px');
$addfeaturerequirementdialog->setDialog(true);
$addfeaturerequirementdialog->setMode(UIComponent::MODE_INSERT);
$addfeaturerequirementdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/objectives/insertobjectivefeaturerequirement&objectiveID=' . $this->registry->objective->objectiveID);


$wordclassfield = new UIFixedTextField("Wordclass",$registry->wordclasses[$registry->objective->wordclassID]->name);
//$wordclassfield = new UISelectField("Wordclass", "wordclasID" , "wordclassID", $registry->wordclasses, "name");
//$wordclassfield->setDisabled(true);
$addfeaturerequirementdialog->addField($wordclassfield);

$languagefield = new UISelectField("Language", "languageID", 'languageID', $registry->languages, 'name');
$languagefield->setOnChange("featurerequirementdialoglanguagechanged()");
$addfeaturerequirementdialog->addField($languagefield);

/*
$features = array();
echo "<br>needed languageID:" . $registry->objective->languageID . "wordclassID:" . $registry->objective->wordclassID;
foreach($registry->features as $index => $feature) {
	echo "<br> -- languageID:" . $feature->languageID . "wordclassID:" . $feature->wordclassID . ", parentID:" . $feature->parentID;
	if (($feature->languageID == $registry->objective->languageID) && ($feature->wordclassID == $registry->objective->wordclassID)) {
		echo "<br> -- -- match";
		if ($feature->parentID == 0) {
			$features[$feature->featureID] = $feature;
			echo "<br> -- -- no parent, match";
		} else {
			echo "<br> -- -- parent found, no match";
		}
	}
}
*/

$features = array();
foreach($registry->wordclassfeatures as $index => $feature) {
	//if (($feature->parentID == 0) && (($feature->inflectional == 0) || ($feature->inflectional == 1))) {
	if (($feature->parentID == 0) && ($feature->inflectional == 0)) {
		$features[$feature->featureID] = $feature;
		//echo "<br> -- -- no parent, match";
	} else {
		//echo "<br> -- -- parent found, no match";
	}
}

// featuret pitää olla wordclassfeaturesseja... eli wordclassfeatures.inflection = 0
$featurefield = new UISelectField("Feature", "featureID", 'featureID', $features, 'name');
$featurefield->setOnChange("featurerequirementdialogfeaturechanged()");
$addfeaturerequirementdialog->addField($featurefield);

$valuefield = new UISelectField("Value", "featureID", 'valueID', array(), 'name');
$valuefield->setDisabled(true);
$addfeaturerequirementdialog->addField($valuefield);

$row = new Row();
$row->languageID = $registry->objective->languageID;


$addfeaturerequirementdialog->setData($row);
$addfeaturerequirementdialog->show();



echo "<script>";
echo "	function featurerequirementdialoglanguagechanged() {";

echo "		console.log('featurerequirementdialoglanguagechanged');";

echo "	 	var languagefieldID 		 = '#".$languagefield->getEditFieldID()."';";
echo "	 	var featurefieldID 		 	 = '#".$featurefield->getEditFieldID()."';";
echo "	 	var valuefieldID 		 	 = '#".$valuefield->getEditFieldID()."';";
echo "	 	var languageID = $(languagefieldID).val();";

echo "	 	$(valuefieldID).empty();";
echo "		$(valuefieldID).attr('disabled', 'disabled');";
echo "		$(valuefieldID).addClass('uitextfield-disabled');";
echo "		$(valuefieldID).removeClass('uitextfield');";

echo "		if (languageID == 0) {";
echo "	 		$(featurefieldID).empty();";
echo "			$(featurefieldID).attr('disabled', 'disabled');";
echo "			$(featurefieldID).addClass('uitextfield-disabled');";
echo "			$(featurefieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";

echo "		$.getJSON('" . getUrl('worder/features/getwordclassfeatures') . "&wordclassID=" . $registry->objective->wordclassID . "&languageID='+languageID,'',function(data) {";

echo "	 		$(featurefieldID).empty();";
echo "			$(featurefieldID).removeAttr('disabled');";
echo "			$(featurefieldID).addClass('uitextfield');";
echo "			$(featurefieldID).removeClass('uitextfield-disabled');";
echo "			$(featurefieldID).append($('<option>', {value:0, text:''}));";

echo "			$.each(data, function(index) {";
echo "				var inflectional = data[index].inflectional;";
echo "				var infname = data[index].name;";

echo "				console.log(' - '+infname+' - '+inflectional);";
echo "				if ((inflectional == 0)) {";
echo "					$(featurefieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "				}";
echo "			});";
echo "		}); ";
echo "	}";
echo "</script>";



echo "<script>";
echo "	function featurerequirementdialogfeaturechanged() {";

echo "	 	var featurefieldID = '#".$featurefield->getEditFieldID()."';";
echo "	 	var valuefieldID = '#".$valuefield->getEditFieldID()."';";
echo "	 	var featureID = $(featurefieldID).val();";

echo "		if (featureID == 0) {";
echo "	 		$(valuefieldID).empty();";
echo "			$(valuefieldID).attr('disabled', 'disabled');";
echo "			$(valuefieldID).addClass('uitextfield-disabled');";
echo "			$(valuefieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";


echo "		$.getJSON('" . getUrl('worder/features/getfeaturesvalues') . "&featureID='+featureID,'',function(data) {";

echo "	 		$(valuefieldID).empty();";
echo "			$(valuefieldID).removeAttr('disabled');";
echo "			$(valuefieldID).addClass('uitextfield');";
echo "			$(valuefieldID).removeClass('uitextfield-disabled');";
echo "			$(valuefieldID).append($('<option>', {value:0, text:''}));";

echo "			$.each(data, function(index) {";
echo "				$(valuefieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "			});";
echo "		}); ";
echo "	}";
echo "</script>";




$section = new UITableSection("Word Feature requirements",$viewwidth);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addfeaturerequirementdialog->getID(), 'Lisää requirement');
$section->addButton($button);
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/objectives/removeobjectivefeaturerequirement&objectiveID=' . $this->registry->objective->objectiveID, 'rowID');

$column = new UISelectColumn("Language", "name", "languageID", $registry->languages);
$section->addColumn($column);

$column = new UISelectColumn("Wordclass", "name", "wordclassID", $registry->wordclasses);
$section->addColumn($column);

$column = new UISelectColumn("Feature", "name", "featureID", $registry->features);
$section->addColumn($column);

$column = new UISelectColumn("Value", "name", "valueID", $registry->features);
$section->addColumn($column);

$section->setData($registry->featurerequirements);
$section->show();




// ---------------------------------------------------------------------------------------------------
// InflectionSet
// ---------------------------------------------------------------------------------------------------

$addinflectionsetdialog = new UISection('Adding Inflectionset','600px');
$addinflectionsetdialog->setDialog(true);
$addinflectionsetdialog->setMode(UIComponent::MODE_INSERT);
$addinflectionsetdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/objectives/insertinflectionset&objectiveID=' . $this->registry->objective->objectiveID);

$field = new UISelectField("Inflectionset", "inflectionsetID", 'inflectionsetID', $registry->inflectionsets, 'name');
$field->setPredictable(true);
$addinflectionsetdialog->addField($field);

$addinflectionsetdialog->show();



$section = new UITableSection("Inflectionset requirements",$viewwidth);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addinflectionsetdialog->getID(), 'Lisää inflectionset');
$section->addButton($button);
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/objectives/removeobjectiveinflectionset&objectiveID=' . $this->registry->objective->objectiveID, 'inflectionsetID');

$column = new UISortColumn("#","inflectionsetID");
$section->addColumn($column);

$column = new UISelectColumn("InflectionsetID", "name", "inflectionsetID", $registry->inflectionsets);
$section->addColumn($column);

$section->setData($registry->lessoninflectionsets);
$section->show();






// ---------------------------------------------------------------------------------------------------
// Generate Features
// ---------------------------------------------------------------------------------------------------


$addgeneratefeaturedialog = new UISection('Add Objective Generate Feature','600px');
$addgeneratefeaturedialog->setDialog(true);
$addgeneratefeaturedialog->setMode(UIComponent::MODE_INSERT);
$addgeneratefeaturedialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/objectives/insertobjectivegeneratefeature&objectiveID=' . $this->registry->objective->objectiveID);

$semanticfeatures = array();
foreach($registry->features as $index => $feature) {
	if (($feature->languageID == 0) && ($feature->parentID == 0)) {
		$semanticfeatures[$feature->featureID] = $feature;
	}
}


$featurefield = new UISelectField("Feature", "featureID", 'featureID', $semanticfeatures, 'name');
$featurefield->setOnChange("generatefeaturechanged()");
$addgeneratefeaturedialog->addField($featurefield);

$valuefield = new UISelectField("Value", "featureID", 'valueID', array(), 'name');
$valuefield->setDisabled(true);
$addgeneratefeaturedialog->addField($valuefield);

$addgeneratefeaturedialog->show();



echo "<script>";
echo "	function generatefeaturechanged() {";

echo "	 	var featurefieldID = '#".$featurefield->getEditFieldID()."';";
echo "	 	var valuefieldID = '#".$valuefield->getEditFieldID()."';";
echo "	 	var featureID = $(featurefieldID).val();";

echo "		if (featureID == 0) {";
echo "	 		$(valuefieldID).empty();";
echo "			$(valuefieldID).attr('disabled', 'disabled');";
echo "			$(valuefieldID).addClass('uitextfield-disabled');";
echo "			$(valuefieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";


echo "		$.getJSON('" . getUrl('worder/features/getfeaturesvalues') . "&featureID='+featureID,'',function(data) {";

echo "	 		$(valuefieldID).empty();";
echo "			$(valuefieldID).removeAttr('disabled');";
echo "			$(valuefieldID).addClass('uitextfield');";
echo "			$(valuefieldID).removeClass('uitextfield-disabled');";
echo "			$(valuefieldID).append($('<option>', {value:0, text:''}));";

echo "			$.each(data, function(index) {";
echo "				$(valuefieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "			});";
echo "		}); ";
echo "	}";
echo "</script>";




$section = new UITableSection("Generate features",$viewwidth);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addgeneratefeaturedialog->getID(), 'Lisää feature');
$section->addButton($button);
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/objectives/removeobjectivegeneratefeature&objectiveID=' . $this->registry->objective->objectiveID, 'rowID');

$column = new UISelectColumn("Feature", "name", "featureID", $registry->features);
$section->addColumn($column);

$column = new UISelectColumn("Value", "name", "valueID", $registry->features);
$section->addColumn($column);

$section->setData($registry->generatefeatures);
$section->show();




// ---------------------------------------------------------------------------------------------------
// Objective features
// ---------------------------------------------------------------------------------------------------

/*
$addfeaturedialog = new UISection('Featuren lisäys','600px');
$addfeaturedialog->setDialog(true);
$addfeaturedialog->setMode(UIComponent::MODE_INSERT);
$addfeaturedialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/objectives/insertobjectivefeature&objectiveID=' . $this->registry->objective->objectiveID);


$requirements = array();

$row = new Row();
$row->requirementID = 0;
$row->name = "Generate";
$requirements[0] = $row;
$row = new Row();
$row->requirementID = 1;
$row->name = "Requirement";
$requirements[1] = $row;


$field = new UISelectField("Requirement", "requirementID", 'requirementID', $requirements, 'name');
$addfeaturedialog->addField($field);


$languagefield = new UISelectField("Language", "languageID", 'languageID', $registry->languages, 'name');
$languagefield->setOnChange("languagechanged()");
$addfeaturedialog->addField($languagefield);

$featurefield = new UISelectField("Feature", "featureID", 'featureID', $registry->wordclassfeatures, 'name');
//$field->setPredictable(true);
$featurefield->setDisabled(true);
$featurefield->setOnChange("featurechanged()");
$addfeaturedialog->addField($featurefield);

$valuefield = new UISelectField("Value", "featureID", 'valueID', $registry->features, 'name');
//$field->setPredictable(true);
$valuefield->setDisabled(true);
$addfeaturedialog->addField($valuefield);

$addfeaturedialog->show();



echo "<script>";
echo "	function languagechanged() {";

echo "	 	var languagefieldID 		 	 = '#".$languagefield->getEditFieldID()."';";
echo "	 	var featurefieldID 		 	 = '#".$featurefield->getEditFieldID()."';";
echo "	 	var valuefieldID 		 	 = '#".$valuefield->getEditFieldID()."';";
echo "	 	var languageID = $(languagefieldID).val();";

echo "	 	$(valuefieldID).empty();";
echo "		$(valuefieldID).attr('disabled', 'disabled');";
echo "		$(valuefieldID).addClass('uitextfield-disabled');";
echo "		$(valuefieldID).removeClass('uitextfield');";

echo "		if (languageID == 0) {";
echo "	 		$(featurefieldID).empty();";
echo "			$(featurefieldID).attr('disabled', 'disabled');";
echo "			$(featurefieldID).addClass('uitextfield-disabled');";
echo "			$(featurefieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";

echo "		$.getJSON('" . getUrl('worder/features/getwordclassfeatures') . "&wordclassID=" . $registry->objective->wordclassID . "&languageID='+languageID,'',function(data) {";

echo "	 		$(featurefieldID).empty();";
echo "			$(featurefieldID).removeAttr('disabled');";
echo "			$(featurefieldID).addClass('uitextfield');";
echo "			$(featurefieldID).removeClass('uitextfield-disabled');";
echo "			$(featurefieldID).append($('<option>', {value:0, text:''}));";

echo "			$.each(data, function(index) {";
echo "				$(featurefieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "			});";
echo "		}); ";
echo "	}";
echo "</script>";





echo "<script>";
echo "	function featurechanged() {";

echo "	 	var featurefieldID 		 	 = '#".$featurefield->getEditFieldID()."';";
echo "	 	var valuefieldID 		 	 = '#".$valuefield->getEditFieldID()."';";
echo "	 	var featureID = $(featurefieldID).val();";

echo "		if (featureID == 0) {";
echo "	 		$(valuefieldID).empty();";
echo "			$(valuefieldID).attr('disabled', 'disabled');";
echo "			$(valuefieldID).addClass('uitextfield-disabled');";
echo "			$(valuefieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";


echo "		$.getJSON('" . getUrl('worder/features/getfeaturesvalues') . "&featureID='+featureID,'',function(data) {";

echo "	 		$(valuefieldID).empty();";
echo "			$(valuefieldID).removeAttr('disabled');";
echo "			$(valuefieldID).addClass('uitextfield');";
echo "			$(valuefieldID).removeClass('uitextfield-disabled');";
echo "			$(valuefieldID).append($('<option>', {value:0, text:''}));";

echo "			$.each(data, function(index) {";
echo "				$(valuefieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "			});";
echo "		}); ";
echo "	}";
echo "</script>";






$section = new UITableSection("Feature requirements",$viewwidth);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addfeaturedialog->getID(), 'Lisää feature');
$section->addButton($button);
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/objectives/removeobjectivefeature&objectiveID=' . $this->registry->objective->objectiveID, 'rowID');

$column = new UISelectColumn("LanguageID", "name", "languageID", $registry->languages);
$section->addColumn($column);

$column = new UISelectColumn("Requirement", "name", "requirementID", $requirements);
$section->addColumn($column);

$column = new UISortColumn("#","featureID");
$section->addColumn($column);

$column = new UISelectColumn("Feature", "name", "featureID", $registry->features);
$section->addColumn($column);


$column = new UISortColumn("#","valueID");
$section->addColumn($column);

$column = new UISelectColumn("Value", "name", "valueID", $registry->features);
$section->addColumn($column);

$column = new UISortColumn("Inflectionl","inflectional");
$section->addColumn($column);

$section->setData($registry->objectivefeatures);
$section->show();

*/



/*

$addfeaturedialog = new UISection('Featuren lisäys Old','600px');
$addfeaturedialog->setDialog(true);
$addfeaturedialog->setMode(UIComponent::MODE_INSERT);
$addfeaturedialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/objectives/insertobjectivefeature&objectiveID=' . $this->registry->objective->objectiveID);


$requirements = array();

$row = new Row();
$row->requirementID = 0;
$row->name = "Generate";
$requirements[0] = $row;
$row = new Row();
$row->requirementID = 1;
$row->name = "Requirement";
$requirements[1] = $row;


$field = new UISelectField("Requirement", "requirementID", 'requirementID', $requirements, 'name');
$addfeaturedialog->addField($field);


$languagefield = new UISelectField("Language", "languageID", 'languageID', $registry->languages, 'name');
$languagefield->setOnChange("languagechanged()");
$addfeaturedialog->addField($languagefield);

$featurefield = new UISelectField("Feature", "featureID", 'featureID', $registry->wordclassfeatures, 'name');
//$field->setPredictable(true);
$featurefield->setDisabled(true);
$featurefield->setOnChange("featurechanged()");
$addfeaturedialog->addField($featurefield);

$valuefield = new UISelectField("Value", "featureID", 'valueID', $registry->features, 'name');
//$field->setPredictable(true);
$valuefield->setDisabled(true);
$addfeaturedialog->addField($valuefield);

$addfeaturedialog->show();



echo "<script>";
echo "	function languagechanged() {";

echo "	 	var languagefieldID 		 	 = '#".$languagefield->getEditFieldID()."';";
echo "	 	var featurefieldID 		 	 = '#".$featurefield->getEditFieldID()."';";
echo "	 	var valuefieldID 		 	 = '#".$valuefield->getEditFieldID()."';";
echo "	 	var languageID = $(languagefieldID).val();";

echo "	 	$(valuefieldID).empty();";
echo "		$(valuefieldID).attr('disabled', 'disabled');";
echo "		$(valuefieldID).addClass('uitextfield-disabled');";
echo "		$(valuefieldID).removeClass('uitextfield');";

echo "		if (languageID == 0) {";
echo "	 		$(featurefieldID).empty();";
echo "			$(featurefieldID).attr('disabled', 'disabled');";
echo "			$(featurefieldID).addClass('uitextfield-disabled');";
echo "			$(featurefieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";

echo "		$.getJSON('" . getUrl('worder/features/getwordclassfeatures') . "&wordclassID=" . $registry->objective->wordclassID . "&languageID='+languageID,'',function(data) {";

echo "	 		$(featurefieldID).empty();";
echo "			$(featurefieldID).removeAttr('disabled');";
echo "			$(featurefieldID).addClass('uitextfield');";
echo "			$(featurefieldID).removeClass('uitextfield-disabled');";
echo "			$(featurefieldID).append($('<option>', {value:0, text:''}));";

echo "			$.each(data, function(index) {";
echo "				$(featurefieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "			});";
echo "		}); ";
echo "	}";
echo "</script>";





echo "<script>";
echo "	function featurechanged() {";

echo "	 	var featurefieldID 		 	 = '#".$featurefield->getEditFieldID()."';";
echo "	 	var valuefieldID 		 	 = '#".$valuefield->getEditFieldID()."';";
echo "	 	var featureID = $(featurefieldID).val();";

echo "		if (featureID == 0) {";
echo "	 		$(valuefieldID).empty();";
echo "			$(valuefieldID).attr('disabled', 'disabled');";
echo "			$(valuefieldID).addClass('uitextfield-disabled');";
echo "			$(valuefieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";


echo "		$.getJSON('" . getUrl('worder/features/getfeaturesvalues') . "&featureID='+featureID,'',function(data) {";

echo "	 		$(valuefieldID).empty();";
echo "			$(valuefieldID).removeAttr('disabled');";
echo "			$(valuefieldID).addClass('uitextfield');";
echo "			$(valuefieldID).removeClass('uitextfield-disabled');";
echo "			$(valuefieldID).append($('<option>', {value:0, text:''}));";

echo "			$.each(data, function(index) {";
echo "				$(valuefieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "			});";
echo "		}); ";
echo "	}";
echo "</script>";

*/


/*

$section = new UITableSection("Feature requirements Old",$viewwidth);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addfeaturedialog->getID(), 'Lisää feature');
$section->addButton($button);
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/objectives/removeobjectivefeature&objectiveID=' . $this->registry->objective->objectiveID, 'rowID');

$column = new UISelectColumn("LanguageID", "name", "languageID", $registry->languages);
$section->addColumn($column);

$column = new UISelectColumn("Requirement", "name", "requirementID", $requirements);
$section->addColumn($column);

$column = new UISortColumn("#","featureID");
$section->addColumn($column);

$column = new UISelectColumn("Feature", "name", "featureID", $registry->features);
$section->addColumn($column);


$column = new UISortColumn("#","valueID");
$section->addColumn($column);

$column = new UISelectColumn("Value", "name", "valueID", $registry->features);
$section->addColumn($column);

$column = new UISortColumn("Inflectionl","inflectional");
$section->addColumn($column);

$section->setData($registry->objectivefeatures);
$section->show();

*/





// ---------------------------------------------------------------------------------------------------
// Lesson objective completion
// ---------------------------------------------------------------------------------------------------

/*
$addcompletiondialog = new UISection('Adding Inflectionset','600px');
$addcompletiondialog->setDialog(true);
$addcompletiondialog->setMode(UIComponent::MODE_INSERT);
$addcompletiondialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/objectives/insertcompletion&objectiveID=' . $this->registry->objective->objectiveID);

$field = new UISelectField("Objective", "objectiveID", 'parentobjectiveID', $registry->objectives, 'name');
$field->setPredictable(true);
$addcompletiondialog->addField($field);

$field = new UISelectField("Inflectionset", "inflectionsetID", 'inflectionsetID', $registry->inflectionsets, 'name');
$field->setPredictable(true);
$addcompletiondialog->addField($field);

$addcompletiondialog->show();



$section = new UITableSection("Objective completion",$viewwidth);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addcompletiondialog->getID(), 'Lisää completion');
$section->addButton($button);
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/objectives/removecompletion&objectiveID=' . $this->registry->objective->objectiveID, 'rowID');

$column = new UISortColumn("#","rowID");
$section->addColumn($column);

$column = new UISelectColumn("Objective", "name", "parentobjectiveID", $registry->objectives);
$section->addColumn($column);

$column = new UISelectColumn("InflectionsetID", "name", "inflectionsetID", $registry->inflectionsets);
$section->addColumn($column);

$section->setData($registry->objectivecompletions);
$section->show();
*/


// ---------------------------------------------------------------------------------------------------
// Lesson objective arguments
// ---------------------------------------------------------------------------------------------------



/*
// Käytettiin TierTablessa, ei enää käytössä
$parentfeatures = array();
foreach($registry->features as $index => $feature) {
	if (($feature->parentID == 0)) {
		$parentfeatures[$feature->featureID] = $feature;
	}
}


$addargumentfeaturedialog = new UISection('Argument featuren lisäys','600px');
$addargumentfeaturedialog->setDialog(true);
$addargumentfeaturedialog->setMode(UIComponent::MODE_INSERT);
$addargumentfeaturedialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/objectives/insertargumentfeature&objectiveID=' . $this->registry->objective->objectiveID);

$argumentfield = new UISelectField("Argument", "argumentID", 'argumentID', $registry->lessonarguments, 'name');
$argumentfield->setOnChange("argumentchanged()");
$addargumentfeaturedialog->addField($argumentfield);

$wordclassfield = new UISelectField("Wordclass", "wordclassID", 'wordclassID', $registry->wordclasses, 'name');
$wordclassfield->setDisabled(true);
$addargumentfeaturedialog->addField($wordclassfield);

$featurefield = new UISelectField("Feature", "featureID", 'featureID', array(), 'name');
$featurefield->setDisabled(true);
$featurefield->setOnChange("argumentfeaturechanged()");
$addargumentfeaturedialog->addField($featurefield);

$valuefield = new UISelectField("Value", "featureID", 'valueID', array(), 'name');
$valuefield->setDisabled(true);
$addargumentfeaturedialog->addField($valuefield);

$addargumentfeaturedialog->show();
 */

/*
echo "<script>";
echo "	function argumentchanged() {";

echo "	 	var argumentfieldID 		 	 = '#".$argumentfield->getEditFieldID()."';";
echo "	 	var argumentID = $(argumentfieldID).val();";
echo "	 	var wordclassfieldID 		 	 = '#".$wordclassfield->getEditFieldID()."';";

echo "		if (argumentID == 0) {";
echo "	 		$(wordclassfieldID).empty();";
echo "			$(wordclassfieldID).attr('disabled', 'disabled');";
echo "			$(wordclassfieldID).addClass('uitextfield-disabled');";
echo "			$(wordclassfieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";

echo "	 	$(wordclassfieldID).empty();";
echo "		$(wordclassfieldID).removeAttr('disabled');";
echo "		$(wordclassfieldID).addClass('uitextfield');";
echo "		$(wordclassfieldID).removeClass('uitextfield-disabled');";

echo "		var wordclassID = 0;";
foreach($registry->lessonarguments as $index => $argument) {
	echo "	if (argumentID == " . $argument->argumentID . ") {";
	$wordclass = $registry->wordclasses[$argument->wordclassID];
	echo "			$(wordclassfieldID).append($('<option>', {value:" . $wordclass->wordclassID . ", text:'" . $wordclass->name . "'}));";
	echo "			wordclassID = " . $wordclass->wordclassID;
	echo "	}";	
}
echo "		console.log('wordclassID - '+wordclassID);";

echo "		console.log('" . getUrl('worder/features/getwordclassfeatures') . "&wordclassID='+wordclassID+'&languageID=" . $this->registry->language->languageID . "');";

echo "		$.getJSON('" . getUrl('worder/features/getwordclassfeatures') . "&wordclassID='+wordclassID+'&languageID=" . $this->registry->language->languageID . "','',function(data) {";

echo "	 		var featurefieldID 		 	 = '#".$featurefield->getEditFieldID()."';";
echo "	 		$(featurefieldID).empty();";
echo "			$(featurefieldID).removeAttr('disabled');";
echo "			$(featurefieldID).addClass('uitextfield');";
echo "			$(featurefieldID).removeClass('uitextfield-disabled');";
echo "			$(featurefieldID).append($('<option>', {value:0, text:''}));";

echo "			$.each(data, function(index) {";
echo "				console.log(' - index - '+data[index].featureID+' - '+data[index].name);";
echo "				$(featurefieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "			});";
echo "		}); ";




echo "	}";
echo "</script>";
*/


/*
echo "<script>";
echo "	function argumentfeaturechanged() {";

echo "	 	var featurefieldID 		 	 = '#".$featurefield->getEditFieldID()."';";
echo "	 	var valuefieldID 		 	 = '#".$valuefield->getEditFieldID()."';";
echo "	 	var featureID = $(featurefieldID).val();";

echo "		if (featureID == 0) {";
echo "	 		$(valuefieldID).empty();";
echo "			$(valuefieldID).attr('disabled', 'disabled');";
echo "			$(valuefieldID).addClass('uitextfield-disabled');";
echo "			$(valuefieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";

echo "		$.getJSON('" . getUrl('worder/features/getfeaturesvalues') . "&featureID='+featureID,'',function(data) {";

echo "	 		$(valuefieldID).empty();";
echo "			$(valuefieldID).removeAttr('disabled');";
echo "			$(valuefieldID).addClass('uitextfield');";
echo "			$(valuefieldID).removeClass('uitextfield-disabled');";
echo "			$(valuefieldID).append($('<option>', {value:0, text:''}));";

echo "			$.each(data, function(index) {";
echo "				$(valuefieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "			});";
echo "		}); ";
echo "	}";
echo "</script>";
*/



$addargumentdialog = new UISection('Add Argument','600px');
$addargumentdialog->setDialog(true);
$addargumentdialog->setMode(UIComponent::MODE_INSERT);
$addargumentdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/objectives/insertobjectiveargument&objectiveID=' . $this->registry->objective->objectiveID);

$selectedarguments = array();
foreach($registry->arguments as $index => $argument) {
	if ($argument->wordclassID = $this->registry->objective->wordclassID) {
		$selectedarguments[$argument->argumentID] = $argument;
	}
}

$argumentfield = new UISelectField("Argument", "argumentID", 'argumentID', $selectedarguments, 'name');
$addargumentdialog->addField($argumentfield);

$objectivefield = new UISelectField("Objective", "objectiveID", 'argumentobjectiveID', $registry->objectives, 'name');
//$objectivefield->setOnChange("objectivechanged()");
$objectivefield->setPredictable(true);
$addargumentdialog->addField($objectivefield);

//$wordclassfield = new UISelectField("Wordclass", "wordclassID", 'wordclassID', $registry->wordclasses, 'name');
//$wordclassfield->setOnChange("wordclasschanged()");
//$addargumentdialog->addField($wordclassfield);

$addargumentdialog->show();

/*
echo "<script>";
echo "	function wordclasschanged() {";

echo "	 	var wordclassfieldID 		 	 = '#".$wordclassfield->getEditFieldID()."';";
echo "	 	var wordclassID = $(wordclassfieldID).val();";
echo "	 	var argumentfieldID 		 	 = '#".$argumentfield->getEditFieldID()."';";

echo "		if (wordclassID == 0) {";
echo "	 		$(argumentfieldID).empty();";
echo "			$(argumentfieldID).attr('disabled', 'disabled');";
echo "			$(argumentfieldID).addClass('uitextfield-disabled');";
echo "			$(argumentfieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";

echo "	 	$(argumentfieldID).empty();";
echo "		$(argumentfieldID).removeAttr('disabled');";
echo "		$(argumentfieldID).addClass('uitextfield');";
echo "		$(argumentfieldID).removeClass('uitextfield-disabled');";

echo "		console.log('wordclassID - '+wordclassID);";

echo "		console.log('" . getUrl('worder/wordclasses/getWordclassArgumentsJSON') . "&wordclassID='+wordclassID);";

echo "		$.getJSON('" . getUrl('worder/wordclasses/getWordclassArgumentsJSON') . "&wordclassID='+wordclassID,'',function(data) {";

echo "			console.log('fetch success');";

echo "	 		var argumentfieldID 		 	 = '#".$argumentfield->getEditFieldID()."';";
echo "	 		$(argumentfieldID).empty();";
echo "			$(argumentfieldID).removeAttr('disabled');";
echo "			$(argumentfieldID).addClass('uitextfield');";
echo "			$(argumentfieldID).removeClass('uitextfield-disabled');";
echo "			$(argumentfieldID).append($('<option>', {value:0, text:''}));";

echo "			$.each(data, function(index) {";
echo "				console.log(' - index - '+index+' - '+data[index]);";
echo "				$(argumentfieldID).append($('<option>', {value:index, text:data[index]}));";
echo "			});";
echo "		}); ";
echo "	}";
echo "</script>";
*/

/*
echo "<script>";
echo "	function objectivechanged() {";

echo "		console.log('objectivechanged');";

echo "	 	var objectivefieldID 		 	 = '#".$objectivefield->getEditFieldID()."';";
echo "	 	var objectiveID = $(objectivefieldID).val();";
echo "		var wordclassID = getobjectivewordclass(objectiveID);";
echo "		console.log('objectiveID - '+objectiveID);";
echo "		console.log('wordclassID - '+wordclassID);";
echo "	 	var argumentfieldID 		 	 = '#".$argumentfield->getEditFieldID()."';";

echo "	 	var wordclassfieldID 		 	 = '#".$wordclassfield->getEditFieldID()."';";
echo "	 	$(wordclassfieldID).val(wordclassID);";

/*
echo "		if (wordclassID == 0) {";
echo "	 		$(argumentfieldID).empty();";
echo "			$(argumentfieldID).attr('disabled', 'disabled');";
echo "			$(argumentfieldID).addClass('uitextfield-disabled');";
echo "			$(argumentfieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";


echo "	 	$(argumentfieldID).empty();";
echo "		$(argumentfieldID).removeAttr('disabled');";
echo "		$(argumentfieldID).addClass('uitextfield');";
echo "		$(argumentfieldID).removeClass('uitextfield-disabled');";

echo "		console.log('wordclassID - '+wordclassID);";

echo "		console.log('" . getUrl('worder/wordclasses/getWordclassArgumentsJSON') . "&wordclassID='+wordclassID);";

echo "		$.getJSON('" . getUrl('worder/wordclasses/getWordclassArgumentsJSON') . "&wordclassID='+wordclassID,'',function(data) {";

echo "			console.log('fetch success');";

echo "	 		var argumentfieldID 		 	 = '#".$argumentfield->getEditFieldID()."';";
echo "	 		$(argumentfieldID).empty();";
echo "			$(argumentfieldID).removeAttr('disabled');";
echo "			$(argumentfieldID).addClass('uitextfield');";
echo "			$(argumentfieldID).removeClass('uitextfield-disabled');";
echo "			$(argumentfieldID).append($('<option>', {value:0, text:''}));";

echo "			$.each(data, function(index) {";
echo "				console.log(' - index - '+index+' - '+data[index]);";
echo "				$(argumentfieldID).append($('<option>', {value:index, text:data[index]}));";
echo "			});";
echo "		}); ";
* /
echo "	}";
echo "</script>";
*/

/*
echo "<script>";
echo "	function getobjectivewordclass(objective) {";
foreach($this->registry->objectives as $index => $objective) {
	echo "		if (objective == " . $objective->objectiveID . ") return " . $objective->wordclassID . ";";
}
echo "	}";
echo "</script>";
*/



$editargumentdialog = new UISection('Argumentin muokkaus','500px');
$editargumentdialog->setDialog(true);
$editargumentdialog->setMode(UIComponent::MODE_EDIT);
$editargumentdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/objectives/updateobjectiveargument&objectiveID=' . $registry->objective->objectiveID, "rowID");

$field = new UISelectField("Argument","argumentID","argumentID", $registry->arguments, "name");
$editargumentdialog->addField($field);

$field = new UISelectField("Objective","valueobjectiveID",'valueobjectiveID',$registry->objectives, "name");
$field->setPredictable(true);
$editargumentdialog->addField($field);

$editargumentdialog->show();





$table = new UITableSection("Objective Arguments",$viewwidth);
$table->setOpen(true);
$table->editable(true);
$table->setFramesVisible(true);
$table->showTableHeader(false);
$table->setButtonAlign(UIComponent::VALIGN_BOTTOM);

//$table->setLineAction(UIComponent::ACTION_FORWARD, 'worder/objectives/showobjective&objectiveID=' . $registry->objective->objectiveID, 'rowID');
$table->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/objectives/removeobjectiveargument&objectiveID=' . $registry->objective->objectiveID, 'rowID');

// TODO: poistettu toistaiseksi käytöstä, koska editointi pitää päivittää lessoneiden objektiivejä
//		 toistaiseksi tämä pitää hoitaa ensin poistamalla argumentti ja sen jälkeen lisäämällä uusi
//$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editargumentdialog->getID(), "rowID");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addargumentdialog->getID(), "Lisää argumentti");
$table->addButton($button);

$column = new UIHiddenColumn("RowID", "rowID");
$table->addColumn($column);

$column = new UISelectColumn("Argument", "name", "argumentID", $this->registry->arguments);
$table->addColumn($column);

$column = new UISelectColumn("Wordclass", "name", "wordclassID", $this->registry->wordclasses);
$table->addColumn($column);

$column = new UISortColumn("oID","valueobjectiveID", "50px");
$table->addColumn($column);

/*
$column = new UIHiddenColumn("oID", "valueobjectiveID");
$table->addColumn($column);
*/

$column = new UISelectColumn("Objective", "name", "valueobjectiveID", $this->registry->objectives);
$table->addColumn($column);

foreach($this->registry->activelanguages as $index => $languageID) {
	$language = $this->registry->languages[$languageID];
	$column = new UIButtonColumn(UIComponent::ACTION_JAVASCRIPT, "valueobjectiveID", $language->shortname . "generatesubobjectiveexamples");
	$column->setWidth("50px");
	$column->setTitle($language->shortname);
	$table->addColumn($column);
}


$table->setData($registry->objectivearguments);
$table->show();



$subobjectiveexamplessection = new UISection("Generating Example Sentences for Argument",$viewwidth);
$subobjectiveexamplessection->setDialog(true);
$subobjectiveexamplessection->setMode(UIComponent::MODE_INSERT);
$subobjectiveexamplessection->setCustomContent('generateSubObjectiveDialogForExamples');
$subobjectiveexamplessection->show();


foreach($this->registry->activelanguages as $index => $languageID) {

	$language = $this->registry->languages[$languageID];
	echo "	<script>";
	echo "		function " . $language->shortname . "generatesubobjectiveexamples(objectiveID) {";
	//echo "			console.log(' - sub objectiveID - '+objectiveID);";
	echo "  		$('#sectiondialog-" . $subobjectiveexamplessection->getID() . "').dialog('open');";
	echo "			opengeneratesubobjectivesdialog(" . $languageID . ", objectiveID);";
	echo "		};";
	echo "	</script>";
}


function generateSubObjectiveDialogForExamples() {

	global $registry;

	echo "	<table style='width:100%'>";
	echo "		<tr>";
	echo "			<td style='padding-right:5px;'>";
	echo " 			</td>";

	echo "			<td>";
	echo "				<select id=examplelessonfield class=field-select style='width:100%'>";
	$firstlesson = 0;
	foreach($registry->lessons as $index => $lesson) {
		if ($firstlesson == 0) {
			$firstlesson = $lesson->lessonID;
			echo "<option value='" . $lesson->lessonID . "' selected>" . $lesson->name . "</option>";
		} else {
			echo "<option value='" . $lesson->lessonID . "'>" . $lesson->name . "</option>";
		}
	}
	echo "				</select>";
	echo " 			</td>";

	
	
	echo "			<td style='padding-right:5px'>";
	echo "			</td>";
	echo "		</tr>";

	echo "		<tr>";
	echo "			<td colspan=3>";

	echo "				<div id=subgenerateloadingdivpre style='display:none;height:100%;width:100%;'>";
	echo "Loading..";
	echo "				</div>";

	echo "				<div id=subgenerateloadeddivpre style='display:none;height:100%;width:100%;overflow:hidden'>";
	echo "					<div style='overflow-y:scroll;max-height:200px;'>";
	echo "					<table id=subgenerateresulttablepre style='width:100%;height:50px;'>";
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

	echo "			<td style='padding-right:5px;padding-top:10px;'>";
	echo "				<div style='text-align:right;'>";
	echo "					<button  class=section-button  onclick='subobjectiveclosebuttonpressed()'>Close</button>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";


	echo "	</table>";


	echo "	<script>";
	echo "		function opengeneratesubobjectivesdialog(languageID, objectiveID) {";
	//echo "			console.log('generate objective');";
	echo "			$('#generateloadingdivpre').show();";
	echo "			$('#generateloadeddivpre').hide();";
	
	echo "			var lessonID = $('#examplelessonfield').val();";
	echo "			console.log('lessonID - '+lessonID);";
	
	echo "			console.log('" . getUrl('worder/lessons/generateobjectivesentence') . "&include=1&languageID='+languageID+'&objectiveID='+objectiveID+'&lessonID='+lessonID);";
	echo "			var url = '" . getUrl('worder/lessons/generateobjectivesentence') . "&include=1&languageID='+languageID+'&comments=1&lessonID='+lessonID+'&objectiveID='+objectiveID;";
	echo "			$('#linkfieldpre').html('<a target=\"_blank\" href=\"'+url+'\">link</a>');";
	
	echo "			$.getJSON('" . getUrl('worder/lessons/generateobjectivesentence') . "&include=1&languageID='+languageID+'&objectiveID='+objectiveID+'&lessonID='+lessonID,'',function(data) {";
	//echo "					console.log('data.length - '+data.length);";
	echo "					$('#subgenerateresulttablepre').empty();";
	echo "					$('#subgenerateloadingdivpre').hide();";
	echo "					$('#subgenerateloadeddivpre').show();";
	echo "					var counter = 1;";
	echo "					$.each(data, function(index) {";
	echo "						var row = '<tr>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+counter+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index]+'</td>'";
	//echo "							+ '<td><button onclick=\"addSentenceToLesson(\''+data[index]+'\')\">lisää</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#subgenerateresulttablepre').append(row);";
	echo "						counter++;";
	echo "					});";
	echo "			}); ";
	echo "		}";
	echo "	</script>";

	echo "	<script>";
	echo "		function addSentenceToLesson(sentence, lessonID) {";
	echo "			console.log('addSentenceToLesson - '+lessonID);";
	echo "		}";
	echo "	</script>";

}

echo "<script>";
echo "		function subobjectiveclosebuttonpressed() {";
echo "  		$('#sectiondialog-" . $subobjectiveexamplessection->getID() . "').dialog('close');";
echo "		};";
echo "	</script>";


/*
foreach($this->registry->activelanguages as $index => $languageID) {

	echo "	<script>";
	echo "		function " . $language->shortname . "generatesubobjectiveexamples(value) {";
	echo "			console.log(' - sub value - '+value);";
	//echo "  		$('#sectiondialog-" . $objectvesentencesection->getID() . "').dialog('open');";
	//echo "			opengenerateobjectivesdialog(" . $languageID . ", value);";
	echo "  		$('#sectiondialog-" . $subobjectiveexamplessection->getID() . "').dialog('open');";
	echo "			opengeneratesubobjectivesdialog(" . $languageID . ", value);";
	echo "		};";
	echo "	</script>";
}
*/









/*
$section = new UITierTableSection("Arguments", $viewwidth);
$section->setFramesVisible(true);
$section->setOpen(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addargumentdialog->getID(), "Lisää argumentti");
$section->addButton($button);

$section->setLineAction(UIComponent::ACTION_FORWARD,"worder/objectives/showobjective","objectiveID");

$column = new UISortColumn("RowID", "rowID");
$section->addColumn($column);

$column = new UISelectColumn("Argument", "name", "argumentID", $this->registry->arguments);
$section->addColumn($column);

$column = new UISelectColumn("Wordclass", "name", "wordclassID", $this->registry->wordclasses);
$section->addColumn($column);

$column = new UISelectColumn("Objective", "name", "objectiveID", $this->registry->objectives);
$section->addColumn($column);

$column = new UIHiddenColumn("ID", "rowID");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "rowID", "worder/objectives/removeobjectiveargument&objectiveID=" . $registry->objective->objectiveID,"5%");
$column->setIcon("fa fa-ban");
$section->addColumn($column);

$section->setData($registry->lessonarguments);

$subcolumns = array();
$subcolumns[] = new UISelectColumn("Feature", "name", "featureID", $this->registry->features);
$subcolumns[] = new UISelectColumn("Value", "name", "valueID", $this->registry->features);
//$subcolumns[] = new UISelectColumn("vaaa", "name", "identifier", $this->registry->semanticfeatures);
//$subcolumns[] = new UIHiddenColumn("fevalueID", "workerorderID");
$subcolumns[] = new UIHiddenColumn("identifier", "identifier");

/*
$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "workerID", "workorders/workorders/setsortdown");
$column->setIcon("fa fa-chevron-down");
$subcolumns[] = $column;

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "workerID", "workorders/workorders/setsortup");
$column->setIcon("fa fa-chevron-up");
$subcolumns[] = $column;
* /

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "identifier", "worder/objectives/removeobjectiveargumentfeature&objectiveID=" . $registry->objective->objectiveID);
$column->setIcon("fa fa-ban");
$subcolumns[] = $column;

$section->setLevelData($registry->argumentfeatures, $subcolumns, "argumentID", "argumentID");
//$section->setSubLevelLineAction(UIComponent::ACTION_OPENDIALOG, $addargumentdialog->getID(), 'rowID');

$section->show();
*/



$lessonlinkdialog = new UISection('Edit lesson link','500px');
$lessonlinkdialog->setDialog(true);
$lessonlinkdialog->setMode(UIComponent::MODE_EDIT);
$lessonlinkdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/objectives/updatelessonlinkstage&objectiveID=' . $registry->objective->objectiveID, "rowID");

$field = new UISelectField("Stage","stage","stage", $registry->stages, "name");
$lessonlinkdialog->addField($field);

$lessonlinkdialog->show();




$section = new UITableSection("Objective Lessons", $viewwidth);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->showTableHeader(false);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);
$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/lessons/showlesson','lessonID');

$column = new UISortColumn("#","rowID", "50px");
$section->addColumn($column);

$column = new UISortColumn("#","lessonID", "50px");
$section->addColumn($column);

$column = new UIMultilangColumn("Name","name", 1);
$section->addColumn($column);

foreach($registry->activelanguages as $index => $languageID) {

	$language = $registry->languages[$languageID];
	$column = new UIButtonColumn(UIComponent::ACTION_JAVASCRIPT, "lessonID", $language->shortname . "generatelessonclicked");
	$column->setColorVariable('color'.$languageID);
	$column->setWidth("50px");
	$column->setTitle($language->shortname);
	$section->addColumn($column);
}

$section->setData($registry->lessons);
$section->show();



$objectvesentencesection = new UISection("Generating Sentences from Objective",$viewwidth);
$objectvesentencesection->setDialog(true);
$objectvesentencesection->setMode(UIComponent::MODE_INSERT);
$objectvesentencesection->setCustomContent('generateObjectiveLessonExamples');

$objectvesentencesection->show();


function generateObjectiveLessonExamples() {

	global $registry;

	echo "	<table style='width:100%'>";
	
	echo "		<tr>";
	echo "			<td style='padding-right:5px;'>";
	echo "<b>Objective:";
	echo " 			</td>";
	
	echo "			<td >";
	echo "<div id=objectivefieldpre style='width:100%;'>" . $registry->objective->name . "</div>";
	echo " 			</td>";
	
	echo "			<td style='padding-right:5px'>";
	echo "			</td>";
	echo "		</tr>";

	echo "		<tr>";
	echo "			<td style='padding-right:5px;'>";
	echo "<b>Lesson:";
	echo " 			</td>";
	
	echo "			<td >";
	echo "<div id=lessonnamefieldpre style='width:100%;'>lessonnamehere</div>";
	echo " 			</td>";
	
	echo "			<td style='padding-right:5px'>";
	echo "			</td>";
	echo "		</tr>";
	
	
	echo "		<tr>";
	echo "			<td>";
	echo "				<div id=linkfieldpre style='width:100%;'></div>";
	echo " 			</td>";
	echo "			<td style='padding-right:5px;'>";
	echo " 			</td>";
	echo "			<td style='padding-right:5px'>";
	echo "				<input id=lessonidfieldpre type=hidden style='width:200px;'>";
	echo "				<input id=languageidfieldpre type=hidden style='width:200px;'>";
	echo "				<input id=objectiveidfieldpre type=hidden style='width:200px;'>";
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
	
	echo "			<td style='padding-right:5px;padding-top:10px;'>";
	echo "				<div style='text-align:right;'>";
	echo "					<button  class=section-button  onclick='objectivesdialoguncheckbuttonpressedpre()'>UnCheck</button>";
	echo "					<button  class=section-button  onclick='objectivesdialogcheckbuttonpressedpre()'>Check</button>";
	echo "					<button  class=section-button  onclick='objectivesdialogclosebuttonpressedpre()'>Close</button>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	
	
	echo "	</table>";


	echo "	<script>";
	echo "		function getLessonName(lessonID) {";
	foreach($registry->lessons as $index => $lesson) {
		echo "		if (lessonID == '" . $lesson->lessonID . "') return '" . parseMultilangString($lesson->name, $_SESSION['languageID']) . "';";
	}
	echo "			return 'unknown';";
	echo "		}";
	echo "	</script>";
	

	echo "	<script>";
	echo "		function opengenerateobjectivesdialog(languageID, lessonID) {";
	echo "			console.log('generate objectives xx');";
	echo "			$('#generateloadingdivpre').show();";
	echo "			$('#generateloadeddivpre').hide();";
	echo "			console.log('" . getUrl('worder/lessons/generateobjectivesentence') . "&include=1&languageID='+languageID+'&objectiveID=" . $registry->objective->objectiveID . "&lessonID='+lessonID);";
	echo "			var url = '" . getUrl('worder/lessons/generateobjectivesentence') . "&include=1&languageID='+languageID+'&comments=1&lessonID='+lessonID+'&objectiveID=" . $registry->objective->objectiveID . "';";
	echo "			$('#linkfieldpre').html('<a target=\"_blank\" href=\"'+url+'\">link</a>');";
	
	echo "			var lessonname = getLessonName(lessonID);";
	echo "			$('#lessonnamefieldpre').html(lessonname);";
	
	echo "			$('#lessonidfieldpre').val(lessonID);";
	echo "			$('#languageidfieldpre').val(languageID);";
	echo "			$('#objectiveidfieldpre').val(" . $registry->objective->objectiveID . ");";
	
	echo "			$.getJSON('" . getUrl('worder/lessons/generateobjectivesentence') . "&include=1&languageID='+languageID+'&objectiveID=" . $registry->objective->objectiveID . "&lessonID='+lessonID,'',function(data) {";
	echo "					console.log('data.length - '+data.length);";
	echo "					console.log('data lessonID - '+lessonID);";
	echo "					$('#generateresulttablepre').empty();";
	echo "					$('#generateloadingdivpre').hide();";
	echo "					$('#generateloadeddivpre').show();";
	echo "					var counter = 1;";
	echo "					$.each(data, function(index) {";
	echo "						var row = '<tr>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+counter+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index]+'</td>'";
	echo "							+ '<td><button onclick=\"addSentenceToObjective(\''+data[index]+'\',\''+lessonID+'\',\''+languageID+'\')\">lisää</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#generateresulttablepre').append(row);";
	echo "						counter++;";
	echo "					});";
	echo "			}); ";
	echo "		}";
	echo "	</script>";

	echo "	<script>";
	echo "		function addSentenceToObjective(sentence, lessonID, languageID) {";
	echo "			console.log('addSentenceToObjective - '+sentence);";
	echo "			console.log('addSentenceToObjective - '+lessonID);";
	echo "			console.log('addSentenceToObjective - " . $registry->objective->objectiveID . "');";
	echo "			window.location = '" . getUrl("worder/objectives/insertsentencetoobjective") . "&sentence='+sentence+'&objectiveID=" . $registry->objective->objectiveID . "&lessonID='+lessonID+'&languageID='+languageID;";
	
	echo "		}";
	echo "	</script>";
	
}


echo "<script>";
echo "		function objectivesdialogcheckbuttonpressedpre() {";
echo "			var lessonID = $('#lessonidfieldpre').val();";
echo "			var languageID = $('#languageidfieldpre').val();";
echo "			url = '" . getUrl("worder/objectives/checkobjectivelesson") . "&lessonID='+lessonID+'&languageID='+languageID+'&objectiveID=" . $registry->objective->objectiveID . "';";
echo "			console.log(url);";
echo "			window.location = '" . getUrl("worder/objectives/checkobjectivelesson") . "&lessonID='+lessonID+'&languageID='+languageID+'&objectiveID=" . $registry->objective->objectiveID . "';";
echo "  		$('#sectiondialog-" . $objectvesentencesection->getID() . "').dialog('close');";
echo "		};";
echo "	</script>";


echo "<script>";
echo "		function objectivesdialoguncheckbuttonpressedpre() {";
echo "			var lessonID = $('#lessonidfieldpre').val();";
echo "			var languageID = $('#languageidfieldpre').val();";
echo "			url = '" . getUrl("worder/objectives/checkobjectivelesson") . "&lessonID='+lessonID+'&languageID='+languageID+'&objectiveID=" . $registry->objective->objectiveID . "';";
echo "			console.log(url);";
echo "			window.location = '" . getUrl("worder/objectives/uncheckobjectivelesson") . "&lessonID='+lessonID+'&languageID='+languageID+'&objectiveID=" . $registry->objective->objectiveID . "';";
echo "  		$('#sectiondialog-" . $objectvesentencesection->getID() . "').dialog('close');";
echo "		};";
echo "	</script>";


echo "<script>";
echo "		function objectivesdialogclosebuttonpressedpre() {";
echo "  		$('#sectiondialog-" . $objectvesentencesection->getID() . "').dialog('close');";
echo "		};";
echo "	</script>";


foreach($this->registry->activelanguages as $index => $languageID) {

	$language = $registry->languages[$languageID];
	echo "	<script>";
	echo "		function " . $language->shortname . "generatelessonclicked(value) {";
	echo "			console.log(' - value - '+value);";
	//echo "  		$('#sectiondialog-" . $objectvesentencesection->getID() . "').dialog('open');";
	//echo "			opengenerateobjectivesdialog(" . $languageID . ", value);";
	echo "  		$('#sectiondialog-" . $objectvesentencesection->getID() . "').dialog('open');";
	echo "			opengenerateobjectivesdialog(" . $languageID . ", value);";
	echo "		};";
	echo "	</script>";
}










$translatesentencesection = new UISection("Objective Sentence Translation",$viewwidth);
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
	echo "					<input id=resulttranslations type=hidden style='width:200px;'>";
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
echo "			var str = $('#resulttranslations').val();";
echo "			url = '" . getUrl("worder/objectives/checkobjectivesentence") . "&linkID='+linkID+'&languageID='+languageID+'&objectiveID=" . $registry->objective->objectiveID . "';";
echo "			console.log(url);";
echo "			window.location = '" . getUrl("worder/objectives/checkobjectivesentence") . "&linkID='+linkID+'&str='+str+'&languageID='+languageID+'&objectiveID=" . $registry->objective->objectiveID . "';";
echo "  		$('#sectiondialog-" . $translatesentencesection->getID() . "').dialog('close');";
echo "		};";
echo "	</script>";


echo "<script>";
echo "		function objectivesdialoguncheckbuttonpressedtranslation() {";
echo "			var linkID = $('#linkidfieldtranslation').val();";
echo "			console.log('uncheckbutton - '+linkID);";
echo "			var languageID = $('#languageidfieldtranslation').val();";
echo "			url = '" . getUrl("worder/objectives/uncheckobjectivesentence") . "&linkID='+linkID+'&languageID='+languageID+'&objectiveID=" . $registry->objective->objectiveID . "';";
echo "			console.log(url);";
echo "			window.location = '" . getUrl("worder/objectives/uncheckobjectivesentence") . "&linkID='+linkID+'&languageID='+languageID+'&objectiveID=" . $registry->objective->objectiveID . "';";
echo "  		$('#sectiondialog-" . $translatesentencesection->getID() . "').dialog('close');";
echo "		};";
echo "	</script>";


echo "<script>";
echo "		function objectivesdialogclosebuttonpressedtranslation() {";
echo "  		$('#sectiondialog-" . $translatesentencesection->getID() . "').dialog('close');";
echo "		};";
echo "	</script>";





// ---------------------------------------------------------------------------------------------------
// Lauseet
// ---------------------------------------------------------------------------------------------------

$section = new UITableSection("Objective Sentences",$viewwidth);
$section->setOpen(true);
$section->editable(true);
$section->showLineNumbers(true);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/objectives/removesentencefromobjective&objectiveID=' . $registry->objective->objectiveID, 'sentenceID');

$column = new UISortColumn("ID", "sentenceID", "sentenceID");
$section->addColumn($column);

$column = new UISelectColumn("Language", "name", "languageID", $registry->languages);
$section->addColumn($column);

$column = new UISelectColumn("Lesson", "name", "lessonID", $registry->lessons);
//$column->setColumnType(Column::COLUMNTYPE_MULTILANG);
$section->addColumn($column);

$column = new UISortColumn("Lause", "sentence", "sentence");
$section->addColumn($column);

$column = new UISortColumn("LinkID", "linkID", "linkID");
$section->addColumn($column);

foreach($registry->activelanguages as $index => $languageID) {

	$language = $registry->languages[$languageID];
	$column = new UIButtonColumn(UIComponent::ACTION_JAVASCRIPT, "linkID", $language->shortname . "translateobjectivesentence");
	$column->setColorVariable('color'.$languageID);
	$column->setWidth("50px");
	$column->setTitle($language->shortname);
	$section->addColumn($column);
}

$counter = 0;
$newsentences = array();
foreach($registry->sentences as $sentenceID => $sentence) {
	if ($counter == 1) $sentence->color = "#ffbdde";
	else $sentence->color = "";
	$counter++;
}

$section->setData($registry->sentences);
$section->show();

echo "<script>";
echo "		function getsentencesourcelanguage(linkID) {";
echo "			console.log('linkID - '+linkID);";
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
	echo "			console.log('translatesentence - '+linkID);";
	echo "			$('#linkidfieldtranslation').val(linkID);";
	
	echo "			var loc = '" . getUrl("worder/sentences/translateobjectivesentenceJSON") . "&linkID='+linkID+'&targetlanguageID=" . $languageID . "';";
	echo "			console.log('loc - '+loc);";
	//echo "			$('#linkfieldtranslation').html(linkID);";
	echo "			$('#linkfieldtranslation').html('<a target=\"_blank\" href=\"'+loc+'&comments=1\">link</a>');";
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
	echo "					var translationstring = '';";
	echo "					$.each(data.results, function(index) {";
	echo "						var row = '<tr>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+counter+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data.results[index].value+'</td>'";
	echo "							+ '</tr>';";
	echo "						$('#generateresulttabletranslation').append(row);";
	echo "						if (translationstring == '') {";
	echo "							translationstring = data.results[index].value;";
	echo "						} else {";
	echo "							translationstring = translationstring + ':' + data.results[index].value;";
	echo "						}";
	echo "						counter++;";
	echo "					});";
	echo "					$('#resulttranslations').val(translationstring);";
	echo "			}); ";
	
	echo "		};";
	echo "</script>";
}




$section = new UITableSection("This Objective is used in Objectives",$viewwidth);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);
$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/objectives/showobjective','objectiveID');

$column = new UISortColumn("#","objectiveID");
$section->addColumn($column);

$column = new UISelectColumn("Argument", "name", "argumentID", $registry->arguments);
$section->addColumn($column);

$column = new UISortColumn("Name", "name");
$section->addColumn($column);

//$column = new UISortColumn("Argument", "argumentID");
//$section->addColumn($column);


//$column = new UISelectColumn("Link", "name", "linktype", $registry->linktypes);
//$section->addColumn($column);

$section->setData($registry->linkedobjectives);
$section->show();



showTasksSection($registry, $viewwidth, "Objective Tasks");


// ---------------------------------------------------------------------------------------------------
// Hallinta
// ---------------------------------------------------------------------------------------------------

$managementSection = new UISection("Hallinta", $viewwidth);
$managementSection->editable(false);
$managementSection->setDebug(true);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/objectives/copyobjective&objectiveID=".$registry->objective->objectiveID, "Kopioi objective");
$managementSection->addButton($button);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/objectives/removeobjective&objectiveID=".$registry->objective->objectiveID, "Poista objective");
$managementSection->addButton($button);

$managementSection->show();


