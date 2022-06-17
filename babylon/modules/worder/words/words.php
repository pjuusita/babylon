<?php

	
	//$paginator = new UIPaginator($registry->currentpage,$registry->rowsperpage,$registry->totalrows,"worder/words/showwords&lang=" .  $registry->language->languageID);
	
echo "<table style='width:600px;'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
//$paginator->show();
echo "		</td>";


	
	if (count($registry->languages) > 1) {
		echo "	<tr>";
		echo "		<td style='width:60%;vertical-align:bottom'>";
		//$paginator->show();
		echo "		</td>";
		$filterbox = new UIFilterBox();
		$filterbox->addSelectFilter($registry->languageID, $registry->languages, "worder/words/showwords&languageID=" . $registry->languageID, "Languages", "languageID", "name");
		$filterbox->setEmptySelect(false);
		echo "		<td style='width:40%;text-align:right;'>";
		$filterbox->show();
		echo "		</td>";
	}
	echo "	<tr>";
	echo "		<td style='width:60%;vertical-align:bottom'>";
	//$paginator->show();
	echo "		</td>";

	$filterbox = new UIFilterBox();
	$filterbox->addSelectFilter($registry->wordclassID, $registry->wordclasses, "worder/words/showwords", "Parts of speech", "wordclassID", "name");
	//$filterbox->addSelectFilter($registry->wordgroupID, $registry->wordgroups, "worder/words/showwords&lang=" .  $registry->language->languageID, "Ryhmä", "wordgroupID", "name");
	$filterbox->addTextFilter("worder/words/showwords", $registry->search, "Search", "search");
	
	//$paginator->setFastFind(true);
	//$paginator->setPagingStyle("pagenumber");
	
	echo "		<td style='width:40%;text-align:right;'>";
	$filterbox->show();
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	



	// ---------------------------------------------------------------------------------------------------
	// Lisää dialogi
	// ---------------------------------------------------------------------------------------------------
	
	$insertsection = new UISection("Add New Lexeme");
	$insertsection->setDialog(true);
	$insertsection->setMode(UIComponent::MODE_INSERT);
	$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/words/insertword&languageID=' . $registry->languageID);
	
	$nimifield = new UITextField("Lemma", 'word');
	$insertsection->addField($nimifield);

	$field = new UISelectField("Part of speech","wordclassID","wordclassID",$registry->wordclasses, "name");
	$insertsection->addField($field);
	
	
	//$field = new UISelectField("Käsite","conceptID","conceptID", $registry->concepts, "name");
	//$field->setPredictive(true, "worder/concepts/showconceptsjson");
	//$field->setPredictive(true);
	
	//$field = new UISelectField("Käsite","conceptID","conceptID",array(),'name');
	//$field->setPredictive(true, "worder/concepts/conceptautocomplete");
	//$insertsection->addField($field);
	
	
	//$field->setValue($registry->conceptID);
	//$insertsection->addField($field);
	
	$insertsection->show();
	
	
	
	// ---------------------------------------------------------------------------------------------------
	// Lisää dialogi
	// ---------------------------------------------------------------------------------------------------
	/*
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
	
	
	if (count($registry->languages) > 1) {
		if ($registry->languageID > 0) {
			$language = $registry->languages[$registry->languageID];
			$table = new UITableSection("Lexicon", '600px');
		} else {
			$table = new UITableSection("Lexicon", '600px');
		}
	} else {
		$table = new UITableSection("Lexicon", '600px');
	}
	//$table->setFramesVisible(false);

	$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/words/showwords&wordclassID=0", "Tyhjennä filtteri");
	$table->addButton($button);
	
	$table->showRowNumbers(true);
	
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), 'Lisää uusi');
	$table->addButton($button);
	
	$table->setLineAction(UIComponent::ACTION_FORWARD,"worder/words/showword","wordID");
	
	//$table->setLineAction(UIComponent::ACTION_FORWARD_INDEX,"worder/words/showword&lang=" . $registry->language->languageID,"wordID");
	
	$column = new UISortColumn("ID", "wordID");
	$table->addColumn($column);
	
	//$column = new UISortColumn("#", "conceptID", "worder/words/showwords&lang=" . $registry->language->languageID);
	//$table->addColumn($column);

	$column = new UISortColumn("Lemma", "lemma");
	$table->addColumn($column);
	
	//$column = new UISortColumn("Sana", "finnish_word", "worder/words/showwords&lang=" . $registry->language->languageID);
	//$table->addColumn($column);

	$column = new UISelectColumn("Part of Speech","name", "wordclassID", $registry->wordclasses);
	$table->addColumn($column);
	
	
	//$column = new UISimpleColumn("Käsite", 3);
	//$table->addColumn($column);
	
	//$column = new UISimpleColumn("Features", 4);
	//$table->addColumn($column);
	
	
	
	//$wordclasscolumn = new UISelectColumn("Sanaluokka", "wordclassID", $registry->wordclasses, "");
	//$wordclasscolumn->setColumnType(Column::COLUMNTYPE_INTEGER);
	//$table->addColumn($wordclasscolumn); // heittää erroria
	
	//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), 'Lisää uusi');
	//$table->addButton($button);
	
	$table->setData($registry->words);
	
	$table->show();
	

	//echo "<br>currentpage - " . $registry->currentpage;
	//echo "<br>wordclassID - " . $registry->wordclassID;
	//echo "<br>wordgroupID - " . $registry->wordgroupID;
	//echo "<br>search - " . $registry->search;
	//echo "<br>totalrows - " . $registry->totalrows;
	//echo "<br>rowcount - " . count($registry->words);
	

	
?>