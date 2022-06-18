<?php

class UIBallColumn extends UIColumn {

	public $name;
	public $showvariable;
	public $datavariable;
	public $link;
	public $width;
	public $selection;

	/**
	 *  TODO: korvaa shovariable ja datavariable järjestys yhdenmukaiseksi
	 */
	public function __construct($name, $showvariable, $datavariable, $colors = null, $width = NULL) {
		parent::__construct($name, $datavariable);
		
		$this->showvariable = $showvariable;
		$this->datavariable = $datavariable;
		$this->selection = $colors;
		$this->width = $width;
	}
	
	
	public function generateHeaderCell() {
		echo "	<td style='' class='listtable-header'>";
		echo "o";
		echo "	</td>";
	}
	
	
	/**
	 * Ongelmana on, että id-elementit pitää olla synkassa käyttävän luokan kanssa
	 * 
	 * Toteutus on kesken, pallosarake on toteutettu tasks/tasks/tasks.php
	 * 
	 * @param Row $row
	 * @param int $rownNumber
	 */
	public function generateContentCell($row, $rownNumber) {
		
		$datavariable = $this->datavariable;
		$showvariable = $this->showvariable;
		
		//echo "<br>Row-" . $row->$datavariable . "-" . $datavariable;
		//echo "<input id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='".$row->$datavariable . "'>";
		if ($row->$datavariable == 0) {
			if (isset($this->selection[$row->$datavariable])) {
				$color = $this->selection[$row->$datavariable];
				echo "<div style='width:14px;height:14px;border:2px solid grey;background-color:" . $color . ";border-radius: 9px;-moz-border-radius: 9px;'></div>";
			} else {
				//$value = "<font size=-1 style='font-style:italic;color:white'>n/a</font>";
				echo "-";
			}
		} else {
			$color = $this->selection[$row->$datavariable];
			echo "<div style='width:14px;height:14px;border:2px solid grey;background-color:" . $color . ";border-radius: 9px;-moz-border-radius: 9px;'></div>";
			//$value = "<font size=-1 style='font-style:italic;color:white'>n/a</font>";
			//echo ".1.";
		}			
	}
}


?>