<?php


echo "<a href='".getUrl('worder/features/showfeatures')."'>Palaa features listalle</a><br>";

echo "<h1>" . $registry->feature->name . "</h1>";



$section = new UISection('Feature','600px');
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/features/updatefeature', 'featureID');

if ($registry->feature->languageID != 0) {
	$languagefield = new UIFixedTextField("Language", $registry->language->name);
	$section->addField($languagefield);
} else {
	$languagefield = new UIFixedTextField("Language", "Semantic");
	$section->addField($languagefield);
}

$field = new UITextField("Name", "name", 'name');
$section->addField($field);

$field = new UITextField("Abbreviation", "abbreviation", 'abbreviation');
$section->addField($field);

$field = new UITextAreaField("Description", "description", 'description');
$section->addField($field);

$field = new UISelectField("Parent","parentID","parentID", $registry->features, "name");
$section->addField($field);

$semanticvalues = array();
if ($registry->feature->parentID == 0) {
	foreach ($registry->semanticfeatures as $index => $semfeat) {
		if ($semfeat->parentID == 0) {
			$semanticvalues[$semfeat->featureID] = $semfeat;
		}
	}
	
} else {
	$parent = $registry->features[$registry->feature->parentID];
	foreach ($registry->semanticfeatures as $index => $semfeat) {
		if ($parent->semanticlinkID == $semfeat->parentID) {
			$semanticvalues[$semfeat->featureID] = $semfeat;
		}
	}
}

$field = new UISelectField("Semanticlink","semanticlinkID","semanticlinkID", $semanticvalues, "name");
//$field = new UISelectField("Semanticlink","semanticlinkID","semanticlinkID", $registry->semanticfeatures, "name");
$section->addField($field);


if ($registry->languageID != 0) {
	
	$sharedfeatures = array();
	foreach($registry->featurelist as $index => $feature) {
		if ($feature->languageID == 0) {
			$sharedfeatures[$feature->featureID] = $feature;
		}
		$feature->sharedname = "Shared " . $feature->name;
	}
	
	$linkfield = new UISelectField("Shared Link","featureID","semanticlinkID", $sharedfeatures, "sharedname");
	$section->addField($linkfield);
}


$section->setData($registry->feature);
$section->show();




$section = new UITableSection("Words","600px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/words/showword', 'wordID');
$section->showLineNumbers(true);

//$column = new UISelectColumn("Wordclass", "name", "wordclassID", $registry->wordclasses);
//$section->addColumn($column);

$column = new UISortColumn("ID", "wordID");
$section->addColumn($column);

$column = new UISortColumn("Word", "lemma");
$section->addColumn($column);

$section->setData($registry->words);
$section->show();






$counter = 0;
if ($registry->instances != null) {
	foreach($registry->instances as $index => $instance) {
		echo "<br>" . $counter . ". " . $instance;
	}
	echo "<br>Instancecount. " . count($registry->instances);
}

echo "<br><br>Lista missä kaikkialla kyseinen feature on käytössä...<br>";
//echo "<br> - rulet (tämä on kai se tärkein)";
//echo "<br> - sanaluokat";
//echo "<br> - sanat";
//echo "<br> - periaatteessa kaikki taulut joissa featureID:tä käytetään...";

echo "<br>worder_features.parentID";
if (count($this->registry->childfeatures) == 0) {
	echo "<br> -- Ei viitteitä";
} else {
	foreach($this->registry->childfeatures as $index => $feature) {
		echo "<br> -- " . $feature->featureID . " " . $feature->name;
	}
}

echo "<br>rulefeatureconstraints.featureID";
if (count($this->registry->rulefeatureconstraints) == 0) {
	echo "<br> -- Ei viitteitä";
} else {
	foreach($this->registry->rulefeatureconstraints as $index => $row) {
		$rule = $this->registry->rules[$row->ruleID];
		echo "<br> -- " . $row->rowID . " ... featureID:" . $row->featureID . ", RuleID: " . $row->ruleID . " -- <a target='_blank' href='" . getUrl('worder/rules/showrule') . "&id=" . $row->ruleID . "'>" . $rule->name . "</a>"; 
	}
}


echo "<br>wordclassfeatures.featureID, defaultvalueID";
if (count($this->registry->wordclassfeatures) == 0) {
	echo "<br> -- Ei viitteitä";
} else {
	foreach($this->registry->wordclassfeatures as $index => $row) {
		echo "<br> -- " . $row->rowID . " - " . $row->featureID . ", defaultvalueID=" . $row->defaultvalueID . ", wordclassID=" . $row->wordclassID;
	}
}


echo "<br>rulefeatureagreements.featureID";
if (count($this->registry->rulefeatureagreements) == 0) {
	echo "<br> -- Ei viitteitä";
} else {
	foreach($this->registry->rulefeatureagreements as $index => $row) {
		echo "<br> -- " . $row->rowID . " " . $row->featureID . ", ruleID=" . $row->ruleID;
	}
}


echo "<br>ruleresultfeatures - featureID";
if (count($this->registry->ruleresultfeatures) == 0) {
	echo "<br> -- Ei viitteitä";
} else {
	foreach($this->registry->ruleresultfeatures as $index => $row) {
		echo "<br> -- " . $row->rowID . " " . $row->featureID . ", ruleID=" . $row->ruleID;
	}
}


echo "<br>wordfeaturelinks - featureID, valueID";
if (count($this->registry->wordfeaturelinks) == 0) {
	echo "<br> -- Ei viitteitä";
} else {
	foreach($this->registry->wordfeaturelinks as $index => $row) {
		echo "<br> -- " . $row->rowID . " ... wordID:" . $row->wordID . ", valueID=" . $row->valueID . ", wordclassID:" . $row->wordclassID;
	}
}


echo "<br>ruleunsets - featureID";
if (count($this->registry->ruleunsets) == 0) {
	echo "<br> -- Ei viitteitä";
} else {
	foreach($this->registry->ruleunsets as $index => $row) {
		echo "<br> -- " . $row->rowID . " ... ruleID:" . $row->ruleID . ", argumentID=" . $row->argumentID;
	}
}


echo "<br>inflectionsetitems - featureID, parentfeatureID (features not implemented)";
if (count($this->registry->inflectionsetitems) == 0) {
	echo "<br> -- Ei viitteitä";
} else {
	foreach($this->registry->inflectionsetitems as $index => $row) {
		echo "<br> -- " . $row->rowID . " ... wordclassID:" . $row->wordclassID . ", inflectionsetID=" . $row->inflectionsetID;
	}
}


echo "<br>objective generate features - featureID, valueID";
if (count($this->registry->generatefeatures) == 0) {
	echo "<br> -- Ei viitteitä";
} else {
	foreach($this->registry->generatefeatures as $index => $row) {
		echo "<br> -- ObjectiveID: " . $row->objectiveID . " ... featureID:" . $row->featureID . ", valueID=" . $row->valueID;
	}
}


echo "<br>semantic links - semanticlinkID";
if (count($this->registry->semanticlinks) == 0) {
	echo "<br> -- Ei viitteitä";
} else {
	foreach($this->registry->semanticlinks as $index => $row) {
		echo "<br> -- " . $row->rowID . " ... featureID:" . $row->featureID . ", semanticlinkID=" . $row->semanticlinkID;
	}
}


echo "<br>Objective Feature Requirements - objectives.features - TODO";



?>