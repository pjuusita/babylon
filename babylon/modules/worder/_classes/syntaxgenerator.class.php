<?php



include_once('./modules/worder/_classes/category.class.php');
include_once('./modules/worder/_classes/wordform.class.php');
include_once('./modules/worder/_classes/rule.class.php');


class SyntaxGenerator {

	
	public static $wordclasses = null;
	public static $features = null;
	public static $wordforms = array();
	public static $wordclassfeatures = null;
	
	private static function generateSingle($featurestructure, $rules, $comments = false) {
		
	}
	
	
	//function analyse($string, $wordclasses, $features, $wordclassfeatures, $wordclassarguments, $components, $conceptcomponents) {
	
	public static function generate($featurestructure, $languageID, $rules, $currentstr, $comments = false, $recursioncounter = 1) {
		
		//$languageID = 1;
		if ($comments) echo "<br>Start Generate";
		if ($comments) $featurestructure->printFeatureStructureRecursive();
			
		if ($recursioncounter > 8) {
			echo "<br>Recursioncounter exceeded... - " . $recursioncounter;
			exit;
		}
		//$comments = true;
		$leftresultsentences = array();
		$rightresultsentences = array();
		$resultsentences = array();
		
		// Featurestructure pitäisi ehkä muokata semanticfeatures-to-languagefeatures (sharedlink)
		foreach($rules as $index => $rule) {
			
			if ($comments) echo "<br> -- rulelang - " . $rule->languageID;
			if ($comments) echo "<br> -- targetlang - " . $languageID;
			
			if ($rule->languageID == $languageID) {
				//echo "<br>language match";
				$compatible = $rule->isGenerateCompatible($featurestructure, $comments);
				$leftresultsentences = array();
				$rightresultsentences = array();
					
				if ($compatible == true) {
					if ($comments) echo "<br>-- rule is compatible - " . $rule->name;
				
					$resultfeatures = $rule->applyGenerateRule($featurestructure,$comments);
				
					//if ($comments) echo "<br><br>Results... after apply generate<br>";
					//if ($comments) echo $resultfeatures[0]->toConceptStructureJSON();
					//if ($comments) echo "<br><br>";
					//if ($comments) echo $resultfeatures[1]->toConceptStructureJSON();
				
					//$leftsentences = array();
					if (SyntaxGenerator::isGenerateComplete($resultfeatures[0],$comments) == true) {
							
						// TODO tällähetkellä isGenerateComplete ei koskaan palauta trueta, tämä haara voidaan poistaa
				
						if ($comments) echo "<br>Left side is complete... searching for surfaceform";
						//$resultsentences[] = $resultfeatures[0];
						$leftsentences = SyntaxGenerator::generateSurfaceForm($resultfeatures[0], $languageID, $comments);
						//echo "<br>Call 01";
						foreach($leftsentences as $inde => $sentence) {
							//if ($comments) echo ", " . $res;
							//$sentence = $currentstr . " " . $res;
							if ($comments) echo "<br> -- leftsentence1 - " . $sentence;
							$leftresultsentences[] = $sentence;
						}
						// TODO: tämä haara voidaan poistaa...
				
					} else {
						$argumentcount = count($resultfeatures[0]->getArguments());
						if ($comments) echo "<br><br>Left side is not complete ... argumentcount=" . $argumentcount;
						if ($comments) echo "<br>Recursive generate for left:<br>";
						if ($comments) echo $resultfeatures[0]->toConceptStructureJSON();
							
						$leftresults = SyntaxGenerator::generate($resultfeatures[0], $languageID, $rules, $currentstr, $comments, $recursioncounter + 1);
						if ($comments) echo "<br>Back from left recursion<br>";
				
							
						//if ($comments) echo "<br>More to generate 1... return";
						//if ($comments) echo "<br>Subresult:";
						//if ($comments) print_r($subresult);
							
						if (count($leftresults) == 0) {
							if ($comments) echo "<br><br> -- No leftresults found...";
							if ($argumentcount == 0) {
								if ($comments) echo "<br> -- -- No leftresults and no arguments, trying to create sufrace form";
								//$leftsentences = array();
								$leftsentences = SyntaxGenerator::generateSurfaceForm($resultfeatures[0], $languageID, $comments);
								//echo "<br>Call 02";
								if ($comments) echo "<br> -- -- -- surfaceforms found a: " . count($resultsentences) . "";
								if ($leftsentences != null) {
									//print_r($leftsentences);
									foreach($leftsentences as $inde => $sentence) {
										if ($comments) echo "<br> -- -- -- -- found c - " . $index . " - " . $sentence;
										$leftresultsentences[] = $sentence;
									}
								}
							} else {
								if ($comments) echo "<br>No more subresults, but more arguments... no results";
							}
						} else {
							if ($comments) echo "<br> -- surfaceforms found b: " . count($leftresults) . "";
							foreach($leftresults as $inde => $sentence) {
								if ($comments) echo "<br> -- -- found -- " . $sentence;
								$leftresultsentences[] = $sentence;
							}
						}
					}
				
				
					//$rightsentences = array();
					//$rightresultsentences = array();
					if (isset($resultfeatures[1])) {
						if (SyntaxGenerator::isGenerateComplete($resultfeatures[1],$comments) == true) {
								
							// TODO tällähetkellä isGenerateComplete ei koskaan palauta trueta, tämä haara voidaan poistaa
							if ($comments) echo "<br>Generate complete... searching for surfaceform";
							//$resultsentences[] = $resultfeatures[1];
							/*
							 $rightsentences= SyntaxGenerator::generateSurfaceForm($resultfeatures[1], $languageID, $comments);
							 foreach($leftresultsentences as $inde1 => $leftsentence) {
							 foreach($rightsentences as $inde2 => $res) {
				
							 if ($res != null) {
							 //if ($comments) echo ", " . $res;
							 $sentence = $leftsentence . " " . $res;
							 if ($comments) echo "<br> -- rightsentence1 - " . $sentence;
							 $resultsentences[] = $sentence;
							 }
							 }
							 }
							 */
							// TODO: tämä haara voidaan poistaa...
								
						} else {
							$argumentcount = count($resultfeatures[1]->getArguments());
							if ($comments) echo "<br><br>Right side is not complete ... argumentcount=" . $argumentcount;
							if ($comments) echo "<br>Recursive generate for right:<br>";
							if ($comments) echo $resultfeatures[1]->toConceptStructureJSON();
								
							$rightresults = SyntaxGenerator::generate($resultfeatures[1], $languageID, $rules, $currentstr, $comments, $recursioncounter + 1);
							if ($comments) echo "<br>Back from right recursion<br>";
							//if ($comments) echo "<br>More to generate 2... return";
							//if ($comments) echo "<br>Subresult:";
							//if ($comments) print_r($subresult);
								
							if (count($rightresults) == 0) {
								if ($comments) echo "<br><br> -- No rightresults found...";
								if ($argumentcount == 0) {
									if ($comments) echo "<br> -- -- No rightresults and no arguments, trying to create sufrace form 22";
									//$rightsentences = array();
									$rightsentences = SyntaxGenerator::generateSurfaceForm($resultfeatures[1], $languageID, $comments);
									//echo "<br>Call 03";
									if ($comments) echo "<br>";
									if ($rightsentences == null) {
										if ($comments) echo "<br> -- -- -- surfaceforms found: null (0)";
									} else {
										if ($comments) echo "<br> -- -- -- surfaceforms found: " . count($rightsentences) . "";
									}
									if ($comments) echo "<br> -- -- -- -- leftsentencecount: " . count($leftresultsentences) . "";
									foreach($leftresultsentences as $inde1 => $leftsentence) {
										if ($comments) echo "<br> -- -- -- -- -- leftsentence = " . $leftsentence;
											
										if ($rightsentences == null) {
											if ($comments) echo "<br> -- -- -- -- rightsentencecount: null";
										} else {
											if ($comments) echo "<br> -- -- -- -- rightsentencecount: " . count($rightsentences) . "";
										}
										if ($rightsentences != null) {
											foreach($rightsentences as $inde2 => $sentence) {
												if ($comments) {
													echo "<br>";
													print_r($sentence);
												}
												if ($comments) echo "<br> -- -- -- -- -- rightsentence = " . $sentence;
												//if ($comments) echo ", " . $res;
												if (($leftsentence != null) && ($leftsentence != "")) {
													if (($sentence != null) && ($sentence != "")) {
														if ($comments) echo "<br> -- -- -- -- found - " . $sentence;
														$fullsentence = $leftsentence . " " . $sentence;
														if ($comments) echo "<br> -- -- -- -- generating full sentence 4 - '" . $fullsentence . "'";
														$resultsentences[] = $fullsentence;
													}
												} else {
													if (($sentence != null) && ($sentence != "")) {
														if ($comments) echo "<br> -- -- -- -- found - " . $sentence;
														$fullsentence = $sentence;
														if ($comments) echo "<br> -- -- -- -- generating full sentence 3 - '" . $fullsentence . "'";
														$resultsentences[] = $fullsentence;
													}
												}
											}
										}
									}
								} else {
									if ($comments) echo "<br>No rightresults, but more arguments... no results";
								}
							} else {
									
								foreach($leftresultsentences as $leftIndex => $leftsentence) {
									if (($leftsentence != null) && ($leftsentence != "")) {
										foreach($rightresults as $rightIndex => $sentence) {
											//if ($comments) echo ", " . $res;
											if (($sentence != null) && ($sentence != "")) {
												if ($comments) echo "<br> -- found - " . $sentence;
												$fullsentence = $leftsentence . " " . $sentence;
												if ($comments) echo "<br> -- generating full sentence 2 - " . $fullsentence;
												$resultsentences[] = $fullsentence;
											}
										}
									} else {
										foreach($rightresults as $rightIndex => $sentence) {
											//if ($comments) echo ", " . $res;
											if (($sentence != null) && ($sentence != "")) {
												if ($comments) echo "<br> -- found - " . $sentence;
												$fullsentence = $sentence;
												if ($comments) echo "<br> -- generating full sentence 1 - " . $fullsentence;
												$resultsentences[] = $fullsentence;
											}
										}
									}
								}
								/*
								 foreach($subresult as $inde => $res) {
								 if ($comments) echo "<br>Res found - " . $res;
								 $resultsentences[] = $res;
								 }
								 */
							}
						}
							
					} else {
						foreach($leftresultsentences as $leftIndex => $leftsentence) {
							if ($comments) echo "<br> -- leftsentence: '" . $leftsentence . "'";
							$resultsentences[] = $leftsentence;
						}
					}
				
				
					//break;
				} else {
					if ($comments) echo "<br>-- rule is not compatible - " . $rule->name;
				}
				if ($comments) echo "<br><br>-------------------------------------------------------";
				
			} else {
				if ($comments) echo "<br>Not this language rule - " . $rule->ruleID . " - " . $rule->languageID;
			}
		}
		
		
		if ((count($featurestructure->getArguments()) == 0) && ($recursioncounter == 1)) {
			if ($comments) echo "<br> Finally test root if no arguments";
			if ($comments) echo $featurestructure->toConceptStructureJSON();
			if ($comments) echo "<br>";
			$sentences = SyntaxGenerator::generateSurfaceForm($featurestructure, $languageID, $comments);
			//echo "<br>Call 05";
			if ($sentences != null) {
				foreach($sentences as $inde2 => $sentence) {
					if ($comments) echo "<br> -- root sentence found - '" . $sentence . "'";
					if ($sentence == "") {
						if ($comments) echo "<br> -- resultsentence empty";
					} else {
						$resultsentences[] = $sentence;
					}
				}
			}
		}
		
		if ($comments) echo "<br>Resultsentences found - " . count($resultsentences);
		return $resultsentences;
	}
	
	
	public function isGenerateComplete($fs, $comments) {
		
		
		//$argumentcount = count($fs->getArguments());
		//if ($argumentcount == 0) {
		//	return true;
		//}
		return false;
	}
	
	
	
	
	public static function generateSurfaceForm($fs, $languageID, $comments = false) {

		//$comments = true;
		$concept = Table::loadRow("worder_concepts", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $fs->conceptID);
		if ($comments) echo "<br>Concept name - " . $concept->name;
		
		
		if (SyntaxGenerator::$wordclassfeatures == null) {
			SyntaxGenerator::$wordclassfeatures = Table::load("worder_wordclassfeatures", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		}
		if (SyntaxGenerator::$features == null) {
			SyntaxGenerator::$features = Table::load("worder_features", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID, $comments);
		}
		
		
		$conceptwordlinks = Table::load("worder_conceptwordlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " AND ConceptID=" . $fs->conceptID . " AND Defaultword=1");
		$wordlist = array();
		$conceptlinks = array();
		//if ($comments) echo "<br><br>Wordlinks...";
		foreach($conceptwordlinks as $index => $link) {
			$wordID = $link->wordID;
			$wordlist[$wordID] = $wordID;
			$conceptlinks[$wordID] = $link->conceptID;
			//if ($comments) echo "<br> -- " . $wordID . ", conceptID=" . $link->conceptID;
		}
		$words = Table::loadWhereInArray("worder_words", "wordID", $wordlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		if ($comments) echo "<br><br>Words...";
		foreach($words as $index => $word) {
			if ($comments) echo "<br>" . $word->lemma;
		}
		
		if ($comments) echo "<br>Words loop...";
		$acceptedword = null;
		foreach($words as $index => $word) {
			//$conceptID = $conceptlinks[$word->wordID];
			//$concept = $concepts[$conceptID];
			//$concept->wordID = $word->wordID;
			$concept->lemma = $word->lemma;
			if ($comments) echo "<br> -- " . $word->wordID . " -- " . $word->lemma . " - conceptID:" . $concept->conceptID;
			
			if (isset(SyntaxGenerator::$wordforms[$word->wordID])) {
				$forms = SyntaxGenerator::$wordforms[$word->wordID];
			} else {
				$forms = Table::load("worder_wordforms", "WHERE WordID=" . $word->wordID . " AND LanguageID=" . $languageID . " AND GrammarID=" . $_SESSION['grammarID']. " AND Defaultform=1");
				SyntaxGenerator::$wordforms[$word->wordID] = $forms;
			}
				
				
			
			$searchfeatures = array();
			$compatible = true;
			foreach(SyntaxGenerator::$wordclassfeatures as $wcfindex => $wordclassfeature) {
				if ($wordclassfeature->wordclassID == $concept->wordclassID) {
					if ($wordclassfeature->inflectional == 1) {
						if ($comments) echo "<br> -- inflectional = 1";
						$feature = SyntaxGenerator::$features[$wordclassfeature->featureID];
						if ($comments) echo "<br> -- needed feature A - " . $feature->name . " (" . $feature->featureID . ")";
			
						// Tsekataan löytyykö halutuista muodoista kyseistä featurea, arvo joka halutaan
						$found = false;
						
						foreach($fs->getFeatures() as $requiredfeatureID => $requiredfeaturevalueID) {
							
							$fsname = SyntaxGenerator::$features[$requiredfeatureID];
							$fsvalue = SyntaxGenerator::$features[$requiredfeaturevalueID];
							if ($comments) echo "<br> -- -- search feature B - " . $fsname->name . " (" . $requiredfeatureID . ")";
							if ($comments) echo "<br> -- -- search feature C - " . $fsvalue->name . " (" . $requiredfeaturevalueID . ")";
							//if ($comments) echo "<br> -- -- checking item - " . $fsname->name . " (" . $requiredfeatureID . ") - " . $fsvalue->name . " (" . $requiredfeaturevalueID . ")";
							if ($requiredfeatureID == $feature->featureID) {
								$requiredfeature = SyntaxGenerator::$features[$requiredfeatureID];
								$requiredvalue = SyntaxGenerator::$features[$requiredfeaturevalueID];
								if ($comments) echo "<br> -- -- requirement found - " . $requiredfeature->name . " == " . $requiredvalue->name;
								$searchfeatures[$wordclassfeature->featureID] = $requiredfeaturevalueID;
								if ($wordclassfeature->defaultvalueID == $requiredfeaturevalueID) {
									$defaultsearchfeatures[$wordclassfeature->featureID] = 1;
								} else {
									$defaultsearchfeatures[$wordclassfeature->featureID] = 0;
								}
								$found = true;
								break;
							}
						}
						if ($found == false) {
							if ($wordclassfeature->defaultvalueID == 0) {
								if ($comments) {
									$requiredfeature = SyntaxGenerator::$features[$requiredfeatureID];
									echo "<br>no wordclassfeature default found for RowID=" . $wordclassfeature->rowID . ", featureID:" . $wordclassfeature->featureID . " - " . $requiredfeature->name;
								}
								$found = false;
								//exit;
							} else {
								if ($comments) echo "<br> -- found false";
								$defaultsearchfeatures[$wordclassfeature->featureID] = 1;
								$searchfeatures[$wordclassfeature->featureID] = $wordclassfeature->defaultvalueID;
								$requiredfeature = SyntaxGenerator::$features[$wordclassfeature->defaultvalueID];
							}
							//if ($comments) echo "<br> -- no needed requirement found, use default - " . $wordclassfeature->rowID . " - " . $requiredfeature->name;
						}
					}
					
					if ($wordclassfeature->inflectional == 0) {
						if ($comments) echo "<br> -- inflectional = 0";
						$feature = SyntaxGenerator::$features[$wordclassfeature->featureID];
						if ($comments) echo "<br> -- needed feature AB - " . $feature->name . " (" . $feature->featureID . ")";
						$found = false;
						foreach($fs->getFeatures() as $requiredfeatureID => $requiredfeaturevalueID) {
							
							if (isset(SyntaxGenerator::$features[$requiredfeatureID]) && isset(SyntaxGenerator::$features[$requiredfeaturevalueID])) {
								$fsname = SyntaxGenerator::$features[$requiredfeatureID];
								$fsvalue = SyntaxGenerator::$features[$requiredfeaturevalueID];
								if ($comments) echo "<br> -- -- checking item - " . $fsname->name . " (" . $requiredfeatureID . ") - " . $fsvalue->name . " (" . $requiredfeaturevalueID . ")";
							}
							if ($requiredfeatureID == $feature->featureID) {
								$requiredfeature = SyntaxGenerator::$features[$requiredfeatureID];
								$requiredvalue = SyntaxGenerator::$features[$requiredfeaturevalueID];
								if ($comments) echo "<br> -- -- requirement found - " . $requiredfeature->name . " == " . $requiredvalue->name;
								//print_r($word);
								
								$wordfeaturearray = explode("|", $word->features);
								
								$featurefound = false;
								foreach($wordfeaturearray as $ind3 => $wordfeaturestr) {
									if ($wordfeaturestr != "") {
										$parts = explode(":", $wordfeaturestr);
										$wordfeatureID = $parts[0];
										$wordfeaturevalueID = $parts[1];
										$wordfeature = SyntaxGenerator::$features[$wordfeatureID];
										$wordfeaturevalue = SyntaxGenerator::$features[$wordfeaturevalueID];
										if ($comments) echo "<br> -- -- -- wordfeature - " . $wordfeature->name . " == " . $wordfeaturevalue->name;
										if ($wordfeatureID == $requiredfeatureID) {
											if ($requiredfeaturevalueID == $wordfeaturevalueID) {
												if ($comments) echo "<br> -- -- -- -- wordfeature compatible";
												$featurefound = true;
											} else {
												if ($comments) echo "<br> -- -- -- -- wordfeature incompatible";
												//$compatible = false;
											}
										} else {
											if ($comments) echo "<br> -- -- -- -- -- not searched wordfeature";
										}
									}
								}
								
								if ($featurefound == false) {
									
									// Pitää vielä tsekata onko kyseessä oletusarvo...
									
									if ($requiredfeature == null) {
										if ($comments) echo "<br> -- -- -- requiredfeature == null";
									}
									
									if ($comments) echo "<br> -- -- -- no requirement found - " . $requiredfeatureID;
										
									if ($comments) echo "<br> -- -- -- no requirement found - " . $requiredfeature->name . " == " . $requiredvalue->name;
									
									if ($wordclassfeature->defaultvalueID > 0) {
										$defaultfeature = SyntaxGenerator::$features[$wordclassfeature->defaultvalueID];
										//if ($comments) echo "<br> -- -- no requirement found - default = " . $defaultfeature->name . " (" . $defaultfeature->defaultvalueID . ")";
										if ($wordclassfeature->defaultvalueID == $requiredfeaturevalueID) {
											if ($comments) echo "<br> -- -- -- default value is required value, accept .. " . $defaultfeature->name . " (" . $defaultfeature->defaultvalueID . ")";
										} else {
											if ($comments) echo "<br> -- fff -- -- default not acceptable .. " . $wordclassfeature->defaultvalueID . " vs. " . $requiredfeaturevalueID . "";
											if ($comments) echo "<br> -- fff -- -- default not acceptable .. " . $defaultfeature->name . " (" . $defaultfeature->defaultvalueID . ")";
											$compatible = false;
										}
									} else {
										if ($comments) echo "<br> -- fff -- -- no default present - " . $requiredfeaturevalueID;
										$compatible = false;
										
									}
								}								
							}
						}	
					}
				}
			}
				
			//$compatible = true;
			if ($compatible == true) {
				
				if ($comments) {
					echo "<br><br>Forms loop...";
					foreach($forms as $index => $word) {
						echo "<br> -- -- " . $word->wordform . " - " . $word->rowID;
					}
				}
					
				//$comments = true;
				if ($comments) echo "<br>Forms...";
				// Kelataan kaikki formit lävitse, ja tsektaan löytyykö jokaiselle searchfeaturelle arvo...
				foreach($forms as $index => $form) {
					if ($comments) echo "<br> -- -- wordforms: " . $form->wordform . " - " . $form->rowID;
					//if ($comments) print_r($form->features);
						
					$allmatch = true;
					foreach($searchfeatures as $requiredfeatureID  => $requiredvalueID) {
						$found = 0;
						foreach($form->features as $i2 => $formFeatureID) {
							$parentID = SyntaxGenerator::$features[$formFeatureID]->parentID;
							if ($comments) {
								echo "<br> -- -- -- requiderfeatureID:" . $requiredfeatureID . " - formfeature:"  . $formFeatureID . " - formparentID:" . $parentID. " - requiredvalue:" . $requiredvalueID;
								$r1 = SyntaxGenerator::$features[$requiredfeatureID]->name;
								$r2 = SyntaxGenerator::$features[$formFeatureID]->name;
								if ($parentID > 0) {
									$r3 = SyntaxGenerator::$features[$parentID]->name;
								} else {
									$r3 = "nulll";
								}
								$r4 = SyntaxGenerator::$features[$requiredvalueID]->name;
								if ($comments) echo "<br> -- -- -- requiderfeatureID:" . $r1 . " - formfeature:"  . $r2 . " - formparentID:" . $r3. " - requiredvalue:" . $r4;
							}
							
							
							if ($parentID == 0) {
								
								if ($formFeatureID == $requiredfeatureID) {
									if ($comments) echo "<br> -- -- -- -- this is it";
									if ($comments) echo "<br> -- -- -- -- parent zero";
									$requiredparentID = SyntaxGenerator::$features[$requiredvalueID]->parentID;
									if ($requiredparentID == $requiredfeatureID) {
										if ($comments) echo "<br> -- -- -- -- parent zero found, accepted";
										$found = 1;
										break;
									}
								} else {
									if ($comments) echo "<br> -- -- -- -- parent zero, not this";
								}
							}
							
							if ($parentID == $requiredfeatureID) {
								if ($comments) echo "<br> -- -- -- -- this is it";
								
								if ($requiredvalueID != $formFeatureID) {
									if ($comments) echo "<br> -- -- -- -- req incompatible values";
									$found = -1;
									break;
								} else {
									if ($comments) echo "<br> -- -- -- -- req compatible values";
									$found = 1;
									break;
								}
							} else {
								
								
								
							}
						}
						if ($found == 0) {
							
							$feature = SyntaxGenerator::$features[$requiredfeatureID];
							if ($comments) echo "<br>-- -- -- no feature found a1 - " . $feature->name;
								
							if ($defaultsearchfeatures[$requiredfeatureID] == 1) {
								if ($comments) echo "<br>-- -- -- -- This is default value, will be accepted x1";
							} else {
								$allmatch = false;
								break;
							}
						}
						if ($found == -1) {
							$feature = SyntaxGenerator::$features[$requiredfeatureID];
							//echo "<br>-- -- -- feature found, but incompatible - " . $feature->name;
							$allmatch = false;
							break;
						}
					}
					if ($allmatch == true) {
						if ($acceptedword != null) {
							echo "<br>-- -- accepted word already found 1 - " . $acceptedword. ", " . $form->wordform . " is duplicate";
							exit;
						} else{
							if ($comments) echo "<br>-- -- allmatch true .....";
							$acceptedword = $form->wordform;
						}
						if ($comments) echo "<br>-- -- accepted .....";
					} else {
						//echo "<br>-- -- not accepted .....";
					}
				}
			} else {
				if ($comments) echo "<br> -- -- compatible = false, " . $acceptedword;
				return null;	
			}
			
			
				
			if ($acceptedword == null) {
				$resultlist[] = "-fail-";
			//} else {
			//	$resultlist[] = $acceptedword;
			}
		}
		
		//echo "<br>Returgin accepted...";
		$returnarray = array();
		$returnarray[] = $acceptedword;
		return $returnarray;				
		
		//$concepts = Table::loadWhereInArray("worder_concepts", "conceptID", $neededconceptslist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		
	}
	
	
}