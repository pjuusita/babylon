<?php


/**
 * Tätä käytetään templatena kun ei haluta menua ollenkaan, käytetään siis silloin kun 
 * ladataan json-filejä ja contenttia. yleensä MENUPRESENT-vakion arvolla false käytetään tätä
 *
 */

class MinimalTemplate extends Template {
	
	public function printErrorMessages() {
	}
	
	public function show($module, $filename) {
		$contentpath = SITE_PATH . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $filename . '.php';
		$registry = $this->registry;
		
		
		if (PDFGEN == true) {
			// Tuleekohan tämä nyt mukaan jsoneihin myös
		} else {
			
			echo "<script>";
			echo "		function getcontenttitle() {";
			echo "			return '" . $_SESSION['pagetitle'] . "';";
			echo "		}";
			echo "</script>";
		}
		
		include ($contentpath);
	}
}


?>