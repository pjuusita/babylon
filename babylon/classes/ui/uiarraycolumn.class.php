<?php




class UIArrayColumn extends UIColumn {

	public $itemarray;
	public $itemarrayvariable;
	
	
	
	/**
	 * Jos itemarray on null, tulostetaan pelkkät arvot sellaisenaan pilkulla erotettuna
	 * Jos itemarrayvariable ei ole nulli, itemarray on tälläin row-taulukko, josta arvoksi haetaan parametrina tullut arvo
	 * Jos itemarray ei ole nulli, mutta itemarrayvariable on null, niin tulostetaan suoraan itemarrayn indeksin elementtejä
	 * 
	 * @param string $name
	 * @param string $datavariable
	 * @param string $itemarray
	 * @param string $itemarrayvariable
	 */	
	public function __construct($name, $datavariable, $itemarray, $itemarrayvariable = null) {
		parent::__construct($name, $datavariable);
		$this->itemarray = $itemarray;
		$this->itemarrayvariable = $itemarrayvariable;
	}

}
?>