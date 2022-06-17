<?php

	
	
	$languageID = $this->registry->languageID;
	
	$filterbox = new UIFilterBox();
	$filterbox->addSelectFilter($this->registry->playerID, $registry->players, "wordino/game/play", "Pelaaja", "playerID", "name");
	$filterbox->setEmptySelect(false);
	
	$gamescreenwidth = 2200;
	$gamescreenheight= 1700;
	
	

	$addLessonSection = new UISection("Adding Lesson");
	$addLessonSection->setDialog(true);
	$addLessonSection->setMode(UIComponent::MODE_INSERT);
	$addLessonSection->setSaveAction(UIComponent::ACTION_FORWARD, 'wordino/game/addlesson&playerID=' . $registry->player->playerID);
	
	$field = new UISelectField("Lesson","lessonID","lessonID", $registry->lessons, "name");
	$addLessonSection->addField($field);
	
	$addLessonSection->show();
	
	
	
	function generateLessons($lessons) {
		echo "<table class=worderlist style='font-size:14px;' id='lessontable'>";
		foreach($lessons as $lessonID => $lesson) {
			echo "	<tr>";
			echo "		<td>";
			echo "" . $lesson->name;
			echo "		</td>";
			echo "	<tr>";
		}
		echo "</table>";		
	}
	
	
	function generateWords($words) {
		echo "<table class=worderlist style='font-size:14px;' id='lessontable'>";
		foreach($words as $lessonID => $word) {
			echo "	<tr>";
			echo "		<td>";
			echo "" . $word->name;
			echo "		</td>";
			echo "	<tr>";
		}
		echo "</table>";
	}
	
	
	
	
	echo "<table style='width:1160px;'>";
	echo "	<tr>";
	echo "		<td style='width:60%;vertical-align:bottom'>";
	//$paginator->show();
	echo "		</td>";
	echo "		<td style='width:40%;text-align:right;'>";
	$filterbox->show();
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	
	echo "<table style='width:1160px;'>";
	echo "	<tr>";
	echo "		<td style='width:380px;background-color:pink;'>";
	
	echo "			<div style='background-color:#BBBBBB;color:black;width:330px;height:26px;border:thin solid grey;padding-left:5px;padding-top:2px;font-weight:bold;text-align:bottom;'>";
	echo "<div style='float:left;padding-top:5px;'>";
	echo "Lessons";
	echo "</div>";
	echo "<div style='float:right;padding-right:3px;'>";
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addLessonSection->getID(), 'Add lesson');
	$button->show();
	echo "			</div>";
	echo "			</div>";
	echo "			<div style='background-color:white;color:black;width:335px;height:160px;overflow-y:scroll;border:thin solid grey;'>";
	echo "				<table class=worderlist style='font-size:14px;' id='sentencetable'></table>";
	generateLessons($registry->selectedlessons);
	echo "			</div>";
	
	echo "			<div style='background-color:#BBBBBB;color:black;width:330px;height:20px;border:thin solid grey;padding-left:5px;padding-top:2px;font-weight:bold;'>";
	echo "Rules";
	echo "			</div>";
	echo "			<div style='background-color:white;color:black;width:335px;height:160px;overflow-y:scroll;border:thin solid grey;'>";
	echo "				<table class=worderlist id='generaterulestable' style='width:310px; border-collapse:collapse'></table>";
	echo "			</div>";
	
	echo "			<div style='background-color:#BBBBBB;color:black;width:330px;height:20px;border:thin solid grey;padding-left:5px;padding-top:2px;font-weight:bold;'>";
	echo "Words";
	echo "			</div>";
	echo "			<div style='background-color:white;color:black;width:335px;height:160px;overflow-y:scroll;border:thin solid grey;'>";
	echo "				<table class=worderlist style='font-size:14px;width:100%' id='generateresults'></table>";
	echo "			</div>";
	
	echo "			<div style='background-color:#BBBBBB;color:black;width:330px;height:20px;border:thin solid grey;padding-left:5px;padding-top:2px;font-weight:bold;'>";
	echo "Gametypes";
	echo "			</div>";
	echo "			<div style='background-color:white;color:black;width:335px;height:90px;overflow-y:scroll;border:thin solid grey;'>";
	echo "				<table  style='font-size:14px;' id='applyruletable'></table>";
	echo "			</div>";
	
	
	echo "		</td>";
	echo "		<td style='width:800px;background-color:lightblue;'>";
	echo "			<div style='height:670px;border:1px thin solid;'>";
	//echo "				<ul>";
	//echo "					<li><a href='#contenttabs-1'>Canvas</a><li>";
	//echo "					<li><a href='#contenttabs-2'>Console</a><li>";
	//echo "				</ul>";
	echo "					<div style='float:left;background-color:#e0e0e0;height:22px;width:350px;'>";
	echo "						<button onclick='generatenextstep()'>Start</button>";
	echo "					</div>";
	
	//echo "				</div>";
	
	
	echo "				<div style='background-color:white;margin:0px;padding:10px;height:630px;width:800px;overflow:scroll;margin-top:4px;'>";
	echo "					<canvas id='generatecanvas' width=" . $gamescreenwidth . " height=" . $gamescreenheight . " style='border:0px solid red;'></canvas>";
	echo "				</div>";
	//echo "				<div id=contenttabs-2 style='background-color:yellow;'>";
	//echo "				</div>";
	echo "			</div>";
	
	
	echo "		</td>";
	echo "	</tr>";
	
	echo "	<tr>";
	echo "		<td colspan=2 style='background-color:lightblue;'>";
	echo "			<div id='consolecontainer' style='background-color:white;color:black;width:1154px;height:100px;overflow-y:scroll;border:thin solid grey;'>";
	echo "				<table  style='font-size:14px;' id='console'></table>";
	echo "			</div>";
	echo "		</td>";
	echo "	</tr>";
	
	echo "</table>";
	
	
	echo "<script>";
	echo "		function generatorPauseOncheckingRule(value) {";
	echo "			console.log('PauseOnCheckingRule - '+value);";
	echo "			var checkvalue = 0;";
	echo "			if (value == true) checkvalue = 1;";
	echo "			$.getJSON('" . getUrl('worder/translate/updategeneratepause') . "&item=1&check='+checkvalue,'',function(data) {";
	//echo "				console.log('updategeneratepause called');";
	//echo "				console.dir(data);";
	echo "			}); ";
	echo "			if (window.generator != null) window.generator.setPauseOnCheckingRule(value);";
	echo "		}";
	echo "		function generatorPauseOnApplyRule(value) {";
	//echo "			console.log('PauseOnApplyRule - '+value);";
	echo "			var checkvalue = 0;";
	echo "			if (value == true) checkvalue = 1;";
	echo "			$.getJSON('" . getUrl('worder/translate/updategeneratepause') . "&item=2&check='+checkvalue,'',function(data) {";
	//echo "				console.log('updategeneratepause called');";
	//echo "				console.dir(data);";
	echo "			}); ";
	echo "			if (window.generator != null) window.generator.setPauseOnApplyRule(value);";
	echo "		}";
	echo "		function generatorPauseOnReverseRule(value) {";
	//echo "			console.log('setPauseOnReverseRule - '+value);";
	echo "			var checkvalue = 0;";
	echo "			if (value == true) checkvalue = 1;";
	echo "			$.getJSON('" . getUrl('worder/translate/updategeneratepause') . "&item=3&check='+checkvalue,'',function(data) {";
	//echo "				console.log('updategeneratepause called');";
	//echo "				console.dir(data);";
	echo "			}); ";
	echo "			if (window.generator != null) window.generator.setPauseOnReverseRule(value);";
	echo "		}";	
	echo "		function generatorPauseOnResultFound(value) {";
	//echo "			console.log('PauseOnResultFound - '+value);";
	echo "			var checkvalue = 0;";
	echo "			if (value == true) checkvalue = 1;";
	echo "			$.getJSON('" . getUrl('worder/translate/updategeneratepause') . "&item=4&check='+checkvalue,'',function(data) {";
	//echo "				console.log('updategeneratepause called');";
	//echo "				console.dir(data);";
	echo "			}); ";
	echo "			if (window.generator != null) window.generator.setPauseOnResultFound(value);";
	echo "		}";
	echo "		function generatorZoomIn() {";
	echo "			alert('ZoomIn - Not implemented');";
	echo "		}";
	echo "		function generatorZoomOut() {";
	echo "			alert('ZoomOut - Not implemented');";
	echo "		}";
	echo "</script>";
	
	echo "<script>";
	echo "		function generatenextstep() {";
	echo "			window.generator.nextStep();";
	echo "		}";
	echo "</script>";
	
	echo "<script>";
	echo "		function getUrl(name) {";
	echo "			url = '" . getRootUrl()  . "';";
	echo "			url += name;";
	echo "			return url;";
	echo "		}";
	echo "</script>";
	
	echo "<script>";
	echo "		function fetchstructure() {";
	echo "			console.log('" . getUrl('worder/generate/fetchstructureJSON') . "&languageID=1');";
	echo "			addConsoleMessage('Fetch structure - " . getUrl('worder/generate/fetchstructureJSON') . "&languageID=" . $languageID . "');";
	echo "			$.getJSON('" . getUrl('worder/generate/fetchstructureJSON') . "&languageID=" . $languageID . "','',function(data) {";
	echo "				window.generator = new SyntaxGenerator('generatecanvas', rules, " . $gamescreenwidth . "," . $gamescreenheight . ");";
	echo "				window.generator.setLanguageID(" . $this->registry->languageID . ");";
	echo "				window.generator.setRulesTableElement('generaterulestable');";
	echo "				window.generator.setGenerateResultTableElement('generateresults');";
	echo "				window.generator.setRuleSectionID(" . $rulesection->getID() . ");";
	
	echo "				var pauseOncheckingRule = $('#generatecheckbox-1').is(':checked');";
	echo "				window.generator.setPauseOnCheckingRule(pauseOncheckingRule);";
	//echo "				console.log('pauseOncheckingRule - '+pauseOncheckingRule);";
	
	echo "				var pauseOnApplyRule = $('#generatecheckbox-2').is(':checked');";
	echo "				window.generator.setPauseOnApplyRule(pauseOnApplyRule);";
	//echo "				console.log('pauseOnApplyRule - '+pauseOnApplyRule);";
	
	echo "				var pauseOnReverseRule = $('#generatecheckbox-3').is(':checked');";
	echo "				window.generator.setPauseOnReverseRule(pauseOnReverseRule);";
	//echo "				console.log('pauseOnReverseRule - '+pauseOnReverseRule);";
	
	echo "				var pauseOnResultFound = $('#generatecheckbox-4').is(':checked');";
	echo "				window.generator.setPauseOnResultFound(pauseOnResultFound);";
	//echo "				console.log('pauseOnResultFound - '+pauseOnResultFound);";
	
	// asetuksissa on pause-täpät, nämä pitää asettaa...
	
	echo "				fs = createFeatureStructureFromJSON(data);";
	echo "				window.generator.setConceptStructure(fs);";
	echo "				window.generator.startGenerate();";
	echo "			}); ";
	echo "		};";
	echo "</script>";
	
	
	echo "<script>";
	echo "	var rules = new Array();";
	echo "	$(document).ready(function() {";
	echo "		addConsoleMessage('" . getUrl('worder/players/get rules JSON') . "&languageID=" . $languageID . "');";
	echo "		$.getJSON('" . getUrl('worder/players/get rules JSON') . "&languageID=" . $languageID . "','',function(data) {";
	echo "			$.each(data, function(index) {";
	echo "				var rule = parseRule(data[index]);";
	echo "				rules[index] = rule;";
	echo "			});";
	echo "			addConsoleMessage('Rules loaded (' + data.length+')');";
	echo "		}); ";
	echo "	});";
	echo "</script>";
	
	
	
	
?>
