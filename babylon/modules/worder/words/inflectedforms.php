<?php

	
	//$paginator = new UIPaginator($this->registry->currentpage,$this->registry->rowsperpage,$this->registry->totalrows,"worder/words/showwords&lang=" .  $registry->language->languageID);
	
	
	$filterbox = new UIFilterBox();
	//$filterbox->addSelectFilter($this->registry->grouptypeID, $this->registry->grouptypes, "worder/words/inflectedforms&lang=" .  $registry->language->languageID, "Sanaluokka", "grouptypeID", "name");
	$filterbox->addSelectFilter($this->registry->languageID, $this->registry->languages, "worder/words/inflectedforms&languageID=" .  $registry->language->languageID, "Kieli", "languageID", "name");
	$filterbox->addSelectFilter($this->registry->wordclassID, $this->registry->wordclasses, "worder/words/inflectedforms&wordclassID=" .  $registry->language->wordclassID, "Sanaluokka", "wordclassID", "name");
	//$filterbox->addSelectFilter($this->registry->wordgroupID, $this->registry->wordgroups, "worder/words/inflectedforms&lang=" .  $registry->language->languageID, "Sanaryhmä", "wordgroupID", "name");
	//$filterbox->addSelectFilter($this->registry->componentID, $this->registry->components, "worder/words/inflectedforms&lang=" .  $registry->language->languageID, "Componentti", "componentID", "name");
	
	
	
	
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
	
	
	//echo "<br>This is inflected forms";

	/*
	$counter = 1;
	$wordswithforms = array();
	$wordsnoforms = array();
	foreach($this->registry->words as $index => $word) {
		if ($word[3]>0) {
			//echo "<br>" . $counter . " - " . $word[0] . " - " . $word[1] . " - " . $word[2] . " - " . $word[3] . " - " . $word[4];
			$wordswithforms[] = $word;
		} else {
			$wordsnoforms[] = $word;
			//echo "<br>" . $counter . " - " . $word->wordID  . " - " . $word->lemma . " - 0";
		}
		$counter++;
	}
	*/

	
	// table näkymä tähän...
	
	/*
	echo "<br>Sanat";
	$table = new UITableSection("Sanat","800px");
	//$section->setOpen(true);
	

	$table->setLineAction(UIComponent::ACTION_FORWARD_INDEX,"worder/words/showword&lang=" . $registry->language->languageID, 1);
	//$table->showLineNumbers(true);
	
	$table->showLineNumbers(true);
	
	//$column = new UISimpleColumn("ConceptID", 0);
	//$table->addColumn($column);
	
	$column = new UISimpleColumn("WordID", 1);
	$table->addColumn($column);
	
	$column = new UISimpleColumn("Lemma", 2);
	$table->addColumn($column);
	
	$column = new UISimpleColumn("Count", 3);
	$table->addColumn($column);
	
	$column = new UISimpleColumn("Wordclass", 4);
	$table->addColumn($column);
	
	$column = new UISimpleColumn("State", 5);
	$table->addColumn($column);
	
	
	$table->setData($this->registry->words);
	$table->show();
	*/
	
	//echo "<br>Ei taivutusmuotoja";
	
	$table = new UITableSection("Ei taivutusmuotoja","800px");
	//$section->setOpen(true);
	$table->setLineAction(UIComponent::ACTION_FORWARD_INDEX,"worder/words/showword&lang=" . $registry->language->languageID, 1);
	
	//$column = new UISimpleColumn("ConceptID", 0);
	//$table->addColumn($column);
	$table->showLineNumbers(true);
	
	
	$column = new UISimpleColumn("WordID", 1);
	$table->addColumn($column);
	
	$column = new UISimpleColumn("Lemma", 2);
	$table->addColumn($column);
	
	$column = new UISimpleColumn("Count", 3);
	$table->addColumn($column);
	
	$column = new UISimpleColumn("Wordclass", 4);
	$table->addColumn($column);
	
	$column = new UISimpleColumn("Infl", 6);
	$table->addColumn($column);
	
	$column = new UISimpleColumn("State", 5);
	$table->addColumn($column);
	
	$table->setData($this->registry->wordswithoutconcept);
	$table->show();
	

	echo "<br>puuttuvia muotoja: " . $this->registry->missinginflections;
	echo "<br>taivutettu muotoja: " . $this->registry->enoughinflections;
	echo "<br>ei taivu: " . $this->registry->noinflections;
	$total = $this->registry->missinginflections + $this->registry->enoughinflections + $this->registry->noinflections;
	echo "<br> - total: " . $total . " -- " . ( $this->registry->missinginflections / $total);
	
	
	
	/*
	$table = new UITableSection("Ei taivutusmuotoja","800px");
	//$section->setOpen(true);
	$table->setLineAction(UIComponent::ACTION_FORWARD_INDEX,"worder/concepts/showconcept&lang=" . $registry->language->languageID, 0);
	
	$column = new UISimpleColumn("ConceptID", 0);
	$table->addColumn($column);
	
	$column = new UISimpleColumn("WordID", 1);
	$table->addColumn($column);
	
	$column = new UISimpleColumn("Lemma", 2);
	$table->addColumn($column);
	
	$column = new UISimpleColumn("Count", 3);
	$table->addColumn($column);
	
	$column = new UISimpleColumn("Wordclass", 4);
	$table->addColumn($column);
	
	$column = new UISimpleColumn("State", 5);
	$table->addColumn($column);
	*/
	
	/*
	echo "<br><br><br>Ei taivutusmuotoja";
	$counter = 0;
	foreach($this->registry->words as $index => $word) {
		if ($word[3]>0) {
			//echo "<br>" . $counter . " - " . $word->wordID . " - " . $word->lemma . " - " . $this->registry->wordcounts[$word->wordID];
		} else {
			echo "<br>" . $counter . " - " . $word[0] . " - " . $word[1] . " - " . $word[2] . " - " . $word[3] . " - " . $word[4];
		}
		$counter++;
	}
	*/
	
	
	//$table->setData($wordsnoforms);
	//$table->show();
	
	
?>