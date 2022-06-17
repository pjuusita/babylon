<?php



//if ((count($registry->languages) > 1) || (count($registry->sharedfeatures) > 0)) {
	
/*
	$selection = array();
	$row = new Row();
	$row->name = 'Shared features';
	$row->languageID = 0;
	$selection[0] = $row;
	
	foreach($registry->languages as $index => $language) {
		$selection[$language->languageID] = $language;
	}
	*/

	$filterbox = new UIFilterBox();
	$filterbox->addSelectFilter($this->registry->languageID, $registry->selection, "worder/features/showfeatures", "Kieli", "languageID", "name");
	$filterbox->setEmptySelect(false);
	
	echo "<table style='width:800px;'>";
	echo "	<tr>";
	echo "		<td style='width:60%;vertical-align:bottom'>";
	//$paginator->show();
	echo "		</td>";
	echo "		<td style='width:40%;text-align:right;'>";
	$filterbox->show();
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
//} 




if ($registry->languageID == 0) {
	$adddialog = new UISection('Add Shared Feature','600px');
} else {
	$adddialog = new UISection('Add Feature','600px');
}
$adddialog->setDialog(true);
$adddialog->setMode(UIComponent::MODE_INSERT);
$adddialog->setInsertAction(UIComponent::ACTION_FORWARD, "worder/features/addfeature&languageID=" . $registry->languageID);

if ($registry->languageID != 0) {
	$language = $registry->languages[$registry->languageID];
	$field = new UIFixedTextField("Language", $language->name, "languageID", $registry->languageID);
	$adddialog->addField($field);

	$field = new UISelectField("Parent","featureID","parentID", $registry->features, "name");
	$adddialog->addField($field);
} else {
	$field = new UIFixedTextField("Kieli", "Feature name", "languageID", 0);
	
	$field = new UISelectField("Parent","featureID","parentID", $registry->sharedfeatures, "name");
	$adddialog->addField($field);
}


$field = new UITextField("Name","name","name");
$adddialog->addField($field);

$field = new UITextField("Abbreviation","abbreviation","abbreviation");
$adddialog->addField($field);

$adddialog->show();




$lista = array();
/*
$row = new Row();
$row->featureID = 0;
$row->sharedname = "";
$lista[0] = $row;
*/


$sharedfeatures = array();

foreach($registry->featurelist as $index => $feature) {
	
	if ($feature->languageID == 0) {
		$sharedfeatures[$feature->featureID] = $feature;
	}
	$lista[$feature->featureID] = $feature;
	$feature->sharedname = "Shared " . $feature->name;
}



if ($registry->languageID == 0) {
	$editdialog = new UISection('Edit Shared Feature','500px');
} else {
	$editdialog = new UISection('Edit Feature','500px');
}

$editdialog->setDialog(true);
$editdialog->setMode(UIComponent::MODE_EDIT);
$editdialog->setSaveAction(UIComponent::ACTION_FORWARD, "worder/features/updatefeature&languageID=" . $registry->languageID, 'featureID');

if ($registry->languageID != 0) {
	$language = $registry->languages[$registry->languageID];
	$languagefield = new UIFixedTextField("Language", $language->name, "languageID", $registry->languageID);
	$editdialog->addField($languagefield);
	$parentfield = new UISelectField("Parent","parentID","parentID", $registry->features, "name");
	//$parentfield->setOnChange("editdialogparentchanged()");
	$editdialog->addField($parentfield);
} else {
	$parentfield = new UISelectField("Parent","parentID","parentID", $registry->sharedfeatures, "name");
	//$parentfield->setOnChange("editdialogparentchanged()");
	$editdialog->addField($parentfield);
}


$field = new UITextField("Name","name","name");
$editdialog->addField($field);


$sharedlist = array();
foreach($registry->features as $index => $feature) {
	if ($feature->languageID == 0) $sharedlist[$feature->featureID] = $feature;
}

if ($registry->languageID != 0) {
	
	//if ((count($registry->languages) > 1) || (count($registry->sharedfeatures) > 0)) {
	//	$column = new UISelectColumn("Shared Link", "sharedname", "semanticlinkID", $lista, "");
	//	$section->addColumn($column);
	//}
	$linkfield = new UISelectField("Shared Link","featureID","semanticlinkID", $sharedfeatures, "sharedname");
	$editdialog->addField($linkfield);
}


$field = new UITextField("Abbreviation","abbreviation","abbreviation");
$editdialog->addField($field);

$editdialog->show();

/*
if ($registry->languageID != 0) {
	echo "<script>";
	echo "	function editdialogparentchanged() {";
	
	echo "	 	var parentfieldID 		 	 = '#".$parentfield->getEditFieldID()."';";
	echo "	 	var languagefieldID 		 	 = '#".$languagefield->getEditFieldID()."';";
	echo "	 	var linkfieldID 		 	 = '#".$linkfield->getEditFieldID()."';";
	echo "	 	var languageID = " . $registry->languageID . ";";
	echo "	 	var featureID = $(parentfieldID).val();";
	
	//echo "		console.log('language - '+languageID);";
	//echo "		console.log('path- " . getUrl('worder/features/getchildsemanticfeatures') . "&languageID='+languageID+'&featureID='+featureID);";
	
	/ *
	 echo "		if (languageID == 0) {";
	 echo "	 		$(featurefieldID).empty();";
	 echo "			$(featurefieldID).attr('disabled', 'disabled');";
	 echo "			$(featurefieldID).addClass('uitextfield-disabled');";
	 echo "			$(featurefieldID).removeClass('uitextfield');";
	
	 echo "	 		$(defaultfieldID).empty();";
	 echo "			$(defaultfieldID).attr('disabled', 'disabled');";
	 echo "			$(defaultfieldID).addClass('uitextfield-disabled');";
	 echo "			$(defaultfieldID).removeClass('uitextfield');";
	
	 echo "	 		$(wordbookfieldID).empty();";
	 echo "			$(wordbookfieldID).attr('disabled', 'disabled');";
	 echo "			$(wordbookfieldID).addClass('uitextfield-disabled');";
	 echo "			$(wordbookfieldID).removeClass('uitextfield');";
	
	 echo "			return;";
	 echo "		}";
	
	
	echo "		$.getJSON('" . getUrl('worder/features/getchildsemanticfeatures') . "&languageID='+languageID+'&featureID='+featureID,function(data) {";
	echo "			console.log('data.length - '+data.length);";
	
	echo "			if (data.length == 0) {";
	echo "	 			$(linkfieldID).empty();";
	echo "				$(linkfieldID).attr('disabled', 'disabled');";
	echo "				$(linkfieldID).addClass('uitextfield-disabled');";
	echo "				$(linkfieldID).removeClass('uitextfield');";
	echo "				return;";
	echo "			}";
	echo "	 		$(linkfieldID).empty();";
	echo "			$(linkfieldID).removeAttr('disabled');";
	echo "			$(linkfieldID).addClass('uitextfield');";
	echo "			$(linkfieldID).removeClass('uitextfield-disabled');";
	echo "			$(linkfieldID).append($('<option>', {value:0, text:''}));";
	
	echo "			$.each(data, function(index) {";
	echo "				console.log('row - '+data[index].featureID+' - '+data[index].name);";
	echo "				$(linkfieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
	echo "			});";
	echo "		}); ";
	echo "	}";
	echo "</script>";
	
}

*/



if ($registry->languageID == 0) {
	$section = new UITreeSection("Shared Features", "1000px");
} else {
	$section = new UITreeSection("Features", "1000px");
}
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $adddialog->getID(), 'Lisää feature');
$section->addButton($button);

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/features/removefeature&languageID=' . $registry->languageID, 'featureID');
$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/features/showfeature', 'featureID', UIComponent::ACTION_FORWARD);
//$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editdialog->getID(),"featureID");

$column = new UIColumn("Nimi", "name");
$section->addColumn($column);

$column = new UIColumn("FeatureID", "featureID");
$section->addColumn($column);

//$column = new UIColumn("Abbreviation", "abbreviation");
//$section->addColumn($column);

/*
foreach($registry->sharedfeatures as $index => $feature) {
	$feature->sharedname = "Shared "  .$feature->name;	
}

foreach($registry->featurelist as $index => $feature) {
	if ($feature->semanticlinkID == 0) $feature->semanticlinkID = null;
}


foreach($registry->features as $index => $feature) {
	if ($feature->semanticlinkID == 0) $feature->semanticlinkID = null;
}
*/


foreach($registry->features as $index => $feature) {
	$feature->sharedname = "Shared "  .$feature->name;
}

foreach($registry->features as $index => $feature) {
	if ($feature->semanticlinkID == 0) $feature->semanticlinkID = null;
}

$lista = array();
$row = new Row();
$row->featureID = 0;
$row->sharedname = "";
$lista[0] = $row;

foreach($registry->featurelist as $index => $feature) {
	$lista[$feature->featureID] = $feature;
	$feature->sharedname = "Shared " . $feature->name;
}
if ($registry->languageID != 0) {
	if ((count($registry->languages) > 1) || (count($registry->sharedfeatures) > 0)) {
		$column = new UISelectColumn("Shared Link", "sharedname", "semanticlinkID", $lista, "");
		$section->addColumn($column);
	}
}

$column = new UISortColumn("Abbraviation", "abbreviation", "abbreviation");
$section->addColumn($column);

$column = new UIHiddenColumn("FeatureID", "featureID");
$section->addColumn($column);

$column = new UIHiddenColumn("ParentID", "parentID");
$section->addColumn($column);

$column = new UIHiddenColumn("SemanticlinkID", "semanticlinkID");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "featureID", "worder/features/movefeature&dir=up");
$column->setIcon("fa fa-chevron-up");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "featureID", "worder/features/movefeature&dir=down");
$column->setIcon("fa fa-chevron-down");
$section->addColumn($column);

$section->setData($registry->features);
$section->show();




?>