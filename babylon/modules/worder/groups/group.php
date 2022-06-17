<?php



// ---------------------------------------------------------------------------------------------------
// Lisää käsite dialog
// ---------------------------------------------------------------------------------------------------


/*
$insertsection = new UISection('Käsitteen lisäys','500px');
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);

//$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/groups/insertgroup');

$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/groups/insertconcept&groupid=' . $registry->group->wordgroupID);

$field = new UISelectField("Käsite","conceptID","conceptID",$registry->allconcepts, "name");
$field->setPredictive(true);
$insertsection->addField($field);

$insertsection->show();
*/


echo "<br><br>";

$section = new UISection("Ryhmä - " . $registry->group->name,"600px");
$section->setOpen(true);
$section->editable(true);
$section->setData($registry->group);

$section->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/groups/updategroup', 'wordgroupID');

$field = new UITextField("Name","name","Name");
$section->addField($field);

$field = new UITextField("Description","description","Description");
$section->addField($field);

$field = new UISelectField("Grouptype","grouptypeID","GrouptypeID",$registry->grouptypes, 'name');
$section->addField($field);

$field = new UISelectField("Language","languageID","LanguageID",$registry->languageselection, 'name');
$section->addField($field);


$section->show();


function groupSearchDiv() {
	
	global $registry;
	
	echo "	<table style='width:100%'>";
	echo "					<tr>";
	echo "						<td style='padding-right:5px;'>";
	echo "<input class=uitextfield  id=searchfield type='text' style='width:100%;' type='text' value=''>";
	echo " 						</td>";
	
	echo "					<td style='padding-right:5px'>";
	echo "						<div>";
	echo "							<button  class=section-button  onclick='searchbuttonpressed()'>Etsi</button>";
	echo "						</div>";
	echo "					</td>";
		
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
	echo "		function addItem(conceptID) {";
	echo "			window.location = '" . getUrl("worder/groups/insertconcept") . "&groupid=" . $registry->group->wordgroupID . "&conceptID='+conceptID;";
	echo "		}";
	echo "	</script>";
	
	
	
	echo "	<script>";
	echo "		function searchbuttonpressed() {";
	//echo "			console.log('search button pressed');";
	echo "			var searh = $('#searchfield').val();";
	echo "			if (searh == '') {";
	echo "				alert('ei saa olla tyhjä');";
	echo "			}";
	echo "			$('#searchloadingdiv').show();";
	echo "			$('#searchloadeddiv').hide();";
	
	echo "			$.getJSON('" . getUrl('worder/groups/searchwords') . "&search='+searh,'',function(data) {";
	//echo "					console.log('data.length - '+data.length);";
	echo "					$('#searchloadingdiv').hide();";
	echo "					$('#searchloadeddiv').show();";
	echo "					$('#searchresulttable tr').remove();";
	echo "					$.each(data, function(index) {";
	//echo "						console.log('row - '+data[index].conceptID+' - '+data[index].name);";
	echo "						var row = '<tr>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordclassID+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].conceptID+'</td>'";
	echo "							+ '<td title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/concepts/showconcept') . "&id='+data[index].conceptID+'\">'+data[index].name+'</a></td>'";
	echo "							+ '<td><button onclick=\"addItem(\''+data[index].conceptID+'\')\">lisää</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#searchresulttable').append(row);";
	echo "					});";
	echo "			}); ";
	//echo " 			console.log('finish');";
	echo "		}";
	echo "	</script>";
}



/*
$addargumentdialog = new UISection('Add argument','500px');
$addargumentdialog->setDialog(true);
$addargumentdialog->setMode(UIComponent::MODE_INSERT);
$addargumentdialog->setInsertAction(UIComponent::ACTION_FORWARD, "worder/groups/insertargument&wordgroupID=" . $registry->group->wordgroupID);

$field = new UISelectField("Role","roleID","roleID",$registry->roles, "name");
$addargumentdialog->addField($field);

$field = new UISelectField("Wordgroup","wordgroupID","targetgroupID",$registry->groups, "name");
$addargumentdialog->addField($field);

$addargumentdialog->show();
*/

/*
$editargumentdialog = new UISection('Update argument','500px');
$editargumentdialog->setDialog(true);
$editargumentdialog->setMode(UIComponent::MODE_EDIT);
$editargumentdialog->setInsertAction(UIComponent::ACTION_FORWARD, "worder/groups/updateargument&wordgroupID=" . $registry->group->wordgroupID);

$field = new UISelectField("Role","roleID","roleID",$registry->roles, "name");
$editargumentdialog->addField($field);

$field = new UISelectField("Wordgroup","wordgroupID","targetgroupID",$registry->groups, "name");
$editargumentdialog->addField($field);

$editargumentdialog->show();
*/

/*
 
// Toistaiseksi poistettu, arguments siirretty conceptiin
$section = new UITableSection("Arguments","600px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addargumentdialog->getID(), 'Add argument');
$section->addButton($button);

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/concepts/removegroup&conceptID=' . $registry->group->wordgroupID, 'wordgroupID');
$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/groups/showgroup','wordgroupID');

$column = new UISelectColumn("RoleID","name","roleID",$registry->roles);
$section->addColumn($column);

$column = new UISelectColumn("TargetgroupID","name","targetgroupID",$registry->groups);
$section->addColumn($column);

$section->setData($registry->grouparguments);
$section->show();
*/


$section = new UITableSection("Parent groups - " . count($registry->parentgroups),"600px");
$section->setOpen(true);
$section->editable(true);
//$section->setSortable(UIComponent::ACTION_FORWARD, 'worder/groups/sortconcept&groupid='. $registry->group->wordgroupID, 0);
$section->setFramesVisible(true);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/concepts/showconcept', 'wordgroupID');

$column = new UISortColumn("Ryhmä", "name", 'name');
$section->addColumn($column);

$section->setData($registry->parentgroups);
$section->setShowTotal(true);

$section->show();



$section = new UITableSection("Käsitteet","600px");
$section->setOpen(true);
$section->editable(true);
$section->setSortable(UIComponent::ACTION_FORWARD, 'worder/groups/sortconcept&groupid='. $registry->group->wordgroupID, 0);
$section->setFramesVisible(true);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/concepts/showconcept', 0);


$column = new UISimpleColumn("KäsiteID", 0);
$section->addColumn($column);


$column = new UISimpleColumn("Sanaluokka", 1);
$section->addColumn($column);

$column = new UISimpleColumn("Käsite", 2);
$section->addColumn($column);

//$wordcolumn = new UIMultilangColumn("Käsite", "Name", 2);
//$section->addColumn($wordcolumn);

$column = new UISimpleColumn("Word", 4);
$section->addColumn($column);


$column = new UISimpleColumn("Freq", 3);
$section->addColumn($column);


$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, 0, "worder/groups/moveconcept&dir=up&groupid=" . $registry->group->wordgroupID);			// huom: toinen parametri 'conceptID' pitää olla taulussa mukana, hiddeninä jos ei muuten
$column->setIcon("fa fa-chevron-up");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, 0, "worder/groups/moveconcept&dir=down&groupid=" . $registry->group->wordgroupID, "5%");		// huom: toinen parametri 'conceptID' pitää olla taulussa mukana, hiddeninä jos ei muuten
$column->setIcon("fa fa-chevron-down");
$section->addColumn($column);

//$section->setDeleteAction(UIComponent::ACTION_FORWARD,  "worder/groups/removeconceptfromgroup&groupid=" . $registry->group->wordgroupID, "0");

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, 0, "worder/groups/removeconceptfromgroup&groupid=" . $registry->group->wordgroupID, "5%");		// huom: toinen parametri 'conceptID' pitää olla taulussa mukana, hiddeninä jos ei muuten
$column->setIcon("fa fa-ban");
$section->addColumn($column);


$section->setData($registry->concepts);
$section->setShowTotal(true);


$section->show();





$section = new UISection("Etsi käsite","600px");
$section->setOpen(true);

$section->setCustomContent('groupSearchDiv');
$section->show();


foreach($registry->languages as $index => $language) {

	//echo "<br>**********************************";
	//echo "<br>Language - " . $language->name;
	
	//$words = $this->registry->languagewords[$language->languageID];
	//foreach($words as $index => $word) {
		//echo "<br> ... " . $word[0] . " .. " . $word[1] . " .. " . $word[2] . " .. " . $word[3] . " .. " . $word[4] . " .. ";
	//}
	
	
	
	// TODO: tämä sectioni pitäisi muottaa load on demand
	
	
	
	
	$section = new UITableSection("Sanat " . $language->name,"600px");
	$section->showLineNumbers(true);
	$section->setOpen(true);
	$section->editable(true);
	$section->setFramesVisible(true);
	$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/words/showword&lang=' . $language->languageID,2);
	
	
	$column = new UISimpleColumn("KäsiteID", 0);
	$section->addColumn($column);
	
	$column = new UISimpleColumn("Käsite", 1);
	$section->addColumn($column);
	
	$column = new UISimpleColumn("WordID", 2);
	$section->addColumn($column);
	
	$column = new UISimpleColumn("Sana", 3);
	$section->addColumn($column);

	$column = new UISimpleColumn("Freq", 4);
	$section->addColumn($column);

	$column = new UISimpleColumn("Sort", 5);
	$section->addColumn($column);
	
	$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, 2, "worder/groups/moveword&dir=up&groupid=" . $registry->group->wordgroupID . "&languageid=" . $language->languageID);			// huom: toinen parametri 'conceptID' pitää olla taulussa mukana, hiddeninä jos ei muuten
	$column->setIcon("fa fa-chevron-up");
	$section->addColumn($column);
	
	$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, 2, "worder/groups/moveword&dir=down&groupid=" . $registry->group->wordgroupID . "&languageid=" . $language->languageID, "5%");		// huom: toinen parametri 'conceptID' pitää olla taulussa mukana, hiddeninä jos ei muuten
	$column->setIcon("fa fa-chevron-down");
	$section->addColumn($column);
	
	$section->setDeleteAction(UIComponent::ACTION_FORWARD,  "worder/groups/removewordfromgroup&groupid=" . $registry->group->wordgroupID . "&languageid=" . $language->languageID , "6");
	
	$section->setData($registry->languagewords[$language->languageID]);
	$section->setDataSource("worder/groups/loadlanguagewordsJSON&lang=" . $language->languageID . "&groupid=" . $registry->group->wordgroupID );
	$section->show();
	
	/*
	// tällä näytetään alkuperäinen valmiiksi ladattu words taulu, conrollerista loadwords trueksi
	$section = new UITableSection("Sanat normal " . $language->name,"600px");
	$section->showLineNumbers(true);
	$section->setOpen(true);
	$section->editable(true);
	$section->setFramesVisible(true);
	$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/words/showword&lang=' . $language->languageID,2);
	
	
	$column = new UISimpleColumn("KäsiteID", 0);
	$section->addColumn($column);
	
	$column = new UISimpleColumn("Käsite", 1);
	$section->addColumn($column);
	
	$column = new UISimpleColumn("WordID", 2);
	$section->addColumn($column);
	
	$column = new UISimpleColumn("Sana", 3);
	$section->addColumn($column);
	
	$column = new UISimpleColumn("Freq", 4);
	$section->addColumn($column);
	
	$column = new UISimpleColumn("Sort", 5);
	$section->addColumn($column);
	
	$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, 2, "worder/groups/moveword&dir=up&groupid=" . $registry->group->wordgroupID . "&languageid=" . $language->languageID);			// huom: toinen parametri 'conceptID' pitää olla taulussa mukana, hiddeninä jos ei muuten
	$column->setIcon("fa fa-chevron-up");
	$section->addColumn($column);
	
	$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, 2, "worder/groups/moveword&dir=down&groupid=" . $registry->group->wordgroupID . "&languageid=" . $language->languageID, "5%");		// huom: toinen parametri 'conceptID' pitää olla taulussa mukana, hiddeninä jos ei muuten
	$column->setIcon("fa fa-chevron-down");
	$section->addColumn($column);
	
	$section->setDeleteAction(UIComponent::ACTION_FORWARD,  "worder/groups/removewordfromgroup&groupid=" . $registry->group->wordgroupID . "&languageid=" . $language->languageID , "6");
	
	$section->setData($registry->languagewords[$language->languageID]);
	//$section->setDataSource("worder/groups/loadlanguagewordsJSON&languageid=1&groupid=197");
	$section->show();
	*/
}


/*
$section = new UITableSection("Käsitteet","600px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);

//$section->setLineAction(UIComponent::ACTION_FORWARD, "worder/concepts/showconcept", "conceptID");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Lisää käsite');
$section->addButton($button);

$column = new UISortColumn("KäsiteID", "conceptID", "worder/wordclasses/showlanguages&sort=languageID", "10%");
$section->addColumn($column);

$column = new UISortColumn("Käsite", "name", 'name');
$section->addColumn($column);

$column = new UISortColumn("Freq", "frequency", 'frequency', "10%");
$section->addColumn($column);


$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "conceptID", "worder/groups/moveconcept&dir=up&groupid=" . $registry->group->wordgroupID);			// huom: toinen parametri 'conceptID' pitää olla taulussa mukana, hiddeninä jos ei muuten
$column->setIcon("fa fa-chevron-up");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "conceptID", "worder/groups/moveconcept&dir=down&groupid=" . $registry->group->wordgroupID, "5%");		// huom: toinen parametri 'conceptID' pitää olla taulussa mukana, hiddeninä jos ei muuten
$column->setIcon("fa fa-chevron-down");
$section->addColumn($column);
$section->setDeleteAction(UIComponent::ACTION_FORWARD,  "worder/groups/removeconcept&groupid=" . $registry->group->wordgroupID, "conceptID");

$section->setData($registry->concepts);
$section->show();
*/



$section = new UITableSection("Logi","600px");
$section->setFramesVisible(true);

$column = new UISortColumn("KäsiteID", "conceptID", "worder/wordclasses/showlanguages&sort=languageID", "10%");
$section->addColumn($column);

$column = new UISortColumn("Käsite", "name", 'name');
$section->addColumn($column);

$column = new UISortColumn("Freq", "frequency", 'frequency', "10%");
$section->addColumn($column);

// TODO: setDataSource -funktio hoitaa taman toiminnon. Tämä kutsu on joko tupla tai kesken jäänyt toteutus
$section->onOpen(UIComponent::ACTION_LOAD, "worder/groups/loadlog&id=" . $registry->group->getID());
$section->show();




$managementSection = new UISection("Hallinta","600px");
$managementSection->editable(false);
$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/groups/removegroup&id=".$registry->group->getID() ,'Poista ryhmä');
$managementSection->addButton($button);
$managementSection->show();




?>