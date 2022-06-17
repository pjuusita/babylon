<?php

echo "<a href='".getUrl('worder/rules/showrulesets')."'>Palaa RuleSets-listalle</a><br>";
echo "<h1>RuleSet: " . $this->registry->ruleset->name . "</h1>";

$sentencesection = new UISection("Ruleset","600px");
$sentencesection->setOpen(true);
$sentencesection->editable(true);
$sentencesection->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/rules/updateruleset', 'setID');

$field = new UIFixedTextField("SetID", $registry->ruleset->setID);
$sentencesection->addField($field);

if ($registry->ruleset->sentencesetID > 0) {
	
	$sentencesection->editable(false);
	
	$field = new UIFixedTextField("Name", $registry->ruleset->name);
	$sentencesection->addField($field);

	$language = $this->registry->languages[$registry->ruleset->languageID];
	$field = new UIFixedTextField("Language", $language->name);
	$sentencesection->addField($field);
	
	// TODO: tämä voitaisiin muuttaa linkiksi...
	$field = new UIFixedTextField("SentencesetID",$registry->ruleset->sentencesetID);
	$sentencesection->addField($field);
	
} else {
	$field = new UITextField("Name","name","name");
	$sentencesection->addField($field);

	$field = new UISelectField("Language", "languageID", "languageID", $this->registry->languages, 'name');
	$sentencesection->addField($field);

	//$field = new UITextField("SentencesetID","sentencesetID","sentencesetID");
	//$sentencesection->addField($field);
	$field = new UIFixedTextField("SentencesetID",$registry->ruleset->sentencesetID);
	$sentencesection->addField($field);
	
}



$sentencesection->setData($registry->ruleset);
$sentencesection->show();


$title = "Rules";
$rulesdialog = new UISection($title,'600px');
$rulesdialog->setMode(UIComponent::MODE_INSERT);
$rulesdialog->setCustomContent('rulesDiv');
$rulesdialog->show();



function rulesDiv() {

	global $registry;


	$maxlevel = 5;
	
	echo "	<table style='width:100%'>";

	echo "		<tr>";
	for($counter = 0;$counter < $maxlevel; $counter++) {
		echo "			<td style='width:30px;'>";
		echo "			</td>";
	}
	echo "			<td>";
	echo "			</td>";
	echo "		</tr>";
	
	foreach($registry->languages as $index => $language) {
		echo "		<tr>";
		echo "			<td colspan=" . $maxlevel . " style='padding-right:5px;'>";
		echo "<b>" . $language->name . "</b>";
		echo "			</td>";
		echo "		</tr>";
		foreach($registry->rules as $index => $rule) {
			if ($rule->languageID == $language->languageID) {
				if ($rule->parentID == 0) {
					echo "		<tr>";
					echo "			<td style='padding-right:5px;'>";
					echo "			</td>";
					echo "			<td style='padding-right:5px;'>";
					echo "<input type=checkbox>";
					echo "			</td>";
					echo "			<td colspan=" . ($maxlevel-1) . " style='padding-right:5px;'>";
					echo $rule->ruleID . " - " . $rule->name;
					echo "			</td>";
					echo "		</tr>";
					subrules(2, $maxlevel, $rule, $registry->rules);
				}
			}
		}
	}
	echo "	</table>";
}


echo "	<script>";
echo "		function checkboxclicked(checkbox, ruleID) {";
echo "			if (checkbox.checked == true) {";
echo "				console.log('checked - '+ruleID);";
//echo "			var url = '" .  getUrl("admin/usergroups/updatedimensionvisibility") . "&usergroupID=" . $usergroupID . "&dimensionID='+dimensionID+'&dimensionvalueID='+dimensionvalueID+'&accesslevel=1';";
echo "				$.getJSON('" . getUrl('worder/rules/checkruleJSON') . "&setID=" .  $this->registry->ruleset->setID . "&ruleID='+ruleID,'',function(data) {";
echo "					console.log('return - '+data);";
echo "				});";
//echo "			console.log('ulr - '+url);";
//echo "				window.location = url;";
echo "			} else {";
echo "				console.log('unchecked - '+ruleID);";
echo "				$.getJSON('" . getUrl('worder/rules/uncheckruleJSON') . "&setID=" .  $this->registry->ruleset->setID . "&ruleID='+ruleID,'',function(data) {";
echo "					console.log('return - '+data);";
echo "				});";
//echo "			var url = '" .  getUrl("admin/usergroups/updatedimensionvisibility") . "&usergroupID=" . $usergroupID . "&dimensionID='+dimensionID+'&dimensionvalueID='+dimensionvalueID+'&accesslevel=0';";
//echo "			console.log('ulr - '+url);";
//echo "			window.location = url;";
echo "			}";
echo "		}";
echo "	</script>";


function subrules($level, $maxlevel, $parent, $rules) {
	
	foreach($rules as $index => $rule) {
		
		if ($rule == null) {
			echo "<br>Rule null";
		}

		if ($rule->parentID == null) {
			echo "<br>ParentID null - " . $rule->ruleID;
		}
		
		if ($rule->parentID == $parent->ruleID) {
			echo "		<tr>";
			for($counter = 0;$counter < $level; $counter++) {
				echo "			<td style='padding-right:5px;'>";
				echo "			</td>";
			}
			echo "			<td style='padding-right:5px;'>";
			if ($rule->selected == 1) {
				echo "<input type=checkbox checked onchange='checkboxclicked(this, " . $rule->ruleID . ")'>";
			} else {
				echo "<input type=checkbox onchange='checkboxclicked(this, " . $rule->ruleID . ")'>";
			}
			echo "			</td>";
			echo "			<td colspan=" . ($maxlevel-$level) . " style='padding-right:5px;'>";
			if ($rule->status == 0) {
				echo $rule->ruleID . " - " . $rule->name . " (disabled)";
			} else {
				echo $rule->ruleID . " - " . $rule->name;
			}
			echo "			</td>";
			echo "		</tr>";
			
			if ($level < $maxlevel) {
				subrules($level + 1, $maxlevel, $rule, $rules);
			}
		}
	}
}


$managementSection = new UISection("Hallinta","600px");
$managementSection->editable(false);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/rules/removeruleset&setID=". $registry->ruleset->setID, "Poista setti");
$managementSection->addButton($button);

$managementSection->show();


?>