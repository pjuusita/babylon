<?php


class FeatureStructure {

	public static $wordclasses;
	public static $features;
	public static $arguments;
	public static $components;
	
	private $wordclassID;
	public $name;
	
	private $language;
	private $lemma;			// word->lemma, sanan perusmuoto
	private $wordform;			// wordform->wordform, taivutettu sana josta lähdettiin liikkeelle
	private $formID;		// wordform->rowID
	private $concept;		// concept->name, käsitteen nimi
	
	
	private $_features;
	private $_components;
	private $_argumentrequirements;
	private $_arguments;
	private $argumentsfull;
	
	
	
	
	public function __construct($lemma, $wordclassID) {
		$this->name = $lemma;
		$this->lemma = $lemma;
		$this->wordclassID = $wordclassID;
		
		$this->_features = array();
		//$this->_featurevalues = array();
		$this->_components = array();
		$this->_argumentrequirements = array();
		$this->_arguments = array();
		$this->argumentsfull = false;
	}


	// Tämä on vanha, pitäisi poistaa, mutta käytetään monessa paikkaa
	public function getClass() {
		return $this->wordclassID;
	}

	
	public function getWordClass() {
		return $this->wordclass;
	}

	public function setWordClass($wordclass) {
		$this->wordclass = $wordclass;
	}
	
	public function getWordClassID() {
		return $this->wordclassID;
	}
	
	public function setWordClassID($wordclassID) {
		$this->wordclassID = $wordclassID;
	}
	
	public function getFormID() {
		return $this->formID;
	}
	
	public function setFormID($formID) {
		$this->formID = $formID;
	}
	
	public function getWordForm() {
		return $this->wordform;
	}
	
	public function setWordForm($wordform) {
		$this->wordform = $wordform;
	}
	
	public function getWordID() {
		return $this->wordID;
	}
	
	public function setWordID($wordID) {
		$this->wordID = $wordID;
	}

	public function getConceptID() {
		return $this->conceptID;
	}
	
	public function setConceptID($conceptID) {
		$this->conceptID = $conceptID;
	}
	

	public function getConceptName() {
		return $this->conceptname;
	}
	
	public function setConceptName($conceptname) {
		$this->conceptname = $conceptname;
	}
	
	
	public function setArgumentsFull() {
		$this->argumentsfull = true;
	}
	

	public function getLanguageID() {
		return $this->languageID;
	}
	
	public function setLanguageID($langeugeID) {
		$this->languageID = $langeugeID;
	}
	
	
	public function getName() {
		return $this->name;
	}
	
	public function addComponent($componentID) {
		$this->_components[$componentID] = $componentID;
	}
	
	
	public function hasComponent($componentID) {
		if (isset($this->_components[$componentID])) return true;
		return false;
	}


	public function hasFeature($featureID) {
		if (isset($this->_features[$featureID])) return true;
		return false;
	}
	
	
	public function addFeature($featureID, $valueID) {
		$this->_features[$featureID] = $valueID;
		//$this->_featurevalues[$feature->featureID] = $value;
	}
	
	
	public function getFeature($featureID) {
		if (!isset($this->_features[$featureID])) return null;
		return $this->_features[$featureID];
	}
	
	public function getFeatureCount() {
		return count($this->_features);
	}
	
	public function getFeatures() {
		return $this->_features;
	}
	
	public function getComponents() {
		return $this->_components;
	}
	
	
	public function getArgumentRequirements() {
		return $this->_argumentrequirements;
	}
	

	public function getArgumentRequirement($argumentID) {
		if (!isset($this->_argumentrequirements[$argumentID])) return null;
		return $this->_argumentrequirements[$argumentID];
	}
	
	public function setArgument($argumentID, $featurestructure) {
		
		//echo "<br>.....setArgument - " . $argumentID . " - " . $featurestructure->name;
		$this->_arguments[$argumentID] = $featurestructure;
	}
	
	
	public function getArgument($argumentID) {
		/*
		echo "<br>getarguments... " . $argumentID;
		foreach($this->_arguments as $index => $value) {
			echo "<br> -- " . $index . " - " . $value->name;
		}
		*/
		return $this->_arguments[$argumentID];
	}
	
	
	public function removeArgument($argumentID) {
		unset($this->_arguments[$argumentID]);
	}
	
	
	
	/**
	 * Tämän avulla tarkistetaan onko parametrina annettu argumentti asetettu. Yleensä jos
	 * 
	 * 
	 * @param $argumentID
	 */
	public function isArgumentSetted($argumentID) {
		// TODO: jos allow more arguments on false, niin myähempiä argumentteja ei sallita, käytetään kun rulen 
		// määrittää, että jatkoruleja ei enää saa soveltaa (argumentsdisabled=true)
		if ($this->argumentsfull == true) return true;
		if (isset($this->_arguments[$argumentID])) return true;
		return false;
	}
	
	
	public function allowMoreArguments() {
		if ($this->argumentsfull == true) return false;
		return true;
	}
	

	// Lisätty tuki sille, että argumentti voi saada useampia componentID-arvoja
	public function addArgumentRequirement($argumentID, $componentID) {
		if (isset($this->_argumentrequirements[$argumentID])) {
			//echo "<br>add argument requirement (allready exists) -- argumentID:" . $argumentID . " - componentID:" . $componentID;
			// Tämä ylikirjoittaa olemassaolevan 
			//$componentlist = array();
			//$componentlist[$componentID] = $componentID;
			
			$this->_argumentrequirements[$argumentID][] = $componentID;
			//$this->_argumentrequirements[$argumentID][$componentID] = $componentID;
		} else {
			//echo "<br>add argument requirement -- argumentID:" . $argumentID . " - componentID:" . $componentID;
			//$componentlist = array();
			//$componentlist[$componentID] = $componentID;
			$arry = array();
			$arry[] = $componentID;
			$this->_argumentrequirements[$argumentID] = $arry;
		}
	}
	
	
	public function getArguments() {
		return $this->_arguments;
	}
	
	
	public function getCopy() {
		
		$fs = new FeatureStructure($this->name, $this->wordclassID);
		$fs->conceptID = $this->conceptID;
		
		foreach ($this->_features as $featureID => $valueID) {
			$fs->addFeature($featureID, $valueID);
		}
		
		foreach ($this->_components as $componentID => $value) {
			$fs->addComponent($componentID);
		}
		
		//echo "<br>copy argumentcount: " . count($this->_arguments);
		foreach ($this->_arguments as $argumentID => $subfs) {
			$fs->setArgument($argumentID, $subfs->getCopy());
		}
		
		//echo "<br>copy argumentrequirementcount: " . count($this->_argumentrequirements);
		foreach ($this->_argumentrequirements as $argumentID => $componentarray) {
			foreach ($componentarray as $index => $componentID) {
				$fs->addArgumentRequirement($argumentID, $componentID);
			}
		}
		
		return $fs;
	}
	
	
	public function getSemanticCopy() {
	
		$fs = new FeatureStructure($this->name, $this->wordclassID);
		$fs->conceptID = $this->conceptID;
	
		foreach ($this->_features as $featureID => $valueID) {
			$feature = FeatureStructure::$features[$featureID];
			$value = FeatureStructure::$features[$valueID];
			if ($feature->semanticlinkID > 0) {
				
				//echo "<br> -- setting semantic: " . $semanticfeature->name . " (" . $semanticfeature->featureID . ") = " . $semanticvalue->name . " (" . $semanticvalue->featureID . ")";
				//echo "<br> -- setting semval: " . $value->name . " (" . $value->featureID . ")";
				
				//echo "<br>Trying to get sementic - " . $feature->featureID . " .... " . $feature->semanticlinkID;
				//echo "<br>Trying to get sementic value - " . $value->featureID . " .... " . $value->semanticlinkID;
				
				$semanticfeature = FeatureStructure::$features[$feature->semanticlinkID];
				$semanticvalue = FeatureStructure::$features[$value->semanticlinkID];
				
				//echo "<br> -- setting semantic: " . $semanticfeature->name . " (" . $semanticfeature->featureID . ") = " . $semanticvalue->name . " (" . $semanticvalue->featureID . ")";
				
				//echo "<br> -- setting semantic: " . $semanticfeature->name . " (" . $semanticfeature->featureID . ") = " . $semanticvalue->name . " (" . $semanticvalue->featureID . ")";
				$fs->addFeature($semanticfeature->featureID, $semanticvalue->featureID);
			}
		}
	
		foreach ($this->_components as $componentID => $value) {
			$fs->addComponent($componentID);
		}
	
		foreach ($this->_arguments as $argumentID => $subfs) {
			$fs->setArgument($argumentID, $subfs->getSemanticCopy());
		}
	
		foreach ($this->_argumentrequirements as $argumentID => $componentarray) {
			foreach ($componentarray as $index => $componentID) {
				$fs->addArgumentRequirement($argumentID, $componentID);
			}
		}
	
		return $fs;
	}
	
	

	public function getRecursiveTargetCopy($targetLanguageID, $comments = false) {
	
		if ($comments) echo "<br> -- getRecursiveTargetCopy";
		$fs = new FeatureStructure($this->name, $this->wordclassID);
		$fs->conceptID = $this->conceptID;
	
		foreach ($this->_features as $featureID => $valueID) {
			$feature = FeatureStructure::$features[$featureID];
			
			//echo "<br> -- -- searching feature - " . $feature->name . "(" . $featureID . ") = " . $valueID;
			foreach(FeatureStructure::$features as $index => $feature) {
				if ($feature->languageID == $targetLanguageID) {
					//echo "<br> -- -- -- checking " . $feature->featureID . " (semantic:" . $feature->semanticlinkID;
					if ($feature->semanticlinkID == $valueID) {
						//echo "<br> -- -- target feature found - " . $feature->name;
						if ($feature->parentID == 0) {
							$fs->addFeature($feature->featureID, $feature->featureID);
						} else {
							$parentfeature = FeatureStructure::$features[$feature->parentID];
							$fs->addFeature($feature->parentID, $feature->featureID);
						}
					}
				}
			}
		}
		
		foreach ($this->_components as $componentID => $value) {
			$fs->addComponent($componentID);
		}
	
		foreach ($this->_arguments as $argumentID => $subfs) {
			$fs->setArgument($argumentID, $subfs->getRecursiveTargetCopy($targetLanguageID));
		}
	
		foreach ($this->_argumentrequirements as $argumentID => $componentarray) {
			foreach ($componentarray as $index => $componentID) {
				$fs->addArgumentRequirement($argumentID, $componentID);
			}
		}

		return $fs;
	}
	
	
	// when equals return 1
	public static function compare($featurestructure1, $featurestructure2, $comments = false) {

		if (count($featurestructure1->_features) != count($featurestructure2->_features)) {
			if ($comments) echo "<br> -- not equal, feature count differs";
			return false;
		}
		foreach ($featurestructure1->_features as $feature1 => $value1) {
			if (isset($featurestructure2->_features[$feature1])) {
				$value2 = $featurestructure2->_features[$feature1];
				if ($value1 != $value2) {
					if ($comments) echo "<br> -- not equal, feature value differs (" . $feature1 . ") ... " . $value1 . " != " . $value2;
					return false;
				}
			} else {
				if ($comments) echo "<br> -- not equal, feature not found " . $feature1;
				return false;
			}
		}

		if (count($featurestructure1->_components) != count($featurestructure2->_components)) {
			if ($comments) echo "<br> -- not equal, component count differs";
			return false;
		}
		foreach ($featurestructure1->_components as $componentID => $value) {
			if (!isset($featurestructure2->_components[$componentID])) {
				if ($comments) echo "<br> -- not equal, component not found - " . $componentID;
				return false;
			}
		}
		
		if (count($featurestructure1->_argumentrequirements) != count($featurestructure2->_argumentrequirements)) return false;
		foreach ($featurestructure1->_argumentrequirements as $argumentID => $componentarray) {
			if (isset($featurestructure2->_argumentrequirements[$argumentID])) {

				$valuecomponentarray = $featurestructure2->_argumentrequirements[$argumentID];
				if (count($componentarray) != count($valuecomponentarray)) return false;
				
				foreach ($componentarray as $index => $componentID) {
					$found = 0;
					foreach($valuecomponentarray as $index => $valuecomponent) {
						if ($valuecomponent == $componentID) {
							$found++;
						}
					}
					if ($found != 1) return false;
				}
				
			} else {
				if ($comments) echo "<br> -- not equal, argumentrequirement not found " . $argumentID;
				return false;
			}
		}
		
		foreach ($featurestructure1->_arguments as $argumentID => $subfs) {
			if (isset($featurestructure2->_arguments[$argumentID])) {
				$argumentfs = $featurestructure2->_arguments[$argumentID];
				$comp = FeatureStructure::compare($subfs, $argumentfs, $comments);
				if ($comp == false) return false;
			} else {
				if ($comments) echo "<br> -- not equal, argument not found " . $argumentID;
				return false;
			}
		}

		if ($comments) echo "<br> -- structures equal";
		return true;
	}
	
	
	
	public static function SetWordFeaturesRecursively($fs, $words, $wordclassfeatures, $wordsByConceptID, $comments = false) {
		
		//$comments = true;
		if ($comments) echo "<br> -- -- SetWordFeaturesRecursively - conceptID: " . $fs->conceptID;
		if ($comments) echo "<br> -- -- -- wordID: " . $wordsByConceptID[$fs->conceptID];
		$word = $words[$wordsByConceptID[$fs->conceptID]];
		
		if ($comments) echo "<br> -- -- -- word - " . $word->lemma . " (" . $word->wordID . ")";
		$fs->wordID = $word->wordID;
		$fs->name = $word->lemma;
		

		//if ($comments) echo "<br> -- checking wordclassfeatures " . count($wordclassfeatures);
		foreach($wordclassfeatures as $wcindex => $wordclassfeature) {
		
			if ($wordclassfeature->wordclassID == $fs->wordclassID) {
				if ($fs->hasFeature($wordclassfeature->featureID)) {
					$feature = FeatureStructure::$features[$wordclassfeature->featureID];
					//if ($comments) echo "<br> -- -- -- -- checking feature1 " . $feature->name . " - found, default = " . $wordclassfeature->defaultvalueID;
					
				} else {
					$feature = FeatureStructure::$features[$wordclassfeature->featureID];
					//if ($comments) echo "<br> -- -- -- -- checking feature2 " . $feature->name . " - not found";
					if ($wordclassfeature->defaultvalueID > 0) {
						$fs->addFeature($wordclassfeature->featureID,$wordclassfeature->defaultvalueID);
						$default =  FeatureStructure::$features[$wordclassfeature->defaultvalueID];
						//if ($comments) echo "<br> -- -- -- -- Setting default - " . $default->name;
					}
				}
			} else {
				$wordclass = FeatureStructure::$wordclasses[$wordclassfeature->wordclassID];
				//if ($comments) echo "<br> -- -- -- -- 2222 "  . $wordclassfeature->wordclassID . " - " . $wordclass->name;
			}
		}
		
		if ($word->features != "") {
			$featurelist = explode("|",$word->features);
			foreach($featurelist as $f1 => $featurecompo) {
				if ($featurecompo != "") {
					$featurecomps = explode(":", $featurecompo);
					$feature = FeatureStructure::$features[$featurecomps[0]];
					$value = FeatureStructure::$features[$featurecomps[1]];
					
					if ($fs->hasFeature($feature->featureID)) {
						//if ($comments) echo "<br> -- -- -- -- *!*!*!*!* adding word feature already setted - " . $feature->name . "";
						$fs->addFeature($feature->featureID,$value->featureID);
					} else {
						//if ($comments) echo "<br> -- -- -- -- adding word feature - " . $feature->name . " - " . $value->name;
						$fs->addFeature($feature->featureID,$value->featureID);
					}
				}
			}
		}
		
		foreach ($fs->getArguments() as $argumentID => $subfs) {
			FeatureStructure::SetWordFeaturesRecursively($subfs, $words, $wordclassfeatures, $wordsByConceptID, $comments);
		}
	}
	
	
	public function getConceptsRecursively(&$conceptlist) {
		$conceptlist[$this->conceptID] = $this->conceptID;
		foreach ($this->_arguments as $argumentID => $subfs) {
			$subfs->getConceptsRecursively($conceptlist);
		}
	}

	
	public function printFeatureStructure() {
		//echo "<br>printFeatureStructure aaa";
		$this->printFeatureStructurePrivate(false);
	}
	

	public function printFeatureStructureRecursive() {
		//echo "<br>printFeatureStructureRecursive aaa";
		$this->printFeatureStructurePrivate(true);
	}
	
	
	public function toString() {
		//echo "<br>featurestructure to string";
		$str = $this->toStringPrivate();
		//echo "<br>str = " . $str;
		return $str;
	}
	
	
	public function toJSON() {
		$str = "";
		$this->toJSONPrivate($str);
		return $str;
	}
	
	public function getLemma() {
		return $this->lemma;
	}
	
	
	// Tämä on kopioitu analyse.getwordformFSAction, sitä käytetään ilmeisesti viimeisimmässä toiminnossa, käyttöliittymässä piirtämiseen
	public function toJSONNew() {
		
		$str = "{";
		$class = FeatureStructure::$wordclasses[$this->wordclassID];
		if ($this->language != null) {
			$str = $str . " \"languageID\" : \"" .  $this->languageID . "\", ";
		} else {
			if ($this->languageID > 0) {
				$str = $str . " \"languageID\" : \"" . $this->languageID . "\", ";
			} else {
				$str = $str . " \"languageID\" : \"0\", ";
			}
		}
		$str = $str . " \"wordclass\": \"" . $class->abbreviation . "\", ";
		$str = $str . " \"lemma\" : \"" .  $this->lemma . "\", ";
		$str = $str . " \"word\" : \"" .  $this->wordform . "\", ";
		$str = $str . " \"wordclassID\": \"" . $class->wordclassID . "\", ";
		
		if ($this->formID != null) {
			$str = $str . " \"formID\" : \"" .  $this->formID . "\", ";
		} else {
			$str = $str . " \"formID\" : \"0\", ";
		}

		if ($this->formID != null) {
			$str = $str . " \"wordID\" : \"" .  $this->wordID . "\", ";
		} else {
			$str = $str . " \"wordID\" : \"0\", ";
		}
		$str = $str . " \"conceptID\" : \"" . $this->conceptID . "\", ";
		
		$str = $str . " \"arguments\" : ";
		$str = $str . "[";
		$str = $str . "],";
		
		$str = $str . " \"requirements\" : ";
		$str = $str . "{";
		if (count($this->getArgumentRequirements()) > 0) {
			$first = true;
			foreach($this->getArgumentRequirements() as $argumentID => $componentarray) {
				
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ", ";
				}
				
				$str = $str . " \"" . $argumentID . "\" : [ ";
				$firstcomponent = true;
				foreach($componentarray as $componentIndex => $componentID) {
					if ($firstcomponent == true) {
						$firstcomponent = false;
					} else {
						$str = $str . ", ";
					}
					$str = $str . " \"" . $componentID . "\" ";
				}
				$str = $str . " ]";
				
				/*
				foreach($componentarray as $componentIndex => $componentID) {
					if ($first == true) {
						$first = false;
					} else {
						$str = $str . ", ";
					}
					$argument = FeatureStructure::$arguments[$argumentID];
					$component = FeatureStructure::$components[$componentID];
					$str = $str . " \"" . $argument->name . "\" : \"" . $component->abbreviation . "\" ";
				}
				*/
				
				/*
				$argument = FeatureStructure::$arguments[$argumentID];
				foreach($componentarray as $index => $componentID) {
					$component = FeatureStructure::$components[$componentID];
					$str = $str . " \"" . $argument->name . "\" : \"" . $component->abbreviation . "\" ";
				}
				*/
				//$str = $str . "\"" . $componentID . "\"";
			}
		}
		$str = $str . "},";
		
		
		$str = $str . " \"features\" : ";
		$str = $str . "{";
		if ($this->getFeatureCount() > 0) {
			$first = true;
			foreach($this->getFeatures() as $featureID => $valueID) {
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ", ";
				}
				if ($featureID == $valueID) {
					$feature = FeatureStructure::$features[$featureID];
					$value = FeatureStructure::$features[$valueID];
					
					$valuestr = $value->abbreviation;
					$firstvalue = false;
					foreach($this::$features as $tempindex => $tempfeature) {
						//echo "<br>Feature - " . $tempfeature->name;
						if ($tempfeature->parentID == $featureID) {
							$valuestr = $valuestr . "|" . $tempfeature->abbreviation;
						}
					}
					$str = $str . " \"" . $feature->name . "\" : \"" . $valuestr . "\" ";
					
				} else {
					$feature = FeatureStructure::$features[$featureID];
					$value = FeatureStructure::$features[$valueID];
					$str = $str . " \"" . $feature->name . "\" : \"" . $value->abbreviation . "\" ";
				}
				
			}
		}
		$str = $str . "},";
		
		
		
		
		$str = $str . " \"components\" : ";
		$str = $str . "[";
		if (count($this->getComponents()) > 0) {
			$first = true;
			foreach($this->getComponents() as $index => $componentID) {
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ", ";
				}
				$component = FeatureStructure::$components[$componentID];
				//$str = $str . "\"" . $componentID . "\"";
				$str = $str . "\"" . $component->abbreviation . "\"";
			}
		}
		$str = $str . "]";
		$str = $str . " }";
		
		return $str;
	}
	
	
	public function toJSONInteger() {
	
		$str = "{";
		$str = $str . " \"languageID\" : \"" . $this->languageID . "\", ";
		//$str = $str . " \"wordclass\": \"" . $class->abbreviation . "\", ";
		$str = $str . " \"lemma\" : \"" .  $this->lemma . "\", ";
		$str = $str . " \"word\" : \"" .  $this->wordform . "\", ";
		$str = $str . " \"wordclassID\": \"" . $this->wordclassID . "\", ";
	
		if ($this->formID != null) {
			$str = $str . " \"formID\" : \"" .  $this->formID . "\", ";
			$str = $str . " \"wordID\" : \"" .  $this->wordID . "\", ";
		} else {
			$str = $str . " \"formID\" : \"0\", ";
			$str = $str . " \"wordID\" : \"0\", ";
		}
	
		$str = $str . " \"conceptID\" : \"" . $this->conceptID . "\", ";
		$str = $str . " \"conceptname\" : \"" . $this->conceptname . "\", ";
		$str = $str . " \"arguments\" : ";
		$str = $str . "[";
		$str = $str . "],";
	
		$str = $str . " \"requirements\" : ";
		$str = $str . "{";
		if (count($this->getArgumentRequirements()) > 0) {
			$first = true;
			foreach($this->getArgumentRequirements() as $argumentID => $componentarray) {

				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ", ";
				}
				
				$str = $str . " \"" . $argumentID . "\" : [ ";
				$firstcomponent = true;
				foreach($componentarray as $componentIndex => $componentID) {
					if ($firstcomponent == true) {
						$firstcomponent = false;
					} else {
						$str = $str . ", ";
					}
					$str = $str . " \"" . $componentID . "\" ";
				}
				$str = $str . " ]";
				
				/*
				foreach($componentarray as $componentIndex => $componentID) {
					if ($first == true) {
						$first = false;
					} else {
						$str = $str . ", ";
					}
					$str = $str . " \"" . $argumentID . "\" : \"" . $componentID . "\" ";
				}
				*/
			}
		}
		$str = $str . "},";
	
	
		$str = $str . " \"features\" : ";
		$str = $str . "{";
		if ($this->getFeatureCount() > 0) {
			$first = true;
			foreach($this->getFeatures() as $featureID => $valueID) {
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ", ";
				}
				if ($featureID == $valueID) {
					$feature = FeatureStructure::$features[$featureID];
					//echo "<br> -- "  . $featureID . " -- "  . $valueID . " -- ";
					$value = FeatureStructure::$features[$valueID];
						
					$firstvalue = false;
					if ($feature->parentID == 0) {
						$valuestr = $valueID . "|0";
					} else {
						$parent = $this::$features[$feature->parentID];
						$valuestr = $valueID . "|" . $parent->featureID;
					}
					//$str = $str . " \"" . $featureID . "\" : \"" . $valuestr . "\" ";
					$str = $str . " \"" . $featureID . "\" : \"" . $valueID . "\" ";
						
				} else {
					$feature = FeatureStructure::$features[$featureID];
					$value = FeatureStructure::$features[$valueID];
					$str = $str . " \"" . $featureID . "\" : \"" . $valueID . "\" ";
				}
	
			}
		}
		$str = $str . "},";
	
	
	
	
		$str = $str . " \"components\" : ";
		$str = $str . "[";
		if (count($this->getComponents()) > 0) {
			$first = true;
			foreach($this->getComponents() as $index => $componentID) {
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ", ";
				}
				$component = FeatureStructure::$components[$componentID];
				//$str = $str . "\"" . $componentID . "\"";
				$str = $str . "\"" . $componentID . "\"";
			}
		}
		$str = $str . "]";
		$str = $str . " }";
	
		return $str;
	}
	
	
	private function getLanguageFeatureForSemanticFeature($featureID, $languageID) {
		foreach(FeatureStructure::$features as $index => $feature) {
			if (($feature->languageID == $languageID) && ($feature->semanticlinkID == $featureID)) {
				return $feature;
			}
		}
	}
	
	
	
	private function recursiveChangeSemanticFeaturesToLanguageFeatures($fs, $languageID) {
		//echo "<br>Looping feature - " . $fs->lemma;
		$features = $fs->getFeatures();
		foreach($features as $parentfeatureID => $featureID) {
			//echo "<br>Loop feature found - " . $parentfeatureID . " - " . $featureID;
			$feature = FeatureStructure::$features[$featureID];
			if ($feature->languageID == 0) {
				//echo "<br>General feature found - " . $feature->name;
				$feature = $this->getLanguageFeatureForSemanticFeature($featureID, $languageID);
				$parentFeature = FeatureStructure::$features[$feature->parentID];
				//echo "<br>Languagefeature - " . $feature->name . "(" . $feature->featureID . ") - " . $parentFeature->name . " (" . $parentFeature->featureID . ")";
				$fs->addFeature($parentFeature->featureID,$feature->featureID);				
			}
		}
		$arguments = $fs->getArguments();
		foreach($arguments as $argumentID => $featurestructure) {
			//echo "<br>More arguments found - " . $featurestructure->name;
			FeatureStructure::recursiveChangeSemanticFeaturesToLanguageFeatures($featurestructure, $languageID);
		}
	}
	
	
	
	public function addLanguageWordFeatures($languageID, $wordfeatures) {
		
	}
	
	
	public function changeSemanticFeaturesToLanguageFeatures($languageID) {
		//echo "<br>Looping feature - " . $this->lemma;
		$features = $this->getFeatures();
		foreach($features as $index => $featureID) {
			//echo "<br>Feature - "  . $feature->name . " - "  .$feature->lemma;
			$feature = FeatureStructure::$features[$featureID];
			//echo "" . $feature->toStringPrivate();
			//print_r($feature);
			if ($feature->languageID == 0) {
				//echo "<br>General feature found - " . $feature->lemma;
				$feature = $this->getLanguageFeatureForSemanticFeature($featureID, $languageID);
				$parentFeature = FeatureStructure::$features[$feature->parentID];
				$fs->addFeature($parentFeature->featureID,$feature->featureID);				
			}
		}
		$arguments = $this->getArguments();
		foreach($arguments as $argumentID => $featurestructure) {
			$this->recursiveChangeSemanticFeaturesToLanguageFeatures($featurestructure, $languageID);
		}
	}
	
	
	// Tätä käytetään concept structure tulostukseen, eli muotoa jossa kielikohtainen tieto on poissa
	public function toConceptStructureJSON() {
	
		$str = "{";
		$class = FeatureStructure::$wordclasses[$this->wordclassID];
		$str = $str . " \"wordclass\": \"" . $class->abbreviation . "\", ";
		$str = $str . " \"lemma\" : \"" .  $this->lemma . "\", ";
		$str = $str . " \"wordclassID\": \"" . $class->wordclassID . "\", ";
		$str = $str . " \"conceptID\" : \"" . $this->conceptID . "\", ";
	
		$str = $str . " \"features\" : ";
		$str = $str . "{";
		if (count($this->_features) > 0) {
			$first = true;
			foreach($this->_features as $featureID => $valueID) {
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ", ";
				}
				$feature = FeatureStructure::$features[$featureID];
				$value = FeatureStructure::$features[$valueID];
				$str = $str . "\"" . $feature->name . "\" : \"" . $value->abbreviation . "\"";
			}
		}
		$str = $str . "},";
		
		$str = $str . " \"arguments\" : ";
		$str = $str . "[";
		if (count($this->_arguments) > 0) {
			$first = true;
			foreach($this->_arguments as $argumentID => $featurestructure) {
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ",";
				}
				$str = $str . " {";
				$argument = FeatureStructure::$arguments[$argumentID];
				$str = $str . "\"argumentname\" : \"" . $argument->name . "\", ";
				$str = $str . "\"argumentID\" : \"" . $argument->argumentID . "\", ";
				$value = $featurestructure->toConceptStructureJSON();
				$str = $str . "\"argumentvalue\" : " .  $value;
				$str = $str . " }";
			}
		}
		$str = $str . "]";
		
	
		$str = $str . " }";
	
		return $str;
	}
	
	public function toJSONPrivate(&$str) {
		
		$str = $str . "{";
		$class = FeatureStructure::$wordclasses[$this->wordclassID];
		$str = $str . " \"wordclass\": \"" . $class->abbreviation . "P\", ";
		$str = $str . " \"name\" : \"" .  $this->name . "\", ";
		$str = $str . " \"wordform\" : \"" .  $this->name . "\", ";
		$str = $str . " \"conceptID\" : \"" .  $this->name . "\", ";
		$str = $str . " \"wordID\" : \"" .  $this->name . "\", ";
		$str = $str . " \"languageID\" : \"" .  $this->name . "\", ";
		
		$str = $str . " \"features\" : ";
		$str = $str . "[";
		if (count($this->_features) > 0) {
			$first = true;
			foreach($this->_features as $featureID => $valueID) {
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ", ";
				}
				$feature = FeatureStructure::$features[$featureID];
				$value = FeatureStructure::$features[$valueID];
				$str = $str . "\"" . $feature->name . "\" : \"" . $value->abbreviation . "\"";
			}
		}
		$str = $str . "],";
		
		$str = $str . " \"components\" : ";
		$str = $str . "[";
		if (count($this->_components) > 0) {
			$first = true;
			foreach($this->_components as $index => $componentID) {
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ", ";
				}
				$component = FeatureStructure::$components[$componentID];
				$str = $str . "\"" . $component->name . "\"";
			}
		}
		$str = $str . "],";
		
		
		$str = $str . " \"arguments\" : ";
		$str = $str . "[";
		if (count($this->_arguments) > 0) {
			$first = true;
			foreach($this->_arguments as $argumentID => $featurestructure) {
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ",";
				}
				$str = $str . " {";
				$argument = FeatureStructure::$arguments[$argumentID];
				$str = $str . "\"argumentname\" : \"" . $argument->name . "\", ";
				$value = "";
				$featurestructure->toJSONPrivate($value);
				$str = $str . "\"argumentvalue\" : " .  $value;
				$str = $str . " }";
			}
		}
		$str = $str . "]";
		
		$str = $str . " }";
		return;
	}

	
	public function toStringPrivate() {
		$class = FeatureStructure::$wordclasses[$this->wordclassID];
		$str = "" . $class->abbreviation . "P(" .  $this->name . ")";
		
		$str = $str . "[";
		if (count($this->_arguments) > 0) {
			$first = true;
			foreach($this->_arguments as $argumentID => $featurestructure) {
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ",";
				}
				$argument = FeatureStructure::$arguments[$argumentID];
				$str = $str . $argument->name . "-";
				$str = $str . $featurestructure->toStringPrivate();
			}
		}
		$str = $str . "]";
		/*
		$str = $str . "],[";
		$first = true;
		foreach($this->_features as $featureID => $valueID) {
			if ($first == true) {
				$first = false;
			} else {
				$str = $str . ",";
			}
			$feature = FeatureStructure::$features[$featureID];
			$value = FeatureStructure::$features[$valueID];
			$str = $str . $feature->name . "-" . $value->abbreviation;
		}
		$str = $str . "],[";
		
		
		$first = true;
		foreach($this->_argumentrequirements as $argumentID => $componentID) {
			foreach($componentarray as $componentID => $value) {
				if ($first == true) {
					$first = false;
				} else {
					$str = $str .  ",";
				}
				$argument = FeatureStructure::$arguments[$argumentID];
				$component = FeatureStructure::$components[$componentID];
				$str = $str .  $argument->name . "-" . $component->name;
				//$str = $str .  $argument->name . "(" . $argumentID . ") - must be - " . $component->name;
			}
		}
		$str = $str .  "],[";

		$first = true;
		foreach($this->_components as $componentID => $componentID) {
			if ($first == true) {
				$first = false;
			} else {
				$str = $str . ",";
			}
			$component = FeatureStructure::$components[$componentID];
			$str = $str . " " . $component->name;
		}
		$str = $str . "]";
		*/
		return $str;
	}
	

	
	public function printFeatureStructurePrivate($recursive, $minimal = false) {
		//echo "<br>FeatureStructure: " . $this->name;
		$class = FeatureStructure::$wordclasses[$this->wordclassID];
		if ($minimal == false) echo "<br>";
		echo "" . $class->abbreviation . "P (" .  $this->name . ")";
		//echo " - argumentcount:" . count($this->_arguments);
		//if ($minimal == false) echo "<br>";
		echo "<br>arguments: (" . count($this->_arguments) . ")";
		if (count($this->_arguments) > 0) {
			$first = true;
			foreach($this->_arguments as $argumentID => $featurestructure) {
				if ($first == true) {
					$first = false;
				} else {
					echo ", ";
				}
				if ($argumentID == 1) {
					echo "<br> -- featureargument empty";
				} elseif ($argumentID == 2) {
					echo "<br> -- featureargument main";
				} else {
					$argument = FeatureStructure::$arguments[$argumentID];
					if ($recursive == true) {
						echo " -- " . $argument->name . " = ";
						$featurestructure->printFeatureStructureRecursive();
					} else {
						echo " -- " . $argument->name . " (" . $argumentID . ") = " . $featurestructure->name;
					}
				}
			}
			//echo "]";
		} else {
			//echo "<br>[ no arguments ]";
		}
		
		//if ($minimal == false) echo "<br>";
		//else ", ";
		echo "<br>features: ";
		$first = true;
		foreach($this->_features as $featureID => $valueID) {
			if ($first == true) {
				$first = false;
			} else {
				echo ", ";
			}
			//echo "<br>featureID - " . $featureID;
			$feature = FeatureStructure::$features[$featureID];
			//echo "<br>ValueID - " . $valueID;
			$value = FeatureStructure::$features[$valueID];
			echo " " . $feature->name . " = " . $value->abbreviation;
		}
		//echo "]";
	
		if (count($this->_features) == 0) {
			//echo "<br>[ no features ]";
		}
		
		
		//if ($minimal == false) echo "<br>";
		//else ", ";
		echo "<br>Argumentrequirements:";
			
		//if ($recursive == false) {
			$first = true;
			foreach($this->_argumentrequirements as $argumentID => $componentarray) {
				foreach($componentarray as $componentIndex => $componentID) {
					if ($first == true) {
						$first = false;
					} else {
						echo ", ";
					}
					$argument = FeatureStructure::$arguments[$argumentID];
					$component = FeatureStructure::$components[$componentID];
					echo " " . $argument->name . "(" . $argumentID . ") - must be - " . $component->name;
				}
				
				
				/*
				foreach($componentarray as $componentID => $value) {
					if ($first == true) {
						$first = false;
					} else {
						echo ", ";
					}
					$argument = FeatureStructure::$arguments[$argumentID];
					$component = FeatureStructure::$components[$componentID];
					echo " " . $argument->name . "(" . $argumentID . ") - must be - " . $component->name;
				}
				*/
			}
			//echo "]";
			
			//if ($minimal == false) echo "<br>";
			//else ", ";
			echo "<br>Components: ";
			$first = true;
			//echo "<br>_componentcoutn - " . count($this->_components);
			foreach($this->_components as $componentID => $componentID) {
				if ($first == true) {
					$first = false;
				} else {
					echo ", ";
				}
				//echo "<br>fs component - " . $componentID;
				$component = FeatureStructure::$components[$componentID];
				echo " " . $component->name;
			}
			//echo " ]";
		//}
	}
	
}



?>