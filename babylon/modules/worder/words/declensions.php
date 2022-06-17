<?php

		
		
echo "<table style='width:800px;'>";
echo "	<tr>";
echo "		<td style='text-align:right'>";

	echo "<select id=languageselectfield class='top-select' style='width:240px;margin-right:5px;'>";
	if ($this->registry->languageID == 0) {
		echo "<option selected='selected' value='0'></option>";
	}
	foreach($this->registry->languages as $index => $language) {
		if ($this->registry->languageID ==  $language->languageID) {
			echo "<option selected='selected' value='" . $language->languageID . "'>" . $language->name . "</option>";
		} else {
			echo "<option value='" . $language->languageID . "'>" . $language->name . "</option>";
		}
	}
	echo "</select>";

echo "		</td>";
echo "	</tr>";



echo "	<tr>";
echo "		<td style='text-align:right'>";

echo "<select id=wordclassselectfield class='top-select' style='width:240px;margin-right:5px;'>";
if ($this->registry->wordclassID == 0) {
	echo "<option selected='selected' value='0'></option>";
}
foreach($this->registry->wordclasses as $index => $wordclass) {
	if ($this->registry->wordclassID ==  $wordclass->wordclassID) {
		echo "<option selected='selected' value='" . $wordclass->wordclassID . "'>" . $wordclass->name . "</option>";
	} else {
		echo "<option value='" . $wordclass->wordclassID . "'>" . $wordclass->name . "</option>";
	}
}
echo "</select>";

echo "		</td>";
echo "	</tr>";



echo "	<tr>";
echo "		<td style='text-align:right;'>";
		
	echo "<select id=parentselectfield class='top-select' style='width:240px;margin-right:5px;'>";
	if ($this->registry->parentfeatureID == 0) {
		echo "<option selected='selected' value='0'></option>";
	}
	foreach($this->registry->parentfeatures as $index => $feature) {
		if ($this->registry->parentfeatureID ==  $feature->featureID) {
			echo "<option selected='selected' value='" . $feature->featureID . "'>" . $feature->name . "</option>";
		} else {
			echo "<option value='" . $feature->featureID . "'>" . $feature->name . "</option>";
		}
	}
	echo "</select>";
	
echo "		</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td style='text-align:right;'>";
		
	echo "<select id=currentselectfield class='top-select' style='width:240px;margin-right:5px;'>";
	if ($this->registry->featureID == 0) {
		echo "<option selected='selected' value='0'></option>";
	}
	foreach($this->registry->currentfeatures as $index => $feature) {
		if ($this->registry->featureID ==  $feature->featureID) {
			echo "<option selected='selected' value='" . $feature->featureID . "'>" . $feature->name . "</option>";
		} else {
			echo "<option value='" . $feature->featureID . "'>" . $feature->name . "</option>";
		}
	}
	echo "</select>";
	
echo "		</td>";

echo "	</tr>";
echo "</table>";
	


echo "	<script>";
echo "		$('#languageselectfield').on('change', function() {";
echo "			window.location='".getUrl('worder/words/declensions')."&languageID='+this.value;";
echo "		});";
echo "	</script>";

echo "	<script>";
echo "		$('#wordclassselectfield').on('change', function() {";
echo "			window.location='".getUrl('worder/words/declensions')."&wordclassID='+this.value;";
echo "		});";
echo "	</script>";

echo "	<script>";
echo "		$('#parentselectfield').on('change', function() {";
echo "			window.location='".getUrl('worder/words/declensions')."&parentfeatureID='+this.value;";
echo "		});";
echo "	</script>";

echo "	<script>";
echo "		$('#currentselectfield').on('change', function() {";
echo "			window.location='".getUrl('worder/words/declensions')."&featureID='+this.value;";
echo "		});";
echo "	</script>";


//echo "<br>Count - " . count($this->registry->words);
$table = new UITableSection("Sanat","800px");
//$section->setOpen(true);
$table->setLineAction(UIComponent::ACTION_FORWARD_INDEX,"worder/words/showword&lang=" . $registry->languageID, "wordID");
$table->showLineNumbers(true);

$column = new UISortColumn("WordID", "wordID");
$table->addColumn($column);

$column = new UISortColumn("Lemma", "lemma");
$table->addColumn($column);

$table->setData($this->registry->words);
$table->show();


?>