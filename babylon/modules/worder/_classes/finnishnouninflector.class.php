<?php


include_once('./modules/worder/_classes/category.class.php');
include_once('./modules/worder/_classes/wordform.class.php');


class FinnishNounInflector {
    
    
	
	
    private static function toFrontVowel($str) {

    	$backvowelfound = false;
    	$frontvowelfound = false;

    	//$str = utf8_encode($str);
    	//$str = utf8_decode($str);
    	//echo "<br>hasFrontVowel - " . $str;
    	for ($i = 0; $i < strlen($str); $i++){
    		//echo "<br> --- " . mb_substr($str,$i,1); 
    		//echo "<br> --- " . $str[$i]; 
    		$char = mb_substr($str,$i,1,'utf-8');
    		if (($char == 'a') || ($char == 'o') || ($char == 'u')) {
    			//echo "<br>-- back found";
    			$backvowelfound = true;
    		}
    		
    		if (($char == 'y') || ($char == 'ä') || ($char == 'ö')) {
    			//echo "<br>-- front found";
    			$frontvowelfound = true;
    		}
    		
    		if (($char == ':') || ($char == '-')) {		// yhdyssana väli ja väliviiva katkaisee
				$backvowelfound = false;
				$frontvowelfound = false;
       		}
    	}
    	if ($frontvowelfound) return 1;
    	if (!$backvowelfound) return 1;
    	return 0;
    }
    
    
    
    public static function getWordForms($wordID, $lemma, $formstr, $inflectionclassstr) {
    
    	$allforms = array();
    
    	$inflectionclassarray = explode('-',$inflectionclassstr);
    	$formID = -2;
    	//echo "<br>first - " . $inflectionclassarray[0];
    	$inflectionclass = $inflectionclassarray[0];
    	 
    	$identifiers = array(Category::NOMINATIVE, Category::SINGULAR);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    
    	$identifiers = array(Category::NOMINATIVE, Category::PLURAL);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    
    	 
    	$identifiers = array(Category::GENITIVE, Category::SINGULAR);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::GENITIVE, Category::PLURAL);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	 
    	$identifiers = array(Category::PARTITIVE, Category::SINGULAR);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::PARTITIVE, Category::PLURAL);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	 
    	//$identifiers = array(Category::ACCUSATIVE, Category::SINGULAR);
    	//$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers);
    	//$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers);
    	 
    	$identifiers = array(Category::INESSIVE, Category::SINGULAR);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::INESSIVE, Category::PLURAL);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    
    	 
    	$identifiers = array(Category::ELATIVE, Category::SINGULAR);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::ELATIVE, Category::PLURAL);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    
    	 
    	$identifiers = array(Category::ILLATIVE, Category::SINGULAR);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    
    	$identifiers = array(Category::ILLATIVE, Category::PLURAL);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    
    	 
    	$identifiers = array(Category::ADESSIVE, Category::SINGULAR);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    
    	$identifiers = array(Category::ADESSIVE, Category::PLURAL);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	 
    	$identifiers = array(Category::ABLATIVE, Category::SINGULAR);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    
    	$identifiers = array(Category::ABLATIVE, Category::PLURAL);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    
    	 
    	$identifiers = array(Category::ALLATIVE, Category::SINGULAR);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::ALLATIVE, Category::PLURAL);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    
    	 
    	$identifiers = array(Category::ESSIVE, Category::SINGULAR);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::ESSIVE, Category::PLURAL);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	 
    	$identifiers = array(Category::TRANSLATIVE, Category::SINGULAR);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::TRANSLATIVE, Category::PLURAL);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    
    	 
    	$identifiers = array(Category::ABESSIVE, Category::SINGULAR);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    	 
    	$identifiers = array(Category::ABESSIVE, Category::PLURAL);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    
    	 
    	$identifiers = array(Category::INSTRUCTIVE, Category::PLURAL);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    
    	// tämä ottaa oikeastaan vielä possessiivin
    	$identifiers = array(Category::COMITATIVE, Category::PLURAL);
    	$wordform = FinnishNounInflector::getFormWithArrayForForms($formstr, $identifiers, $inflectionclass, $formID);
    	$allforms[] = new WordForm($wordID, $lemma, $wordform, $identifiers, $formID);
    
    	return $allforms;
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
    	$form = FinnishNounInflector::getFormPrivate($person, $number, $tense, $modus, $polarity, $wforms);
    	return $form;
    }
    
    

    public static function getFormWithArrayForForms($forms, $wordforms, $inflectionclass, &$formID) {
    	 
    	$number = Category::SINGULAR;
    	$case = Category::NOMINATIVE;
    	$possessive = Category::NOPOSSESSIVE;
    	
    	foreach ($wordforms as $index => $value) {
    		switch ($value) {
    			case Category::SINGULAR: $number = Category::SINGULAR; break;
    			case Category::PLURAL: $number = Category::PLURAL; break;
    
    			case Category::NOMINATIVE: $case = Category::NOMINATIVE; break;
    			case Category::ACCUSATIVE: $case = Category::ACCUSATIVE; break;
    			case Category::GENITIVE: $case = Category::GENITIVE; break;
    			case Category::DATIVE: $case = Category::DATIVE; break;
    			case Category::INSTRUMENTAL: $case = Category::INSTRUMENTAL; break;
    			
    			case Category::PARTITIVE: $case = Category::PARTITIVE; break;
    			case Category::INESSIVE: $case = Category::INESSIVE; break;
    			case Category::ELATIVE: $case = Category::ELATIVE; break;
    			case Category::ILLATIVE: $case = Category::ILLATIVE; break;
    			case Category::ADESSIVE: $case = Category::ADESSIVE; break;
    			
    			case Category::ABLATIVE: $case = Category::ABLATIVE; break;
    			case Category::ALLATIVE: $case = Category::ALLATIVE; break;
    			case Category::ESSIVE: $case = Category::ESSIVE; break;
    			case Category::TRANSLATIVE: $case = Category::TRANSLATIVE; break;
    			case Category::INSTRUCTIVE: $case = Category::INSTRUCTIVE; break;
    			 
    			case Category::ABESSIVE: $case = Category::ABESSIVE; break;
    			case Category::COMITATIVE: $case = Category::COMITATIVE; break;
    			
    			case Category::NOPOSSESSIVE: $possessive = Category::NOPOSSESSIVE; break;
    			case Category::SG1: $possessive = Category::SG1; break;
    			case Category::SG2: $possessive = Category::SG2; break;
    			case Category::SG3: $possessive = Category::SG3; break;
    			case Category::PL1: $possessive = Category::PL1; break;
    			case Category::PL2: $possessive = Category::PL2; break;
    			case Category::PL3: $possessive = Category::PL3; break;
    		}
    	}
    	 
    	$wforms = explode('/',$forms);
    	//echo "<br>wordformcount - " . count($wforms);
    	//print_r($wforms);
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
    	$form = FinnishNounInflector::getBaseFormsPrivate($number, $case, $possessive, $wforms, $inflectionclass, $formID);
    	return $form;
    }
    
    
    
    
    private static function getBaseFormsPrivate($number, $case, $possessive, $form, $inflectionclass, &$formID) {
    
    	//echo "<br>" . $person . ", " . $number . ", " . $tense . ", " . $modus . ", " . $negation;
    	$str = "";
    	//echo "<br>Inflectionclass - " . $inflectionclass;
    	if ($case == Category::NOMINATIVE) {				// ok	
    		if ($number == Category::SINGULAR) {			// ok
				$formID = 0;
    			$str = $form[0];	
    		} elseif ($number == Category::PLURAL) {		// ok
 				$formID = 1;
    			$str = $form[1] . "t";
    		}
     	} elseif ($case == Category::GENITIVE) {			// ok	
    		if ($number == Category::SINGULAR) {			// ok
 				$formID = 1;
    			$str = $form[1] . "n";
    		} elseif ($number == Category::PLURAL) {		// ok
    			
    			$i = intval($inflectionclass);
    			switch ($i) {
    				case ($i == 1 || $i == 2 || $i == 4 || $i == 8 || $i == 9):
 						$formID = 4;
    					$str = $form[4] . "jen";
    					break;
    				case ($i == 3 || $i == 12 || $i == 13 || $i == 14 || $i == 15 || $i == 17 || $i == 18 || $i == 19 || $i == 20 || $i == 21 || $i == 22 || $i == 41 || $i == 43 || $i == 44 || $i == 47 || $i == 48 || $i == 49 || $i == 50):
 						$formID = 4;
    					$str = $form[4] . "iden";
    					break;
    				case ($i == 5 || $i == 6 || $i == 7 || $i == 10 || $i == 11 || $i == 16 || $i == 23 || $i == 24 || $i == 25 || $i == 26 || $i == 27 || $i == 28 || $i == 29 || $i == 30 || $i == 31 || $i == 32 || $i == 33 || $i == 34 || $i == 35 || $i == 36 || $i == 37 || $i == 38 || $i == 39 || $i == 40 || $i == 42 || $i == 45 || $i == 46 || $i == 50):
 						$formID = 4;
    					$str = $form[4] . "ien";
    					break;
    				default:
 						$formID = 4;
    					$str = $form[4] . "-----";
    			}
    		}
    		
    	} elseif ($case == Category::PARTITIVE) {			// ok
    		if ($number == Category::SINGULAR) {			// ok
    			$i = intval($inflectionclass);
    			switch ($i) {
    				case ($i == 1 || $i == 2 || $i == 4 || $i == 5 || $i == 6 || $i == 7 || $i == 8 || $i == 9 || $i == 10 || $i == 11 || $i == 12 || $i == 13 || $i == 14 || $i == 15 || $i == 16 || $i == 50):
 						$formID = 2;
    					$str = $form[2] . "_A_";
    					break;
    				case ($i == 3 || $i == 17 || $i == 18 || $i == 19 || $i == 20 || $i == 21 || $i == 22 || $i == 23 || $i == 24 || $i == 25 || $i == 26 || $i == 27 || $i == 28 || $i == 29 || $i == 30 || $i == 31 || $i == 32 || $i == 33 || $i == 34 || $i == 35 || $i == 36 || $i == 37 || $i == 38 || $i == 39 || $i == 40 || $i == 41 || $i == 42 || $i == 43 || $i == 44 || $i == 45 || $i == 46 || $i == 47 || $i == 48 || $i == 49):
 						$formID = 2;
    					$str = $form[2] . "t_A_";
    					break;
    				default:
 						$formID = 2;
    					$str = $form[2] . "-----";
    			}
    		} elseif ($number == Category::PLURAL) {		// ok
    			$i = intval($inflectionclass);
    			switch ($i) {
    				case ($i == 3 || $i == 12 || $i == 13 || $i == 15 || $i == 17 || $i == 18 || $i == 19 || $i == 20 || $i == 21 || $i == 22 || $i == 41 || $i == 43 || $i == 44 || $i == 47 || $i == 48 || $i == 49):
 						$formID = 5;
    					$str = $form[5] . "it_A_";
    					break;
    				case ($i == 2 || $i == 1 || $i == 4 || $i == 5 || $i == 6 || $i == 8 || $i == 9|| $i == 14 || $i == 50):
 						$formID = 5;
    					$str = $form[5] . "j_A_";
    					break;
    				case ($i == 7 || $i == 10 || $i == 11 || $i == 16 || $i == 23 || $i == 24 || $i == 25 || $i == 26 || $i == 27 || $i == 28 || $i == 29 || $i == 30 || $i == 31 || $i == 32 || $i == 33 || $i == 34 || $i == 35 || $i == 36 || $i == 37 || $i == 38 || $i == 39 || $i == 40 || $i == 42 || $i == 45 || $i == 46):
 						$formID = 5;
    					$str = $form[5] . "i_A_";
    					break;
    				default:
 						$formID = 0;
    					$str = $form[0] . "-----";
    			}
    		}
    	} elseif ($case == Category::ACCUSATIVE) {			// akkusatiivi toistaiseksi ei käytässä (mietinnässä miten 
    														// tämä olisi kieliopin kannalta järkevin esittää/mallintaa)
    		if ($number == Category::SINGULAR) {
 				$formID = 0;
    			$str = $form[0] . "n";
    		} elseif ($number == Category::PLURAL) {
 				$formID = 0;
    			$str = $form[0] . "ja";
    		}
    	} elseif ($case == Category::INESSIVE) {
    		if ($number == Category::SINGULAR) {			// ok
 				$formID = 1;
    			$str = $form[1] . "ss_A_";
    		} elseif ($number == Category::PLURAL) {		// ok
 				$formID = 6;
    			$str = $form[6] . "iss_A_";
    		}
		} elseif ($case == Category::ELATIVE) {
    		if ($number == Category::SINGULAR) {			// ok
 				$formID = 1;
    			$str = $form[1] . "st_A_";
    		} elseif ($number == Category::PLURAL) {		// ok
 				$formID = 6;
    			$str = $form[6] . "ist_A_";
    		}
		} elseif ($case == Category::ILLATIVE) {
    		if ($number == Category::SINGULAR) {			// OK
 				$formID = 3;
 				//$lastchar = substr($form[3], -1);							// HUOM: Tämä ottaa viimeisen kirjaimen eri muodosta kuin
    			
 				$lastchar = mb_substr($form[3], -1,1, "UTF-8");							// HUOM: Tämä ottaa viimeisen kirjaimen eri muodosta kuin
 				
 				// taivutusluokka 18 muoto puuh --> un, pääh -> än
    			if ($lastchar == 'h') $lastchar = mb_substr($form[3], -2,1, "UTF-8");	
    			
    																		// mihin pääte lisätään, aiheutti ongelmaa maahan-tyypissä, taivutusluokka 18
    			//echo "<br>Lastchar - " . $lastchar;
    			if ($lastchar == 'o') $str = $form[3] . "on";
    			elseif ($lastchar == 'u') $str = $form[3] . "un";
    			elseif ($lastchar == 'a') $str = $form[3] . "an";
    			elseif ($lastchar == 'o') $str = $form[3] . "on";
    			elseif ($lastchar == 'y') $str = $form[3] . "yn";
    			elseif ($lastchar == 'o') $str = $form[3] . "on";
    			elseif ($lastchar == 'i') $str = $form[3] . "in";
    			elseif ($lastchar == 'a') $str = $form[3] . "an";
    			elseif ($lastchar == 'ä') $str = $form[3] . "än";
    			elseif ($lastchar == 'e') $str = $form[3] . "en";
    			else {
	    			$lastchar = substr($form[0], -1);							// HUOM: Tämä ottaa viimeisen kirjaimen eri muodosta kuin
    				if ($lastchar == 'o') $str = $form[3] . "on";
	    			elseif ($lastchar == 'u') $str = $form[3] . "un";
	    			elseif ($lastchar == 'a') $str = $form[3] . "an";
	    			elseif ($lastchar == 'o') $str = $form[3] . "on";
	    			elseif ($lastchar == 'y') $str = $form[3] . "yn";
	    			elseif ($lastchar == 'o') $str = $form[3] . "on";
	    			elseif ($lastchar == 'i') $str = $form[3] . "in";
	    			elseif ($lastchar == 'a') $str = $form[3] . "an";
	    			elseif ($lastchar == 'ä') $str = $form[3] . "än";
	    			elseif ($lastchar == 'ö') $str = $form[3] . "ön";
	    			elseif ($lastchar == 'e') $str = $form[3] . "en";
	    			else {
	    				
	    				//echo " - " . mb_detect_encoding($form[3]);
	    				//echo " - " . mb_detect_encoding('ä');
	    				 
	    				//echo "<br>Lastchar - '" . $lastchar . "'";
	    				//echo "<br>Lastchar - '" . utf8_encode($lastchar) . "'";
	    				//echo "<br>Lastchar - '" . utf8_decode($lastchar) . "'";

	    				//echo "<br>Lastchar - '" . (int)$lastchar . "'";
	    				 
	    				//if ($lastchar == utf8_decode('ä')) {
	    				if (strcmp($lastchar,'ä')) {
	    					$str = $form[3] . "än";
	    					//echo "<br>Match ää - " . $form[3];
	    				} else {
	    					//echo "<br>No match äää - " . $form[3];
	    				}
	    				
	    				
	    				if (strcmp($lastchar,'ö')) {
	    					$str = $form[3] . "ön";
	    					//echo "<br>Match öö - " . $form[3];
	    				} else {
	    					//echo "<br>No match ööö - " . $form[3];
	    				} 
	    				
	    				//echo "<br>intval - " . intval($lastchar);
	    				 
	    				//echo "<br>Finnishnouninflector - unknown word end. - " . $lastchar;
	    				//die();
	    			}
    			}
    		} elseif ($number == Category::PLURAL) {
    			$i = intval($inflectionclass);
    			switch ($i) {
    				case ($i == 1 || $i == 2 || $i == 3 || $i == 20 || $i == 4 || $i == 5 || $i == 6 || $i == 8 || $i == 9 || $i == 12 || $i == 13 || $i == 14 || $i == 15 || $i == 18 || $i == 19 || $i == 21 || $i == 22):
    					//echo "<br>Ovi inflcass - " . $inflectionclass;
 						$formID = 5;
    					$str = $form[5] . "ihin";
    					break;
    				case ($i == 7 || $i == 10 || $i == 11 || $i == 16 || $i == 23 || $i == 24 || $i == 25 || $i == 26 || $i == 27 || $i == 28 || $i == 29 || $i == 30 || $i == 31 || $i == 32 || $i == 33 || $i == 34 || $i == 35 || $i == 36 || $i == 37 || $i == 38 || $i == 39 || $i == 40 || $i == 42 || $i == 45 || $i == 46):
 						$formID = 5;
    					$str = $form[5] . "iin";
    					break;
    				case ($i == 17 || $i == 41 || $i == 43 || $i == 44 || $i == 47 || $i == 48 || $i == 49):
 						$formID = 5;
    					$str = $form[5] . "isiin";
    					break;
    				default:
 						$formID = 5;
    					$str = $form[5] . "-----";
    			}
    			/*
    			switch ($i) {
    				case (1 || 2 || 3 || 4 || 5 || 6 || 8 || 9 || 12 || 13 || 14 || 15 || 18 || 19 || 20 || 21 || 22):
    					echo "<br>Ovi inflcass - " . $inflectionclass;
    					$str = $form[5] . "ihin";
    					break;
    				case (7 || 10 || 11 || 16 || 23 || 24 || 25 || 26 || 27 || 28 || 29 || 30 || 31 || 32 || 33 || 34 || 35 || 36 || 37 || 38 || 39 || 40 || 42 || 45 || 46):
    					$str = $form[5] . "iin";
    					break;
    				case (17 || 41 || 43 || 44 || 47 || 48 || 49):
    					$str = $form[5] . "isiin";
    					break;
    				default:
    					$str = $form[5] . "-----";
    			}
    			*/
    		}
		} elseif ($case == Category::ADESSIVE) {
    		if ($number == Category::SINGULAR) {
 				$formID = 1;
    			$str = $form[1] . "ll_A_";						// ok
    		} elseif ($number == Category::PLURAL) {
 				$formID = 6;
    			$str = $form[6] . "ill_A_";						// ok
    		}
		} elseif ($case == Category::ABLATIVE) {
			if ($number == Category::SINGULAR) {
 				$formID = 1;
				$str = $form[1] . "lt_A_";						// ok
    		} elseif ($number == Category::PLURAL) {
 				$formID = 6;
    			$str = $form[6] . "ilt_A_";						// ok
    		}
		} elseif ($case == Category::ALLATIVE) {
		if ($number == Category::SINGULAR) {
 				$formID = 1;
				$str = $form[1] . "lle";						// ok
    		} elseif ($number == Category::PLURAL) {
 				$formID = 6;
    			$str = $form[6] . "ille";						// ok
    		}
		} elseif ($case == Category::ESSIVE) {
			if ($number == Category::SINGULAR) {				// ok
 				$formID = 7;
				$str = $form[7] . "n_A_";
    		} elseif ($number == Category::PLURAL) {			// ok
 				$formID = 5;
    			$str = $form[5] . "in_A_";
    		}
		} elseif ($case == Category::TRANSLATIVE) {
		if ($number == Category::SINGULAR) {					// ok
 				$formID = 1;
				$str = $form[1] . "ksi";
    		} elseif ($number == Category::PLURAL) {			// ok
 				$formID = 6;
    			$str = $form[6] . "iksi";
    		}
		} elseif ($case == Category::ABESSIVE) {
		if ($number == Category::SINGULAR) {					// ok
 				$formID = 1;
				$str = $form[1] . "tt_A_";
    		} elseif ($number == Category::PLURAL) {			// ok
 				$formID = 6;
    			$str = $form[6] . "itt_A_";
    		}
		} elseif ($case == Category::INSTRUCTIVE) {
    		if ($number == Category::SINGULAR) {
 				$formID = 0;
    			$str = $form[0] . "---";						// tällaista muotoa ei ole, pitäisi ehkä heittää virhe tai palauttaa tyhjä
    		} elseif ($number == Category::PLURAL) {
 				$formID = 6;
    			$str = $form[6] . "in";							// ok
    		}
		} elseif ($case == Category::COMITATIVE) {
		if ($number == Category::SINGULAR) {
 				$formID = 0;
				$str = $form[0] . "---";						// tällaista muotoa ei ole, pitäisi ehkä heittää virhe tai palauttaa tyhjä
    		} elseif ($number == Category::PLURAL) {
 				$formID = 5;
    			$str = $form[5] . "ine";						// ok, tässä on ongelma koska substantiivien osalta esiintyy vain possessiivin kanssa
																// palataan tähän myähemmin possessiivien yhteydessä jos tarpeen. Tämä on aika harvinainen
    		}
		} 
		
		
 
    	if ($possessive == Category::SG1) {
    		$str = $str . "ni";
    	} elseif ($possessive == Category::SG2) {
    		$str = $str . "si";
    	} elseif ($possessive == Category::SG3) {
    		$str = $str . "nsa";
    	} elseif ($possessive == Category::PL1) {
    		$str = $str . "mme";
    	} elseif ($possessive == Category::PL2) {
    		$str = $str . "nne";
    	} elseif ($possessive == Category::PL3) {
    		$str = $str . "nsa";
    	}
    	
    	
    	$str2 = $str;
    	if (FinnishNounInflector::toFrontVowel($str2)) {
    		$str = str_replace("_A_", "ä", $str);
    		$str = str_replace("_O_", "ö", $str);
    	} else {
    		$str = str_replace("_A_", "a", $str);
    		$str = str_replace("_O_", "o", $str);
    	}
    	$str = str_replace(":", "", $str);
    	 
		return $str;    	
    }
    
  
    
}

    




?>