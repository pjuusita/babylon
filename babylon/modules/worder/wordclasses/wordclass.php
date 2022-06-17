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

$section = new UISection("Wordclass", $width);
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD, "worder/wordclasses/updatewordclass", 'wordclassID');

$field = new UIFixedTextField("WordclassID", $registry->wordclass->wordclassID);
$section->addField($field);

$field = new UITextField("Name", "name", "name");
$section->addField($field);

$field = new UITextField("Abbreviation", "abbreviation", "abbreviation");
$section->addField($field);

$field = new UISelectField("Parent","parentID","parentID", $registry->wordclasses, "name");
$section->addField($field);


//$field = new UITextField("Comment", "comment", "comment");
//$section->addField($field);

//$field = new UIBooleanField("Active", "active", "active");
//$section->addField($field);

//$field = new UITextField("Features", "features", "features");
//$section->addField($field);

$section->setData($registry->wordclass);
$section->show();




// ---------------------------------------------------------------------------------------------------
// Add argument dialog
// ---------------------------------------------------------------------------------------------------

$argumentlanguages = array();
if (count($registry->languages) > 1) {
	$row = new Row();
	$row->languageID = 0;
	$row->name = "Shared";
	$argumentlanguages[0] = $row;
}
foreach($this->registry->languages as $index => $language) {
	//echo "<br>languageID - " . $language->languageID . " - " . $language->name;
	$argumentlanguages[$language->languageID] = $language;
}


$addargumentdialog = new UISection('Add argument','500px');
$addargumentdialog->setDialog(true);
$addargumentdialog->setMode(UIComponent::MODE_INSERT);
$addargumentdialog->setInsertAction(UIComponent::ACTION_FORWARD, 'worder/wordclasses/insertwordclassargument&wordclassID=' . $registry->wordclass->wordclassID);
//$addargumentdialog->setInsertAction('worder/wordclasses/insertsemanticfeatue', UIComponent::ACTION_FORWARD);

//$section->setInsertAction('worder/concepts/insertgroup', UIComponent::ACTION_JSON);
//$section->setSuccessAction('groupadded()', UIComponent::ACTION_JSON);

$field = new UIFixedTextField("Wordclass", $registry->wordclass->name, "wordclassID", $registry->wordclass->wordclassID);
$addargumentdialog->addField($field);

/*
if (count($registry->languages) > 1) {
	$languagefield = new UISelectField("Language","languageID","languageID", $argumentlanguages, "name");
	$languagefield->acceptEmpty(false);
	$languagefield->setOnChange("typechanged()");
	$languagefield->setValue(null);
	$addargumentdialog->addField($languagefield);
} else {
	$language = $registry->languages[$registry->languageID];
	$languagefield = new UIFixedTextField("Language",$language->name, 'languageID', $registry->languageID);
	$addargumentdialog->addField($languagefield);
}
*/


$field = new UITextField("Name", "name", "name");
$addargumentdialog->addField($field);




//$typefield = new UISelectField("Type", "typeID", "typeID", $typearray);
//$typefield->setOnChange("typechanged()");
//$addargumentdialog->addField($typefield);

$worclassfield = new UISelectField("Wordclass","wordclassvalueID","wordclassvalueID",$registry->wordclasses, "name");
//$worclassfield->setDisabled(true);
$addargumentdialog->addField($worclassfield);

//$featurefield = new UISelectField("Feature","featurevalueID","featurevalueID",$registry->features, "name");
//$featurefield->setDisabled(true);
//$addargumentdialog->addField($featurefield);

// Poistettu tilapäisesti, tämä lienee tarpeeton, kun typeID on aina = 2
/*
echo "<script>";
echo "	function typechanged() {";

echo "	 	var typefieldID 		 	 = '#".$typefield->getEditFieldID()."';";
echo "	 	var featurefieldID 		 	 = '#".$featurefield->getEditFieldID()."';";
echo "	 	var wordclassfieldID 		 	 = '#".$worclassfield->getEditFieldID()."';";
//echo "	 	var languagefieldID 		 	 = '#".$languagefield->getEditFieldID()."';";
//echo "	 	var languageID = $(languagefieldID).val();";
//echo "		console.log('language - '+languageID);";
echo "	 	var type = $(typefieldID).val();";

echo "			switch(type) {";
echo "				case '1':";			// table reference

echo "					$(wordclassfieldID).attr('disabled', 'disabled');";
echo "					$(wordclassfieldID).val('');";
echo "					$(wordclassfieldID).addClass('uitextfield-disabled');";
echo "					$(wordclassfieldID).removeClass('uitextfield');";

echo "					$.getJSON('" . getUrl('worder/features/getfeatures') . "&languageID='+languageID,function(data) {";
//echo "						console.log('data.length - '+data.length);";

echo "						if (data.length == 0) {";
echo "	 						$(linkfieldID).empty();";
echo "							$(linkfieldID).attr('disabled', 'disabled');";
echo "							$(linkfieldID).addClass('uitextfield-disabled');";
echo "							$(linkfieldID).removeClass('uitextfield');";
echo "							return;";
echo "						}";
echo "	 					$(featurefieldID).empty();";
echo "						$(featurefieldID).removeAttr('disabled');";
echo "						$(featurefieldID).addClass('uitextfield');";
echo "						$(featurefieldID).removeClass('uitextfield-disabled');";
echo "						$(featurefieldID).append($('<option>', {value:0, text:''}));";

echo "						$.each(data, function(index) {";
//echo "							console.log('row - '+data[index].featureID+' - '+data[index].name);";
echo "							$(featurefieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "						});";
echo "					}); ";

echo "					break;";


echo "				case '2':";			// table reference

echo "					$(wordclassfieldID).removeAttr('disabled');";
echo "					$(wordclassfieldID).addClass('uitextfield');";
echo "					$(wordclassfieldID).removeClass('uitextfield-disabled');";

echo "	 				$(featurefieldID).empty();";
echo "					$(featurefieldID).attr('disabled', 'disabled');";
echo "					$(featurefieldID).val('');";
echo "					$(featurefieldID).addClass('uitextfield-disabled');";
echo "					$(featurefieldID).removeClass('uitextfield');";

echo "					break;";

echo "				default:";			// table reference

echo "	 				$(featurefieldID).empty();";
echo "					$(featurefieldID).attr('disabled', 'disabled');";
echo "					$(featurefieldID).val('');";
echo "					$(featurefieldID).addClass('uitextfield-disabled');";
echo "					$(featurefieldID).removeClass('uitextfield');";

echo "					$(wordclassfieldID).attr('disabled', 'disabled');";
echo "					$(wordclassfieldID).val('');";
echo "					$(wordclassfieldID).addClass('uitextfield-disabled');";
echo "					$(wordclassfieldID).removeClass('uitextfield');";
echo "					break;";
echo "			}";

echo "	}";
echo "</script>";
*/ 
//$row = new Row();
//$row->languageID = $registry->languageID;
//$addargumentdialog->setData($row);
$addargumentdialog->show();





global $editdialog;

// TODO: lienee vanhentunut, siirretty argumentsectionille
$editdialog = new UISection('Edit Argument','500px');

$editdialog->setDialog(true);
$editdialog->setMode(UIComponent::MODE_EDIT);
$editdialog->setCustomSetFunction('editDialogSetFunction');

$editdialog->setSaveAction(UIComponent::ACTION_FORWARD, "worder/wordclasses/updateargument", 8);


$field = new UIFixedTextField("Wordclass", $registry->wordclass->name, "wordclassID", $registry->wordclass->wordclassID);
$editdialog->addField($field);

if (count($registry->languages) > 1) {
	$languagefield = new UISelectField("Language","languageID","languageID", $argumentlanguages, "name");
	$languagefield->acceptEmpty(false);
	$languagefield->setOnChange("typechanged()");
	$languagefield->setValue(null);
	$editdialog->addField($languagefield);
} else {
	$language = $registry->languages[$registry->languageID];
	$languagefield = new UIFixedTextField("Language",$language->name, 'languageID', $registry->languageID);
	$editdialog->addField($languagefield);
}

$namefield = new UITextField("Name", "name", "name");
$editdialog->addField($namefield);



$typefield = new UISelectField("Type", "typeID", "typeID", $typearray);
$typefield->setOnChange("edittypechanged()");
$editdialog->addField($typefield);

$worclassfield = new UISelectField("Wordclass","wordclassvalueID","wordclassvalueID",$registry->wordclasses, "name");
$worclassfield->setDisabled(true);
$editdialog->addField($worclassfield);

$featurefield = new UISelectField("Feature","featurevalueID","featurevalueID",$registry->features, "name");
$featurefield->setDisabled(true);
$editdialog->addField($featurefield);




echo "	<script>";

//echo "		var argumentidfield-" . $editdialog->getID() . " = 0;";

echo "		function setValue_" . $editdialog->getID(). "(fieldname, value) {";
//echo "			console.log('setting value - ' + fieldname + '='+value);";

echo "			if (fieldname == '0') {";
echo "	 			" . $languagefield->setValueJSFunction()."(value);";
//echo "				console.log('setting languagefield- ' + fieldname + '='+value);";
echo "			}";

echo "			if (fieldname == '2') {";
echo "	 			" . $namefield->setValueJSFunction()."(value);";
//echo "				console.log('setting namefield- ' + fieldname + '='+value);";
echo "			}";

echo "			if (fieldname == '5') {";
echo "	 			" . $typefield->setValueJSFunction()."(value);";
//echo "				console.log('setting typefield- ' + fieldname + '='+value);";
echo "			}";

echo "			if (fieldname == '6') {";
echo "	 			" . $worclassfield->setValueJSFunction()."(value);";
//echo "				console.log('setting wordclassfield- ' + fieldname + '='+value);";
echo "			}";

echo "			if (fieldname == '8') {";
//echo "	 			argumentidfield-" . $editdialog->getID() . " = value;";
echo "				$('#sectionidvalue-".$editdialog->getID()."').val(value);";
//echo "				console.log('setting sectionid - ' + fieldname + '='+value);";
echo "			}";

echo "			if (fieldname == '7') {";
echo "	 			" . $featurefield->setValueJSFunction()."(value);";

echo "	 			var typefieldID 		 	 = '#".$typefield->getEditFieldID()."';";
echo "	 			var typeID = $(typefieldID).val();";
echo "				if (typeID == 1) {";

echo "	 				var wordclassfieldID = '#".$worclassfield->getEditFieldID()."';";
echo "					$(wordclassfieldID).attr('disabled', 'disabled');";
echo "					$(wordclassfieldID).val('');";
echo "					$(wordclassfieldID).addClass('uitextfield-disabled');";
echo "					$(wordclassfieldID).removeClass('uitextfield');";

echo "	 				var featurefieldID = '#".$featurefield->getEditFieldID()."';";
echo "	 				$(featurefieldID).empty();";
echo "					$(featurefieldID).removeAttr('disabled');";
echo "					$(featurefieldID).addClass('uitextfield');";
echo "					$(featurefieldID).removeClass('uitextfield-disabled');";
echo "					$(featurefieldID).append($('<option>', {value:0, text:''}));";

//echo "					console.log('loadfeatures ...');";
//echo "					console.log('value - '+value);";
echo "	 				var languagefieldID = '#".$languagefield->getEditFieldID()."';";
echo "	 				var languageID = $(languagefieldID).val();";

echo "					$.getJSON('" . getUrl('worder/features/getfeatures') . "&languageID='+languageID,function(data) {";
//echo "						console.log('data.length - '+data.length);";

echo "						$.each(data, function(index) {";
echo "							$(featurefieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "						});";
echo "						$(featurefieldID).val(value);";
echo "					}); ";
echo "				} ";

echo "				if (typeID == 2) {";

echo "	 				var wordclassfieldID = '#".$worclassfield->getEditFieldID()."';";
echo "					$(wordclassfieldID).removeAttr('disabled');";
echo "					$(wordclassfieldID).addClass('uitextfield');";
echo "					$(wordclassfieldID).removeClass('uitextfield-disabled');";

echo "	 				var featurefieldID = '#".$featurefield->getEditFieldID()."';";
echo "	 				$(featurefieldID).empty();";
echo "					$(featurefieldID).attr('disabled', 'disabled');";
echo "					$(featurefieldID).val('');";
echo "					$(featurefieldID).addClass('uitextfield-disabled');";
echo "					$(featurefieldID).removeClass('uitextfield');";
echo "				} ";
//echo "				console.log('last item setted');";
echo "			}";
echo "		}";
echo "	</script>";


function editDialogSetFunction() {

	global $registry;
	global $editdialog;
	
	
}


echo "<script>";
echo "	function edittypechanged() {";

//echo "		console.log('edittypechanged....');";

echo "	 	var typefieldID 		 	 = '#".$typefield->getEditFieldID()."';";
echo "	 	var featurefieldID 		 	 = '#".$featurefield->getEditFieldID()."';";
echo "	 	var wordclassfieldID 		 	 = '#".$worclassfield->getEditFieldID()."';";
echo "	 	var languagefieldID 		 	 = '#".$languagefield->getEditFieldID()."';";
echo "	 	var languageID = $(languagefieldID).val();";
//echo "		console.log('language - '+languageID);";
echo "	 	var type = $(typefieldID).val();";

echo "			switch(type) {";
echo "				case '1':";			// table reference

echo "					$(wordclassfieldID).attr('disabled', 'disabled');";
echo "					$(wordclassfieldID).val('');";
echo "					$(wordclassfieldID).addClass('uitextfield-disabled');";
echo "					$(wordclassfieldID).removeClass('uitextfield');";

echo "					$.getJSON('" . getUrl('worder/features/getfeatures') . "&languageID='+languageID,function(data) {";
//echo "						console.log('data.length - '+data.length);";

echo "						if (data.length == 0) {";
echo "	 						$(linkfieldID).empty();";
echo "							$(linkfieldID).attr('disabled', 'disabled');";
echo "							$(linkfieldID).addClass('uitextfield-disabled');";
echo "							$(linkfieldID).removeClass('uitextfield');";
echo "							return;";
echo "						}";
echo "	 					$(featurefieldID).empty();";
echo "						$(featurefieldID).removeAttr('disabled');";
echo "						$(featurefieldID).addClass('uitextfield');";
echo "						$(featurefieldID).removeClass('uitextfield-disabled');";
echo "						$(featurefieldID).append($('<option>', {value:0, text:''}));";

echo "						$.each(data, function(index) {";
//echo "							console.log('row - '+data[index].featureID+' - '+data[index].name);";
echo "							$(featurefieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "						});";
echo "					}); ";

echo "					break;";


echo "				case '2':";			// table reference

echo "					$(wordclassfieldID).removeAttr('disabled');";
echo "					$(wordclassfieldID).addClass('uitextfield');";
echo "					$(wordclassfieldID).removeClass('uitextfield-disabled');";

echo "	 				$(featurefieldID).empty();";
echo "					$(featurefieldID).attr('disabled', 'disabled');";
echo "					$(featurefieldID).val('');";
echo "					$(featurefieldID).addClass('uitextfield-disabled');";
echo "					$(featurefieldID).removeClass('uitextfield');";

echo "					break;";

echo "				default:";			// table reference

echo "	 				$(featurefieldID).empty();";
echo "					$(featurefieldID).attr('disabled', 'disabled');";
echo "					$(featurefieldID).val('');";
echo "					$(featurefieldID).addClass('uitextfield-disabled');";
echo "					$(featurefieldID).removeClass('uitextfield');";

echo "					$(wordclassfieldID).attr('disabled', 'disabled');";
echo "					$(wordclassfieldID).val('');";
echo "					$(wordclassfieldID).addClass('uitextfield-disabled');";
echo "					$(wordclassfieldID).removeClass('uitextfield');";
echo "					break;";
echo "			}";

echo "	}";
echo "</script>";

$editdialog->show();


// ---------------------------------------------------------------------------------------------------
// Arguments
// ---------------------------------------------------------------------------------------------------

$section = new UITableSection("Arguments", $width);		// Näitä nimitettiin aiemmin semantic features
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);

$section->showLineNumbers(true);
// Poisto operaatio poistettu tästä, koska argumentilla on oma sectionsivu, jossa pitäisi olla hallinta-section
//$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/wordclasses/removeargument&wordclassID=' . $registry->wordclass->wordclassID, 8);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addargumentdialog->getID(), 'Lisää argumentti');
$section->addButton($button);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/arguments/showargument',8);
//$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editdialog->getID(), 8);

$column = new UISimpleColumn("Name", 2);
$section->addColumn($column);

//$column = new UISimpleColumn("Valuetype", 3);
//$section->addColumn($column);

$column = new UISimpleColumn("Arvojoukko", 4);
$section->addColumn($column);

$column = new UISimpleColumn("ID", 8);
$section->addColumn($column);

$column = new UIHiddenColumn("LanguageID", 0);
$section->addColumn($column);

$column = new UIHiddenColumn("TypeID", 5);
$section->addColumn($column);

$column = new UIHiddenColumn("WordclassID", 6);
$section->addColumn($column);

//$column = new UIHiddenColumn("ArgumentID", 8);
//$section->addColumn($column);

$column = new UIHiddenColumn("FeatureID", 7);
$section->addColumn($column);

/*
$column = new UISortColumn("ArgumentID", "argumentID", "worder/wordclasses/showword&sort=groupiID");		// tää pitäisi olla taulun sisäinen operaatio innertablella
$section->addColumn($column);

$column = new UISortColumn("Name", "name", 'name');
$section->addColumn($column);

$column = new UISortColumn("Valueset", "featurevalueID", 'featurevalueID');
$section->addColumn($column);
*/

$section->setData($registry->arguments);
$section->show();








// ---------------------------------------------------------------------------------------------------
// Semantic Features
// ---------------------------------------------------------------------------------------------------

/*
$addsemanticfeaturedialog = new UISection('Add Semantic Feature','500px');
$addsemanticfeaturedialog->setDialog(true);
$addsemanticfeaturedialog->setMode(UIComponent::MODE_INSERT);
$addsemanticfeaturedialog->setInsertAction(UIComponent::ACTION_FORWARD, 'worder/wordclasses/insertfeature&languageID=0');

$field = new UIFixedTextField("Wordclass", $registry->wordclass->name, "wordclassID", $registry->wordclass->wordclassID);
$addsemanticfeaturedialog->addField($field);

$semanticfeatures = array();
foreach($registry->features as $index => $feature) {
	if (($feature->languageID == 0) && ($feature->parentID == 0)) {
		$semanticfeatures[$index] = $feature;
	}
}

$featurefield = new UISelectField("Feature","featureID","featureID",$semanticfeatures, "name");
$addsemanticfeaturedialog->addField($featurefield);

$addsemanticfeaturedialog->show();
*/


/*
$section = new UITableSection("Semanttiset featuret","600px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addsemanticfeaturedialog->getID(), 'Lisää semanttinen feature');
$section->addButton($button);

//$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editfeaturedialog->getID(),"rowID");

$typeColumn = new UISortColumn("#", "rowID", "worder/wordclasses/showword&sort=groupiID");
$section->addColumn($typeColumn);

$typeColumn = new UISelectColumn("Feature", "name", "featureID", $this->registry->features);
$section->addColumn($typeColumn);

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/wordclasses/removewordclassfeature&wordclassID=' . $registry->wordclass->wordclassID, 'rowID');

$section->setData($registry->semanticfeatures);
$section->show();
*/






// ---------------------------------------------------------------------------------------------------
// Add feature dialog
// ---------------------------------------------------------------------------------------------------


$semanticvalues = array();
foreach($registry->allfeatures as $index => $feature) {
	if ($feature->languageID == 0) {
		$semanticvalues[$feature->featureID] = $feature;
	}
}

$addfeaturedialog = new UISection('Add Wordclass Feature','500px');
$addfeaturedialog->setDialog(true);
$addfeaturedialog->setMode(UIComponent::MODE_INSERT);
$addfeaturedialog->setInsertAction(UIComponent::ACTION_FORWARD, 'worder/wordclasses/insertfeature&wordclassID=' . $registry->wordclass->wordclassID);

$field = new UIFixedTextField("Wordclass", $registry->wordclass->name, "wordclassID", $registry->wordclass->wordclassID);
$addfeaturedialog->addField($field);

$languagefield = new UISelectField("Language","languageID","languageID",$registry->languages, "name");
$languagefield->setOnChange("adddialoglanguagechanged()");
$addfeaturedialog->addField($languagefield);

$typefield = new UISelectField("Type","inflectional","inflectional",$typeselection, "name");
$addfeaturedialog->addField($typefield);

$featurefield = new UISelectField("Feature","featureID","featureID",$registry->features, "name");
$featurefield->setDisabled(true);
$featurefield->setOnChange("adddialogfeaturechanged()");
$addfeaturedialog->addField($featurefield);

$defaultfield = new UISelectField("Default","defaultvalueID","defaultvalueID",$registry->features, "name");
$defaultfield->setDisabled(true);
$addfeaturedialog->addField($defaultfield);

$semdefaultfield = new UISelectField("Semantic","semanticdefaultID","semanticdefaultID",$semanticvalues, "name");
$addfeaturedialog->addField($semdefaultfield);

//$wordbookfield = new UISelectField("Sanakirjamuoto","wordbookformID","wordbookformID",$registry->features, "name");
//$wordbookfield->setDisabled(true);
//$addfeaturedialog->addField($wordbookfield);


$addfeaturedialog->setData($registry->concept);
$addfeaturedialog->show();




echo "<script>";
echo "	function adddialoglanguagechanged() {";

echo "	 	var languagefieldID 		 	 = '#".$languagefield->getEditFieldID()."';";
echo "	 	var featurefieldID 		 	 = '#".$featurefield->getEditFieldID()."';";
echo "	 	var defaultfieldID 		 	 = '#".$defaultfield->getEditFieldID()."';";
//echo "	 	var wordbookfieldID 		 	 = '#".$wordbookfield->getEditFieldID()."';";
echo "	 	var languageID = $(languagefieldID).val();";
//echo "		console.log('language - '+languageID);";
//echo "		console.log('path- " . getUrl('worder/features/getfeatures') . "&languageID='+languageID);";


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
echo "	function adddialogfeaturechanged() {";

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

//echo "	 		$(wordbookfieldID).empty();";
//echo "			$(wordbookfieldID).attr('disabled', 'disabled');";
//echo "			$(wordbookfieldID).addClass('uitextfield-disabled');";
//echo "			$(wordbookfieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";


echo "		$.getJSON('" . getUrl('worder/features/getfeaturesvalues') . "&featureID='+featureID,'',function(data) {";
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
echo "				$(defaultfieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
//echo "				$(wordbookfieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "			});";
echo "		}); ";
echo "	}";
echo "</script>";



// ---------------------------------------------------------------------------------------------------
// Edit feature dialog
// ---------------------------------------------------------------------------------------------------

$editfeaturedialog = new UISection('Edit Wordclass Feature','500px');

$editfeaturedialog->setDialog(true);
$editfeaturedialog->setMode(UIComponent::MODE_EDIT);
$editfeaturedialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/wordclasses/updatefeature', 'rowID');

$namefield = new UIFixedTextField("Wordclass", $registry->wordclass->name, "wordclassID", $registry->wordclass->wordclassID);
$editfeaturedialog->addField($namefield);

$languagefield = new UISelectField("Language","languageID","languageID",$registry->languages, "name");
$languagefield->setOnChange("editdialoglanguagechanged()");
$editfeaturedialog->addField($languagefield);

$typefield = new UISelectField("Type3","inflectional","inflectional",$typeselection, "name");
$editfeaturedialog->addField($typefield);

$featurefield = new UISelectField("Feature","featureID","featureID", $registry->features, "name");
$featurefield->setOnChange("editdialogfeaturechanged()");
$editfeaturedialog->addField($featurefield);

//$typeColumn = new UISelectColumn("Type", "name", "inflectional", $selection );
//$section->addColumn($typeColumn);


$defaultfield = new UISelectField("Default","defaultvalueID","defaultvalueID",$registry->allfeatures, "name");
$editfeaturedialog->addField($defaultfield);


$semdefaultfield = new UISelectField("Semantic","semanticdefaultID","semanticdefaultID",$semanticvalues, "name");
$editfeaturedialog->addField($semdefaultfield);

// Only in Rules voitaneen poistaa
//$boolfield = new UIBooleanField("Only in Rules","onlyinrules","onlyinrules");
//$editfeaturedialog->addField($boolfield);

//$wordbookfield = new UISelectField("Sanakirjamuoto","wordbookformID","wordbookformID",$registry->allfeatures, "name");
//$editfeaturedialog->addField($wordbookfield);

$editfeaturedialog->show();




echo "	<script>";
echo "		function setValue_" . $editfeaturedialog->getID(). "(fieldname, value) {";
echo "			console.log('setting value - ' + fieldname + '='+value);";
echo "			console.log('id - " . $languagefield->getID() . " - " .  $languagefield->setValueJSFunction()  . "');";

echo "			if (fieldname == 'rowID') {";
echo "				console.log('- namefield - " . $namefield->getID()  . "');";
echo "				" . $namefield->setValueJSFunction() . "(value);";
echo "				$('#sectionidvalue-".$editfeaturedialog->getID()."').val(value);";
echo "			}";

echo "			if (fieldname == 'languageID') {";
echo "				console.log('- languagefield - " . $languagefield->getID()  . "');";
echo "				" . $languagefield->setValueJSFunction() . "(value);";
echo "			}";

echo "			if (fieldname == 'featureID') {";
echo "				console.log('- featurefield - " . $featurefield->getID()  . "');";
echo "				" . $featurefield->setValueJSFunction() . "(value);";
echo "			}";

echo "			if (fieldname == 'inflectional') {";
echo "				console.log('- typefield - " . $typefield->getID()  . "');";
echo "				" . $typefield->setValueJSFunction() . "(value);";
echo "			}";

echo "			if (fieldname == 'defaultvalueID') {";
echo "				console.log('- defaultfield - " . $defaultfield->getID()  . "');";
//echo "				" . $defaultfield->setValueJSFunction() . "(value);";
echo "				var featureID = $('#editfield-" . $featurefield->getID() . "').val();";
echo "			 	var defaultfieldID = '#".$defaultfield->getEditFieldID()."';";
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




// ---------------------------------------------------------------------------------------------------
// Features
// ---------------------------------------------------------------------------------------------------

$section = new UITableSection("Taivutuskategoriat", $width);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);


$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/wordclasses/removewordclassfeature&wordclassID=' . $registry->wordclass->wordclassID, 'rowID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addfeaturedialog->getID(), 'Lisää taivutuskategoria');
$section->addButton($button);

//$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editfeaturedialog->getID(),"rowID");
$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/wordclasses/showwordclassfeature','rowID');

//$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/concepts/removegroup&conceptID=' . $registry->concept->conceptID, 'wordgroupID');
//$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/groups/showgroup','wordgroupID');


//$typeColumn = new UISelectColumn("Wordclass", "name", "wordclassID", $this->registry->wordclasses);
//$section->addColumn($typeColumn);

$typeColumn = new UISortColumn("#", "rowID", "worder/wordclasses/showword&sort=groupiID");
$section->addColumn($typeColumn);

$typeColumn = new UISelectColumn("Language", "name", "languageID", $this->registry->languages);
$section->addColumn($typeColumn);

$typeColumn = new UISelectColumn("Feature", "name", "featureID", $this->registry->features);
$section->addColumn($typeColumn);

//$typeColumn = new UISortColumn("Pakollinen", "mustbeused", "worder/wordclasses/showword&sort=groupiID");
//$section->addColumn($typeColumn);

$typeColumn = new UISelectColumn("Defaultvalue", "name", "defaultvalueID", $this->registry->allfeatures);
$section->addColumn($typeColumn);

//$typeColumn = new UISelectColumn("Sanakirjamuoto", "name", "wordbookformID", $this->registry->allfeatures);
//$section->addColumn($typeColumn);

$typeColumn = new UISelectColumn("Type", "name", "inflectional", $typeselection);
$section->addColumn($typeColumn);

$typeColumn = new UISelectColumn("Semantic", "name", "semanticdefaultID", $this->registry->allfeatures);
$section->addColumn($typeColumn);

// OnlyInRules-kenttä voitaneen poistaa
//$onlyinrulesColumn = new UIBooleanColumn("OiR", "onlyinrules");
//$section->addColumn($onlyinrulesColumn);

//$column = new UISortColumn("FeatureID", "featureID", "worder/wordclasses/showword&sort=groupiID");		// tää pitäisi olla taulun sisäinen operaatio innertablella
//$section->addColumn($column);

$section->setData($registry->wordclassfeatures);
$section->show();







// ---------------------------------------------------------------------------------------------------
// Baseforms
// ---------------------------------------------------------------------------------------------------



$addbaseform = new UISection('Add Baseform','500px');
$addbaseform->setDialog(true);
$addbaseform->setMode(UIComponent::MODE_INSERT);
$addbaseform->setInsertAction(UIComponent::ACTION_FORWARD, 'worder/wordclasses/insertbaseform&wordclassID=' . $registry->wordclass->wordclassID);

$field = new UIFixedTextField("Wordclass", $registry->wordclass->name, "wordclassID", $registry->wordclass->wordclassID);
$addbaseform->addField($field);

$field = new UITextField("Name", "name", "name");
$addbaseform->addField($field);

$languagefield = new UISelectField("Language","languageID","languageID",$registry->languages, "name");
$languagefield->setOnChange("addbaseformlanguagechanged()");
$addbaseform->addField($languagefield);

$addbaseformfeaturefield = new UISelectField("Feature","featureID","featureID",$registry->features, "name");
$addbaseformfeaturefield->setDisabled(true);
$addbaseformfeaturefield->setOnChange("addbaseformfeaturechanged()");
$addbaseform->addField($addbaseformfeaturefield);

$addbaseformvaluefield = new UISelectField("Value","valueID","valueID",$registry->features, "name");
$addbaseformvaluefield->setDisabled(true);
$addbaseform->addField($addbaseformvaluefield);

$addbaseform->setData($registry->concept);
$addbaseform->show();


echo "<script>";
echo "	function addbaseformlanguagechanged() {";

echo "	 	var languagefieldID 		 	 = '#".$languagefield->getEditFieldID()."';";
echo "	 	var featurefieldID 		 	 = '#".$addbaseformfeaturefield->getEditFieldID()."';";
echo "	 	var valuefieldID 		 	 = '#".$addbaseformvaluefield->getEditFieldID()."';";
echo "	 	var languageID = $(languagefieldID).val();";
echo "		console.log('language - '+languageID);";
echo "	 	$(valuefieldID).empty();";

echo "		if (languageID == 0) {";
echo "			$(featurefieldID).attr('disabled', 'disabled');";
echo "			$(featurefieldID).addClass('uitextfield-disabled');";
echo "			$(featurefieldID).removeClass('uitextfield');";
echo "			$(valuefieldID).attr('disabled', 'disabled');";
echo "			$(valuefieldID).addClass('uitextfield-disabled');";
echo "			$(valuefieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";

echo "	 	$(featurefieldID).empty();";
echo "		$(featurefieldID).removeAttr('disabled');";
echo "		$(featurefieldID).addClass('uitextfield');";
echo "		$(featurefieldID).removeClass('uitextfield-disabled');";
echo "		$(featurefieldID).append($('<option>', {value:0, text:''}));";

foreach($registry->languages as $index => $language) {
	echo "	if (languageID == " . $language->languageID . ") {";
	foreach($registry->wordclassfeatures as $index => $wordclassfeature) {
		if ($wordclassfeature->languageID == $language->languageID) {
			if ($wordclassfeature->inflectional == 1) {
				$feature = $registry->features[$wordclassfeature->featureID];
				echo "		$(featurefieldID).append($('<option>', {value:" . $wordclassfeature->featureID . ", text:'" . $feature->name . "'}));";
			}
		}
	}
	echo "	}";
}
echo "	}";
echo "</script>";





echo "<script>";
echo "	function addbaseformfeaturechanged() {";

echo "	 	var languagefieldID 		 	 = '#".$languagefield->getEditFieldID()."';";
echo "	 	var featurefieldID 		 	 = '#".$addbaseformfeaturefield->getEditFieldID()."';";
echo "	 	var defaultfieldID 		 	 = '#".$addbaseformvaluefield->getEditFieldID()."';";
echo "	 	var languageID = $(languagefieldID).val();";
echo "	 	var featureID = $(featurefieldID).val();";
echo "		console.log('featurechanged- '+featureID);";

echo "		if (featureID == 0) {";
echo "	 		$(defaultfieldID).empty();";
echo "			$(defaultfieldID).attr('disabled', 'disabled');";
echo "			$(defaultfieldID).addClass('uitextfield-disabled');";
echo "			$(defaultfieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";

echo "		$.getJSON('" . getUrl('worder/features/getfeaturesvalues') . "&featureID='+featureID,'',function(data) {";
echo "	 		$(defaultfieldID).empty();";
echo "			$(defaultfieldID).removeAttr('disabled');";
echo "			$(defaultfieldID).addClass('uitextfield');";
echo "			$(defaultfieldID).removeClass('uitextfield-disabled');";
echo "			$(defaultfieldID).append($('<option>', {value:0, text:''}));";
echo "			$.each(data, function(index) {";
echo "				$(defaultfieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "			});";
echo "		}); ";
echo "	}";
echo "</script>";




$appendbaseform = new UISection('Append Baseform','500px');
$appendbaseform->setDialog(true);
$appendbaseform->setMode(UIComponent::MODE_INSERT);
$appendbaseform->setInsertAction(UIComponent::ACTION_FORWARD, 'worder/wordclasses/appendbaseform&wordclassID=' . $registry->wordclass->wordclassID);


$appendformfield = new UISelectField("Form","formID","formID",$registry->baseforms, "fullname");
//$addbaseformfeaturefield->setDisabled(true);
$appendformfield->setOnChange("appendbaseformchanged()");
$appendbaseform->addField($appendformfield);

/*
$languagefield = new UISelectField("Language","languageID","languageID",$registry->languages, "name");
$languagefield->setOnChange("appendbaseformlanguagechanged()");
$appendbaseform->addField($languagefield);
*/

$addbaseformfeaturefield = new UISelectField("Feature","featureID","featureID",$registry->features, "name");
$addbaseformfeaturefield->setDisabled(true);
$addbaseformfeaturefield->setOnChange("appendbaseformfeaturechanged()");
$appendbaseform->addField($addbaseformfeaturefield);

$appendbaseformvaluefield = new UISelectField("Value","valueID","valueID",$registry->features, "name");
$appendbaseformvaluefield->setDisabled(true);
$appendbaseform->addField($appendbaseformvaluefield);

$appendbaseform->show();


echo "<script>";
echo "	function appendbaseformchanged() {";

echo "	 	var formfieldID 		 	 = '#".$appendformfield->getEditFieldID()."';";
echo "	 	var featurefieldID 		 	 = '#".$addbaseformfeaturefield->getEditFieldID()."';";
echo "	 	var valuefieldID 		 	 = '#".$appendbaseformvaluefield->getEditFieldID()."';";
echo "	 	var languageID = 0;";
echo "	 	var formID = $(formfieldID).val();";

foreach($registry->baseforms as $index => $form) {
	echo "	if (formID == " . $form->formID . ") languageID = " . $form->languageID . ";";
}
echo "		console.log('language - '+languageID);";
echo "	 	$(valuefieldID).empty();";

echo "		if (languageID == 0) {";
echo "			$(featurefieldID).attr('disabled', 'disabled');";
echo "			$(featurefieldID).addClass('uitextfield-disabled');";
echo "			$(featurefieldID).removeClass('uitextfield');";
echo "			$(valuefieldID).attr('disabled', 'disabled');";
echo "			$(valuefieldID).addClass('uitextfield-disabled');";
echo "			$(valuefieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";

echo "	 	$(featurefieldID).empty();";
echo "		$(featurefieldID).removeAttr('disabled');";
echo "		$(featurefieldID).addClass('uitextfield');";
echo "		$(featurefieldID).removeClass('uitextfield-disabled');";
echo "		$(featurefieldID).append($('<option>', {value:0, text:''}));";

foreach($registry->languages as $index => $language) {
	echo "	if (languageID == " . $language->languageID . ") {";
	foreach($registry->wordclassfeatures as $index => $wordclassfeature) {
		if ($wordclassfeature->languageID == $language->languageID) {
			if ($wordclassfeature->inflectional == 1) {
				$feature = $registry->features[$wordclassfeature->featureID];
				echo "		$(featurefieldID).append($('<option>', {value:" . $wordclassfeature->featureID . ", text:'" . $feature->name . "'}));";
			}
		}
	}
	echo "	}";
}
echo "	}";
echo "</script>";





echo "<script>";
echo "	function appendbaseformfeaturechanged() {";

echo "	 	var featurefieldID 		 	 = '#".$addbaseformfeaturefield->getEditFieldID()."';";
echo "	 	var defaultfieldID 		 	 = '#".$appendbaseformvaluefield->getEditFieldID()."';";
echo "	 	var featureID = $(featurefieldID).val();";
echo "		console.log('featurechanged - '+featureID);";

echo "		if (featureID == 0) {";
echo "	 		$(defaultfieldID).empty();";
echo "			$(defaultfieldID).attr('disabled', 'disabled');";
echo "			$(defaultfieldID).addClass('uitextfield-disabled');";
echo "			$(defaultfieldID).removeClass('uitextfield');";
echo "			return;";
echo "		}";

echo "		$.getJSON('" . getUrl('worder/features/getfeaturesvalues') . "&featureID='+featureID,'',function(data) {";
echo "	 		$(defaultfieldID).empty();";
echo "			$(defaultfieldID).removeAttr('disabled');";
echo "			$(defaultfieldID).addClass('uitextfield');";
echo "			$(defaultfieldID).removeClass('uitextfield-disabled');";
echo "			$(defaultfieldID).append($('<option>', {value:0, text:''}));";
echo "			$.each(data, function(index) {";
echo "				$(defaultfieldID).append($('<option>', {value:data[index].featureID, text:data[index].name}));";
echo "			});";
echo "		}); ";
echo "	}";
echo "</script>";




$section = new UITableSection("Baseforms", $width);
$section->setOpen(true);
//$section->editable(true);
$section->setFramesVisible(true);

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/wordclasses/removebaseform&wordclassID=' . $registry->wordclass->wordclassID, 'formID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addbaseform->getID(), 'Lisää');
$section->addButton($button);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $appendbaseform->getID(), 'Lisää feature');
$section->addButton($button);

$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $addbaseform->getID(),"rowID");

$typeColumn = new UISortColumn("#", "formID", "worder/wordclasses/showword&sort=groupiID");
$section->addColumn($typeColumn);

$typeColumn = new UISelectColumn("Language", "name", "languageID", $this->registry->languages);
$section->addColumn($typeColumn);

$typeColumn = new UISortColumn("Name", "name");
$section->addColumn($typeColumn);

$typeColumn = new UISortColumn("Features", "featurestring");
$section->addColumn($typeColumn);

$section->setData($registry->baseforms);
$section->show();



?>