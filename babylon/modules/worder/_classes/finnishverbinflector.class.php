<?php



include_once('./modules/worder/_classes/wordform.class.php');
include_once('./modules/worder/_classes/category.class.php');



class FinnishVerbInflector {
    
    
    
    
    private static function hasBackVowels($str) {
    	for ($i = 0; $i < strlen($str); $i++){
    		if (($str[$i] == 'y') || ($str[$i] == 'ä') || ($str[$i] == 'ä'));
    	}
    	return 0;
    }
    
    
    
    
    public static function getFormWithArray($forms, $wordforms) {
    	
    	$person = FIRSTPERSON;
    	$number = SINGULAR;
    	$tense = PRESENT;
    	$modus = INDIKATIVE;
    	$polarity = AFFIRMATIVE;
    	
    	foreach ($forms as $index => $value) {
    		switch ($index) {
    			case FIRSTPERSON: $person = FIRSTPERSON; break;
    			case SECONDPERSON: $person = FIRSTPERSON; break;
    			case THIRDPERSON: $person = FIRSTPERSON; break;
    			
    			case SINGULAR: $number = SINGULAR; break;
    			case PLURAL: $number = PLURAL; break;
    			 
    			case PRESENT: $tense = PRESENT; break;
    			case PAST: $tense = PAST; break;
    			case FUTURE: $tense = FUTURE; break;
    			 
    			case INDIKATIVE: $modus = INDIKATIVE; break;
    			case IMPERATIVE: $modus = IMPERATIVE; break;
    			case CONDITIONAL: $modus = CONDITIONAL; break;
    			case POTENTIAL: $modus = POTENTIAL; break;
    			
    			case AFFIRMATIVE: $polarity = AFFIRMATIVE; break;
    			case NEGATION: $polarity = NEGATION; break;   			 
    		}
    	}
    	
    	$wforms = explode('/',$wordforms);
    	$form = FinnishVerbInflector::getFormPrivate($person, $number, $tense, $modus, $polarity, $wforms);
    	return $form;
    }
    
    

    public static function getFormWithArrayForForms($forms, $wordforms, $inflectionclass, &$formID) {
    
    	$person = Category::PERSON;
    	$number = Category::VNUMBER;
    	$tense = Category::TEMPUS;
    	$modus = Category::MODUS;
    	$polarity = Category::POLARITY;
    	$voice = Category::VOICE;
    	$infinitive = Category::INFINITIVE;
    	 
    	foreach ($wordforms as $index => $value) {
    		switch ($value) {
    			case Category::FIRSTPERSON: $person = Category::FIRSTPERSON; break;
    			case Category::SECONDPERSON: $person = Category::SECONDPERSON; break;
    			case Category::THIRDPERSON: $person = Category::THIRDPERSON; break;
    			 
    			case Category::VSINGULAR: $number = Category::VSINGULAR; break;
    			case Category::VPLURAL: $number = Category::VPLURAL; break;
    
    			case Category::PRESENT: $tense = Category::PRESENT; break;
    			case Category::PAST: $tense = Category::PAST; break;
    			case Category::FUTURE: $tense = Category::FUTURE; break;
    			case Category::PERFECT: $tense = Category::PERFECT; break;
    			
    			case Category::INDIKATIVE: $modus = Category::INDIKATIVE; break;
    			case Category::IMPERATIVE: $modus = Category::IMPERATIVE; break;
    			case Category::CONDITIONAL: $modus = Category::CONDITIONAL; break;
    			case Category::POTENTIAL: $modus = Category::POTENTIAL; break;
    			 
    			case Category::AFFIRMATIVE: $polarity = Category::AFFIRMATIVE; break;
    			case Category::NEGATION: $polarity = Category::NEGATION; break;
    			
    			case Category::ACTIVE: $voice = Category::ACTIVE; break;
    			case Category::PASSIVE: $voice = Category::PASSIVE; break;
    			
    			case Category::A_INFINITIVE: $infinitive = Category::A_INFINITIVE; break;
    			 
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
    	$form = FinnishVerbInflector::getBaseFormsPrivate($person, $number, $tense, $modus, $polarity, $voice, $infinitive, $wforms, $inflectionclass, $formID);
    	return $form;
    }
    
    
    
    
    
    public static function getWordForms($wordID, $lemma, $formstr, $inflectionclassstr) {
    
    	$allforms = array();
    	$inflectionclassarray = explode('-',$inflectionclassstr);
    	$inflectionclass = $inflectionclassarray[0];
    	$formID = -2;
    	
    	// perusmuoto, A-infinitiivi
    	
    	$identifiers = array(Category::A_INFINITIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	
    	// indicative present
    	
    	$identifiers = array(Category::INDIKATIVE, Category::PRESENT, Category::VSINGULAR, Category::FIRSTPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::INDIKATIVE, Category::PRESENT, Category::VSINGULAR, Category::SECONDPERSON, Category::ACTIVE,Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::INDIKATIVE, Category::PRESENT, Category::VSINGULAR, Category::THIRDPERSON, Category::ACTIVE,Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::INDIKATIVE, Category::PRESENT, Category::VPLURAL, Category::FIRSTPERSON, Category::ACTIVE,Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::INDIKATIVE, Category::PRESENT, Category::VPLURAL, Category::SECONDPERSON, Category::ACTIVE,Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::INDIKATIVE, Category::PRESENT, Category::VPLURAL, Category::THIRDPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::INDIKATIVE, Category::PRESENT, Category::VNUMBER, Category::PERSON, Category::PASSIVE,Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	
    	
    	// Negation indicative present
    	
    	//$identifiers = array(Category::INDIKATIVE, Category::PRESENT, Category::VSINGULAR, Category::PERSON, Category::ACTIVE, Category::NEGATION);
    	//$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	//$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	//$identifiers = array(Category::INDIKATIVE, Category::PRESENT, Category::VPLURAL, Category::PERSON, Category::ACTIVE, Category::NEGATION);
    	//$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	//$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::INDIKATIVE, Category::PRESENT, Category::VNUMBER, Category::PERSON, Category::ACTIVE, Category::NEGATION);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::INDIKATIVE, Category::PRESENT, Category::VNUMBER, Category::PERSON, Category::PASSIVE, Category::NEGATION);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	 
    	// Indicative past
    	
    	$identifiers = array(Category::INDIKATIVE, Category::PAST, Category::VSINGULAR, Category::FIRSTPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::INDIKATIVE, Category::PAST, Category::VSINGULAR, Category::SECONDPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::INDIKATIVE, Category::PAST, Category::VSINGULAR, Category::THIRDPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::INDIKATIVE, Category::PAST, Category::VPLURAL, Category::FIRSTPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::INDIKATIVE, Category::PAST, Category::VPLURAL, Category::SECONDPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::INDIKATIVE, Category::PAST, Category::VPLURAL, Category::THIRDPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::INDIKATIVE, Category::PAST, Category::VNUMBER, Category::PERSON, Category::PASSIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	
    	$identifiers = array(Category::INDIKATIVE, Category::PAST, Category::VSINGULAR, Category::PERSON, Category::ACTIVE, Category::NEGATION);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::INDIKATIVE, Category::PAST, Category::VPLURAL, Category::PERSON, Category::ACTIVE, Category::NEGATION);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::INDIKATIVE, Category::PAST, Category::VNUMBER, Category::PERSON, Category::PASSIVE, Category::NEGATION);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	
    	// conditional
    	
    	$identifiers = array(Category::CONDITIONAL, Category::PRESENT, Category::VSINGULAR, Category::FIRSTPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::CONDITIONAL, Category::PRESENT, Category::VSINGULAR, Category::SECONDPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::CONDITIONAL, Category::PRESENT, Category::VSINGULAR, Category::THIRDPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::CONDITIONAL, Category::PRESENT, Category::VPLURAL, Category::FIRSTPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::CONDITIONAL, Category::PRESENT, Category::VPLURAL, Category::SECONDPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::CONDITIONAL, Category::PRESENT, Category::VPLURAL, Category::THIRDPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::CONDITIONAL, Category::PRESENT, Category::VNUMBER, Category::PERSON, Category::PASSIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	
    	//$identifiers = array(Category::CONDITIONAL, Category::PRESENT, Category::VSINGULAR, Category::PERSON, Category::ACTIVE, Category::NEGATION);
    	//$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	//$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	//$identifiers = array(Category::CONDITIONAL, Category::PRESENT, Category::VPLURAL, Category::PERSON, Category::ACTIVE, Category::NEGATION);
    	//$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	//$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::CONDITIONAL, Category::PRESENT, Category::VNUMBER, Category::PERSON, Category::ACTIVE, Category::NEGATION);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::CONDITIONAL, Category::PRESENT, Category::VNUMBER, Category::PERSON, Category::PASSIVE, Category::NEGATION);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	
    	// imperative
    	
    	$identifiers = array(Category::IMPERATIVE, Category::PRESENT, Category::VSINGULAR, Category::SECONDPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::IMPERATIVE, Category::PRESENT, Category::VSINGULAR, Category::THIRDPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::IMPERATIVE, Category::PRESENT, Category::VPLURAL, Category::FIRSTPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::IMPERATIVE, Category::PRESENT, Category::VPLURAL, Category::SECONDPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::IMPERATIVE, Category::PRESENT, Category::VPLURAL, Category::THIRDPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::IMPERATIVE, Category::PRESENT, Category::VNUMBER, Category::PERSON, Category::PASSIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	
    	$identifiers = array(Category::IMPERATIVE, Category::PRESENT, Category::VSINGULAR, Category::SECONDPERSON, Category::ACTIVE, Category::NEGATION);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::IMPERATIVE, Category::PRESENT, Category::VSINGULAR, Category::THIRDPERSON, Category::ACTIVE, Category::NEGATION);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::IMPERATIVE, Category::PRESENT, Category::VPLURAL, Category::PERSON, Category::ACTIVE, Category::NEGATION);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::IMPERATIVE, Category::PRESENT, Category::VNUMBER, Category::PERSON, Category::PASSIVE, Category::NEGATION);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	
    	// potential
    	
    	$identifiers = array(Category::POTENTIAL, Category::PRESENT, Category::VSINGULAR, Category::FIRSTPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::POTENTIAL, Category::PRESENT, Category::VSINGULAR, Category::SECONDPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::POTENTIAL, Category::PRESENT, Category::VSINGULAR, Category::THIRDPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::POTENTIAL, Category::PRESENT, Category::VPLURAL, Category::FIRSTPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::POTENTIAL, Category::PRESENT, Category::VPLURAL, Category::SECONDPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::POTENTIAL, Category::PRESENT, Category::VPLURAL, Category::THIRDPERSON, Category::ACTIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::POTENTIAL, Category::PRESENT, Category::VNUMBER, Category::PERSON, Category::PASSIVE, Category::AFFIRMATIVE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	 
    	// potential
    	 
    	
    	//$identifiers = array(Category::POTENTIAL, Category::PRESENT, Category::VSINGULAR, Category::PERSON, Category::ACTIVE, Category::NEGATION);
    	//$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	//$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	//$identifiers = array(Category::POTENTIAL, Category::PRESENT, Category::VPLURAL, Category::PERSON, Category::ACTIVE, Category::NEGATION);
    	//$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	//$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::POTENTIAL, Category::PRESENT, Category::VNUMBER, Category::PERSON, Category::ACTIVE, Category::NEGATION);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 

    	$identifiers = array(Category::POTENTIAL, Category::PRESENT, Category::VNUMBER, Category::PERSON, Category::PASSIVE, Category::NEGATION);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	
    	// perfect
    	
    	$identifiers = array(Category::PERFECT, Category::VSINGULAR, Category::PERSON, Category::ACTIVE, Category::VOICE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::PERFECT, Category::VPLURAL, Category::PERSON, Category::ACTIVE, Category::VOICE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	
    	$identifiers = array(Category::PERFECT, Category::VNUMBER, Category::PERSON, Category::PASSIVE, Category::VOICE);
    	$wordform = FinnishVerbInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	return $allforms;
    }
    
    
    private static function getBaseFormsPrivate($person, $number, $tense, $modus, $polarity, $voice, $infinitive, $form, $inflectionclass, &$formID) {
    
    	//echo "<br>" . $person . ", " . $number . ", " . $tense . ", " . $modus . ", " . $polarity . ", " . $voice . ", " . $infinitive;
    	if ($infinitive == Category::A_INFINITIVE) {
    		$i = intval($inflectionclass);
    		$formID = 0;
    		switch ($i) {
    			case ($i == 62 || $i == 63 || $i == 64 || $i == 65 || $i == 68 || $i == 71):
    				$str = $form[0] . "d_A_";
    				break;
    			case ($i == 67):
    				$str = $form[0] . "l_A_";
    				break;
    			case ($i == 66 || $i == 70):
    				$str = $form[0] . "t_A_";
    				break;
    			default:
    				$str = $form[0] . "_A_";
    		}
    	}
    		 
    	if ($tense == Category::PERFECT) {
    		 
    		if ($voice == Category::ACTIVE) {
    			if ($number == Category::VSINGULAR) {
    				$formID = 7;
    				$str = $form[7] . "_U_t";
   				} elseif ($number == Category::VPLURAL) {
   					$formID = 7;
   					$str = $form[7] . "eet";
    			} else {
    				$str = $form[0] . "unknown - 380";
    			}
    		} else if ($voice == Category::PASSIVE) {
    			
    			$i = intval($inflectionclass);
    			$formID = 4;
    			switch ($i) {
    				case ($i == 62 || $i == 63 || $i == 64 || $i == 65 || $i == 66 || $i == 67 || $i == 68 || $i == 70 ||  $i == 71):
    					$str = $form[4] . "t_U_";
    					break;
    				default:
    					$str = $form[4] . "tt_U_";
    			}
    			
    			//$formID = 4;
    			//$str = $form[4] . "tt_U_";
    		} else {
    			$formID = -1;
    			$str = $form[0] . "unknown - 147";
    		}
    	}
    	
    	if ($polarity == Category::AFFIRMATIVE) {
    		if ($modus == Category::INDIKATIVE) {
    			if ($tense == Category::PRESENT) {
    				if ($voice == Category::ACTIVE) {
    					if ($number == Category::VSINGULAR) {
    						if ($person == Category::FIRSTPERSON) {
    							$formID = 1;
    							$str = $form[1] . "n";
    						} elseif ($person == Category::SECONDPERSON) {
    							$formID = 1;
    							$str = $form[1] . "t";
    						} elseif ($person == Category::THIRDPERSON) {
    							
    							$i = intval($inflectionclass);
    							if ($i == 62 || $i == 63 || $i == 64 || $i == 65 || $i == 68 || $i == 73) {
    								$formID = 6;
    								$str = $form[6];
    							} else {
    								$formID = 6;
    								$lastchar = mb_substr($form[6], -1);
    								//$lastchar = utf8_encode($lastchar);
    								if ($lastchar == 'o') $str = $form[6] . "o";
    								elseif ($lastchar == 'u') $str = $form[6] . "u";
    								elseif ($lastchar == 'a') $str = $form[6] . "a";
    								elseif ($lastchar == 'o') $str = $form[6] . "o";
    								elseif ($lastchar == 'y') $str = $form[6] . "y";
    								elseif ($lastchar == 'o') $str = $form[6] . "o";
    								elseif ($lastchar == 'i') $str = $form[6] . "i";
    								elseif ($lastchar == 'a') $str = $form[6] . "a";
    								elseif ($lastchar == 'ä') $str = $form[6] . "ä";
    								elseif ($lastchar == 'e') $str = $form[6] . "e";
    								else {
    									$formID = -1;
    									$str = $form[0] . "unknown word end 443-".$lastchar;
    								}
    							}
    							
    							
    							
    						} else {
    							$formID = -1;
    							$str = $form[0] . "unknown - 44";
    							//echo "<br>Unnown Person " . $person;
    						}
    					} elseif ($number == Category::VPLURAL) {
    						if ($person == Category::FIRSTPERSON) {
    							$formID = 1;
    							$str = $form[1] . "mme";
    						} elseif ($person == Category::SECONDPERSON) {
    							$formID = 1;
    							$str = $form[1] . "tte";
    						} elseif ($person == Category::THIRDPERSON) {
    							$formID = 6;
    							$str = $form[6] . "v_A_t";
    						} else {
    							$formID = -1;
    							$str = $form[0] . "unknown - 45";
    							//$str = $form[0] . "--- 07";
    						}
    					} else {
    						$str = $form[0] . "unknown - 46";
    					}
    				} else if ($voice == Category::PASSIVE) {
    					$i = intval($inflectionclass);
    					$formID = 4;
    					switch ($i) {
    						case ($i == 62 || $i == 63 || $i == 64 || $i == 65 || $i == 68 || $i == 71):
    							$str = $form[4] . "d_A__A_n";
    							break;
    						case ($i == 67):
    							$str = $form[4] . "l_A__A_n";
    							break;
    						default:
    							$str = $form[4] . "t_A__A_n";
    					}
    					
    				} else {
    					$formID = -1;
    					$str = $form[0] . "unknown - 146";
    				}
    						
    			} else if ($tense == Category::PAST) {
    				if ($voice == Category::ACTIVE) {
    					if ($number == Category::VSINGULAR) {
    						if ($person == Category::FIRSTPERSON) {
    							$formID = 2;
    							$str = $form[2] . "in";
    						} elseif ($person == Category::SECONDPERSON) {
    							$formID = 2;
    							$str = $form[2] . "it";
    						} elseif ($person == Category::THIRDPERSON) {
    							$formID = 3;
    							$str = $form[3] . "i";
    						} else {
    							$formID = -1;
    							$str = $form[0] . "unknown - 47";
    						}
    					} elseif ($number == Category::VPLURAL) {
    						if ($person == Category::FIRSTPERSON) {
    							$formID = 2;
    							$str = $form[2] . "imme";
    						} elseif ($person == Category::SECONDPERSON) {
    							$formID = 2;
    							$str = $form[2] . "itte";
    						} elseif ($person == Category::THIRDPERSON) {
    							$formID = 3;
    							$str = $form[3] . "iv_A_t";
    						} else {
    							$formID = -1;
    							$str = $form[0] . "unknown - 48";
    							//echo "<br>Unnown Person " . $person;
    						}
    					} else {
    						$formID = -1;
    						$str = $form[0] . "unknown - 49";
    						//echo "<br>number not defined - ";
    					}
    				} else if ($voice == Category::PASSIVE) {
    					
    					$i = intval($inflectionclass);
    					$formID = 4;
    					switch ($i) {
    						case ($i == 62 || $i == 63 || $i == 64 || $i == 65 || $i == 66 || $i == 67 || $i == 68 || $i == 70 ||  $i == 71):
    							$str = $form[4] . "tiin";
    							break;
    						default:
    							$str = $form[4] . "ttiin";
    					}
    					
    					//$formID = 4;
    					//$str = $form[4] . "ttiin";
    				} else {
    					$formID = -1;
    					$str = $form[0] . "unknown - 147";
    				}
    					 
    					
    			} else if ($tense == Category::FUTURE) {
    				if ($number == Category::VSINGULAR) {
    					if ($person == Category::FIRSTPERSON) {
    						$formID = -1;
    						$str = $form[0] . "--- 13";
    						//return $form[2] . 'in';
    					} elseif ($person == Category::SECONDPERSON) {
    						$formID = -1;
    						$str = $form[0] . "--- 14";
    						//return $form[2] . 'it';
    					} elseif ($person == Category::THIRDPERSON) {
    						$formID = -1;
    						$str = $form[0] . "--- 15";
    						//return $form[10] . 'i';
    					} else {
    						$formID = -1;
    						$str = $form[0] . "unknown - 50";
    						//echo "<br>Unnown Person " . $person;
    					}
    				} elseif ($number == Category::VPLURAL) {
    					if ($person == Category::FIRSTPERSON) {
    						$formID = -1;
    						$str = $form[0] . "--- 16";
    		    			//return $form[2] . 'imme';
    					} elseif ($person == Category::SECONDPERSON) {
    						$formID = -1;
    						$str = $form[0] . "--- 17";
    		    			//return $form[2] . 'itte';
    					} elseif ($person == Category::THIRDPERSON) {
    						$formID = -1;
    						$str = $form[0] . "--- 18";
    		    			//if (FinnishVerbInflector::hasBackVowels($form[10])) return $form[10] . 'ivät';
    						//else return $form[10] . 'ivat';
    						//return $form[10] . 'ivAt';
    					} else {
    						$formID = -1;
    						$str = $form[0] . "unknown - 51";
    						//echo "<br>Unnown Person " . $person;
    					}
    				} else {
    					$formID = -1;
    					$str = $form[0] . "unknown - 52";
    					//echo "<br>number not defined - ";
    				}
    			} else {
    				$formID = -1;
    				$str = $form[0] . "unknown - 53";
    				//echo "<br>Unnown Tense " . $tense;
    			}
    
    		} elseif ($modus == Category::CONDITIONAL) {
    			if ($tense == Category::PRESENT) {
    				if ($voice == Category::ACTIVE) {
    					if ($number == Category::VSINGULAR) {
    						if ($person == Category::FIRSTPERSON) {
    							$formID = 5;
    							$str = $form[5] . "isin";
    							//return $form[3] . 'isin';
    						} elseif ($person == Category::SECONDPERSON) {
    							$formID = 5;
    							$str = $form[5] . "isit";
    							//return $form[3] . 'isit';
    						} elseif ($person == Category::THIRDPERSON) {
    							$formID = 5;
    							$str = $form[5] . "isi";
    							//return $form[3] . 'isi';
    						} else {
    							$formID = -1;
    							$str = $form[0] . "unknown - 54";
    							//echo "<br>Unnown Person " . $person;
    						}
    					} elseif ($number == Category::VPLURAL) {
    						if ($person == Category::FIRSTPERSON) {
    							$formID = 5;
    							$str = $form[5] . "isimme";
    							//return $form[3] . 'isimme';
    						} elseif ($person == Category::SECONDPERSON) {
    							$formID = 5;
    							$str = $form[5] . "isitte";
    							//return $form[3] . 'isitte';
    						} elseif ($person == Category::THIRDPERSON) {
    							$formID = 5;
    							$str = $form[5] . "isiv_A_t";
    							//if (FinnishVerbInflector::hasBackVowels($form[3])) return $form[3] . 'isivät';
    							//else return $form[3] . 'isivat';
    							//return $form[3] . 'isivAt';
    						} else {
    							$formID = -1;
    							$str = $form[0] . "unknown - 55";
    							//echo "<br>Unnown Person " . $person;
    						}
    					} else {
    						$str = $form[0] . "unknown - 56";
    						//echo "<br>Unnown Number " . $number;
    					}
    				} else if ($voice == Category::PASSIVE) {
    					
    					$i = intval($inflectionclass);
    					$formID = 4;
    					switch ($i) {
    						case ($i == 62 || $i == 63 || $i == 64 || $i == 65 || $i == 66 || $i == 67 || $i == 68 || $i == 70 ||  $i == 71):
    							$str = $form[4] . "t_A_isiin";
    							break;
    						default:
    							$str = $form[4] . "tt_A_isiin";
    					}
    						
    					
    					//$formID = 4;
    					//$str = $form[4] . "tt_A_isiin";
    				} else {
    					$formID = -1;
    					$str = $form[0] . "unknown - 147";
    				}
    			} else if ($tense == Category::PAST) {      // ei oikeastaan ole olemassa
    				$formID = -1;
    				$str = $form[0] . "unknown - 57";
    				//echo "<br>Konditional past not exists in Finnish language";
    			} else if ($tense == Category::FUTURE) {      // ei oikeastaan ole olemassa
    				$formID = -1;
    				$str = $form[0] . "unknown - 58";
    				//echo "<br>Konditional future not exists in Finnish language";
    			} else {
    				$formID = -1;
    				$str = $form[0] . "unknown - 59";
    				//echo "<br>Unnown Tense " . $tense;
    			}
    
    		} elseif ($modus == Category::POTENTIAL) {
    			if ($tense == Category::PRESENT) {
    				
    				if ($voice == Category::ACTIVE) {
    					if ($number == Category::VSINGULAR) {
    						if ($person == Category::FIRSTPERSON) {
    							$formID = 7;
    							$str = $form[7] . "en";
    							//return $form[7] . 'en';
    						} elseif ($person == Category::SECONDPERSON) {
    							$formID = 7;
    							$str = $form[7] . "et";
    							//return $form[7] . 'et';
    						} elseif ($person == Category::THIRDPERSON) {
    							$formID = 7;
    							$str = $form[7] . "ee";
    							//return $form[7] . 'ee';
    						} else {
    							$formID = -1;
    							$str = $form[0] . "unknown - 60";
    							//echo "<br>Unnown Person " . $person;
    						}
    					} elseif ($number == Category::VPLURAL) {
    						if ($person == Category::FIRSTPERSON) {
    							$formID = 7;
    							$str = $form[7] . "emme";
    							//return $form[7] . 'emme';
    						} elseif ($person == Category::SECONDPERSON) {
    							$formID = 7;
    							$str = $form[7] . "ette";
    							//return $form[7] . 'ette';
    						} elseif ($person == Category::THIRDPERSON) {
    							$formID = 7;
    							$str = $form[7] . "ev_A_t";
    							//if (FinnishVerbInflector::hasBackVowels($form[7])) return $form[3] . 'evät';
    							//else return $form[7] . 'evat';
    							//return $form[7] . 'evAt';
    						} else {
    							$formID = -1;
    							$str = $form[0] . "unknown - 61";
    							//echo "<br>Unnown Person " . $person;
    						}
    					} else {
    						$formID = -1;
    						$str = $form[0] . "unknown - 62";
    						//echo "<br>Unnown Number " . $number;
    					}
    				} else if ($voice == Category::PASSIVE) {
    					
    					$i = intval($inflectionclass);
    					$formID = 4;
    					switch ($i) {
    						case ($i == 62 || $i == 63 || $i == 64 || $i == 65 || $i == 66 || $i == 67 || $i == 68 || $i == 70 ||  $i == 71):
    							$str = $form[4] . "t_A_neen";
    							break;
    						default:
    							$str = $form[4] . "tt_A_neen";
    					}
    				} else {
    					$formID = -1;
    					$str = $form[0] . "unknown - 147";
    				}
    				
    				
    				
    			} else if ($tense == Category::PAST) {
    				$formID = -1;
    				$str = $form[0] . "unknown - 63";
    				//echo "<br>Modus:potential with tense:past not exists in Finnish language";
    			} else if ($tense == Category::FUTURE) {
    				$formID = -1;
    				$str = $form[0] . "unknown - 64";
    				//echo "<br>tense:future not exists in Finnish language";
    			} else {
    				$formID = -1;
    				$str = $form[0] . "unknown - 65";
    				//echo "<br>Unnown Tense " . $tense;
    			}
    
    		} elseif ($modus == Category::IMPERATIVE) {
    			if ($tense == Category::PRESENT) {
    				if ($voice == Category::ACTIVE) {
    					if ($number == Category::VSINGULAR) {
    						if ($person == Category::FIRSTPERSON) {
    							$formID = -1;
    							$str = $form[0] . "unknown - 66";
    							//echo "<br>Modus:imperative with singular first person not exists in Finnish language";
    							//return '';
    						} elseif ($person == Category::SECONDPERSON) {
    							$formID = 1;
    							$str = $form[1] . "";
    							//return $form[1] . '';
    						} elseif ($person == Category::THIRDPERSON) {
    							$formID = 0;
    							$str = $form[0] . "k_O__O_n";
    							//if (FinnishVerbInflector::hasBackVowels($form[4])) return $form[4] . 'kään';
    							//else return $form[4] . 'koon';
    							//return $form[4] . 'kOOn';
    						} else {
    							$formID = 0;
    							$str = $form[0] . "unknown - 67";
    							//echo "<br>Unnown Person " . $person;
    						}
    					} elseif ($number == Category::VPLURAL) {
    						if ($person == Category::FIRSTPERSON) {
    							$formID = 0;
    							$str = $form[0] . "k_A__A_mme";
    							//if (FinnishVerbInflector::hasBackVowels($form[4])) return $form[4] . 'käämme';
    							//else return $form[4] . 'kaamme';
    							//return $form[4] . 'kAAmme';
    						}
    						elseif ($person == Category::SECONDPERSON) {
    							$formID = 0;
    							$str = $form[0] . "k_A__A_";
    							//if (FinnishVerbInflector::hasBackVowels($form[4])) return $form[4] . 'kää';
    							//else return $form[4] . 'kaa';
    							//return $form[4] . 'kAA';
    						}
    						elseif ($person == Category::THIRDPERSON) {
    							$formID = 0;
    							$str = $form[0] . "k_O__O_t";
    							//if (FinnishVerbInflector::hasBackVowels($form[4])) return $form[4] . 'käät';
    							//else return $form[4] . 'koot';
    							//return $form[4] . 'kOOt';
    						} else {
    							$formID = -1;
    							$str = $form[0] . "unknown - 68";
    							//echo "<br>Unnown Person " . $person;
    						}
    					} else {
    						$str = $form[0] . "unknown - 69";
    						//echo "<br>Unnown Number " . $number;
    					}
    				} elseif ($voice == Category::PASSIVE) {
    					
    					$i = intval($inflectionclass);
    					$formID = 4;
    					switch ($i) {
    						case ($i == 62 || $i == 63 || $i == 64 || $i == 65 || $i == 66 || $i == 67 || $i == 68 || $i == 70 ||  $i == 71):
    							$str = $form[4] . "t_A_k_O__O_n";
    							break;
    						default:
    							$str = $form[4] . "tt_A_k_O__O_n";
    					}
    					
    					//$formID = 0;
    					//$str = $form[0] . "ttak_O__O_n";
    					
    				} else {
    				
    				}
    				
    				
    				
    			} else if ($tense == Category::PAST) {
    				$formID = -1;
    				$str = $form[0] . "unknown - 70";
    				//echo "<br>Modus:imperative with tense:past not exists in Finnish language";
    			} else if ($tense == Category::FUTURE) {
    				$formID = -1;
    				$str = $form[0] . "unknown - 71";
    				//echo "<br>Tense:future not exists in Finnish language";
    			} else {
    				$formID = -1;
    				$str = $form[0] . "unknown - 72";
    				//echo "<br>Unnown Tense " . $tense;
    			}
    		} else {
    			$formID = -1;
    			$str = $form[0] . "unknown - 73";
    			//echo "<br>Unnown Modus " . $modus;
    		}
    	} elseif ($polarity == Category::NEGATION) {
    
    		if ($modus == Category::INDIKATIVE) {
    			if ($tense == Category::PRESENT) {
    				if ($voice == Category::ACTIVE) {
    					if ($number == Category::VSINGULAR) {
    						$formID = 1;
    						$str = $form[1] . "";
    					} elseif ($number == Category::VPLURAL) {
    						$formID = 1;
    						$str = $form[1] . "";
    					} elseif ($number == Category::VNUMBER) {
    						$formID = 1;
    						$str = $form[1] . "";
    					} else {
    						$formID = -1;
    						$str = $form[0] . "unknown - 147";
    					}
    				} else if ($voice == Category::PASSIVE) {
    					$i = intval($inflectionclass);
    					$formID = 4;
    					switch ($i) {
    						case ($i == 62 || $i == 63 || $i == 64 || $i == 65 || $i == 68 || $i == 71):
    							$str = $form[4] . "d_A_";
    							break;
    						case ($i == 67):
    							$str = $form[4] . "l_A_";
    							break;
    						default:
    							$str = $form[4] . "t_A_";
    					}
    				} else {
    					$formID = -1;
    					$str = $form[0] . "unknown - 147";
    				}
    			} else if ($tense == Category::PAST) {
    				if ($voice == Category::ACTIVE) {
    					if ($number == Category::VSINGULAR) {
    						$formID = 7;
    						$str = $form[7] . "_U_t";
    					} elseif ($number == Category::VPLURAL) {
    						$formID = 7;
    						$str = $form[7] . "eet";
    					} else {
    						$formID = -1;
    						$str = $form[0] . "unknown - 147";
    					}
    				} else if ($voice == Category::PASSIVE) {
    				
    					$i = intval($inflectionclass);
    					$formID = 4;
    					switch ($i) {
    						case ($i == 62 || $i == 63 || $i == 64 || $i == 65 || $i == 66 || $i == 67 || $i == 68 || $i == 70 ||  $i == 71):
    							$str = $form[4] . "t_U_";
    							break;
    						default:
    							$str = $form[4] . "tt_U_";
    					}
    					
    					//$formID = 4;
    					//$str = $form[4] . "tt_U_";
    				} else {
    					$formID = -1;
    					$str = $form[0] . "unknown - 147";
    				}
    			} else {
    				$formID = -1;
    				$str = $form[0] . "unknown - 75";
    				//echo "<br>Unnown Tense " . $tense;
    			}
    
    		} elseif ($modus == Category::CONDITIONAL) {
    		
    			if ($tense == Category::PRESENT) {
    				if ($voice == Category::ACTIVE) {
    					if ($number == Category::VSINGULAR) {
    						$formID = 5;
    						$str = $form[5] . "isi";
    					} elseif ($number == Category::VPLURAL) {
    						$formID = 5;
    						$str = $form[5] . "isi";
    					} elseif ($number == Category::VNUMBER) {
    						$formID = 5;
    						$str = $form[5] . "isi";
    					} else {
    						$formID = -1;
    						$str = $form[0] . "unknown - 147";
    					}
    				} else if ($voice == Category::PASSIVE) {
    					
    					$i = intval($inflectionclass);
    					$formID = 4;
    					switch ($i) {
    						case ($i == 62 || $i == 63 || $i == 64 || $i == 65 || $i == 66 || $i == 67 || $i == 68 || $i == 70 ||  $i == 71):
    							$str = $form[4] . "t_A_isi";
    							break;
    						default:
    							$str = $form[4] . "tt_A_isi";
    					}
    					//$formID = 4;
    					//$str = $form[4] . "tt_A_isi";
    				} else {
    					$formID = -1;
    					$str = $form[0] . "unknown - 147";
    				}
    			} else if ($tense == Category::PAST) {
    				if ($voice == Category::ACTIVE) {
    					if ($number == Category::VSINGULAR) {
    						$formID = -1;
    						$str = $form[7] . "-120";
    					} elseif ($number == Category::VPLURAL) {
    						$formID = -1;
    						$str = $form[7] . "-121";
    					} else {
    						$formID = -1;
    						$str = $form[0] . "unknown - 147";
    					}
    				} else if ($voice == Category::PASSIVE) {
    					$formID = 4;
    					$str = $form[4] . "-122";
    				} else {
    					$formID = -1;
    					$str = $form[0] . "unknown - 147";
    				}
    			} else {
    				$formID = -1;
    				$str = $form[0] . "unknown - 75";
    				//echo "<br>Unnown Tense " . $tense;
    			}
    			
    
    		} elseif ($modus == Category::POTENTIAL) {
    
    			if ($tense == Category::PRESENT) {
    				
    				if ($voice == Category::ACTIVE) {
    				
    					if ($number == Category::VSINGULAR) {
    						$formID = 7;
    						$str = $form[7] . "e";
    					} elseif ($number == Category::VPLURAL) {
    						$formID = 7;
    						$str = $form[7] . "e";
    					} elseif ($number == Category::VNUMBER) {
    						$formID = 7;
    						$str = $form[7] . "e";
    					} else {
    						$formID = -1;
    						$str = $form[0] . "unknown - 147";
    					}
    					
    				} else if ($voice == Category::PASSIVE) {
    					
    					$i = intval($inflectionclass);
    					$formID = 4;
    					switch ($i) {
    						case ($i == 62 || $i == 63 || $i == 64 || $i == 65 || $i == 66 || $i == 67 || $i == 68 || $i == 70 ||  $i == 71):
    							$str = $form[4] . "t_A_ne";
    							break;
    						default:
    							$str = $form[4] . "tt_A_ne";
    					}
    					
    					//$formID = 6;
    					//$str = $form[6] . "tt_A_ne";
    				} else {
    					$formID = -1;
    					$str = $form[0] . "unknown - 147";
    				}
    				
    				//$formID = -1;
    				//$str = $form[0] . "--- 40";
    		    	//return '' . $form[7] . 'e';
    			} else if ($tense == Category::PAST) {
    				$formID = -1;
    				$str = $form[0] . "unknown - 79";
    				//echo "<br>Modus:potential with tense:past not exists in Finnish language";
    			} else if ($tense == Category::FUTURE) {
    				$formID = -1;
    				$str = $form[0] . "unknown - 80";
    				//echo "<br>tense:future not exists in Finnish language";
    			} else {
    				$formID = -1;
    				$str = $form[0] . "unknown - 81";
    				//echo "<br>Unnown Tense " . $tense;
    			}
    
    		} elseif ($modus == Category::IMPERATIVE) {
    			if ($tense == Category::PRESENT) {
    				if ($voice == Category::ACTIVE) {
    					
    					if ($number == Category::VSINGULAR) {
    						if ($person == Category::FIRSTPERSON) {
    							$formID = -1;
    							$str = $form[0] . "unknown - 82";
    							echo "<br>Modus:imperative with singular first person not exists in Finnish language";
    						} elseif ($person == Category::SECONDPERSON) {
    							$formID = 1;
    							$str = $form[1] . "";
    							//return '' . $form[1] . '';
    						} elseif ($person == Category::THIRDPERSON) {
    							$formID = 0;
    							$str = $form[0] . "k_O_";
    							//if (FinnishVerbInflector::hasBackVowels($form[4])) return '' . $form[4] . 'kä';
    							//else return '' . $form[4] . 'ko';
    							//return 'älkään ' . $form[4] . 'kO';
    						} else {
    							$formID = -1;
    							$str = $form[0] . "unknown - 83";
    							//echo "<br>Unnown Person " . $person;
    						}
    					} elseif ($number == Category::VPLURAL) {
    						$formID = 0;
    						$str = $form[0] . "k_O_";
    						//if (FinnishVerbInflector::hasBackVowels($form[4])) return '' . $form[4] . 'kä';
    						//else return '' . $form[4] . 'ko';
    					} else {
    						$formID = -1;
    						$str = $form[0] . "unknown - 84";
    						echo "<br>Unnown Number " . $number;
    					}
    					
    				} else if ($voice == Category::PASSIVE) {
    					
    					$i = intval($inflectionclass);
    					$formID = 4;
    					switch ($i) {
    						case ($i == 62 || $i == 63 || $i == 64 || $i == 65 || $i == 66 || $i == 67 || $i == 68 || $i == 70 ||  $i == 71):
    							$str = $form[4] . "t_A_k_O_";
    							break;
    						default:
    							$str = $form[4] . "tt_A_k_O_";
    					}
    					
    					//$formID = 4;
    					//$str = $form[4] . "tt_A_k_O_";
    				} else {
    					$formID = -1;
    					$str = $form[0] . "unknown - 147";
    				}
    				
    				
    			} else if ($tense == Category::PAST) {
    				$formID = -1;
    				$str = $form[0] . "unknown - 85";
    				//echo "<br>Modus:imperative with tense:past not exists in Finnish language";
    			} else if ($tense == Category::FUTURE) {
    				$formID = -1;
    				$str = $form[0] . "unknown - 86";
    				//echo "<br>Tense:future not exists in Finnish language";
    			} else {
    				$formID = -1;
    				$str = $form[0] . "unknown - 87";
    				//echo "<br>Unnown Tense " . $tense;
    			}
    		} else {
    			$formID = -1;
    			$str = $form[0] . "unknown - 88";
    			//echo "<br>Unnown Modus " . $modus;
    		}
    	}
    	
    	
		
    	if (FinnishVerbInflector::toFrontVowel($str)) {
    		$str = str_replace("_A_", "ä", $str);
    		$str = str_replace("_O_", "ö", $str);
    		$str = str_replace("_U_", "y", $str);
    	} else {
    		$str = str_replace("_A_", "a", $str);
    		$str = str_replace("_O_", "o", $str);
    		$str = str_replace("_U_", "u", $str);
    	}
    	 
    	return $str;
    	
    }
    
    
    
    


    private static function toFrontVowel($str) {
    
    	$backvowelfound = false;
    	$frontvowelfound = false;
    	 
    	for ($i = 0; $i < strlen($str); $i++){
    		if (($str[$i] == 'a') || ($str[$i] == 'o') || ($str[$i] == 'u')) $backvowelfound = true;
    		if (($str[$i] == 'y') || ($str[$i] == 'ä') || ($str[$i] == 'ä')) $frontvowelfound = true;
    	}
    	if ($frontvowelfound) return 1;
    	if (!$backvowelfound) return 1;
    	return 0;
    }
    
    
    /*

    	// vanha koodi backupiksi
    private static function getBaseFormsPrivate($person, $number, $tense, $modus, $negation, $form) {
    
    	//echo "<br>" . $person . ", " . $number . ", " . $tense . ", " . $modus . ", " . $negation;
    
    	if ($negation == Category::AFFIRMATIVE) {
    		if ($modus == Category::INDIKATIVE) {
    			if ($tense == Category::PRESENT) {
    				if ($number == Category::VSINGULAR) {
    					if ($person == Category::FIRSTPERSON) return $form[1] . 'n';
    					elseif ($person == Category::SECONDPERSON) return $form[1] . 't';
    					elseif ($person == Category::THIRDPERSON) return $form[9] . '';
    					else echo "<br>Unnown Person " . $person;
    				} elseif ($number == Category::VPLURAL) {
    					if ($person == Category::FIRSTPERSON) return $form[1] . 'mme';
    					elseif ($person == Category::SECONDPERSON) return $form[1] . 'tte';
    					elseif ($person == Category::THIRDPERSON) {
    						if (FinnishVerbInflector::hasBackVowels($form[8])) return $form[8] . 'vät';
    						else return $form[8] . 'vat';
    						//return $form[8] . 'vAt';
    					}
    					else echo "<br>Unnown Person " . $person;
    				} else {
    					echo "<br>Unnown Number " . $number;
    				}
    			} else if ($tense == Category::PAST) {
    				if ($number == Category::VSINGULAR) {
    					if ($person == Category::FIRSTPERSON) return $form[2] . 'in';
    					elseif ($person == Category::SECONDPERSON) return $form[2] . 'it';
    					elseif ($person == Category::THIRDPERSON) return $form[10] . 'i';
    					else echo "<br>Unnown Person " . $person;
    				} elseif ($number == Category::VPLURAL) {
    					if ($person == Category::FIRSTPERSON) return $form[2] . 'imme';
    					elseif ($person == Category::SECONDPERSON) return $form[2] . 'itte';
    					elseif ($person == Category::THIRDPERSON) {
    						if (FinnishVerbInflector::hasBackVowels($form[10])) return $form[10] . 'ivät';
    						else return $form[10] . 'ivat';
    						//return $form[10] . 'ivAt';
    					}
    					else echo "<br>Unnown Person " . $person;
    				} else {
    					echo "<br>number not defined - ";
    				}
    			} else if ($tense == Category::PER) {
    				if ($number == Category::VSINGULAR) {
    					if ($person == Category::FIRSTPERSON) return $form[2] . 'in';
    					elseif ($person == Category::SECONDPERSON) return $form[2] . 'it';
    					elseif ($person == Category::THIRDPERSON) return $form[10] . 'i';
    					else echo "<br>Unnown Person " . $person;
    				} elseif ($number == Category::VPLURAL) {
    					if ($person == Category::FIRSTPERSON) return $form[2] . 'imme';
    					elseif ($person == Category::SECONDPERSON) return $form[2] . 'itte';
    					elseif ($person == Category::THIRDPERSON) {
    						if (FinnishVerbInflector::hasBackVowels($form[10])) return $form[10] . 'ivät';
    						else return $form[10] . 'ivat';
    						//return $form[10] . 'ivAt';
    					}
    					else echo "<br>Unnown Person " . $person;
    				} else {
    					echo "<br>number not defined - ";
    				}
    			} else {
    				echo "<br>Unnown Tense " . $tense;
    			}
    
    		} elseif ($modus == Category::CONDITIONAL) {
    			if ($tense == Category::PRESENT) {
    				if ($number == Category::VSINGULAR) {
    					if ($person == Category::FIRSTPERSON) return $form[3] . 'isin';
    					elseif ($person == Category::SECONDPERSON) return $form[3] . 'isit';
    					elseif ($person == Category::THIRDPERSON) return $form[3] . 'isi';
    					else echo "<br>Unnown Person " . $person;
    				} elseif ($number == Category::VPLURAL) {
    					if ($person == Category::FIRSTPERSON) return $form[3] . 'isimme';
    					elseif ($person == Category::SECONDPERSON) return $form[3] . 'isitte';
    					elseif ($person == Category::THIRDPERSON) {
    						if (FinnishVerbInflector::hasBackVowels($form[3])) return $form[3] . 'isivät';
    						else return $form[3] . 'isivat';
    						//return $form[3] . 'isivAt';
    					}
    					else echo "<br>Unnown Person " . $person;
    				} else {
    					echo "<br>Unnown Number " . $number;
    				}
    			} else if ($tense == Category::PAST) {      // ei oikeastaan ole olemassa
    				echo "<br>Konditional past not exists in Finnish language";
    			} else if ($tense == Category::FUTURE) {      // ei oikeastaan ole olemassa
    				echo "<br>Konditional future not exists in Finnish language";
    			} else {
    				echo "<br>Unnown Tense " . $tense;
    			}
    
    		} elseif ($modus == Category::POTENTIAL) {
    			if ($tense == Category::PRESENT) {
    				if ($number == Category::VSINGULAR) {
    					if ($person == Category::FIRSTPERSON) return $form[7] . 'en';
    					elseif ($person == Category::SECONDPERSON) return $form[7] . 'et';
    					elseif ($person == Category::THIRDPERSON) return $form[7] . 'ee';
    					else echo "<br>Unnown Person " . $person;
    				} elseif ($number == Category::VPLURAL) {
    					if ($person == Category::FIRSTPERSON) return $form[7] . 'emme';
    					elseif ($person == Category::SECONDPERSON) return $form[7] . 'ette';
    					elseif ($person == Category::THIRDPERSON) {
    						if (FinnishVerbInflector::hasBackVowels($form[7])) return $form[3] . 'evät';
    						else return $form[7] . 'evat';
    						//return $form[7] . 'evAt';
    					}
    					else echo "<br>Unnown Person " . $person;
    				} else {
    					echo "<br>Unnown Number " . $number;
    				}
    			} else if ($tense == Category::PAST) {
    				echo "<br>Modus:potential with tense:past not exists in Finnish language";
    			} else if ($tense == Category::FUTURE) {
    				echo "<br>tense:future not exists in Finnish language";
    			} else {
    				echo "<br>Unnown Tense " . $tense;
    			}
    
    		} elseif ($modus == Category::IMPERATIVE) {
    			if ($tense == Category::PRESENT) {
    				if ($number == Category::VSINGULAR) {
    					if ($person == Category::FIRSTPERSON) {
    						echo "<br>Modus:imperative with singular first person not exists in Finnish language";
    						return '';
    					} elseif ($person == Category::SECONDPERSON) {
    						return $form[1] . '';
    					}
    					elseif ($person == Category::THIRDPERSON) {
    						if (FinnishVerbInflector::hasBackVowels($form[4])) return $form[4] . 'kään';
    						else return $form[4] . 'koon';
    						//return $form[4] . 'kOOn';
    					}
    					else echo "<br>Unnown Person " . $person;
    				} elseif ($number == Category::VPLURAL) {
    					if ($person == Category::FIRSTPERSON) {
    						if (FinnishVerbInflector::hasBackVowels($form[4])) return $form[4] . 'käämme';
    						else return $form[4] . 'kaamme';
    						//return $form[4] . 'kAAmme';
    					}
    					elseif ($person == Category::SECONDPERSON) {
    						if (FinnishVerbInflector::hasBackVowels($form[4])) return $form[4] . 'kää';
    						else return $form[4] . 'kaa';
    						//return $form[4] . 'kAA';
    					}
    					elseif ($person == Category::THIRDPERSON) {
    						if (FinnishVerbInflector::hasBackVowels($form[4])) return $form[4] . 'käät';
    						else return $form[4] . 'koot';
    						//return $form[4] . 'kOOt';
    					}
    					else echo "<br>Unnown Person " . $person;
    				} else {
    					echo "<br>Unnown Number " . $number;
    				}
    			} else if ($tense == Category::PAST) {
    				echo "<br>Modus:imperative with tense:past not exists in Finnish language";
    			} else if ($tense == Category::FUTURE) {
    				echo "<br>Tense:future not exists in Finnish language";
    			} else {
    				echo "<br>Unnown Tense " . $tense;
    			}
    		} else {
    			echo "<br>Unnown Modus " . $modus;
    		}
    	} elseif ($negation == Category::NEGATION) {
    
    		if ($modus == Category::INDIKATIVE) {
    			if ($tense == Category::PRESENT) {
    				return '' . $form[1] . '';
    			} else if ($tense == Category::PAST) {
    				if ($number == Category::VSINGULAR) {
    					if (FinnishVerbInflector::hasBackVowels($form[7])) return '' . $form[7] . 'yt';
    					else return '' . $form[7] . 'ut';
    				} elseif ($number == Category::VPLURAL) {
    					return '' . $form[7] . 'eet';
    				} else {
    					echo "<br>number not defined";
    				}
    			} else {
    				echo "<br>Unnown Tense " . $tense;
    			}
    
    		} elseif ($modus == Category::CONDITIONAL) {
    			if ($tense == Category::PRESENT) {
    				return '' . $form[3] . 'isi';
    			} else if ($tense == Category::PAST) {      // ei oikeastaan ole olemassa
    				echo "<br>Konditional past not exists in Finnish language";
    			} else if ($tense == Category::FUTURE) {      // ei oikeastaan ole olemassa
    				echo "<br>Konditional future not exists in Finnish language";
    			} else {
    				echo "<br>Unnown Tense " . $tense;
    			}
    
    		} elseif ($modus == Category::POTENTIAL) {
    
    			if ($tense == Category::PRESENT) {
    				return '' . $form[7] . 'e';
    			} else if ($tense == Category::PAST) {
    				echo "<br>Modus:potential with tense:past not exists in Finnish language";
    			} else if ($tense == Category::FUTURE) {
    				echo "<br>tense:future not exists in Finnish language";
    			} else {
    				echo "<br>Unnown Tense " . $tense;
    			}
    
    		} elseif ($modus == Category::IMPERATIVE) {
    			if ($tense == Category::PRESENT) {
    				if ($number == Category::VSINGULAR) {
    					if ($person == Category::FIRSTPERSON) {
    						echo "<br>Modus:imperative with singular first person not exists in Finnish language";
    					} elseif ($person == Category::SECONDPERSON) {
    						return '' . $form[1] . '';
    					} elseif ($person == Category::THIRDPERSON) {
    						if (FinnishVerbInflector::hasBackVowels($form[4])) return '' . $form[4] . 'kä';
    						else return '' . $form[4] . 'ko';
    						//return 'älkään ' . $form[4] . 'kO';
    					}
    					else echo "<br>Unnown Person " . $person;
    				} elseif ($number == Category::VPLURAL) {
    					if (FinnishVerbInflector::hasBackVowels($form[4])) return '' . $form[4] . 'kä';
    					else return '' . $form[4] . 'ko';
    				} else {
    					echo "<br>Unnown Number " . $number;
    				}
    			} else if ($tense == Category::PAST) {
    				echo "<br>Modus:imperative with tense:past not exists in Finnish language";
    			} else if ($tense == Category::FUTURE) {
    				echo "<br>Tense:future not exists in Finnish language";
    			} else {
    				echo "<br>Unnown Tense " . $tense;
    			}
    		} else {
    			echo "<br>Unnown Modus " . $modus;
    		}
    	}
    }
    */
    
    /*
     * 
     // Mitä immeisimmin vanhaa tuotantoa
    private static function getFormPrivate($person, $number, $tense, $modus, $negation, $form) {
        
        //echo "<br>" . $person . ", " . $number . ", " . $tense . ", " . $modus . ", " . $negation;
        
        if ($negation == Category::AFFIRMATIVE) {
            if ($modus == Category::INDIKATIVE) {
                if ($tense == Category::PRESENT) {
                    if ($number == Category::VSINGULAR) {
                        if ($person == Category::FIRSTPERSON) return $form[1] . 'n';
                        elseif ($person == Category::SECONDPERSON) return $form[1] . 't';
                        elseif ($person == Category::THIRDPERSON) return $form[9] . '';
                        else echo "<br>Unnown Person " . $person;
                    } elseif ($number == Category::VPLURAL) {
                        if ($person == Category::FIRSTPERSON) return $form[1] . 'mme';
                        elseif ($person == Category::SECONDPERSON) return $form[1] . 'tte';
                        elseif ($person == Category::THIRDPERSON) {
                        	if (FinnishVerbInflector::hasBackVowels($form[8])) return $form[8] . 'vät';
                        	else return $form[8] . 'vat';
                        	//return $form[8] . 'vAt';
                        }
                        else echo "<br>Unnown Person " . $person;
                    } else {
                        echo "<br>Unnown Number " . $number;
                    }
                } else if ($tense == Category::PAST) {
                    if ($number == Category::VSINGULAR) {
                        if ($person == Category::FIRSTPERSON) return $form[2] . 'in';
                        elseif ($person == Category::SECONDPERSON) return $form[2] . 'it';
                        elseif ($person == Category::THIRDPERSON) return $form[10] . 'i';
                        else echo "<br>Unnown Person " . $person;
                    } elseif ($number == Category::VPLURAL) {
                        if ($person == Category::FIRSTPERSON) return $form[2] . 'imme';
                        elseif ($person == Category::SECONDPERSON) return $form[2] . 'itte';
                        elseif ($person == Category::THIRDPERSON) {
                        	if (FinnishVerbInflector::hasBackVowels($form[10])) return $form[10] . 'ivät';
                        	else return $form[10] . 'ivat';
                        	//return $form[10] . 'ivAt';
                        }
                        else echo "<br>Unnown Person " . $person;
                    } else {
                        echo "<br>number not defined - ";
                    }
                } else {
                    echo "<br>Unnown Tense " . $tense;
                }
                
            } elseif ($modus == Category::CONDITIONAL) {
                if ($tense == Category::PRESENT) {
                    if ($number == Category::VSINGULAR) {
                        if ($person == Category::FIRSTPERSON) return $form[3] . 'isin';
                        elseif ($person == Category::SECONDPERSON) return $form[3] . 'isit';
                        elseif ($person == Category::THIRDPERSON) return $form[3] . 'isi';
                        else echo "<br>Unnown Person " . $person;
                    } elseif ($number == Category::VPLURAL) {
                        if ($person == Category::FIRSTPERSON) return $form[3] . 'isimme';
                        elseif ($person == Category::SECONDPERSON) return $form[3] . 'isitte';
                        elseif ($person == Category::THIRDPERSON) {
                        	if (FinnishVerbInflector::hasBackVowels($form[3])) return $form[3] . 'isivät';
                        	else return $form[3] . 'isivat';
                        	//return $form[3] . 'isivAt';
                        }
                        else echo "<br>Unnown Person " . $person;
                    } else {
                        echo "<br>Unnown Number " . $number;
                    }
                } else if ($tense == Category::PAST) {      // ei oikeastaan ole olemassa
                    echo "<br>Konditional past not exists in Finnish language";
                } else if ($tense == Category::FUTURE) {      // ei oikeastaan ole olemassa
                    echo "<br>Konditional future not exists in Finnish language";
                } else {
                    echo "<br>Unnown Tense " . $tense;
                }
                
            } elseif ($modus == Category::POTENTIAL) {
                if ($tense == Category::PRESENT) {
                    if ($number == Category::VSINGULAR) {
                        if ($person == Category::FIRSTPERSON) return $form[7] . 'en';
                        elseif ($person == Category::SECONDPERSON) return $form[7] . 'et';
                        elseif ($person == Category::THIRDPERSON) return $form[7] . 'ee';
                        else echo "<br>Unnown Person " . $person;
                    } elseif ($number == Category::VPLURAL) {
                        if ($person == Category::FIRSTPERSON) return $form[7] . 'emme';
                        elseif ($person == Category::SECONDPERSON) return $form[7] . 'ette';
                        elseif ($person == Category::THIRDPERSON) {
                        	if (FinnishVerbInflector::hasBackVowels($form[7])) return $form[3] . 'evät';
                        	else return $form[7] . 'evat';
                        	//return $form[7] . 'evAt';
                        }
                        else echo "<br>Unnown Person " . $person;
                    } else {
                        echo "<br>Unnown Number " . $number;
                    }
                } else if ($tense == Category::PAST) {
                    echo "<br>Modus:potential with tense:past not exists in Finnish language";
                } else if ($tense == Category::FUTURE) {
                    echo "<br>tense:future not exists in Finnish language";
                } else {
                    echo "<br>Unnown Tense " . $tense;
                }
                
            } elseif ($modus == Category::IMPERATIVE) {
                if ($tense == Category::PRESENT) {
                    if ($number == Category::VSINGULAR) {
                        if ($person == Category::FIRSTPERSON) {
                            echo "<br>Modus:imperative with singular first person not exists in Finnish language";
                            return '';
                        } elseif ($person == Category::SECONDPERSON) {
                            return $form[1] . '';
                        }
                        elseif ($person == Category::THIRDPERSON) {
                        	if (FinnishVerbInflector::hasBackVowels($form[4])) return $form[4] . 'kään';
                        	else return $form[4] . 'koon';
                        	//return $form[4] . 'kOOn';
                        }
                        else echo "<br>Unnown Person " . $person;
                    } elseif ($number == Category::VPLURAL) {
                        if ($person == Category::FIRSTPERSON) {
                        	if (FinnishVerbInflector::hasBackVowels($form[4])) return $form[4] . 'käämme';
                        	else return $form[4] . 'kaamme';
                       		//return $form[4] . 'kAAmme';
                        }
                        elseif ($person == Category::SECONDPERSON) {
                        	if (FinnishVerbInflector::hasBackVowels($form[4])) return $form[4] . 'kää';
                        	else return $form[4] . 'kaa';
                       		//return $form[4] . 'kAA';
                        }
                        elseif ($person == Category::THIRDPERSON) {
                         	if (FinnishVerbInflector::hasBackVowels($form[4])) return $form[4] . 'käät';
                        	else return $form[4] . 'koot';
                       		//return $form[4] . 'kOOt';
                        }
                        else echo "<br>Unnown Person " . $person;
                    } else {
                        echo "<br>Unnown Number " . $number;
                    }
                } else if ($tense == Category::PAST) {
                    echo "<br>Modus:imperative with tense:past not exists in Finnish language";
                } else if ($tense == Category::FUTURE) {
                    echo "<br>Tense:future not exists in Finnish language";
                } else {
                    echo "<br>Unnown Tense " . $tense;
                }
            } else {
                echo "<br>Unnown Modus " . $modus;                
            }           
        } elseif ($negation == Category::NEGATION) {
            
            if ($modus == Category::INDIKATIVE) {
                if ($tense == Category::PRESENT) {
                    if ($number == Category::VSINGULAR) {
                        if ($person == Category::FIRSTPERSON) return 'en ' . $form[1] . '';
                        elseif ($person == Category::SECONDPERSON) return'et ' . $form[1] . '';
                        elseif ($person == Category::THIRDPERSON) return 'ei ' . $form[1] . '';
                        else echo "<br>Unnown Person " . $person;
                    } elseif ($number == Category::VPLURAL) {
                        if ($person == Category::FIRSTPERSON) return 'emme '. $form[1] . '';
                        elseif ($person == Category::SECONDPERSON) return 'ette ' . $form[1] . '';
                        elseif ($person == Category::THIRDPERSON) return 'eivät ' . $form[1] . '';
                        else echo "<br>Unnown Person " . $person;
                    } else {
                        echo "<br>Unnown Number " . $number;
                    }
                } else if ($tense == Category::PAST) {
                    if ($number == Category::VSINGULAR) {
                        if ($person == Category::FIRSTPERSON) {
                        	if (FinnishVerbInflector::hasBackVowels($form[7])) return 'en ' . $form[7] . 'yt';
                        	else return 'en ' . $form[7] . 'ut';
                        	//return 'en ' . $form[7] . 'Ut';
                        }
                        elseif ($person == Category::SECONDPERSON) {
                        	if (FinnishVerbInflector::hasBackVowels($form[7])) return 'et ' . $form[7] . 'yt';
                        	else return 'et ' . $form[7] . 'ut';
                        	//return 'et ' . $form[7] . 'Ut';
                        }
                        elseif ($person == Category::THIRDPERSON) {
                        	if (FinnishVerbInflector::hasBackVowels($form[7])) return 'ei ' . $form[7] . 'yt';
                        	else return 'ei ' . $form[7] . 'ut';
                        	//return 'ei ' . $form[7] . 'Ut';
                        }
                        else echo "<br>Unnown Person " . $person;
                    } elseif ($number == Category::VPLURAL) {
                        if ($person == Category::FIRSTPERSON) return 'emme ' . $form[7] . 'eet';
                        elseif ($person == Category::SECONDPERSON) return 'ette ' . $form[7] . 'eet';
                        elseif ($person == Category::THIRDPERSON) return 'eivät ' . $form[7] . 'eet';
                        else echo "<br>Unnown Person " . $person;
                    } else {
                        echo "<br>number not defined";
                    }
                } else {
                    echo "<br>Unnown Tense " . $tense;
                }
                
            } elseif ($modus == Category::CONDITIONAL) {
                if ($tense == Category::PRESENT) {
                    if ($number == Category::VSINGULAR) {
                        if ($person == Category::FIRSTPERSON) return 'en ' . $form[3] . 'isi';
                        elseif ($person == Category::SECONDPERSON) return 'et ' . $form[3] . 'isi';
                        elseif ($person == Category::THIRDPERSON) return 'ei ' . $form[3] . 'isi';
                        else echo "<br>Unnown Person " . $person;
                    } elseif ($number == Category::VPLURAL) {
                        if ($person == Category::FIRSTPERSON) return 'emme ' . $form[3] . 'isi';
                        elseif ($person == Category::SECONDPERSON) return 'ette ' . $form[3] . 'isi';
                        elseif ($person == Category::THIRDPERSON) return 'eivät ' . $form[3] . 'isi';
                        else echo "<br>Unnown Person " . $person;
                    } else {
                        echo "<br>Unnown Number " . $number;
                    }
                } else if ($tense == Category::PAST) {      // ei oikeastaan ole olemassa
                    echo "<br>Konditional past not exists in Finnish language";
                } else if ($tense == Category::FUTURE) {      // ei oikeastaan ole olemassa
                    echo "<br>Konditional future not exists in Finnish language";
                } else {
                    echo "<br>Unnown Tense " . $tense;
                }
                
            } elseif ($modus == Category::POTENTIAL) {
                
                if ($tense == Category::PRESENT) {
                    if ($number == Category::VSINGULAR) {
                        if ($person == Category::FIRSTPERSON) return 'en ' . $form[7] . 'e';
                        elseif ($person == Category::SECONDPERSON) return 'et ' . $form[7] . 'e';
                        elseif ($person == Category::THIRDPERSON) return 'ei ' . $form[7] . 'e';
                        else echo "<br>Unnown Person " . $person;
                    } elseif ($number == Category::VPLURAL) {
                        if ($person == Category::FIRSTPERSON) return 'emme ' . $form[7] . 'e';
                        elseif ($person == Category::SECONDPERSON) return 'ette ' . $form[7] . 'e';
                        elseif ($person == Category::THIRDPERSON) return 'eivät ' . $form[7] . 'e';
                        else echo "<br>Unnown Person " . $person;
                    } else {
                        echo "<br>Unnown Number " . $number;
                    }
                } else if ($tense == Category::PAST) {
                    echo "<br>Modus:potential with tense:past not exists in Finnish language";
                } else if ($tense == Category::FUTURE) {
                    echo "<br>tense:future not exists in Finnish language";
                } else {
                    echo "<br>Unnown Tense " . $tense;
                }
                
            } elseif ($modus == Category::IMPERATIVE) {
                if ($tense == Category::PRESENT) {
                    if ($number == Category::VSINGULAR) {
                        if ($person == Category::FIRSTPERSON) {
                            echo "<br>Modus:imperative with singular first person not exists in Finnish language";
                        } elseif ($person == Category::SECONDPERSON) return 'älä ' . $form[1] . '';
                        elseif ($person == Category::THIRDPERSON) {
                        	if (FinnishVerbInflector::hasBackVowels($form[4])) return 'älkään ' . $form[4] . 'kä';
                        	else return 'älkään ' . $form[4] . 'ko';
                        	//return 'älkään ' . $form[4] . 'kO';
                        }
                        else echo "<br>Unnown Person " . $person;
                    } elseif ($number == Category::VPLURAL) {
                        if ($person == Category::FIRSTPERSON) {
                        	if (FinnishVerbInflector::hasBackVowels($form[4])) return 'älkäämme ' . $form[4] . 'kä';
                        	else return 'älkäämme ' . $form[4] . 'ko';
                        	//return 'älkäämme ' . $form[4] . 'kO';
                        }
                        elseif ($person == Category::SECONDPERSON) {
                         	if (FinnishVerbInflector::hasBackVowels($form[4])) return 'älkää ' . $form[4] . 'kä';
                        	else return 'älkää ' . $form[4] . 'ko';
                       		//return 'älkää ' . $form[4] . 'kO';
                        }
                        elseif ($person == Category::THIRDPERSON) {
                        	if (FinnishVerbInflector::hasBackVowels($form[4])) return 'älkäät ' . $form[4] . 'kä';
                        	else return 'älkäät ' . $form[4] . 'ko';
                       		//return 'älkäät ' . $form[4] . 'kO';
                        }
                        else echo "<br>Unnown Person " . $person;
                    } else {
                        echo "<br>Unnown Number " . $number;
                    }
                } else if ($tense == Category::PAST) {
                    echo "<br>Modus:imperative with tense:past not exists in Finnish language";
                } else if ($tense == Category::FUTURE) {
                    echo "<br>Tense:future not exists in Finnish language";
                } else {
                    echo "<br>Unnown Tense " . $tense;
                }
            } else {
                echo "<br>Unnown Modus " . $modus;
            }
            
            
        }
        
    }
    */
    
    

    /*
    public static function generateEveryForm($wordforms) {
        
        $forms = explode('/',$wordforms);
       	 
        echo "<br><br>";
        echo "<br>Form0 " . $forms[0];
        echo "<br>Form1 " . $forms[1];
        echo "<br>Form2 " . $forms[2];
        echo "<br>Form3 " . $forms[3];
        echo "<br>Form4 " . $forms[4];
        echo "<br>Form5 " . $forms[5];
        echo "<br>Form6 " . $forms[6];
        echo "<br>Form7 " . $forms[7];
        echo "<br>Form8 " . $forms[8];
        echo "<br>Form9 " . $forms[9];
        echo "<br>Form10 " . $forms[10];
        
        
        echo "<br><br>Indikative";     
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VSINGULAR, Category::PRESENT, Category::INDIKATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Minä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VSINGULAR, Category::PRESENT, Category::INDIKATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Sinä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VSINGULAR, Category::PRESENT, Category::INDIKATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Hän " . $form;
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VPLURAL, Category::PRESENT, Category::INDIKATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Me " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VPLURAL, Category::PRESENT, Category::INDIKATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Te " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VPLURAL, Category::PRESENT, Category::INDIKATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>He " . $form;
        
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VSINGULAR, Category::PAST, Category::INDIKATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Minä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VSINGULAR, Category::PAST, Category::INDIKATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Sinä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VSINGULAR, Category::PAST, Category::INDIKATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Hän " . $form;
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VPLURAL, Category::PAST, Category::INDIKATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Me " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VPLURAL, Category::PAST, Category::INDIKATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Te " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VPLURAL, Category::PAST, Category::INDIKATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>He " . $form;
        
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VSINGULAR, Category::PRESENT, Category::INDIKATIVE, Category::NEGATION, $forms);
        echo "<br>Minä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VSINGULAR, Category::PRESENT, Category::INDIKATIVE,Category::NEGATION, $forms);
        echo "<br>Sinä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VSINGULAR, Category::PRESENT, Category::INDIKATIVE,Category::NEGATION, $forms);
        echo "<br>Hän " . $form;
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VPLURAL, Category::PRESENT, Category::INDIKATIVE,Category::NEGATION, $forms);
        echo "<br>Me " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VPLURAL, Category::PRESENT, Category::INDIKATIVE,Category::NEGATION, $forms);
        echo "<br>Te " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VPLURAL, Category::PRESENT, Category::INDIKATIVE,Category::NEGATION, $forms);
        echo "<br>He " . $form;
        
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VSINGULAR, Category::PAST, Category::INDIKATIVE,Category::NEGATION, $forms);
        echo "<br>Minä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VSINGULAR, Category::PAST, Category::INDIKATIVE,Category::NEGATION, $forms);
        echo "<br>Sinä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VSINGULAR, Category::PAST, Category::INDIKATIVE,Category::NEGATION, $forms);
        echo "<br>Hän " . $form;
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VPLURAL, Category::PAST, Category::INDIKATIVE,Category::NEGATION, $forms);
        echo "<br>Me " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VPLURAL, Category::PAST, Category::INDIKATIVE,Category::NEGATION, $forms);
        echo "<br>Te " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VPLURAL, Category::PAST, Category::INDIKATIVE,Category::NEGATION, $forms);
        echo "<br>He " . $form;
        
        
        echo "<br><br>Imperative";
        //$forms[] = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VSINGULAR, Category::PRESENT, Category::IMPERATIVE,Category::AFFIRMATIVE, $forms);
        //echo "<br>Minä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VSINGULAR, Category::PRESENT, Category::IMPERATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Sinä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VSINGULAR, Category::PRESENT, Category::IMPERATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Hän " . $form;
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VPLURAL, Category::PRESENT, Category::IMPERATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Me " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VPLURAL, Category::PRESENT, Category::IMPERATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Te " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VPLURAL, Category::PRESENT, Category::IMPERATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>He " . $form;
        
        /* 
        $forms[] = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VSINGULAR, Category::PAST, Category::IMPERATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Minä " . $form;
        $forms[] = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VSINGULAR, Category::PAST, Category::IMPERATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Sinä " . $form;
        $forms[] = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VSINGULAR, Category::PAST, Category::IMPERATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Hän " . $form;
        
        $forms[] = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VPLURAL, Category::PAST, Category::IMPERATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Me " . $form;
        $forms[] = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VPLURAL, Category::PAST, Category::IMPERATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>Te " . $form;
        $forms[] = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VPLURAL, Category::PAST, Category::IMPERATIVE,Category::AFFIRMATIVE, $forms);
        echo "<br>He " . $form;
        * /
        
        // $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VSINGULAR, Category::PRESENT, Category::IMPERATIVE,Category::NEGATION, $forms);
        // echo "<br>Minä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VSINGULAR, Category::PRESENT, Category::IMPERATIVE,Category::NEGATION, $forms);
        echo "<br>Sinä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VSINGULAR, Category::PRESENT, Category::IMPERATIVE,Category::NEGATION, $forms);
        echo "<br>Hän " . $form;
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VPLURAL, Category::PRESENT, Category::IMPERATIVE,Category::NEGATION, $forms);
        echo "<br>Me " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VPLURAL, Category::PRESENT, Category::IMPERATIVE,Category::NEGATION, $forms);
        echo "<br>Te " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VPLURAL, Category::PRESENT, Category::IMPERATIVE,Category::NEGATION, $forms);
        echo "<br>He " . $form;
        
        /*
        $forms[] = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VSINGULAR, Category::PAST, Category::IMPERATIVE,Category::NEGATION, $forms);
        echo "<br>Minä " . $form;
        $forms[] = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VSINGULAR, Category::PAST, Category::IMPERATIVE,Category::NEGATION, $forms);
        echo "<br>Sinä " . $form;
        $forms[] = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VSINGULAR, Category::PAST, Category::IMPERATIVE,Category::NEGATION, $forms);
        echo "<br>Hän " . $form;
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VPLURAL, Category::PAST, Category::IMPERATIVE,Category::NEGATION, $forms);
        echo "<br>Me " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VPLURAL, Category::PAST, Category::IMPERATIVE,Category::NEGATION, $forms);
        echo "<br>Te " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VPLURAL, Category::PAST, Category::IMPERATIVE,Category::NEGATION, $forms);
        echo "<br>He " . $form;
        * /
        
        
        echo "<br><br>Conditional";
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VSINGULAR, Category::PRESENT, Category::CONDITIONAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Minä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VSINGULAR, Category::PRESENT, Category::CONDITIONAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Sinä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VSINGULAR, Category::PRESENT, Category::CONDITIONAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Hän " . $form;
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VPLURAL, Category::PRESENT, Category::CONDITIONAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Me " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VPLURAL, Category::PRESENT, Category::CONDITIONAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Te " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VPLURAL, Category::PRESENT, Category::CONDITIONAL,Category::AFFIRMATIVE, $forms);
        echo "<br>He " . $form;
        
        
        /*
        $forms[] = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VSINGULAR, Category::PAST, Category::CONDITIONAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Minä " . $form;
        $forms[] = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VSINGULAR, Category::PAST, Category::CONDITIONAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Sinä " . $form;
        $forms[] = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VSINGULAR, Category::PAST, Category::CONDITIONAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Hän " . $form;
        
        $forms[] = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VPLURAL, Category::PAST, Category::CONDITIONAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Me " . $form;
        $forms[] = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VPLURAL, Category::PAST, Category::CONDITIONAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Te " . $form;
        $forms[] = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VPLURAL, Category::PAST, Category::CONDITIONAL,Category::AFFIRMATIVE, $forms);
        echo "<br>He " . $form;
        * /
        
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VSINGULAR, Category::PRESENT, Category::CONDITIONAL,Category::NEGATION, $forms);
        echo "<br>Minä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VSINGULAR, Category::PRESENT, Category::CONDITIONAL,Category::NEGATION, $forms);
        echo "<br>Sinä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VSINGULAR, Category::PRESENT, Category::CONDITIONAL,Category::NEGATION, $forms);
        echo "<br>Hän " . $form;
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VPLURAL, Category::PRESENT, Category::CONDITIONAL,Category::NEGATION, $forms);
        echo "<br>Me " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VPLURAL, Category::PRESENT, Category::CONDITIONAL,Category::NEGATION, $forms);
        echo "<br>Te " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VPLURAL, Category::PRESENT, Category::CONDITIONAL,Category::NEGATION, $forms);
        echo "<br>He " . $form;
        
        /*
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VSINGULAR, Category::PAST, Category::CONDITIONAL,Category::NEGATION, $forms);
        echo "<br>Minä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VSINGULAR, Category::PAST, Category::CONDITIONAL,Category::NEGATION, $forms);
        echo "<br>Sinä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VSINGULAR, Category::PAST, Category::CONDITIONAL,Category::NEGATION, $forms);
        echo "<br>Hän " . $form;
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VPLURAL, Category::PAST, Category::CONDITIONAL,Category::NEGATION, $forms);
        echo "<br>Me " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VPLURAL, Category::PAST, Category::CONDITIONAL,Category::NEGATION, $forms);
        echo "<br>Te " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VPLURAL, Category::PAST, Category::CONDITIONAL,Category::NEGATION, $forms);
        echo "<br>He " . $form;
        * /
        
                
        echo "<br><br>Potential";
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VSINGULAR, Category::PRESENT, Category::POTENTIAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Minä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VSINGULAR, Category::PRESENT, Category::POTENTIAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Sinä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VSINGULAR, Category::PRESENT, Category::POTENTIAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Hän " . $form;
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VPLURAL, Category::PRESENT, Category::POTENTIAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Me " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VPLURAL, Category::PRESENT, Category::POTENTIAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Te " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VPLURAL, Category::PRESENT, Category::POTENTIAL,Category::AFFIRMATIVE, $forms);
        echo "<br>He " . $form;
        
        
        /*
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VSINGULAR, Category::PAST, Category::POTENTIAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Minä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VSINGULAR, Category::PAST, Category::POTENTIAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Sinä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VSINGULAR, Category::PAST, Category::POTENTIAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Hän " . $form;
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VPLURAL, Category::PAST, Category::POTENTIAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Me " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VPLURAL, Category::PAST, Category::POTENTIAL,Category::AFFIRMATIVE, $forms);
        echo "<br>Te " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VPLURAL, Category::PAST, Category::POTENTIAL,Category::AFFIRMATIVE, $forms);
        echo "<br>He " . $form;
        * / 
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VSINGULAR, Category::PRESENT, Category::POTENTIAL,Category::NEGATION, $forms);
        echo "<br>Minä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VSINGULAR, Category::PRESENT, Category::POTENTIAL,Category::NEGATION, $forms);
        echo "<br>Sinä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VSINGULAR, Category::PRESENT, Category::POTENTIAL,Category::NEGATION, $forms);
        echo "<br>Hän " . $form;
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VPLURAL, Category::PRESENT, Category::POTENTIAL,Category::NEGATION, $forms);
        echo "<br>Me " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VPLURAL, Category::PRESENT, Category::POTENTIAL,Category::NEGATION, $forms);
        echo "<br>Te " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VPLURAL, Category::PRESENT, Category::POTENTIAL,Category::NEGATION, $forms);
        echo "<br>He " . $form;
        
        
        /*
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VSINGULAR, Category::PAST, Category::POTENTIAL,Category::NEGATION, $forms);
        echo "<br>Minä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VSINGULAR, Category::PAST, Category::POTENTIAL,Category::NEGATION, $forms);
        echo "<br>Sinä " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VSINGULAR, Category::PAST, Category::POTENTIAL,Category::NEGATION, $forms);
        echo "<br>Hän " . $form;
        
        $form = FinnishVerbInflector::getFormPrivate(Category::FIRSTPERSON, Category::VPLURAL, Category::PAST, Category::POTENTIAL,Category::NEGATION, $forms);
        echo "<br>Me " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::SECONDPERSON, Category::VPLURAL, Category::PAST, Category::POTENTIAL,Category::NEGATION, $forms);
        echo "<br>Te " . $form;
        $form = FinnishVerbInflector::getFormPrivate(Category::THIRDPERSON, Category::VPLURAL, Category::PAST, Category::POTENTIAL,Category::NEGATION, $forms);
        echo "<br>He " . $form;
        * / 
        
    }
    */
    
    static function analyseForm($word) {
        
    }
    
}

    




?>