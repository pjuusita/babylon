<?php


class Row {

	private $rowID = 0;
	private $vars = array();
	private $childs = null;
	
	function __construct($columns = null, $row = null, $rawformat = false) {
		if ($row != null) {
			$this->vars = array(); 
			
			/** @var mixed $index */
			foreach($columns as $index => $column) {
				if ($column->type == Column::COLUMNTYPE_KEY) {
					if ($this->rowID == 0) {
						$this->rowID = $column->variablename;
						//echo "<br>Variablename - " . $column->variablename . " - " . $row[$column->columnname];
						$this->vars[$column->variablename] = $row[$column->columnname];
					} else {
						// taulussa on useampi key sarake, getID pitäisi palauttaa joko arrayn, tai sitten tyhjän jolloin rows['x'] muuttuu muoton rows[]
						$this->rowID = null;
						$this->vars[$column->variablename] = $row[$column->columnname];
					}
				} elseif ($column->type == Column::COLUMNTYPE_MULTILANG) {
					if ($rawformat == true) {
						$value = $row[$column->columnname];
					} else {
						$value = $row[$column->columnname];
						//$value = parseMultilangString($row[$column->columnname], $_SESSION['languageID']);
					}
					$this->vars[$column->variablename] = $value;
				} elseif ($column->type == Column::COLUMNTYPE_INTEGERARRAY) {
					$str = $row[$column->columnname];
					if ($str == '') {
						$this->vars[$column->variablename] = array();
					} else {
						$value = explode(':',$row[$column->columnname]);
						$this->vars[$column->variablename] = $value;
					}
				} elseif ($column->type == Column::COLUMNTYPE_KEYVALUELIST) {
					
					if ($row[$column->columnname] == "") {
						$this->vars[$column->variablename] = array();
					} else {
						$list = explode(':',$row[$column->columnname]);
						$pairarray = array();
						
						/** @var mixed $i */
						foreach($list as $i => $value) {
							$pair = explode('=', $value);
							//echo "<br>Pair - " . $pair[0] . " - " . $pair[1];
							$pairarray[$pair[0]] = $pair[1];
						}
						$this->vars[$column->variablename] = $pairarray;
					}
					
				} else {
					$this->vars[$column->variablename] = $row[$column->columnname];
				}
				
				/*
				} else if ($column->type == Column::COLUMNTYPE_MULTILANG) {
					$value = parseMultilangString($row[$column->columnname], $_SESSION['languageID']);
					$this->vars[$column->variablename] = $row[$column->columnname];
				*/
				
				//echo "<br>Value : " . $column->columnname;
				//echo "<br>Value : " . $this->vars[$column->variablename];
				//echo "<br>Varr : " . $column->variablename;
			}
		}
	}
	
	
	public function getChild($index) {
		if ($this->childs == null) return null;
		return $this->childs[$index];
	}
	
	
	public function getChilds() {
		if ($this->childs == null) return null;
		return $this->childs;
	}
	
	public function getChildren() {
		if ($this->childs == null) return null;
		return $this->childs;
	}
	
	
	
	public function getChildCount() {
		if ($this->childs == null) return 0;
		return count($this->childs);
	}
	
	public function addChild($row) {
		if ($this->childs == null) $this->childs = array();
		$this->childs[$row->getID()] = $row;	
		//echo "<br>childcount - " . count($this->childs);
	}
	
	
	public function sortChilds($comparefunc) {
		if ($this->childs == null) return;
		/*
		echo "<br>Before:";
		foreach($this->childs as $index => $child) {
			echo " - " . $child->sortorder;
		}
		*/
		usort($this->childs, $comparefunc);
		//echo "<br>After:";
		foreach($this->childs as $index => $child) {
			//echo " - " . $child->sortorder;
			$child->sortChilds($comparefunc);
		}
	}
	
	
	public function getID() {
		if ($this->rowID == null) return null;
		return $this->vars[$this->rowID];
	}
	
	
	public function __set($index, $value) {
		$this->vars[$index] = $value;
	}
	

	public function __get($index) {
		//echo "<br>index .." . $index . "..";
		if (!isset($this->vars[$index])) return null;
		return $this->vars[$index];
	}
	
	
	public function issetted($index) {
		return isset($this->vars[$index]);
	}

	public function getDatavariables() {
		return $this->vars;
	}
	
	public function printContent() {
		foreach($this->vars as $index => $value) {
			echo "<br>Index - " . $index;
			echo "<br>Value - " . $value;
		}
	}
	
	public function getCopy() {
		$row = new Row();
		$row->rowID = $this->rowID;
		foreach($this->vars as $index => $value) {
			$row->vars[$index] = $value;
		} 
		$childs = $this->getChildren();
		if ($childs != null) {
			foreach($childs as $index => $child) {
				$row->childs[$index] = $child->getCopy();
			}
		}
		return $row;
	}
	
	
	public function setID($id) {
		$this->rowID = $id;
	}
	
	// Toteutettu tablen puolelle Table::addRow...
	//public function addCopy() {
		// TODO: Tämä voisi tallentaa itsensä uudemman kerran kantaan, ei siis update vaan save...
		// homma toimisi siis niin, että lisäisi itsensä uudella ID:llä kantaan
	//}
}


?>