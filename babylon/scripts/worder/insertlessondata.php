<?php


	$lessons = Table::load("worder_lessons", "WHERE SystemID=5 AND GrammarID=1");

	
	foreach($lessons as $lessonID => $lesson) {
		
		$finnish = parseMultilangStringWithEmpty($lesson->name, 1);
		$english = parseMultilangStringWithEmpty($lesson->name, 2);
		
		//echo "<br> - " . $lessonID . " -- " . $finnish . " - " . $english;
		
		if ($english == "") {
			echo "<br>English missing - " . $lessonID . " - " . $lesson->name;
		}
		
		if ($finnish == "") {
			echo "<br>Finnish missing - " . $lessonID . " - " . $lesson->name;
		}
		
		
		$values = array();
		$values['LessonID'] = $lesson->lessonID;
		$values['Name'] = $finnish;
		$values['LanguageID'] = 1;
		$values['GrammarID'] = 1;
		$values['RulesetID'] = 84;
		if ($lesson->difficultylevel == "") $values['Difficultylevel'] = 0;
		else $values['Difficultylevel'] = $lesson->difficultylevel;
		//$success = Table::addRow("worder_lessondata", $values);
		
		
		$values = array();
		$values['LessonID'] = $lesson->lessonID;
		$values['Name'] = $english;
		$values['LanguageID'] = 2;
		$values['GrammarID'] = 1;
		$values['RulesetID'] = 85;
		if ($lesson->difficultylevel == "") $values['Difficultylevel'] = 0;
		else $values['Difficultylevel'] = $lesson->difficultylevel;
		//$success = Table::addRow("worder_lessondata", $values);
		
	}


?>