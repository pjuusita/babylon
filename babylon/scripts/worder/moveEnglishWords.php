<?php


/*
$words = Table::load('worder_english_words','',true);

foreach($words as $index => $word) {
	echo "<br>Word - " . $word->lemma;
	
	$values = array();
	$values['Lemma'] = $word->lemma;
	$values['WordclassID'] = $word->wordclassID;
	$values['ConceptID'] = $word->conceptID;
	$values['GrammarID'] = 1;
	$values['LanguageID'] = 2;
	$wordID = Table::addRow('worder_finnish_words', $values);
	
	echo "<br>WordID - " . $wordID;
	
	$values = array();
	$values['OldwordID'] = $word->wordID;
	$values['NewwordID'] = $wordID;
	$wordID = Table::addRow('worder_englishtransitions', $values);
}
*/

$wordlinks = Table::load('worder_englishtransitions','',true);
$wordids = array();

foreach($wordlinks as $index => $link) {
	$wordids[$link->oldwordID] = $link->newwordID;	
}


/*
$sentences = Table::load('worder_english_sentences','',true);

foreach($sentences as $index => $sentence) {
	echo "<br>Sentence - " . $sentence->sentenceID . " - " . $sentence->sentence;
	$wordsstr = "";
	
	$oldwordID = 0;
	$newwordID = 0;
	if (count($sentence->words) == 2) {

		$oldwordID = $sentence->words[0];
		$newwordID = $wordids[$sentence->words[0]];
		$wordsstr = $newwordID . ":" . $sentence->words[1];
	} 
	
	$values = array();
	$values['Sentence'] = $sentence->sentence;
	$values['Words'] = $wordsstr;
	$values['GrammarID'] = 1;
	$values['LanguageID'] = 2;
	$newsentenceID = Table::addRow('worder_finnish_sentences', $values);
	
	echo "<br>-- words = " . $wordsstr;
	
	if ($wordsstr != "") {
		$sentencelink = Table::loadRow("worder_english_sentencelinks", "WHERE WordID=" . $oldwordID . " AND SentenceID=" . $sentence->sentenceID);
		
		if ($sentencelink != null) {
			$values = array();
			$values['WordID'] = $newwordID;
			$values['SentenceID'] = $newsentenceID;
			Table::updateRow("worder_english_sentencelinks", $values, $sentencelink->rowID);
		}
	}	
}
*/

/*
$links = Table::load('worder_english_sentencelinks','',true);

foreach($links as $index => $link) {
	echo "<br>Word - " . $link->wordID;

	$values = array();
	$values['WordID'] = $link->wordID;
	$values['SentenceID'] = $link->sentenceID;
	$values['ConceptID'] = $link->conceptID;
	$values['SystemID'] = 5;
	$values['GrammarID'] = 1;
	$values['LanguageID'] = 2;
	$wordID = Table::addRow('worder_finnish_sentencelinks', $values);
}
*/

/*
$wordforms = Table::load('worder_english_wordforms','',true);

foreach($wordforms as $index => $wordform) {
	echo "<br>Word - " . $wordform->wordID;
	$newwordID = $wordids[$wordform->wordID];
	
	$values = array();
	$values['WordID'] = $newwordID;
	Table::updateRow("worder_english_wordforms", $values, $wordform->rowID);
}
*/

/*
$links = Table::load('worder_english_wordforms','',true);

foreach($links as $index => $link) {
	echo "<br>Word - " . $link->wordID;

	$values = array();
	$values['Wordform'] = $link->wordform;
	$values['WordID'] = $link->wordID;
	$values['Features'] = implode(":",$link->features);
	$values['Grammatical'] = 1;
	$values['SystemID'] = 5;
	$values['GrammarID'] = 1;
	$values['LanguageID'] = 2;
	$wordID = Table::addRow('worder_finnish_wordforms', $values);
}
*/

/*
$wordforms = Table::load('worder_english_wordlinks','',true);

foreach($wordforms as $index => $wordform) {
	echo "<br>Wordlink - " . $wordform->wordID;
	$newwordID = $wordids[$wordform->wordID];

	
	$values = array();
	$values['WordID'] = $newwordID;
	Table::updateRow("worder_english_wordlinks", $values, $wordform->rowID);
}
*/

/*
$links = Table::load('worder_english_wordlinks','',true);

foreach($links as $index => $link) {
	echo "<br>Word - " . $link->wordID;

	$values = array();
	$values['ConceptID'] = $link->conceptID;
	$values['WordID'] = $link->wordID;
	$values['SystemID'] = 5;
	$values['GrammarID'] = 1;
	$values['LanguageID'] = 2;
	$wordID = Table::addRow('worder_finnish_wordlinks', $values);

}
*/


/*
$sql = "SELECT * FROM worder_english_words";
echo "<br>sql - " . $sql;

global $mysqli;
$result = $mysqli->query($sql);
$foundwords = array();
while($row = $result->fetch_array()) {

}
*/

?>