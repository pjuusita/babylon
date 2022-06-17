<?php


/**
 * Tämän oli tarkoitus esittää sanan venäjäkielinen esitys niin, että sanan paino on merkitty sanassa eri värillä.
 *
 */
class WeightFormatter implements ColumnFormatter {
	
	
	function __construct() {
		
	}
	
	public function getString($word, $format) {
	
		if ($word == "_unknown_") {
			return "<font style='color:red;'>tuntematon</font>";
		}
		
		if ($format == "") return $word;

		$comments = false;
		if ($comments) echo "word:" . $word . ",format:" . $format;
		
		$dots = explode(':',$format);
		
		$start = 0;
		$result = "";
		foreach($dots as $index => $value) {
			if ($comments) echo "<br>value - " . $value;
			$result = $result . mb_substr($word, $start, $value-1-$start, "UTF-8");
			if ($comments) echo "<br>result - " . $result;
				
			//$result = $result . "<font style='color:black;text-decoration: underline;font-weight:bold;'>" . mb_substr($word, $value-1, 1, "UTF-8") . "</font>";			
			$result = $result . "<font style='color:red;font-weight:bold;'>" . mb_substr($word, $value-1, 1, "UTF-8") . "</font>";			
			if ($comments) echo "<br>result - " . $result;
			
			$start = $value;
			if ($comments) echo "<br>start - " . $start;
				
		}
		$result = $result . mb_substr($word, $start, NULL, "UTF-8");
		if ($comments) echo "<br>result - " . $result;
		
		return $result;
		
		//$word1 = substr($word, 0,1);
		//$word2 = substr($word, 1,1);
		//$word3 = substr($word, 2);
		//return $word1 . "<font style='color:red;font-weight:bold;'>" . $word2 . "</font>" . $word3;		
	}
	
}

?>