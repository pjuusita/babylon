<?php
	
	// Luodaan oletuslevelit kaikkiin lessoneihin, 
	
	// Tällähetkellä luodaan easy tyyppiset pohjalle.

	// Uudelleenajattava	

	global $mysqli;

	$sql = "SELECT * FROM worder_lessons WHERE GrammarID=1";
	echo "<br>SQL - " . $sql;
	$lessons = array();
	$result = $mysqli->query($sql);
	
	while($row = $result->fetch_array()) {
		$lessonID = $row['LessonID'];
		$lessons[$lessonID] = $lessonID;
	}
	echo "<br>Lessoncount - " . count($lessons);
	
	$sql = "SELECT * FROM worder_lessonlevels WHERE GrammarID=1";
	echo "<br>SQL - " . $sql;
	$result = $mysqli->query($sql);
	
	$haslevels = array();
	while($row = $result->fetch_array()) {
		$lessonID = $row['LessonID'];
		$haslevels[$lessonID] = $lessonID;
	}
	echo "<br>Lessoncount - " . count($haslevels);
	
	
	$counter = 0;	
	echo "<br><br>";
	foreach($lessons as $lessonID => $conceptlist) {
		echo "<br><br>LessonID - " . $lessonID;
		
		if (isset($haslevels[$lessonID])) {
			echo "<br> -- already has levels: " . $lessonID;
		} else {
			
			$sql = "INSERT INTO worder_lessonlevels (LessonID, Level, Experience, Stage1weight, Stage2weight, Stage3weight, Stage4weight, Stage1recap, Stage2recap, Stage3recap, Stage4recap, Stage1newcount, Stage2newcount, Stage3newcount, Stage4newcount, GrammarID, SystemID) VALUES (" . $lessonID . ",1,20,100,0,0,0,30,0,0,0,10,0,0,0,1,5)";
			echo "<br> -- " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) {
				die("Error 1: " . $mysqli->connect_error);
			}
			
			$sql = "INSERT INTO worder_lessonlevels (LessonID, Level, Experience, Stage1weight, Stage2weight, Stage3weight, Stage4weight, Stage1recap, Stage2recap, Stage3recap, Stage4recap, Stage1newcount, Stage2newcount, Stage3newcount, Stage4newcount, GrammarID, SystemID) VALUES (" . $lessonID . ",2,30,100,0,0,0,40,0,0,0,2,0,0,0,1,5)";
			echo "<br> -- " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) {
				die("Error 1: " . $mysqli->connect_error);
			}
			
			$sql = "INSERT INTO worder_lessonlevels (LessonID, Level, Experience, Stage1weight, Stage2weight, Stage3weight, Stage4weight, Stage1recap, Stage2recap, Stage3recap, Stage4recap, Stage1newcount, Stage2newcount, Stage3newcount, Stage4newcount, GrammarID, SystemID) VALUES (" . $lessonID . ",3,40,100,0,0,0,60,0,0,0,2,0,0,0,1,5)";
			echo "<br> -- " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) {
				die("Error 1: " . $mysqli->connect_error);
			}
			
			$sql = "INSERT INTO worder_lessonlevels (LessonID, Level, Experience, Stage1weight, Stage2weight, Stage3weight, Stage4weight, Stage1recap, Stage2recap, Stage3recap, Stage4recap, Stage1newcount, Stage2newcount, Stage3newcount, Stage4newcount, GrammarID, SystemID) VALUES (" . $lessonID . ",4,50,100,0,0,0,75,0,0,0,0,0,0,0,1,5)";
			echo "<br> -- " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) {
				die("Error 1: " . $mysqli->connect_error);
			}
			
			$sql = "INSERT INTO worder_lessonlevels (LessonID, Level, Experience, Stage1weight, Stage2weight, Stage3weight, Stage4weight, Stage1recap, Stage2recap, Stage3recap, Stage4recap, Stage1newcount, Stage2newcount, Stage3newcount, Stage4newcount, GrammarID, SystemID) VALUES (" . $lessonID . ",5,60,100,0,0,0,75,0,0,0,0,0,0,0,1,5)";
			echo "<br> -- " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) {
				die("Error 1: " . $mysqli->connect_error);
			}
		}
		
		if ($counter > 0) break;
	}
	

?>