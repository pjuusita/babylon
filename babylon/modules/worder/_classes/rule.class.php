<?php



class Rule {
	
	public static $wordclassfeatures;
	public static $arguments;
	public static $operators = null;
	
	
	private $index;
	public $name;
	public $allowmorearguments;
	public $ruleID;
	public $wordclassID;
	public $analyse;
	public $generate;
	public $languageID;
	public $conceptID;
	public $conceptName;
	
	public $ruleterms;
	public $termwordclasses;
	public $featureagreements;
	public $allagreements;
	public $featureconstraints;
	public $unsetarguments;
	public $featureconstraintoperators;
	public $componentrequirements;
	public $resultfeatures;
	public $resultpositions;
	public $resultpositionvalues;
	public $activeargument;
	public $activeargumentpos;
	public $ruleconcepts;
	public $ruleconceptIDs;

	
	public $conceptarguments;
	public $conceptcomponents;
	
	
	const OPERATOR_EQUAL = 1;
	const OPERATOR_NOT_EQUAL = 2;
	const OPERATOR_IS_EMPTY = 3;
	const OPERATOR_NOT_EMPTY = 4;
	const OPERATOR_OVERRIDE = 5;
	
	

	/**
	 * Luo säännän nimellä. ArgumentID on se argumentti, jota tällä säännällä modifioidaan.
	 * Headilla pitää olla kyseinen argumentti olemassa wordclassargumentseissa.
	 *
	 * Yksi mahdollisuus olisi, että head on aina indeksissä 1, ja yhdistettävien sanojen
	 * indeksit asetetaan setPosition-fuktiolla.
	 *
	 * @param $name
	 * @param $argumentID
	 */
	//public function __construct($name, $wordclass, $argumentID, $argumentpos) {
	public function __construct($name, $wordclassID, $analyse, $conceptID = 0) {
	
		$this->name = $name;
		$this->wordclassID = $wordclassID;
		$this->analyse = $analyse;
		$this->index = 0;
		$this->conceptID = $conceptID;
		$this->conceptarguments = null;
		$this->conceptcomponents = null;
	
		$this->allowmorearguments = true;
		$this->ruleterms = array();
		$this->ruleconcepts = array();
		$this->ruleconceptIDs = array();
		$this->termwordclasses  = array();
		$this->featureagreements[0] = array();
		$this->allagreements = array();
		$this->featureconstraints = array();
		$this->featureconstraintoperators = array();
		$this->componentrequirements = array();
		$this->resultfeatures = array();
		$this->resultpositions = array();
		$this->resultpositionvalues = array();
	
		$this->featureconstraints[-1] = array();
	
		$class = FeatureStructure::$wordclasses[$wordclassID];
	}
	

	
	public static function getRuleOperators() {
		if (Rule::$operators == null) {
			$operators = array();
			$operators[Rule::OPERATOR_EQUAL] = "=";
			$operators[Rule::OPERATOR_NOT_EQUAL] = "!=";
			$operators[Rule::OPERATOR_IS_EMPTY] = "== [empty]";
			$operators[Rule::OPERATOR_NOT_EMPTY] = "!= [empty]";
			$operators[Rule::OPERATOR_OVERRIDE] = "=>";				// override
			Rule::$operators = $operators;
		}
		return Rule::$operators;
	}
	
	private static function getWordclassArguments($wordclass) {
	
		$selectedfeatures = array();
		foreach(Rule::$arguments as $index => $wordclassfeature) {
			if ($wordclassfeature->wordclassID == $wordclass) {
				$selectedfeatures[$wordclassfeature->rowID] = $wordclassfeature;
			}
		}
		return $selectedfeatures;
	}
	
	
	

	private static function getWordclassFeatures($wordclass) {
	
		$selectedfeatures = array();
		foreach(Rule::$wordclassfeatures as $index => $wordclassfeature) {
			if ($wordclassfeature->wordclassID == $wordclass) {
				$selectedfeatures[$wordclassfeature->featureID] = $wordclassfeature;
			}
		}
		return $selectedfeatures;
	}
	
	
	
	public function setConceptName($name) {
		$this->conceptName = $name;
	}
	
	public function getRuleTerms() {
		return $this->ruleterms;
	}
	
	public function getRuleTermCount() {
		return count($this->ruleterms);
	}
	
	
	public function setRuleID($ruleID) {
		$this->ruleID = $ruleID;
	}
	
	
	/**
	 * Tämän funktion avulla poistetaan säännän soveltamisen jälkeen mahdollisuus muiden sääntöjen soveltamiseen
	 * tiettyyn argumentti-positioon. Esimerkiksi 'se talo', kun 'se'-sana on kiinnitetty, niin substantiiville
	 * ei haluta enää lisää argumentteja, esimerkiksi '*punainen se talo' vs. 'se punainen talo'.
	 * 
	 * Tätä todennäkäisesti tarvitaan lähinnä substantiivien kanssa.
	 * 
	 */
	public function setAllowmoreArguments($boolean) {
		$this->allowmorearguments = $boolean;
	}

	
	public function addConceptArgument($argumentID, $componentID, $mode) {
		
		if ($this->conceptarguments == null) {
			$this->conceptarguments = array();
			$this->conceptcomponents = array();
		}
		//echo "<br>addconceptargument - argument:" . $argumentID . ", component:" . $componentID;
		$this->conceptarguments[] = $argumentID;
		$this->conceptcomponents[] = $componentID;
	}
	
	/**
	 * Sama kuin edellä, mutta disabloidaan kaikki wordclass argumentit.
	 * 
	 */
	public function allowmoreArguments() {
		return $this->allowmorearguments;
	}
	
	
	
	public function addFeatureAgreement($position1, $position2, $featureID) {
		
		//echo "<br>Adding agreement - " . $position1 . " - " . $position2 . " - " . $featureID;
		
		if (!isset($this->termwordclasses[$position1])) {
			echo "<br>Rule addFeatureAgreement failed -- argument in position " . $position1 . " not defined";
			exit;
		}
		
		if (!isset($this->termwordclasses[$position2])) {
			echo "<br>Rule addFeatureAgreement failed -- argument in position " . $position2 . " not defined";
			exit;
		}
		
		$allagreementslist = array();
		$allagreementslist[0] = $position1;
		$allagreementslist[1] = $position2;
		$allagreementslist[2] = $featureID;
		$this->allagreements[] = $allagreementslist;
		
		$agreements1 = $this->featureagreements[$position1];
		$agreements2 = $this->featureagreements[$position2];
		
		//var_dump($agreements1);
		//var_dump($agreements2);
		
		if (isset($agreements1[$featureID]) && isset($agreements2[$featureID])) {

			if ($agreements1[$featureID] == $agreements2[$featureID]) {
				echo "<br>Rule addFeatureAgreement failed -- feature " . $featureID . " already defined";
				exit;
			} else {
				echo "<br>Rule addFeatureAgreement failed -- feature " . $featureID . " already defined (but not equals)";
				exit;
			}
			
		} else if (isset($agreements1[$featureID]) || isset($agreements2[$featureID])) {
			if (isset($agreements1[$featureID])) {
				$this->featureagreements[$position2][$featureID] = $agreements1[$featureID];
				echo "<br>Rule addFeatureAgreement warning -- feature " . $featureID . " already defined (index1)";
				//exit;
				
				//$this->agreements[$position2] = $agreements1[$featureID];
			} else {
				
				$this->featureagreements[$position1][$featureID] = $agreements2[$featureID];
				echo "<br>Rule addFeatureAgreement warning -- feature " . $featureID . " already defined (index2)";
				//exit;
				
				//$this->agreements[$position1] = $agreements2[$featureID];
			}
		} else {
			$this->index = $this->index + 1;
			$this->featureagreements[$position1][$featureID] = $this->index;
			$this->featureagreements[$position2][$featureID] = $this->index;
		}
	}
	
	
	
	public function addTerm($position, $argumendID, $wordclassID, $argumentsallowed, $conceptID, $concept) {

		if (isset($this->termwordclasses[$position])) {
			echo "<br>Rule addArgument failed -- index position already defined";
			exit;
		} else {
			$this->ruleterms[$position] = $argumendID;
			$this->termwordclasses[$position] = $wordclassID;
			$this->termargumentsallowed[$position] = $argumentsallowed;
			$this->ruleconcepts[$position] = $concept;
			$this->ruleconceptIDs[$position] = $conceptID;
				
			$this->featureagreements[$position] = array();
			$this->featureconstraints[$position] = array();
			$this->featureconstraintoperators[$position] = array();
			$this->componentrequirements[$position] = array();
			$this->unsetarguments[$position] = array();
			$this->unsetfeatures[$position] = array();
		}
	}
	
	
	public function addConstraint($position, $featureID, $valueID, $operator) {
		if (!isset($this->featureconstraints[$position])) {
			echo "<br>Rule addConstraint failed -- index not defined";
			exit;
		} else {
			$this->featureconstraints[$position][$featureID] = $valueID;
			$this->featureconstraintoperators[$position][$featureID] = $operator;
		}
	}
	
	

	public function addComponent($position, $componentID, $presence) {
		if (!isset($this->componentrequirements[$position])) {
			echo "<br>Rule addComponent failed -- index not defined";
			exit;
		} else {
			$this->componentrequirements[$position][$componentID] = $presence;
		}
	}
	
	
	

	public function addResultFeature($featureID, $valueID, $position) {

		$this->resultpositions[$featureID] = $position;
		$this->resultpositionvalues[$featureID] = $valueID;
	}
	
	

	public function addUnsetArgument($position, $argumentID) {
	
		if (!isset($this->unsetarguments[$position])) {
			echo "<br>Rule addUnsetArgument failed -- index not defined";
			exit;
		} else {
			$this->unsetarguments[$position][$argumentID] = $argumentID;
		}
	}
		

	public function addUnsetFeature($position, $featureID) {
	
		if (!isset($this->unsetfeatures[$position])) {
			echo "<br>Rule addUnsetFeature failed -- index not defined";
			exit;
		} else {
			$this->unsetfeatures[$position][$featureID] = $featureID;
		}
	}
	
	public function getWordClassID() {
		return $this->termwordclasses[0];
	}
	
	
	// Tässä oletetaan, että compatibility on jo tarkistettu ja ovat yhteensopivia...
	public function applyAnalyseRule($index, $structures, $comments) {
		
		//$comments = true;
		if ($comments) echo "<br> -- applying rule - " . $this->name . " (" . $this->ruleID . ")";
		
		//if ($comments) echo "<br> -- maintermposition " . $maintermposition;
		
		$maintermposition = -1;
		$emptyposition = -1;
		$argumentID = -1;
		foreach($this->ruleterms as $position => $tempargumentID) {
			if ($comments) echo "<br> -- -- ruleterms - position:" . $position . ", argumentID:" . $tempargumentID . "";
			if ($tempargumentID == 2) {
				$maintermposition = $position;
			} else {
				if ($tempargumentID == 1) {
					$emptyposition = $position;
				} else {
					$argumentID = $tempargumentID;					
				}
			}
		}
			
		if (!(($maintermposition == 0) || ($maintermposition == 1))) {
			if ($comments) echo "<br> -- -- maintermposition not found - " . $maintermposition;
			return false;
		}
		if ($comments) echo "<br> -- -- maintermposition " . $maintermposition;
			
		if ($maintermposition == 0) {
				
			$fs = $structures[$index]->getCopy();
			
			if ($emptyposition == 1) {
				if ($comments) echo "<br> -- -- argument [1] on empty, ei component requirementteja";
			} else {
				if ($argumentID == -1) {
					if ($comments) echo "<br> -- -- -- yksipaikkainen rule, ei argumenttia, argumenttia ei aseteta";					
				} else {
					if ($comments) echo "<br> -- -- ruleID " . $this->ruleID;
					$argument = FeatureStructure::$arguments[$argumentID];
					if ($comments) echo "<br> -- -- argument [1] not empty, asetetaan argumentti " . $argument->name . " (" . $argumentID . ")";
					//$argumentfs = $structures[$index+1]->getCopy();
					$fs->setArgument($argumentID, $structures[$index+1]);
				}
			}
			

			foreach($this->resultpositions as $featureID => $position) {
			
				if ($position == null) {
					//echo "<br> -- resultposition is null";
					$feature = FeatureStructure::$features[$featureID];
					$featurevalue = FeatureStructure::$features[$this->resultpositionvalues[$featureID]];
					if ($comments) echo "<br> -- -- -- setting result feature - " . $feature->name . " = " . $featurevalue->name;
					$fs->addFeature($featureID, $this->resultpositionvalues[$featureID]);
			
				} else {
					if ($position == 1) {
						$feature = FeatureStructure::$features[$featureID];
						$featurevalueID = $structures[$index+1]->getFeature($featureID);
						$featurevalue = FeatureStructure::$features[$featurevalueID];
						if ($comments) echo "<br> -- -- -- setting result feature position 1 - " . $feature->name . " = " . $featurevalue->name;
						$fs->addFeature($featureID, $featurevalueID);
					} else {
						if ($comments) echo "<br> -- -- -- setting result feature, current structure position.. ";
					}
				}
			}
			
			
			// TODO: asetetaan agreementtien featuret oikein....
			
			/*
			$agreements = $this->featureagreements[0];
			echo "<br> -- agreementcount0: " . count($agreements);
			foreach($agreements as $featureID => $valueID) {
				echo "<br> -- -- -- agreement: " . $featureID . " - " . $valueID;
			}
			*/
			
			
			// Puuttuukohan tästä argument valueiden asettaminen?
			/*
			$constraints = $this->featureconstraints[0];
			foreach($constraints as $featureID => $valueID) {
				
				$feature = FeatureStructure::$features[$featureID];
				$featurevalue = FeatureStructure::$features[$valueID];
				echo "<br> -- -- -- setting constraint feature - " . $feature->name . " = " . $featurevalue->name;
				$fs->addFeature($featureID, $valueID);
			}
			*/
			
		}
		
		if ($maintermposition == 1) {
	
			$fs = $structures[$index+1]->getCopy();
				
			if ($emptyposition == 0) {
				if ($comments) echo "<br> -- -- argument [0] on empty, ei component requirementteja";
			} else {
				$argument = FeatureStructure::$arguments[$argumentID];
				if ($comments) echo "<br> -- -- argument [0] not empty, asetetaan argumentti " . $argument->name . " (" . $argumentID . ")";
				//$argumentfs = $structures[$index]->getCopy();
				$fs->setArgument($argumentID, $structures[$index]);
			}
			
			foreach($this->resultpositions as $featureID => $position) {
				
				if ($position == null) {
					//echo "<br> -- resultposition is null";
					$feature = FeatureStructure::$features[$featureID];
					$featurevalue = FeatureStructure::$features[$this->resultpositionvalues[$featureID]];
					if ($comments) echo "<br> -- -- -- setting result feature - " . $feature->name . " = " . $featurevalue->name;
					$fs->addFeature($featureID, $this->resultpositionvalues[$featureID]);
						
				} else {
					if ($position == 0) {
						$feature = FeatureStructure::$features[$featureID];
						$featurevalueID = $structures[$index]->getFeature($featureID);
						$featurevalue = FeatureStructure::$features[$featurevalueID];
						if ($comments) echo "<br> -- -- -- setting result feature position 0 - " . $feature->name . " = " . $featurevalue->name;
						$fs->addFeature($featureID, $featurevalueID);
					} else {
						if ($comments) echo "<br> -- -- -- setting result feature, current structure position.. ";
					}
				}
			}
			
			// TODO: tämä pitää kipioida muutoksin myös maintermposition 0 haaraan
			// Asetetaan agreementtien arvot result featureen
			$agreements = $this->featureagreements[1];
			if ($comments) echo "<br> -- agreementcount0: " . count($agreements);
			foreach($agreements as $featureID => $valueID) {
				if ($comments) echo "<br> -- -- -- agreement2: " . $featureID . " - " . $valueID;
				$agreemeentvalue = 0;
				
				$features = $fs->getFeatures();
				foreach($features as $fsindex => $fsfeaturevalue) {
					if ($comments) echo "<br> -- -- -- -- agreement values: " . $fsindex . " - " . $fsfeaturevalue;
					if ($fsindex == $featureID) {
						if ($comments) echo "<br> -- -- -- -- agreement value found: " . $fsindex . " - " . $fsfeaturevalue;
						$agreemeentvalue = $fsfeaturevalue;
					}
				}
				
				$agreementfeaturevalue = FeatureStructure::$features[$agreemeentvalue];
				$agreementargumentvalue = 0;
				
				$argumentfs = $structures[$index];
				$features = $argumentfs->getFeatures();
				foreach($features as $fsindex => $fsfeaturevalue) {
					if ($comments) echo "<br> -- -- -- -- agreement2 values: " . $fsindex . " - " . $fsfeaturevalue;
					if ($fsindex == $featureID) {
						if ($comments) echo "<br> -- -- -- -- agreement2 value found: " . $fsindex . " - " . $fsfeaturevalue;
						$agreementargumentvalue = $fsfeaturevalue;
					}
				}
				
				if ($agreemeentvalue == $agreementargumentvalue) {
					if ($comments) echo "<br> -- -- -- -- -- agreement values match for " . $featureID . ": " . $agreemeentvalue . " - " . $agreementargumentvalue;
					// Jos a
					$fs->addFeature($featureID, $agreemeentvalue);
				} else {
					if ($comments) echo "<br> -- -- -- -- -- agreement values _no_ match for " . $featureID . ": " . $agreemeentvalue . " - " . $agreementargumentvalue;
					
					if ($comments) echo "<br>RuleID  .. "  . $this->ruleID . " - " . $this->name;
					if ($comments) echo "<br>Agreemeentvalue xxx .. "  . $agreemeentvalue;
					$agreementmainfeature = FeatureStructure::$features[$agreemeentvalue];
					
					if ($agreementmainfeature->parentID > 0) {
						if ($agreementmainfeature->parentID == $agreementargumentvalue) {
							if ($comments) echo "<br> -- -- -- -- -- -- agreement feature parent match - " . $agreementmainfeature->parentID;
							if ($comments) echo "<br> -- -- -- -- -- -- -- result value - " . $agreementmainfeature->featureID;
							$fs->addFeature($featureID, $agreementmainfeature->featureID);
						}
					}
					
					$agreementargumentfeature = FeatureStructure::$features[$agreementargumentvalue];
					if ($comments) echo "<br>Agreementargumentvalue .. "  . $agreementargumentvalue;
					if ($agreementargumentfeature->parentID > 0) {
						if ($agreementargumentfeature->parentID == $agreemeentvalue) {
							if ($comments) echo "<br> -- -- -- -- -- -- agreement argument feature parent match - " . $agreementargumentfeature->parentID;
							if ($comments) echo "<br> -- -- -- -- -- -- -- result value - " . $agreementargumentfeature->featureID;
							$fs->addFeature($featureID, $agreementargumentfeature->featureID);
						}
					}
				}
			}
				
				
			/*
			$constraints = $this->featureconstraints[1];
			foreach($constraints as $featureID => $valueID) {
			
				$feature = FeatureStructure::$features[$featureID];
				$featurevalue = FeatureStructure::$features[$valueID];
				echo "<br> -- -- -- setting constraint feature - " . $feature->name . " = " . $featurevalue->name;
				$fs->addFeature($featureID, $valueID);
			}
			*/
				
		}
		
		
		
		/*
		$indexfs = $structures[$index];
		if ($indexfs == null) {
			echo "<br>infex is null";
		}
		//$indexfs->printFeatureStructure();
		//$fs->setArgument($this->activeargument, $structures[$index+$this->activeargumentpos]);
		
		foreach ($this->ruleterms as $pos => $argumentID) {
			if ($comments) echo "<br>applyrule - copy arguments - " . ($index+$pos) . " - pos:" . $pos . " - " . $argumentID . ", index:" . $index;
			
			//$copy = $structures[$index+$pos]->getCopy();
			//$copy->printFeatureStructure();
			if ($pos != 0) $fs->setArgument($argumentID, $structures[$index+$pos]);
		}
		
		
		if ($this->allowmorearguments == false) $fs->setArgumentsFull();
		*/
		return $fs;
	}
	
	
	
	public function applyGenerateRule($fs, $comments) {

		//$comments = true;
		if ($comments) echo "<br><br>ApplyGenerateRule...<br>";
		if ($comments) echo $fs->toConceptStructureJSON();
		if ($comments) echo "<br>Breakpoint";
		
		$maintermposition = -1;
		$emptyposition = -1;
		foreach($this->ruleterms as $position => $ruleargumentID) {
			if ($ruleargumentID == 2) {
				$maintermposition = $position;
			}
			if ($ruleargumentID == 1) {
				$emptyposition = $position;
			}			
		}

		$leftFS = null;
		$rightFS = null;
		
		if ($maintermposition == 0) {
			
			if ($emptyposition > -1) {
				if ($comments) echo "<br> -- Empty position found - pos:0";
				$leftFS = $fs->getCopy();
				$wordclassID = $this->termwordclasses[$emptyposition];
				$rightFS = new FeatureStructure('empty', $wordclassID);
				$conceptID = $this->ruleconceptIDs[$emptyposition];
				$rightFS->setConceptID($conceptID);
					
			} else {
				
				if (count($this->ruleterms) == 1) {
					// only one term, no right side needed...
					$leftFS = $fs->getCopy();
						
				} else {
					$argumentID = $this->ruleterms[1];
					if ($comments) echo "<br>0 ArgumentID - " . $argumentID;
					$leftFS = $fs->getCopy();
					$leftFS->removeArgument($argumentID);
					$rightTemp = $fs->getArgument($argumentID);
					$rightFS = $rightTemp->getCopy();
				}

			}
			
		} else {
			
			if ($emptyposition > -1) {
				if ($comments) echo "<br> -- Empty position found - pos:1";
				$rightFS = $fs->getCopy();
				$wordclassID = $this->termwordclasses[$emptyposition];
				$leftFS = new FeatureStructure('empty', $wordclassID);
				$conceptID = $this->ruleconceptIDs[$emptyposition];
				$leftFS->setConceptID($conceptID);				
			} else {
				$argumentID = $this->ruleterms[0];
				if ($comments) echo "<br>1 ArgumentID - " . $argumentID;
				$rightFS = $fs->getCopy();
				$rightFS->removeArgument($argumentID);
				$leftTemp = $fs->getArgument($argumentID);
				$leftFS = $leftTemp->getCopy();
			}
		}
		//if ($comments) echo "<br><br>LefFS:";
		//if ($comments) echo $leftFS->toConceptStructureJSON();
		//if ($comments) echo "<br><br>RightFS:";
		//if ($comments) echo $rightFS->toConceptStructureJSON();
		
		
		
		// asetetaan leftside constraints
		if (isset($this->featureconstraints[0])) {
			$constraints = $this->featureconstraints[0];
			foreach($constraints as $featureID => $valueID) {
				if ($comments) echo "<br> -- feature constraint from rule-left found: " . FeatureStructure::$features[$featureID]->name . " = " .  FeatureStructure::$features[$valueID]->name;
				$featurefound = false;
				$features = $leftFS->getFeatures();
				foreach($features as $fsfeatureID => $fsvalueID) {
					if ($fsfeatureID == $featureID) {

						$operator = $this->featureconstraintoperators[0][$featureID];
						
						if ($operator == 2) {
							//if ($comments) echo "<br> -- -- Rule Mainfeature constraint found = " . FeatureStructure::$features[$featureID]->name . " = " .  FeatureStructure::$features[$valueID]->name;
							if ($comments) echo "<br> -- -- " . FeatureStructure::$features[$featureID]->name . ": operator found - " . $operator;
							$featurefound = true;
						} else {

							if ($valueID == $fsvalueID) {
								if ($comments) echo "<br> -- feature already setted";
							} else {
								if ($comments) echo "<br> -- not compatible2";
								$leftFS->addFeature($featureID, $valueID);
								$featurefound = true;
							}
						}
					}
				}
				if ($featurefound == false) {
					$leftFS->addFeature($featureID, $valueID);
					if ($comments) echo "<br> -- -- adding left feature = " . FeatureStructure::$features[$featureID]->name . " = " .  FeatureStructure::$features[$valueID]->name;
				}
			}	
		}
		
		// asetetaan rightside constraints
		
		
		if (count($this->ruleterms) == 1) {
			if ($comments) echo "<br> ... only one term in rule, no rightside featureconstraint check necessary";
		} else {
			if (isset($this->featureconstraints[1])) {
				$constraints = $this->featureconstraints[1];
				foreach($constraints as $featureID => $valueID) {
					if ($comments) echo "<br> -- feature constraint from rule-right found: " . FeatureStructure::$features[$featureID]->name . " = " .  FeatureStructure::$features[$valueID]->name;
					$featurefound = false;
					$features = $rightFS->getFeatures();
					foreach($features as $fsfeatureID => $fsvalueID) {
						if ($fsfeatureID == $featureID) {
							if ($valueID == $fsvalueID) {
								if ($comments) echo "<br> -- -- feature already setted";
							} else {
								if ($comments) {
									$temp = FeatureStructure::$features[$featureID];
									$temp2 = FeatureStructure::$features[$valueID];
									echo "<br> -- -- feature (" . $temp->name . ") already setted , overriding previous with feature from rule (" . $temp2->name . ")";
								}
								$rightFS->addFeature($featureID, $valueID);
								$featurefound = true;
							}
						}
					}
					if ($featurefound == false) {
						$rightFS->addFeature($featureID, $valueID);
						if ($comments) echo "<br> adding right feature = " . FeatureStructure::$features[$featureID]->name . " = " .  FeatureStructure::$features[$valueID]->name;
					}
				}
			}
		}
		
		
		// asetetaan agreementit (ja tsekataan, että kaikissa agreementeissa on arvot?)
		foreach($this->allagreements as $index => $agreementarray) {
			$index1 = $agreementarray[0];
			$index2 = $agreementarray[1];
			$featureID = $agreementarray[2];
			if ($comments) echo "<br>agreement - " . $index1 . " - " . $index2 . " - " . $featureID;
			
			$foundvalue = null;
			$leftfeatures = $leftFS->getFeatures();
			foreach($leftfeatures as $leftfeatureID => $leftfeaturevalue) {
				if ($leftfeatureID == $featureID) {
					$foundvalue = $leftfeaturevalue;
					break;
				}
			}
			if ($foundvalue != null) {
				$rightFS->addFeature($featureID, $foundvalue);
			} else {
				$rightfeatures = $rightFS->getFeatures();
				foreach($rightfeatures as $rightfeatureID => $rightvalueID) {
					if ($rightfeatureID == $featureID) {
						$foundvalue = $rightvalueID;
						break;
					}
				}
				if ($foundvalue != null) {
					$leftFS->addFeature($featureID, $foundvalue);
				} else {
					if ($comments) echo "<br> -- agreement feature not found";
				}
			}
		}
		
		
		// Asetetaan resultfeatureseista, mikäli arvot on sidottu childeihin
		//if ($comments) echo "<br> -- -- asetetaan reulspositions...";
		foreach($this->resultpositions as $featureID => $position) {
			if ($comments) echo "<br> -- -- resultpositions --" . $featureID . " - " . $position;
			if ($position != null) {
				if ($comments) echo "<br> -- -- -- this value from item - " . $featureID . " - " . FeatureStructure::$features[$featureID]->name;
				$foundvalue = $fs->getFeature($featureID);
				if ($comments) echo "<br> -- -- -- -- " . FeatureStructure::$features[$foundvalue]->name . " - " . $foundvalue;
				//$leftFS->addFeature($featureID, $foundvalue);
				
				if ($position == 0) {
					if ($maintermposition == 1) {
						$newvalue = $leftFS->getFeature($featureID);
						if ($comments) echo "<br> -- -- -- -- new value found from left: " . $newvalue;
						$rightFS->addFeature($featureID, $newvalue); 
					}
				}
				if ($position == 1) {
					if ($maintermposition == 0) {
						$newvalue = $rightFS->getFeature($featureID);
						if ($comments) echo "<br> -- -- -- -- new value found from right: " . $newvalue;
						$leftFS->addFeature($featureID, $newvalue);
					}
				}
			}
		}
		
		if ($comments) echo "<br><br>LefFS after rule apply:<br>";
		if ($comments) echo $leftFS->toConceptStructureJSON();
		if ($comments) echo "<br><br>RightFS after rule apply:<br>";
		if ($rightFS != null) {
			if ($comments) echo $rightFS->toConceptStructureJSON();
		} else {
			if ($comments) echo "<br>No RightFS<br>";
		}
		
		
		
		
		// palautetaan resultfeaturesissa left ja right.
		$resultfeatures = array();
		$resultfeatures[] = $leftFS;
		if ($rightFS != null) $resultfeatures[] = $rightFS;
		return $resultfeatures;
	}
	
	
	/**
	 * 
	 * Palauttaa truen jos parametrina annettu featurestructure täyttää säännän kriteerit.
	 * 
	 * @param $index
	 * @param $structures
	 * @param $wordclasses 
	 */
	public function isAnalyseCompatible($index, $structures, $comments) {
		
		
		$comments = false;
		$comments2 = false;
		
		if ($comments) $comments2 = true;
		
		//$acceptrule = true;
		//$comments = true;
		//$comments2 = true;
		
		//if ($comments) echo "<br>Tsekataan voiko rulea soveltaa tähän - " . $this->name . ", index=" . $index;
		//echo "<br>Leftside - ";
		
		// Pitää vielä tsekata onko agreement vapaa, esim. onko agentti asettamatta
		/*
		foreach($this->ruleterms as $position => $argumentID) {
			if ($structures[$index]->isArgumentSetted($argumentID) == true) {
				if ($comments) echo "<br>Current structure doesn't allow arguments, already setted or doesn't have";
				return false;
			}
		}
		*/
		
		
		// Käydään lävitse kaikki tämän rulen argumentit. Otetaan siis structures-listasta
		// läpikäytävä indeksi + argumentin indeksi, jolloin tsekataan siis rulen toisen osan
		// soveltuvuutta. Note: voiko säännöllä olla vain yksi toinen elementti. Tässä oletetaan
		// että voi olla useampia.
		if ($comments) echo "<br><br>Tsekataan onko säännön oikea ja vasen puoli oikeaa sanaluokkaa";
		if ($comments) echo "<br>Termcount - " . count($this->termwordclasses) . ", ruleID:" . $this->ruleID;
		$acceptrule = true;
		foreach($this->termwordclasses as $position => $wordclassID) {
				
			
			if ($index + $position < 0) {
				if ($comments2) echo "<br>-- beyond first, not compatible";
				$acceptrule = false;
			} else {
				if ($index + $position >= count($structures)) {
					if ($comments2) echo "<br>-- beyond last, not compatible";
					$acceptrule = false;
				} else {

					$tempFS = $structures[$index+$position];
					
					if ($comments) echo "<br>-- tsekataan conceptID";
					
					if (isset($this->ruleconceptIDs[$position])) {
						$neededconceptID = $this->ruleconceptIDs[$position];
						if ($comments) echo "<br>-- -- neededconcept - " . $neededconceptID;
						if ($neededconceptID > 0) {
							if ($comments) echo "<br>-- -- rule needs concept - " . $tempFS->getConceptID();
							if ($tempFS->getConceptID() != $neededconceptID) {
								if ($comments2) echo "<br>-- conceptID fails";
								return false;
							}
						}
					}
						
					
					if ($comments) echo "<br>-- checking argument position (index+argumentindex) = " . ($index+$position ). ", argumenindex = " . $position . ", counntti=" . count($structures);
					if ($wordclassID == $structures[$index+$position]->getWordClassID()) {
						
						// Säännön toinen osapuoli täsmää structures taulun elementtiin.
						
						if ($comments) {
							$class1 = FeatureStructure::$wordclasses[$wordclassID];
							$class2 = FeatureStructure::$wordclasses[$structures[$index+$position]->getWordClassID()];
							//if ($comments) echo "<br>-- ArgumentIndex: " . $position;
							//if ($comments) echo "<br>-- Currentindex: " . ($index+$position);
							if ($comments) echo "<br>-- Compatible - " . $class1->name . " (" . $wordclassID . ") vs. " . $class2->name . " (" . $structures[$index+$position]->getWordClassID() . ")";
						}
		
					} else {
						if ($comments) {
							$class1 = FeatureStructure::$wordclasses[$wordclassID];
							$class2 = FeatureStructure::$wordclasses[$structures[$index+$position]->getWordClassID()];
							//if ($comments) echo "<br>-- ArgumentIndex: " . $position;
							//if ($comments) echo "<br>-- Currentindex: " . ($index+$position);
							if ($comments2) echo "<br>-- Not compatible4 (index: " . ($index+$position) . ",argumentindex:" . $position . ") - " . $class1->name . " (" . $wordclassID . ") vs. " . $class2->name . " (" . $structures[$index+$position]->getWordClassID() . ")";
						}
						$acceptrule = false;
					}
				}
			}
		}
		
		if ($acceptrule == false) {
			if ($comments2) echo "<br> -- Sanaluokat ei täsmää";
			return false;
		}
		
		if ($comments) echo "<br>** Sanaluokat täsmää";
		$foundvalues = array();
		$agreementsholds = true;
		if ($comments) echo "<br>";
		
		// Tsekataan argumentit...?
		if (count($this->ruleterms) == 1) {
			if ($comments) echo "<br>yksipaikkainen rule, argumentteja ei tarvitse tsekata";
		} else {
			if ($comments) echo "<br>kaksipaikkainen rule tsekataan, että argumentit täsmää...";
			
			$maintermposition = -1;
			$emptyposition = -1;
			$argumentID = -1;
			foreach($this->ruleterms as $position => $tempArgumentID) {
				if ($comments) echo "<br> -- ruleterms - position:" . $position . ", argumentID:" . $tempArgumentID . "";
				if ($tempArgumentID == 2) {
					$maintermposition = $position;
				} else {
					if ($tempArgumentID == 1) {
						$emptyposition = $position;
					} else {
						$argumentID = $tempArgumentID;
					}
				}
			}
			if ($comments) echo "<br>Selected argumentID = " . $argumentID;
			
			if (!(($maintermposition == 0) || ($maintermposition == 1))) {
				if ($comments2) echo "<br> -- -- maintermposition not found - " . $maintermposition;
				return false;
			}
			// Jos rulen vasemmanpuoleinen fs on mainterm
			if ($maintermposition == 0) {
				
				// TODO: tässä varmaan virhe, mistä tuo argumentID saadaan?
				if ($structures[$index]->isArgumentSetted($argumentID) == true) {
					$argument = FeatureStructure::$arguments[$argumentID];
					if ($comments2) echo "<br> -- -- Argument already setted [0]: " . $argument->name . " (" . $argument->argumentID . ")";
					return false;
				} else {
					if ($emptyposition == 1) {
						echo "<br> -- -- argument [1] on empty, ei argument requirementteja";
					} else {
						$argument = FeatureStructure::$arguments[$argumentID];
						$componentArray = $structures[$index]->getArgumentRequirement($argumentID);
						if ($componentArray == null) {
							return false;
						}
						
						$componentfound = false;
						foreach($componentArray as $index2 => $componentID) {
							if ($comments) echo "<br> -- -- argument component requirement found - " . $componentID;
							if ($componentID == null) {
								if ($comments2) echo "<br> -- -- no argument requirement[1] - " . $argument->name;
								return false;
							} else {
								$component = FeatureStructure::$components[$componentID];
								if ($comments) echo "<br> -- -- fs[1] component " . $component->name . " löytyi.";
							
								if ($structures[$index+1]->hasComponent($componentID)) {
									$component = FeatureStructure::$components[$componentID];
									if ($comments) echo "<br> -- -- fs[1] löytyi argumentcomponent " . $argument->name . " component " . $component->name . " löytyi.";
									$componentfound = true;
								} else {
									$component = FeatureStructure::$components[$componentID];
									if ($comments) echo "<br> -- -- fs[1] ei sisällä argumentin " . $argument->name . " vaatimaa componenttia - " . $component->name;
									//return false;
								}
							}
						}
						if ($componentfound == false) {
							return false;
						}
						
						
						
					}				
				}
			}
			
			// Jor rulen oikeanpuoleinen fs on mainterm
			if ($maintermposition == 1) {
				if ($structures[$index+1]->isArgumentSetted($argumentID) == true) {
					$argument = FeatureStructure::$arguments[$argumentID];
					if ($comments2) echo "<br> -- -- Argument already setted [1]: " . $argument->name . " (" . $argument->argumentID . ")";
					return false;
				} else {
					if ($emptyposition == 0) {
						if ($comments) echo "<br> -- -- argument [0] on empty, ei argument requirementteja";
					} else {
						$argument = FeatureStructure::$arguments[$argumentID];
						//$componentID = $structures[$index+1]->getArgumentRequirement($argumentID);
						
						
						if ($comments) echo "<br> -- -- -- fs from index - " . $index;
						$componentArray = $structures[$index+1]->getArgumentRequirement($argumentID);
						
						if ($componentArray == null) {
							if ($comments2) echo "<br> -- -- -- no argument requirements for ArgumentID - " . $argumentID;
							return false;
						}
						
						$componentfound = false;
						foreach($componentArray as $index2 => $componentID) {
							if ($comments) echo "<br> -- -- -- argument component requirement found - " . $componentID;
							if ($componentID == null) {
								if ($comments2) echo "<br> -- -- no argument requirement[1] - " . $argument->name;
								return false;
							} else {
								if ($structures[$index]->hasComponent($componentID)) {
									$component = FeatureStructure::$components[$componentID];
									if ($comments) echo "<br> -- -- fs[1] argumentcomponent " . $argument->name . " component " . $component->name . " löytyi.";
									$componentfound = true;
								} else {
									$component = FeatureStructure::$components[$componentID];
									if ($comments) echo "<br> -- -- fs[1] ei sisällä argumentin " . $argument->name . " vaatimaa componenttia - " . $component->name;
									//return false;
								}
							}
						}
						if ($componentfound == false) {
							return false;
						}
						
						
						
					}
				}
			}
		}
		if ($comments) echo "<br> -- Argumentit täsmää";
		
		
		
		if ($comments) echo "<br><br>Loopataan featureagreementit läpi, verrataan currentfs:n arvoja, uuden argumentindexin arvoihin";
		if (count($this->featureagreements) == 0) {
			if ($comments) echo "<br>No featureagreements - 0, featureagreements läpikäyntieä ei tarvita";
		} else {
			if ($comments) echo "<br> -- Featureagreements count - " . count($this->featureagreements);
			// TODO: tämä toiminto on hieman epäselvä... ainakin lista käydään läpi kahteen kertaan
			//   - positionia ei mielestäni tarvita mihinkään.
			foreach($this->featureagreements as $position => $rulefeatureagreementlist) {
					
				//$argumentclass = $this->termwordclasses[$position];
				//$currentargumentwordclass = FeatureStructure::$wordclasses[$argumentclass];
			
				//if ($comments) echo "<br>-- argumentposition = " . $position;
				//if ($comments) echo "<br>-- checking structures index:" . ($index+$position) . " - " . $currentargumentwordclass->name;
				$currentfs = $structures[$index+$position];
				//if ($comments) echo "<br>-- Checking featurestructure - " . $currentfs->toString();
					
				foreach($rulefeatureagreementlist as $featureID => $value) {
					if ($comments) echo "<br>-- xxx featureID ---- (" . $featureID . ")";
						
					$feature = FeatureStructure::$features[$featureID];
					//$featurevalue = FeatureStructure::$features[$featureID];
					if ($comments) echo "<br>-- check featureagreements, featuren jonka valueta etsitään - " . $feature->name . " (" . $value . ")";
					if ($comments) echo "<br>-- Löydetty featuren value = " . $currentfs->getFeature($featureID);
			
					if (isset($foundvalues[$value])) {			 // arvo on kohdattu aiemmin
						if ($foundvalues[$value] ==  $currentfs->getFeature($featureID)) {
							if ($comments) echo "<br>---- Agreement holds - " .  $feature->name . ", arvo on molemmissa = " . $foundvalues[$value];
						} else {
							
							// Nyt pitää tsekata onko foundvaluessin
							if ($comments) echo "<br>-- Foundvalue = " . $foundvalues[$value];
							
							$foundfeature =  FeatureStructure::$features[$foundvalues[$value]];
							if ($comments) echo "<br>-- Foundfeature = " . $foundfeature->name;

							if ($foundfeature->parentID == $currentfs->getFeature($featureID)) {
								if ($comments) echo "<br> -- -- parentfeature found, compatible true";
							} else {
								if ($comments2) echo "<br>-- Existings variable value [" . $value . "] = " . $foundvalues[$value];
								if ($comments2) echo "<br>-- Not hold - currentfs value = " . $currentfs->getFeature($featureID);
								if ($comments2) echo "<br>****** Agreement doesn't holds";
								$agreementsholds = false;
							}
							
							//if ($foundfeature->parentID == $)
							//$parentfeature = FeatureStructure::$features[$featureID]->parentID;
							//if ($comments) echo "<br>-- " . $parentfeature-;
								
							
						}
					} else {
						if ($comments) echo "<br> -- Featuren valueta ei ole aiemmin asetettu (" . $value . ") = " . $currentfs->getFeature($featureID);
						$foundvalues[$value] = $currentfs->getFeature($featureID);
					}
				}
			}
		}
		
			
		if ($agreementsholds == false) {
			if ($comments2) echo "<br> -- All featrueagreements doesn't hold";
			return false;
		}
		if ($comments) echo "<br> -- Feature agreementit täsmää";
		
		
		if ($comments) echo "<br><br>Tsekataan vielä featurerequirementit";
		$requirementsholds = true;
		$requirementcount = 0;
		foreach($this->termwordclasses as $position => $wordclassID) {
					
			if ($comments) echo "<br> -- position = " . $position;
			//if ($comments) echo "<br> -- checking structures (index+argumentindex):" . ($index+$position);
			$currentfs = $structures[$index+$position];
			if ($comments) echo "<br> -- -- Checking item - " . $currentfs->name;
			
			//if ($comments) echo "<br>-- Requirement count - " . count($this->featureconstraints[$position]);
			foreach($this->featureconstraints[$position] as $featureID => $valuefeatureID) {
				
				$feature = FeatureStructure::$features[$featureID];
				$shouldbevaluefeature = FeatureStructure::$features[$valuefeatureID];
				//echo "<br>Currentfs - " . $currentfs->toJSON();
				if ($currentfs->getFeature($featureID) == null) {
					echo "<br>Feature is null - " . $featureID;
				}
				$isfeaturevalue = FeatureStructure::$features[$currentfs->getFeature($featureID)];
				if ($comments) echo "<br> -- -- checking feature " . $feature->name . ", should be " . $shouldbevaluefeature->abbreviation . "... foundvalue = " . $isfeaturevalue->name;
				$operator = $this->featureconstraintoperators[$position][$featureID];
				if ($comments) echo "<br> -- -- xx operator - " . $operator;
				
				if ($operator == 5) {		// overriding operator
					if ($comments) echo "<br> -- -- overriding operator, accepted";
				} else {
					if ($operator == 2) {	// not equals operation
						if ($currentfs->getFeature($featureID) == $valuefeatureID) {
							if ($comments2) echo "<br> -- -- not equals operator, feature values equals -> not accepted";
							$requirementsholds = false;
							return false;
						} else {
							if ($comments) echo "<br> -- -- not equals operator, feature values not equal, accepted";
						}
					} else {
						
						// Pitäisi ehkä tsekata overridaako tämä leftsiden arvon...
						
						//if (isset($this->resultpositionvalues[$featureID])) {
						//	if ($comments) echo "<br> -- -- this overrides leftside...";
						//}
						
						if ($currentfs->getFeature($featureID) == $valuefeatureID) {
							if ($comments) echo "<br> -- -- featurerequirement holds";
						} else {
							if ($comments2) echo "<br> -- -- checking feature " . $feature->name . ", should be " . $shouldbevaluefeature->abbreviation . "... foundvalue = " . $isfeaturevalue->name;
							if ($comments2) echo "<br> -- -- requirement doesn't hold 4";
							
							//$valli = $this->resultpositionvalues[$featureID];
							//if ($comments) echo "<br> -- -- valli - " . $valli;
								
							$requirementsholds = false;
							return false;
						}
					}
				}
			}
		}
			
		if ($requirementsholds == false) {
			if ($comments2) echo "<br>---- All requirements doesn't hold";
			return false;
		}
		if ($comments) echo "<br>All featureconstraints hold";
			
		
		// pitää vielä tsekata componenttien olemassaolo....
		if (count($this->componentrequirements) > 0) {
			foreach($this->componentrequirements as $position => $componentlist) {
				if (count($componentlist) > 0) {
					foreach($componentlist as $componentID => $operation) {
						
						
						if ($comments) echo "<br> -- component requirement - " . $componentID . " - " . $operation;
						if ($operation == 1) {
							if ($structures[$index+$position]->hasComponent($componentID) == false) {
								if ($comments2) echo "<br> -- component not exits, shoud exist";
								return false;
							}
						} else {
							if ($operation == 2) {
								if ($structures[$index+$position]->hasComponent($componentID) == true) {
									if ($comments2) echo "<br> -- component exits, should not";
									return false;
								}
							} else {
								echo "<br> -- unknown component operation - " . $operation;
								exit;
							}
						}
					}	
				} else {
					if ($comments) echo "<br> -- no components for position " . $position;
				}
			}
		}
		if ($comments) echo "<br>** All componentrequirements hold";
		
		
		if ($comments) echo "<br>** isAnalyseCompotible.. tarkistukset täsmää";
		return true;
	}
	
	
	public function isGenerateCompatible($fs, $comments = false) {
		
		//$comments = true;
		
		//if ($comments) $this->printRule();
		
		if ($comments) echo "<br><br><br>Rule - " . $this->ruleID . " - " . $this->name;
		if ($comments) echo "<br><br>";
		//if ($comments) echo $fs->toConceptStructureJSON();
		
		$featurearguments = $fs->getArguments();
		$argumentsfound = false;
		$argumentposition = null;
		$maintermposition = null;
		$argumentfs = null;
		
		// Tsekataan aluksi, että jokin rulen käyttämä argumentti löytyy featurestructuresta
		foreach($featurearguments as $argumentID => $argumentvalue) {
			
			if ($comments) echo "<br>Trying to find argument - " . $argumentID . " (" . FeatureStructure::$arguments[$argumentID]->name . ")" . ", wordclass = " . $argumentvalue->getWordClassID();
			//print_r($argumentvalue);
			
			foreach($this->ruleterms as $position => $ruleargumentID) {
				if ($comments) echo "<br> -- ruleargument = " . $position . " - " . $ruleargumentID; // . " (" . FeatureStructure::$arguments[$ruleargumentID]->name . ")";
				
				//$this->termwordclasses[$position]
				
				if ($argumentID == $ruleargumentID) {
					if ($this->termwordclasses[$position] != $argumentvalue->getWordClassID()) {
						if ($comments) echo "<br> -- ruleargument wordclasses doesn't match - " . FeatureStructure::$wordclasses[$this->termwordclasses[$position]]->name . " vs. " . FeatureStructure::$wordclasses[$argumentvalue->getWordClassID()]->name;							
						return false;
					}
					$argumentsfound = true;
					$argumentposition = $position;
					//if ($comments) {
					//	echo "<br>+++++++++++++++++++++++++++++++++++ argumentfs setted<br>";
					//	var_dump($argumentvalue);
					//	echo "<br><br>";
					//}
					$argumentfs = $argumentvalue;
				}
				if ($ruleargumentID == 2) {			// TODO: Mainterm hardkoodattu
					$maintermposition = $position;
				}
			}
		}
				
		$othercheck = true;
		if ($argumentsfound == false) {
			if ($comments) echo "<br> -- argument not found, tsekataan onko empty";
			$emptyfound = false;
			
			foreach($this->ruleterms as $position => $ruleargumentID) {
				//if ($comments) echo "<br> -- -- ruleterms = " . $position . " - " . $ruleargumentID . " (" . FeatureStructure::$arguments[$ruleargumentID]->name . ")";
			
				if ($ruleargumentID == 2) {		// TODO: tämä on hardkoodattu indeksi maintermille
					//if ($comments) echo "<br> -- -- -- mainterm found";
					$maintermposition = $position;
				}
				
				if ($ruleargumentID == 1) {		// TODO: tämä on hardkoodattu indeksi tyhjälle argumentille
					//if ($comments) echo "<br> -- -- -- empty found";
					$argumentposition = $position;
					$emptyfound = true;
				}
			}
			//exit;
			if ($emptyfound == false) {
				if (count($this->ruleterms) == 1) {
					if ($comments) echo "<br> -- -- -- no empty found, but only one term in rule";
				} else {
					if ($comments) echo "<br> -- -- -- no empty found, return false";
					return false;
				}
			}
			
			// Tsekataan maintermin wordclass...
			$maintermwordclassID = $this->termwordclasses[$maintermposition];
			//if ($comments) echo "<br> -- Maintermwordclasses - " . $maintermwordclassID;
			//if ($comments) echo "<br> -- fs wordclass - " . $fs->getWordClassID();

			if ($maintermwordclassID != $fs->getWordClassID()) {
				if ($comments) echo "<br> -- Maintermwordclasses - " . $maintermwordclassID;
				if ($comments) echo "<br> -- fs wordclass - " . $fs->getWordClassID();
				if ($comments) echo "<br> -- mainterm wordclass incompatible, return false";
				return false;
			}
			
			$othercheck = false;
		}
		
		// Tsekataan seuraavaksi ovatko asetetut featuret yhteensopivia rulen kanssa...

		$compatible = true;
		foreach($this->resultpositionvalues as $featureID => $valueID) {
			
			if ($this->resultpositions[$featureID] == "") {			// result feature asetetaan kiinteästi...
				if ($comments) echo "<br>Tsekataan resultpositionien yhteensopivuus";
				if (!isset(FeatureStructure::$features[$featureID])) echo " feature not found - " . $featureID;
				if (!isset(FeatureStructure::$features[$valueID])) echo " feature not found - " . $valueID;
				$feature = FeatureStructure::$features[$featureID];
				$value = FeatureStructure::$features[$valueID];
				if ($comments) echo "<br> -- resultfeature - " . $feature->name . " = " . $value->name . ",";
				
				$found = false;
				$features = $fs->getFeatures();
				foreach($features as $fsfeatureID => $fsvalueID) {
					//if ($comments) echo "<br> -- -- featurevalue = " . FeatureStructure::$features[$fsfeatureID]->name . " = " .  FeatureStructure::$features[$fsvalueID]->name;
						
					if ($fsfeatureID == $featureID) {
						if ($comments) echo "<br> -- featurevalue found in fs = " . FeatureStructure::$features[$fsfeatureID]->name . " = " .  FeatureStructure::$features[$fsvalueID]->name;
						if ($valueID == $fsvalueID) {
							if ($comments) echo "<br> -- -- values match - " . FeatureStructure::$features[$valueID]->name . " vs. " .  FeatureStructure::$features[$fsvalueID]->name;
							$found = true;
						} else {
							// Sallitaan tämä, koska generatessa ylikirjoitetaan uudella arvolla...
							if ($comments) echo "<br> -- -- values doesn't match - " . FeatureStructure::$features[$valueID]->name . " vs. " .  FeatureStructure::$features[$fsvalueID]->name . ", but this is override.. accept";
							$compatible = false;
							return false;
							//$found = true;
						}
					}
				}
				
					
				if ($found == false) {
					if ($comments) echo "<br> -- -- value not found - " . FeatureStructure::$features[$valueID]->name;
					return false;
				}
			} else {
				
				$feature = FeatureStructure::$features[$featureID];
				if ($comments) echo "<br> -- -- resulffeature comes from position- " . FeatureStructure::$features[$valueID]->name;
				// TODO: not implemented??
				//if ($comments) echo "<br>Not implemented.";
				//exit;
			}
		}
		if ($comments) echo "<br> -- resultpositions check finished.<br>";
		
		
		// Tsekataan featureiden yhteensopivuus maintermille
		$constraints = $this->featureconstraints;
		if ($comments) echo "<br> -- checking featureconstraints...";
		if (isset($this->featureconstraints[$maintermposition])) {
			$mainfeatureconstraints = $this->featureconstraints[$maintermposition];
			
				
			
			foreach($mainfeatureconstraints as $featureID => $valueID) {
				
				$operator = $this->featureconstraintoperators[$maintermposition][$featureID];
				if ($comments) echo "<br> -- -- Rule Mainfeature constraint found = " . FeatureStructure::$features[$featureID]->name . " = " .  FeatureStructure::$features[$valueID]->name;
				if ($comments) echo "<br> -- -- operator = " . $operator . " in position " . $maintermposition;
				
				$features = $fs->getFeatures();

				if (!(($operator == 1) || ($operator == 2) || ($operator == 5))) {
					echo "<br> -- -- operator " . $operator . " -- Not implemented";
					exit;
				}
				
				if ($operator == 1) {
					
					foreach($features as $fsfeatureID => $fsvalueID) {
						//if ($comments) echo "<br> -- -- -- checking featurestructure " . FeatureStructure::$features[$fsfeatureID]->name . " = " .  FeatureStructure::$features[$fsvalueID]->name;
						if ($featureID == $fsfeatureID) {
							if ($valueID == $fsvalueID) {
								//if ($comments) echo "<br> -- -- -- featurevalues compatible";
							} else {
									
								// Tsekataan, josko resultfeature ylikirjoittaa tämän? Jos ylikirjoittaa, niin
								if ($comments) echo "<br> -- -- -- featurevalues incompatible";
									
								if (isset($this->resultpositionvalues[$featureID])) {
									$resultfeature = $this->resultpositionvalues[$featureID];
									if ($comments) echo "<br> -- -- -- resultpositionvalue resultfeature found, still compatible";
					
									if ($this->resultpositions[$featureID] == "") {
										if ($comments) echo "<br> -- -- -- -- static feature value, from resultpositions";
									} else {
										if ($comments) echo "<br> -- non-static feature value, from resultpositions";
										return false;
									}
					
								} else {
									if ($comments) echo "<br> -- no resultfeature";
									return false;
								}
							}
						}
					}
				}
				
				if ($operator == 2) {
						
					foreach($features as $fsfeatureID => $fsvalueID) {
						//if ($comments) echo "<br> -- -- -- checking featurestructure " . FeatureStructure::$features[$fsfeatureID]->name . " = " .  FeatureStructure::$features[$fsvalueID]->name;
						if ($featureID == $fsfeatureID) {
							if ($valueID == $fsvalueID) {
								// Tsekataan, josko resultfeature ylikirjoittaa tämän? Jos ylikirjoittaa, niin
								if ($comments) echo "<br> -- -- -- featurevalues should not be equal, incompatible";
								return false;
							} else {
								if ($comments) echo "<br> -- -- -- featurevalues not equal, accepted";
							}
						}
					}
				}
				
				if ($operator == 5) {
					if ($comments) echo "<br> -- -- operator " . $operator . " -- Not implemented";
					foreach($features as $fsfeatureID => $fsvalueID) {
						//if ($comments) echo "<br> -- -- -- checking featurestructure " . FeatureStructure::$features[$fsfeatureID]->name . " = " .  FeatureStructure::$features[$fsvalueID]->name;
						if ($featureID == $fsfeatureID) {

							if ($valueID == $fsvalueID) {
								if ($comments) echo "<br> -- -- -- featurevalues compatible";
							} else {
								if ($comments) echo "<br> -- -- -- featurevalues incompatible, lets override x1";
								$fs->addFeature($featureID, $fsvalueID);	
							}
						}
					}
				}
				
			}	
		}
		
		
		if ($othercheck == true) {
			if (isset($this->featureconstraints[$argumentposition])) {
				$argumentfeatureconstraints = $this->featureconstraints[$argumentposition];
				foreach($argumentfeatureconstraints as $featureID => $valueID) {
					if ($comments) echo "<br>Non-mainterm constraint found = " . FeatureStructure::$features[$featureID]->name . " = " .  FeatureStructure::$features[$valueID]->name;
					$operator = $this->featureconstraintoperators[$argumentposition][$featureID];
					if ($comments) echo "<br> -- -- Rule Argumentfeature constraint found = " . FeatureStructure::$features[$featureID]->name . " = " .  FeatureStructure::$features[$valueID]->name;
					if ($comments) echo "<br> -- -- operator = " . $operator . " in position " . $argumentposition;
					

					$features = $argumentfs->getFeatures();
					if (!(($operator == 1) || ($operator == 2) || ($operator == 5))) {
						echo "<br> -- -- operator " . $operator . " -- Not implemented";
						exit;
					}
					
					if ($operator == 1) {
						foreach($features as $fsfeatureID => $fsvalueID) {
							if ($comments) echo "<br>- featurevalue found = " . FeatureStructure::$features[$fsfeatureID]->name . " = " .  FeatureStructure::$features[$fsvalueID]->name;
								
							if ($fsfeatureID == $featureID) {
								if ($comments) echo "<br>- featurevalue found in fs = " . FeatureStructure::$features[$fsfeatureID]->name . " = " .  FeatureStructure::$features[$featureID]->name;
								if ($valueID == $fsvalueID) {
									if ($comments) echo "<br>- - - values match - " . FeatureStructure::$features[$valueID]->name . " vs. " .  FeatureStructure::$features[$fsvalueID]->name;
								} else {
									if ($comments) echo "<br>- - - values doesn't match - " . FeatureStructure::$features[$valueID]->name . " vs. " .  FeatureStructure::$features[$fsvalueID]->name;
									return false;
								}
							}
						}	
					}
					
					

					if ($operator == 2) {
					
						foreach($features as $fsfeatureID => $fsvalueID) {
							//if ($comments) echo "<br> -- -- -- checking featurestructure " . FeatureStructure::$features[$fsfeatureID]->name . " = " .  FeatureStructure::$features[$fsvalueID]->name;
							if ($featureID == $fsfeatureID) {
								if ($valueID == $fsvalueID) {
									// Tsekataan, josko resultfeature ylikirjoittaa tämän? Jos ylikirjoittaa, niin
									if ($comments) echo "<br> -- -- -- featurevalues should not be equal, incompatible";
									return false;
								} else {
									if ($comments) echo "<br> -- -- -- featurevalues not equal, accepted";
								}
							}
						}
					}
					
					if ($operator == 5) {
						foreach($features as $fsfeatureID => $fsvalueID) {
							if ($comments) echo "<br> -- -- -- checking featurestructure " . FeatureStructure::$features[$fsfeatureID]->name . " = " .  FeatureStructure::$features[$fsvalueID]->name;
							if ($featureID == $fsfeatureID) {
						
								if ($valueID == $fsvalueID) {
									if ($comments) echo "<br> -- -- -- featurevalues compatible";
								} else {
									if ($comments) echo "<br> -- -- -- featurevalues incompatible, lets override";
									$argumentfs->addFeature($featureID, $fsvalueID);
								}
							}
						}
					}
				}
			}
		} else {
			if ($comments) echo "<br> -- -- no othercheck necessary..";
		}
		
		
		// Agreementtien tarkistus...
		$maintermagreements = $this->featureagreements[$maintermposition];
		foreach($maintermagreements as $agreementfeatureID => $variableIndex) {
			$agreementfeature = FeatureStructure::$features[$agreementfeatureID];
			
			if ($comments) echo "<br> -- -- maintermagreement: " . $agreementfeature->name . " => " . $agreementfeatureID;
			$mainfeatures = $fs->getFeatures();
			//$agreementfeaturefound = false;
			$agreementunmatch = false;
			foreach($mainfeatures as $mainFeatureID => $mainFeatureValueID) {
				
				$mainfs = FeatureStructure::$features[$mainFeatureID];
				$mainfsvalue = FeatureStructure::$features[$mainFeatureValueID];
				//if ($comments) echo "<br> -- -- -- mainfeature - " . $mainfs->name . " - " . $mainfsvalue->name . " ... " . $mainFeatureID . " -> " . $mainFeatureValueID;
				
				if ($agreementfeatureID == $mainFeatureID) {
					
					if ($comments) echo "<br> -- -- -- -- mainfeaturefound - " . $mainfs->name . " = " . $mainfsvalue->name;
					if ($argumentfs != null) {
						//echo "<br>";
						//var_dump($argumentfs);
						$argumentfeatures = $argumentfs->getFeatures();
						foreach($argumentfeatures as $argumentfsfeatureID => $argumentfsValueID) {
							$ffargumentfs = FeatureStructure::$features[$argumentfsfeatureID];
							$argumentfsvalue = FeatureStructure::$features[$argumentfsValueID];
							//if ($comments) echo "<br> -- -- -- argumentfs - " . $ffargumentfs->name . " - " . $argumentfsvalue->name . " ... " . $argumentfsfeatureID . " -> " . $argumentfsValueID;
						
							if ($argumentfsfeatureID == $mainFeatureID) {
								if ($comments) echo "<br> -- -- -- -- agrgumentfeaturefound";
								if ($mainFeatureValueID == $argumentfsValueID) {
									if ($comments) echo "<br> -- -- -- -- agrgumentfeaturefound - match";
									//$agreementfeaturefound = true;
								} else {
									if ($comments) echo "<br> -- -- -- -- agrgumentfeature unmatch " . $mainFeatureValueID . " - " . $argumentfsValueID;
									if ($comments) echo "<br> -- -- -- -- --- parentti " . $argumentfsvalue->parentID;
										
									// tsekataan onko mainfeaturen value halutun parentti, tällöin yhteensopivat (ja pitäisi asettaa ko. arvo mainfeatureeen...
									if ($argumentfsvalue->parentID == $mainFeatureValueID) {
										if ($comments) echo "<br> -- -- -- -- --- parent match.. compatible";										
									} else {
										$agreementunmatch = true;
										return false;
									}
									
								}
							} else {
								// Ei ole argumefeature
							}
						}
					} else {
						// argumentfs ei ole olemassa, empty todennäköisesti...
					}
				}
			}
		}
				
		// Tsekataan komponenttien olemassa olo / olemattomuus
		// TODO: tätä on testattu hieman heikosti, kopioitiin vain koodi analysestä / sovellettiin
		if (count($this->componentrequirements) > 0) {
			foreach($this->componentrequirements as $position => $componentlist) {
				if (count($componentlist) > 0) {
					
					if ($position == $maintermposition) {
						foreach($componentlist as $componentID => $operation) {
							if ($comments) echo "<br> -- mainterm component requirement - " . $componentID . " - " . $operation;
							if ($operation == 1) {
								if ($fs->hasComponent($componentID) == false) {
									if ($comments) echo "<br> -- mainterm component not exits, shoud exist";
									return false;
								}
							} else {
								if ($operation == 2) {
									if ($fs->hasComponent($componentID) == true) {
										if ($comments) echo "<br> -- mainterm component exits, should not";
										return false;
									}
								} else {
									echo "<br> -- unknown mainterm component operation - " . $operation;
									exit;
								}
							}
						}
					}
					
					if ($argumentsfound == true) {
						if ($position == $argumentposition) {
							foreach($componentlist as $componentID => $operation) {
								if ($comments) echo "<br> -- argument component requirement - " . $componentID . " - " . $operation;
								if ($operation == 1) {
									if ($argumentfs->hasComponent($componentID) == false) {
										if ($comments) echo "<br> -- argument component not exits, shoud exist";
										return false;
									}
								} else {
									if ($operation == 2) {
										if ($argumentfs->hasComponent($componentID) == true) {
											if ($comments) echo "<br> -- argument component exits, should not";
											return false;
										}
									} else {
										echo "<br> -- unknown argument component operation - " . $operation;
										exit;
									}
								}
							}
						}
					}
					
					
				} else {
					if ($comments) echo "<br> -- no components for position " . $position;
				}
			}
		}
		if ($comments) echo "<br>** All componentrequirements hold";
		
		
		
		
		
		return true;
	}
	
	
	public function getTermPositions() {
		
		$arri = array();
		//if ($this->termwordclasses == null) {
			//echo "<br>******** termwordclasses null";
		//}
		
		//echo "<br>termwordclasses - ";
		foreach($this->termwordclasses as $index => $value) {
			//echo ", " . $index;
			if ($index != 0) $arri[$index] = $index;
		}
		
		/*
		echo "<br>ruleterms - ";
		foreach($this->ruleterms as $index => $value) {
			echo ", " . $index;
		}
		*/
		
		return $arri;					// tämä palauttaa indeksissä nolla pääsanan luokan
	}
	
	
	public function getTermWordClasses() {
		return $this->termwordclasses;					// tämä palauttaa indeksissä nolla pääsanan luokan
	}
	
	
	public function printRule() {
		
		$miniprin = false;
		
		echo "<br>Rule - " . $this->name;
		
		if ($this->analyse == 2) {
			echo "<br>Resultrule...";
			
			$class = FeatureStructure::$wordclasses[$this->wordclassID];
			echo "<br>" . $class->abbreviation . "P [";
				
			foreach($this->resultpositionvalues as $featureID => $valueID) {
				if (!isset(FeatureStructure::$features[$featureID])) echo " feature not found - " . $featureID;
				if (!isset(FeatureStructure::$features[$valueID])) echo " feature not found - " . $valueID;
				$feature = FeatureStructure::$features[$featureID];
				$value = FeatureStructure::$features[$valueID];
				echo " " . $feature->name . " = " . $value->name . ",";
			}
			
		} else {

			$wordclassindexes = array();
			foreach($this->termwordclasses as $index => $wordclassID) {
				//echo "<br> -- " . $index . " - " . $wordclassID;
				$wordclassindexes[$index] = $index;
			}
			sort($wordclassindexes);
			
			$maintermposition = 0;
			foreach($this->ruleterms as $argumentindex => $argumentID) {
				if ($argumentID == 2) $maintermposition = $argumentindex;
			}
			
			$class = FeatureStructure::$wordclasses[$this->termwordclasses[$maintermposition]];
			echo "<br>" . $class->abbreviation . "P [";
			
			foreach($this->resultpositionvalues as $featureID => $valueID) {
				if (!isset(FeatureStructure::$features[$featureID])) echo " feature not found - " . $featureID;
				if (!isset(FeatureStructure::$features[$valueID])) echo " feature not found - " . $valueID;
				$feature = FeatureStructure::$features[$featureID];
				$value = FeatureStructure::$features[$valueID];
				echo " " . $feature->name . " (" . $feature->featureID . ") = " . $value->name . " (" . $value->featureID . "),";
			}
			
			$first = true;
			if (count($this->featureagreements[$maintermposition]) > 0) echo ",";
			foreach($this->featureagreements[$maintermposition] as $featureID => $value) {
				if ($first == true) {
					$first = false;
				} else {
					echo ",";
				}
				$feature = FeatureStructure::$features[$featureID];
				echo " " . $feature->name . " = [" . $value . "]";
			}
			echo " ]";
			
			echo "<br>=";
			
			$firstrulecomponent = true;
			foreach($wordclassindexes as $temp => $pos) {
				//echo "<br>Pos = " . $pos;
				$wordclassID = $this->termwordclasses[$pos];
				if ($firstrulecomponent == true) {
					$firstrulecomponent = false;
				} else {
					echo "<br>+";
				}
				$class = FeatureStructure::$wordclasses[$wordclassID];
				echo "<br>" . $class->abbreviation . "P";
				echo "<br>[";
			
				/*
				 if ($pos == 0) {
			
				 foreach($this->ruleterms as $argumentindex => $argumentID) {
				 $argument = FeatureStructure::$arguments[$argumentID];
				 echo " " . $argument->name . " = Not setted ";
				 }
				 } else {
				 echo "<br> [X" . ($pos+1) . "] " . $class->abbreviation . "P";
				 echo "<br>[";
				 }
				 */
					
				/*
				 foreach($this->featureagreements as $aa => $arraybb) {
				 echo "<br>FeatureAgreements - " . $aa . "";;
				 foreach($arraybb as $bb => $cc) {
				 echo "<br> --- " . $bb . " - " . $cc;
				 }
				 }
				 echo "<br>Pos - " . $pos;
				 */
				if (count($this->featureagreements[$pos]) > 0) echo ",";
				$first = true;
				foreach($this->featureagreements[$pos] as $featureID => $value) {
			
					//echo "<br>Pos - " . $pos . ". FeatureID - " . $featureID . " - " . $this->featureagreements[$pos][$featureID];
			
					if (isset($this->featureconstraints[$pos][$featureID])) {
						$feature = FeatureStructure::$features[$this->featureconstraints[$pos][$featureID]];
						//echo " " . $feature->abbreviation;
					} else {
						if ($first == true) {
							$first = false;
						} else {
							echo ", ";
						}
						$feature = FeatureStructure::$features[$featureID];
						echo " " . $feature->name . " = [" . $value . "]";
					}
				}
					
				foreach($this->featureconstraints[$pos] as $featureID => $valuefeatureID) {
					if ($first == true) {
						$first = false;
					} else {
						echo ", ";
					}
					//echo "<br>reqfeat - " . $featureID . " - " . $valuefeatureID;
					$feature = FeatureStructure::$features[$featureID];
					$valuefeature = FeatureStructure::$features[$valuefeatureID];
					if (isset($this->featureagreements[$pos][$featureID])) {
						$value = $this->featureagreements[$pos][$featureID];
						echo " " . $feature->name . " = [" . $value . "] " . $valuefeature->abbreviation . "";
					} else {
						echo " " . $feature->name . " = " . $valuefeature->abbreviation . "";
					}
				}
				echo " ]";
			}
			
		}
		
		
	}
	
	
	
	public function toJSON($wordclasses, $arguments, $features, $components = null) {
	
		//echo "<br>rule to JSON ...";
		
		//$comments = false;
		foreach($arguments as $index => $argument) {
			//echo "<br>Argument - " . $argument->name . " - " . $argument->argumentID . " - " . $index;
		}
		foreach($wordclasses as $index => $wordclass) {
			//echo "<br>Wordclass - " . $wordclass->name . " - " . $wordclass->wordclassID . " - " . $index;
		}
		
		$indexcounter = 1;
		$str = "{";
		$wordclass = $wordclasses[$this->wordclassID];
		$str = $str . " \"name\": \"" . $this->ruleID . "-" . $this->name . "\", ";
		$str = $str . " \"wordclassID\": \"" . $wordclass->wordclassID . "\", ";
		if ($wordclass->abbreviation == "") {
			$str = $str . " \"wordclass\": \"" . $wordclass->name . "\", ";
		} else {
			$str = $str . " \"wordclass\": \"" . $wordclass->abbreviation . "\", ";
		}
		$str = $str . " \"index\" : \"" . $indexcounter . "\",";
		$str = $str . " \"conceptID\" : \"" . $this->conceptID . "\",";
		$str = $str . " \"conceptName\" : \"" . $this->conceptName . "\",";
		$maintermcounter = $indexcounter;
		$indexcounter++;
		
		
		
		$str = $str . " \"arguments\" : ";
		$str = $str . "[";
		$first = true;
		if ($this->conceptID > 0) {
			foreach($this->conceptarguments as $index => $argumentID) {
				$componentID = $this->conceptcomponents[$index];
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ", ";
				}
				$str = $str . "{";
				$str = $str . " \"argumentID\" : \"" . $argumentID . "\",";
				$argument = $arguments[$argumentID];
				$str = $str . " \"argument\" : \"" . $argument->name . "\",";
				$str = $str . " \"componentID\" : \"" . $componentID . "\",";
				$component = $components[$componentID];
				$str = $str . " \"component\" : \"" . $component->name . "\"";
				$str = $str . "}";
			}
		}
		$str = $str . "],";
		
		$str = $str . " \"resultfeatures\" : ";
		$str = $str . "[";
		$first = true;
		if (count($this->resultfeatures) > 0) {
			foreach($this->resultfeatures as $featureID => $valueID) {
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ", ";
				}
				$str = $str . "{";
				$str = $str . " \"featureID\" : \"" . $featureID . "\",";
				$feature = $features[$featureID];
				$str = $str . " \"feature\" : \"" . $feature->name . "\",";
				$str = $str . " \"valueID\" : \"" . $valueID . "\",";
				$feature = $features[$valueID];
				$str = $str . " \"value\" : \"" . $feature->abbreviation . "\"";
				$str = $str . "}";
			}
		}	
		if (count($this->resultpositions) > 0) {
			foreach($this->resultpositions as $featureID => $position) {
				if ($position === null) {
					if ($first == true) {
						$first = false;
					} else {
						$str = $str . ", ";
					}
					$str = $str . "{";
					$str = $str . " \"featureID\" : \"" . $featureID . "\",";
					$feature = $features[$featureID];
					$str = $str . " \"feature\" : \"" . $feature->name . "\",";
					$valueID = $this->resultpositionvalues[$featureID];
					$str = $str . " \"valueID\" : \"" . $valueID . "\",";
					$feature = $features[$valueID];
					$str = $str . " \"value\" : \"" . $feature->abbreviation . "\"";
					$str = $str . "}";
				}
			}
		}
		$str = $str . "],";
		
		$str = $str . " \"resultpositions\" : ";
		$str = $str . "[";
		if (count($this->resultpositions) > 0) {
			$first = true;
			foreach($this->resultpositions as $featureID => $position) {
				
				if ($position != null) {
					if ($first == true) {
						$first = false;
					} else {
						$str = $str . ", ";
					}
					$str = $str . "{";
					$str = $str . " \"featureID\" : \"" . $featureID . "\",";
					$feature = $features[$featureID];
					$str = $str . " \"feature\" : \"" . $feature->name . "\",";
					$valueID = $this->resultpositionvalues[$featureID];
					if ($valueID > 0) {
						$feature = $features[$valueID];
						$str = $str . " \"sourcefeatureID\" : \"" . $valueID . "\",";
						$str = $str . " \"sourcefeature\" : \"" . $feature->name. "\",";
					} else {
						$str = $str . " \"sourcefeatureID\" : \"0\",";
						$str = $str . " \"sourcefeature\" : \"\",";
					}
					$str = $str . " \"position\" : \"" . $position . "\",";
					$str = $str . " \"index\" : \"" . $indexcounter . "\"";
					$indexcounter++;
					$str = $str . "}";
				}
			}
		}
		$str = $str . "],";
		
		$str = $str . " \"terms\" : ";
		$str = $str . "[";
		if (count($this->ruleterms) > 0) {
			$first = true;
			foreach($this->ruleterms as $position => $argumentID) {
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ", ";
				}
				$wordclassID = $this->termwordclasses[$position];
				$str = $str . "{";
				$str = $str . " \"position\" : \"" . $position . "\",";		// ruleterm->position
				$str = $str . " \"argumentID\" : \"" . $argumentID . "\",";
				$argument = $arguments[$argumentID];
				$str = $str . " \"argument\" : \"" . $argument->name . "\",";
				$wordclass = $wordclasses[$wordclassID];
				$str = $str . " \"wordclassID\" : \"" . $wordclassID . "\",";
				$argumentsallowed = $this->termargumentsallowed[$position];
				$str = $str . " \"argumentallowed\" : \"" . $argumentsallowed . "\",";
				$str = $str . " \"wordclass\": \"" . $wordclass->abbreviation . "\", ";
				if ($argumentID == 0) {
					$str = $str . " \"index\" : \"" . $maintermcounter . "\",";
				} else {
					$str = $str . " \"index\" : \"" . $indexcounter . "\",";
					$indexcounter++;
				}
				$conceptID = $this->ruleconceptIDs[$position];
				$concept = $this->ruleconcepts[$position];
				$str = $str . " \"conceptID\" : \"" . $conceptID . "\",";
				$str = $str . " \"concept\" : \"" . $concept . "\"";
				
				$str = $str . "}";
			}
		}
		$str = $str . "],";
		
		
		$str = $str . " \"agreements\" : ";
		$str = $str . "[";
		if (count($this->allagreements) > 0) {
			$first = true;
			foreach($this->allagreements as $index => $agreementlist) {
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ", ";
				}
				$str = $str . "{";
				$str = $str . " \"featureID\" : \"" . $agreementlist[2] . "\",";
				$feature = $features[$agreementlist[2]];
				$str = $str . " \"feature\" : \"" . $feature->name . "\",";
				$str = $str . " \"position1\" : \"" . $agreementlist[0] . "\",";
				$str = $str . " \"position2\" : \"" . $agreementlist[1] . "\",";
				$str = $str . " \"index\" : \"" . $indexcounter . "\"";
				$indexcounter++;
				$str = $str . "}";
			}
		}
		$str = $str . "],";
		
		$str = $str . " \"components\" : ";
		$str = $str . "[";
		if ($this->componentrequirements != null) {
			$first = true;
			foreach($this->componentrequirements as $position => $componentlist) {

				foreach($componentlist as $componentID => $presence) {
					if ($first == true) {
						$first = false;
					} else {
						$str = $str . ", ";
					}
					$str = $str . "{";
					$str = $str . " \"position\" : \"" . $position . "\",";
					$str = $str . " \"componentID\" : \"" . $componentID . "\",";
					
					if ($components != null) {
						$component = $components[$componentID];
						if (!isset($components[$componentID])) echo "<br> *** not setted " . count($components) . "***";
						$str = $str . " \"component\" : \"" . $component->abbreviation . "\",";
					}
					//$feature = $features[$valueID];
					$str = $str . " \"presenceID\" : \"" . $presence . "\",";
					if ($presence == 1) {
						$str = $str . " \"presence\" : \"Obligatory\"";
					} else {
						if ($presence == 2) $str = $str . " \"presence\" : \"Absent\"";
						else $str = $str . " \"presence\" : \"None\"";
					}
						
					
					//$str = $str . " \"value\" : \"" . $feature->abbreviation . "\"";
					$str = $str . "}";
				}
			}
		}
		$str = $str . "],";
		
		$str = $str . " \"constraints\" : ";
		$str = $str . "[";
		if ($this->featureconstraints != null) {
			$first = true;
			foreach($this->featureconstraints as $position => $constraints) {
				
				foreach($constraints as $featureID => $valueID) {
					if ($first == true) {
						$first = false;
					} else {
						$str = $str . ", ";
					}
					$str = $str . "{";
					$str = $str . " \"position\" : \"" . $position . "\",";
					$str = $str . " \"featureID\" : \"" . $featureID . "\",";
					$operator = $this->featureconstraintoperators[$position][$featureID];
					$str = $str . " \"operator\" : \"" . $operator. "\",";
					$feature = $features[$featureID];
					$str = $str . " \"feature\" : \"" . $feature->name . "\",";
					if ($valueID == 0) {
						$str = $str . " \"valueID\" : \"0\",";
						$str = $str . " \"value\" : \"\"";
					} else {
						$feature = $features[$valueID];
						$str = $str . " \"valueID\" : \"" . $valueID . "\",";
						$str = $str . " \"value\" : \"" . $feature->abbreviation . "\"";
					}
					$str = $str . "}";
				}
			}
		}
		$str = $str . "],";
		
		$str = $str . " \"unsetarguments\" : ";
		$str = $str . "[";
		if ($this->unsetarguments != null) {
			$first = true;
			foreach($this->unsetarguments as $position => $argumentlist) {
			
				foreach($argumentlist as $argumentID => $argumentID) {
					if ($first == true) {
						$first = false;
					} else {
						$str = $str . ", ";
					}
					$str = $str . "{";
					$str = $str . " \"position\" : \"" . $position . "\",";
					$str = $str . " \"argumentID\" : \"" . $argumentID . "\",";
					$argument = $arguments[$argumentID];
					$str = $str . " \"argument\" : \"" . $argument->name . "\"";
					$str = $str . "}";
				}
			}
		}
		$str = $str . "],";
		
		$str = $str . " \"unsetfeatures\" : ";
		$str = $str . "[";
		if ($this->unsetarguments != null) {
			$first = true;
			foreach($this->unsetfeatures as $position => $featurelist) {
			
				foreach($featurelist as $featureID => $featureID) {
					if ($first == true) {
						$first = false;
					} else {
						$str = $str . ", ";
					}
					$str = $str . "{";
					$str = $str . " \"position\" : \"" . $position . "\",";
					$str = $str . " \"featureID\" : \"" . $featureID . "\",";
					$feature = $features[$featureID];
					$str = $str . " \"feature\" : \"" . $feature->name . "\"";
					$str = $str . "}";
				}
			}
		}
		$str = $str . "] ";
		
		$str = $str . "}";
		return $str;
	}
	
	
	

	public function toIntegerJSON($wordclasses, $arguments, $features, $components = null) {

		//$comments = false;
		foreach($arguments as $index => $argument) {
			//echo "<br>Argument - " . $argument->name . " - " . $argument->argumentID . " - " . $index;
		}
		foreach($wordclasses as $index => $wordclass) {
			//echo "<br>Wordclass - " . $wordclass->name . " - " . $wordclass->wordclassID . " - " . $index;
		}
		
		$indexcounter = 1;
		$str = "{";
		$wordclass = $wordclasses[$this->wordclassID];
		$str = $str . " \"name\": \"" . $this->ruleID . "-" . $this->name . "\", ";
		$str = $str . " \"ruleID\": \"" . $this->ruleID . "\", ";
		$str = $str . " \"wordclassID\": \"" . $this->wordclassID . "\", ";
		if ($wordclass->abbreviation == "") {
			$str = $str . " \"wordclass\": \"" . $wordclass->name . "\", ";
		} else {
			$str = $str . " \"wordclass\": \"" . $wordclass->abbreviation . "\", ";
		}
		$str = $str . " \"conceptID\": \"" . $this->conceptID . "\", ";
		$str = $str . " \"analyse\": \"" . $this->analyse . "\", ";
		$str = $str . " \"generate\": \"" . $this->generate . "\", ";
		$str = $str . " \"conceptName\": \"" . $this->conceptName . "\", ";
		$str = $str . " \"index\" : \"" . $indexcounter . "\",";
		$maintermcounter = $indexcounter;
		$indexcounter++;
		
		$str = $str . " \"arguments\" : ";
		$str = $str . "[";
		$first = true;
		if ($this->conceptID > 0) {
			foreach($this->conceptarguments as $index => $argumentID) {
				$componentID = $this->conceptcomponents[$index];
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ", ";
				}
				$str = $str . "{";
				$str = $str . " \"argumentID\" : \"" . $argumentID . "\",";
				$argument = $arguments[$argumentID];
				$str = $str . " \"argument\" : \"" . $argument->name . "\",";
				$str = $str . " \"componentID\" : \"" . $componentID . "\",";
				$component = $components[$componentID];
				$str = $str . " \"component\" : \"" . $component->name . "\"";
				$str = $str . "}";
			}
		}
		$str = $str . "],";
		
		$str = $str . " \"resultfeatures\" : ";
		$str = $str . "[";
		$first = true;
		if (count($this->resultfeatures) > 0) {
			foreach($this->resultfeatures as $featureID => $valueID) {
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ", ";
				}
				$str = $str . "{";
				$str = $str . " \"featureID\" : \"" . $featureID . "\",";
				$str = $str . " \"valueID\" : \"" . $valueID . "\"";
				$str = $str . "}";
			}
		}
		if (count($this->resultpositions) > 0) {
			foreach($this->resultpositions as $featureID => $position) {
				if ($position === null) {
					if ($first == true) {
						$first = false;
					} else {
						$str = $str . ", ";
					}
					$str = $str . "{";
					$str = $str . " \"featureID\" : \"" . $featureID . "\",";
					$valueID = $this->resultpositionvalues[$featureID];
					$str = $str . " \"valueID\" : \"" . $valueID . "\"";
					$str = $str . "}";
				}
			}
		}
		$str = $str . "],";
		
		$str = $str . " \"resultpositions\" : ";
		$str = $str . "[";
		if (count($this->resultpositions) > 0) {
			$first = true;
			foreach($this->resultpositions as $featureID => $position) {
		
				if ($position != null) {
					if ($first == true) {
						$first = false;
					} else {
						$str = $str . ", ";
					}
					$str = $str . "{";
					$str = $str . " \"featureID\" : \"" . $featureID . "\",";
					$valueID = $this->resultpositionvalues[$featureID];
					if ($valueID > 0) {
						$str = $str . " \"sourcefeatureID\" : \"" . $valueID . "\",";
					} else {
						$str = $str . " \"sourcefeatureID\" : \"0\",";
					}
					$str = $str . " \"position\" : \"" . $position . "\",";
					$str = $str . " \"index\" : \"" . $indexcounter . "\"";
					$indexcounter++;
					$str = $str . "}";
				}
			}
		}
		$str = $str . "],";
		
		$str = $str . " \"terms\" : ";
		$str = $str . "[";
		if (count($this->ruleterms) > 0) {
			$first = true;
			foreach($this->ruleterms as $position => $argumentID) {
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ", ";
				}
				$wordclassID = $this->termwordclasses[$position];
				$str = $str . "{";
				$str = $str . " \"position\" : \"" . $position . "\",";		// ruleterm->position
				$str = $str . " \"argumentID\" : \"" . $argumentID . "\",";
				$wordclass = $wordclasses[$wordclassID];
				$str = $str . " \"wordclassID\" : \"" . $wordclassID . "\",";
				$argumentsallowed = $this->termargumentsallowed[$position];
				$str = $str . " \"argumentallowed\" : \"" . $argumentsallowed . "\",";
				if ($argumentID == 0) {
					$str = $str . " \"index\" : \"" . $maintermcounter . "\",";
				} else {
					$str = $str . " \"index\" : \"" . $indexcounter . "\",";
					$indexcounter++;
				}
				$conceptID = $this->ruleconceptIDs[$position];
				$concept = $this->ruleconcepts[$position];
				$str = $str . " \"conceptID\" : \"" . $conceptID . "\",";
				$str = $str . " \"concept\" : \"" . $concept . "\"";
		
				$str = $str . "}";
			}
		}
		$str = $str . "],";
		
		
		$str = $str . " \"agreements\" : ";
		$str = $str . "[";
		if (count($this->allagreements) > 0) {
			$first = true;
			foreach($this->allagreements as $index => $agreementlist) {
				if ($first == true) {
					$first = false;
				} else {
					$str = $str . ", ";
				}
				$str = $str . "{";
				$str = $str . " \"featureID\" : \"" . $agreementlist[2] . "\",";
				$str = $str . " \"position1\" : \"" . $agreementlist[0] . "\",";
				$str = $str . " \"position2\" : \"" . $agreementlist[1] . "\",";
				$str = $str . " \"index\" : \"" . $indexcounter . "\"";
				$indexcounter++;
				$str = $str . "}";
			}
		}
		$str = $str . "],";
		
		$str = $str . " \"components\" : ";
		$str = $str . "[";
		if ($this->componentrequirements != null) {
			$first = true;
			foreach($this->componentrequirements as $position => $componentlist) {
		
				foreach($componentlist as $componentID => $presence) {
					if ($first == true) {
						$first = false;
					} else {
						$str = $str . ", ";
					}
					$str = $str . "{";
					$str = $str . " \"position\" : \"" . $position . "\",";
					$str = $str . " \"componentID\" : \"" . $componentID . "\",";
					$str = $str . " \"presenceID\" : \"" . $presence . "\"";
					$str = $str . "}";
				}
			}
		}
		$str = $str . "],";
		
		$str = $str . " \"constraints\" : ";
		$str = $str . "[";
		if ($this->featureconstraints != null) {
			$first = true;
			foreach($this->featureconstraints as $position => $constraints) {
		
				foreach($constraints as $featureID => $valueID) {
					if ($first == true) {
						$first = false;
					} else {
						$str = $str . ", ";
					}
					$str = $str . "{";
					$str = $str . " \"position\" : \"" . $position . "\",";
					$str = $str . " \"featureID\" : \"" . $featureID . "\",";
					$operator = $this->featureconstraintoperators[$position][$featureID];
					$str = $str . " \"operator\" : \"" . $operator. "\",";
					if ($valueID == 0) {
						$str = $str . " \"valueID\" : \"0\"";
					} else {
						$str = $str . " \"valueID\" : \"" . $valueID . "\"";
					}
					$str = $str . "}";
				}
			}
		}
		$str = $str . "],";
		
		$str = $str . " \"unsetarguments\" : ";
		$str = $str . "[";
		if ($this->unsetarguments != null) {
			$first = true;
			foreach($this->unsetarguments as $position => $argumentlist) {
					
				foreach($argumentlist as $argumentID => $argumentID) {
					if ($first == true) {
						$first = false;
					} else {
						$str = $str . ", ";
					}
					$str = $str . "{";
					$str = $str . " \"position\" : \"" . $position . "\",";
					$str = $str . " \"argumentID\" : \"" . $argumentID . "\"";
					$str = $str . "}";
				}
			}
		}
		$str = $str . "],";
		
		$str = $str . " \"unsetfeatures\" : ";
		$str = $str . "[";
		if ($this->unsetarguments != null) {
			$first = true;
			foreach($this->unsetfeatures as $position => $featurelist) {
					
				foreach($featurelist as $featureID => $featureID) {
					if ($first == true) {
						$first = false;
					} else {
						$str = $str . ", ";
					}
					$str = $str . "{";
					$str = $str . " \"position\" : \"" . $position . "\",";
					$str = $str . " \"featureID\" : \"" . $featureID . "\"";
					$str = $str . "}";
				}
			}
		}
		$str = $str . "] ";
		
		
		$str = $str . "}";
		return $str;
	}
	
}



?>