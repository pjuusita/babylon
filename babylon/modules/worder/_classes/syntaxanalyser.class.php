<?php



include_once('./modules/worder/_classes/category.class.php');
include_once('./modules/worder/_classes/wordform.class.php');
include_once('./modules/worder/_classes/rule.class.php');


class SyntaxAnalyser {

	
	public static $wordclasses = null;
	public static $rulesByClass = null;

	

	private static function getRulesForClass($wordclassID, $rules) {
	
		//echo "<br>SyntaxAnalyse::rules - " . count($rules);
		
		if (SyntaxAnalyser::$rulesByClass == null) {
			SyntaxAnalyser::$rulesByClass = array();
		}

		if (isset(SyntaxAnalyser::$rulesByClass[$wordclassID])) {
			return SyntaxAnalyser::$rulesByClass[$wordclassID];
		}
		
		$wordclass = SyntaxAnalyser::$wordclasses[$wordclassID];
		//echo "<br>Getclass - " . $wordclassID .  " - " . $wordclass->name;
		//echo "<br>Rules count  - " . count($rules);
		
		$acceptedrules = array();
		foreach($rules as $index => $rule) {
			//echo "<br>Ruleclass  - " . $index . " - ruleID:" . $rule->ruleID . " - " . $rule->name;
			//echo "<br>RuleTerms...";
			//foreach($rule->termwordclasses as $aa => $wordclass) {
			//	echo "<br> -- term - " . $aa . " - " . $wordclass;
			//}
			if ($rule->termwordclasses[0] == $wordclassID) {
				$acceptedrules[] = $rule;
			}
		}
		SyntaxAnalyser::$rulesByClass[$wordclassID] = $acceptedrules;
		return $acceptedrules;
	}
	
	
	private static function checkResultRules($featurestructure, $resultrules, $comments = false) {
		
		//$comments = false;
		if ($resultrules == null) {
			echo "<br> -- no resultrules exit";
			exit();
		}
		if ($comments) echo "<br> -- checkresultrules - " . count($resultrules);
			
		foreach($resultrules as $index => $resultrule) {
			if ($comments) echo "<br> -- checking resultrule " . $resultrule->name . " (" . $resultrule->ruleID . "), resultfeaturecount: " . count($resultrule->resultpositionvalues);
			
			$allfound = true;
			foreach($resultrule->resultpositionvalues as $featureID => $valueID) {
				
				$found = false;
				$feature = FeatureStructure::$features[$featureID];
				$featurevalue = FeatureStructure::$features[$valueID];
				if ($comments) echo "<br> -- -- checking resultfeature x " . $feature->name . " -- " . $featurevalue->name;
				$featurevalueID = $featurestructure->getFeature($featureID);
				if ($featurevalueID == null) {
					$feature = FeatureStructure::$features[$featureID];
					if ($comments) echo "<br> -- -- feature not found " . $feature->name;
					$found = false; 
					//return false;			
				} else {
					if ($featurevalueID != $valueID) {
						$rulefeaturevalue = FeatureStructure::$features[$valueID];
						$fsfeaturevalue = FeatureStructure::$features[$featurevalueID];
						if ($comments) echo "<br> -- -- -- featurevalue mitchmatch  " . $rulefeaturevalue->name . " (" . $valueID . ") vs. " . $fsfeaturevalue->name . " (" . $featurevalueID . ")";
						$found = false; 
						//return false;
					} else {
						$feature = FeatureStructure::$features[$featureID];
						$fsfeaturevalue = FeatureStructure::$features[$featurevalueID];
						$found = true; 
						if ($comments) echo "<br> -- -- -- featurevalue found  " . $feature->name . " (" . $valueID . ") vs. " . $fsfeaturevalue->name . " (" . $featurevalueID . ")";
					}			
				}
				
				if ($found == false) {
					$allfound = false;
					break;
				}
			}
			if ($allfound == true) {
				if ($comments) echo "<br> -- compatible resultrule found - " . $resultrule->name . " - " . $resultrule->ruleID;
				return true;
			} else {
				if ($comments) echo "<br> -- compatible resultrule not compatible - " . $resultrule->name . " - " . $resultrule->ruleID;
			}
		}
		return false;
	}
	
	
	// Tämä pitää olla rekursiivinen, epäselvää on miten structure palautetaan alkutilaan läpikäydessä (vai pitääkä se kopioida)
	private static function analyseFS($currentindex, &$foundstructures, $structures, $rules, $resultrules, $recursioncount = 0, $allreadyone = false, $comments = false) {
		
		//$comments = true;
		if ($comments) echo "<br>--------------------------------------------";
		if ($comments) echo "<br>Currentindex - " . $currentindex;
		if ($comments) echo "<br>Recursioncount - " . $recursioncount;
		foreach($structures as $index => $fs) {
			if ($comments) echo "<br>fs[" . $index . "] = " . $fs->toString();
		}
		
		//if ($comments) $fs->printFeatureStructure();
		if ($comments) echo "<br>";
		
		
		if (count($structures) == 1) {
			// Tänne mennään ensimmäisellä kerralla jos sentencessä on vain yksi sana
			if ($comments) echo "<br>Ready, only one structure left aa - " . $structures[0]->name;
			
			$match = SyntaxAnalyser::checkResultRules($structures[0], $resultrules, $comments);
			if ($match == true) {
				if ($comments) echo "<br> -- result check true, adding found structures";
				
				// TODO: Tsekataan, että ei lisätä tupla structurea...
				
				$equalstructurefound = false;
				foreach($foundstructures as $foundindex => $foundstructure) {
					$comp = FeatureStructure::compare($foundstructure, $structures[0], $comments);
					if ($comp == true) {
						$equalstructurefound = true;
						break;
					}
				}
				if ($equalstructurefound == false) {
					$foundstructures[] = $structures[0]->getCopy();
				} else {
					if ($comments) echo "<br> -- -- compare true, not added";
				}
			} else {
				if ($comments) echo "<br> -- result check false aaaa";
			}
			$allreadyone = true;
			//return true;
		}
	
		//echo "<br><br>----------------------------------------";
		//echo "<br>----------------------------------------";
		
	
		foreach($structures as $currentindex => $fs) {
	
			// jos kyseessä on ensimmäinen, niin käydään läpi vain rulet, jotka ottavat argumentteja oikealle puolelle
			// jos kyseessä on viimeinen, käydään läpi vain rulet, jotka ottavat argumentteja vasemmalle puolelle
			if ($comments) echo "<br>";
			if ($comments) echo "<br>CurrentIndex - " . $currentindex;
			$acceptedrules = SyntaxAnalyser::getRulesForClass($fs->getClass(), $rules);
			if ($comments) echo "<br>Acceptedrulescount - " . count($acceptedrules) . "<br>";
			
			if (count($acceptedrules) > 0) {
	
				foreach($acceptedrules as $ruleindex => $rule) {
	
					if ($comments) echo "<br>Checking rule - " . $rule->name . " (" . $rule->ruleID . ")";
					//if ($comments) $rule->printRule();
						
					//$compatibility = $rule->isAnalyseCompatible($currentindex, $structures, $comments);
					$compatibility = $rule->isAnalyseCompatible($currentindex, $structures, true);
						
					if ($compatibility == true) {
						if ($comments) echo "<br> --- Rule is compatible";
							
						//if ($comments) echo "<br>--- applyrule (structurescount - " . count($structures) . ")";
						$newfs = $rule->applyAnalyseRule($currentindex, $structures, false);
						//if ($comments) echo "<br>--- newfs (structurescount - " . count($structures) . ")";
						//if ($comments) $newfs->printFeatureStructure();
						//if ($comments) echo "<br>newfs - " . get_class($newfs);
							
						if (count($structures) == 1) {
							if ($comments) echo "<br>RuleApply ready, only one structure left aa - " . $structures[0]->name;
							$match = SyntaxAnalyser::checkResultRules($newfs, $resultrules, $comments);
							if ($match == true) {
								if ($comments) echo "<br> -- result check true 2, adding found structures";
								$foundstructures[] = $newfs;
							} else {
								if ($comments) echo "<br> -- result check false bbbb";
							}
							if ($allreadyone == false) {
								// Onkohan tämä haara turha, ei tulla koskaan kun structures count on 0
								//echo "<br>Going deeber - 1 --- newarraycount = " . count($newarray);
								SyntaxAnalyser::analyseFS(0, $foundstructures, $newarray, $rules, $resultrules, ($recursioncount+1), true, $comments);
							} else {
								if ($comments) echo "<br> -- -- -- already one item, no recursion";
							}
							
						} else {
							
							if ($fs != null) {
								$newarray = array();
								$temparray = array();
							
								$indexfound = false;
								$newindex = 0;
								foreach($structures as $index => $oldfs) {
									//if ($comments) echo "<br>Structures - "  .$index . " - " . $oldfs->name . " - " . get_class($newfs);
									if ($index < $currentindex) {
										$newarray[$newindex] = $oldfs;
										$newindex++;
									} else {
										if ($index == $currentindex) {
											//if ($comments) echo "<br>Current - found - " . $rule->getRuleTermCount();
											$newarray[$newindex] = $newfs;
											$newindex++;
											$indexfound = true;
										} else {
											// TODO: tämä ei varmaankaan toimi kunnolla...
											if ($rule->getRuleTermCount() == 2) {
												if ($indexfound == true) {
													if ($comments) echo "<br>Current - no add";
													$indexfound = false;		
												} else {
													if ($comments) echo "<br>Current - add";
													$newarray[$newindex] = $oldfs;
													$newindex++;
												}
												// Ei lisätä toista termiä
											} else {
												$newarray[$newindex] = $oldfs;
												$newindex++;
											}
										}
									}
								}
								if ($recursioncount > 6) {
									echo "<br> -- Recursion max - exit";
									exit;
								}
								//if ($comments) echo "<br>Going deeber - 2 --- newarraycount = " . count($newarray);
								SyntaxAnalyser::analyseFS(0, $foundstructures, $newarray, $rules, $resultrules, ($recursioncount+1), false, $comments);
							}
						}
						
						
					} else {
						if ($comments) echo "<br>xxx xxx --- Rule not compatible<br><br>";
					}
				}
			} else {
				if ($comments)  echo "<br>No rules found, for " . $fs->getClass();
			}
		}
		if ($comments) echo "<br>Recursion end";
		return false;
	}
	
	
	
	
	//function analyse($string, $wordclasses, $features, $wordclassfeatures, $wordclassarguments, $components, $conceptcomponents) {
	
	public static function analyse($featuresstructures, $rules, $comments = false) {
		
		if (SyntaxAnalyser::$wordclasses == null) {
			echo "<br>SyntaxAnalyser::wordclasses - not defined.";
			exit;
		}
		
		
		//$words = createWords($string, $wordclasses, $features, $wordclassfeatures, $wordclassarguments, $components, $conceptcomponents);
		
		// start analysing...
		if ($comments) echo "<br>----------------------------------------";
		if ($comments) echo "<br>Start analysing... - analyserules - " . count($rules);
		if ($comments) {
			$analyserulecounter = 0;
			$analyseresultrulecounter = 0;
			foreach($rules as $index => $rule) {
				//echo "<br>Rule - " . $rule->name;
				if ($rule->analyse == 1) {
					//echo "<br> -- analyse";
					$analyserulecounter++;
				}
				if ($rule->analyse == 2) {
					//echo "<br> -- analyse";
					$analyseresultrulecounter++;
				}
			}
			echo "<br> - rulecount - " . $analyserulecounter;
			echo "<br> - rulecount - " . $analyseresultrulecounter;
		}
		if ($comments) echo "<br>----------------------------------------";

		
		foreach($rules as $index => $rule) {
			//if ($comments) echo "<br>---------------------------------------------------";
			//if ($comments) $rule->printRule();
		}
		
		
		foreach($featuresstructures as $index => $fs) {
			if ($comments) echo "<br>---- featurestructure ------------------------------------";
			if ($comments) $fs->printFeatureStructure();
		}
		
	
		if ($comments) echo "<br>----------------------------------------";
		if ($comments) echo "<br>analyse";
		if ($comments) echo "<br>----------------------------------------";
	
		$analyserules = array();
		$resultrules = array();
		foreach($rules as $index => $rule) {
			if ($rule->analyse == 2) {
				$resultrules[] = $rule;
			} else {
				$analyserules[] = $rule;
			}
		}
		
		
		$foundstructures = array();
		SyntaxAnalyser::analyseFS(0, $foundstructures, $featuresstructures, $analyserules, $resultrules, 0, false, $comments);
		
		
		foreach($foundstructures as $index => $fs) {
			if ($comments) echo "<br><br>Found structure:";
			if ($comments) $fs->printFeatureStructureRecursive();
		}
		
		return $foundstructures;
	}
	
	

}