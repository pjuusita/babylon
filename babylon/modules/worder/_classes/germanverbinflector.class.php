<?php



include_once('./modules/worder/_classes/wordform.class.php');
//include_once('./modules/worder/_classes/GermanVerbInflector.class.php');



class GermanVerbInflector {
    
    
	const PERSON = 1045;
	const FIRSTPERSON = 1049;
	const SECONDPERSON = 1050;
	const THIRDPERSON = 1051;
	
	const NUMBER = 1046;
	const SINGULAR = 1052;
	const PLURAL = 1053;
	
	const TEMPUS = 1047;
	const PRESENT = 1054;
	const PRETERITE = 1055;
	const PERFECT = 1056;
	
	const MODUS = 1092;
	const INDICATIVE = 1093;
	const INFINITIVE = 1094;
	const PARTICLE = 1095;
	const IMPERATIVE = 1100;
	const SUBJUNCTIVE = 1316;
	
    
    
  
    
    
    
    public static function getWordForms($wordID, $lemma, $formstr, $inflectionclassstr) {
    
    	$allforms = array();
    	$inflectionclassarray = explode('-',$inflectionclassstr);
    	$inflectionclass = $inflectionclassarray[0];
    	$formID = -2;
    	
    	// perusmuoto, infinitive
    	
    	$identifiers = array(GermanVerbInflector::INFINITIVE);
    	$wordform = GermanVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	
    	// particles
    	
    	$identifiers = array(GermanVerbInflector::PARTICLE, GermanVerbInflector::PRESENT);
    	$wordform = GermanVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(GermanVerbInflector::PARTICLE, GermanVerbInflector::PRETERITE);
    	$wordform = GermanVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);

    	// indicatives
    	
    	$identifiers = array(GermanVerbInflector::INDICATIVE, GermanVerbInflector::PRESENT, GermanVerbInflector::SINGULAR, GermanVerbInflector::FIRSTPERSON);
    	$wordform = GermanVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(GermanVerbInflector::INDICATIVE, GermanVerbInflector::PRESENT, GermanVerbInflector::SINGULAR, GermanVerbInflector::SECONDPERSON);
    	$wordform = GermanVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(GermanVerbInflector::INDICATIVE, GermanVerbInflector::PRESENT, GermanVerbInflector::SINGULAR, GermanVerbInflector::THIRDPERSON);
    	$wordform = GermanVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(GermanVerbInflector::INDICATIVE, GermanVerbInflector::PRESENT, GermanVerbInflector::PLURAL, GermanVerbInflector::FIRSTPERSON);
    	$wordform = GermanVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(GermanVerbInflector::INDICATIVE, GermanVerbInflector::PRESENT, GermanVerbInflector::PLURAL, GermanVerbInflector::SECONDPERSON);
    	$wordform = GermanVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(GermanVerbInflector::INDICATIVE, GermanVerbInflector::PRESENT, GermanVerbInflector::PLURAL, GermanVerbInflector::THIRDPERSON);
    	$wordform = GermanVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);

    	
    	// Indicative preterite
   	 
    	$identifiers = array(GermanVerbInflector::INDICATIVE, GermanVerbInflector::PRETERITE, GermanVerbInflector::SINGULAR, GermanVerbInflector::FIRSTPERSON);
    	$wordform = GermanVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(GermanVerbInflector::INDICATIVE, GermanVerbInflector::PRETERITE, GermanVerbInflector::SINGULAR, GermanVerbInflector::SECONDPERSON);
    	$wordform = GermanVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(GermanVerbInflector::INDICATIVE, GermanVerbInflector::PRETERITE, GermanVerbInflector::SINGULAR, GermanVerbInflector::THIRDPERSON);
    	$wordform = GermanVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(GermanVerbInflector::INDICATIVE, GermanVerbInflector::PRETERITE, GermanVerbInflector::PLURAL, GermanVerbInflector::FIRSTPERSON);
    	$wordform = GermanVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(GermanVerbInflector::INDICATIVE, GermanVerbInflector::PRETERITE, GermanVerbInflector::PLURAL, GermanVerbInflector::SECONDPERSON);
    	$wordform = GermanVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(GermanVerbInflector::INDICATIVE, GermanVerbInflector::PRETERITE, GermanVerbInflector::PLURAL, GermanVerbInflector::THIRDPERSON);
    	$wordform = GermanVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	// imperative
    	
    	$identifiers = array(GermanVerbInflector::IMPERATIVE, GermanVerbInflector::PRESENT, GermanVerbInflector::SINGULAR);
    	$wordform = GermanVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(GermanVerbInflector::IMPERATIVE, GermanVerbInflector::PRESENT, GermanVerbInflector::PLURAL);
    	$wordform = GermanVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);

    	// subjunctives not implemented yet
    	
    	return $allforms;
    }
    
    
    


    public static function getFormWithArrayForForms($forms, $wordforms, $inflectionclass, &$formID) {
    
    	$person = GermanVerbInflector::PERSON;
    	$number = GermanVerbInflector::NUMBER;
    	$tense = GermanVerbInflector::TEMPUS;
    	$modus = GermanVerbInflector::MODUS;
    	 
    	 
    	foreach ($wordforms as $index => $value) {
    		switch ($value) {
    			case GermanVerbInflector::FIRSTPERSON: $person = GermanVerbInflector::FIRSTPERSON; break;
    			case GermanVerbInflector::SECONDPERSON: $person = GermanVerbInflector::SECONDPERSON; break;
    			case GermanVerbInflector::THIRDPERSON: $person = GermanVerbInflector::THIRDPERSON; break;
    
    			case GermanVerbInflector::SINGULAR: $number = GermanVerbInflector::SINGULAR; break;
    			case GermanVerbInflector::PLURAL: $number = GermanVerbInflector::PLURAL; break;
    
    			case GermanVerbInflector::PRESENT: $tense = GermanVerbInflector::PRESENT; break;
    			case GermanVerbInflector::PRETERITE: $tense = GermanVerbInflector::PRETERITE; break;
    			case GermanVerbInflector::PERFECT: $tense = GermanVerbInflector::PERFECT; break;
    			 
    			case GermanVerbInflector::INDICATIVE: $modus = GermanVerbInflector::INDICATIVE; break;
    			case GermanVerbInflector::INFINITIVE: $modus = GermanVerbInflector::INFINITIVE; break;
    			case GermanVerbInflector::PARTICLE: $modus = GermanVerbInflector::PARTICLE; break;
    			case GermanVerbInflector::IMPERATIVE: $modus = GermanVerbInflector::IMPERATIVE; break;
    			case GermanVerbInflector::SUBJUNCTIVE: $modus = GermanVerbInflector::SUBJUNCTIVE; break;
    		}
    	}
    
    	$wforms = explode('/',$forms);
    	if (count($wforms) == 1) {
    		$wforms[0] = $wforms[0];
    		$wforms[1] = $wforms[0];
    		$wforms[2] = $wforms[0];
    		$wforms[3] = $wforms[0];
    		$wforms[4] = $wforms[0];
    		$wforms[5] = $wforms[0];
    		$wforms[6] = $wforms[0];
    		$wforms[7] = $wforms[0];
    	}
    	$form = GermanVerbInflector::getBaseFormsPrivate($person, $number, $tense, $modus, $wforms, $inflectionclass, $formID);
    	return $form;
    }
    
    
    
    
    private static function getBaseFormsPrivate($person, $number, $tense, $modus, $form, $inflectionclass, &$formID) {
    
    	$inf = intval($inflectionclass);
    	$formID = 0;
    	$str = "";
    	
    	if ($modus == GermanVerbInflector::INFINITIVE) {
    		$formID = 0;
    		switch ($inf) {
    			case ($inf == 1):
    				$str = $form[0] . "n";
    				break;
    			case ($inf == 2):
    				$str = $form[0] . "n";
    				break;
    			default:
    				$str = $form[0] . "xxxx";
    		}
    	}


    	if ($modus == GermanVerbInflector::PARTICLE) {
    		if ($tense == GermanVerbInflector::PRESENT) {
    			$str = $form[0] . "nd";
    		} else if ($tense == GermanVerbInflector::PRETERITE) {
    			$str = "ge" . $form[0] . "t";
    		} else {
    			$str = $form[0] . "xxxx";
    		}
    	} 
    	

    	if ($modus == GermanVerbInflector::INDICATIVE) {
    		if ($tense == GermanVerbInflector::PRESENT) {
    		
    			if ($number == GermanVerbInflector::SINGULAR) {
    				if ($person == GermanVerbInflector::FIRSTPERSON) {
    					$str = $form[0];
    				} elseif ($person == GermanVerbInflector::SECONDPERSON) {
    					$str = $form[0] . "st";
    				} elseif ($person == GermanVerbInflector::THIRDPERSON) {
    					$str = $form[0] . "t";
    				} else {
    					$str = $form[0] . "xxxx-223";
    				}
    			} elseif ($number == GermanVerbInflector::PLURAL) {
    				if ($person == GermanVerbInflector::FIRSTPERSON) {
    					$str = $form[0] . "n";
    				} elseif ($person == GermanVerbInflector::SECONDPERSON) {
    					$str = $form[0] . "t";
    				} elseif ($person == GermanVerbInflector::THIRDPERSON) {
    					$str = $form[0] . "n";
    				} else {
    					$str = $form[0] . "xxxx-233";
    				}
    			} else {
    				$str = $form[0] . "xxxx-236";
    			}
    			
    			
    		} else if ($tense == GermanVerbInflector::PRETERITE) {
    		

    			if ($number == GermanVerbInflector::SINGULAR) {
    				if ($person == GermanVerbInflector::FIRSTPERSON) {
    					$str = $form[0] . "te";
    				} elseif ($person == GermanVerbInflector::SECONDPERSON) {
    					$str = $form[0] . "test";
    				} elseif ($person == GermanVerbInflector::THIRDPERSON) {
    					$str = $form[0] . "te";
    				} else {
    					$str = $form[0] . "xxxx-223";
    				}
    			} elseif ($number == GermanVerbInflector::PLURAL) {
    				if ($person == GermanVerbInflector::FIRSTPERSON) {
    					$str = $form[0] . "ten";
    				} elseif ($person == GermanVerbInflector::SECONDPERSON) {
    					$str = $form[0] . "tet";
    				} elseif ($person == GermanVerbInflector::THIRDPERSON) {
    					$str = $form[0] . "ten";
    				} else {
    					$str = $form[0] . "xxxx-233";
    				}
    			} else {
    				$str = $form[0] . "xxxx-236";
    			}
    			 
    			
    		} else {
    			$str = $form[0] . "xxxx";
    		}
    	}
    	 
    	

    	

    	if ($modus == GermanVerbInflector::IMPERATIVE) {
    		if ($tense == GermanVerbInflector::PRESENT) {
    			if ($number == GermanVerbInflector::SINGULAR) {
    				$str = $form[0] . "";
    			} elseif ($number == GermanVerbInflector::PLURAL) {
    				$str = $form[0] . "t";
    			} else {
    				$str = $form[0] . "xxxx-286";
    			}
    		} else {
    			$str = $form[0] . "xxxx-289";
    		}
    	}
    	 
    	
    	return $str;
    	
    }
    
    
}

    




?>