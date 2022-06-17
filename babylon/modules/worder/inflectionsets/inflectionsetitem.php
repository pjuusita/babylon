<?php

echo "<a href='" . getUrl("worder/inflectionsets/showinflectionset") . "&id=" . $registry->inflectionsetitem->inflectionsetID . "'>Takaisin</a>";

$feature = $registry->features[$registry->inflectionsetitem->featureID];
echo "<h1>Inflectionsetitem - " . $feature->name . "</h1>";


$section = new UISection("Inflectionsetitem","600px");
$section->setOpen(true);
$section->editable(true);

$section->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/inflectionsets/updateinflectionsetitem', 'rowID');

$field = new UIFixedTextField("RowID", $registry->inflectionsetitem->rowID);
$section->addField($field);
$language = $registry->languages[$registry->inflectionsetitem->languageID];
$field = new UIFixedTextField("Language", $language->name);
$section->addField($field);

$field = new UISelectField("Sanaluokka","wordclassID","wordclassID",$registry->wordclasses, "name");
$section->addField($field);

$field = new UISelectField("Wordfeature","parentfeatureID","parentfeatureID",$registry->features, "name");
$section->addField($field);

$field = new UISelectField("Value","featureID","featureID",$registry->features, "name");
$section->addField($field);

$section->setData($registry->inflectionsetitem);
$section->show();


$section = new UISection("Features","600px");
$section->setOpen(true);
$section->editable(true);

$section->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/inflectionsets/updateinflectionsetitemfeatures', 'inflectionsetitemID');

foreach($this->registry->wordclassfeatures as $index => $feature) {
	//echo "<br>Feature - "  .$feature->name;
	//echo "<br>Featurevalues - " . count($registry->featurevalues[$feature->featureID]);
	
	$field = new UISelectField($feature->name,"feature-" . $feature->featureID,"feature-" . $feature->featureID,$registry->featurevalues[$feature->featureID], "name");
	$section->addField($field);
}

$section->setData($registry->featureitem);
$section->show();



?>