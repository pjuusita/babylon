<?php

// Grammatical categories


// Categorys käytetään sanojen agreementin määrittelyyn
// Kun nämä määrittelyt otetaan huomioon, jotkin sanat karsiutuvat pois valikoimasta
// esim. 'sinä' jää pois, jos personiksi on valittu ensimmäinen persoona


class Category {
    
    
	// Nämä on testausta varten, vakioita rulejen tekoon
	// Varmaan osa näistä pitäisi siirtää tietokantaan
	
	const FEATURE_PERSON = 20;
	const FEATURE_PERSON_1ST = 21;
	const FEATURE_PERSON_2ND = 22;
	const FEATURE_PERSON_3RD = 23;
	
	const FEATURE_TEMPUS = 24;
	const FEATURE_TEMPUS_PRESENT = 27;
	const FEATURE_TEMPUS_PAST = 28;
	
	const FEATURE_MODUS = 25;
	const FEATURE_MODUS_INDICATIVE = 30;
	const FEATURE_MODUS_IMPERATIVE = 31;
	const FEATURE_MODUS_CONDITIONAL = 32;
	
	const FEATURE_NUMBER = 2;
	const FEATURE_NUMBER_SINGULAR = 5;
	const FEATURE_NUMBER_PLURAL = 6;
	
	const FEATURE_POLARITY = 67;
	const FEATURE_POLARITY_AFFIRMATIVE = 116;
	const FEATURE_POLARITY_NEGATION = 117;
	
	const FEATURE_COMPARISON = 59;
	
	const FEATURE_CASE_NOMINATIVE = 13;
	const FEATURE_CASE_ADESSIVE = 51;
	
	const ARGUMENT_AGENT = 7;
	const ARGUMENT_SIZE = 57;
	const ARGUMENT_POSSESSOR = 3;
	const ARGUMENT_LOCATION = 14;
	const ARGUMENT_COLOR = 4;
	const ARGUMENT_DETERMINER = 5;
	
	const FEATURE_CASE = 1;
	const NUMBER_FEATURE = 2;
	
	const POSSESSIVE_NONE = 122;
	
	const COMPONENT_SIZE = 28;
	const COMPONENT_HUMANLIKE = 9;
	const COMPONENT_LEGGED = 24;
	
	const CONCEPT_BOY = 55823;
	const CONCEPT_RUN = 110037;
	const CONCEPT_SMALL = 89890;
	
	const NOUN = 1;
	const VERB = 2;
	const ADJ = 3;
	const PRON = 5;
	
	
	
    // tarvitaan, että pystytään rajoittamaan argumenttien tyyppiä
    //const PATH = 43;
    //const NEARNESS = 45;   // ? luona, lähiställä, nearness
    
    
    // CASE
    
    //const NOMINATIVE = 43;
    
    // owning case
    //const GENETIVE = 44;
    
    // PAIKALLISSIJAT, LOCATION
    //const ON_SOMEWHERE = 3;            // -llä/-lle
    
    //  
    //
    //const FROM_SOMEWHERE = 4;          // talolta
    
    //
    // esim. kentälle, 
    //const TO_SOMEWHERE = 5;
    
    // 
    // esim. talossa vs. kentällä, laatikossa, katossa
    //const IN_SOMEWHERE = 6;
    
    // ulkopaikallissijat
    
    // sijaita jonkin päällä, jollain pinnalla, tai jonkin lähellä (talolla)
    // -lla/-llä, tämä on suomen kielen adessiivi
    //const ON_SOMETHING = 7;
    
    // 
    //const FROM_SOMETHING = 8;
    
    //public $name;
    
    //public $features = array();
    
    
    // Category::addFeature('Gender');
    //const FEMININE = 'F';
    //const MASCULINE = 'M';
    //const NEUTER = 'N';
    
    // public static $PLUPERFECT = 135;    // olin juossut, we had arrived
    
    // Aspect
    //public static $PERFECT = 106;       // olen lukenut
    //public static $IMPERFECT = 107;     // olin lukenut
    
    // Otetaan suomen passiivi toistaiseksi pois käytästä, koska on
    // epäselvää miten se käännetään muille kielille
    //public static $PASSIVE = 'PAS';
    //public static $ACTIVE = 'ACT';        
   
    // käytetään ainakin Rooleissa: Passive, negation arvoina
   	const POLARITY = 67;
    const NEGATION = 117;
    const AFFIRMATIVE = 116;
     
    
    // Person
    const PERSON = 20;
    const FIRSTPERSON = 21;
    const SECONDPERSON = 22;
    const THIRDPERSON = 23;
    
    // Number
  	const NUMBER = 2;
  	const SINGULAR = 5;
    const PLURAL = 6;
	//const SG = 5;
    //const PL = 6;
    
    const VNUMBER = 1158;
    const VSINGULAR = 1159;
    const VPLURAL = 1160;
    //const VSG = 1150;
    //const VPL = 1160;
    
    
    // Tempus
    const TEMPUS = 24;
  	const PRESENT = 27;       // juoksen, I arrive
    const PAST = 28;          // juoksin, I arrived
    const PERFECT = 121;          // juossut, juosseet
    const FUTURE = 29;        // tulen juoksemaan?  I arrive
      
    
    // Modus / mood
    const MODUS = 25;            // suomi
    const INDIKATIVE = 30;            // suomi
    const IMPERATIVE = 31;            // suomi 
    const CONDITIONAL = 32;         // suomi
    const POTENTIAL = 33;           // suomi
    const INTERROGATIVE = 34;
    const SUBJUNKTIVE = 35;
    const INJUNCTIVE = 36;
    const OPTATIVE = 37;
    
    // Voice
    const  VOICE = 118;
    const  PASSIVE = 119;
    const  ACTIVE = 120;
      
    // Infinitiivit
    const  INFINITIVE = 194;
    const  A_INFINITIVE = 195;
    
    
    // Cases
    const NOUNCASE = 1;          	// suomi
    const NOMINATIVE = 13;          	// suomi
    const ACCUSATIVE = 14;          	// suomi
    const GENITIVE = 15;            	// suomi
    const DATIVE = 16;         			
    const INSTRUMENTAL = 17;           
    const PREPOSITIONAL = 18;
    const PARTITIVE = 19;
    const INESSIVE = 48;				// suomi
    const ELATIVE = 49;					// suomi
    const ILLATIVE = 50;				// suomi
    const ADESSIVE = 51;				// suomi
    const ABLATIVE = 52;				// suomi
    const ALLATIVE = 53;				// suomi
    const ESSIVE = 54;					// suomi
    const TRANSLATIVE = 55;				// suomi
    const INSTRUCTIVE = 56;				// suomi
    const ABESSIVE = 57;				// suomi
    const COMITATIVE = 58;				// suomi
    
    // Possessive
    const POSSESSIVE = 63;          	// suomi
    const NONE = 122;          	// suomi
 	const NOPOSSESSIVE= 122;          	// suomi
    const SG1 = 77;          	// suomi
    const SG2 = 78;          	// suomi
    const SG3 = 79;          	// suomi
    const PL1 = 80;          	// suomi
    const PL2 = 81;          	// suomi
    const PL3 = 82;          	// suomi
    
    
    // Modality
    const MAY = 117;
    const MUST = 118;
    //const OUHGT = 119;             // pitäisi
    const WILL = 120;
    const SHALL = 121;
    //const NEED = 122;
    //const ARE = 123;
    const MIGHT = 124;
    const COULD = 125;
    const WOULD = 126;
    const SHOULD = 128;
    const CAN = 129;
    
    
    const RULETYPE_ANALYSEANDGENERATE = 1;
    const RULETYPE_ONLYANALYSE = 2;
    const RULETYPE_ONLYGENERATE = 3;
    
    
    /*
    Category::addFeature('Case');
    public static('Case','Nominative',1);
    public static('Case','Accusative',0);
    public static('Case','Genetive',0);
    public static('Case','Dative',0);
    public static('Case','Instrumental',0);
    */
    
    
    /*
    static function getFeature($name) {
        echo "<br>getFeature - " . $name;
        if (isset(Feature::$features[$name])) {
            return Feature::$features[$name];
        } else {
            $feature = new Feature($name);
            self::$features[$name] = $feature;
            return $feature;
        }
    }
    
    static function addFeature($name) {
        echo "<br>getFeature - " . $name;
        if (isset(Feature::$features[$name])) {
            return Feature::$features[$name];
        } else {
            $feature = new Feature($name);
            self::$features[$name] = $feature;
            return $feature;
        }
    }
    */
    
    static function addFeatureValue($featurename, $value, $probability = 1) {
        
    }
    
    function __construct($name) {
        $this->name = $name;
    }
    
    function getName() {
        return $this->name;
    }
    
    function printSingleFeature($category) {
        
    }
    
    


    public static function getRuleTypes() {
    	$ruletypes = array();
    	$ruletypes[Category::RULETYPE_ANALYSEANDGENERATE] = "Analyse and Generate";
    	$ruletypes[Category::RULETYPE_ONLYANALYSE] = "Only Analyse";	// ??
    	$ruletypes[Category::RULETYPE_ONLYGENERATE] = "Only Generate";	// ??
    	return $ruletypes;
    }
    
    
    
    static function printFeatures($category) {
        if (is_array($category)) {
            foreach ($category as $index => $value) {
                Category::printSingleFeature($value);
            }
        } else {
            Category::printSingleFeature($category);
        }
    }
}
