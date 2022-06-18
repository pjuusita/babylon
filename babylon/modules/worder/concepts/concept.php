<?php

$concept = $registry->concept;

$width = "700px";

// ---------------------------------------------------------------------------------------------------
// Perustiedot
// ---------------------------------------------------------------------------------------------------

$conceptsection = new UISection("Concept", $width);
$conceptsection->setOpen(true);
$conceptsection->editable(true);
$conceptsection->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/concepts/updateconcept', 'conceptID');

//$conceptfield = new UIFixedTextField("KäsiteID", $concept->conceptID);
//$conceptsection->addField($conceptfield);

$field = new UITextField("Concept","name","name", $this->registry->systemlangs);
//$field->setMultiline(3);
$conceptsection->addField($field);

//$field = new UITextField("Abbreviation","abbreviation","Abbreviation");
//$conceptsection->addField($field);

$wordclassfield = new UISelectField("Sanaluokka","wordclassID","wordclassID",$registry->wordclasses, "name");
$conceptsection->addField($wordclassfield);

$field = new UITextAreaField("Selite","gloss","gloss");
$conceptsection->addField($field);

//$field = new UITextField("Selite","gloss","Gloss", $this->registry->systemlangs);
//$field->setMultiline(3);
//$conceptsection->addField($field);

//$field = new UISelectField("Parent","parentID","ParentID",array(),'name');
//$name = null;
//if ($registry->parent != null) $name = substr($registry->parent->name,3);
//$field->setPredictive(true, "worder/concepts/conceptautocomplete", $name);
//$field->setLink('worder/concepts/showconcept', 'parentID');
//$conceptsection->addField($field);

//$heredityfield = new UISelectField("Heredity","heredity","heredity",$registry->heredities);
//$conceptsection->addField($heredityfield);

//$field = new UITextField("Frequency","frequency","Frequency");
//$conceptsection->addField($field);

//$rarityfield = new UISelectField("Rarity","rarityID","RarityID",$registry->rarities, "name");
//$conceptsection->addField($rarityfield);


$selectedtypes = array();
$selectedtypes[0] = "Ei valittu";
$selectedtypes[1] = "Valittu";

//$selectedfield = new UISelectField("Selected","selected","Selected", $selectedtypes);
//$conceptsection->addField($selectedfield);


$conceptsection->setData($registry->concept);
$conceptsection->show();



// ---------------------------------------------------------------------------------------------------
// Add Component dialog
// ---------------------------------------------------------------------------------------------------

$adddefinitiondialog = new UISection('Add Definition','500px');
$adddefinitiondialog->setDialog(true);
$adddefinitiondialog->setMode(UIComponent::MODE_INSERT);
$adddefinitiondialog->setInsertAction(UIComponent::ACTION_FORWARD, "worder/concepts/insertdefinition&conceptID=" . $registry->concept->conceptID);

$field = new UISelectField("Language", "languageID", "languageID", $this->registry->languages, 'name');
$adddefinitiondialog->addField($field);

$field = new UITextField("Definition","definition","definition");
$adddefinitiondialog->addField($field);

$field = new UISelectField("Source","sourceID","sourceID",$registry->sources, "name");
$adddefinitiondialog->addField($field);

$adddefinitiondialog->show();


// ---------------------------------------------------------------------------------------------------
// Update Definition dialog
// ---------------------------------------------------------------------------------------------------

$editdefinitiondialog = new UISection('Edit Defintion');
$editdefinitiondialog->setDialog(true);
$editdefinitiondialog->setMode(UIComponent::MODE_EDIT);
$editdefinitiondialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/concepts/updatedefinition&conceptID=' . $registry->concept->conceptID, 'definitionID');

$field = new UISelectField("Language", "languageID", "languageID", $this->registry->languages, 'name');
$editdefinitiondialog->addField($field);

$field = new UITextField("Definition","definition","definition");
$editdefinitiondialog->addField($field);

//$field = new UISelectField("Source","sourceID","sourceID",$registry->sources, "name");
//$editdefinitiondialog->addField($field);

$editdefinitiondialog->show();




// ---------------------------------------------------------------------------------------------------

$section = new UITableSection("Definitions",$width);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/concepts/removedefinition&conceptID=' . $registry->concept->conceptID, 'definitionID');
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);
$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editdefinitiondialog->getID(),"rowID");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $adddefinitiondialog->getID(), 'Add Definition');
$section->addButton($button);

$column = new UISortColumn("DefinitionID", "definitionID", "definitionID");
$section->addColumn($column);

$column = new UISelectColumn("Language", "name", "languageID", $registry->languages);
$section->addColumn($column);

$column = new UISortColumn("Definition", "definition");
$section->addColumn($column);

$section->setData($registry->definitions);
$section->show();




function parentSearchDiv() {
    
    global $registry;
    
    echo "	<table style='width:100%'>";
    echo "		<tr>";
    //echo "			<td style='padding-right:5px;'>";
    /*
     echo "				<select id=parentlanguagefield class=field-select style='width:100%'>";
     foreach($registry->languages as $index => $language) {
     echo "<option value='" . $language->languageID . "'>" . $language->name . "</option>";
     }
     echo "				</select>";
     */
    //echo " 			</td>";
    
    echo "			<td colspan=2 style='padding-right:5px;'>";
    echo "				<input class=uitextfield  id=searchparentfield type='text' style='width:100%;' type='text' value=''>";
    echo " 			</td>";
    
    echo "			<td style='padding-right:5px'>";
    echo "				<div>";
    echo "					<button  class=section-button  onclick='searchparentbuttonpressed()'>Etsi</button>";
    echo "				</div>";
    echo "			</td>";
    
    echo "	<script>";
    echo "		$('#searchparentfield').keypress(function (e) {";
    echo "			if (e.keyCode == 13) {";
    echo "				searchparentbuttonpressed();";
    echo "			};";
    echo "		})";
    echo "	</script>";
    
    /*
     echo "	<script>";
     echo "	$('#filterselect_". $this->getID() ."_" . $selectID . "').keyup(function(e){";
     echo "		if(e.keyCode == 13)";
     echo "		{";
     //echo "			value = $('#filterselect_". $this->getID() ."_" . $selectID . "').val();";
     //echo "			alert('value - " . $this->urlparams[$selectID] . " - '+value);";
     echo "			value = $('#filterselect_". $this->getID() ."_" . $selectID . "').val();";
     echo "			window.location = '" . getUrl($action) . "&" . $this->urlparams[$selectID] . "='+value";
     echo "		}";
     echo "	});";
     */
    
    echo "		</tr>";
    echo "		<tr>";
    echo "			<td colspan=3>";
    
    echo "				<div id=searchparentloadingdiv style='display:none;height:100%;width:100%;'>";
    echo "					<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
    echo "				</div>";
    
    echo "				<div id=searchparentloadeddiv style='display:none;height:100%;width:100%;overflow:hidden'>";
    //echo "					<div style='overflow-y:scroll;max-height:200px;'>";
    //echo "				<div id=resultpanel style='width:100%;height:50px;background-color:pink;'>result</div>";
    echo "					<table id=searchparentresulttable style='width:100%;height:50px;table-layout:fixed'>";
    echo "						<tr><td>Empty</td></tr>";
    echo "					</table>";
    //echo "					</div>";
    echo "				</div>";
    echo "			</td>";
    echo "		</tr>";
    echo "	</table>";
    
    echo "	<script>";
    echo "		function addParentItem(conceptID) {";
    echo "			window.location = '" . getUrl("worder/concepts/addparenttoconcept") . "&conceptID=" . $registry->concept->conceptID . "&parentID='+conceptID;";
    echo "		}";
    echo "	</script>";
    
    
    
    echo "	<script>";
    echo "		function searchparentbuttonpressed() {";
    //echo "			console.log('search button pressed');";
    echo "			var search = $('#searchparentfield').val();";
    echo "			if (search == '') {";
    echo "				alert('ei saa olla tyhjä 1');";
    echo "				return;";
    echo "			}";
    echo "			$('#searchparentloadingdiv').show();";
    echo "			$('#searchparentloadeddiv').hide();";
    //echo "			var languageID = $('#parentlanguagefield').val();";
    //echo "			console.log('languageid -'+languageID+'-');";
    echo "			console.log('" . getUrl('worder/concepts/searchconcept') . "&search='+search);";
    
    echo "			$.getJSON('" . getUrl('worder/concepts/searchconcept') . "&search='+search,'',function(data) {";
    //echo "					console.log('data.length - '+data.length);";
    echo "					$('#searchparentloadingdiv').hide();";
    echo "					$('#searchparentloadeddiv').show();";
    echo "					$('#searchparentresulttable tr').remove();";
    echo "					$.each(data, function(index) {";
    //echo "						console.log('row - '+data[index].conceptID+' - '+data[index].name);";
    echo "						var row = '<tr>'";
    echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
    echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordclassID+'</td>'";
    echo "							+ '<td style=\"padding-right:10px;\">'+data[index].conceptID+'</td>'";
    //echo "							+ '<td title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/words/showword') . "&id='+data[index].wordID+'\">'+data[index].name+'</a></td>'";
    echo "							+ '<td style=\"width:330px;overflow:hidden;white-space: nowrap\" title=\''+data[index].gloss+'\'>'+data[index].name+'</td>'";
    echo "							+ '<td><button onclick=\"addParentItem(\''+data[index].conceptID+'\')\">lisää</button></td>'";
    echo "							+ '</tr>';";
    echo "						$('#searchparentresulttable').append(row);";
    echo "					});";
    echo "			}); ";
    ////echo " 			console.log('finish');";
    echo "		}";
    echo "	</script>";
}



$addparentdialog = new UISection("Etsi parent","600px");
$addparentdialog->setDialog(true);
$addparentdialog->setMode(UIComponent::MODE_INSERT);
////$addparentdialog->setInsertAction(UIComponent::ACTION_FORWARD, "worder/concepts/addcomponent&conceptID=" . $registry->concept->conceptID);

$addparentdialog->setCustomContent('parentSearchDiv');
$addparentdialog->show();




$section = new UITreeSection("Hierarchy",$width);
$section->setOpen(true);
$section->setCollapse(true);		// Kaikki treen solmut ovat auki
$section->editable(true);
$section->setFramesVisible(true);
//$section->setTableHeaderVisible(false);
//$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/concepts/showconcept','conceptID');


$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/concepts/removeparent&conceptID=' . $registry->concept->conceptID, 'conceptID');
$section->setDeleteActiveParam('removepossible');


$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addparentdialog->getID(), 'Add parent');
$section->addButton($button);

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addchilddialog->getID(), 'Add child');
//$section->addButton($button);

//$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/concepts/removecomponent&conceptID=' . $registry->concept->conceptID, 0);

$column = new UISortColumn("Nimi", "name", "worder/groups/showgrouplist&sort=nimi");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$section->addColumn($column);

$column = new UISortColumn("ConceptID", "conceptID", "worder/groups/showgrouplist");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);


$section->setData($registry->hierarchy);
$section->show();




// ---------------------------------------------------------------------------------------------------
// Add Component dialog
// ---------------------------------------------------------------------------------------------------

$addcomponentdialog = new UISection('Add Component','500px');
$addcomponentdialog->setDialog(true);
$addcomponentdialog->setMode(UIComponent::MODE_INSERT);
$addcomponentdialog->setInsertAction(UIComponent::ACTION_FORWARD, "worder/concepts/addcomponent&conceptID=" . $registry->concept->conceptID);

$field = new UISelectField("Component","componentID","componentID",$registry->components, "name");
$field->setPredictable(true);
$addcomponentdialog->addField($field);

$field = new UISelectField("Inheritance","rowID","inheritancemodeID",$registry->inheritancemodes, "name");
$addcomponentdialog->addField($field);

$addcomponentdialog->show();



// ---------------------------------------------------------------------------------------------------

$section = new UITableSection("Components",$width);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
//$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/concepts/removecomponent&conceptID=' . $registry->concept->conceptID, array(1 => 'lang', 3 => 'id' ));
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/concepts/removecomponent&conceptID=' . $registry->concept->conceptID, array(3 => 'componentID', 4=> 'sourceconceptID' ));
$section->setDeleteActiveParam(5);
//$section->setTableHeaderVisible(false);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addcomponentdialog->getID(), 'Add component');
$section->addButton($button);

/*
 $column = new UISelectColumn("Component", "name", "componentID", $this->registry->components);
 $section->addColumn($column);
 
 $column = new UISelectColumn("Mode", "name", "inheritancemodeID", $this->registry->inheritancemodes);
 $section->addColumn($column);
 
 $column = new UISelectColumn("From", null, "fromconceptID", $this->registry->allparents);
 $section->addColumn($column);
 */

$column = new UISimpleColumn("Component", 0);
$section->addColumn($column);

$column = new UISimpleColumn("Mode", 1);
$section->addColumn($column);

$column = new UISimpleColumn("From", 2);
$section->addColumn($column);

$section->setData($registry->conceptcomponents);
$section->show();





// ---------------------------------------------------------------------------------------------------
// Add description dialog
// ---------------------------------------------------------------------------------------------------

//echo "<br>argumentgroups - " . count($registry->argumentgroups);

$addargumentdialog = new UISection('Add argument22','500px');
$addargumentdialog->setDialog(true);
$addargumentdialog->setMode(UIComponent::MODE_INSERT);
$addargumentdialog->setInsertAction(UIComponent::ACTION_FORWARD, "worder/concepts/addargument&conceptID=" . $registry->concept->conceptID);
//$addargumentdialog->setInsertAction(UIComponent::ACTION_FORWARD, "worder/concepts/insertargument&conceptID=" . $registry->concept->conceptID);

if ($registry->concept->wordclassID == 0) {
    $wordclassfield = new UISelectField("Wordclass","wordclassID","wordclassID", $registry->wordclasses, "name");
    $wordclassfield->setOnChange("wordclasschanged()");
    $addargumentdialog->addField($wordclassfield);
    
    $argumentfield = new UISelectField("Argument","argumentID","argumentID", $registry->arguments, "name");
    $argumentfield->setDisabled(true);
    $addargumentdialog->addField($argumentfield);
    $inheritancefielddisabled = true;
} else {
    
    $wordclassfield = new UISelectField("Wordclass","wordclassID","wordclassID", $registry->wordclasses, "name");
    $wordclassfield->setOnChange("wordclasschanged()");
    $addargumentdialog->addField($wordclassfield);
    
    $wordclassarguments = array();
    foreach($registry->arguments as $index => $argument) {
        if ($argument->wordclassID == $registry->concept->wordclassID) {
            $wordclassarguments[$argument->argumentID] = $argument;
        }
    }
    $argumentfield = new UISelectField("Argument","argumentID","argumentID", $wordclassarguments, "name");
    $addargumentdialog->addField($argumentfield);
    $inheritancefielddisabled = false;
}


$componentfield = new UISelectField("Component","componentID","componentID",$registry->components, "name");
//$componentfield->setDisabled(true);
$componentfield->setPredictable(true);
$addargumentdialog->addField($componentfield);

$inheritancefield = new UISelectField("Inheritance","rowID","inheritancemodeID",$registry->inheritancemodes, "name");
if ($inheritancefielddisabled == true) $inheritancefield->setDisabled(true);
$addargumentdialog->addField($inheritancefield);

$row = new Row();
$row->wordclassID = $registry->concept->wordclassID;

$addargumentdialog->setData($row);
$addargumentdialog->show();


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

/*
 echo "	 		var componentfieldID 		 	 = '#".$componentfield->getEditFieldID()."';";
 echo "			$(componentfieldID).attr('disabled', 'disabled');";
 echo "			$(componentfieldID).addClass('uitextfield-disabled');";
 echo "			$(componentfieldID).removeClass('uitextfield');";
 */

echo "	 		var inheritancefieldID 		 	 = '#".$inheritancefield->getEditFieldID()."';";
echo "			$(inheritancefieldID).attr('disabled', 'disabled');";
echo "			$(inheritancefieldID).addClass('uitextfield-disabled');";
echo "			$(inheritancefieldID).removeClass('uitextfield');";

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

/*
 echo "	 		var componentfieldID 		 	 = '#".$componentfield->getEditFieldID()."';";
 echo "			$(componentfieldID).removeAttr('disabled');";
 echo "			$(componentfieldID).addClass('uitextfield');";
 echo "			$(componentfieldID).removeClass('uitextfield-disabled');";
 */

echo "	 		var inheritancefieldID 		 	 = '#".$inheritancefield->getEditFieldID()."';";
echo "			$(inheritancefieldID).removeAttr('disabled');";
echo "			$(inheritancefieldID).addClass('uitextfield');";
echo "			$(inheritancefieldID).removeClass('uitextfield-disabled');";


echo "			$.each(data, function(index) {";
echo "				console.log(' - index - '+index+' - '+data[index]);";
echo "				$(argumentfieldID).append($('<option>', {value:index, text:data[index]}));";
echo "			});";
echo "		}); ";




echo "	}";
echo "</script>";




$editargumentdialog = new UISection('Update argument','500px');
$editargumentdialog->setDialog(true);
$editargumentdialog->setMode(UIComponent::MODE_EDIT);
$editargumentdialog->setInsertAction(UIComponent::ACTION_FORWARD, "worder/concepts/updateargument&conceptID=" . $registry->concept->conceptID);

$field = new UISelectField("Argument","argumentID","argumentID",$registry->arguments, "name");
$editargumentdialog->addField($field);

$field = new UISelectField("Component44","componentID","componentID",$registry->components, "name");
$editargumentdialog->addField($field);

//$field = new UISelectField("Connectivity","connectivity","connectivity",$registry->connectivity, "name");
//$editargumentdialog->addField($field);

//$field = new UISelectField("Argumentgroup","argumentgroupID","argumentgroupID",$registry->argumentgroups, 1);
//$editargumentdialog->addField($field);

$editargumentdialog->show();



function conceptListDiv() {
    
    global $registry;
    
    
    echo "	<table style='width:100%'>";
    
    echo "		<tr>";
    echo "			<td colspan=3 style='height:8px;'>";
    echo "		</tr>";
    
    echo "		<tr>";
    echo "			<td colspan=3 style='border-top:2px solid;border-color:#ccc;height:6px;'>";
    echo "		</tr>";
    
    echo "		<tr>";
    echo "			<td colspan=2>";
    
    echo "				<div id=conceptlistloadingdiv style='display:none;height:100%;width:100%;'>";
    echo "					<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
    echo "				</div>";
    
    echo "				<div id=conceptlistloadeddiv style='display:none;height:100%;width:470px;overflow:hidden'>";
    echo "					<div style='overflow-y:scroll;max-height:200px;width:470px;'>";
    echo "					<table id=conceptlisttable style='width:450px;height:50px;table-layout:fixed;'>";
    echo "						<tr><td>Empty</td></tr>";
    echo "					</table>";
    echo "					</div>";
    echo "				</div>";
    echo "			</td>";
    echo "		</tr>";
    
    echo " 	<tr>";
    echo " 		<td class=field-text></td>";
    echo " 		<td class='iu-middle-block field-value' style='text-align:right;'><button onclick=\"closeConceptLinkDialog()\">Sulje</button></td>";
    echo " </tr>";
    
    
    echo "	</table>";
    
    
}


$showargumentdialog = new UISection('Argumenttiin sopivat käsitteet','500px');
$showargumentdialog->setDialog(true);
$showargumentdialog->setFramesVisible(true);
$showargumentdialog->setMode(UIComponent::MODE_EDIT);

$showargumentdialog->setCustomContent('conceptListDiv');

$showargumentdialog->show();


echo "<script>";
echo "		function closeConceptLinkDialog() {";
echo "  		$('#sectiondialog-" . $showargumentdialog->getID() . "').dialog('close');";
echo "		};";
echo "	</script>";




echo "	<script>";
echo "		function setValue_" . $showargumentdialog->getID(). "(fieldname, value) {";
echo "			console.log('setting value - ' + fieldname + '='+value);";

echo "			if (fieldname == '5') {";
echo "				console.log('get items with component - '+value);";
echo "			}";

echo "					console.log('" . getUrl('worder/concepts/getconceptswithcomponentJSON') . "&componentID='+value);";
echo "					$.getJSON('" . getUrl('worder/concepts/getconceptswithcomponentJSON') . "&componentID='+value,'',function(data) {";

echo "						$('#conceptlistloadingdiv').hide();";
echo "						$('#conceptlistloadeddiv').show();";
echo "						$('#conceptlisttable').empty();";
echo "						$.each(data, function(index) {";
echo "							console.log('data - '+data[index].name);";

echo "							if (data[index].name == '') {";
echo "								console.log(' - ei conceptstringiä - '+data[index].conceptID);";
echo "							}";

echo "							var row = '<tr>'";
echo "								+ '<td style=\"padding-right:10px;width:30px;\">'+data[index].conceptID+'</td>'";
echo "								+ '<td style=\"padding-right:10px;width:140px;\">'+data[index].wordclassID+'</td>'";
echo "								+ '<td style=\"padding-right:10px;width:250px;\">'+data[index].name+'</td>'";
//echo "								+ '<td style=\"padding-right:10px;\">'+data[index].duedate+'</td>'";
//echo "								+ '<td style=\"padding-right:10px;\">'+data[index].grossamount+'</td>'";
//echo "								+ '<td style=\"width:300px;overflow:hidden;\" title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/concepts/showconcept') . "&id='+data[index].conceptID+'\">'+data[index].name+'</a></td>'";
//echo "								+ '<td><button onclick=\"linksalesInvoice(\''+rowID+'\',\''+data[index].invoiceID+'\')\">kohdista</button></td>'";
echo "								+ '</tr>';";
echo "							$('#conceptlisttable').append(row);";
echo "						});";
echo "					}); ";
echo "					console.log('end');";

echo "		}";
echo "	</script>";



function exampleListDiv() {
    
    global $registry;
    
    
    echo "	<table style='width:100%'>";
    
    echo "		<tr>";
    echo "			<td colspan=3 style='height:8px;'>";
    echo "		</tr>";
    
    echo "		<tr>";
    echo "			<td colspan=3 style='border-top:2px solid;border-color:#ccc;height:6px;'>";
    echo "		</tr>";
    
    echo "		<tr>";
    echo "			<td colspan=2>";
    
    echo "				<div id=conceptlistloadingdiv style='display:none;height:100%;width:100%;'>";
    echo "					<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
    echo "				</div>";
    
    echo "				<div id=conceptlistloadeddiv style='display:none;height:100%;width:470px;overflow:hidden'>";
    echo "					<div style='overflow-y:scroll;max-height:200px;width:470px;'>";
    echo "					<table id=conceptlisttable style='width:450px;height:50px;table-layout:fixed;'>";
    echo "						<tr><td>Empty</td></tr>";
    echo "					</table>";
    echo "					</div>";
    echo "				</div>";
    echo "			</td>";
    echo "		</tr>";
    
    echo " 	<tr>";
    echo " 		<td class=field-text></td>";
    echo " 		<td class='iu-middle-block field-value' style='text-align:right;'><button class='section-button' onclick=\"closeExampleListDialog()\">Sulje</button></td>";
    echo " </tr>";
    
    
    echo "	</table>";
}





/*
 echo "<script>";
 echo "		function exampleListDiv() {";
 echo "			alert('jeejee');";
 //echo "  		$('#sectiondialog-" . $showargumentdialog->getID() . "').dialog('close');";
 echo "		};";
 echo "	</script>";
 */



$examplegeneratordialog = new UISection('Esimerkki generoinnit','500px');
$examplegeneratordialog->setDialog(true);
$examplegeneratordialog->setFramesVisible(true);
$examplegeneratordialog->setMode(UIComponent::MODE_EDIT);

$examplegeneratordialog->setCustomContent('exampleListDiv');

$examplegeneratordialog->show();


echo "<script>";
echo "		function closeExampleListDialog() {";
echo "  		$('#sectiondialog-" . $examplegeneratordialog->getID() . "').dialog('close');";
echo "		};";
echo "	</script>";




echo "	<script>";
echo "		function setValue_" . $examplegeneratordialog->getID(). "(fieldname, value) {";
echo "			console.log('setting value - ' + fieldname + '='+value);";

/*
 echo "			if (fieldname == '5') {";
 echo "				console.log('get items with component - '+value);";
 echo "			}";
 
 echo "					console.log('" . getUrl('worder/concepts/getconceptswithcomponentJSON') . "&componentID='+value);";
 echo "					$.getJSON('" . getUrl('worder/concepts/getconceptswithcomponentJSON') . "&componentID='+value,'',function(data) {";
 
 echo "						$('#conceptlistloadingdiv').hide();";
 echo "						$('#conceptlistloadeddiv').show();";
 echo "						$('#conceptlisttable').empty();";
 echo "						$.each(data, function(index) {";
 echo "							console.log('data - '+data[index].name);";
 
 echo "							if (data[index].name == '') {";
 echo "								console.log(' - ei conceptstringiä - '+data[index].conceptID);";
 echo "							}";
 
 echo "							var row = '<tr>'";
 echo "								+ '<td style=\"padding-right:10px;width:30px;\">'+data[index].conceptID+'</td>'";
 echo "								+ '<td style=\"padding-right:10px;width:140px;\">'+data[index].wordclassID+'</td>'";
 echo "								+ '<td style=\"padding-right:10px;width:250px;\">'+data[index].name+'</td>'";
 //echo "								+ '<td style=\"padding-right:10px;\">'+data[index].duedate+'</td>'";
 //echo "								+ '<td style=\"padding-right:10px;\">'+data[index].grossamount+'</td>'";
 //echo "								+ '<td style=\"width:300px;overflow:hidden;\" title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/concepts/showconcept') . "&id='+data[index].conceptID+'\">'+data[index].name+'</a></td>'";
 //echo "								+ '<td><button onclick=\"linksalesInvoice(\''+rowID+'\',\''+data[index].invoiceID+'\')\">kohdista</button></td>'";
 echo "								+ '</tr>';";
 echo "							$('#conceptlisttable').append(row);";
 echo "						});";
 echo "					}); ";
 echo "					console.log('end');";
 */
echo "		}";
echo "	</script>";


echo "	<script>";
echo "		function loadExampleList(itemID) {";
//echo "			$('#fsfss')";
echo "			console.log('loadExampleList painettu - '+itemID);";

/*
 echo "			console.log('" . getUrl('worder/concepts/getconceptswithcomponentJSON') . "&componentID='+value);";
 echo "			$.getJSON('" . getUrl('worder/concepts/getconceptswithcomponentJSON') . "&ruleID=" . $registry->concept->conceptID . "','',function(data) {";
 
 echo "				console.log('data - ddd');";
 echo "				$('#conceptlistloadingdiv').hide();";
 echo "				$('#conceptlistloadeddiv').show();";
 echo "				$('#conceptlisttable').empty();";
 echo "				$.each(data, function(index) {";
 echo "					console.log('data - '+data[index].name);";
 
 echo "					if (data[index].name == '') {";
 echo "						console.log(' - ei conceptstringiä - '+data[index].conceptID);";
 echo "					}";
 
 echo "					var row = '<tr>'";
 echo "						+ '<td style=\"padding-right:10px;width:30px;\">'+data[index].conceptID+'</td>'";
 echo "						+ '<td style=\"padding-right:10px;width:140px;\">'+data[index].wordclassID+'</td>'";
 echo "						+ '<td style=\"padding-right:10px;width:250px;\">'+data[index].name+'</td>'";
 //echo "						+ '<td style=\"padding-right:10px;\">'+data[index].duedate+'</td>'";
 //echo "						+ '<td style=\"padding-right:10px;\">'+data[index].grossamount+'</td>'";
 //echo "						+ '<td style=\"width:300px;overflow:hidden;\" title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/concepts/showconcept') . "&id='+data[index].conceptID+'\">'+data[index].name+'</a></td>'";
 //echo "						+ '<td><button onclick=\"linksalesInvoice(\''+rowID+'\',\''+data[index].invoiceID+'\')\">kohdista</button></td>'";
 echo "						+ '</tr>';";
 echo "					$('#conceptlisttable').append(row);";
 echo "				});";
 echo "		}); ";
 */


echo "			console.log('end');";
echo "		}";
echo "	</script>";


$section = new UITableSection("Argument constraints",$width);
$section->setOpen(true);
$section->editable(true);
$section->setMode(UIComponent::MODE_EDIT);
$section->setFramesVisible(true);
//$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addargumentdialog->getID(), 'Add argument');
$section->addButton($button);

// Mikähän tän loadexamplelistin tarkoitus on ollut?
//$button = new UIButton(UIComponent::ACTION_JAVASCRIPT, "loadExampleList", "Show examples");
//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $examplegeneratordialog->getID(), 'Show examples');
//$section->addButton($button);

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/concepts/removeargument&conceptID=' . $registry->concept->conceptID, array(5 => 'componentID', 4=> 'argumentID' ));
$section->setDeleteActiveParam(6);

$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $showargumentdialog->getID(),5);


//$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/concepts/removeargument&conceptID=' . $registry->concept->conceptID, 'rowID');
//$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/groups/showgroup','wordgroupID');
//$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $showsection->getID(),"componentID");

$column = new UISimpleColumn("Argument", 0);
$section->addColumn($column);

//$column = new UISimpleColumn("Wordclass", 7);
//$section->addColumn($column);

$column = new UISimpleColumn("Component", 1);
$section->addColumn($column);

$column = new UISimpleColumn("Mode", 3);
$section->addColumn($column);

$column = new UISimpleColumn("From", 2);
$section->addColumn($column);

$column = new UIHiddenColumn("ComponentID", 5);
$section->addColumn($column);

$section->setData($registry->conceptarguments);
$section->show();







// ---------------------------------------------------------------------------------------------------
// Sanat
// ---------------------------------------------------------------------------------------------------

/*
 $section = new UITableSection("Translations","600px");
 $section->setOpen(true);
 $section->editable(true);
 $section->setFramesVisible(true);
 $section->setTableHeaderVisible(false);
 $section->setButtonAlign(UIComponent::VALIGN_BOTTOM);
 
 
 $section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/concepts/removedefaultword&conceptID=' . $registry->concept->conceptID, array(1 => 'lang', 3 => 'id' ));
 $section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/words/showword', array(1 => 'lang', 3 => 'id' ));
 
 $selectedwords = array();
 foreach($this->registry->languages as $index => $language) {
 
 // 	/echo "<br>test - " . $language->name . " - " . $language->active;
 
 if ($language->active == 1) {
 $wordline = array();
 $wordcolumn = "finnish_word";
 $wordline[0] = $language->name;
 $wordline[1] = $language->languageID;
 
 //echo "<br>test - " . $language->name . " - " . $registry->concept->english_wordID . " - " . $registry->concept->english_word;
 //echo "<br>test - " . $wordIDcolumn;
 //echo "<br>test - " . $wordcolumn;
 
 
 if ($registry->concept->$wordIDcolumn == null) {
 //echo "<br>" . $language->name . " - null";
 $wordline[2] = "";
 $wordline[3] = 0;
 } else {
 //echo "<br>" . $language->name . " - " . $registry->concept->$wordIDcolumn . " - " . $registry->concept->$wordcolumn;
 $wordline[2] = $registry->concept->$wordcolumn;
 $wordline[3] = $registry->concept->$wordIDcolumn;
 }
 
 $selectedwords[] = $wordline;
 }
 }
 
 
 
 $column = new UISimpleColumn("Kieli", 0);
 $section->addColumn($column);
 
 $column = new UISimpleColumn("Sana", 2);
 $section->addColumn($column);
 
 
 $section->setData($selectedwords);
 $section->show();
 */




// ---------------------------------------------------------------------------------------------------
// Add comment dialog
// ---------------------------------------------------------------------------------------------------

$addcommentdialog = new UISection('Add comment','500px');
$addcommentdialog->setDialog(true);
$addcommentdialog->setMode(UIComponent::MODE_INSERT);
$addcommentdialog->setInsertAction(UIComponent::ACTION_FORWARD, "worder/concepts/insertcomment&conceptID=" . $registry->concept->conceptID);

$field = new UITextField("Kommentti", "comment", 'comment');
$addcommentdialog->addField($field);

$addcommentdialog->show();



// tämän voisi siirtää utils.js-tiedostoon, suomenkieliset sanat pitäisi muuttaa multilangiksi tms.
echo "<script>";
echo "		function ConfirmDialog(message, func, params) {";
echo "			$('<div></div>').appendTo('body')";
echo "			.html('<div>' + message + '</div>')";
echo "			.dialog({";
echo "				modal: true,";
echo "				title: 'Tuplasanan varoitus',";
echo "				zIndex: 10000,";
echo "				autoOpen: true,";
echo "				width: 'auto',";
echo "				resizable: false,";
echo "				buttons: {";
echo "					Kyllä: function() {";
echo "						$(this).dialog('close');";
echo "						console.log('para2m - ' + params.length);";

echo "						window[func](params);";
echo "					},";
echo "					Peruuta: function() {";
echo "						$(this).dialog('close');";
echo "					}";
echo "				},";

echo "				close: function(event, ui) {";
echo "					$(this).remove();";
echo "				}";
echo "			});";
echo "		}";
echo "	</script>";


$searchworddialog = new UISection("Etsi sana","600px");
$searchworddialog->setDialog(true);
$searchworddialog->setMode(UIComponent::MODE_INSERT);
////$addparentdialog->setInsertAction(UIComponent::ACTION_FORWARD, "worder/concepts/addcomponent&conceptID=" . $registry->concept->conceptID);

$searchworddialog->setCustomContent('wordSearchDiv2');
$searchworddialog->show();

$_SESSION['global_sectionID'] = $searchworddialog->getID();


function wordSearchDiv2() {
    
    global $registry;
    $sectionID = $_SESSION['global_sectionID'];
    
    
    echo "	<table style='width:100%'>";
    echo "		<tr>";
    echo "			<td class=field-text style='width:150px;'>Kieli</td>";
    echo "			<td style='width:250px;'>";
    echo "				<select id=searchwordlanguage class=field-select style='width:200px;'>";
    echo "					<option value='0' selected></option>";
    foreach ($registry->languages as $index => $language) {
        if ($registry->defaultlanguageID == $language->languageID) {
            echo "				<option selected='selected' value=" . $language->languageID . ">" . $language->name . "</option>";
        } else {
            echo "				<option value=" . $language->languageID . ">" . $language->name . "</option>";
        }
    }
    echo "				</select>";
    echo " 			</td>";
    echo "			<td id='messagefield-zz-1 style='width:100px;'></td>";
    echo "		</tr>";
    
    
    echo "		<tr>";
    echo "			<td class=field-text style='width:150px;'>Name</td>";
    echo "			<td style='padding-right:5px;'>";
    echo "				<input class=uitextfield  id=searchwordfield type='text' style='width:100%;' type='text' value=''>";
    echo " 			</td>";
    echo "			<td style='padding-right:5px'>";
    echo "				<div>";
    echo "					<button  class=section-button  onclick='searchwordbuttonpressed()'>Etsi</button>";
    echo "				</div>";
    echo "			</td>";
    echo "		</tr>";
    
    echo "		<tr>";
    echo "			<td colspan=3 style='height:10px;'></td>";
    echo "		</tr>";
    
    echo "		<tr>";
    echo "			<td class=field-text style='width:150px;'></td>";
    echo "			<td style='padding-right:5px;'>";
    echo " 			</td>";
    echo "			<td style='padding-right:5px;text-align:right;'>";
    echo "				<div>";
    echo "					<button  class=section-button  onclick='closeSearchWordDialog()'>Peruuta</button>";
    echo "				</div>";
    echo "			</td>";
    echo "		</tr>";
    
    
    echo "	<script>";
    echo "		$('#searchwordfield').keypress(function (e) {";
    echo "			if (e.keyCode == 13) {";
    echo "				searchwordbuttonpressed();";
    echo "			};";
    echo "		})";
    echo "	</script>";
    
    echo "		<tr>";
    echo "			<td colspan=3>";
    echo "				<div id=searchwordloadingdiv style='display:none;height:100%;width:100%;'>";
    echo "					<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
    echo "				</div>";
    echo "				<div id=searchwordloadeddiv style='display:none;height:100%;width:100%;overflow:hidden'>";
    echo "					<table id=searchwordresulttable style='width:100%;height:50px;table-layout:fixed'>";
    echo "						<tr><td>Empty</td></tr>";
    echo "					</table>";
    echo "				</div>";
    echo "			</td>";
    echo "		</tr>";
    echo "	</table>";
    
    
    echo "	<script>";
    echo "		function addWordItem(languageID, wordID) {";
    echo "			window.location = '" . getUrl("worder/concepts/insertword") . "&conceptID=" . $registry->concept->conceptID . "&languageID='+languageID+'&wordID='+wordID;";
    echo "		}";
    echo "	</script>";
    
    
    echo "	<script>";
    echo "		function insertWordItem(languageID, word) {";
    echo "			var languageID = $('#searchwordlanguage').val();";
    echo "			window.location = '" . getUrl("worder/concepts/insertnewword") . "&conceptID=" . $registry->concept->conceptID . "&word='+word+'&languageID='+languageID;";
    echo "		}";
    echo "	</script>";
    
    
    echo "	<script>";
    echo "		function closeSearchWordDialog() {";
    //echo "			alert('close window - " . $sectionID . "');";
    echo "  		$('#sectiondialog-" . $sectionID . "').dialog('close');";
    //echo "			window.location = '" . getUrl("worder/concepts/insertnewword") . "&conceptID=" . $registry->concept->conceptID . "&word='+word+'&languageID='+languageID;";
    echo "		}";
    echo "	</script>";
    
    
    echo "	<script>";
    echo "		function searchwordbuttonpressed() {";
    echo "			var search = $('#searchwordfield').val();";
    echo "			var languageID = $('#searchwordlanguage').val();";
    
    echo "			if (search == '') {";
    echo "				alert('ei saa olla tyhjä 1');";
    echo "				return;";
    echo "			}";
    echo "			$('#searchwordloadingdiv').show();";
    echo "			$('#searchwordloadeddiv').hide();";
    //echo "			var languageID = $('#parentlanguagefield').val();";
    //echo "			console.log('languageid -'+languageID+'-');";
    echo "			console.log('" . getUrl('worder/words/searchwordsJSON') . "&search='+search+'&languageID='+languageID);";
    
    echo "			$.getJSON('" . getUrl('worder/words/searchwordsJSON') . "&search='+search+'&languageID='+languageID,'',function(data) {";
    //echo "					console.log('data.length - '+data.length);";
    echo "					var languageID = $('#searchwordlanguage').val();";
    echo "					var search = $('#searchwordfield').val();";
    echo "					$('#searchwordloadingdiv').hide();";
    echo "					$('#searchwordloadeddiv').show();";
    echo "					$('#searchwordresulttable tr').remove();";
    echo "					var counter = 0;";
    echo "					$.each(data, function(index) {";
    //echo "						console.log('row - '+data[index].conceptID+' - '+data[index].name);";
    echo "						var row = '<tr>'";
    echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordclass+'</td>'";
    echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordID+'</td>'";
    //echo "							+ '<td title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/words/showword') . "&id='+data[index].wordID+'\">'+data[index].name+'</a></td>'";
    echo "							+ '<td style=\"width:330px;overflow:hidden;white-space: nowrap\" title=\''+data[index].gloss+'\'>'+data[index].name+'</td>'";
    echo "							+ '<td><button onclick=\"addWordItem(\''+languageID+'\', \''+data[index].wordID+'\')\">lisää</button></td>'";
    echo "							+ '</tr>';";
    echo "						$('#searchwordresulttable').append(row);";
    echo "						counter++;";
    echo "					});";
    
    echo "					if (counter == 0) {";
    echo "						var row = '<tr>'";
    echo "							+ '<td colspan=4>'";
    echo "							+ 'Ei yhtään löytynyt'";
    echo "							+ '</td>'";
    echo "							+ '</tr>';";
    echo "						$('#searchwordresulttable').append(row);";
    echo "					}";
    
    echo "						var row = '<tr>'";
    echo "							+ '<td colspan=4 style=\"text-align:right;\">'";
    echo "							+ '<button onclick=\"insertWordItem(\''+languageID+'\', \''+search+'\')\">Lisää sana</button>'";
    //echo "							+ '<button style=\"margin-left:3px;\" onclick=\"closeSearchWordDialog()\">Sulje</button>'";
    echo "							+ '</td>'";
    echo "							+ '</tr>';";
    echo "						$('#searchwordresulttable').append(row);";
    
    echo "			}); ";
    ////echo " 			console.log('finish');";
    echo "		}";
    echo "	</script>";
}





// ---------------------------------------------------------------------------------------------------
// Sanat
// ---------------------------------------------------------------------------------------------------

$section = new UITableSection("All connected words",$width);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setTableHeaderVisible(false);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addsection->getID(), 'Add new word');
//$section->addButton($button);

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addexistingsection->getID(), 'Add existing word');
//$section->addButton($button);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $searchworddialog->getID(), 'Etsi sana');
$section->addButton($button);

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $setselectedwordsection->getID(), 'Set selected word');
//$section->addButton($button);


$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/concepts/removewordfromconcept&conceptID=' . $registry->concept->conceptID, array(4 => 'lang', 3 => 'id' ));
$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/words/showword', array(4 => 'lang', 3 => 'id' ));


$column = new UISimpleColumn("WordID", 3);
$section->addColumn($column);

$column = new UISimpleColumn("Sanaluokka", 5);
$section->addColumn($column);

$column = new UISimpleColumn("Kieli", 0);
$section->addColumn($column);

$column = new UISimpleColumn("Sana", 1);
$section->addColumn($column);

//$column = new UISimpleColumn("LangID", 4);
//$section->addColumn($column);

$column = new UISimpleColumn("Def", 6);
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, 3, "worder/concepts/undefaultword&conceptID=" . $registry->concept->conceptID);			// huom: toinen parametri 'conceptID' pitää olla taulussa mukana, hiddeninä jos ei muuten
$column->setIcon("fa fa-check-circle");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_JAVASCRIPT, "", "linebuttonclicked");
$column->setIcon("fa fa-chevron-up");
$section->addColumn($column);


$section->setData($registry->words);
$section->show();


echo "	<script>";
echo "		function linebuttonclicked(wordID,wordclass, language,word,languageID,test3) {";
echo "			console.log('linebuttonclicked');";
echo "			console.log('---wordID: '+wordID);";
echo "			console.log('---language: '+language);";
echo "			console.log('---wordclass: '+wordclass);";
echo "			console.log('---languageID: '+languageID);";
echo "			console.log('---test3: '+test3);";
echo "			inserttranslationfrombutton(languageID,wordID);";
echo "		};";
echo "	</script>";

echo "<script>";
echo "		function inserttranslationfrombutton(lan,wordID) {";
echo "			console.log('---language: '+lan);";
echo "			console.log('---wordID: '+wordID);";

//echo "			var urli = '" . getUrl('worder/concepts/isSetToDefault') . "&languageID='+lan+'&wordID='+wordID;";
echo "			var params = [];";
echo "			params['wordID'] = wordID;";
echo "			params['languageID'] = lan;";
echo "			params['conceptID'] = " . $registry->concept->conceptID . ";";
echo "			console.log(urli);";
echo "			console.log('para3m - ' + params.length);";
echo "			linkwordfrombutton(params);";

/*
 echo "			$.getJSON(urli,params,function(reply) { ";
 
 echo "					if (reply == 1) {";
 echo "						console.log('reply - '+reply);";
 echo "						console.log('para4m - ' + params.length);";
 echo "						ConfirmDialog('Sana on linkitetty jo, linkitetäänkö tähänkin?', 'linkword', params);";
 echo "					} else {";
 echo "						linkwordfrombutton(params);";
 echo "					}";
 echo "					console.log('reply - '+reply);";
 echo "			});";
 */
echo "		};";
echo "	</script>";

echo "<script>";
echo "		function linkwordfrombutton(params) {";
echo "			console.log('linkataan');";
echo "			var urli = '" . getUrl('worder/concepts/setdefaultword') . "';";
//echo "			console.log('param - ' + params.length);";
echo "			for(var key in params) {";
echo "				urli = urli + '&' + key + '=' + params[key];";
//echo "				console.log(urli);";
echo "			};";
echo "			console.log(urli);";
echo "			window.location = urli;";
echo "		};";
echo "	</script>";

echo "<script>";
echo "		function linkword(params) {";
echo "			console.log('linkataan');";
echo "			var urli = '" . getUrl('worder/concepts/setdefaultword') . "';";
//echo "			console.log('param - ' + params.length);";
echo "			for(var key in params) {";
echo "				urli = urli + '&' + key + '=' + params[key];";
//echo "				console.log(urli);";
echo "			};";
echo "			console.log(urli);";
echo "			window.location = urli;";
echo "		};";
echo "	</script>";

// ---------------------------------------------------------------------------------------------------
// Add sentence dialog
// ---------------------------------------------------------------------------------------------------

/*
 $addsentencedialog = new UISection('Add sentence','500px');
 $addsentencedialog->setDialog(true);
 $addsentencedialog->setMode(UIComponent::MODE_INSERT);
 $addsentencedialog->setInsertAction(UIComponent::ACTION_FORWARD, "worder/concepts/insertsentence&conceptID=" . $registry->concept->conceptID);
 
 $field = new UISelectField("Language","languageID","languageID",$registry->languages, "name");
 $addsentencedialog->addField($field);
 
 $field = new UITextField("Kuvaus", "Kuvaus", 'description');
 $addsentencedialog->addField($field);
 
 $field = new UITextField("Url", "sourceurl", 'sourceurl');
 $addsentencedialog->addField($field);
 
 $addsentencedialog->show();
 */





$section = new UITableSection("Lessons",$width);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setTableHeaderVisible(false);
$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/lessons/showlesson','lessonID');

$column = new UISortColumn("#", "lessonID", 'lessonID');
$section->addColumn($column);

$column = new UIMultilangColumn("Lesson", "name", $registry->defaultlanguageID );
$section->addColumn($column);

$section->setData($registry->lessons);
$section->show();




// ---------------------------------------------------------------------------------------------------


function sentenceSearchDiv() {
    
    global $registry;
    
    echo "	<table style='width:100%'>";
    echo "		<tr>";
    
    echo "			<td style='padding-right:5px;'>";
    echo "				<select id=sentencelanguagefield class=field-select style='width:100%'>";
    foreach($registry->languages as $index => $language) {
        echo "<option value='" . $language->languageID . "'>" . $language->name . "</option>";
    }
    echo "				</select>";
    echo " 			</td>";
    
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
    echo "			window.location = '" . getUrl("worder/concepts/insertsentencetoconcept") . "&sentenceID='+sentenceID+'&conceptID=" . $registry->concept->conceptID . "';";
    echo "		}";
    echo "	</script>";
    
    
    echo "	<script>";
    echo "		function searchsentencebuttonpressed() {";
    echo "			console.log('search button pressed');";
    
    echo "			var languageID = $('#sentencelanguagefield').val();";
    echo "			var searh = $('#searchsentencefield').val();";
    echo "			if (searh == '') {";
    echo "				alert('ei saa olla tyhj� 2');";
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
    echo "					var counter = 0;";
    echo "					$.each(data, function(index) {";
    echo "						counter++;";
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



$insertsentencesection = new UISection("Lauseen lisäys");
$insertsentencesection->setDialog(true);
$insertsentencesection->setMode(UIComponent::MODE_INSERT);
$insertsentencesection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/concepts/insertexamplesentence&conceptID=' . $registry->concept->conceptID);

$field = new UISelectField("Language", "languageID", "languageID", $this->registry->languages, 'name');
$insertsentencesection->addField($field);

$field = new UITextField("Lause", "sentence", 'sentence');
$insertsentencesection->addField($field);

$insertsentencesection->show();



$section = new UITableSection("Lauseet",$width);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setTableHeaderVisible(false);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/concepts/removesentencefromconcept&conceptID=' . $registry->concept->conceptID, 'sentenceID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsentencesection->getID(), 'Lisää lause');
$section->addButton($button);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $searchsection->getID(), 'Etsi lause');
$section->addButton($button);

$column = new UISortColumn("SentenceID", "sentenceID", "sentenceID");
$section->addColumn($column);

$column = new UISelectColumn("Kieli", "name", "languageID", $registry->languages);
$section->addColumn($column);

$column = new UISortColumn("Lause", "sentence", "sentence");
$section->addColumn($column);

$section->setData($registry->sentences);
$section->show();



// ---------------------------------------------------------------------------------------------------

$section = new UITableSection("Comments",$width);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setTableHeaderVisible(false);
$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addcommentdialog->getID(), 'Add comment');
$section->addButton($button);

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/concepts/removecomment&conceptID=' . $registry->concept->conceptID, 'sentenceID');

// Tähän ehkä edit action, ACTION_DIALOG?
//$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/groups/showgroup','wordgroupID');

$column = new UISortColumn("Comment", "comment", 'comment');
$section->addColumn($column);

//$column = new UISortColumn("Url", "sourceurl", 'sourceurl');
//$section->addColumn($column);

$section->setData($registry->comments);
$section->show();






// ---------------------------------------------------------------------------------------------------
// Hallinta
// ---------------------------------------------------------------------------------------------------

$managementSection = new UISection("Hallinta", $width);
$managementSection->editable(false);
$managementSection->setDebug(true);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/concepts/removeconcept&id=".$registry->concept->getID(), "Poista käsite");
$managementSection->addButton($button);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/concepts/activateconcept&id=".$registry->concept->getID(), "Aktivoi käsite");
$managementSection->addButton($button);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/concepts/deactivateconcept&id=".$registry->concept->getID(), "Deaktivoi käsite");
$managementSection->addButton($button);

$managementSection->show();




?>