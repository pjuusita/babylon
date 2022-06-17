<?php


class FeaturesController extends AbstractController {
	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
		//return array('menu.css', 'main.css','mytheme/jquery-ui.css','worder.css','yritys.css','prism.css','chosen.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}
		
	
	public function indexAction() {
		//$this->showfeaturesAction();
		$this->registry->template->show('system/error','unknown');
	}
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	/*
	public function showfeaturelistAction() {
		
		$this->registry->features = Table::loadHierarchy('worder_features','parentID',"WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->languages= Table::load('worder_languages',"WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$this->registry->template->show('worder/features','featurelist');
	}
	*/
	
	

	public function showfeatureAction() {
		
		$featureID = $_GET['id'];
		$this->registry->feature = Table::loadRow('worder_features',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND FeatureID=" . $featureID);

		if ($this->registry->feature == null) {
			echo "<br>Feature not found";
			redirecttotal('worder/features/showfeatures',null);
			return;
		}
		
		$this->registry->features = Table::load('worder_features',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->feature->languageID);
		$this->registry->language = Table::loadRow('worder_languages',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->feature->languageID);
		$this->registry->semanticfeatures = Table::load('worder_features',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=0");
		
		updateActionPath($this->registry->feature->name);
		
		// Tässä ladataan kaikki taulut, joissa asianomaista featurea on käytetty...
		$rulelist = array();
		
		// 34 - worder_wordclasses
		//			- SourcefeatureID-sarake, ei käytössä
		//			- Features-sarake, lista ei käytössä
		
		// 37 - worder_features
		//			- FeatureID, pääavain ei huomioida viitteitä tarkistettaessa
		//			- ParentID, tämä pitää huomioida...
		$this->registry->childfeatures = Table::load('worder_features',"WHERE ParentID=" . $featureID);
		
		// 63 - worder_rulefeatureconstraints
		//			- featureID (TODO: tämä ei jostainsyystä ole tableReference tietokannassa)
		$this->registry->rulefeatureconstraints = Table::load('worder_rulefeatureconstraints',"WHERE FeatureID=" . $featureID . " OR FeaturevalueID=" . $featureID);
		foreach($this->registry->rulefeatureconstraints as $index => $constraint) {
			$rulelist[$constraint->ruleID] = $constraint->ruleID; 
		}
		
		//foreach($this->registry->rulefeatureconstraints as $index => $row) {
		//	$rulelist[$row->ruleID] = $row->ruleID;
		//}

		// 98 - worder_wordclassfeatures
		//			- wordbookformID		muutamia löytyy, tämä lienee vanhentunut ja tarpeeton
		//			- semanticdefaultID		muutamia esiintyy, tämä lienee vanhentunut, käytössä ehkä defaultvalueID
		//			- defaultvalueID		muutamia esiintyy, tämä lienee tarpeellinen
		//			- featureID				oleellinen
		$this->registry->wordclassfeatures = Table::load('worder_wordclassfeatures',"WHERE FeatureID=" . $featureID . " OR DefaultvalueID=" . $featureID);
		
		// 100 - worder_rulefeatureagreements
		//			- featureID
		$this->registry->rulefeatureagreements = Table::load('worder_rulefeatureagreements',"WHERE FeatureID=" . $featureID);
		foreach($this->registry->rulefeatureagreements as $index => $row) {
			$rulelist[$row->ruleID] = $row->ruleID;
		}
		
		
		// 146 - worder_ruleresultfeatures
		//			- featureID
		$this->registry->ruleresultfeatures = Table::load('worder_ruleresultfeatures',"WHERE FeatureID=" . $featureID . " OR ValueID=" . $featureID);
		foreach($this->registry->ruleresultfeatures as $index => $row) {
			$rulelist[$row->ruleID] = $row->ruleID;
		}
		
		// 154 - wordfeaturelinks
		$this->registry->wordfeaturelinks = Table::load('worder_wordfeaturelinks',"WHERE FeatureID=" . $featureID . " OR ValueID=" . $featureID);
		
		$wordlist = array();
		foreach($this->registry->wordfeaturelinks as $index => $link) {
			$wordlist[$link->wordID] = $link->wordID;
		}
		$this->registry->words = Table::loadWhereInArray('worder_words', 'WordID', $wordlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		
		// 155 - worder_ruleunsets
		$this->registry->ruleunsets = Table::load('worder_ruleunsets',"WHERE FeatureID=" . $featureID);
		
		// 160 - worder_inflectionsetitems
		//			- featureID
		//			- features
		//			- parentfeatureID
		$this->registry->inflectionsetitems = Table::load('worder_inflectionsetitems',"WHERE FeatureID=" . $featureID . " OR ParentfeatureID=" . $featureID);
		
		// TODO: pitää periaatteessa vielä tsekata features-taulukko...
		$this->registry->semanticlinks= Table::load('worder_features',"WHERE SemanticlinkID=" . $featureID);
		
		
		$this->registry->generatefeatures = Table::load('worder_objectivegeneratefeatures',"WHERE FeatureID=" . $featureID . " OR ObjectiveID=" . $featureID);
		
		
		// TODO: Sitten on vielä kokojoukko words, ja wordfeatures, jotka on arrayssä sisällä
		
		$this->registry->rules = Table::loadWhereInArray("worder_rules", "ruleID", $rulelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		
		
		// worder_wordforms ...
		// pitää etsiä regexpillä:  '121:' tai sitten hankalampi ':121' (viimeinen)
		// haku ei taida onnistua lopusta, pitänee muuttaa tallennusta...
		
		
		$this->registry->template->show('worder/features','feature');
	}
	
	

	public function showfeaturesAction() {
	
		$languageID = getModuleSessionVar('grammarlanguageID', 0);
		
		if (isset($_GET['languageID'])) {
			$languageID = $_GET['languageID'];
			setModuleSessionVar('grammarlanguageID', $languageID);
		}
		//echo "<br>LanguageID - " . $languageID;
		
		updateActionPath("Features");
		//echo "<br>LanguageID - " . $languageID;
		$this->registry->languages = Table::load('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID']);
		if ($languageID > 0) {
			if (!isset($this->registry->languages[$languageID])) {
				foreach ($this->registry->languages as $index => $language) {
					$languageID = $language->languageID;
					break;
				}
			}
		}
		$this->registry->languageID = $languageID;
		
		if ($languageID > 0) $this->registry->language = $this->registry->languages[$languageID];
		$this->registry->grammar =  Table::loadRow('worder_grammars', $_SESSION['grammarID']);
		

		$seletion = array();
		if (count($this->registry->languages) > 1) {
			$row = new Row();
			$row->name = 'Semantics';
			$row->languageID = 0;
			$selection[0] = $row;
		}
		foreach	($this->registry->languages as $index => $language) {
			$selection[$language->languageID] = $language;
		}
		$this->registry->selection = $selection;
		
		
		if (count($this->registry->languages) > 1) {
			//echo "<br>shared over one";
			//$this->registry->sharedfeatures = Table::loadHierarchy('worder_features','parentID',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=0", false);
		} else {
			//echo "<br>shared nolla";
			$this->registry->sharedfeatures = array();
		}
		
		$this->registry->sharedfeatures = Table::loadHierarchy('worder_features','parentID',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=0", false);
		$this->registry->features = Table::loadHierarchy('worder_features','parentID',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=".$languageID . " ORDER BY Sortorder", false);
		
		if ($this->registry->sharedfeatures == null) {
			$this->registry->sharedfeatures = array();
		}
		
		$this->registry->featurelist = Table::load('worder_features',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND (LanguageID=".$languageID . " OR LanguageID=0)", false);
		
		$this->registry->template->show('worder/features','featurelist');
	}
	
	
	

	public function insertfeatureAction () {
	
		$values['Abbreviation'] = $_GET['Abbreviation'];
		$values['ParentID'] = $_GET['ParentID'];
		$values['Description'] = $_GET['Description'];
		$values['Name'] = $_GET['Name'];
		$values['GrammarID'] = $_SESSION['grammarID'];
		
		$success = Table::addRow("worder_features", $values);
	
		
		if ($success === true) {
			addMessage('Lisätty onnistuneesti. - ' . $name . " - " . $number . " - " . $parentID);
		} else {
			addErrorMessage("Tuntematon tietokantavirhe. - " . $success);
		}

		$parentID = $_GET['ParentID'];
		//redirecttotal('worder/features/showfeatures',null, "anchor" . $parentID );
	}
	
	

	public function updatefeatureAction() {
	
		$featureID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		
		if (isset($_GET['languageID'])) {
			$values['LanguageID'] = $_GET['languageID'];
		}
		
		$values['Abbreviation'] = $_GET['abbreviation'];
		$values['ParentID'] = $_GET['parentID'];
		$values['Description'] = $_GET['description'];
		if (isset($_GET['semanticlinkID'])) {
			//echo "<br>isset";
			$values['SemanticlinkID'] = $_GET['semanticlinkID'];
			if ($values['SemanticlinkID']  == "") $values['SemanticlinkID'] = "";
		} else {
			$values['SemanticlinkID'] = "";
		}
		$values['ParentID'] = $_GET['parentID'];
		$success = Table::updateRow("worder_features", $values,"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND FeatureID=" . $featureID);
		
		redirecttotal('worder/features/showfeature&id=' . $featureID, null);
	}
	
	

	public function removefeatureAction() {
		
		$languageID = $_GET['languageID'];
		$featureID = $_GET['id'];
		
		$success = Table::deleteRow('worder_features',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND FeatureID=" . $featureID);
		redirecttotal('worder/features/showfeatures', null);
	}
	
	
	
	public function movefeatureAction() {
	
		$featureID = $_GET['id'];
		$comments = false;
		$orderby = "";
		if (isset($_GET['dir'])) {
			$direction = $_GET['dir'];
			if ($direction == 'down') $orderby = "DESC";
		}
		$feature = Table::loadRow("worder_features", "WHERE FeatureID=" . $featureID . " AND GrammarID=" . $_SESSION['grammarID']);
		if ($comments) echo "<br>Parentti - "  . $feature->parentID;
		
		$features = Table::load("worder_features", "WHERE ParentID=" . $feature->parentID . " AND GrammarID=" . $_SESSION['grammarID'] . " ORDER BY Sortorder " . $orderby);
	
		if ($comments) echo "<br>count - " . count($features);
		$current = null;
		$previous = null;
		foreach($features as $index => $feature) {
				
			if ($comments) echo "<br>Loop - " . $feature->name;
	
			if ($feature->featureID == $featureID) {
				$current = $feature;
				if ($previous == null) {
					if ($comments) echo "<br>Already first";
					$previous = null;
					break;
				} else {
					//$previousID = $objective->rowID;
					break;
				}
			}
			$previous = $feature;
		}
	
		if ($comments) echo "<br>Previous - " . $previous->name;
		if ($comments) echo "<br>Current - " . $current->name;
		
		if (($previous != null) && ($current != null)) {
				
			global $mysqli;
				
				
			$sql = "UPDATE worder_features SET Sortorder='" . $previous->sortorder . "' WHERE FeatureID=" . $current->featureID . "";
			//echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) echo "<br>Error: " . $mysqli->error;
				
			$sql = "UPDATE worder_features SET Sortorder='" . $current->sortorder . "' WHERE FeatureID=" . $previous->featureID . "";
			//echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) echo "<br>Error: " . $mysqli->error;
		}
		
		if (!$comments) redirecttotal('worder/features/showfeatures', null);
	}
	
	
	
	public function addfeatureAction() {
		
		$languageID = 0;
		if (isset($_GET['languageID'])) {
			$languageID = $_GET['languageID'];
		} else {
			$languageID = $_SESSION['grammarlanguageID'];
		}
		$values = array();
		$values['LanguageID'] = $languageID;
		$values['ParentID'] = $_GET['parentID'];;
		$values['Name'] = $_GET['name'];
		if ($_GET['abbreviation'] == '') {
			$values['Abbreviation'] = $_GET['name'];
		} else {
			$values['Abbreviation'] = $_GET['abbreviation'];
		}
		$values['GrammarID'] = $_SESSION['grammarID'];
		$rowID = Table::addRow("worder_features", $values);
		redirecttotal('worder/features/showfeatures&languageID=' . $languageID, null);
	}
	
	
	// Tätä käytetään tietokantascriptissä - CreateEnglishNounArticles, voidaan poistaa kun hoidettu
	public function addArticletypeAction() {
	
		global $mysqli;
		
		$comments = true;
		$wordID = $_GET['wordID'];
		$word = Table::loadRow('worder_words', "WHERE WordID=" . $wordID . " AND GrammarID=" . $_SESSION['grammarID'], true);
		
		$str = $word->features;
		if ($str == "") {
			$str = "252:" . $_GET['featureID'] . ":" . $wordID;
		} else {
			$str = $str . "|252:" . $_GET['featureID'] . ":" . $wordID;
		}
		$sql = "UPDATE worder_words SET Features='" . $str . "' WHERE WordID=" . $wordID;
		$result = $mysqli->query($sql);
		if (!$result) {
			die("Error 1: " . $mysqli->connect_error);
		}
		
		
		
		$values = array();
		$values['LanguageID'] = 2;
		$values['WordclassID'] = 1;
		$values['WordID'] = $wordID;
		$values['ValueID'] = $_GET['featureID'];
		$values['WordclassID'] = 1;
		$values['FeatureID'] = 252;
		$values['InheritancemodeID'] = 1;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$rowID = Table::addRow("worder_wordfeaturelinks", $values, true);
		
		echo "1";
		
	}
	
	
	// Tätä käytetään tietokantascriptissä - CreateEnglishNounArticles, voidaan poistaa kun hoidettu
	public function setAdessiveRoleAction() {
	
		global $mysqli;
	
		$parentfeatureID = 997;
		$comments = true;
		$wordID = $_GET['wordID'];
		$word = Table::loadRow('worder_words', "WHERE WordID=" . $wordID . " AND GrammarID=" . $_SESSION['grammarID'], true);
	
		$str = $word->features;
		if ($str == "") {
			$str = $parentfeatureID . ":" . $_GET['featureID'] . ":" . $wordID;
		} else {
			$str = $str . "|" . $parentfeatureID . ":" . $_GET['featureID'] . ":" . $wordID;
		}
		$sql = "UPDATE worder_words SET Features='" . $str . "' WHERE WordID=" . $wordID;
		$result = $mysqli->query($sql);
		if (!$result) {
			die("Error 1: " . $mysqli->connect_error);
		}
	
	
	
		$values = array();
		$values['LanguageID'] = 1;
		$values['WordclassID'] = 1;
		$values['WordID'] = $wordID;
		$values['ValueID'] = $_GET['featureID'];
		$values['WordclassID'] = 1;
		$values['FeatureID'] = $parentfeatureID;
		$values['InheritancemodeID'] = 1;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$rowID = Table::addRow("worder_wordfeaturelinks", $values, true);
	
		echo "1";
	
	}
	
	
	
	
	public function getfeaturesAction() {
		$languageID = $_GET['languageID'];
		
		$features = Table::load('worder_features', " WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " AND ParentID=0");
		
		echo "[";
		$first = true;
		foreach($features as $index => $feature) {
			if ($first == true) $first = false; else echo ",";
		
			echo " {";
			echo "	  \"featureID\":\"" . $feature->featureID . "\",";
			echo "	  \"name\":\"" . $feature->name . "\"";
			echo " }\n";
		}
		echo "]";
	}
	
	

	public function getchildsemanticfeaturesAction() {
		$languageID = $_GET['languageID'];
		$featureID = $_GET['featureID'];
		
		$feature = Table::loadRow('worder_features',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND FeatureID=" . $featureID);
		
		if ($feature == null) {
			echo "[]";
			return;
		}
		
		if ($feature->semanticlinkID == null) {
			echo "[]";
			return;
		}
		
		
		//echo "<br>feature - " . $feature->name;
		//echo "<br>semanticlinkID - " . $feature->semanticlinkID;
		
		$features = Table::load('worder_features', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=0 AND ParentID=" . $feature->semanticlinkID);
	
		echo "[";
		$first = true;
		foreach($features as $index => $feature) {
			if ($first == true) $first = false; else echo ",";
	
			echo " {";
			echo "	  \"featureID\":\"" . $feature->featureID . "\",";
			echo "	  \"name\":\"" . $feature->name . "\"";
			echo " }\n";
		}
		echo "]";
	}
	
	

	public function getfeaturesvaluesAction() {

		$featureID = $_GET['featureID'];
		if (isset($_GET['self'])) {
			$features = Table::load('worder_features', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND (ParentID=" . $featureID. " OR FeatureID=" . $featureID . ") ORDER BY Sortorder");
		} else {
			$features = Table::load('worder_features', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ParentID=" . $featureID. " ORDER BY Sortorder");
		}
	
		echo "[";
		$first = true;
		foreach($features as $index => $feature) {
			if ($first == true) $first = false; else echo ",";
	
			echo " {";
			echo "	  \"featureID\":\"" . $feature->featureID . "\",";
			echo "	  \"name\":\"" . $feature->name . "\"";
			echo " }\n";
		}
		echo "]";
	}
	
	
	/*
	public function getwordclassfeaturesAction() {
	
		$wordclassID = $_GET['wordclassID'];
		$languageID = $_GET['languageID'];
		$featurelinks = Table::load('worder_wordclassfeatures', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $featureID. " AND LanguageID=" . $languageID);
		
		$features = Table::loadWhereInArray('worder_features','featureID', $featurelinks, "WHERE SystemID=" . $_SESSION['systemID'] . " AND GrammarID=" . $_SESSION['grammarID']);
		
		
		echo "[";
		$first = true;
		foreach($features as $index => $feature) {
			if ($first == true) $first = false; else echo ",";
	
			echo " {";
			echo "	  \"featureID\":\"" . $feature->featureID . "\",";
			echo "	  \"name\":\"" . $feature->name . "\"";
			echo " }\n";
		}
		echo "]";
	}
	*/
	

	public function getwordclassfeaturesAction() {
	
		$wordclassID = $_GET['wordclassID'];
		$languageID = $_GET['languageID'];
		$featurelinks = Table::load('worder_wordclassfeatures', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $wordclassID. " AND LanguageID=" . $languageID);
		$featurelist = array();
		foreach($featurelinks as $rowID => $link) {
			$featurelist[$link->featureID] = $link->inflectional;
		}
		$features = Table::loadWhereInArray("worder_features","featureID", $featurelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		echo "[";
		$first = true;
		foreach($features as $index => $feature) {
			if ($first == true) $first = false; else echo ",";
	
			$inflectional = $featurelist[$feature->featureID];
			echo " {";
			echo "	  \"featureID\":\"" . $feature->featureID . "\",";
			echo "	  \"inflectional\":\"" . $inflectional. "\", ";
			echo "	  \"name\":\"" . $feature->name . "\"";
			echo " }\n";
		}
		echo "]";
	}
}
?>