<?php


function cmpLanguageWords($a, $b) {
	if ($a[5] < $b[5]) return -1;
	return 1;	
}



class GroupsController extends AbstractController {
	
	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
		//return array('menu.css', 'main.css','mytheme/jquery-ui.css','worder.css','yritys.css','prism.css','chosen.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','chosen.jquery.js','prism.js');
	}
		
	
	public function indexAction() {
		//$this->showgrouplistAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showgrouplistAction() {
	
		if (isset($_GET['languageid'])) {
			$languageID = $_GET['languageid'];
			$this->registry->grouptypeID = getSessionVar('grouptypeID-'.$languageID, 0);
		} else {
			$this->registry->grouptypeID = getSessionVar('grouptypeID', 0);
			$this->registry->languageID = getSessionVar('languageID', 0);
		}
		
		
		if ($this->registry->grouptypeID == "") {
			$this->registry->grouptypeID = 0;
		}
		if ($this->registry->languageID == "") {
			$this->registry->languageID = 1;
		}
		
		//echo "<br>Language - " . $this->registry->languageID;
		//echo "<br>Grouptype - " . $this->registry->grouptypeID;
		
		
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->allgroups = Table::load('worder_wordgroups',' WHERE GrouptypeID=' . $this->registry->grouptypeID . ' AND LanguageID=' . $this->registry->languageID);

		// TODO: grouplinksejä ladataan liikaa näille pitäisi myäs asettaa languageID
		$this->registry->grouplinks = Table::load('worder_wordgrouplinks', ' WHERE GrouptypeID=' . $this->registry->grouptypeID);	// nollat on ekana
		$this->registry->groupselection = Table::load('worder_wordgroups',' WHERE GrouptypeID=' . $this->registry->grouptypeID . ' AND LanguageID=' . $this->registry->languageID . " ORDER BY Name");
		
		
		$hierarchy = array();
		foreach($this->registry->grouplinks as $index => $link) {
			
			if ($link->parentgroupID == 0) {
				if (isset($this->registry->allgroups[$link->wordgroupID])) {
					$group = $this->registry->allgroups[$link->wordgroupID];
					$hierarchy[] = $group;
				}
			} else {
				if (isset($this->registry->allgroups[$link->wordgroupID])) {
					$group = $this->registry->allgroups[$link->wordgroupID];
					$group->parentID = $link->parentgroupID;
					//echo "<br>base - " . $group->name;
					$parentgroup = $this->registry->allgroups[$link->parentgroupID];
					//echo "<br>addchild - " . $group->name . " - " . $parentgroup->name;
					$parentgroup->addChild($group);
				}
			}
		}
		
		$this->registry->groups = $hierarchy;
		$this->registry->grouptypes = Table::load('worder_wordgrouptypes');
	
		$this->registry->template->show('worder/groups','groups');
	}
	
	
	
	// En tiedä onko muut oikeastaan enää käytössä, mutta featuregroupsit on uusi ominaisuus
	public function showfeaturegroupsAction() {
		
		$languageID = getSessionVar('languageID',0);
		$featureID = getSessionVar('featureID',0);
		
		$this->registry->languageID = $languageID;
		$this->registry->featureID = $featureID;
		
		$this->registry->languages = Table::load('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->features = Table::load('worder_features', "WHERE GrammarID=" . $_SESSION['grammarID']. " AND LanguageID=" . $languageID);
		
		echo "<br>LanguageID - " . $languageID;
		echo "<br>FeatureID - " . $featureID;
		//$this->registry->featureID = 0;
		
		foreach($this->registry->features as $index => $value) {
			//echo "<br>feature - " . $index . " - " . $value->name;
		}
		
		/*
		$this->registry->wordclassfeatures = Table::load('worder_wordclassfeatures', "WHERE GrammarID=" . $_SESSION['grammarID']. " AND LanguageID=" . $languageID);
		$selectedfeatures = array();
		foreach($this->registry->wordclassfeatures as $index => $feature) {
			if ()
		}
		*/
		
		
		if (($languageID > 0) && ($featureID > 0)) {

			$this->registry->links = Table::load('worder_wordfeaturelinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ValueID=" . $featureID);
			
			$wordlist = array();
			foreach($this->registry->links as $index => $link) {
				$wordlist[$link->wordID] = $link->wordID;
			}
			$this->registry->words = Table::loadWhereInArray('worder_words','wordID',$wordlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
				
		}
		
		$this->registry->template->show('worder/groups','featuregroups');		
	}
	
	
	
	/**
	 * Periaatteessa sama kuin edellinen, mutta tämä on staattiselta languageID:llä
	 * Tämän pystyisi varmaan toteuttamaan jotenkin hienomminkin
	 * 
	 */
	public function showlanguagegrouplistAction() {
	
		if (isset($_GET['languageid'])) {
			$languageID = $_GET['languageid'];
			$this->registry->grouptypeID = getSessionVar('grouptypeID', 0);
		}
	
	
		if ($this->registry->grouptypeID == "") {
			$this->registry->grouptypeID = 0;
		}
		if ($this->registry->languageID == "") {
			$this->registry->languageID = 1;
		}
	
		$this->registry->staticlanguageID = $this->registry->languageID;
		//echo "<br>Language - " . $this->registry->languageID;
		//echo "<br>Grouptype - " . $this->registry->grouptypeID;
		
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->allgroups = Table::load('worder_wordgroups',' WHERE GrouptypeID=' . $this->registry->grouptypeID);
	
		// TODO: grouplinksejä ladataan liikaa näille pitäisi myäs asettaa languageID
		$this->registry->grouplinks = Table::load('worder_wordgrouplinks', ' WHERE GrouptypeID=' . $this->registry->grouptypeID);	// nollat on ekana
		$this->registry->groupselection = Table::load('worder_wordgroups',' WHERE GrouptypeID=' . $this->registry->grouptypeID . ' AND LanguageID=' . $this->registry->languageID . " ORDER BY Name");
	
	
		$hierarchy = array();
		foreach($this->registry->grouplinks as $index => $link) {
				
			if ($link->parentgroupID == 0) {
				if (isset($this->registry->allgroups[$link->wordgroupID])) {
					$group = $this->registry->allgroups[$link->wordgroupID];
					$hierarchy[] = $group;
				}
			} else {
				if (isset($this->registry->allgroups[$link->wordgroupID])) {
					$group = $this->registry->allgroups[$link->wordgroupID];
					$group->parentID = $link->parentgroupID;
					//echo "<br>base - " . $group->name;
					$parentgroup = $this->registry->allgroups[$link->parentgroupID];
					//echo "<br>addchild - " . $group->name . " - " . $parentgroup->name;
					$parentgroup->addChild($group);
				}
			}
		}
	
		$this->registry->groups = $hierarchy;
		$this->registry->grouptypes = Table::load('worder_wordgrouptypes');
	
		$this->registry->template->show('worder/groups','languagegroups');
	}
	
	
	

	public function showallwordsAction() {
	
		
		$this->registry->allgroups = Table::load('worder_wordgroups');
		$this->registry->grouplinks = Table::load('worder_wordgrouplinks');
		$this->registry->conceptlinks = Table::load('worder_wordgroupconcepts');
		
		
	
		$conceptlist = array();
		foreach($this->registry->conceptlinks as $index => $link) {
			$conceptlist[$link->conceptID] = $link->conceptID;
			if (isset($conceptgroups[$link->conceptID])) {
				$conceptgroups[$link->conceptID][$link->wordgroupID] = $link->wordgroupID;
			} else {
				$conceptgroups[$link->conceptID] = array();
				$conceptgroups[$link->conceptID][$link->wordgroupID] = $link->wordgroupID;
			}
		}
	

		$this->registry->concepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$returntable = array();
		$group1count = 0;
		$group2count = 0;
		$group3count = 0;
		foreach($this->registry->concepts as $index => $concept) {
			$content = array();
			$content[0] = $concept->conceptID;
			$content[1] = $concept->name;
			//echo "<br>" . $concept->name;
			
			$content[2] = '-';
			$content[3] = '-';
			$content[4] = '-';
				
			
			foreach($conceptgroups[$concept->conceptID] as $index2 => $wordgroupID) {
				$group = $this->registry->allgroups[$wordgroupID];
				//echo "<br> -- " . $group->name;
				$grouptypeID = $group->grouptypeID;
				if ($grouptypeID == 1) {
					//echo "<br> -- grouptype - 1";
					$content[2] = 'X';
					$group1count++;
				}
				if ($grouptypeID == 2) {
					//echo "<br> -- grouptype - 2";
					$content[3] = 'X';
					$group2count++;
				}
				if ($grouptypeID == 3) {
					//echo "<br> -- grouptype - 3";
					$content[4] = 'X';
					$group3count++;
				}
			}
			$returntable[] = $content;
		}
	
		$this->registry->count1 = $group1count;
		$this->registry->count2 = $group2count;
		$this->registry->count3 = $group3count;
		
		$this->registry->table = $returntable;
		$this->registry->template->show('worder/groups','groupwords');
	}
	
	public function recursivePrintChilden($row, $level) {
		//var_dump($row);
		echo "<br>" .  $row->name . " - " . $row->getChildCount();
		if ($row->getChildren() != null) {
			foreach($row->getChildren() as $index => $rowxxs) {
				echo "<br>";
				for($i = 0;$i < $level;$i++) echo " --- ";
				echo " child " .  $rowxxs->name . " - ";
				$this->recursivePrintChilden($rowxxs, ($level+1));
			}
		}
	}
	
	
	public function showgrouplistoldAction() {
	
		$this->registry->grouptypeID = getSessionVar('grouptypeID', 0);
		
		if ($this->registry->grouptypeID == 0) {
			$this->registry->groups = Table::load('worder_wordgroups');
			$this->registry->groups = Table::load('worder_wordgroups','parentID', 'ORDER BY Sortorder');
			$this->registry->allgroups = Table::load('worder_wordgroups', ' ORDER BY Name');
		} else {
			$this->registry->groups = Table::load('worder_wordgroups','parentID', ' WHERE GrouptypeID=' . $this->registry->grouptypeID . ' ORDER BY Sortorder');
			$this->registry->allgroups = Table::load('worder_wordgroups', ' WHERE GrouptypeID=' . $this->registry->grouptypeID . ' ORDER BY Name');
		}
		$this->registry->grouptypes = Table::load('worder_wordgrouptypes');
	}
	
	
	
	public function showgroupcountsAction() {
		
		global $mysqli;
		
		$this->registry->grouptypeID = getSessionVar('grouptypeID', 0);
		
		$this->registry->allgroups = Table::load('worder_wordgroups', ' ORDER BY Name');
		
		//echo "<br>Jee - "  . $this->registry->grouptypeID;
		
		if ($this->registry->grouptypeID == 0) {
			$this->registry->groups = Table::load('worder_wordgroups'," ORDER BY Name");
			
		} else {
			$this->registry->groups = Table::load('worder_wordgroups'," WHERE GrouptypeID=" . $this->registry->grouptypeID . " ORDER BY Name");
		}
		$this->registry->wordgrouptypes = Table::load('worder_wordgrouptypes');
		$groupcounts = array();
		foreach($this->registry->groups as $index => $group) {
			$groupcounts[$group->wordgroupID] = 0;
		}
		
		$sql = "SELECT * FROM worder_wordgroupconcepts";
		$result = $mysqli->query($sql);
		if (!$result) die('<br>query failed222 ' . $mysqli->connect_error);
		
		while($row = $result->fetch_array()) {
			$groupID = $row['WordgroupID'];
			if (isset($groupcounts[$groupID])) {
				$groupcounts[$groupID] = $groupcounts[$groupID] + 1;
			}
		}
		$this->registry->groupcounts = $groupcounts;
		$this->registry->template->show('worder/groups','groupcounts');
	}
	
	
	private function getParents($allgrouplinks, $currentgroupID, &$parents) {
		
		foreach($allgrouplinks as $index => $link) {
			
			if ($link->wordgroupID == $currentgroupID) {
				$parents[$currentgroupID] = $currentgroupID;
				if ($link->parentgroupID != 0) {
					$this->getParents($allgrouplinks, $link->parentgroupID, $parents);
				}
			}
		}
		return;
	}
	

	
	/*
	 * Tänne pitää tehdä seuraavat lisäykset
	 * 
	 *   - Otetaan languageID parametri mukaan
	 *   - Käsitelistalla näytetään
	 *   - Parentgroupin otsikkoon voitaisiin lisätä grouppien lukumäärä sulkuihin? Oletuksena kiinni
	 *   - Ryhmä sectionin otsikkoon ehkä name kentän arvo, tai erillinen yläotsikko
	 *   - Sectionin settings nappulasta voitaisiin asettaa oletuksena näkyvät kentät
	 *   - Käsitteet listaan pitäisi hakea käsitteiden primary translation mikäli lang on valittuna (ehkä käsitteen nimen tilalla)
	 *   - Sanat suomi (tai valittu language) sanat ovat ladattu valmiiksi, ei pyäritä pylpyrää
	 *   - Pylpyrää pyärittävät sectiot ovat oletuksena aina kiinni, vaikka olisivat aukaistu (myähemmin näihin autoload)
	 *   
	 */
	public function showgroupAction() {

		$comments = false;
		$groupID = $_GET['id'];
		$languageID = 0;
		if (isset($_GET['languageid'])) $languageID = $_GET['languageid'];
		
		
		$this->registry->group = Table::loadRow('worder_wordgroups',$groupID);
		$this->registry->groups = Table::load('worder_wordgroups');
		$this->registry->grouptypes = Table::load('worder_wordgrouptypes');
		$this->registry->roles = Table::load("worder_roles", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$this->registry->allgrouplinks = Table::load('worder_wordgrouplinks');

		$grouplinks = array();
		foreach($this->registry->allgrouplinks as $index => $link) {
			if ($link->wordgroupID == $groupID) $grouplinks[] = $groupID;
		}
		$this->registry->grouplinks = $grouplinks;
		$parentsIDs = array();
		$parents = array();
		$this->getParents($this->registry->allgrouplinks, $groupID, $parentsIDs);
		$parentstr = arrayToSqlString($parentsIDs);
		
		// poistettu, arguments siirretty conceptiin
		//$this->registry->grouparguments = Table::load('worder_grouparguments', ' WHERE GroupID IN (' . $parentstr . ')');
		
		unset($parentsIDs[$groupID]);
		foreach($parentsIDs as $index => $parentID) {
			$parents[$parentID] = $this->registry->groups[$parentID];
		}
		$this->registry->parentgroups = $parents;
		
		
		
		
		
		$wordclasses = Table::load('worder_wordclasses', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->languageselection = Table::load('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$languages = $this->registry->languageselection;
		//$this->registry->allconcepts = Table::load('worder_concepts');
		
		global $mysqli;
		
		// TODO: tuplat pitäisi poistaa?? Tuleeko näitä, tsekattava
		$sql = "SELECT * FROM worder_wordgroupconcepts WHERE WordgroupID='" . $groupID . "' ORDER BY Sortorder";
		//echo "<br>Sql - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) die('<br>query failed ' . $mysqli->connect_error);

		$conceptIDs = array();
		while($row = $result->fetch_array()) {
			$conceptID = $row['ConceptID'];
			$conceptIDs[$conceptID] = $conceptID;
		}
		//echo "<br>conceptcount - " . count($conceptIDs);
		
		$concepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $conceptIDs, "WHERE GrammarID=" . $_SESSION['grammarID']);		// sekoittaa järjestyksen
		// järjestetään lista uudelleen, koska loadWhereInArray kadottaa järjestyksen
		
		$activelanguages = array();
		$wordarray = array();
		$conceptcounts = array();
		
		$loadwords = false;
		
		//echo "<br>conceptcount - " . count($concepts);
		foreach($languages as $index => $language) {
			
			if ($language->active == 1) {
				//echo "<br>Language - " . $language->name;
				$activelanguages[] = $language;
				
				if ($loadwords == true) {
					
					$loadedwords = Table::load("worder_wordgroupwords"," WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordgroupID=" . $groupID . " ORDER BY Sortorder");
					$wordIDlist = array();
					foreach($loadedwords as $index => $rowwis) {
						if ($comments) echo "<br>index - " . $index . " - " . $rowwis->rowID . " - " . $rowwis->wordgroupID . " - " . $rowwis->conceptID . " - " . $rowwis->wordID . " - " . $rowwis->sortorder . "";
						//var_dump($rowwis);
						$wordIDlist[$rowwis->wordID] = $rowwis;
						if ($comments) echo "<br>Word - "  .$rowwis->wordID;
					}
					$words = Table::loadWhereInArray("worder_words", 'WordID', $wordIDlist, "WHERE GrammarID=" . $_SESSION['grammarID']); 	// pitäisi sortata loadedwords järjestykseen
					
					foreach($wordIDlist as $index => $value) {
						if ($comments) echo "<br> -- wordidlist - " . $index . " - " . $value->rowID;
					}
					
					if ($comments) echo "<br>words count - " . count($words);
					foreach($words as $index => $value) {
						if ($comments) echo "<br> -- words - " . $index . " - " . $value->lemma;
					}
					
					//$wordarray[$language->languageID] = $words;
					if ($comments) echo "<br>*****************************";
					$languagewords = array();
					foreach($concepts as $conceptID => $concept) {
						$found = 0;
						$rowarr = array();
						if ($comments) echo "<br>looping conceptID - " . $concept->conceptID;
						if (count($wordIDlist) == 0) {
								
						} else {
							//echo "<br>wordlistcount - "  . $concept->name . " - " . count($wordIDlist);
							foreach ($wordIDlist as $index => $groupword) {
					
								if ($groupword->removed == 0) {
									if ($comments) echo "<br>wordgroup - conceptid - " . $groupword->conceptID;
									if ($groupword->conceptID == $concept->conceptID) {
										if (isset($conceptcounts[$concept->conceptID])) {
											$conceptcounts[$concept->conceptID] = $conceptcounts[$concept->conceptID] + 1;
										} else {
											$conceptcounts[$concept->conceptID] = 1;
										}
					
										$word = $words[$groupword->wordID];
										$rowarr = array();
										$rowarr[] = $concept->conceptID;
										$rowarr[] = $concept->name;
										$rowarr[] = $groupword->wordID;
										$rowarr[] = $word->lemma;
										$rowarr[] = $concept->frequency;
										$rowarr[] = 'finnish_word';
										$rowarr[] = $groupword->rowID;
										$languagewords[] = $rowarr;
										if ($comments) echo "<br> --- foundi - " . $groupword->wordID . " - " . $word->lemma;
										$found++;
									} else {
										if ($comments) echo "<br> --- noftound";
									}
					
								}
					
							}
							if ($found == 0) {
								//echo "<br>Foundnolla";
								$rowarr = array();
								$rowarr[] = $concept->conceptID;
								$rowarr[] = $concept->name;
								$rowarr[] = "--";
								$rowarr[] = "--";
								$rowarr[] = $concept->frequency;
								$rowarr[] = "0";
								$rowarr[] = "0";
								$languagewords[] = $rowarr;
							}
						}
							
					}
					usort($languagewords, "cmpLanguageWords");
					$wordarray[$language->languageID] = $languagewords;
				}
			}
		}
		if ($loadwords == true) $this->registry->languagewords = $wordarray;
		
		$returnconcepts = array();
		foreach($conceptIDs as $index => $conceptID) {
			$concept = $concepts[$conceptID];

			$rowarr = array();
			$rowarr[] = $concept->conceptID;
			$rowarr[] = $wordclasses[$concept->wordclassID]->name;
			$rowarr[] = parseMultilangString($concept->name,2);
			$rowarr[] = $concept->frequency;
			$rowarr[] = $concept->finnish_word;
				
			/*
			if (isset($conceptcounts[$concept->conceptID])) {
				$rowarr[] = $conceptcounts[$concept->conceptID];
			} else {
				$rowarr[] = 0;
			}
			*/
			$returnconcepts[$conceptID] = $rowarr;
		}
		$this->registry->concepts = $returnconcepts;
		$this->registry->template->show('worder/groups','group');
	}
	

	

	
	/*

	// argumentti siirretty conceptiin
	public function insertargumentAction() {
	
		$wordgroupID =  $_GET['wordgroupID'];
		$roleID =  $_GET['roleID'];
		$targetgroupID =  $_GET['targetgroupID'];
	
		$values = array();
		$values['GroupID'] = $wordgroupID;
		$values['RoleID'] = $roleID;
		$values['TargetgroupID'] = $targetgroupID;
	
		//$sentenceID = Table::addRow("worder_grouparguments", $values, false);
	
		redirecttotal('worder/groups/showgroup&id=' . $wordgroupID, null);
	}
	*/
	
	
	
	
	public function loadlanguagewordsJSONAction() {
		
		$comments = false;
		$languageID = $_GET['lang'];
		$groupID = $_GET['groupid'];

		$language = Table::loadRow('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		
		global $mysqli;
		
		// TODO: tuplat pitäisi poistaa?? Tuleeko näitä, tsekattava
		$sql = "SELECT * FROM worder_wordgroupconcepts WHERE WordgroupID='" . $groupID . "' ORDER BY Sortorder";
		//echo "<br>Sql - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) die('<br>query failed ' . $mysqli->connect_error);
		
		$conceptIDs = array();
		while($row = $result->fetch_array()) {
			$conceptID = $row['ConceptID'];
			$conceptIDs[$conceptID] = $conceptID;
		}
		//echo "<br>conceptcount - " . count($conceptIDs);
		
		$concepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $conceptIDs, "WHERE GrammarID=" . $_SESSION['grammarID']);		// sekoittaa järjestyksen
		
		$loadedwords = Table::load("worder_wordgroupwords"," WHERE Grammar=" . $_SESSION['grammarID'] . " AND WordgroupID=" . $groupID . " ORDER BY Sortorder");
		$wordIDlist = array();
		foreach($loadedwords as $index => $rowwis) {
			//if ($comments) echo "<br>index - " . $index . " - " . $rowwis->rowID . " - " . $rowwis->wordgroupID . " - " . $rowwis->conceptID . " - " . $rowwis->wordID . " - " . $rowwis->sortorder . "";
			//var_dump($rowwis);
			$wordIDlist[$rowwis->wordID] = $rowwis;
			//if ($comments) echo "<br>Word - "  .$rowwis->wordID;
		}
		
		$words = Table::loadWhereInArray("worder_words", 'WordID', $wordIDlist, "WHERE GrammarID=" . $_SESSION['grammarID']); 	// pitäisi sortata loadedwords järjestykseen
		
		foreach($wordIDlist as $index => $value) {
			//if ($comments) echo "<br> -- wordidlist - " . $index . " - " . $value->rowID;
		}
		
		if ($comments) echo "<br>words count - " . count($words);
		foreach($words as $index => $value) {
			//if ($comments) echo "<br> -- words - " . $index . " - " . $value->lemma;
		}
		
		//$wordarray[$language->languageID] = $words;
		//if ($comments) echo "<br>*****************************";
		$languagewords = array();
		foreach($concepts as $conceptID => $concept) {
			$found = 0;
			$rowarr = array();
			//if ($comments) echo "<br>looping conceptID - " . $concept->conceptID;
			if (count($wordIDlist) == 0) {
				/*
				 $rowarr = array();
				 $rowarr[] = $concept->conceptID;
				 $rowarr[] = $concept->name;
				 $rowarr[] = "--";
				 $rowarr[] = "--";
				 $rowarr[] = $concept->frequency;
				 $rowarr[] = "0";
				 $rowarr[] = "0";
				 $languagewords[] = $rowarr;
				 */
			} else {
				//echo "<br>wordlistcount - "  . $concept->name . " - " . count($wordIDlist);
				foreach ($wordIDlist as $index => $groupword) {
		
					if ($groupword->removed == 0) {
						//if ($comments) echo "<br>wordgroup - conceptid - " . $groupword->conceptID;
						if ($groupword->conceptID == $concept->conceptID) {
							if (isset($conceptcounts[$concept->conceptID])) {
								$conceptcounts[$concept->conceptID] = $conceptcounts[$concept->conceptID] + 1;
							} else {
								$conceptcounts[$concept->conceptID] = 1;
							}
		
							$word = $words[$groupword->wordID];
							$rowarr = array();
							$rowarr[] = $concept->conceptID;
							$rowarr[] = $concept->name;
							$rowarr[] = $groupword->wordID;
							$rowarr[] = $word->lemma;
							$rowarr[] = $concept->frequency;
							$rowarr[] = $groupword->sortorder;
							$rowarr[] = $groupword->rowID;
							$languagewords[] = $rowarr;
							//if ($comments) echo "<br> --- foundi - " . $groupword->wordID . " - " . $word->lemma;
							$found++;
						} else {
							//if ($comments) echo "<br> --- noftound";
						}
		
					}
		
				}
				if ($found == 0) {
					//echo "<br>Foundnolla";
					$rowarr = array();
					$rowarr[] = $concept->conceptID;
					$rowarr[] = $concept->name;
					$rowarr[] = "--";
					$rowarr[] = "--";
					$rowarr[] = $concept->frequency;
					$rowarr[] = "0";
					$rowarr[] = "0";
					$languagewords[] = $rowarr;
				}
			}
				
		}
		usort($languagewords, "cmpLanguageWords");
		$wordarray = $languagewords;
		
		echo json_encode($wordarray);
	}
	
	
	
	public function searchwordsAction() {

		$search = $_GET['search'];
		$concepts = Table::load("worder_concepts","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND name LIKE '%" . $search . "%' ORDER BY Frequency DESC");
		
		echo "[";
		$first = true;
		foreach($concepts as $index => $concept) {
			if ($first == true) $first = false; else echo ",";
			//echo "<br>" . $concept->conceptID . " - " . $concept->name . " - " . $concept->frequency;
			
			$wordclass = $concept->wordclassID;
			if ($concept->wordclassID == 1) $wordclass = "N";
			if ($concept->wordclassID == 2) $wordclass = "V";
			if ($concept->wordclassID == 3) $wordclass = "A";
			if ($concept->wordclassID == 10) $wordclass = "AS";
			if ($concept->wordclassID == 4) $wordclass = "AD";
				
			echo " {";
			echo "	  \"conceptID\":\"" . $concept->conceptID . "\",";
			echo "	  \"name\":\"" . $concept->name . "\",";
			echo "	  \"gloss\":\"" . $concept->gloss . "\",";
			echo "	  \"wordclassID\":\"" . $wordclass . "\",";
			echo "	  \"frequency\":\"" . $concept->frequency. "\"";
			echo " }\n";
		}
		echo "]";
		//echo "[  { \"conceptID\":\"12112\", \"name\":\"bbbb\" }, { \"conceptID\":\"1233112\" , \"name\":\"bbbaab\" }  ]";
	}
	
	
	
	public function loadlogAction() {
		
		$groupID = $_GET['id'];
		$tableID = Table::getTableID("worder_wordgroups");
		$rows = Table::load("worder_log"," WHERE TableID=" . $tableID . " AND KeyID=" .  $groupID);
		
		$userarray = array();
		foreach($rows as $index => $row) {
			echo "<br>" . $row->userID . " - " . $row->description;		
			$userarray[$row->userID] = $row->userID;
		}
		
		$users = Table::loadWhereInArray("system_users", "UserID", $userarray);
		foreach($users as $index => $user) {
			echo "<br>" . $user->firstname . " - " . $user->lastname;
		}
		 
		foreach($rows as $index => $row) {
			echo "<br>" . $user->lastname . " " . $user->firstname . " - " . $row->logtime . " - " . $row->description;
			$userarray[$row->userID] = $row->userID;
		}
		
		
	}
	
	
	/*
	public function searchWordsAction() {

		$search = $_GET['search'];
		$concepts = Table::load("worder_concepts","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND name LIKE '%" . $search . "%' ORDER BY Frequency DESC");
		
		echo "[";
		$first = true;
		foreach($concepts as $index => $concept) {
			if ($first == true) $first = false; else echo ",";
			echo " {";
			//echo "<br>" . $concept->conceptID . " - " . $concept->name . " - " . $concept->frequency;
			echo "		\"" . $concept->conceptID . "\",";
			echo "		\"" . $concept->name. "\"";
			echo " }\n";
		}
		echo "]";
		//echo json_encode($concepts);
	}
	

	*/
	
	public function updategroupAction() {
	
	
		$success='';
		$columns = array();
		foreach($_GET as $index => $value) {
			//echo "<br>" . $index . " - " . $value;
			//						$success=$success.$index.'= '.$value.' - ';
			if (($index != 'id') && ($index != 'rt') && ($index != 'lang')) {
				$columns[$index]=$value;
			} elseif ($index == 'id') {
				$id=$value;
			}
		}
		//		$success=count($_GET);
		$success = Table::updateRow('worder_wordgroups', $columns, $id);
		
		
		/*
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
		*/
		
		redirecttotal('worder/groups/showgroup&id=' . $id,null);
	}
	
	

	public function removegroupAction() {
	
		$wordgroupID = $_GET['id'];
		$success = Table::deleteRowsWhere("worder_wordgroupconcepts"," WHERE WordgroupID='" . $wordgroupID . "'");
		$success = Table::deleteRowsWhere("worder_wordgroups"," WHERE WordgroupID='" . $wordgroupID . "'");
		
		// TODO: parentista varmaan pitää poistaa myäs
		$success = Table::deleteRowsWhere("worder_wordgrouplinks"," WHERE WordgroupID='" . $wordgroupID . "'");
		
		$values = array();
		$values['ParentID'] = 0;
		$success = Table::updateRowsWhere("worder_wordgrouplinks",$values," WHERE ParentID='" . $wordgroupID . "'");
		echo "[{\"success\":\"true\"}]";
	}
	

	public function deletegroupAction() {
	
		$wordgroupID = $_GET['id'];

		// TODO: pitää poistaa joko aliluokat, tai estää yläluokkien poisto
		
		$success = Table::deleteRow("worder_wordgroupconcepts"," WordgroupID='" . $wordgroupID . "'");
		$success = Table::deleteRow("worder_wordgroups"," WordgroupId='" . $wordgroupID . "'");
		redirecttotal('worder/groups/showgrouplist',null);
		
	}
	
	
	public function deletegrouplinkAction() {
	
		$comments = false;
		
		$grouptypeID = $_GET['grouptypeID'];
		$wordgroupID = $_GET['wordgroupID'];
		$parentID = $_GET['parentID'];
		if ($comments) echo "<br>Deletegrouplink - " . $wordgroupID;
		if ($comments) echo "<br>Deletegrouplink - " . $parentID;
		
		// TODO: pitää poistaa joko aliluokat, tai estää yläluokkien poisto
		$success = Table::deleteRowsWhere("worder_wordgrouplinks"," WHERE WordgroupID=" . $wordgroupID . " AND ParentgroupID=" . $parentID);
	
		if ($comments == false) redirecttotal('worder/groups/showgrouplist&grouptypeID=' .$grouptypeID,null);
	}
	

	// TODO: muuta nimeksi removeconceptfromgroup -- samankaltainen toiminto concept->removegroup, pitäisi yhdistää yhteen paikkaan
	// mutta tämä aiheuttaa ongelmia redirectin kanssa, redirecti komento pitäisi tulla ehkä jostain muualta, parametrinako?
	// varmaan ainoa vaihtoehto laittaa redirect osoite parametriksi. Jokin toiminto tähän pitäisi keksiä... eriytytetään tietokanta
	// operaatiot omaan luokkaan ja controllerit kutsuisivat niitä sieltä? Delete omalla json-scriptillä? Tämä on parempi vaihtoehto
	// koska delete on usein se jota käytetään useammassa paikassa. JSON-toteutus on hieman hankalampi, se on abstraktimpi.
	// 
 	public function removeconceptfromgroupAction() {
	
		
		
		$conceptID = $_GET['id'];
		$wordgroupID = $_GET['groupid'];
		$languageID = $_GET['languageid'];
		
		echo "<br>RemoveConcept - rowid:" . $rowID . ", wordgoup: " . $wordgroupID;
		
		$languages = Table::load('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		foreach($languages as $index => $language) {
			$success = Table::deleteRowsWhere("worder_wordgroupwords"," WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $language->languageID . " AND ConceptID=" . $conceptID . " AND WordgroupID=" . $wordgroupID);
		}
		
		$success = Table::deleteRowsWhere("worder_wordgroupconcepts"," WHERE GrammarID=" . $_SESSION['grammarID'] . " AND  ConceptID=" . $conceptID . " AND WordgroupID=" . $wordgroupID);

		
		
		/*
		 * 
		 * 
		$id = $_GET['id'];
		$languageID = $_GET['lang'];
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		
		$success = Table::deleteRow("worder_words","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $id);
		$success = Table::deleteRowsWhere("worder_wordgroupwords," WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $id . " AND LanguageID=" . $languageID);
		
		$success = Table::deleteRowsWhere("worder_conceptwordlinks"," WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $id);
		
		
		 */
		// TODO: pitää poistaa joko aliluokat, tai estää yläluokkien poisto
		
		//$success = Table::deleteRowsWhere("worder_wordgroupconcepts"," WHERE WordgroupID='" . $wordgroupID . "' AND ConceptID='" . $conceptID . "'");
		//echo "[{\"success\":\"123\"}]";
		redirecttotal('worder/groups/showgroup&id=' . $wordgroupID ,null);
	}
	
	
	public function removewordfromgroupAction() {
	
		$rowID = $_GET['id'];
		$wordgroupID = $_GET['groupid'];
		$languageID = $_GET['languageid'];
	
		echo "<br>RemoveConcept - rowid:" . $rowID . ", wordgoup: " . $wordgroupID . ", language: " . $languageID;
		$language = Table::loadRow('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		
		$values = array();
		$values['Removed'] = 1;
		$loadedwords = Table::updateRow("worder_wordgroupwords",$values, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID);

		
		// TODO: pitää poistaa joko aliluokat, tai estää yläluokkien poisto
	
		//$success = Table::deleteRowsWhere("worder_wordgroupconcepts"," WHERE WordgroupID='" . $wordgroupID . "' AND ConceptID='" . $conceptID . "'");
		//echo "[{\"success\":\"123\"}]";
		redirecttotal('worder/groups/showgroup&id=' . $wordgroupID ,null);
	}
	
	

	public function insertgroupAction () {
	
		$comments = false;
		
		$grouptypeID = $_GET['grouptypeID'];
		$parentID = $_GET['parentID'];
		$languageID = $_GET['languageID'];
		$links = Table::load("worder_wordgrouplinks");	// tähän pitäisi lisätä grouptypeID filtteriksi
		
	
		//$loop = $this->checkParentRecursive($links, $parentID, 0, 1, $comments);
		$loop = false;
		
		if ($loop == false) {
			
			$values = array();
			$values['Name'] = $_GET['Name'];
			$values['GrouptypeID'] = $grouptypeID;
			$values['LanguageID'] = $languageID;
				
			$rowID = Table::addRow("worder_wordgroups", $values, $comments);
			
			if ($comments) echo "<br> --- lisätty groupID - " . $rowID;
			
			$values = array();
			$values['WordgroupID'] = $rowID;
			$values['ParentgroupID'] = $parentID;
			$values['GrouptypeID'] = $grouptypeID;
			$rowID = Table::addRow("worder_wordgrouplinks", $values, $comments);
			if ($comments == false) redirecttotal('worder/groups/showgrouplist&grouptypeID=' . $grouptypeID,null);
					
		} else {
			echo "<br>Loop found";
		}
	}
	
	
	


	private function checkParentRecursive($grouplinks, $parentID, $groupID, $level, $comments = false) {
	
		$comments = true;
		if ($comments == true) echo "<br>Recu - " . $groupID . " --- parentID: " . $parentID;
		
		if ($parentID == $groupID) {
			if ($comments == true) echo "<br>Same- " . $parentID;
			return true;
		}
		
		
		foreach($grouplinks as $index => $link) {
				
			if ($link->wordgroupID == $parentID) {
	
				if ($link->parentgroupID == $groupID) {
					if ($comments == true) echo "<br>Loop found --- groupID: " . $groupID . ", parent: " . $link->parentgroupID;
					return true;
				}
	
				if ($comments == true) echo "<br>Checking - " . $level . " --- parentID: " . $link->parentgroupID;
	
				if ($link->parentgroupID != 0) {
					if ($link->parentgroupID == $parentID) {
						if ($comments == true) echo "<br>Already exists - " . $parentID;
						return true;
					}
						
					$loop = $this->checkParentRecursive($grouplinks, $link->parentgroupID, $groupID, $level+1, $comments);
					if ($loop == true) {
						if ($comments == true) echo "<br>Loop found";
						return true;
					}
				}
			}
		}
		return false;
	}
	

	public function insertgrouplinkAction () {
	
		$comments = false;
		
		$links = Table::load("worder_wordgrouplinks");
		$wordgroupID = $_GET['wordgroupID'];
		$grouptypeID = $_GET['grouptypeID'];
		$parentID = $_GET['parentID'];
		
		$loop = $this->checkParentRecursive($links, $parentID, $grouptypeID, 1, $comments);
		
		if ($loop == false) {
			if ($comments == true) echo "<br>Loop false";
			$parentID = $_GET['parentID'];
			
			if ($parentID == 0) {
				
				$values = array();
				$values['WordgroupID'] =  $wordgroupID;
				$values['ParentgroupID'] = $parentID;
				$values['GrouptypeID'] = $grouptypeID;
				$rowID = Table::addRow("worder_wordgrouplinks", $values, $comments);
					
			} else {
				//$linkrow = Table::loadRow("worder_wordgroups", $parentID, $comments);
					
				if ($linkrow == null) echo "<br>link null";
				if ($comments == true) echo "<br>Parent - " . $parentID;
				//if ($comments == true) echo "<br>Linkrow - " . $linkrow->grouptypeID;
					
				$values = array();
				$values['WordgroupID'] =  $wordgroupID;
				$values['ParentgroupID'] = $parentID;
				$values['GrouptypeID'] = $grouptypeID;
				//$values['GrouptypeID'] = $linkrow->grouptypeID;
				$rowID = Table::addRow("worder_wordgrouplinks", $values, $comments);
			}
			
			if ($comments == false) redirecttotal('worder/groups/showgrouplist&grouptypeID=' .$grouptypeID,null);
			
		} else {
			echo "<br>Loop found";
		}
	}
	
	
	
	public function moveconceptupAction() {
		$conceptID = $_GET['id'];
		$wordgroupID = $_GET['groupid'];
		
	}
	
	

	public function sortconceptAction() {
		
		global $mysqli;
		
		$groupID = $_GET['groupid'];
		$direction = $_GET['direction'];
		$item = $_GET['item'];
		$place = $_GET['place'];
		
		echo "<br>Group: " . $groupID . " - direction:" . $direction . " - item:" . $item . " - place:" . $place;
		
		$sql = "SELECT * FROM worder_wordgroupconcepts WHERE WordgroupID='" . $groupID . "' ORDER BY Sortorder";
		echo "<br>Sql - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) die('<br>query failed ' . $mysqli->connect_error);
		
		
		$conceptIDs = array();
		while($row = $result->fetch_array()) {
			echo "<br>conceptID - " . $row['ConceptID'] - ", SortOrder - " . $row['Sortorder'];
		}
		echo "<br>----------------------------------------------";
		
		
		/*
		// tämä ei varmaankaan toimi tablename muuttuja tuntematon
		$concepts = Table::load('worder_wordgroupconcepts', " WHERE WordgroupID='" . $groupID . "' ORDER BY Sortorder");		// sekoittaa järjestyksen
		$found = false;
		$previousID = 0;
		$previousSortID = 0;
		
		foreach($concepts as $index => $concept) {
			echo "<br>ConceptID - " . $concept->conceptID;
			
			if ($found == true) {
				
			}
			
			if ( $concept->conceptID == $item) {
				echo "<br>found " . $item;
				$found = true;

				
				$sql = "UPDATE " . $tablename . " SET " . $valuestr . " WHERE " . $keycolumn->columnname . "='" . $id . "'";
				if ($comments) echo "<br>sql - " . $sql;
				//$result = $mysqli->query($sql);
			} else {
				
			}
		}
		*/
		
		exit();
	}
	
	
	
	public function movewordAction() {
	
		
		$wordID = $_GET['id'];
		$wordgroupID = $_GET['groupid'];
		$languageID = $_GET['languageid'];
		
		$orderby = "";
		if (isset($_GET['dir'])) {
			$direction = $_GET['dir'];
			if ($direction == 'up') $orderby = "DESC";
		}
		
		
		$language = Table::loadRow('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		$groups = Table::load("worder_wordgroupwords", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordgroupID=" . $wordgroupID . " AND Removed=0 ORDER BY Sortorder " . $orderby);
		
		$currentrowID = null;
		$currentrow = null;
		$found = false;
		foreach($groups as $index => $row) {
			//echo "<br>nn - " . $row->conceptID . " vs. " . $conceptID;
			$nextID = $row->rowID;
			$next = $row;
			if ($found == true) break;
			if ($row->wordID == $wordID) {
				$currentrowID = $row->rowID;
				$currentrow = $row;
				$found = true;
			}
		}
		
		if ($nextID == $currentrowID) {
			echo "<br>already at the end";
			// already last
		}
		
		if ($nextID != $currentrowID) {
			echo "<br>nextid - " . $nextID . " currentID - " . $currentrowID;
			
			$values = array();
			$values['Sortorder'] = $next->sortorder;
			$updaterow = Table::updateRow("worder_wordgroupwords", $values, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $currentrowID);
				
			$values = array();
			$values['Sortorder'] = $currentrow->sortorder;
			$updaterow = Table::updateRow("worder_wordgroupwords", $values, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $nextID);
		}
		
		//$concept = Table::loadRow("worder_wordgroupconcepts", "ConceptID='" . $conceptID . "' AND WordgroupID='" . $wordgroupID . "'");
		
		/*
		
		//echo "<br>count - " . count($groups);
		$found = false;
		$next = null;
		$concept = null;
		foreach($groups as $index => $row) {
			//echo "<br>nn - " . $row->conceptID . " vs. " . $conceptID;
			$nextID = $row->rowID;
			$next = $row;
			if ($found == true) break;
			if ($row->rowID == $wordID) {
				$word = $row;
				$found = true;
			}
		}
		//echo "<br>nextID - "  . $nextID;
		
		if ($nextID == $wordID) {
			echo "<br>already at the end";
			// already last 
		}
		
		if ($nextID != $wordID) {
		
			global $mysqli;
			
			$values = array();
			$values['Sortorder'] = $next->sortorder;
			$updaterow = Table::updateRow($tablename, $columnvalues, $id);
			$sql = "UPDATE worder_wordgroupconcepts SET Sortorder='" . $next->sortorder . "' WHERE WordgroupID=" . $wordgroupID . " AND ConceptID='" . $concept->conceptID . "'";
			//echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) {
				echo "<br>Error: " . $mysqli->error;
			}
	
			$sql = "UPDATE worder_wordgroupconcepts SET Sortorder='" . $concept->sortorder . "' WHERE WordgroupID=" . $wordgroupID . " AND ConceptID='" . $next->conceptID . "'";
			//echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) {
				echo "<br>Error: " . $mysqli->error;
			}
		}
		*/
		redirecttotal('worder/groups/showgroup&id=' . $wordgroupID,null);
		
	}

	
	// ehkä voi poistaa, korvattu movewordAction, conceptin siirto ehkä yleisessä käsitelistassa
	public function moveconceptAction() {
	
	
		$conceptID = $_GET['id'];
		$wordgroupID = $_GET['groupid'];
	
		$orderby = "";
		if (isset($_GET['dir'])) {
			$direction = $_GET['dir'];
			if ($direction == 'up') $orderby = "DESC";
		}
	
		echo "<br>conceptID - "  . $conceptID;
		echo "<br>wordgroupID - "  . $wordgroupID;
	
		//$concept = Table::loadRow("worder_wordgroupconcepts", "ConceptID='" . $conceptID . "' AND WordgroupID='" . $wordgroupID . "'");
		$groups = Table::load("worder_wordgroupconcepts", "WHERE WordgroupID='" . $wordgroupID . "' ORDER BY Sortorder " . $orderby);
	
		//echo "<br>count - " . count($groups);
		$found = false;
		$next = null;
		$concept = null;
		foreach($groups as $index => $row) {
			//echo "<br>nn - " . $row->conceptID . " vs. " . $conceptID;
			$nextID = $row->conceptID;
			$next = $row;
			if ($found == true) break;
			if ($row->conceptID == $conceptID) {
				$concept = $row;
				$found = true;
			}
		}
		//echo "<br>nextID - "  . $nextID;
	
		if ($nextID == $conceptID) {
			echo "<br>already at the end";
			// already last
		}
	
		if ($nextID != $conceptID) {
	
			global $mysqli;
				
			$sql = "UPDATE worder_wordgroupconcepts SET Sortorder='" . $next->sortorder . "' WHERE WordgroupID=" . $wordgroupID . " AND ConceptID='" . $concept->conceptID . "'";
			//echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) {
				echo "<br>Error: " . $mysqli->error;
			}
	
			$sql = "UPDATE worder_wordgroupconcepts SET Sortorder='" . $concept->sortorder . "' WHERE WordgroupID=" . $wordgroupID . " AND ConceptID='" . $next->conceptID . "'";
			//echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) {
				echo "<br>Error: " . $mysqli->error;
			}
		}
	
		redirecttotal('worder/groups/showgroup&id=' . $wordgroupID,null);
	}
	
	
	/*
	public function deletegrouplinkAction() {

		$wordgroupID = $_GET['id'];
		echo "<br>group to delete - " . $wordgroupID;
	}
	
	*/

	public function movegroupAction() {
	
		$wordgroupID = $_GET['id'];
		$parentID = $_GET['parentid'];
		
		$orderby = "";
		if (isset($_GET['dir'])) {
			$direction = $_GET['dir'];
			if ($direction == 'up') $orderby = "DESC";
		}
	
		
		$wordgrouplink = Table::loadRow("worder_wordgrouplinks", " WHERE WordgroupID=" . $wordgroupID . " AND ParentgroupID=" . $parentID);
		
		$groups = Table::load("worder_wordgrouplinks", " WHERE ParentID=" . $concept->parentID  . " ORDER BY Sortorder " . $orderby);
		
		echo "<br>count - " . count($groups);
		$found = false;
		$next = null;
		$wordgroup = null;
		foreach($groups as $index => $row) {
			echo "<br>nn - " . $row->wordgroupID . " vs. " . $wordgroupID;
			$nextID = $row->wordgroupID;
			$next = $row;
			if ($found == true) break;
			if ($row->wordgroupID == $wordgroupID) {
				$wordgroup = $row;
				$found = true;
			}
		}
		echo "<br>nextID - "  . $nextID;
	
		if ($nextID == $wordgroupID) {
			echo "<br>already at the end";
			// already last
		}
	
		if ($nextID != $wordgroupID) {
	
			global $mysqli;
				
			$sql = "UPDATE worder_wordgroups SET Sortorder='" . $next->sortorder . "' WHERE WordgroupID=" . $wordgroupID . "";
			//echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) echo "<br>Error: " . $mysqli->error;
	
			$sql = "UPDATE worder_wordgroups SET Sortorder='" . $wordgroup->sortorder . "' WHERE WordgroupID=" . $nextID . "";
			//echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) echo "<br>Error: " . $mysqli->error;
		}
	
		redirecttotal('worder/groups/showgrouplist',null);
	}
	
	
	
	// pitää estää tuplien syntyminen...
	//  -- sekä wordgroupconcept taulusta, että groupfinnish_wordsistä ja muistakin kielikohtaisista
	public function insertconceptAction () {
	
		$wordgroupID =  $_GET['groupid'];
		$values['WordgroupID'] = $wordgroupID;
		$conceptID =  $_GET['conceptID'];
		$values['ConceptID'] = $conceptID;
		
		$loadedrow = Table::loadRowWhere("worder_wordgroupconcepts", " WHERE ConceptID=" . $conceptID . " AND WordgroupID=" . $wordgroupID);
		$rowID = 0;
		if ($loadedrow == null) {
			$rowID = Table::addRow("worder_wordgroupconcepts", $values);
		} else {
			//echo "<br>Concept on jo groupissa - " . $loadedrow->rowID . " conceptID:" . $loadedrow->conceptID . ", wordgroupID:" . $loadedrow->wordgroupID;
			$rowID = $loadedrow->rowID;
		}
		
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$words = array();
		foreach($this->registry->languages as $index => $language) {
			
			if ($language->active == 1) {
				$loadedwords = Table::loadNoID("worder_conceptwordlinks","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID='" . $conceptID . "'");
				
				if ($loadedwords == null) {
					//echo "<br>Language "  . $language->name . " table not found.";
				} elseif (count($loadedwords) == 0) {
					//echo "<br>Language "  . $language->name . " words not found.";
				} else {
					foreach($loadedwords as $wordID => $row) {
			
						$values = array();
						$values['WordgroupID'] = $wordgroupID;
						$values['ConceptID'] = $conceptID;
						$values['WordID'] = $row->wordID;
						$values['GrammarID'] = $_SESSION['grammarID'];
						
						$loadedrow = Table::loadRowWhere("worder_wordgroupwords", " WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $row->wordID . " AND ConceptID=" . $conceptID . " AND WordgroupID=" . $wordgroupID);
						if ($loadedrow == null) {
							$rowID = Table::addRow("worder_wordgroupwords", $values);
						} else {
							//echo "<br>Row already exists - setting removed=0 -- " . $loadedrow->rowID;
							$values2 = array();
							$values2['Removed'] = 0;
							Table::updateRow("worder_wordgroupwords", $values2, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $loadedrow->rowID);
 						}
					}
				}
			}
		}
		
		
		
		//echo "<br>groupdID - " . $wordgroupID;
		//echo "<br>groupdID - " . $_GET['conceptID'];
		
		
		/*
		if ($success === true) {
			addMessage('Lisätty onnistuneesti.');
		} else {
			addErrorMessage("Tuntematon tietokantavirhe. - " . $success);
		}
		*/
	
		redirecttotal('worder/groups/showgroup&id=' . $wordgroupID,null);
	
	}
}
?>