<?php


// Kopioitu suoraan UIColumn.class.php

// pitää ehkä harkita pystyykä tämän yhdistämään UIFieldin kanssa

// pitäisi poistaa set columntype, tää on mun mielestäni vanhentunut

// TODO: UIColumniin pitäisi lisätä itseensä draw-funktionaalisuus, ehkä. Tällähetkellä columnin 
//       visualisointi on section luokan vastuulla. Tämä ainakin vaikeuttaa custom columnien tekemistä
//       olisi tyylikkäämpää, että visualisointi olisi column-luokan sisällä. Yksinkertaistaisi koodia
// 		 varmaan section luokkien osalta myös

class UIColumn extends UIComponent {

	public $name;
	public $datavariable;
	public $linkurl = null;
	public $linkvariable = null;
	public $type;
	
	public $dataformatter = NULL;
	public $align;
	
	public $searchvariable = NULL;
	public $retriever = NULL;
	public $undefinedstring;
	
	
	public function __construct($name, $datavariable) {
		parent::__construct();
		$this->name = $name;
		$this->datavariable = $datavariable;
		$this->dataformatter = NULL;
		$this->undefinedstring = "n/a";
	}
	
	
	public function setColumnType($columntype) {
		$this->type = $columntype;
	}

	
	public function setUndefinedString($undefinedstring) {
		$this->undefinedstring = $undefinedstring;
	}
	
	public function setLink($linkurl, $linkvariable) {
		$this->linkurl = $linkurl;
		$this->linkvariable = $linkvariable;
	}


	public function getVariable() {
		return "" . $this->datavariable;
	}
	
	
	public function setFormatter($dataformatter, $dataformatvariable = "") {
		$this->dataformatter = $dataformatter;
		$this->dataformatvariable = $dataformatvariable;
	}
	
	public function setAlign($align) {
		$this->align = $align;
	}
	

	public function setValueGetter($valueretriever) {
		$this->retriever = $valueretriever;
	}
	
	
	public function needMonospace() {
		if ($this->dataformatter == Column::COLUMNTYPE_DATE) return true;
		if ($this->dataformatter == Column::COLUMNTYPE_DATETIME) return true;
		if ($this->dataformatter == Column::COLUMNTYPE_FLOAT) return true;
		return false;
	}
	
	
	public function formatValue($value, $formatinfo) {
		
	
		if ($this->retriever instanceof ColumnValueGetter) {
			$value = $this->retriever->getValue($value);

			if ($this->dataformatter instanceof ColumnFormatter) {
				return $this->dataformatter->getString($value, $formatinfo);
			} else {
				return $value;
			}
		}
		
		if ($this->dataformatter instanceof ColumnFormatter) {
			return $this->dataformatter->getString($value, $formatinfo);
		}
		
		if ($this->dataformatter == 0) {
			if ($value == "") {
				return $value;
				//return "<font style='color:red'>tuntematon</font>";
			} else {
				return $value;
			}
		}
		if ($this->dataformatter == Column::COLUMNTYPE_DATE) {
			if ($value == null) return "";
			if ($value == '0000-00-00') {
				$valuestr = "Ei asetttu";
			} else {
				$day = substr($value, 8,2);
				$month = substr($value, 5, 2);
				$year = substr($value, 0, 4);
				$datestr = $year . "-" . $month . "-" . $day ;
				$valuestr = "" . $day . "." . $month . "." . $year;
			}	
			return $valuestr;
		}
		if ($this->dataformatter == Column::COLUMNTYPE_DATETIME) {
			if ($value == '0000-00-00') {
				$valuestr = "Ei asetttu";
			} else {
				$day = substr($value, 8,2);
				$month = substr($value, 5, 2);
				$year = substr($value, 0, 4);
				$time = substr($value, 11,5);
				$datestr = $year . "-" . $month . "-" . $day ;
				$valuestr = "" . $day . "." . $month . "." . $year . " " . $time;
				
			}
			return $valuestr;
		}
		if ($this->dataformatter == Column::COLUMNTYPE_FLOAT) {
			
			$valuestr = number_format($value, 2, ',', '');
			return $valuestr;			
		}
		return $value;
	}
	
	
	public static function createUIColumn($column, $data) {
	
		$uifield = null;
		switch($column->type) {
			case 2 :
				return new UIFixedColumn($column->name, $column->variablename);
				break;
			case Column::COLUMNTYPE_TABLEREFERENCE :
				return new UISelectColumn($column->name, $column->variablename, $data,null);
				break;
			case Column::COLUMNTYPE_INTEGER :
				return new UIFixedColumn($column->name, $column->variablename);
				break;
			case Column::COLUMNTYPE_FLOAT:
				return new UIFixedColumn($column->name, $column->variablename);
				break;
			case Column::COLUMNTYPE_STRING:
				return new UIFixedColumn($column->name, $column->variablename);
				break;
			case Column::COLUMNTYPE_DATE:
				return new UIFixedColumn($column->name, $column->variablename);
				break;
			case Column::COLUMNTYPE_DATETIME:
				return new UIFixedColumn($column->name, $column->variablename);
				break;
			case Column::COLUMNTYPE_TEXT:
				return new UIFixedColumn($column->name, $column->variablename);
				break;
			case Column::COLUMNTYPE_MULTILANG:
				return new UIMultiLangTextField($column->name, $column->variablename);
				break;
			case Column::COLUMNTYPE_INTEGERARRAY:
				return new UIFixedColumn($column->name, $column->variablename);
				break;
			case Column::COLUMNTYPE_KEYVALUELIST:
				return new UIFixedColumn($column->name, $column->variablename);
				break;
			default :
				echo "[{\"success\":\"Tuntematon tyyppi " . $column->type . "\"}]";
				exit;
				break;
		}
	}
}
?>