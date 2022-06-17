<?php


include_once('./modules/worder/_classes/category.class.php');


// tämä on todennäkäisesti vanha toteutus
class FinnishVerbInflector {
    
    
    
    
    private static function hasBackVowels($str) {
    	for ($i = 0; $i < strlen($str); $i++){
    		if (($str[$i] == 'y') || ($str[$i] == 'ä') || ($str[$i] == 'ä'));
    	}
    	return 0;
    }
    
    
    public static function getKieltomuodot() {
    	
    }
    
    
    public static function getForms($lemma) {
    	
    	if ($lemma == "ei") getKieltomuodot;
    	
    	
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
    
    
    
    private static function getFormPrivate($person, $number, $tense, $modus, $negation, $form) {
        
        
    	
        
    }
    
    
    
    
    
    static function analyseForm($word) {
        
    }
    
}

    




?>