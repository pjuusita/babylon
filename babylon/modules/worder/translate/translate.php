<?php

	$sourcelanguageID = $this->registry->sourcelanguageID;
	$targetlanguageID = $this->registry->targetlanguageID;
	$sourcerulesetID = $this->registry->sourcesetID;
	$targetrulesetID = $this->registry->targetsetID;
	
	$gamescreenwidth = 2600;
	$gamescreenheight= 4000;
	
	//echo "<br>Languages - " . $sourcelanguageID . " - " . $targetlanguageID;
	
	$rulesection = new UISection("Rule x","840px");
	$rulesection->setDialog(true);
	$rulesection->setMode(UIComponent::MODE_INSERT);
	$rulesection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/lessons/insertlesson');
	
	$rulesection->setCustomContent('ruleCanvasDiv');
	$rulesection->show();
	
	
	function ruleCanvasDiv() {
	
		global $registry;
	
		echo "	<table style='width:700px;'>";
		echo "		<tr>";
		echo "			<td style='padding-right:5px;'>";
		echo "			<div id=contenttabs-1 style='background-color:white;margin:0px;padding:10px;height:340px;overflow:scroll;width:800px;'>";
		echo "				<canvas id='rulecanvas' width=980 height=1260 style='border:0px solid red;'></canvas>";
		echo "			</div>";
		echo "			</td>";
		echo "		</tr>";
	
		echo "		<tr>";
		echo "			<td style='padding-right:5px;'>";
		echo "				<div style='float:right'>";
		echo "					<button  class=section-button  onclick='ruledialogclosebuttonpressed()'>Sulje</button>";
		echo "				</div>";
		echo "			</td>";
		echo "		</tr>";
	
		echo "	</table>";
	}
	
	echo "<script>";
	echo "		function ruledialogclosebuttonpressed() {";
	echo "  		$('#sectiondialog-" . $rulesection->getID() . "').dialog('close');";
	echo "		};";
	echo "	</script>";
	
	
	
	//echo "<br> - sourcelanguage - " . $sourcelanguageID;
	
	echo "<table style='width:1100px;'>";

	echo "	<tr>";
	echo "		<td colspan=2 style='height:20px;vertical-align:top'>";
	echo "			<table style='border-collapse:collapse;' cellspacing=0 cellpadding=0>";
	echo "				<tr>";
	echo "					<td>";
	echo "						<select id=sourcelanguageselect  onchange=\"sourcelanguagechange(this.value)\" class=uitextfield style='width:163px;margin-right:4px;padding-top:0px;'>";
	foreach($registry->languages as $index => $language) {
		if ($language->languageID == $sourcelanguageID) {
			echo "						<option selected value='" . $language->languageID . "'>" . $language->name . "</option>";
		} else {
			echo "						<option value='" . $language->languageID . "'>" . $language->name . "</option>";
		}
	}
	echo "						</select>";
	echo "					</td>";
	echo "					<td>";
	echo "						<select id=targetlanguageselect  onchange=\"targetlanguagechange(this.value)\"  class=uitextfield style='width:163px;margin-right:4px;padding-top:0px;'>";
	foreach($registry->languages as $index => $language) {
		if ($language->languageID == $targetlanguageID) {
			echo "						<option selected value='" . $language->languageID . "'>" . $language->name . "</option>";
		} else {
			echo "						<option value='" . $language->languageID . "'>" . $language->name . "</option>";
		}
	}
	echo "						</select>";
	echo "					</td>";
	echo "					<td>";
	echo "						<input id=inputsentence type='text' value='" . $registry->sentence . "' class=uitextfield style='width:410px;margin-right:4px;'>";
	echo "					</td>";
	echo "					<td>";
	echo "						<select id=sourcerulesetselect  onchange=\"sourcerulesetchange(this.value)\"  class=uitextfield style='width:163px;margin-right:4px;padding-top:0px;'>";
	if ($sourcerulesetID == 0) {
		echo "						<option selected value='0'>All active rules</option>";
	} else {
		echo "						<option value='0'>All active rules</option>";
	}
	foreach($registry->rulesets as $index => $ruleset) {
		if ($ruleset->languageID == $sourcelanguageID) {
			if ($ruleset->setID == $sourcerulesetID) {
				echo "						<option selected value='" . $ruleset->setID . "'>" . $ruleset->name . "</option>";
			} else {
				echo "						<option value='" . $ruleset->setID . "'>" . $ruleset->name . "</option>";
			}
		}
	}
	echo "						</select>";
	echo "					</td>";
	echo "					<td>";
	echo "						<select id=targetrulesetselect  onchange=\"targetrulesetchange(this.value)\"  class=uitextfield style='width:163px;margin-right:4px;padding-top:0px;'>";
	if ($targetrulesetID == 0) {
		echo "						<option selected value='0'>All active rules</option>";
	} else {
		echo "						<option value='0'>All active rules</option>";
	}
	foreach($registry->rulesets as $index => $ruleset) {
		if ($ruleset->languageID == $targetlanguageID) {
			if ($ruleset->setID == $targetrulesetID) {
				echo "						<option selected value='" . $ruleset->setID . "'>" . $ruleset->name . "</option>";
			} else {
				echo "						<option value='" . $ruleset->setID . "'>" . $ruleset->name . "</option>";
			}
		}
	}
	echo "						</select>";
	echo "					</td>";
	echo "					<td>";
	echo "						<button  class=section-button onclick='analysesentence()'>Analysoi</button> ";
	echo "					</td>";
	echo "				</tr>";
	echo "			</table>";
	echo "		</td>";
	echo "	</tr>";
	
	
	
	

	echo "<script>";
	echo "		function forwardchange(languageID) {";
	echo "			var targetruleset = $('#targetrulesetselect').val();";
	echo "			var sourceruleset = $('#sourcerulesetselect').val();";
	echo "			var inputsentence = $('#inputsentence').val();";
	echo "			var sourcelanguage = $('#sourcelanguageselect').val();";
	echo "			var targetlanguage = $('#targetlanguageselect').val();";
	
	echo "			console.log(' source rule set - '+sourceruleset);";
	echo "			console.log(' target rule set - '+targetruleset);";
	echo "			console.log(' inputsentence - '+inputsentence);";
	echo "			console.log(' sourcelanguage - '+sourcelanguage);";
	echo "			console.log(' targetlanguage - '+targetlanguage);";
	
	echo "			window.location = '" . getUrl("worder/translate/translate") . "&sourcelanguageID='+sourcelanguage+'&targetlanguageID='+targetlanguage+'&sentence='+inputsentence+'&sourcesetID='+sourceruleset+'&targetsetID='+targetruleset;";
	//echo "			window.location = '" . getUrl("worder/translate/translate") . "&sourcelanguageID='+languageID;";
	echo "		}";
	echo "</script>";
	
	echo "<script>";
	echo "		function sourcelanguagechange(languageID) {";
	echo "			forwardchange();";
	echo "		}";
	echo "</script>";
	
	echo "<script>";
	echo "		function targetlanguagechange(languageID) {";
	echo "			forwardchange();";
	//echo "			console.log('target language changed - '+languageID);";
	//echo "			window.location = '" . getUrl("worder/translate/translate") . "&targetlanguageID='+languageID;";
	echo "		}";
	echo "</script>";
	

	echo "<script>";
	echo "		function sourcerulesetchange(setID) {";
	echo "			forwardchange();";
	//echo "			console.log('ruleset changed - '+setID);";
	//echo "			window.location = '" . getUrl("worder/translate/translate") . "&sourcesetID='+setID;";
	echo "		}";
	echo "</script>";
	
	echo "<script>";
	echo "		function targetrulesetchange(setID) {";
	echo "			forwardchange();";
	//echo "			console.log('ruleset changed - '+setID);";
	//echo "			window.location = '" . getUrl("worder/translate/translate") . "&targetsetID='+setID;";
	echo "		}";
	echo "</script>";
	
	
	
	echo "	<tr>";
	echo "		<td style='width:380px;'>";

	echo "			<div style='background-color:white;color:black;width:330px;height:80px;overflow-y:scroll;border:thin solid grey;'>";
	echo "				<table class=worderlist style='font-size:14px;' id='analysesentencetable'></table>";
	echo "			</div>";
	
	echo "			<div id='analyserulestablecontainer' style='background-color:white;color:black;width:330px;height:300px;overflow-y:scroll;border:thin solid grey;'>";
	echo "				<table class=worderlist  id='analyserulestable' style='width:310px; border-collapse:collapse'></table>";
	echo "			</div>";
	
	echo "			<div style='background-color:white;color:black;width:330px;height:50px;overflow-y:scroll;border:thin solid grey;'>";
	echo "				<table class=worderlist style='font-size:14px;' id='foundanalysestable'></table>";
	echo "			</div>";
	
	echo "			<div id='consolecontainer' style='background-color:white;color:black;width:330px;height:110px;overflow-y:scroll;border:thin solid grey;'>";
	echo "				<table class=worderlist style='font-size:14px;' id='console'></table>";
	echo "			</div>";
	
	/*
	echo "			<div style='background-color:white;color:black;width:330px;height:140px;overflow-y:scroll;border:thin solid grey;'>";
	echo "				<table class=worderlist style='font-size:14px;' id='sentencetable'></table>";
	echo "			</div>";
	*/
	
	echo "			<div id='generaterulestablecontainer' style='background-color:white;color:black;width:330px;height:330px;overflow-y:scroll;border:thin solid grey;'>";
	echo "				<table class=worderlist id='generaterulestable' style='width:310px; border-collapse:collapse'></table>";
	echo "			</div>";
	
	echo "			<div style='background-color:white;color:black;width:330px;height:50px;overflow-y:scroll;border:thin solid grey;'>";
	echo "				<table class=worderlist style='font-size:14px;width:100%' id='generateresults'></table>";
	echo "			</div>";
	echo "		</td>";

	echo "		<td style='width:800px;background-color:#e0e0e0;'>";
	echo "			<div style='height:465px;border:1px thin solid;'>";
	echo "					<div style='float:right;background-color:#e0e0e0;height:22px;width:350px;'>";
	echo "						<button onclick='analysenextstep()'>Next</button>";
	echo "						<button onclick='analyserZoomIn()'>+</button>";
	echo "						<button onclick='analyserZoomOut()'>-</button>";
	$checked = "";
	if ($this->registry->analysePauseOnCheckingRule == 1) $checked = "checked";
	echo "						<input id=analysecheckbox-1 type='checkbox' id=state1 title='Pause on Checking Rule' onclick='analyserPauseOncheckingRule(this.checked)' " . $checked . ">";
	$checked = "";
	if ($this->registry->analysePauseOnApplyRule == 1) $checked = "checked";
	echo "						<input id=analysecheckbox-2 type='checkbox' id=state1 title='Pause on Apply Rule' onclick='analyserPauseOnApplyRule(this.checked)' " . $checked . ">";
	$checked = "";
	if ($this->registry->analysePauseOnReverseRule == 1) $checked = "checked";
	echo "						<input id=analyseecheckbox-3 type='checkbox' id=state1 title='Pause on Reverse Rule' onclick='analyserPauseOnReverseRule(this.checked)' " . $checked . ">";
	$checked = "";
	if ($this->registry->analysePauseOnResultFound == 1) $checked = "checked";
	echo "						<input id=analysecheckbox-4 type='checkbox' id=state1 title='Pause on Result Found' onclick='analyserPauseOnResultFound(this.checked)' " . $checked . ">";
	echo "					</div>";
	echo "					<div style='float:left;background-color:#e0e0e0;height:22px;width:400px;font-weight:bold;padding-left:4px;'>";
	echo " Analyse: ";
	echo "					</div>";
	echo "				<div style='background-color:white;margin:0px;padding:10px;height:420px;width:800px;overflow:scroll;margin-top:4px;'>";
	echo "					<canvas id='analysecanvas' width=" . $gamescreenwidth . " height=" . $gamescreenheight . " style='border:0px solid red;'></canvas>";
	echo "				</div>";
	echo "			</div>";
	echo "			<div style='height:460px;border:1px thin solid;'>";
	echo "					<div style='float:right;background-color:#e0e0e0;height:22px;width:350px;'>";
	echo "						<button  onclick='showsemantic()'>Sem</button> ";
	echo "						<button  onclick='startgenerate()'>Start</button> ";
	echo "						<button onclick='generatenextstep()'>Next</button>";
	echo "						<button onclick='generatorZoomIn()'>+</button>";
	echo "						<button onclick='generatorZoomOut()'>-</button>";
	$checked = "";
	if ($this->registry->generatePauseOnCheckingRule == 1) $checked = "checked";
	echo "						<input id=generatecheckbox-1 type='checkbox' id=state1 title='Pause on Checking Rule' onclick='generatorPauseOncheckingRule(this.checked)' " . $checked . ">";
	$checked = "";
	if ($this->registry->generatePauseOnApplyRule == 1) $checked = "checked";
	echo "						<input id=generatecheckbox-2 type='checkbox' id=state1 title='Pause on Apply Rule' onclick='generatorPauseOnApplyRule(this.checked)' " . $checked . ">";
	$checked = "";
	if ($this->registry->generatePauseOnReverseRule == 1) $checked = "checked";
	echo "						<input id=generatecheckbox-3 type='checkbox' id=state1 title='Pause on Reverse Rule' onclick='generatorPauseOnReverseRule(this.checked)' " . $checked . ">";
	$checked = "";
	if ($this->registry->generatePauseOnResultFound == 1) $checked = "checked";
	echo "						<input id=generatecheckbox-4 type='checkbox' id=state1 title='Pause on Result Found' onclick='generatorPauseOnResultFound(this.checked)' " . $checked . ">";
	echo "					</div>";
	echo "					<div style='float:left;background-color:#e0e0e0;height:22px;width:400px;font-weight:bold;padding-left:4px;padding-top:2px;'>";
	echo " Generate: ";
	echo "					</div>";
	echo "				<div style='background-color:white;margin:0px;padding:10px;height:420px;width:800px;overflow:scroll;margin-top:4px;'>";
	echo "					<canvas id='generatecanvas' width=" . $gamescreenwidth . " height=" . $gamescreenheight . " style='border:0px solid red;'></canvas>";
	echo "				</div>";
	echo "			</div>";
	echo "		</td>";
	echo "	</tr>";
	
	/*
	echo "	<tr>";
	echo "		<td colspan=2 style='background-color:lightblue;'>";
	echo "			<div id='consolecontainer' style='background-color:white;color:black;width:1154px;height:100px;overflow-y:scroll;border:thin solid grey;'>";
	echo "				<table  style='font-size:14px;' id='console'></table>";
	echo "			</div>";
	echo "		</td>";
	echo "	</tr>";
	*/
	
	echo "</table>";
	
	
	
	echo "<script>";
	echo "		function analyserPauseOncheckingRule(value) {";
	//echo "			console.log('PauseOnCheckingRule - '+value);";
	echo "			var checkvalue = 0;";
	echo "			if (value == true) checkvalue = 1;";
	echo "			$.getJSON('" . getUrl('worder/translate/updateanalysepause') . "&item=1&check='+checkvalue,'',function(data) {";
	//echo "				console.log('updateanalysepause called');";
	//echo "				console.dir(data);";
	echo "			}); ";
	echo "			if (window.analyser != null) window.analyser.setPauseOnCheckingRule(value);";
	echo "		}";
	echo "		function analyserPauseOnApplyRule(value) {";
	//echo "			console.log('PauseOnApplyRule - '+value);";
	echo "			var checkvalue = 0;";
	echo "			if (value == true) checkvalue = 1;";
	echo "			$.getJSON('" . getUrl('worder/translate/updateanalysepause') . "&item=2&check='+checkvalue,'',function(data) {";
	//echo "				console.log('updateanalysepause called');";
	//echo "				console.dir(data);";
	echo "			}); ";
	echo "			if (window.analyser != null) window.analyser.setPauseOnApplyRule(value);";
	echo "		}";
	echo "		function analyserPauseOnReverseRule(value) {";
	//echo "			console.log('setPauseOnReverseRule - '+value);";
	echo "			var checkvalue = 0;";
	echo "			if (value == true) checkvalue = 1;";
	echo "			$.getJSON('" . getUrl('worder/translate/updateanalysepause') . "&item=3&check='+checkvalue,'',function(data) {";
	//echo "				console.log('updateanalysepause called');";
	//echo "				console.dir(data);";
	echo "			}); ";
	echo "			if (window.analyser != null) window.analyser.setPauseOnReverseRule(value);";
	echo "		}";
	echo "		function analyserPauseOnResultFound(value) {";
	//echo "			console.log('PauseOnResultFound - '+value);";
	echo "			var checkvalue = 0;";
	echo "			if (value == true) checkvalue = 1;";
	echo "			$.getJSON('" . getUrl('worder/translate/updateanalysepause') . "&item=4&check='+checkvalue,'',function(data) {";
	//echo "				console.log('updateanalysepause called');";
	//echo "				console.dir(data);";
	echo "			}); ";
	echo "			if (window.analyser != null) window.analyser.setPauseOnResultFound(value);";
	echo "		}";
	echo "		function analyzerZoomIn() {";
	echo "			alert('ZoomIn - Not implemented');";
	echo "		}";
	echo "		function analyzerZoomOut() {";
	echo "			alert('ZoomOut - Not implemented');";
	echo "		}";
	echo "</script>";
	
	
	
	echo "<script>";
	echo "		function analysenextstep() {";
	echo "			window.analyser.nextStep();";
	echo "		}";
	echo "</script>";
	

	echo "<script>";
	
	echo "		var globalwordfeatures = new Map();";
	echo "		var globalfeatures = new Map();";
	echo "		var globalwordconceptlinks = new Map();";
	//echo "		var globalwordfeatures = { };";
	//echo "		var globalfeatures =  { };";
	//echo "		var globalwordconceptlinks = { };";
	
	echo "		function analysesentence() {";
	echo "  		sentence = $('#inputsentence').val();";
	echo "			addConsoleMessage('Started analyse - '+sentence);";
	
	echo "			console.log('" . getUrl('worder/translate/analysewordsintegerJSON') . "&languageID=" . $sourcelanguageID . "&sentence='+sentence);";
	echo "			$.getJSON('" . getUrl('worder/translate/analysewordsintegerJSON') . "&languageID=" . $sourcelanguageID . "&sentence='+sentence,'',function(data) {";
	//echo "				console.log('sentence analysed returned');";
	
	echo "				window.analyser = new SyntaxAnalyser('analysecanvas', analyserules, resultrules, " . $gamescreenwidth  ."," . $gamescreenheight . ");";
	echo "				window.analyser.setRulesTableElement('analyserulestable', 'analyserulestablecontainer');";
	echo "				window.analyser.setFoundAnalysesTableElement('foundanalysestable');";
	echo "				window.analyser.setAnalyseSentencesTableElement('analysesentencetable');";
	echo "				window.analyser.setRuleSectionID(" . $rulesection->getID() . ");";
	
	echo "				var pauseOncheckingRule = $('#analysecheckbox-1').is(':checked');";
	echo "				window.analyser.setPauseOnCheckingRule(pauseOncheckingRule);";
	//echo "				console.log('pauseOncheckingRule - '+pauseOncheckingRule);";
	
	echo "				var pauseOnApplyRule = $('#analysecheckbox-2').is(':checked');";
	echo "				window.analyser.setPauseOnApplyRule(pauseOnApplyRule);";
	//echo "				console.log('pauseOnApplyRule - '+pauseOnApplyRule);";
	
	echo "				var pauseOnReverseRule = $('#analysecheckbox-3').is(':checked');";
	echo "				window.analyser.setPauseOnReverseRule(pauseOnReverseRule);";
	//echo "				console.log('pauseOnReverseRule - '+pauseOnReverseRule);";
	
	echo "				var pauseOnResultFound = $('#analysecheckbox-4').is(':checked');";
	echo "				window.analyser.setPauseOnResultFound(pauseOnResultFound);";
	//echo "				console.log('pauseOnResultFound - '+pauseOnResultFound);";
	
	//echo "				addConsoleMessage(''+data.length+' morpholigical analyses found');";
	echo "				$('#sentencetable').empty();";
	echo "				var fscount = 0;";
	echo "				var sentencecounter = 1;";
	echo "				$.each(data, function(index) {";

	//echo "					console.log(' - data - '+index);";
	echo "					if (index == 'sentences') {";
	echo "						var sentencedata = data[index];";
	echo "						var sentence = '';";
	echo "						var fs = null;";
	echo "						$.each(sentencedata, function(index2) {";
	//echo "							console.log(' -- sentencedata - '+index2);";
	echo "							var sentencecontent = sentencedata[index2];";
	//echo "							console.dir(sentencedata);";
	echo "							sentence = '';";
	echo "							conceptstr = '';";
	echo "							$.each(sentencecontent, function(index3) {";
	echo "								var sencont = sentencecontent[index3];";
	echo "								sentence = sentence + ' ' + sencont['word'];";
	echo "								if (conceptstr == '') {";
	echo "									conceptstr = sencont['conceptID'];";
	echo "								} else {";
	echo "									conceptstr = conceptstr + ',' + sencont['conceptID'];";
	echo "								}";
	echo "								fs = createFeatureStructureFromJSON(sencont);";
	//echo "								console.log('wordlink - '+sencont['wordID']+' - '+sencont['conceptID']);";
	echo "								globalwordconceptlinks.set(parseInt([sencont['conceptID']]), parseInt(sencont['wordID']));";
	echo "								analyser.addFeatureStructure(index2, fs);";
	echo "							});";
	echo "							window.analyser.addAnalyseSourceSentence(index2,sentence,conceptstr, 0);";
	echo "						});";
	echo "					}";

	// wordsien tuonti taitaa olla joko tarpeetonta tilanteessa, jossa kielet ovat eriä...
	echo "					if (index == 'words') {";
	
	echo "						var wordlist = data[index];";
	echo "						$.each(wordlist, function(index2) {";
	echo "							var worddata = wordlist[index2];";
	echo "							var wordID = worddata['wordID'];";
	//echo "							console.log(' wordID - '+wordID);";

	echo "							var wordfeatures = worddata['wordclassfeatures'];";
	echo "							var featurelist = new Map();";
	echo "							$.each(wordfeatures, function(featureindex) {";
	echo "								var featurevalue = wordfeatures[featureindex];";
	//echo "								console.log(' featurepair - '+featureindex+' - '+featurevalue);";
	echo "								featurelist.set(parseInt(featureindex), parseInt(featurevalue));";
	echo "							});";
	echo "							globalwordfeatures.set(parseInt(wordID), featurelist);";
	echo "						});";
	echo "					}";
	
	echo "					if (index == 'features') {";
	echo "						var featurelist = data[index];";
	echo "						$.each(featurelist, function(featureindex) {";
	echo "							var featurename = featurelist[featureindex];";
	//echo "							console.log(' featurepair - '+featureindex+' - '+featurename);";
	echo "							globalfeatures.set(parseInt(featureindex),parseInt(featurename));";
	echo "						});";
	echo "					}";
	echo "				});";
	
	//echo "				console.dir(globalwordfeatures);";
	//echo "				console.dir(globalfeatures);";
	
	echo "				window.analyser.reDrawSentences(0);";
	echo "				window.analyser.startAnalyse();";
	echo "			}); ";
	echo "		};";
	echo "</script>";
	
	echo "<script>";
	echo "	var analyserules = new Array();";
	echo "	var generaterules = new Array();";
	echo "	var resultrules = new Array();";
	
	echo "	$(document).ready(function() {";
	
	echo "  	sourcelanguage = $('#sourcelanguageselect').val();";
	echo "  	targetlanguage = $('#targetlanguageselect').val();";
	echo "  	sourceSetID = $('#sourcerulesetselect').val();";
	echo "  	targetSetID = $('#targetrulesetselect').val();";
	
	echo "		console.log('" . getUrl('worder/rules/getrulesfullJSON') . "&direction=analyse&sourcesetID='+sourceSetID+'&languageID=" . $sourcelanguageID . "');";
	echo "		$.getJSON('" . getUrl('worder/rules/getrulesfullJSON') . "&direction=analyse&sourcesetID='+sourceSetID+'&languageID=" . $sourcelanguageID . "','',function(data) {";
	echo "			$.each(data, function(index) {";
	echo "				var rule = parseRule(data[index]);";
	echo "				analyserules[index] = rule;";
	echo "			});";
	echo "			addConsoleMessage('Analyse rules loaded (' + data.length+')');";
	echo "		}); ";

	echo "		console.log('" . getUrl('worder/rules/getrulesfullJSON') . "&direction=generate&targetsetID='+targetSetID+'&languageID=" . $targetlanguageID . "');";
	echo "		$.getJSON('" . getUrl('worder/rules/getrulesfullJSON') . "&direction=generate&targetsetID='+targetSetID+'&languageID=" . $targetlanguageID . "','',function(data) {";
	echo "			$.each(data, function(index) {";
	echo "				var rule = parseRule(data[index]);";
	echo "				generaterules[index] = rule;";
	echo "			});";
	echo "			addConsoleMessage('Generate rules loaded (' + data.length+')');";
	echo "		}); ";
	
	echo "		console.log('" . getUrl('worder/rules/getresultrulesJSON') . "&languageID=" . $sourcelanguageID . "');";
	echo "		$.getJSON('" . getUrl('worder/rules/getresultrulesJSON') . "&languageID=" . $sourcelanguageID . "','',function(data) {";
	echo "			$.each(data, function(index) {";
	echo "				var rule = parseRule(data[index]);";
	echo "				resultrules[index] = rule;";
	echo "			});";
	echo "			addConsoleMessage('Result rules loaded (' + data.length+')');";
	echo "		}); ";
	
	echo "	});";
	echo "</script>";
	
	
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
	echo "		function showsemantic() {";
	echo "			fs = window.analyser.getActiveFS();";
	//echo "			console.log('analysefeature');";
	//echo "			console.dir(fs);";
	echo "			var copy = fs.getRecursiveSemanticCopyWithoutComponents();";
	echo "			console.log('copy');";
	echo "			console.dir(copy);";
	echo "			showFeatureStructure(copy,'rulecanvas', " . $rulesection->getID() . ");";
	
	echo "		};";
	echo "</script>";
	
	
	
	echo "<script>";
	echo "		function startgenerate() {";
	
	//echo "			console.log('-----------------------');";
	echo "			console.log('-----------------------');";
	echo "			console.log('startgenerate...');";
	echo "			window.generator = new SyntaxGenerator('generatecanvas', generaterules, " . $gamescreenwidth . "," . $gamescreenheight . ");";
	echo "			window.generator.setLanguageID(" . $targetlanguageID . ");";
	echo "			window.generator.setRulesTableElement('generaterulestable','generaterulestablecontainer');";
	echo "			window.generator.setGenerateResultTableElement('generateresults');";
	echo "			window.generator.setRuleSectionID(" . $rulesection->getID() . ");";
	
	echo "			var pauseOncheckingRule = $('#generatecheckbox-1').is(':checked');";
	echo "			window.generator.setPauseOnCheckingRule(pauseOncheckingRule);";
	
	echo "			var pauseOnApplyRule = $('#generatecheckbox-2').is(':checked');";
	echo "			window.generator.setPauseOnApplyRule(pauseOnApplyRule);";
	
	echo "			var pauseOnReverseRule = $('#generatecheckbox-3').is(':checked');";
	echo "			window.generator.setPauseOnReverseRule(pauseOnReverseRule);";
	
	echo "			var pauseOnResultFound = $('#generatecheckbox-4').is(':checked');";
	echo "			window.generator.setPauseOnResultFound(pauseOnResultFound);";
	
	echo "			fs = window.analyser.getActiveFS();";
	//echo "			console.log('analysefeature');";
	//echo "			console.dir(fs);";
	echo "			var copy = fs.getRecursiveSemanticCopy();";
	echo "			console.log('copy');";
	echo "			console.dir(copy);";
	//echo "			console.log('*******************');";
	//echo "			console.log('fs conceptID - '+fs.conceptID);";
	
	echo "			var targetfs = copy.getRecursiveTargetCopy();";
	echo "			console.log('targetfs');";
	echo "			console.dir(targetfs);";
	
	echo "			var conceptarray = new Array();";
	echo "			targetfs.getConceptsRecursively(conceptarray);";
	echo "			var conceptlist = '';";
	echo "			var first = true;";
	echo "			$.each(conceptarray, function(index) {";
	echo "				if (first == false) conceptlist += ':';";
	echo "				else first = false;";
	echo "				var conceptID = conceptarray[index];";
	//echo "				console.log(' -- conceptID - '+conceptID);";
	echo "				conceptlist += conceptID;";
	echo "			});";
	
	//echo "			globalwordconceptlinks = new Map();";
	//echo "			globalwordfeatures = new Map();";
	
	echo "			console.log('" . getUrl('worder/translate/getwordclassfeaturesJSON') . "&languageID=" . $targetlanguageID . "&concepts='+conceptlist);";
	echo "			$.getJSON('" . getUrl('worder/translate/getwordclassfeaturesJSON') . "&languageID=" . $targetlanguageID . "&concepts='+conceptlist,'',function(data) {";
	//echo "				console.log('getwordclassfeaturesJSON returned');";

	echo "				$.each(data, function(index) {";

	echo "					if (index == 'words') {";
	
	echo "						var wordlist = data[index];";
	echo "						$.each(wordlist, function(index2) {";
	echo "							var worddata = wordlist[index2];";
	echo "							var wordID = worddata['wordID'];";
	echo "							var conceptID = worddata['conceptID'];";
	//echo "							console.log(' wordID - '+wordID);";
	echo "							globalwordconceptlinks.set(parseInt(conceptID), parseInt(wordID));";
	
	echo "							var wordfeatures = worddata['wordclassfeatures'];";
	echo "							var featurelist = new Map();";
	echo "							$.each(wordfeatures, function(featureindex) {";
	echo "								var featurevalue = wordfeatures[featureindex];";
	//echo "								console.log(' featurepair - '+featureindex+' - '+featurevalue);";
	echo "								featurelist.set(parseInt(featureindex), parseInt(featurevalue));";
	echo "							});";
	echo "							globalwordfeatures.set(parseInt(wordID),featurelist);";
	echo "						});";
	echo "					}";
	
	// Tämä pitäisi olla jo ennestään haettu
	/*
	echo "					if (index == 'features') {";
	echo "						var featurelist = data[index];";
	echo "						$.each(featurelist, function(featureindex) {";
	echo "							var featurename = featurelist[featureindex];";
	//echo "							console.log(' featurepair - '+featureindex+' - '+featurename);";
	echo "							globalfeatures[featureindex] = featurename;";
	echo "						});";
	echo "					}";
	*/
	echo "				});";
	
	//echo "				console.dir(globalwordfeatures);";
	//echo "				console.dir(globalwordconceptlinks);";
	echo "				targetfs.setWordFeaturesRecursively(globalwordconceptlinks, globalwordfeatures, globalfeatures);";
	
	
	//echo "				console.log('*******************');";
	echo "				window.generator.setConceptStructure(targetfs);";
	echo "				window.generator.startGenerate();";
	//echo "				console.log('----------------------');";
	
	echo "			}); ";
	
	
	
	// TODO: tähän pitää lisätä targetlanguagen wordfeaturessien lataaminen...
	//			- Pitää hakea conceptit rekursiivisesti...
	
	echo "		};";
	echo "</script>";
	
	
	echo "<script>";
	echo "		function getUrl(name) {";
	echo "			url = '" . getRootUrl()  . "';";
	echo "			url += name;";
	echo "			return url;";
	echo "		}";
	echo "</script>";
	
	
	echo "<script>";
	echo "		function addConsoleMessage(message) {";
	echo "			var tr = document.createElement('tr');";
	echo "			td = document.createElement('td');";
	echo "			td.innerHTML = message;";
	echo "			td.className += ' myclass';";
	echo "			tr.append(td);";
	echo "			tr.append(td);";
	echo "			$('#console').append(tr);";
	echo "			var height = $('#console').height();";
	//echo "			console.log('consoleheight - '+height);";
	echo "			$('#consolecontainer').scrollTop(height);";
	//echo "			var scr = $('#consolecontainer')[0].scrollHeight;​";
	//echo "			$('#console').animate({scrollTop: scr},2000);​";
	//echo "			$('#consolecontainer').scrollTop($('#console').position().top);​";
	echo "		}";
	
	echo "</script>";
	

	// TODO tätä käytetään featurestructure.js:stä, olisi hyvä jotenkin siirtää sinne...
	echo "<script>";
	echo "		function isSharedFeature(feature) {";
	foreach( $registry->features as $index => $feature) {
		if (($feature->languageID == 0) && ($feature->parentID == 0)) {
			echo " if (feature == '" . $feature->featureID . "') return true;";
		}
	}
	echo "		}";
	echo "</script>";


	echo "<script>";
	echo "		function getSharedFeature(feature) {";
	//echo "			console.log('getshared - '+feature);";
	foreach( $registry->features as $index => $feature) {
		
		if ($feature->languageID == $sourcelanguageID) {
			if ($feature->semanticlinkID > 0) {
				$semantic = $registry->features[$feature->semanticlinkID];
				echo "if (feature == " . $feature->featureID . ") return " . $semantic->featureID. ";";
			}
		}
	}
	echo "		return 0;";
	echo "		}";
	echo "</script>";
	
	

	echo "<script>";
	echo "		function getSharedValue(feature) {";
	//echo "			console.log('getshared - '+feature);";
	foreach( $registry->features as $index => $feature) {
	
		if ($feature->languageID == $sourcelanguageID) {
			if ($feature->semanticlinkID > 0) {
				$semantic = $registry->features[$feature->semanticlinkID];
				echo "if (feature == " . $feature->featureID . ") return " . $semantic->featureID . ";";
			}
		}
	}
	echo "		return 0;";
	echo "		}";
	echo "</script>";
	
	
	

	echo "<script>";
	echo "		function getTargetFeature(feature) {";
	//echo "			console.log('getTargetFeature - '+feature);";
	//echo "			console.log('gettargetf- '+feature);";
	foreach( $registry->features as $index => $feature) {
		//echo "		console.log(' -- feature - " . $feature->featureID . "');";
		if ($feature->languageID == $targetlanguageID) {
			if ($feature->semanticlinkID > 0) {
				$semantic = $registry->features[$feature->semanticlinkID];
				echo "if (feature == " . $semantic->featureID . ") {";
				//echo "		console.log(' -- match - " . $feature->featureID . "');";
				echo "		return " . $feature->featureID . ";";
				echo "}";
			}
		}
	}
	echo "		return 0;";
	echo "		}";
	echo "</script>";
	
	
	
	echo "<script>";
	echo "		function getTargetValue(feature) {";
	//echo "			console.log('gettargetv - '+feature);";
	foreach( $registry->features as $index => $feature) {
	
		if ($feature->languageID == $targetlanguageID) {
			if ($feature->semanticlinkID > 0) {
				$semantic = $registry->features[$feature->semanticlinkID];
				echo "if (feature == " . $semantic->featureID . ") return " . $feature->featureID . ";";
			}
		}
	}
	echo "		return 0;";
	echo "		}";
	echo "</script>";
	
	
	
	
	// Mikäli kyseessä on shared feature, niin palautetaan valuen oikean arvon sijaan 
	// linkitetty value joka on shared featuren arvo.
	// TODO tätä käytetään rule.js:stä, olisi hyvä jotenkin siirtää sinne...
	echo "<script>";
	echo "		function linkSharedFeature(sharedfeature, linkvalue) {";
	echo "			console.log('asking shared feature value');";
	foreach( $registry->features as $index => $feature) {
		$linkcount = 0;
		if (($feature->languageID == 0) && ($feature->parentID == 0)) {
			echo "		if (sharedfeature == '" . $feature->name . "') {";
			foreach($registry->features as $index2 => $childfeature) {
				if ($childfeature->parentID == $feature->featureID) {
					foreach($registry->features as $index3 => $linkfeature) {
						if (($childfeature->featureID == $linkfeature->semanticlinkID) && ($linkfeature->languageID == $sourcelanguageID)) {
							echo "if (linkvalue == '" . $linkfeature->name . "') return '" . $childfeature->name . "';";
						}						
					}
				}
			}
			echo "	return null;";
			echo "		}";
		}
	}
	echo "	return null;";
	echo "		}";
	echo "</script>";
	
	
	
	echo "<script>";
	
	echo "	let fParents = new Map();";
	foreach( $registry->features as $index => $feature) {
		echo "		fParents.set(" . $feature->featureID . ",'" . $feature->parentID . "');";
	}
	
	
	echo "	let fAbbs = new Map();";
	foreach( $registry->features as $index => $feature) {
		echo "		fAbbs.set(" . $feature->featureID . ",'" . $feature->abbreviation . "');";
	}

	echo "	let fNames = new Map();";
	foreach( $registry->features as $index => $feature) {
		echo "		fNames.set(" . $feature->featureID . ",'" . $feature->name . "');";
	}
	
	echo "	let cAbbs = new Map();";
	foreach( $registry->components as $index => $component) {
		echo "		cAbbs.set(" . $component->componentID . ",'" . $component->abbreviation . "');";
	}
	
	echo "	let aNames = new Map();";
	foreach( $registry->arguments as $index => $argument) {
		echo "		aNames.set(" . $argument->argumentID . ",'" . $argument->name . "');";
	}
	

	echo "	let wAbbs = new Map();";
	foreach( $registry->wordclasses as $index => $wordclass) {
		echo "		wAbbs.set(" . $wordclass->wordclassID . ",'" . $wordclass->abbreviation . "');";
	}

	echo "	let wNames = new Map();";
	foreach( $registry->wordclasses as $index => $wordclass) {
		echo "		wNames.set(" . $wordclass->wordclassID . ",'" . $wordclass->name . "');";
	}
	
	//echo "		console.log(' fNames - '+fNames.get(59));";
	//echo "		console.log(' fAbbs - '+fAbbs.get(59));";
	//echo "		console.log(' cAbbs - '+cAbbs.get(110));";
	//echo "		console.log(' aNames - '+aNames.get(18));";
	echo "</script>";
	
	
?>
