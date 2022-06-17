<?php

class WordGetter implements ColumnValueGetter {
	
	private $variable = null;
	private $array = null;

	
	function __construct($array, $variable) {
		$this->variable = $variable;
		$this->array = $array;
	}

	
	public function getValue($value) {
	
		foreach($this->array as $index => $word) {
			if ($word->concepts == $value) {
				return $word->lemma;
			}
		}
		return "<font style='color:red;'>tuntematon</font>";
	}
	
}

?>