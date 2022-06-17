<?php

echo "<br>grouptype - " . $this->registry->grouptypeID;


echo "<br>Rowcount - " . count($registry->words);
echo "<br>grouptypes - " . count($registry->grouptypes);

//var_dump($registry->words);


//$paginator = new UIPaginator($this->registry->currentpage,$this->registry->rowsperpage,$this->registry->totalrows,"worder/concepts/showconcepts");

$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->grouptypeID, $this->registry->grouptypes, "worder/words/showlanguageconcepts&lang=" . $this->registry->language->languageID, "Tyyppi","grouptypeID", "name");

//$filterbox->addSelectFilter($this->registry->wordclassID, $this->registry->wordclasses, "worder/concepts/showconcepts", "Sanaluokka","wordclassID", "name");
//$filterbox->addSelectFilter($this->registry->wordgroupID, $this->registry->wordgroups, "worder/concepts/showconcepts", "Ryhmä", "wordgroupID", "name");
//$filterbox->addTextFilter("worder/concepts/showconcepts", $this->registry->search, "Tekstihaku", "search");

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



/*
	include "weightformatter.class.php";
	include "wordgetter.class.php";
	
	$formatter = new WeightFormatter();
	$wordgetter = new WordGetter($registry->words, "conceptID");
	
	

	// ---------------------------------------------------------------------------------------------------
	// Lisää dialogi
	// ---------------------------------------------------------------------------------------------------
	
	$insertsection = new UISection("Sanan lisäys");
	$insertsection->setDialog(true);
	$insertsection->setMode(UIComponent::MODE_INSERT);
	$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/words/insertword&lang=' . $registry->language->languageID);
	
	$nimifield = new UITextField("Sana", "Sana", 'word');
	$insertsection->addField($nimifield);
	
	$field = new UISelectField("Käsite","conceptID","conceptID", $registry->concepts, "name");
	$field->setPredictive(true);
	$field->setValue($registry->conceptID);
	$insertsection->addField($field);
	
	$insertsection->show();
*/
	
	
$table = new UITableSection("Käsitteet: " . $registry->language->name, '800px');
	//$table->setFramesVisible(false);

	
$table->setLineAction(UIComponent::ACTION_FORWARD_INDEX,"worder/concepts/showconcept&lang=" . $registry->language->languageID,"conceptID");
$table->showLineNumbers(true);

$wordcolumn = new UISortColumn("ConceptID","conceptID", "");
$table->addColumn($wordcolumn);
	
$conceptcolumn = new UISortColumn("Käsite", "name", "");
$table->addColumn($conceptcolumn);

$conceptcolumn = new UISortColumn("Käsite", "finnish_word", "");
$table->addColumn($conceptcolumn);

$column = new UISelectColumn("Sanaluokka","name", "wordclassID", $this->registry->wordclasses);
$table->addColumn($column);


//$conceptcolumn = new UISortColumn("Sana", ""2);
//$table->addColumn($conceptcolumn);
	
//$conceptcolumn = new UISortColumn("Käsite", 3);
//$table->addColumn($conceptcolumn);
	
//$conceptcolumn = new UISortColumn("Features", 4);
//$table->addColumn($conceptcolumn);
	
//$wordclasscolumn = new UISelectColumn("Sanaluokka", "wordclassID", $this->registry->wordclasses, "");
//$wordclasscolumn->setColumnType(Column::COLUMNTYPE_INTEGER);
//$table->addColumn($wordclasscolumn); // heittää erroria
	
//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), 'Lisää uusi');
//$table->addButton($button);
	
	
$table->setData($this->registry->words);
$table->show();
	
	
	
?>