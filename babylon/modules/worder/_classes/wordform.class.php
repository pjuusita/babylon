<?php


class WordForm {
	
	public $lemma;
	public $wordform;
	public $wordID;
	public $features;
	public $wordformID;
	
	function __construct($wordID, $lemma, $wordform, $features = array(), $formID = -3) {
		$this->wordID = $wordID;
		$this->lemma = $lemma;
		$this->wordform = $wordform;
		$this->features = $features;
		$this->wordformID = $formID;
	}
	
	public function addFeature($feature) {
		$this->features[$featureID] = $featureID;
	}
	
	
}