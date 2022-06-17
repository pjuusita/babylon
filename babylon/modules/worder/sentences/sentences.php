<?php


// Tämä on versio sentencesetin sanoista, jossa ei ole rulesetkohtaista checkkiä otettu huomioon.
// putodusvalikossa on ainoastaan yksi ruleset, joten molempien kielten ruleset pitää sisältää myös
// target kielen säännöt. Tästä tehdään toinen versio, jossa on target setit kaikille aktiivisille 
// kielille erikseen, ja check voidaan tehdä sitten ruleset kohtaisesti.


//echo "<br>LanguageID - " . $this->registry->languageID;
$languageID = $this->registry->languageID;


echo "<table style='width:1200px;'>";

echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:100px;text-align:right;'>";
echo "		</td>";
echo "		<td style='width:200px;text-align:right;'>";
echo "		</td>";
echo "	</tr>";

echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
//$paginator->show();
echo "		</td>";
echo "		<td colspan=2 style='text-align:right;'>";
$filterbox = new UIFilterBox("304px");
$filterbox->addSelectFilter($this->registry->languageID, $this->registry->languages, "worder/sentences/showsentences", "","languageID", "name");
$filterbox->show();
echo "		</td>";
echo "	</tr>";


$analyserulesets = array();
foreach($registry->rulesets as $rulesetID => $ruleset) {
	if ($ruleset->languageID == $this->registry->languageID) {
		$analyserulesets[$ruleset->setID] = $ruleset;
	}
}


echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'></td>";
echo "		<td colspan=2 style='text-align:right;'>";
$filterbox = new UIFilterBox("304px");
$filterbox->addSelectFilter($this->registry->rulesetID, $analyserulesets, "worder/sentences/showsentences", "","rulesetID", "name");
$filterbox->show();
echo "		</td>";
echo "	</tr>";


echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
//$paginator->show();
echo "		</td>";
echo "		<td colspan=2 style='text-align:right;'>";
$filterbox = new UIFilterBox("304px");
$filterbox->addSelectFilter($this->registry->setID, $this->registry->sets, "worder/sentences/showsentences", "","setID", "name");
$filterbox->show();
echo "		</td>";
echo "	</tr>";

$activeruleset = array();

foreach($registry->activelanguages as $index => $languageID) {
	
	$language = $registry->languages[$languageID];
	
	echo "	<tr>";
	echo "		<td style='width:60%;vertical-align:bottom'>";
	//$paginator->show();
	echo "		</td>";
	echo "		<td style='width:40%;text-align:right;'>";
	echo "			<select class=field-select style='width:100px;height:30px;' disabled>";
	echo "				<option value='" . $language->name . "'>" . $language->name . "</option>";
	echo "			</select>";
	//$filterbox = new UIFilterBox();
	//$filterbox->addSelectFilter($this->registry->rulesetID, $this->registry->rulesets, "worder/sentences/showsentences", "","rulesetID", "name");
	//$filterbox->show();
	echo "		</td>";
	echo "		<td style='width:60%;vertical-align:bottom'>";
	
	$activerulesetID = $registry->activerulesets[$index];
	
	$languagerulesets = array();
	foreach($registry->rulesets as $rulesetID => $ruleset) {
		if ($ruleset->languageID == $languageID) {
			$languagerulesets[$ruleset->setID] = $ruleset;
		}
	}
	$activeruleset[$languageID] = $activerulesetID;
	$filterbox = new UIFilterBox();
	$filterbox->addSelectFilter($activerulesetID, $languagerulesets, "worder/sentences/showsentences", "","rulesetID-" . $languageID, "name");
	$filterbox->show();
	echo "		</td>";
	echo "	</tr>";
	
}



echo "</table>";





$languagessection = new UISection('Kielten näkyvyys','500px');
$languagessection->setDialog(true);
$languagessection->setMode(UIComponent::MODE_EDIT);
$languagessection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/lessons/updateactivelanguages');

$row = new Row();

foreach($this->registry->languages as $index => $language) {

	$field = new UIBooleanField($language->name, 'language-' . $language->languageID, 'language-' . $language->languageID);
	$languagessection->addField($field);
	if (isset($this->registry->activelanguages[$language->languageID])) {
		$var = 'language-' . $language->languageID;
		$row->$var = 0;
	}
}

foreach($this->registry->activelanguages as $index => $languageID) {
	$var = 'language-' . $languageID;
	$row->$var = 1;
}
$languagessection->setData($row);
$languagessection->show();



// ---------------------------------------------------------------------------------------------------
// Add sentence dialog
// ---------------------------------------------------------------------------------------------------

$insertsection = new UISection('Add sentence','500px');
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
//$insertsection->setInsertAction(UIComponent::ACTION_FORWARD, "worder/sentences/insertsentence&lang="+$registry->languageID);

$insertsection->setInsertAction(UIComponent::ACTION_FORWARD, "worder/sentences/insertsentence&source=sentences&languageID=" . $this->registry->languageID . "&setID=" . $this->registry->setID);

$field = new UITextField("Lause", "sentence", 'sentence');
$insertsection->addField($field);

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
$insertsection->addField($field);

$insertsection->show();



/*
$insertsetsection = new UISection('Add Sentence Set','500px');
$insertsetsection->setDialog(true);
$insertsetsection->setMode(UIComponent::MODE_INSERT);
//$insertsection->setInsertAction(UIComponent::ACTION_FORWARD, "worder/sentences/insertsentence&lang="+$registry->languageID);

$insertsetsection->setInsertAction(UIComponent::ACTION_FORWARD, "worder/sentences/insertsentenceset&languageID=" . $this->registry->languageID);

$field = new UITextField("Name", "name", 'name');
$insertsetsection->addField($field);

$insertsetsection->show();
*/





function sentenceSearchDiv() {

	global $registry;

	echo "	<table style='width:100%'>";

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
	echo "		function addSentence(sentenceID) {";
	//echo "			window.location = '" . getUrl("worder/sentences/addexistingsentence") . "&sentenceID='+sentenceID+'&languageID=" . $registry->languageID . "&setID=" . $registry->set->setID . "';";
	echo "		}";
	echo "	</script>";


	echo "	<script>";
	echo "		function searchsentencebuttonpressed() {";
	echo "			console.log('search button pressed');";

	echo "			var languageID = " . $registry->languageID . ";";
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




// ---------------------------------------------------------------------------------------------------
// Taulukko
// ---------------------------------------------------------------------------------------------------


// Poistettu toistaiseksi käytöstä tarpeettomana, ei toteutettu tai vanha toteutus
//$button3 = new UIButton(UIComponent::ACTION_FORWARD, "worder/sentences/unchecksetsentences&setID=" . $this->registry->setID, 'Uncheck All');

$button1 = new UIButton(UIComponent::ACTION_OPENDIALOG, $searchsection->getID(), 'Search sentence');

$button2 = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), 'Add sentence');

$settingsbutton = new UIButton(UIComponent::ACTION_OPENDIALOG, $languagessection->getID(), "");
$settingsbutton->setIcon('fa-cog fa-lg');
					
echo "<table class='listtable' id='sectiontable1' style='width:1200px;padding:0px 0px 0px 0px;margin:0px;'>";
echo " 	<tr class='listtable-row'>";
echo "		<td style='padding-left:10px;padding-top:4px;max-width:300;width:400;text-align:right;'>";
//$button3->show();
//echo "<div style='width:2px;'></div>";
$button1->show();
$button2->show();
$settingsbutton->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";

//var_dump($this->registry->activelanguages);

echo "<table class='listtable' id='sectiontable1' style='width:1200px;padding:0px 0px 0px 0px;margin:0px;'>";
echo " 	<tr class='listtable-row'>";
echo "		<td class='listtable-header'  style='width:60px;'>ID</td>";
echo "		<td class='listtable-header'  style='width:250px;'>Sentence</td>";
foreach($this->registry->activelanguages as $index => $languageID) {
	//echo "<br>LanguageID - " . $languageID;
	$language = $this->registry->languages[$languageID];
	echo "		<td class='listtable-header'  style='width:250px;'>" . $language->name . "</td>";
	//echo "		<td class='listtable-header'  style=''></td>";
	echo "		<td class='listtable-header'  style='width:250px;'></td>";
	echo "		<td class='listtable-header'  style='width:50px;'></td>";
	echo "		<td class='listtable-header'  style='width:50px;'></td>";	
	echo "		<td class='listtable-header'  style='width:50px;'></td>";
}

/*
foreach($this->registry->activelanguages as $index => $languageID) {
	$language = $this->registry->languages[$languageID];
	echo "		<td class='listtable-header'  style='width:70px;'></td>";
	echo "		<td class='listtable-header'  style='width:70px;'></td>";
	//echo "		<td class='listtable-header'  style='width:80px;'></td>";
}
*/

echo "	</tr>";
echo "<tbody id='sectiontbody1'>";

$rowNumber = 0;
foreach($this->registry->sentences as $index => $sentence) {
	$rowNumber++;
	$pointerclass = "cursor:pointer;";
	$trclass = "listtable-evenrow";
	if ($rowNumber % 2 == 0) $trclass = "listtable-oddrow";
	echo " 	<tr onclick='rowclick_1_".$rowNumber."()' id='tablerow-1-".$rowNumber."' class='" . $trclass . "' style='" . $pointerclass . "'>";
		
	echo "<script>";
	echo "	function rowclick_1_".$rowNumber."() {";
	echo "		console.log('lineaction aa');";
	echo "		loadpage('worder/sentences/showsentence&id=" . $sentence->sentenceID . "','sentencetitle');";
	echo "	}";
	echo "</script>";
		
	echo "<td  id='sentenceidrow-" . $rowNumber . "' style='padding-left:10px;padding-top:0px;width:100px;'>";
	echo "" . $sentence->sentenceID;
	echo "</td>";	

	if ($sentence->correctness == 1) {
		echo "<td style='padding-left:10px;padding-top:0px;width:300px;'>";
		echo "" . $sentence->sentence;
		echo "</td>";
	} else {
		echo "<td style='background-color:pink;padding-left:10px;padding-top:0px;width:300px;'>";
		echo "" . $sentence->sentence;
		echo "</td>";
	}
	
	foreach($this->registry->activelanguages as $index => $languageID) {
		$language = $this->registry->languages[$languageID];
		echo "<td id='resultrow-" . $languageID . "-" . $rowNumber . "' style='padding-left:10px;padding-top:0px;width:200px;'>";
		echo "</td>";
		if ($sentence->correctness == 1) {
			//echo "<td id='rightresultrow-" . $languageID . "-" . $rowNumber . "' style='padding-left:10px;padding-top:0px;width:200px;'>1";
			echo "<input type=hidden id='rightresultrow-" . $languageID . "-" . $rowNumber . "' value='1'>";
			//echo "</td>";
			if ($languageID == $this->registry->languageID) {
				echo "<td id='rightresultstrrow-" . $languageID . "-" . $rowNumber . "' style='padding-left:10px;padding-top:0px;width:200px;'>";
				echo "" . $sentence->sentence;
				echo "</td>";
			} else {
				echo "<td id='rightresultstrrow-" . $languageID . "-" . $rowNumber . "' style='padding-left:10px;padding-top:0px;width:200px;'>";
				$var = "sentence" . $languageID;
				if ($sentence->$var != null) {
					echo $sentence->$var;
				} else {
					echo "--";
				}
				echo "</td>";
			}
		} else {
			echo "<input type=hidden id='rightresultrow-" . $languageID . "-" . $rowNumber . "' value='0'>";
			//echo "<td id='rightresultrow-" . $languageID . "-" . $rowNumber . "' style='padding-left:10px;padding-top:0px;width:200px;'>0";
			//echo "</td>";
			echo "<td id='rightresultstrrow-" . $languageID . "-" . $rowNumber . "' style='padding-left:10px;padding-top:0px;width:200px;'>";
			echo "</td>";
				
		}
		echo "</td>";
		
		echo "<td style='padding-left:2px;padding-top:0px;width:50px;'>";
		$language = $this->registry->languages[$languageID];
		echo "		<button class=section-button style='margin-right:2px;margin-top:2px;padding-top:0px;padding-left:5px;width:50px;height:22px;font-size:12px;white-space:nowrap;' OnClick=\"buttonpressed_" . $language->languageID . "(event," . $language->languageID . "," . $sentence->sentenceID . "," . $rowNumber . ")\">";
		echo "" . $language->shortname . "";
		echo "		</button>";
		echo "</td>";
		
		echo "<td style='padding-left:2px;padding-top:0px;width:50px;'>";
		$language = $this->registry->languages[$languageID];
		echo "		<button class=section-button style='margin-right:2px;margin-top:2px;padding-top:0px;padding-left:5px;width:50px;height:22px;font-size:12px;white-space:nowrap;' OnClick=\"translatebuttonpressed_" . $language->languageID . "(event," . $language->languageID . "," . $sentence->sentenceID . ")\">";
		echo "" . $language->shortname . "";
		echo "		</button>";
		echo "</td>";
		
		if (($sentence->checkdate != "") && ($sentence->checkdate != "0000-00-00 00:00:00")) {
			
			$var = 'color'.$languageID;

			if (isset($sentence->$var)) {
				$color = "background-color:" . $sentence->$var;
			} else {
				$color = "";
			}
			
			
			echo "<td id='checkrow-" .$rowNumber . "' style='padding-left:2px;padding-top:0px;;width:55px;text-align:center;'>";
			echo "check1";
			echo "</td>";
		
		} else {
			
			$var = 'color'.$languageID;
			$checked = 0;
			
			if ($sentence->$var != "") {
				$color = "background-color:" . $sentence->$var;
				$checked = 1;
			} else {
				$color = "";
			}
				
			echo "<td id='checkrow-" .$rowNumber . "' style='padding-left:2px;padding-top:0px;width:55px;text-align:center;'>";
			echo "		<button id=checkbutton-" . $languageID . "-" . $rowNumber . " class=section-button style='" . $color  .";margin-right:2px;margin-top:2px;padding-top:0px;padding-left:5px;width:50px;height:22px;font-size:12px;white-space:nowrap;' OnClick=\"chekbuttonpressed_" . $language->languageID . "(event," . $language->languageID . "," . $sentence->sentenceID . "," . $rowNumber . ", " . $checked . ")\">";
			echo "check";
			echo "		</button>";
			echo "</td>";
		}
		
	}
	
	/*
	foreach($this->registry->activelanguages as $index => $languageID) {
		echo "<td style='padding-left:2px;padding-top:0px;width:60px;'>";
		$language = $this->registry->languages[$languageID];
		echo "		<button class=section-button style='margin-right:2px;margin-top:2px;padding-top:0px;padding-left:5px;width:60px;height:22px;font-size:12px;white-space:nowrap;' OnClick=\"buttonpressed_" . $language->languageID . "(event," . $language->languageID . "," . $sentence->sentenceID . "," . $rowNumber . ")\">";
		echo "" . $language->shortname . "";
		echo "		</button>";
		echo "</td>";
	}
	*/
	
	/*
	foreach($this->registry->activelanguages as $index => $languageID) {
		echo "<td style='padding-left:2px;padding-top:0px;width:60px;'>";
		$language = $this->registry->languages[$languageID];
		echo "		<button class=section-button style='margin-right:2px;margin-top:2px;padding-top:0px;padding-left:5px;width:60px;height:22px;font-size:12px;white-space:nowrap;' OnClick=\"translatebuttonpressed_" . $language->languageID . "(event," . $language->languageID . "," . $sentence->sentenceID . ")\">";
		echo "-" . $language->shortname . "-";
		echo "		</button>";
		echo "</td>";
	}
	*/
	
	/*
	if (($sentence->checkdate != "") && ($sentence->checkdate != "0000-00-00 00:00:00")) {
		echo "<td id='checkrow-" .$rowNumber . "' style='padding-left:2px;padding-top:0px;;width:55px;text-align:center;'>";
		echo "check";
		echo "</td>";
		
	} else {
		echo "<td id='checkrow-" .$rowNumber . "' style='padding-left:2px;padding-top:0px;width:55px;text-align:center;'>";
		echo "		<button class=section-button style='margin-right:2px;margin-top:2px;padding-top:0px;padding-left:5px;width:50px;height:22px;font-size:12px;white-space:nowrap;' OnClick=\"chekbuttonpressed(event," . $sentence->sentenceID . "," . $rowNumber . ")\">";
		echo "check";
		echo "		</button>";
		echo "</td>";
	}
	*/
	
	echo "</tr>";
}

foreach($this->registry->activelanguages as $index => $languageID) {
	$language = $this->registry->languages[$languageID];
	echo "<script>";
	echo "		function buttonpressed_" . $language->languageID . "(event, languageID, sentenceID, rowID) {";
	echo "			event.stopPropagation();";
	
	echo "			var targetsetID = " . $activeruleset[$languageID] . ";";
	if ($this->registry->rulesetID == 0) {
		echo "			alert('Please set analyseset');";
		echo "			return;";
		//echo "			var loc = '" . getUrl("worder/sentences/translatesentenceJSON2") . "&id='+sentenceID+'&targetlanguageID='+languageID+'&rulesetID=" . $this->registry->set->rulesetID . "';";
	} else {
		echo "			var loc = '" . getUrl("worder/sentences/translatesentenceJSON2") . "&id='+sentenceID+'&targetlanguageID='+languageID+'&sourcesetID=" . $this->registry->rulesetID . "&targetsetID='+targetsetID;";
	}
	
	
	echo "			console.log('url - '+loc);";
	//echo "			var loc = '" . getUrl("worder/sentences/checksentence") . "&comments=1&id='+sentenceID;";
	//echo "			console.log(loc);";
	echo "			$.getJSON(loc,'',function(data) {";
	echo "				console.log('data.resultcount - '+data.resultcount);";
	echo "				var rowname = '#resultrow-'+languageID+'-'+rowID;";
	echo "				var str = '';";
	echo "				var first = 0;";
	echo "				if (data.resultcount == 0) {";
	echo "					console.log('no results');";
	echo "					var rightvalue =  $('#rightresultstrrow-'+languageID+'-'+rowID).html();";
	echo "					console.log(' - rightvalue - '+rightvalue);";
	echo "					if (rightvalue != '') {";
	echo "						console.log(' - - right not empty');";
	echo "						$(rowname).html('--');";
	echo "						$(rowname).title = 'empty not right';";
	echo "						$(rowname).css('background-color','pink');";
	echo "						var rightrowname =  '#rightresultstrrow-'+languageID+'-'+rowID;";
	echo "						$(rightrowname).css('background-color','pink');";
	echo "					} else {";
	echo "						$(rowname).html('--');";
	echo "						$(rowname).title = 'empty right';";
	echo "						$(rowname).css('background-color','#90EE90');";
	echo "						var rightrowname =  '#rightresultstrrow-'+languageID+'-'+rowID;";
	echo "						$(rightrowname).css('background-color','#90EE90');";
	echo "					}";
	echo "				} else {";
	echo "					$.each(data.results, function(index) {";
	echo "						if (first == 0) {";
	echo "							first = 1;";
	echo "						} else {";
	echo "						str = str + ',';";
	echo "						}";
	echo "						str = str + data.results[index].value;";
	echo "					});";
	echo "					console.log('str - '+str);";
	echo "					$(rowname).html(str);";
	echo "					var rightvalue =  $('#rightresultstrrow-'+languageID+'-'+rowID).html();";
	echo "					console.log('rightvalue - '+rightvalue);";
	echo "					if (rightvalue == '--') {";
	echo "						var sentenceID = $('#sentenceidrow-'+rowID).html();";
	echo "						console.log('sentenceID - '+sentenceID);";
	echo "						$(rowname).html(str+' <button OnClick=\"addtranslationpressed(event, '+sentenceID+'," . $languageID . ",\''+str+'\','+rowID+')\">+</button>');";
	echo "					} else {";
	echo "						if (rightvalue == str) {";
	echo "							$(rowname).css('background-color','#90EE90');";
	echo "							var rightrowname =  '#rightresultstrrow-'+languageID+'-'+rowID;";
	echo "							$(rightrowname).css('background-color','#90EE90');";
	echo "						} else {";
	echo "							$(rowname).css('background-color','pink');";
	echo "						}";
	echo "					}";
	echo "				}";
	echo "			}); ";
	echo "		};";
	echo "</script>";
}


foreach($this->registry->activelanguages as $index => $languageID) {
	$language = $this->registry->languages[$languageID];
	echo "<script>";
	echo "		function translatebuttonpressed_" . $language->languageID . "(event, languageID, sentenceID) {";
	echo "			event.stopPropagation();";
	if ($this->registry->rulesetID == 0) {
		echo "			alert('Please set analyseset');";
		echo "			return;";
		echo "			var loc = '" . getUrl("worder/sentences/translatesentenceJSON2") . "&comments=1&id='+sentenceID+'&targetlanguageID='+languageID+'&rulesetID=" . $this->registry->set->rulesetID . "';";
	} else {
		//echo "			var loc = '" . getUrl("worder/sentences/translatesentenceJSON2") . "&comments=1&id='+sentenceID+'&targetlanguageID='+languageID+'&rulesetID=" . $this->registry->rulesetID . "';";
		echo "			var targetsetID = " . $activeruleset[$languageID] . ";";
		echo "			var loc = '" . getUrl("worder/sentences/translatesentenceJSON2") . "&comments=1&id='+sentenceID+'&targetlanguageID='+languageID+'&sourcesetID=" . $this->registry->rulesetID . "&targetsetID='+targetsetID;";
	}
	echo "			window.open(loc, '_blank');";
	echo "		};";
	echo "</script>";
}



foreach($this->registry->activelanguages as $index => $languageID) {
	$language = $this->registry->languages[$languageID];
	echo "<script>";
	echo "		function chekbuttonpressed_" . $language->languageID . "(event, languageID, sentenceID, rowNumber, checked) {";
	
	echo "			event.stopPropagation();";
	echo "			var buttonid = '#checkbutton-" . $languageID . "-'+rowNumber;";
	echo "			var color = $(buttonid).css(\"background-color\");";
	//echo "			console.log('buttoncolor - '+color);";
	//echo "			if (color == 'rgb(144, 238, 144)') {";
	//echo "				console.log('yestcolor');";
	//echo "			}";
	
	if ($this->registry->rulesetID == 0) {
		echo "				alert('source set pitää olla valittu');";
		echo "				return;";
	}
	echo "			var targetsetID = " . $activeruleset[$languageID] . ";";
	echo "			if (targetsetID == 0) {";
	echo "				alert('target set pitää olla valittu');";
	echo "				return;";
	echo "			}";
	echo "			if (color == 'rgb(144, 238, 144)') {";
	echo "				var loc = '" . getUrl("worder/sentences/unchecksentence") . "&id='+sentenceID+'&targetsetID='+targetsetID+'&languageID='+languageID+'&setID=" . $this->registry->setID . "'+'&sourcesetID=" . $this->registry->rulesetID . "';";
	echo "				console.log(loc);";
	echo "				var buttonid = '#checkbutton-" . $languageID . "-'+rowNumber;";
	echo "				console.log(buttonid);";
	echo "				$.getJSON(loc,'',function(data) {";
	echo "					console.log('data.resultcount - '+data);";
	echo "					if (data == '2') {";
	echo "						$(buttonid).css('background-color','#ffffff');";
	echo "					}";
	echo "				});";
	echo "			} else {";
	echo "				var loc = '" . getUrl("worder/sentences/checksentence") . "&id='+sentenceID+'&targetsetID='+targetsetID+'&languageID='+languageID+'&setID=" . $this->registry->setID . "'+'&sourcesetID=" . $this->registry->rulesetID . "';";
	echo "				console.log(loc);";
	echo "				var buttonid = '#checkbutton-" . $languageID . "-'+rowNumber;";
	echo "				console.log(buttonid);";
	echo "				$.getJSON(loc,'',function(data) {";
	echo "					console.log('data.resultcount - '+data);";
	echo "					if (data == '1') {";
	echo "						$(buttonid).css('background-color','#90ee90');";
	echo "					}";
	echo "				});";
	echo "			}";
	echo "		};";
	echo "</script>";
}


/*
echo "<script>";
echo "		function chekbuttonpressed(event, languageID, sentenceID, rowNumber) {";
echo "			event.stopPropagation();";
echo "			var loc = '" . getUrl("worder/sentences/checksentence") . "&id='+sentenceID+'&languageID='+languageID+'&setID=" . $this->registry->rulesetID . "'+'&sourcesetID=" . $this->registry->rulesetID . "';";
echo "			console.log(loc);";

/*
echo "			$.getJSON(loc,'',function(data) {";
echo "				console.log('data.return- '+data);";
echo "				if (data == 1) {";
echo "					$('#checkrow-'+rowNumber).html('check');";
echo "				}";
echo "			}); ";
* /
echo "		};";
echo "</script>";
*/

echo "<script>";
echo "		function addtranslationpressed(event, sentenceID, languageID, sentence, rowID) {";
echo "			event.stopPropagation();";
echo "			console.log('addtranslationpressed');";
echo "			console.log(' -- sentenceID - '+sentenceID);";
echo "			console.log(' -- languageID - '+languageID);";
echo "			console.log(' -- sentence - '+sentence);";
echo "			console.log(' -- rowID- '+rowID);";

echo "			var loc = '" . getUrl("worder/sentences/insertsentenceJSON") . "&sentenceID='+sentenceID+'&languageID='+languageID+'&sentence='+sentence;";
echo "			console.log(loc);";

echo "			$.getJSON(loc,'',function(data) {";
echo "				console.log('success - '+data);";
echo "				if (data == 1) {";

echo "					var rowname = '#resultrow-'+languageID+'-'+rowID;";
echo "					$(rowname).html(sentence);";
echo "					$(rowname).css('background-color','#90EE90');";

echo "					var rightrowname =  '#rightresultstrrow-'+languageID+'-'+rowID;";
echo "					$(rightrowname).html(sentence);";
echo "					$(rightrowname).css('background-color','#90EE90');";
echo "				}";
echo "			}); ";
echo "		};";
echo "</script>";


echo "</tbody>";
echo "</table>";

echo "<br>";
echo "<br>";

?>

