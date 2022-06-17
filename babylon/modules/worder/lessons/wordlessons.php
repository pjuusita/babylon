<?php

	
	//$paginator = new UIPaginator($registry->currentpage,$registry->rowsperpage,$registry->totalrows,"worder/words/showwords&lang=" .  $registry->language->languageID);
	
echo "<table style='width:600px;'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
//$paginator->show();
echo "		</td>";



/*
	if (count($registry->languages) > 1) {
		echo "	<tr>";
		echo "		<td style='width:60%;vertical-align:bottom'>";
		//$paginator->show();
		echo "		</td>";
		$filterbox = new UIFilterBox();
		//$filterbox->addSelectFilter($registry->languageID, $registry->languages, "worder/lessons/showlexiconlessons&", "languageID", "name");
		//$filterbox->setEmptySelect(false);
		echo "		<td style='width:40%;text-align:right;'>";
		//$filterbox->show();
		echo "		</td>";
	}
	*/
	echo "	<tr>";
	echo "		<td style='width:60%;vertical-align:bottom'>";
	//$paginator->show();
	echo "		</td>";

	$filterbox = new UIFilterBox();
	$filterbox->addSelectFilter($registry->wordclassID, $registry->wordclasses, "worder/lessons/showlexiconlessons", "Parts of speech", "wordclassID", "name");
	//$filterbox->addSelectFilter($registry->wordgroupID, $registry->wordgroups, "worder/words/showwords&lang=" .  $registry->language->languageID, "Ryhmä", "wordgroupID", "name");
	//$filterbox->addTextFilter("worder/words/showwords", $registry->search, "Search", "search");
	
	//$paginator->setFastFind(true);
	//$paginator->setPagingStyle("pagenumber");
	
	echo "		<td style='width:40%;text-align:right;'>";
	$filterbox->show();
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	



	$table = new UITableSection("Concepts", '600px');
	//$table->setFramesVisible(false);

	//$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/words/showwords&wordclassID=0", "Tyhjennä filtteri");
	//$table->addButton($button);
	
	$table->showRowNumbers(true);
	
	//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), 'Lisää uusi');
	//$table->addButton($button);
	
	$table->setLineAction(UIComponent::ACTION_FORWARD,"worder/concepts/showconcept","conceptID");
	
	//$table->setLineAction(UIComponent::ACTION_FORWARD_INDEX,"worder/words/showword&lang=" . $registry->language->languageID,"wordID");
	
	$column = new UISortColumn("ID", "conceptID");
	$table->addColumn($column);
	
	//$column = new UISortColumn("#", "conceptID", "worder/words/showwords&lang=" . $registry->language->languageID);
	//$table->addColumn($column);

	$column = new UISortColumn("Name", "name");
	$table->addColumn($column);
	
	//$column = new UISortColumn("Sana", "finnish_word", "worder/words/showwords&lang=" . $registry->language->languageID);
	//$table->addColumn($column);

	$column = new UISelectColumn("PoS","name", "wordclassID", $registry->wordclasses);
	$table->addColumn($column);
	
	$column = new UISortColumn("lessonID", "lessonID");
	$table->addColumn($column);
	
	//$column = new UISelectColumn("Lesson","name", "lessonID", $registry->lessons);
	//$table->addColumn($column);
	
	
	
	//$column = new UISimpleColumn("Käsite", 3);
	//$table->addColumn($column);
	
	//$column = new UISimpleColumn("Features", 4);
	//$table->addColumn($column);
	
	
	
	//$wordclasscolumn = new UISelectColumn("Sanaluokka", "wordclassID", $registry->wordclasses, "");
	//$wordclasscolumn->setColumnType(Column::COLUMNTYPE_INTEGER);
	//$table->addColumn($wordclasscolumn); // heittää erroria
	
	//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), 'Lisää uusi');
	//$table->addButton($button);
	
	$table->setData($registry->concepts);
	
	$table->show();
	

	//echo "<br>currentpage - " . $registry->currentpage;
	//echo "<br>wordclassID - " . $registry->wordclassID;
	//echo "<br>wordgroupID - " . $registry->wordgroupID;
	//echo "<br>search - " . $registry->search;
	//echo "<br>totalrows - " . $registry->totalrows;
	//echo "<br>rowcount - " . count($registry->words);
	

	
?>