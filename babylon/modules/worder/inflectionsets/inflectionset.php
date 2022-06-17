<?php

echo "<a href='" . getUrl("worder/inflectionsets/showinflectionsets") . "'>Takaisin</a>";


echo "<h1>" . $registry->inflectionset->name . "</h1>";


$section = new UISection("Inflectionset", "800px");
$section->setOpen(true);
$section->editable(true);

$section->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/inflectionsets/updateinflectionset&languageID=' . $registry->inflectionset->languageID, 'inflectionsetID');

$field = new UIFixedTextField("InflectionsetID", $registry->inflectionset->inflectionsetID);
$section->addField($field);

$language = $registry->languages[$registry->inflectionset->languageID];
$field = new UIFixedTextField("Language", $language->name);
$section->addField($field);

$field = new UITextField("Nimi","name","name");
$section->addField($field);

$field = new UISelectField("Parent","parentID","parentID",$registry->inflectionsets, "name");
$section->addField($field);

$field = new UITextAreaField("Kuvaus","description","description");
$section->addField($field);

//$wordclassfield = new UISelectField("Sanaluokka","wordclassID","wordclassID",$registry->wordclasses, "name");
//$section->addField($wordclassfield);

$section->setData($registry->inflectionset);
$section->show();


$insertsection = new UISection("Tavoitemuodon lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/inflectionsets/insertinflectionsetitem&inflectionsetID=' . $registry->inflectionset->inflectionsetID);

$wordclassfield = new UISelectField("Sanaluokka","wordclassID","wordclassID",$registry->wordclasses, "name");
$wordclassfield->setOnChange("wordclasschanged()");
$insertsection->addField($wordclassfield);

$featurefield = new UISelectField("Feature","featureID","featureID",$registry->wordclassfeatures, "name");
$featurefield->setOnChange("featurechanged()");
$insertsection->addField($featurefield);

$valuefield = new UISelectField("Value","featureID","valueID",$registry->features, "name");
$valuefield->setDisabled(true);
$insertsection->addField($valuefield);

$insertsection->show();


echo "<script>";
echo "	function wordclasschanged() {";

echo "	 	var wordclassfieldID = '#".$wordclassfield->getEditFieldID()."';";
echo "	 	var featurefieldID = '#".$featurefield->getEditFieldID()."';";
echo "	 	var wordclassID = $(wordclassfieldID).val();";

echo "		if (wordclassID == 0) {";
echo "	 		$(featurefieldID).empty();";
echo "			$(featurefieldID).attr('disabled', 'disabled');";
echo "			$(featurefieldID).addClass('uitextfield-disabled');";
echo "			$(featurefieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";

echo "		console.log('" . getUrl('worder/features/getwordclassfeatures') . "&languageID=" . $registry->inflectionset->languageID . "&wordclassID='+wordclassID);";

echo "		$.getJSON('" . getUrl('worder/features/getwordclassfeatures') . "&languageID=" . $registry->inflectionset->languageID . "&wordclassID='+wordclassID,'',function(data) {";

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




$section = new UITableSection("Selected forms", "800px");
$section->setFramesVisible(true);
$section->showLineNumbers(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää muoto");
$section->addButton($button);


$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/inflectionsets/removeinflectionsetitem&inflectionsetID=' . $registry->inflectionset->inflectionsetID, 'rowID');

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertlinksection->getID(), "Lisää inflection item");
//$section->addButton($button);

$section->setLineAction(UIComponent::ACTION_FORWARD, "worder/inflectionsets/showinflectionsetitem&inflectionsetID=" . $registry->inflectionset->inflectionsetID, 'rowID');

$column = new UISortColumn("RowID", "rowID", "rowID");
$section->addColumn($column);

$column = new UISelectColumn("Sanaluokka", "name", "wordclassID", $registry->wordclasses);
$section->addColumn($column);

$column = new UISelectColumn("Feature", "name", "parentfeatureID", $registry->features);
$section->addColumn($column);

$column = new UISelectColumn("Value", "name", "featureID", $registry->features);
$section->addColumn($column);

$column = new UISortColumn("Features", "featurestr", "featurestr");
$section->addColumn($column);

$section->setData($registry->inflectionsetitems);

$section->show();



// Valitut sanat tähän, ja ehkä myös niiden formit jotka täsmää mikäli mahdollista



$section = new UITableSection("Sanat", "800px");
$section->setFramesVisible(true);
$section->showLineNumbers(true);

$column = new UISortColumn("Sanat", "lemma", "lemma");
$section->addColumn($column);

$section->setData($registry->words);

$section->show();



$managementSection = new UISection("Hallinta", "800px");
$managementSection->editable(false);
$managementSection->setDebug(true);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/inflectionsets/removeinflectionset&inflectionsetID=" . $registry->inflectionset->inflectionsetID, "Poista inflectionset");
$managementSection->addButton($button);

$managementSection->show();


?>