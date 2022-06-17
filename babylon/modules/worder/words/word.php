<?php



include "weightformatter.class.php";
$formatter = new WeightFormatter();
//echo "<br>Jeejee - " . $formatter->getString('доброе утро', '2:8');



$grammaticals = array();
$row = new Row();
$row->rowID = 0;
$row->name = "False";
$grammaticals[0] = $row;
$row = new Row();
$row->rowID = 1;
$row->name = "True";
$grammaticals[1] = $row;


echo "<h1>" . $registry->word->lemma . "</h1>";


//echo "<br>LanguageID - " . $registry->language->languageID;

$wordsection = new UISection("Lexeme","800px");
$wordsection->setOpen(true);
$wordsection->editable(true);
$wordsection->setData($registry->word);

$wordsection->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/words/updateword&languageID=' . $registry->word->languageID, 'wordID');


$wordIDfield = new UIFixedTextField("WordID", $registry->word->wordID);
$wordsection->addField($wordIDfield);

//echo "<br>languageID - " . $registry->word->languageID;
//echo "<br>language count - " . count($registry->languages);
$language = $registry->languages[$registry->word->languageID];
$languagefield = new UIFixedTextField("Language", $registry->language->name);
$wordsection->addField($languagefield);


$lemma = new UITextField("Lemma","lemma","lemma");
$lemma->setFormatter($formatter, 'weight');		// TODO: setFormatter korvattu toisenkaltaisella toiminnolla
$wordsection->addField($lemma);

$wordclassfield = new UISelectField("Part of speech","wordclassID","wordclassID",$registry->wordclasses, "name");
$wordsection->addField($wordclassfield);


//$field = new UISelectField("Käsite","conceptID","conceptid",array(), "name");
//$field = new UISelectField("Käsite","conceptID","ConceptID",$registry->concepts, "name");
//$field->setPredictive(true);
//$wordsection->addField($field);

$field = new UITextField("Paino","weight","weight");
$wordsection->addField($field);


$field = new UITextField("Latin","transcription_latin","transcription_latin");
$wordsection->addField($field);

//$field = new UITextField("Cyrillic","transcription_cyrillic","transcription_cyrillic");
//$wordsection->addField($field);

//$field = new UITextField("Phonetic","phonetic","Phonetic");
//$wordsection->addField($field);

$field = new UITextField("Taivutusluokka","inflection","inflection");
$wordsection->addField($field);


$field = new UITextField("Taivutusbase","inflectionforms","inflectionforms");
$wordsection->addField($field);

$casemarkings = array();
$casemarkings[0] = "Lowercase";									// talo
$casemarkings[1] = "First-letter uppercase";					// Mikko, Matti, Suomi
$casemarkings[2] = "First-letter uppercase (only first part)";	// Atlantin valtameri
$casemarkings[3] = "Full uppercase";							// USA
$casemarkings[4] = "First word full uppercase";					// DVD-soitin
$casemarkings[5] = "Mixed";										// United States of America
$casemarkings[6] = "First-letter uppercase (all parts)";		// Iso-Britannia, Marja-Leena, United States, Pohjois-amerikka


$field	= new UISelectField("Case","casemarking","casemarking",$casemarkings);
$wordsection->addField($field);


$field	= new UISelectField("Taivutin","inflectorID","inflectorID",$registry->inflectors);
$wordsection->addField($field);


if ($registry->language->languageID == 2) {
	$field = new UIFixedTextField("Pluralchecked", $registry->word->pluralchecked);
	$wordsection->addField($field);
}


$field = new UITextAreaField("Selite","description","description");
$wordsection->addField($field);


//$field	= new UITextField("Selected","selected","selected");
//$wordsection->addField($field);

$wordsection->show();





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
	echo "		function addConcept(conceptID) {";
	echo "			var languageID = $('#languagefield').val();";
	echo "			window.location = '" . getUrl("worder/words/insertconcept") . "&conceptID='+conceptID+'&wordID=" . $registry->word->wordID . "&languageID=" . $registry->language->languageID . "';";
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
	echo "			var languageID = $('#languagefield').val();";
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
	echo "							+ '<td><button onclick=\"addConcept(\''+data[index].conceptID+'\')\">lisää</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#searchresulttable').append(row);";
	echo "					});";
	echo "			}); ";
	////echo " 			console.log('finish');";
	echo "		}";
	echo "	</script>";
	
}



$searchsection = new UISection("Etsi sana","600px");
$searchsection->setDialog(true);
$searchsection->setMode(UIComponent::MODE_INSERT);
//$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/lessons/insertlesson');

$searchsection->setCustomContent('wordSearchDiv');
$searchsection->show();







function parentSearchDiv() {

	global $registry;

	echo "	<table style='width:100%'>";
	echo "		<tr>";
	echo "			<td colspan=2 style='padding-right:5px;'>";
	echo "				<input class=uitextfield  id=searchparentfield type='text' style='width:100%;' type='text' value=''>";
	echo " 			</td>";

	echo "			<td style='padding-right:5px'>";
	echo "				<div>";
	echo "					<button  class=section-button  onclick='searchparentbuttonpressed()'>Etsi</button>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	echo "		<tr>";
	echo "			<td colspan=3>";

	echo "				<div id=searchparentloadingdiv style='display:none;height:100%;width:100%;'>";
	echo "					<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
	echo "				</div>";

	echo "				<div id=searchparentloadeddiv style='display:none;height:100%;width:100%;overflow:hidden'>";
	echo "					<table id=searchparentresulttable style='width:100%;height:50px;table-layout:fixed'>";
	echo "						<tr><td>Empty</td></tr>";
	echo "					</table>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	echo "	</table>";


	echo "	<script>";
	echo "		$('#searchparentfield').keypress(function (e) {";
	echo "			if (e.keyCode == 13) {";
	echo "				searchparentbuttonpressed();";
	echo "			};";
	echo "		})";
	echo "	</script>";
	
	
	echo "	<script>";
	echo "		function searchparentbuttonpressed() {";
	echo "			console.log('search button pressed');";
	echo "			var search = $('#searchparentfield').val();";
	echo "			if (search == '') {";
	echo "				alert('ei saa olla tyhjä 1');";
	echo "				return;";
	echo "			}";
	echo "			$('#searchparentloadingdiv').show();";
	echo "			$('#searchparentloadeddiv').hide();";
	echo "			var languageID = " . $registry->word->languageID . ";";
	echo "			console.log('languageid -'+languageID+'-');";
	echo "			console.log('" . getUrl('worder/words/searchwordsJSON') . "&search='+search+'&languageID='+languageID);";

	echo "			$.getJSON('" . getUrl('worder/words/searchwordsJSON') . "&search='+search+'&languageID='+languageID,'',function(data) {";
	echo "					console.log('data.length - '+data.length);";
	echo "					$('#searchparentloadingdiv').hide();";
	echo "					$('#searchparentloadeddiv').show();";
	echo "					$('#searchparentresulttable tr').remove();";
	echo "					$.each(data, function(index) {";
	echo "						console.log('row - '+data[index].conceptID+' - '+data[index].name);";
	echo "						var row = '<tr>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordclassID+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordID+'</td>'";
	echo "							+ '<td title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/words/showword') . "&id='+data[index].wordID+'\">'+data[index].name+'</a></td>'";
	echo "							+ '<td style=\"width:310px;overflow:hidden;white-space: nowrap\" title=\''+data[index].gloss+'\'>'+data[index].name+'</td>'";
	echo "							+ '<td><button onclick=\"addParentItem(\''+data[index].wordID+'\')\">lisää</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#searchparentresulttable').append(row);";
	echo "					});";
	echo "			}); ";
	echo " 			console.log('finish');";
	echo "		}";
	echo "	</script>";

	echo "	<script>";
	echo "		function addParentItem(parentID) {";
	echo "			window.location = '" . getUrl("worder/words/addparenttoword") . "&wordID=" . $registry->word->wordID . "&parentID='+parentID+'&languageID=" . $registry->word->languageID . "';";
	echo "		}";
	echo "	</script>";
}



$addparentdialog = new UISection("Find parent","600px");
$addparentdialog->setDialog(true);
$addparentdialog->setMode(UIComponent::MODE_INSERT);

$addparentdialog->setCustomContent('parentSearchDiv');
$addparentdialog->show();




$section = new UITreeSection("Hierarchy","800px");
$section->setOpen(true);
$section->setCollapse(true);		// Kaikki treen solmut ovat auki
$section->editable(true);
$section->setFramesVisible(true);

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/words/removeparent&wordID=' . $registry->word->wordID, 'wordID');
$section->setDeleteActiveParam('removepossible');


$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addparentdialog->getID(), 'Add parent');
$section->addButton($button);

$column = new UISortColumn("Nimi", "lemma", "worder/groups/showgrouplist&sort=nimi");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$section->addColumn($column);

$column = new UISortColumn("WordID", "wordID", "worder/groups/showgrouplist");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

$section->setData($registry->hierarchy);
$section->show();




// ---------------------------------------------------------------------------------------------------
// Add Feature dialog
// ---------------------------------------------------------------------------------------------------

$addfeaturedialog = new UISection('Add Feature','500px');
$addfeaturedialog->setDialog(true);
$addfeaturedialog->setMode(UIComponent::MODE_INSERT);
$addfeaturedialog->setInsertAction(UIComponent::ACTION_FORWARD, "worder/words/addwordfeature&wordID=" . $registry->word->wordID);

$featurefield = new UISelectField("Feature","featureID","featureID",$registry->wordclassfeatures, "name");
$featurefield->setOnChange("featurechanged()");
$addfeaturedialog->addField($featurefield);

$valuefield = new UISelectField("Value","featureID","valueID",$registry->features, "name");
$valuefield->setDisabled(true);
$addfeaturedialog->addField($valuefield);

//$field = new UISelectField("Inheritance","rowID","inheritancemodeID",$registry->inheritancemodes, "name");
//$addfeaturedialog->addField($field);

$addfeaturedialog->show();



echo "<script>";
echo "	function featurechanged() {";

echo "	 	var featurefieldID 		 	 = '#".$featurefield->getEditFieldID()."';";
echo "	 	var valuefieldID 		 	 = '#".$valuefield->getEditFieldID()."';";
echo "	 	var featureID = $(featurefieldID).val();";
echo "		console.log('featureID - '+featureID);";

echo "		if (featureID == 0) {";
echo "	 		$(valuefieldID).empty();";
echo "			$(valuefieldID).attr('disabled', 'disabled');";
echo "			$(valuefieldID).addClass('uitextfield-disabled');";
echo "			$(valuefieldID).removeClass('uitextfield');";
echo "			console.log('feature empty');";
echo "			return;";
echo "		}";


echo "		$.getJSON('" . getUrl('worder/features/getfeaturesvalues') . "&featureID='+featureID,'',function(data) {";

echo "			console.log('get featurevalues from backend');";

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



$section = new UITableSection("Word Features","800px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addfeaturedialog->getID(), 'Add Feature');
$section->addButton($button);

$section->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/words/updatefeatures&languageID=' . $registry->language->languageID . '&wordID=' . $registry->word->wordID,'', true);

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/words/removefeature&wordID=' . $registry->word->wordID, array(1 => 'featureID', 3 => 'valueID' ));
$section->setDeleteActiveParam(6);

$column = new UISimpleColumn("Feature", 0);
$section->addColumn($column);

$column = new UISimpleColumn("Value", 2);
$section->addColumn($column);

$column = new UISimpleColumn("Mode", 4);
$section->addColumn($column);

$column = new UISimpleColumn("From", 5);
$section->addColumn($column);

$column = new UIHiddenColumn("FeatureID", 1);
$section->addColumn($column);

$column = new UIHiddenColumn("ValueID", 3);
$section->addColumn($column);

$section->setData($registry->featurevalues);
$section->show();


// ---------------------------------------------------------------------------------------------------
// Sanat
// ---------------------------------------------------------------------------------------------------

$section = new UITableSection("Käsitteet","800px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setTableHeaderVisible(false);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $searchsection->getID(), 'Lisää käsite');
$section->addButton($button);
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/words/removeconceptfromword&lang=' . $registry->language->languageID . '&wordID=' . $registry->word->wordID,'conceptID');
$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/concepts/showconcept', 'conceptID');

//$column = new UISimpleColumn("Kieli", 0);
//$section->addColumn($column);

$column = new UISortColumn("ConceptID","conceptID","ConceptID");
$section->addColumn($column);

$column = new UISortColumn("Name","name","Name");
$section->addColumn($column);
$section->setColumnWidth(2, '600px');

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "conddceptID", "worder/words/moveconcept&dir=up&wordID=" . $registry->word->wordID . "&languageID=" . $registry->word->languageID);
$column->setIcon("fa fa-chevron-up");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "conddceptID", "worder/words/moveconcept&dir=down&wordID=" . $registry->word->wordID . "&languageID=" . $registry->word->languageID);
$column->setIcon("fa fa-chevron-down");
$section->addColumn($column);

$section->setData($registry->concepts);
$section->show();






$addformdialog = new UISection('Add wordform','500px');
$addformdialog->setDialog(true);
$addformdialog->setMode(UIComponent::MODE_INSERT);
$addformdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/words/insertwordform&languageID=' . $registry->language->languageID . '&wordID=' . $registry->word->wordID);


$field = new UITextField("Sana","wordform","wordform");
$addformdialog->addField($field);

foreach($this->registry->inflectionalfeatures as $index => $wordclassfeature) {

	$parentfeature = $this->registry->features[$wordclassfeature->featureID];
	$valuearray = array();
	if ($wordclassfeature->defaultvalueID == null) {
		//$valuearray[0] = "";
	}
	//echo "<br>Feature - " . $parentfeature->name;
	foreach($this->registry->features as $index2 => $feature) {
		if ($feature->parentID == $parentfeature->featureID) {
			//echo "<br> -- value = " . $feature->name;
			$valuearray[$feature->featureID] = $feature;
			//if ($wordclassfeature->defaultvalueID == $feature->featureID) echo "***";
		}
	}
	//var_dump($valuearray);
	$namefeature = $registry->features[$wordclassfeature->featureID];
	$field = new UISelectField($namefeature->name,"feature-" . $parentfeature->featureID,"feature-" . $parentfeature->featureID,$valuearray, "name");
	$addformdialog->addField($field);
}

//$field = new UISelectField("Grammatical","grammatical",'grammatical',$grammaticals, "name");
//$addformdialog->addField($field);

$addformdialog->show();




$editformdialog = new UISection('Sanamuodon muokkaus','500px');
$editformdialog->setDialog(true);
$editformdialog->setMode(UIComponent::MODE_EDIT);
$editformdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/words/updatewordform&languageID=' . $registry->language->languageID . '&wordID=' . $registry->word->wordID, "rowID");


$field = new UITextField("Sana","wordform","wordform");
$editformdialog->addField($field);



foreach($this->registry->inflectionalfeatures as $index => $wordclassfeature) {

	//echo "<br>Taivutusmuoto - " . $wordclassfeature->description;
	$parentfeature = $this->registry->features[$wordclassfeature->featureID];
	$valuearray = array();
	if ($wordclassfeature->defaultvalueID == null) {
		//$valuearray[0] = "";
	}
	//echo "<br>Feature - " . $parentfeature->name;
	foreach($this->registry->features as $index2 => $feature) {
		if ($feature->parentID == $parentfeature->featureID) {
			//echo "<br> -- value = " . $feature->name;
			$valuearray[$feature->featureID] = $feature;
			//if ($wordclassfeature->defaultvalueID == $feature->featureID) echo "***";
		}
		// Lisätään myös itse featuren tänne (eli parent), tämä sallii geneerisen / tarkentuvat / ylemmän tason valuen olemassaolon
		// En osaa sanoa onko tämä välttämätön, jos ei ole asetettu niin ajaa ehkä ton saman ylemmän tason value ominaisuuden
		if ($feature->featureID == $parentfeature->featureID) {
			$valuearray[$feature->featureID] = $feature;
		}
	}
	//var_dump($valuearray);
	$namefeature = $registry->features[$wordclassfeature->featureID];
	$field = new UISelectField($namefeature->name,"feature-" . $parentfeature->featureID,$namefeature->name,$valuearray, "name");
	$editformdialog->addField($field);
}


$field = new UISelectField("Grammatical","grammatical",'grammatical',$grammaticals, "name");
$editformdialog->addField($field);

$editformdialog->show();



$section = new UITableSection("Wordforms","800px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->showLineNumbers(true);
//$button = new UIButton(UIComponent::ACTION_FORWARD, 'worder/words/addfailforms&lang=' . $registry->language->languageID . '&wordid=' . $registry->word->wordID, 'Lisää väärä muoto');
//$section->addButton($button);

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/words/removewordform&languageID=' . $registry->language->languageID . '&wordID=' . $registry->word->wordID,'rowID');
$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editformdialog->getID(), "rowID");


//$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/concepts/showconcept', 'conceptID');

if ($registry->language->languageID == 2) {
	
	if ($registry->word->pluralchecked == 0) {
		$button = new UIButton(UIComponent::ACTION_FORWARD, 'worder/words/pluralcheck&languageID=' . $registry->language->languageID . '&wordID=' . $registry->word->wordID, 'Tsekkaa monikko');
		$section->addButton($button);
	} else {
		$button = new UIButton(UIComponent::ACTION_FORWARD, 'worder/words/pluraluncheck&languageID=' . $registry->language->languageID . '&wordID=' . $registry->word->wordID, 'Untsekkaa monikko');
		$section->addButton($button);
	}
	
} 


$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addformdialog->getID(), 'Lisää muoto');
$section->addButton($button);


$column = new UISortColumn("#", "rowID", "worder/groups/showgrouplist&sort=nimi");
$section->addColumn($column);

$column = new UISortColumn("Wordform", "wordform", "worder/groups/showgrouplist&sort=nimi");
$section->addColumn($column);

foreach($this->registry->inflectionalfeatures as $index => $wordclassfeature) {
	$parentfeature = $this->registry->features[$wordclassfeature->featureID];
	$column = new UISelectColumn($parentfeature->name, "name", $parentfeature->name, $registry->features);
	$section->addColumn($column);
}

$column = new UISortColumn("Grammatical", "grammaticalstr", "worder/groups/showgrouplist&sort=nimi");
$section->addColumn($column);


$column = new UISortColumn("Def", "default", "worder/groups/showgrouplist&sort=nimi");
$section->addColumn($column);

$column = new UIHiddenColumn("Grammatical", "grammatical");
$section->addColumn($column);


$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "rowID", "worder/words/undefaultwordform&languageID=" . $registry->language->languageID . "&wordID=" . $registry->word->wordID);			// huom: toinen parametri 'conceptID' pitää olla taulussa mukana, hiddeninä jos ei muuten
//$column->setTitle("accept");
$column->setIcon("fa fa-check-circle");
$section->addColumn($column);


$section->setData($registry->acceptedforms);
$section->show();






if ($registry->word->languageID == 1) {

	$wordsection = new UISection("Baseforms","800px");
	$wordsection->setOpen(true);
	$wordsection->editable(true);
	
	$wordsection->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/words/updatewordbase&wordID=' . $registry->word->wordID, 'wordID');
	
	$forms = explode("/", $registry->word->inflectionforms);
	$row = new Row();
	foreach($forms as $index=>$value) {
		//echo "<br>" . $index . " - " . $value;
		$var = "base" . $index;
		$row->$var = $value;
	}
	
	$lemma = new UITextField("Base 0","base0","base0");
	$wordsection->addField($lemma);
	$lemma = new UITextField("Base 1","base1","base1");
	$wordsection->addField($lemma);
	$field = new UITextField("Base 2","base2","base2");
	$wordsection->addField($field);
	$field = new UITextField("Base 3","base3","base3");
	$wordsection->addField($field);
	$field = new UITextField("Base 4","base4","base4");
	$wordsection->addField($field);
	$field = new UITextField("Base 5","base5","base5");
	$wordsection->addField($field);
	$field = new UITextField("Base 6","base6","base6");
	$wordsection->addField($field);
	$field = new UITextField("Base 7","base7","base7");
	$wordsection->addField($field);
	
	$wordsection->setData($row);
	$wordsection->show();
	
}



$section = new UITableSection("Taivutus","800px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_FORWARD, 'worder/words/addfailforms&lang=' . $registry->language->languageID . '&wordid=' . $registry->word->wordID, 'Lisää väärä muoto');
$section->addButton($button);

$button = new UIButton(UIComponent::ACTION_FORWARD, 'worder/words/checkallforms&lang=' . $registry->language->languageID . '&wordid=' . $registry->word->wordID, 'Tsekkaa kaikki');
$section->addButton($button);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addformdialog->getID(), 'Lisää muoto');
$section->addButton($button);

$column = new UISimpleColumn("F#", 5, Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

$column = new UISimpleColumn("Sana", 0);
$section->addColumn($column);

$column = new UISimpleColumn("Muoto", 1);
$section->addColumn($column);

$column = new UISimpleColumn("Tsekattu", 2);
$section->addColumn($column);

$column = new UIHiddenColumn("Parent", 3);
$section->addColumn($column);

$column = new UISimpleColumn("RowID", 4);
$section->addColumn($column);


$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "3", "worder/words/acceptwordform&lang=" . $registry->language->languageID . "&wordid=" . $registry->word->wordID);			// huom: toinen parametri 'conceptID' pitää olla taulussa mukana, hiddeninä jos ei muuten
$column->setTitle("accept");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "4", "worder/words/failwordform&lang=" . $registry->language->languageID . "&wordid=" . $registry->word->wordID);			// huom: toinen parametri 'conceptID' pitää olla taulussa mukana, hiddeninä jos ei muuten
$column->setTitle("fail");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "4", "worder/words/deletewordform&lang=" . $registry->language->languageID . "&wordid=" . $registry->word->wordID);			// huom: toinen parametri 'conceptID' pitää olla taulussa mukana, hiddeninä jos ei muuten
$column->setTitle("del");
$section->addColumn($column);

$section->setData($registry->forms);
$section->show();



//-----------------------------------------------------------------------------
//   Sentences section
//-----------------------------------------------------------------------------


$sentencedialog = new UISection('Lauseen lisäys','500px');
$sentencedialog->setDialog(true);
$sentencedialog->setMode(UIComponent::MODE_INSERT);
$sentencedialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/words/insertsentence&languageID=' . $registry->language->languageID . '&wordID=' . $registry->word->wordID);

$field = new UITextField("Lause","sentence","sentence");
$field->setMultiline(1);
$sentencedialog->addField($field);

if (count($registry->concepts) > 0) {
	$field = new UISelectField("Käsite","conceptID","conceptID", $registry->concepts, "name");
	$sentencedialog->addField($field);

	if (count($registry->concepts) == 1) {
		$row = new Row();
		foreach($registry->concepts as $index => $concept) $row->conceptID = $concept->conceptID;
		$sentencedialog->setData($row);
	}
}


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
$sentencedialog->addField($field);

$sentencedialog->show();


function sentenceSearchDiv() {

	global $registry;

	echo "	<table style='width:100%'>";


	if (count($registry->concepts) > 0) {
		echo "		<tr>";
		echo "			<td colspan=2>";
		echo "				<select id=searchsentenceconceptfield class='field-select' style='width:300px;'>";
		echo "					<option value='0'></option>";
		foreach($registry->concepts as $index => $concept) {
			if (count($registry->concepts) == 1) {
				echo "				<option value='" . $concept->conceptID . "' selected='selected'>" . $concept->name . "</option>";
			} else {
				echo "				<option value='" . $concept->conceptID . "'>" . $concept->name . "</option>";
			}
		}
		echo "				</select>";
		echo "			</td>";
		echo "		</tr>";
	}


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
	echo "		function addSentence(sentenceID,conceptID) {";
	echo "			console.log('addSentence - '+sentenceID+','+conceptID);";
	//echo "			console.log('" . getUrl("worder/words/insertsentencetoword") . "&sentenceID='+sentenceID+'&conceptID=" . $registry->concept->conceptID . "');";
	echo "			window.location = '" . getUrl("worder/words/insertsentencetoword") . "&sentenceID='+sentenceID+'&conceptID='+conceptID+'&languageID=" . $registry->language->languageID . "&wordID=" . $registry->wordID . "';";
	echo "		}";
	echo "	</script>";


	echo "	<script>";
	echo "		function searchsentencebuttonpressed() {";
	echo "			console.log('search button pressed');";

	echo "			var conceptID = $('#searchsentenceconceptfield').val();";
	echo "			if (conceptID == 0) {";
	echo "				alert('ei saa olla tyhjä 2');";
	echo "				return;";
	echo "			}";

	echo "			var languageID = " . $registry->language->languageID . ";";
	echo "			var searh = $('#searchsentencefield').val();";
	echo "			if (searh == '') {";
	echo "				alert('ei saa olla tyhjä 2');";
	echo "				return;";
	echo "			}";
	echo "			$('#searchsentenceloadingdiv').show();";
	echo "			$('#searchsentenceloadeddiv').hide();";
	//echo "			var languageID = $('#languagefield').val();";
	//echo "			var languageID = " . $registry->rule->languageID . ";";
	echo "			console.log('languageid -'+languageID+'-');";
	echo "			console.log('" . getUrl('worder/sentences/searchsentences') . "&search='+searh+'&languageID='+languageID);";

	echo "			$.getJSON('" . getUrl('worder/sentences/searchsentences') . "&search='+searh+'&languageID='+languageID,'',function(data) {";
	echo "					console.log('data.length aa - '+data.length);";
	echo "					$('#searchsentenceloadingdiv').hide();";
	echo "					$('#searchsentenceloadeddiv').show();";
	echo "					$('#searchsentenceresulttable tr').remove();";
	echo "					var conceptID = $('#searchsentenceconceptfield').val();";
	echo "					var counter = 0;";

	echo "					$.each(data, function(index) {";
	echo "						counter++;";
	echo "						console.log('row - '+data[index].sentenceID+' - '+data[index].sentence);";
	echo "						var row = '<tr>'";
	//echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].sentenceID+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].sentence+'</td>'";
	//echo "							+ '<td title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/words/showword') . "&lang='+languageID+'&id='+data[index].wordID+'\">'+data[index].name+'</a></td>'";
	echo "							+ '<td><button onclick=\"addSentence(\''+data[index].sentenceID+'\',\''+conceptID+'\')\">lisää</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#searchsentenceresulttable').append(row);";
	echo "					});";

	echo "					if (counter == 0) {";
	echo "						var row = '<tr>'";
	echo "							+ '<td style=\"padding-right:10px;\">Ei löytynyt</td>'";
	echo "							+ '</tr>';";
	echo "						$('#searchsentenceresulttable').append(row);";
	echo "					}";

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



$section = new UITableSection("Lauseet","800px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $sentencedialog->getID(), 'Lisää lause');
$section->addButton($button);
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $searchsection->getID(), 'Etsi lause');
$section->addButton($button);

//$section->setDeleteAction('worder/words/removesentencefromword&wordID=' . $registry->word->wordID . '&languageID=' . $registry->language->languageID, 'worder/words/showword&id=' . $registry->word->wordID, 'sentenceID');
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/words/removesentencefromword&wordID=' . $registry->word->wordID . '&languageID=' . $registry->language->languageID, 'sentenceID');

$column = new UISortColumn("#", "sentenceID");
$section->addColumn($column);

$column = new UISortColumn("Lause", "sentence", "worder/groups/showgrouplist&sort=nimi");
$section->addColumn($column);

$column = new UISelectColumn("Käsite", "name", "conceptID", $registry->concepts);
$section->addColumn($column);

$section->setData($registry->sentences);
$section->show();




/*
 foreach ($registry->featurevalues as $ind1 => $val1) {
 echo "<br><br>featurevalue22 - " . $ind1 . " - " . var_dump($val1);
 }
 */
/*
foreach($registry->featurevalues as $index => $value) {
	$valueset = $registry->featurevaluesets[$index];
	//echo "<br>Indexaaa - " . $index . " ... value - " . $value . "<br>";
	//print_r($valueset);
	//foreach ($valueset as $ind1 => $val1) {
	//echo "<br><br>featurevalue-rr - " . $ind1 . " - " . get_class($val1) . " - " . var_dump($val1);
	//}


	$field	= new UISelectField($index,$value,$index,$valueset, "name");
	if ($registry->mandatories[$index] == 0) $field->setMandatory(true);
	$section->addField($field);
}
*/
//$section->setData($registry->featurevalues);


//-----------------------------------------------------------------------------
//   Ryhmän lisäys dialogi
//-----------------------------------------------------------------------------

//$contept = $registry->concepts[$registry->word->conceptID];
/*
$groupdialog = new UISection('Ryhmän lisäys','500px');
$groupdialog->setDialog(true);
$groupdialog->setMode(UIComponent::MODE_INSERT);
$groupdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/words/insertgroup&lang=' . $registry->language->languageID);

//$field = new UIFixedTextField("Käsite", $registry->contept->name, "conceptID", $registry->word->conceptID);
//$groupdialog->addField($field);

$field = new UIFixedTextField("Sana", $registry->word->lemma, "wordID", $registry->word->wordID);
$groupdialog->addField($field);

$field = new UISelectField("Ryhmä","wordgroupID","groupID",$registry->wordgroups, "name");
$field->setPredictive(true);
$groupdialog->addField($field);

$groupdialog->setData($registry->word);
$groupdialog->show();
*/


//-----------------------------------------------------------------------------
//   Ryhmä section
//-----------------------------------------------------------------------------

/*
$section = new UITableSection("Ryhmät","800px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);


$section->setData($registry->groups);
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $groupdialog->getID(), 'Lisää ryhmä');
$section->addButton($button);

//$section->setDeleteAction('worder/words/removewordfromgroup&wordid=' . $registry->word->wordID, 'worder/words/showword&id=' . $registry->word->wordID, 'rowID');
$section->setDeleteAction(UIComponent::ACTION_FORWARD,  'worder/words/removewordfromgroup&lang=' . $registry->language->languageID . '&wordid=' . $registry->word->wordID, 'rowID');

$column = new UISortColumn("RyhmäID", "wordgroupID", "worder/words/showword&sort=groupiID");		// tää pitäisi olla taulun sisäinen operaatio innertablella
$section->addColumn($column);

$column = new UISelectColumn("Ryhmä", "name", 'wordgroupID', $registry->wordgroups);
$section->addColumn($column);

$section->show();
*/





$managementSection = new UISection("Hallinta","800px");
$managementSection->editable(false);
$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/words/removeword&noframes=1&wordID=". $registry->word->wordID, "Poista sana");
$managementSection->addButton($button);
$managementSection->show();


?>
