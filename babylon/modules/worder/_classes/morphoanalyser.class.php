<?php



include_once('./modules/worder/_classes/category.class.php');
include_once('./modules/worder/_classes/wordform.class.php');


class MorphoAnalyser {

    function __construct() {
        
    }

	public static function analyse($wordform, $lang, $features = null) {
		
		$words = Table::load("worder_wordforms"," WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $lang->languageID . " AND Wordform='" . $wordform . "'");
		
		if ($words == null) {
			echo "<br>No analyse found for '" . $wordform . "'";
			return null;
		} else {
			foreach($words as $index => $word) {
				//echo "<br>--- found - " . $word->wordform . " - " . $word->wordID;
			}
		}
		
		return $words;
	}
}