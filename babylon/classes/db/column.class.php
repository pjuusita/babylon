<?php


/**
 * TODO: Tama luokka ehka voidaan poistaa, ei taalla taida olla muutakuin tuo columntypes staattinen luokka. 
 *       Toisaalta ei tuotantoversiossa system_columns tauluakaan tarvittaisi columns-taulun osalta.
 * 
 * Ehkä pitäisi ainakin loadiin ottaa roweista sisältä kuten muissakin
 * 
 * @author pjuusita
 *
 */

class ColumnTypeClass {
	public $name;
	public $id;
	
	function __construct($id, $name) {
		$this->name = $name;
		$this->id = $id;
	}
}


class Column {


	public $columnID;
	public $tableID;
	public $variablename;
	public $columnname;
	public $namestrings;
	public $type;
	public $obligatory;
	public $referencetableID;		
	public $editable;
	public $min;
	public $max;
	public $defaultvalue;
	public $constraints;
	public $tablevisibility;
	public $sectionvisibility;
	public $logvalue;
	public $sortorder;
	
	
	const NOTEMPTY=1;
	const MINVALUE=2;
	const MAXVALUE=3;
	
	
	// Column types, nämä läytyy taulusta system_columntypes
	const COLUMNTYPE_TABLEREFERENCE = 1;
	const COLUMNTYPE_KEY = 2;
	
	const COLUMNTYPE_INTEGER = 10;
	const COLUMNTYPE_FLOAT = 11;
	const COLUMNTYPE_STRING = 12;
	const COLUMNTYPE_DATE = 13;
	const COLUMNTYPE_DATETIME = 14;
	const COLUMNTYPE_TEXT = 15;
	const COLUMNTYPE_VALUESET = 16;
	const COLUMNTYPE_BOOLEAN = 17;
	const COLUMNTYPE_ORDER = 18;
	const COLUMNTYPE_ATTACHMENT = 19;
	const COLUMNTYPE_MULTILANG = 20;
	
	const COLUMNTYPE_INSERTDATE = 30;
	const COLUMNTYPE_INSERTID= 31;
	const COLUMNTYPE_MODIFIEDDATE = 32;
	const COLUMNTYPE_MODIFIEDID = 33;
	const COLUMNTYPE_REMOVEDATE = 34;
	const COLUMNTYPE_REMOVEID = 35;
	
	const COLUMNTYPE_INTEGERARRAY = 36;
	const COLUMNTYPE_KEYVALUELIST = 37;
	
	
	const ALIGN_LEFT = 1;
	const ALIGN_CENTER = 2;
	const ALIGN_RIGHT = 3;
	
	
	// ehdotuksia
	//  + minvalue, voidaan ehka kayttaa stringin minimipituutena
	//  + maxvalue, voidaan ehka korvata length
	
	function getID() {
		return $this->columnID;
	}
	

	function getColumnType() {
		return $this->type;
	}
	
	
	function __construct($row) {

		if ($row != null) {
			$this->columnID = $row['ColumnID'];
			$this->tableID = $row['TableID'];
			$this->variablename = $row['Variablename'];
			$this->columnname = $row['Columnname'];
			//$this->namestrings = $row['Namestrings'];		// tama pitaa parsata
			
			//echo "<br>Loading Column language: " . $_SESSION['languageID'];

			
			/*
			$pos = strpos($row["Name"],"[" . $_SESSION['languageID'] . "]");
			$pos2 = strpos($row["Name"],"[", ($pos+1));
			if ($pos2 == '') $pos2 = strlen($row["Name"]);
			$this->name = substr($row["Name"], $pos+3, $pos2-$pos-3);
				*/
			
			// name kenttä on multilang??
			//$this->name = parseMultilangString($row['Name'],2);
			$this->name = $row['Name'];
					
			$this->type = $row['Type'];
			$this->obligatory = $row['Obligatory'];
			$this->referencetableID = $row['ReferencetableID'];
			$this->editable = $row['Editable'];
			$this->min = $row['Min'];
			$this->max = $row['Max'];
			$this->defaultvalue = $row['Defaultvalue'];
			$this->logvalue = $row['Logvalue'];
			$this->sortorder = $row['Sortorder'];
			$this->removeID = $row['RemoveID'];
				
			$this->constraints = array();
			if ($this->obligatory == 1) $this->constraints[Column::NOTEMPTY] = true;
			if ($this->min != 0) $this->constraints[Column::MINVALUE] = $this->min;
			if ($this->max != 0) $this->constraints[Column::MAXVALUE] = $this->max;
			
			
			$this->tablevisibility = $row['Tablevisibility'];
			$this->sectionvisibility = $row['Sectionvisibility'];
		}
	}
	
	
	
	public static function load($columnID) {
		global $mysqli;
		$sql = "SELECT * FROM system_columns WHERE ColumnID='" . $columnID . "' AND RemoveID=0";
		$result = $mysqli->query($sql);
		$row = $result->fetch_array();
		$column = new Column($row);
		return $column;
	}

	
	public static function update($columnID,$columns, $comments = false) {
		
		global $mysqli;
		$sql = "UPDATE system_columns SET ";
		$first=true;
		foreach ($columns as $index => $value) {
			if ($first != true) {
				$sql .= ", ";
			}
			if ($first==true) {
				$first = false;
			}
			if ($value == null) {
				$sql .= $index."=null";
			} else {
				$sql .= $index."='".$value."'";
			}
		}
		$sql .= " WHERE ColumnID='".$columnID."'";
		$result = $mysqli->query($sql);
		if ($comments) echo "<br>sql - "  . $sql;
		if (!$result) return('Column::updatefailed: ' . $sql . " " . $mysqli->connect_error);
		return true;
	}
	
	

	public static function getColumnTypeClasses() {
		
		$columntypearray = array();
		
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_TABLEREFERENCE,'Table reference');
		$columntypearray[Column::COLUMNTYPE_TABLEREFERENCE] = $columntype;
		
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_KEY,'Key');
		$columntypearray[Column::COLUMNTYPE_KEY] = $columntype;
		
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_INTEGER,'Integer');
		$columntypearray[Column::COLUMNTYPE_INTEGER] = $columntype;
		
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_FLOAT,'Double');
		$columntypearray[Column::COLUMNTYPE_FLOAT] = $columntype;
		
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_STRING,'String');
		$columntypearray[Column::COLUMNTYPE_STRING] = $columntype;
		
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_DATE,'Date');
		$columntypearray[Column::COLUMNTYPE_DATE] = $columntype;
		
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_DATETIME,'Datetime');
		$columntypearray[Column::COLUMNTYPE_DATETIME] = $columntype;
		
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_TEXT,'Text');
		$columntypearray[Column::COLUMNTYPE_TEXT] = $columntype;
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_VALUESET,'Valueset');
		$columntypearray[Column::COLUMNTYPE_VALUESET] = $columntype;
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_BOOLEAN,'Boolean');
		$columntypearray[Column::COLUMNTYPE_BOOLEAN] = $columntype;
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_ORDER,'Order');
		$columntypearray[Column::COLUMNTYPE_ORDER] = $columntype;
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_ATTACHMENT,'Attachment');
		$columntypearray[Column::COLUMNTYPE_ATTACHMENT] = $columntype;
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_MULTILANG,'Multilang');
		$columntypearray[Column::COLUMNTYPE_MULTILANG] = $columntype;
		
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_INSERTDATE,'Insert timestamp');
		$columntypearray[Column::COLUMNTYPE_INSERTDATE] = $columntype;
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_INSERTID,'Insert userID');
		$columntypearray[Column::COLUMNTYPE_INSERTID] = $columntype;
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_MODIFIEDDATE,'Modified timestamp');
		$columntypearray[Column::COLUMNTYPE_MODIFIEDDATE] = $columntype;
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_MODIFIEDID,'Modified userID');
		$columntypearray[Column::COLUMNTYPE_MODIFIEDID] = $columntype;
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_REMOVEDATE,'Remove timestamp');
		$columntypearray[Column::COLUMNTYPE_REMOVEDATE] = $columntype;
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_REMOVEID,'Remove userID');
		$columntypearray[Column::COLUMNTYPE_REMOVEID] = $columntype;
		
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_INTEGERARRAY,'IntegerArray');
		$columntypearray[Column::COLUMNTYPE_INTEGERARRAY] = $columntype;
		
		$columntype = new ColumnTypeClass(Column::COLUMNTYPE_KEYVALUELIST,'KeyValueList');
		$columntypearray[Column::COLUMNTYPE_KEYVALUELIST] = $columntype;
		
		return $columntypearray;
	}
	
	

	public static function getColumnTypes() {
	
		return array (
				Column::COLUMNTYPE_TABLEREFERENCE 	=> 'Table reference' ,
				Column::COLUMNTYPE_KEY 				=> 'Key',
	
				Column::COLUMNTYPE_INTEGER 			=> 'Integer',
				Column::COLUMNTYPE_FLOAT 			=> 'Double',
				Column::COLUMNTYPE_STRING 			=> 'String',
				Column::COLUMNTYPE_DATE				=> 'Date',
				Column::COLUMNTYPE_DATETIME 		=> 'Datetime',
				Column::COLUMNTYPE_TEXT 			=> 'Text',
				Column::COLUMNTYPE_VALUESET 		=> 'Valueset',
				Column::COLUMNTYPE_BOOLEAN 			=> 'Boolean',
				Column::COLUMNTYPE_ORDER 			=> 'Order',
				Column::COLUMNTYPE_ATTACHMENT 		=> 'Attachment',
				Column::COLUMNTYPE_MULTILANG 		=> 'Multilang',
	
				Column::COLUMNTYPE_INSERTDATE 		=> 'Insert timestamp',
				Column::COLUMNTYPE_INSERTID 		=> 'Insert userID',
				Column::COLUMNTYPE_MODIFIEDDATE 	=> 'Modified timestamp',
				Column::COLUMNTYPE_MODIFIEDID 		=> 'Modified userID',
				Column::COLUMNTYPE_REMOVEDATE 		=> 'Remove timestamp',
				Column::COLUMNTYPE_REMOVEID 		=> 'Remove userID',
				Column::COLUMNTYPE_INTEGERARRAY 	=> 'IntegerArray',
				Column::COLUMNTYPE_KEYVALUELIST 	=> 'KeyValueList'
		);
	}
	
	
}



?>