<?php



$typeselection = array();
$row = new Row();
$row->inflectional = 0;
$row->name = "Word";
$typeselection[0] = $row;
$row = new Row();
$row->inflectional = 1;
$row->name = "Inflectional";
$typeselection[1] = $row;
$row = new Row();
$row->inflectional = 2;
$row->name = "Rule";
$typeselection[2] = $row;

$typearray = array();
$typearray[1] = "Feature";
$typearray[2] = "Wordclass";

$width = "800px";

/*
$semanticvalues = array();
foreach($registry->allfeatures as $index => $feature) {
	if ($feature->languageID == 0) {
		$semanticvalues[$feature->featureID] = $feature;
	}
}
*/

$section = new UISection("Wordclassfeature", $width);
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD, "worder/wordclasses/updatewordclassfeature", 'rowID');

$field = new UIFixedTextField("#", $registry->wordclassfeature->rowID);
$section->addField($field);

$wordclassfield = new UISelectField("Wordclass","wordclassID","wordclassID", $registry->wordclasses, "name");
$section->addField($wordclassfield);

//$namefield = new UIFixedTextField("Wordclass", $registry->wordclass->name, "wordclassID", $registry->wordclass->wordclassID);
//$section->addField($namefield);

$languagefield = new UISelectField("Language","languageID","languageID",$registry->languages, "name");
$languagefield->setOnChange("editdialoglanguagechanged()");
$section->addField($languagefield);

$typefield = new UISelectField("Featuretype","inflectional","inflectional",$typeselection, "name");
$section->addField($typefield);

$featurefield = new UISelectField("Feature","featureID","featureID", $registry->features, "name");
$featurefield->setOnChange("editdialogfeaturechanged()");
$section->addField($featurefield);

$defaultfield = new UISelectField("Default","defaultvalueID","defaultvalueID",$registry->featurevalues, "name");
$section->addField($defaultfield);

$semdefaultfield = new UISelectField("Semantic","semanticdefaultID","semanticdefaultID",$registry->semanticvalues, "name");
$section->addField($semdefaultfield);

$field = new UITextAreaField("Description","description","description");
$section->addField($field);

$section->setData($registry->wordclassfeature);
$section->show();



echo "<script>";
echo "	function editdialoglanguagechanged() {";

echo "	 	var languagefieldID 		 	 = '#".$languagefield->getEditFieldID()."';";
echo "	 	var featurefieldID 		 	 = '#".$featurefield->getEditFieldID()."';";
echo "	 	var defaultfieldID 		 	 = '#".$defaultfield->getEditFieldID()."';";
//echo "	 	var wordbookfieldID 		 	 = '#".$wordbookfield->getEditFieldID()."';";
echo "	 	var languageID = $(languagefieldID).val();";
//echo "		console.log('language - '+languageID);";
echo "		console.log('path- " . getUrl('worder/features/getfeatures') . "&languageID='+languageID);";


echo "		if (languageID == 0) {";
echo "	 		$(featurefieldID).empty();";
echo "			$(featurefieldID).attr('disabled', 'disabled');";
echo "			$(featurefieldID).addClass('uitextfield-disabled');";
echo "			$(featurefieldID).removeClass('uitextfield');";

echo "	 		$(defaultfieldID).empty();";
echo "			$(defaultfieldID).attr('disabled', 'disabled');";
echo "			$(defaultfieldID).addClass('uitextfield-disabled');";
echo "			$(defaultfieldID).removeClass('uitextfield');";

//echo "	 		$(wordbookfieldID).empty();";
//echo "			$(wordbookfieldID).attr('disabled', 'disabled');";
//echo "			$(wordbookfieldID).addClass('uitextfield-disabled');";
//echo "			$(wordbookfieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";

echo "		$.getJSON('" . getUrl('worder/features/getFeatures') . "&languageID='+languageID,'',function(data) {";
//echo "			console.log('data.length - '+data.length);";

echo "	 		$(defaultfieldID).empty();";
echo "			$(defaultfieldID).attr('disabled', 'disabled');";
echo "			$(defaultfieldID).addClass('uitextfield-disabled');";
echo "			$(defaultfieldID).removeClass('uitextfield');";

echo "	 		$(featurefieldID).empty();";
echo "			$(featurefieldID).removeAttr('disabled');";
echo "			$(featurefieldID).addClass('uitextfield');";
echo "			$(featurefieldID).removeClass('uitextfield-disabled');";
echo "			$(featurefieldID).append($('<option>', {value:0, text:''}));";

echo "			$.each(data, function(index) {";
//echo "				console.log('row - '+data[index].featureID+' - '+data[index].name);";
echo "				$(featurefieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "			});";
echo "		}); ";
echo "	}";
echo "</script>";




echo "<script>";
echo "	function editdialogfeaturechanged() {";

echo "	 	var languagefieldID 		 	 = '#".$languagefield->getEditFieldID()."';";
echo "	 	var featurefieldID 		 	 = '#".$featurefield->getEditFieldID()."';";
echo "	 	var defaultfieldID 		 	 = '#".$defaultfield->getEditFieldID()."';";
//echo "	 	var wordbookfieldID 		 	 = '#".$wordbookfield->getEditFieldID()."';";

echo "	 	var languageID = $(languagefieldID).val();";
echo "	 	var featureID = $(featurefieldID).val();";
//echo "		console.log('featurechanged- '+featureID);";
//echo "		console.log('path- " . getUrl('worder/features/getfeaturesvalues') . "&featureID='+featureID);";

echo "		if (featureID == 0) {";
echo "	 		$(defaultfieldID).empty();";
echo "			$(defaultfieldID).attr('disabled', 'disabled');";
echo "			$(defaultfieldID).addClass('uitextfield-disabled');";
echo "			$(defaultfieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";


echo "		$.getJSON('" . getUrl('worder/features/getfeaturesvalues') . "&self=1&featureID='+featureID,'',function(data) {";
//echo "			console.log('data.length - '+data.length);";

echo "	 		$(defaultfieldID).empty();";
echo "			$(defaultfieldID).removeAttr('disabled');";
echo "			$(defaultfieldID).addClass('uitextfield');";
echo "			$(defaultfieldID).removeClass('uitextfield-disabled');";
echo "			$(defaultfieldID).append($('<option>', {value:0, text:''}));";

//echo "	 		$(wordbookfieldID).empty();";
//echo "			$(wordbookfieldID).removeAttr('disabled');";
//echo "			$(wordbookfieldID).addClass('uitextfield');";
//echo "			$(wordbookfieldID).removeClass('uitextfield-disabled');";
//echo "			$(wordbookfieldID).append($('<option>', {value:0, text:''}));";

echo "			$.each(data, function(index) {";
//echo "				console.log('row - '+data[index].featureID+' - '+data[index].name);";
//echo "				$(wordbookfieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "				$(defaultfieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "			});";
echo "		}); ";
echo "	}";
echo "</script>";

if ($registry->wordclassfeature->inflectional == 0) {
	
	foreach($registry->words as $index => $word) {
		echo "<br>Word - " . $word->lemma . " - " . $word->wordID;
	}
	if (count($registry->words) == 0) {
		echo "<br>Ei ilmentymiä wordeissä...";		
	}
}


?>