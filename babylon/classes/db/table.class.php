<?php



function sortBySortOrder($a, $b) {
	if ($a->sortorder < $b->sortorder) return -1;
	return 1;
}



/**
 *   Funktoiden nimeamisessa hieman epaselvyyksia...
 *   
 *   load('tablename') - palautta ilmeisesti taulun sisallan
 *   loadTable('tablename'|ID) - palauttaisi ilmeisesti taulun rakenteen, tama pitais ehka korvata getTable() funtiolla
 * 
 *
 */


class Table {

	public static $comments = true;
	public static $loadedtables = array();
	public static $loadedtablesByID = array();
	private static $columns = array();
	public static $lang = 1;
	
	public $tableID;
	public $name;
	public $logtable;
	public $tabletype;
	public $moduleID;
	public $description;
	
	
	// nämä läytyy taulusta system_logrowtypes
	const LOG_INSERT = 1;
	const LOG_UPDATE = 2;
	const LOG_DELETE = 3;
	const LOG_MOVEFROM = 4;
	const LOG_MOVETO = 5;
	const LOG_CREATE = 6;
	
	// const LOG_REFDELETE = 3;
	// const LOG_REFMOVEFROM = 4;
	// const LOG_REFMOVETO = 5;
	

//******************************************************************************************************************
//***
//***
//******************************************************************************************************************

	// TODO: Tätä ei ehkä tarvita, tablea käsitellään tällatavalla ladattaessa Row-luokan avulla
	public function __construct($row) {

		if ($row != null) {
			$this->tableID = $row['TableID'];
			$this->name = $row['Name'];
			$this->logtable = $row['Logtable'];
			$this->tabletype= $row['Logtable'];
			$this->moduleID = $row['ModuleID'];
			$this->systemspecific = $row['Systemspecific'];		
			$this->description = $row['Description'];
		}
	}
	
//******************************************************************************************************************
//***
//***
//******************************************************************************************************************
	
	
	// Forced parametri tarkoittaa, että ladataanko sisältö uudeleen pakosta
	// TODO: vaihda paremetrit forced ja comments toisinpäin
	private static function loadTableStruct($tablename, $comments = false, $forced = false) {
		
		//$comments = true;
		
		global $mysqli;
		//$comments = false;
		if ($forced == false) {
			if (isset(self::$loadedtables[$tablename])) {
				if ($comments) echo "<br>Table " . $tablename . "is allready loaded.";
				return self::$loadedtables[$tablename];
			}
		}
		
		$sql = "SELECT * FROM system_tables WHERE Name='" . $tablename . "'";
		//if ($comments)  echo "<br>loadTableStruct Sql - " . $sql;
		//debug_print_backtrace();
		$result = $mysqli->query($sql);
		if (!$result) {
			//echo "<br>Sql - " . $sql;
			die('Load tablecontent failed: ' . $mysqli->connect_error);
		}
		
		$row = $result->fetch_array();
		if ($row == null) {
			return null;
			//echo "<br>Row - null";
		}
		$table = new Table($row);
					
		$sql = "SELECT * FROM system_columns WHERE TableID='" . $table->tableID . "' AND RemoveID=0";
		//if ($comments) echo "<br>loadTableStruct sql2 - " . $sql;
		$result = $mysqli->query($sql);
		$columns = array();
		while($row = $result->fetch_array()) {
			$column = new Column($row);
			$columns[$column->columnID] = $column;
		}
		//if ($comments) echo "<br>Columns found z - " . $tablename . " - " . count($columns);
		self::$columns[$tablename] = $columns;
		self::$loadedtables[$tablename] = $table;
		self::$loadedtablesByID[$table->tableID] = $table;
		
		return $table;
	}

	
	
	
	public static function getTableID($tablename, $comments = false) {
		
		if ($tablename == "") return 0;
		if (isset(self::$loadedtables[$tablename])) {
			return self::$loadedtables[$tablename]->tableID;
		}
		if ($comments) echo "<br>get tableID - " . $tablename;
		$table = Table::loadTableStruct($tablename,$comments);
		return $table->tableID;
	}
	
	
	


	public static function getTableCount() {

		global $mysqli;
		$sql = "SELECT COUNT(*) as Count FROM information_schema.tables WHERE table_schema = 'dbName';";
		
		$result = $mysqli->query($sql);
		if (!$result) {
			die('get TableCount failed: ' . $mysqli->connect_error);
		}
		
		$row = $result->fetch_array();
		return $row['Count'];
	}
	
	
	
	private static function loadTableStructWithID($tableID) {
	
		global $mysqli;
		$sql = "SELECT * FROM system_tables WHERE TableID='" . $tableID . "'";
		$result = $mysqli->query($sql);
		if (!$result) die('Load tablecontent failed 1: ' . $mysqli->connect_error);
		
		// palauta null jos table ei ole olemassa?
		
		$row = $result->fetch_array();
		$table = new Table($row);
			
		$tablename = $table->name;
			
		$sql = "SELECT * FROM system_columns WHERE TableID='" . $table->tableID . "' AND RemoveID=0";
		$result = $mysqli->query($sql);
		$columns = array();
		//echo "<br>Rowcount - " . $mysqli->num_rows;
		while($row = $result->fetch_array()) {
			$column = new Column($row);
			$columns[$column->columnID] = $column;
		}
		self::$columns[$tablename] = $columns;
		self::$loadedtables[$tablename] = $table;
		self::$loadedtablesByID[$table->tableID] = $table;
		return $table;
	}
	
	
	public static function getTableName($tableID) {
		
		if (isset(self::$loadedtablesByID[$tableID])) {
			return self::$loadedtablesByID[$tableID]->name;
		}
		
		$table = self::loadTableStructWithID($tableID);
		return $table->name;
	}
	
	
	
	public static function getTableWithID($tableID) {
		if (isset(self::$loadedtablesByID[$tableID])) return self::$loadedtablesByID[$tableID];
		else return self::loadTableStructWithID($tableID);
	}
	

	/**
	 *   Mitahan taa palauttaa jos taulua ei ole asetettu
	 *   
	 *   Ehkä tämä pitäisi ladata sessiosta, ettei tarvitsisi koko ajan kuormittaa tietokantaa. Tälläin table update
	 *   ei heti näy. Samalla haulla ei nyt ladata näitä, että kerran per lataus ainoastaan. 
	 *   
	 *   Tätä ei tarvitsisi tuotantoversiossa. eli tätä ei pitäisi edes kutsua tuotannosta
	 *   
	 *   Tämä voisi olla private, mutta tällähetkellä tätä kutsutaan monessa paikkaa koska tablelta tarvitaan getColumnssi, 
	 *   useimmiten getColumnssia tarvitaan utillssin section tai insertsection luontiin. Näistä insertsectioninkin taitaa
	 *   olla vanhentunut. Pitäisi jokatapauksessa pyrkiä siihen, että joko sectionia käytettäisiin useammin/kaikkialla tai
	 *   sitten kaikkialla ei. Ainakin tämä voitaisiin pistää privatiksi ja luoda erillinen public getcolumns.
	 *   
	 */
	public static function getTable($table, $comments = false) {
		
		if (($table == null) || ($table == '')) {
			echo "<br>Table on nulli";
			exit();
		}
		
		if (is_integer($table)) {
			if (isset(self::$loadedtablesByID[$table])) return self::$loadedtablesByID[$table];
			else return self::loadTableStructWithID($table);
		} else {
			if (isset(self::$loadedtables[$table])) {
				if ($comments) echo "<br>Table strcut already loaded";
				return self::$loadedtables[$table];
			} else {
				if ($comments) echo "<br>Loading table struct - " . $table;
				return self::loadTableStruct($table,$comments);
			}
		}
	}

	
	
	public function getColumns() {
		return self::$columns[$this->name];
	}

	
	
	public function getColumnsSorted() {
		$arri = self::$columns[$this->name];
		usort($arri , "sortBySortOrder");
		return $arri;
	}
	
	
	public function getName() {
		return $this->name;	
	}


	
	public function getID() {
		return $this->tableID;
	}
		

	
	public static function loadRow($table, $selector, $comments = false) {
	
		if ($selector == null) {
			echo "<br>Selector on null - " . $table;
			exit;
		}
		if ($selector == "") {
			echo "<br>Selector on tyhjä";
			exit;
		}
		
		//$comments = false;
		if ($comments) echo "<br>Table loadrow - " . $table . " - selector: '" . $selector . "'";
		$rawformat = false;
	
		global $mysqli;
		if (is_numeric($table)) {
			if ($comments) echo "<br>Table loadrow1 - " . $table;
			if (isset(self::$loadedtablesByID[$table])) {
				if ($comments) echo "<br>Table loadrow2 notset - " . $table;
				$dbtable = self::$loadedtablesByID[$table];
			} else {
				if ($comments) echo "<br>Table loadrow1 notset - " . $table;
				$dbtable = self::loadTableStructWithID($table,$comments);
			}
		} else {
			if ($comments) echo "<br>Table loadrow2 - " . $table;
			if (isset(self::$loadedtables[$table])) {
				if ($comments) echo "<br>Table loadrow2 isset - " . $table;
				$dbtable = self::$loadedtables[$table];
			} else {
				if ($comments) echo "<br>Table loadrow2 not set - " . $table;
				$dbtable = self::loadTableStruct($table,$comments);
			}
		}
	
		//var_dump($dbtable);
	
		if ($dbtable == null) {
			echo "<br>table fail: " . $table;
			return null;
		}
	
		if (is_numeric($selector)) {
				
			//echo "<br>integer, selector=" . $selector;
				
			$columns = $dbtable->getColumns();
			$keycolumn = 0;
			foreach($columns as $columnID => $column) {
				if ($column->type == 2) $keycolumn = $column;
			}
			if ($keycolumn == null) {
				if ($dbtable->systemspecific == 1) {
					$sql = "SELECT * FROM " . $dbtable->name . " WHERE SystemID=" . $_SESSION['systemID'];
				} else {
					$sql = "SELECT * FROM " . $dbtable->name . "";
				}
			} else {
				if ($dbtable->systemspecific == 1) {
					$sql = "SELECT * FROM " . $dbtable->name . " WHERE SystemID=" . $_SESSION['systemID'] . " AND " . $keycolumn->columnname . "='" . $selector . "'";
				} else {
					$sql = "SELECT * FROM " . $dbtable->name . " WHERE " . $keycolumn->columnname . "='" . $selector . "'";
				}
			}
			//echo "<br>Jee - " . $sql;
			//var_dump($mysqli);
			if ($comments == true) echo "<br>Sql1 - " . $sql;
			$result = $mysqli->query($sql);
			if ($result->num_rows == 0) {
				//echo "<br>Error: " . $mysqli->error;
				//echo "<br>Error: " . $mysqli->database;
				//echo "<br>nullli";
				//var_dump($mysqli);
					
				return null;
			}
			$row = $result->fetch_array();
			$item = new Row($columns, $row, $rawformat);
			return $item;
				
	
		} else {
	
			//echo "<br>not integer, selector=" . $selector;
				
			$columns = $dbtable->getColumns();
				
			$selector = Table::processWhereClause($selector, $dbtable->systemspecific, "loadRow - " . $table);
			$sql = "SELECT * FROM " . $dbtable->name . " " .  $selector;
			//} else {
			//	$selector = Table::processWhereClause($selector, 0, "loadRow2 - " . $table);
			//	$sql = "SELECT * FROM " . $dbtable->name . " " . $selector;
			//}
				
			if ($comments) echo "<br>sql2 - " . $sql;
			$result = $mysqli->query($sql);
			if ($result == null) {
				//echo "<br>Nulli error";
				return null;
			}
			if (mysqli_num_rows($result) == 0) {
				//echo "<br>Multible rows";
				return null;
			}
			$row = $result->fetch_array();
			$item = new Row($columns, $row, $rawformat);
			return $item;
		}
	
	}
	
	
	private static function loadRowRaw($table, $selector) {
	
		global $mysqli;
	
		$sql = "SELECT * FROM " . $table . " "  . $selector;
		echo "<br>sql - " . $sql;
		$result = $mysqli->query($sql);
		$array = array();
		while($row = $result->fetch_array()) {
			$array[] = $row;
		}
		return $array;
	}
	
	
	
	
	// tämä palauttaa vain yhden itemin, mitäs sitten jos where ehdolla läytyy monta?
	public static function loadRowWhere($table, $whereclause, $comments = false) {
	
		global $mysqli;
		if (is_numeric($table)) {
			if (isset(self::$loadedtablesByID[$table])) $dbtable = self::$loadedtablesByID[$table];
			else $dbtable = self::loadTableStructWithID($table);
		} else {
			if (isset(self::$loadedtables[$table])) $dbtable = self::$loadedtables[$table];
			else $dbtable = self::loadTableStruct($table);
		}
	
		$columns = $dbtable->getColumns();
		$sql = "SELECT * FROM " . $dbtable->name . " " . $whereclause;
		if ($comments) echo "<br>Sql xx - " . $sql;
		$result = $mysqli->query($sql);
		if ($result->num_rows == 0) return null;
		$row = $result->fetch_array();
		$item = new Row($columns, $row, true);
		return $item;
	}
	
	
	
	// raw format antaa ainakin multilang kentän kokonaisena stringinä, tähän pitäisi tehdä jokin selkeämpi ratkaisu
	//
	// TODO: tästä voitaisiin tehdä loadWithID, tarvitaan joskus, ainakin tasks modulissa
	//	
	public static function load($tablename, $whereclause = "", $comments = false) {
		
		global $mysqli;
		$rawformat = false;
		
		$rows = array();
		if ($mysqli == null) {
			echo "<br>Ei tietokanta yhteyttä 1 - " . $tablename;
			return $rows;
		}
		
		//echo "<br>-tablename -" . $tablename;
		if (!isset(self::$loadedtables[$tablename])) {
			
			// Tämä toiminto on ilmeisest tarkoitettu niille tauluille, jotka hakee general index tablesta, ei muistikuvaa
			//echo "<br>Not table setted";
			$table = self::loadTableStruct($tablename, $comments);
			if ($table == null) {
				echo "<br>Table not found - " . $tablename;
				return null;
			}
			//$table = self::$loadedtables[$tablename];
			
			// TODO Virheilmoitus taulua ei läytynyt
			//echo "<br>Table - " . $tablename . " not found";
			//return null;
		} else {
			$table = self::$loadedtables[$tablename];
		}

		$whereclause = Table::processWhereClause($whereclause, $table->systemspecific, "load - " . $tablename);
		$columns = self::$columns[$tablename];
				//debug_print_backtrace();
		$sql = "SELECT * FROM " . $table->name . " " . $whereclause;
		if ($comments) echo "<br>Select --- " . $sql;
				
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "<br>Error: " . $mysqli->error;
		}
		
		if ($result == null) {
			echo "<br>Rowwi nulli";
		}
		
		while($row = $result->fetch_array()) {
			$item = new Row($columns, $row, $rawformat);
			if ($item->getID() == null) {
				//echo "<br>ket key null";
				$rows[] = $item;
			} else {
				//echo "<br>key - ". $item->getID();
				$rows[$item->getID()] = $item;
			}
		}

		if (count($rows) == 0) return array();
		return $rows;
	}
	
	
	/*
	public static function loadGlobal($con, $tablename, $whereclause = "", $rawformat = false) {
	
		$mysqli = $con;
		$rows = array();
		if ($mysqli == null) {
			echo "<br>Ei tietokanta yhteyttä 2 - " . $tablename;
			return $rows;
		}
	
		//$whereclause = Table::processWhereClause($whereclause, 1, "loadGlobal");
		if ($table->systemspecific == 1) {
			$where = Table::processWhereClause($where, 1, "loadGlobal");
		}
		//if ($whereclause == "") {
		//	echo "<br>Empty where clause";
		//	die();
		//}
	
	
		if (!isset(self::$loadedtables[$tablename])) {
				
			// Tämä toiminto on ilmeisest tarkoitettu niille tauluille, jotka hakee general index tablesta, ei muistikuvaa
			//echo "<br>Not table setted";
			$table = self::loadTableStruct($tablename);
			if ($table == null) {
				echo "<br>Table not found - " . $tablename;
				return null;
			}
			//$table = self::$loadedtables[$tablename];
				
			// TODO Virheilmoitus taulua ei läytynyt
			//echo "<br>Table - " . $tablename . " not found";
			//return null;
		} else {
				
			//echo "<br>Table is setted";
			$table = self::$loadedtables[$tablename];
		}
	
	
			
		//if ($table->tableexists == 0) {
			
		$columns = self::$columns[$tablename];
		//debug_print_backtrace();
		$sql = "SELECT * FROM " . $table->name . " " . $whereclause;
		
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "<br>Error: " . $mysqli->error;
		}
	
		while($row = $result->fetch_array()) {
			$item = new Row($columns, $row, $rawformat);
			if ($item->getID() == null) {
				//echo "<br>ket key null";
				$rows[] = $item;
			} else {
				//echo "<br>key - ". $item->getID();
				$rows[$item->getID()] = $item;
			}
		}
		return $rows;
	}
	*/
	
	
	
	/**
	 * Tämä NoID functio on tarkoitettu kutsuttavaksi sellaisten where taulujen osalta joissa ei ole 
	 * yksittäistä ID-tä, eli multi-column-key. Tälläin taulukon id-numeroksi ei voi laittaa 
	 * ID-saraketta koska se ylikirjoittaa saman avaimen. Muuten kopioitu perus load-funktio
	 * 
	 * 
	 * @param string $tablename
	 * @param string $whereclause
	 * @param string $rawformat
	 */
	public static function loadNoID($tablename, $whereclause = "", $rawformat = false) {
	
		global $mysqli;
		
		$rows = array();
		if ($mysqli == null) {
			echo "<br>Ei tietokanta yhteyttä 3 - " . $tablename;
			return $rows;
		}
	
		if ($whereclause == "") {
			echo "<br>Empty where clause";
			die();
		}
	
		if (!isset(self::$loadedtables[$tablename])) {
			self::loadTableStruct($tablename);
			$table = self::$loadedtables[$tablename];
		} else {
			$table = self::$loadedtables[$tablename];
		}
		
		$whereclause = Table::processWhereClause($whereclause, $table->systemspecific, 'loadNoID - ' . $tablename);
		
		$columns = self::$columns[$tablename];
		$sql = "SELECT * FROM " . $table->name . " " . $whereclause;
		//echo "<br>sql - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "<br>Error: " . $mysqli->error;
		}
	
		while($row = $result->fetch_array()) {
			$item = new Row($columns, $row, $rawformat);
			$rows[] = $item;
		}

		return $rows;
	}
	
	
	// Tässä voisi arraycolumnID olla oletuksena sama kuin wherecolumnID, jos sitä ei ole annettu, tämä on varmaan 
	// yleensä se käyttätapa
	public static function loadWhereInRowArray($tablename, $wherecolumnID, $array, $arraycolumnID) {
	   
		if (($array == null) || (count($array) == 0)) {
			return array();			
		}
		
		$where = "";
		$first = true;
		
		/** @var mixed $index */
		foreach($array as $index => $value) {
			if ($first == true) {
				$first = false;
			} else {
				$where = $where . ",";
			}
			//echo "<br>TargetConcept - " . $value->$arraycolumnID;
			$where = $where . $value->$arraycolumnID;
		}
		return Table::load($tablename, " WHERE " . $wherecolumnID . " IN (" . $where . ")");
	}
	
	
	// TODO: Tämä ei toistaiseksi tue order by määritettä
	public static function loadWhereInArray($tablename, $wherecolumnID, $array, $whereclause = "", $comments = false, $sortstr = "") {
			
		if (($array == null) || (count($array) == 0)) {
			return array();
		}
	
		$idlist = "";
		$first = true;
		
		if (count($array) == 0) return array();
		//echo "<br>Countarray - " . count($array);
		//echo "<br>";
		//print_r($array);
		
		/** @var mixed $value */
		foreach($array as $index => $value) {
			if ($first == true) {
				$first = false;
			} else {
				$idlist = $idlist . ",";
			}
			//echo "<br>TargetConcept - " . $value->$arraycolumnID;
			$idlist = $idlist . $index;
		}
		
		if ($whereclause == "") {
			$whereclause = "WHERE " . $wherecolumnID . " IN (" . $idlist . ")"; // " . $orderby;
		} else {
			$whereclause = $whereclause . " AND " . $wherecolumnID . " IN (" . $idlist . ")" . $sortstr; // " . $orderby;
		}
		
		//if ($comments == true) echo "<br>" . $tablename . " ... " . $whereclause;
		return Table::load($tablename, $whereclause, $comments);
	}
	
	
	
	public static function loadWithPrimaryKeyIndex($tablename,$primaryKey,$where) {
		
		$rows 		 = Table::load($tablename, $where);
		$indexSorted = array();
		
		foreach($rows as $index => $row) {
			
			$primary 	= $row->$primaryKey;
			$indexSorted[$primary] = $row;
					
		}
		
		return $indexSorted;
		
	}

	
	public static function loadRowIndexValuePairs($tablename,$rowID) {
		
			$row = Table::loadRow($tablename,$rowID);
			$datavariables = $row->getDatavariables();
			
			$pairs = array();
			
			foreach($datavariables as $index => $value) {
			
				$pairs[$index] = $row->$index;
			}
		
			return $pairs;
	}

	
	
	/**
	 * Tämä ei kyllä toimi täysin oikein, esim.. $this->registry->hierarchy = Table::loadHierarchy('worder_ inflectionsets','parentID',"WHERE GrammarID=" . $_SESSION['grammarID'] . ' AND LanguageID=' . $languageID .  " ORDER BY Sortorder");
	 * 
	 * @param unknown $tablename
	 * @param unknown $parentvariable
	 * @param string $whereclause
	 * @param string $comments
	 * @return multitype:Ambigous <NULL, multitype:, multitype:unknown Row >
	 */
	public static function loadHierarchy($tablename, $parentvariable, $whereclause = "", $comments = false, $sorted = false) {
			
		
		$rows = Table::load($tablename, $whereclause, $comments);
		
		
		$parents = array();
		$rootparents = array();
		$counter = 0;
				
		while(count($rows) > 0) {
			//echo "<br>Counter - " . $counter . ", rowscount - " . count($rows) . ", passedcount - " . count($passed);
			
			
			foreach($rows as $index => $row) {
				
				//echo "<br>parentvariable - " . $parentvariable . " - " . $row->$parentvariable;
				//if (!isset($passed[$index])) {
					if ($row->$parentvariable == 0) {
						$parents[$row->getID()] = $row;
						$rootparents[$row->getID()] = $row;
						unset($rows[$index]);
					} else {
						
						if (isset($parents[$row->$parentvariable])) {
							$parent = $parents[$row->$parentvariable];
							$parent->addChild($row);
							$parents[$row->getID()] = $row;
							//$parents[$row->getID()] = $row;
							//echo "<br>child found - " . $row->getID() . ", parent - " . $parent->getID();
							//$passed[$index] = 1;
							unset($rows[$index]);
						} else {
							//echo "<br>Parent not found - " . $row->$parentvariable;
						}
					}
				//}
			}
			
			$counter++;
			if ($counter>10000) break;
		}
		
		if ($sorted == true) {
			foreach($rootparents as $index => $root) {
				//echo "<br>Sort childs";
				$root->sortChilds("sortBySortOrder");
			}
		}
		
		return $rootparents;
	}
		
	
	
	
//******************************************************************************************************************
//** KESKEN!!!!!!
//**
//******************************************************************************************************************	
	
	
	/**
	 * Miten tätä käytetään?
	 * 
	 */
	public static function loadMultiHierarchy($tables,$parentKeys,$childKeys,$wheres) {

		$root 			 = Table::load($tables[0],$wheres[0]);
		$tableCount		 = count($tables);
		$currentParents	 = $root;
		
		/** @var mixed $rootIndex */
		foreach($root as $rootIndex => $rootItem) $rootItem->depthlevel = 0;
		
		for($index=1;$index<$tableCount;$index++) {
			
			$currentParents = Table::addChildren($currentParents,$tables[$index],$parentKeys[$index],$childKeys[$index],$wheres[$index],$index);	
			
		}
				
		return $root;
				
	}

	
//******************************************************************************************************************
//** KESKEN!!!!!!
//**
//******************************************************************************************************************
		
	private static function addChildren($parents,$tableName,$parentKey,$childKey,$where,$index) {
		
		$newChildren = array();
		$childItems  = Table::load($tableName,$where);

		/** @var mixed $parentIndex */
		foreach($parents as $parentIndex => $parent) {
			
			$parentKeyValue = $parent->$parentKey;
			
			/** @var mixed $childIndex */
			foreach($childItems as $childIndex => $child) {
				
				$childKeyValue = $child->$childKey;
				
				if ($parentKeyValue==$childKeyValue) {
					
					$child->depthlevel = $index;
					
					$parent->addChild($child);
					$newChildren[] = $child;		
					
				}
			}
		}
		
		return $newChildren;
	}	
	
//******************************************************************************************************************
//***
//***
//******************************************************************************************************************
		
	
	//
	//	Tämän funktion tarkoituksena on ladata taulukkoon tietty määrä rivejä.
	//
	public static function loadRowsByLimitAndOffset($tablename,$limit,$offset,$sort = null,$direction = null,$whereclause=null, $rawformat = false) {
	
		global $mysqli;
		$table 	   = Table::getTable($tablename);
		$tablename = $table->name;
		$columns   = self::$columns[$tablename];
		
		$sql  = "SELECT * FROM ".$tablename." ";
		if ($sort != null) {
			$sql .= "ORDER BY ".$sort." ".$direction." ";
		}
		if ($whereclause != null) {
			$sql .= "WHERE " . $whereclause . " ";
		}
		
		$sql .= "LIMIT ".$limit." ";
		$sql .= "OFFSET ".$offset." ";
		
		//echo "<br>sql - " . $whereclause;
		//echo "<br>sql - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) die('Load tablecontent failed 2: ' . $mysqli->connect_error);
	
		$rows = array();
		
		while($row = $result->fetch_array()) {
			$item = new Row($columns,$row, $rawformat);
			$rows[] = $item;
		}
		
		return $rows;
	}
	
	
	public static function arrayToString($array, $variable) {
		$str = "(";
		$first = true;
		foreach($array as $index => $row) {
			$value = $row->$variable;
			echo "<br>index - " . $index . " - " . $value;
			if ($first == true) {
				$str = $str . $value;
				$first = false;
			} else {
				$str = $str . "," . $value;
			}
		}
		$str = $str . ")";
		return $str;
	}
	
	
	// Source parametri on virheiden lähteen träkkäämiseen
	private static function processWhereClause($where, $systenspecific, $source = null) {

		// Pitäisi oikeastaan tarkistaa vielä, joko where-stringi sisältää systemID:n
		$where = trim($where);
		
		$comments = false;
		if ($comments) echo "<br>processWhereClause --" . $where . "--";
		
		if (($where == null) || ($where == "")) {
			if ($systenspecific == 1) {
				$whereclause = "WHERE SystemID=" . $_SESSION['systemID'];
			} else {
				$whereclause = "";
			}
			if ($comments) echo "<br>where --" . $whereclause . "--";
			return $whereclause;
		}
		
		$pos = strpos($where, "SystemID=");
		if ($pos != false) {
			if ($comments) echo "<br>Query allready has systemID where clause";
			return $where;
		}
		
		$pos = strpos($where, "WHERE ");
			
		if ($pos === false) {
			$pos = strpos($where, "ORDER BY");
			if ($pos === false) {
				echo "<br>WHERE or ORDER clause missing from query - " . $where;
				echo "<br>Source - " . $source;
				debug_print_backtrace();
				die();
			}
			// $where-clause contains only order by -clause, we need to add systemID
			if ($systenspecific == 1) {
				if ($comments) echo "<br>-- table is system spesific";
				$whereclause = "WHERE SystemID=" . $_SESSION['systemID'] . " " . $where;
			} else {
				if ($comments) echo "<br>-- table no system spesific";
				// ORDER BY Kysely sellaisenaan...
				$whereclause = $where;
			}
			if ($comments) echo "<br>where --" . $whereclause . "--";
			return $whereclause;
		}

		if ($systenspecific == 1) {
			$whereclause = "WHERE SystemID=" . $_SESSION['systemID'] . " AND " . substr($where,$pos+5);
			if ($comments) echo "<br>where --" . $whereclause . "--";
			return $whereclause;
		} else {
			return $where;
		}
	}
	
	
	
	// TODO: Ota systemID  mukaan hakuun
	public static function loadWithPaging($tablename,$startpage,$rowsperpage, $where = "", $comments = false) {
	
		//$comments = true;
		if ($comments) echo "<br>loadWithPaging Where - " . $where;
		
		global $mysqli;
		$table = Table::getTable($tablename);
		$tablename = $table->name;
		$columns = $table->getColumns();
		
		if ($table->systemspecific == 1) {
			$where = Table::processWhereClause($where, $table->systemspecific, "loadWithPaging");
		}
		
		//$sql  = "SELECT * FROM ".$tablename." " . $where . " LIMIT ".$rowsperpage." OFFSET " . (($startpage-1)*$rowsperpage) . "";
		$sql  = "SELECT * FROM ".$tablename." " . $where . "";
		if ($comments) echo "<br>Sql - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) die('Load tablecontent failed 3: ' . $mysqli->connect_error);
	
		$rows = array();
		while($row = $result->fetch_array()) {
			$item = new Row($columns,$row);
			$rows[] = $item;
		}
		return $rows;
	}
	
	

	// TODO: Tämä pitää muuttaa ei grammar specifiksi
	public static function searchString($tablename,$searchcolumn, $searchword, $maxrows, $keycolumn = null) {
	
		global $mysqli;
		if ($keycolumn == null) {
			$sql  = "SELECT " . $searchcolumn . " FROM ".$tablename." WHERE GrammarID=" . $_SESSION['grammarID'] . " AND " . $searchcolumn . " LIKE '" . $searchword . "%' LIMIT " . $maxrows;
			$result = $mysqli->query($sql);
			if (!$result) die('Load tablecontent failed 44: ' . $mysqli->connect_error);
			
			$rows = array();
			while($row = $result->fetch_array()) {
				$item = $row[$searchcolumn];
				$rows[] = $item;
			}
			return $rows;
			
		} else {
			$sql  = "SELECT " . $keycolumn . "," . $searchcolumn . " FROM ".$tablename." WHERE GrammarID=" . $_SESSION['grammarID'] . " AND  " . $searchcolumn . " LIKE '" . $searchword . "%' LIMIT " . $maxrows;
			$result = $mysqli->query($sql);
			if (!$result) die('Load tablecontent failed 44: ' . $mysqli->connect_error);
			
			$rows = array();
			while($row = $result->fetch_array()) {
				$key = $row[$keycolumn];
				$item = $row[$searchcolumn];
				$rows[$key] = $item;
			}
			return $rows;
		}
		//echo "<br>" . $sql . "<br>";
		
	}
	
	

	public static function countRows($tablename, $columnname, $where) {
	
		//echo "<br>Where - " . $where;
	
		global $mysqli;
		$table = Table::getTable($tablename);
		$tablename = $table->name;
		
		$sql  = "SELECT COUNT(" . $columnname . ") FROM ".$tablename." " . $where . "";
		//echo "<br>Sql - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) die('Load tablecontent failed 3: ' . $mysqli->connect_error);
		//echo "<br>Sql - " . print_r($result->fetch_row());
		$resultarray = $result->fetch_row();
		//echo "<br>Sql result - " . print_r($resultarray);
		return $resultarray[0];
	}
	
	
	


	public static function customWhereClause($sql) {
	
		global $mysqli;
		//$table = Table::getTable($tablename);
		//$tablename = $table->name;
	
		$result = $mysqli->query($sql);
		if (!$result) die('Load tablecontent failed 3: ' . $mysqli->connect_error);
		$resultarray = $result->fetch_row();
		return $resultarray[0];
	}
	
	

//******************************************************************************************************************
//***
//***
//******************************************************************************************************************
	
	public static function countTableRows($tablename,$whereclause = "") {
		
		global $mysqli;
		
		$sql = "SELECT COUNT(*) FROM ". $tablename . " " . $whereclause;
		
		//echo "<br>sql - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) die('Load tablecontent failed 4: ' . $mysqli->connect_error);
		
		$count  = $result->fetch_array();
		
		return $count[0];
	}

//******************************************************************************************************************
//***
//***
//******************************************************************************************************************
	
	/*
	public static function updatePureSQL($sql) {
	
		global $mysqli;
		$result = $mysqli->query($sql);
		
		if (!$result) return("Pure SQL Updatefail " . $tablename . " failed: " . $mysqli->connect_error);

		return true;
	}
	*/
	
//******************************************************************************************************************
//*** PUBLIC STATIC FUNCTION ROWEXISTS($tablename,$rowID);
//*** Checks if row with rowID exists in table.
//******************************************************************************************************************

	public static function rowExists($tablename,$where, $comments = false) {
			
		global $mysqli;
		$sql   = "SELECT 1 FROM ".$tablename." WHERE ".$where;
		if ($comments) echo "<br>" . $sql;		
		$result = $mysqli->query($sql);
		if ($result == null) return false;
		if ($result->num_rows == 0) return false;
		/*			
		$rowCount = mysql_num_rows($mysqli);
		if ($rowCount == 0) {
			return false;
		} 
		*/
		return true;
	}
	
//******************************************************************************************************************
//***
//***
//******************************************************************************************************************
	
	public static function getPrimapryKey($tablename) {
		
		if (!isset(self::$loadedtables[$tablename])) {
			
			self::loadTableStruct($tablename);
			$table = self::$loadedtables[$tablename];
			
		} else {
		
			$table = self::$loadedtables[$tablename];
		}
		
		$columns = $table->getColumns();

		foreach($columns as $index => $column) {
			if ($column->type == 2) return $column;
		}
	}
	
//******************************************************************************************************************
//***
//***
//******************************************************************************************************************
	
	public static function loadKeyValueArray($table, $keycolumn, $valuecolumn, $whereclause = null, $language = 0) {
		
		// if value is multilangstring, take language value
		
		global $mysqli;
		if ($whereclause == null) {
			$sql = "SELECT * FROM " . $table . "";
		} else {
			$sql = "SELECT * FROM " . $table . " WHERE " . $whereclause;
		}
		//echo "<br>SQL - " . $sql;
		
		$rows = array();
		
		$result = $mysqli->query($sql);
		while($row = $result->fetch_array()) {
			$key = $row[$keycolumn];
			
			if ($language == 0) {
				$value = $row[$valuecolumn];
			} else {
				$value = parseMultilangString($row[$valuecolumn], $language);
			}
			$rows[$key] = $value;
		}
		
		return $rows;
	}
	
	//******************************************************************************************************************
	//***
	//***
	//******************************************************************************************************************
	
	public static function loadKeyValuePairsMultipleValues($table, $keycolumn, $valuecolumns, $divider = " ",$whereclause = null) {
	
		global $mysqli;
		if ($whereclause == null) {
			$sql = "SELECT * FROM " . $table . "";
		} else {
			$sql = "SELECT * FROM " . $table . " WHERE " . $whereclause;
		}
	
		$rows = array();
		
		$result = $mysqli->query($sql);
		
		while($row = $result->fetch_array()) {
			
			$key 	= $row[$keycolumn];
			$value 	= "";
			
			foreach($valuecolumns as $index => $column) {
				$value .= $row[$column].$divider;
			}
			
			$value 		= trim($value,$divider);
			$rows[$key] = $value;
		}
	
		return $rows;
		
	}
	
	
//******************************************************************************************************************
//***
//***
//******************************************************************************************************************
	
	public static function loadTables($includeremoved = false, $sort = null) {
			
		global $mysqli;
		$items = array();
		
		if ($includeremoved == true) {
			$sql = "SELECT * FROM system_tables " . $sort;
		} else {
			$sql = "SELECT * FROM system_tables " . $sort;
		}
	
		$result = $mysqli->query($sql);
		if (!$result) {
			//echo "<br>sql - " . $sql;
			die('Load tablecontent failed 5: ' . $mysqli->connect_error);
		}
		
		while($row = $result->fetch_array()) {
			$item = new Table($row);
			$items[$item->getID()] = $item;
		}
		return $items;
	}
	
//******************************************************************************************************************
//***
//***
//******************************************************************************************************************

	// tämä palauttaa reference tablen row-descriptionin. Palauttaa parametrina annetun taulun ja rivin sellaisen
	// sarakkeen arvon, jonka column.logvalue on 1
	private static function getReferenceTableValue() {
		
	}
	
	public static function addRowWithKey($tablename, $values = array()) {
		return Table::addRow($tablename, $values, true, true, true);
	}
	
	public static function addRow($tablename, $values = array(), $comments = false, $override = false, $key = false) {
		
		//$comments = true;
		//if ($comments) echo "<br>";
		
		global $mysqli;
		
		
		
		$table = Table::getTable($tablename,$comments);
		$columns = $table->getColumns();
		if ($comments) echo "<br>Lisätään tauluun " . $tablename;
		if ($comments) echo "<br>columncount " . count($columns);
		if ($comments) echo "<br>";

		$valuearray = array();
		if ($values instanceof Row) {
			$row = $values;
			if ($comments) echo "<br>Adding copyrow";
			foreach($columns as $columnID => $column) {
				if ($comments) echo "<br>column->name xxx - ". $column->columnname;
				$addvalue = true;
				if (($column->type == Column::COLUMNTYPE_KEY) || 
					($column->type == Column::COLUMNTYPE_INSERTDATE) ||
					($column->type == Column::COLUMNTYPE_INSERTID) ||
					($column->type == Column::COLUMNTYPE_MODIFIEDDATE) ||
					($column->type == Column::COLUMNTYPE_MODIFIEDID) ||
					($column->type == Column::COLUMNTYPE_REMOVEDATE) ||
					($column->type == Column::COLUMNTYPE_REMOVEID) ||
					($column->type == Column::COLUMNTYPE_INTEGERARRAY) ||
					($column->type == Column::COLUMNTYPE_KEYVALUELIST) ||
					($column->type == Column::COLUMNTYPE_ORDER)) {
						$addvalue = false;
				} 
				if ($addvalue == true) {
					$var = $column->variablename;
					$valuearray[$column->columnname] = $row->$var;
				} else {
					if (($key == true) && ($column->type == Column::COLUMNTYPE_KEY)) {
						$var = $column->variablename;
						$valuearray[$column->columnname] = $row->$var;
					}
				}
			}
			$values = $valuearray;
		}
		
		if ($comments) echo "<br>";
		if ($comments) print_r($values);
		if ($comments) echo "columns countti - " . count($columns);
		
		$acceptedvalues = array();
		$keycolumn = null;
		
		if ($table->systemspecific == 1) {
			if ($comments) echo "<br>System specific yes";
			if (!isset($values['SystemID'])) {
				if ($comments) echo "<br>-- systemID not setted";
				$values['SystemID'] = $_SESSION['systemID'];
			} else {
				if ($comments) echo "<br>-- systemID yes setted";
				$values['SystemID'] = $values['SystemID'];
			}
		} else {
			if ($comments) echo "<br>System specific no";
		}
			
		
		if ($comments) echo "<br>Columsn count " . count($columns);
		
		/** @var mixed $columnID */
		foreach($columns as $columnID => $column) {
			// InsertDate ei saa olla asetettu
			// jos insertdate niin lisätään automaattisesti acceptedvaluessiin value -- nämä menee logiin
			// jos insertuser niin lisätään automaattisesti acceptedvaluessiin insertuser -- nämä menee logiin
			
			if ($comments) echo "<br>column->name - ". $column->columnname;
			
			if ($column->type == 1) {	// viite taulu
				if (!isset($values[$column->columnname])) {
					if ($column->obligatory == 1) {
						echo "<br>Referencetaulun arvo on asetettava - " . $column->columnname;
						exit;
					} else {
						$values[$column->columnname] = 0;
						// viite saa olla nolla
					}
				}
			}
			
			
			if ($column->type == Column::COLUMNTYPE_KEY) {
				$keycolumn = $column;
					
				if (isset($values[$column->columnname])) {
					if ($comments) echo "<br>Keycolumn found " . $column->columnname;
					if ($override) $acceptedvalues[$column->columnname] = $values[$column->columnname];
					
					//return "Avaimelle" . $column->columnname . " ei saa asettaa arvoa";		// key on aina autoincrement
				}
			} else {
				if ($comments) echo "<br>Not key";
				if (isset($values[$column->columnname])) {
					if ($comments) echo "<br>Column - " . $column->columnname . " - "  . $values[$column->columnname] ;
					
					$acceptedvalues[$column->columnname] = $values[$column->columnname];
				} else {
					if ($comments) echo "<br>Not set";
						
					if (($column->obligatory == 1) && ($column->type != Column::COLUMNTYPE_ORDER)) {
						
						if ($column->columnname == "SystemID") {
							$acceptedvalues["SystemID"] = $_SESSION['systemID'];
 						} else {
 							echo "<br>Kenttä " . $column->columnname . " on pakollinen";
 							exit;
 							return "Kenttä " . $column->columnname . " on pakollinen";
 						}
						
					}
				}
			}
		}
		
		
		
		
		$sqlcolumns = "";
		$sqlvalues = "";
		$logvalues = "";
		$delimiter = null;
		
		foreach($acceptedvalues as $columnname => $columnvalue) {
			$sqlcolumns = $sqlcolumns . $delimiter . $columnname;
			$sqlvalues = $sqlvalues . $delimiter . "'" . $columnvalue . "'";
			$logvalues = $logvalues . $delimiter . $columnname . "=" . $columnvalue;
			if ($delimiter == null) $delimiter = ",";
		}
		
		$sql = "INSERT INTO " . $table->name . " (" . $sqlcolumns . ") VALUES (" . $sqlvalues . ")";
		if ($comments) echo "<br>SQL - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) {
			if ($comments) echo "<br> - insert rows failed - " . $mysqli->error;
			return "insert rows failed - " . $mysqli->error;
		} 
		$rowID = $mysqli->insert_id;
		$logvalues = "(" . $rowID . ") " . $logvalues;
		
		
		
		
		if ($rowID > 0) {
			
			//$comments = true;
			$sql = "SELECT * FROM " . $table->name . " WHERE " . $keycolumn->columnname . "=" . $rowID . "";
			if ($comments) echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if ($result->num_rows == 0) return 0;
			$row = $result->fetch_array();
			
			
			
			$references = array();
			
			foreach($columns as $index => $column) {
				// Joissakin tapauksissa mainserveriltä ladatut tableID:t eroavat lokaalista, tämän takia reftableID:t on joisssakin tapauksissa 0
				// TODO: Synkkaa tableID:t 
				if ($column->referencetableID != 0) {
					if ($column->type == 1) $references[$column->referencetableID] = $row[$column->columnname];
				}
			}
			
			
			// päivitetään sortID täsmäämään rowID:tä
			foreach($columns as $index => $column) {
				if ($column->type == Column::COLUMNTYPE_ORDER) {	// reference column
					$sql = "UPDATE " . $table->name . " SET " .  $column->columnname . "='" . $rowID . "' WHERE " . $keycolumn->columnname . "='". $rowID . "'";
					if ($comments) echo "<br>" . $sql;
					$result = $mysqli->query($sql);
					if (!$result) return("updateTable " . $tablename . " failed: " . $mysqli->connect_error);
				}
			}

			if ($comments) echo "<br>Creating log line - " . $tablename . " - " . $table->tableID;
			$logstr = Table::createLogLine($table->tableID, $columns, $row, false);
			self::addLogLine($table->logtable, $table->tableID, $rowID, "Add: " . $logstr, Table::LOG_INSERT);
			
			

			// logitus kaikkiin reference tableihin...
			foreach($references as $tableID => $idValue) {
				$dbtable = Table::getTableWithID($tableID);
				if ($comments) echo "<br>logstr - " .  $dbtable->logtable;
				if ($comments) echo "<br>tablename - " .  $dbtable->name;
				if ($comments) echo "<br>idValue aaaa - " .  $idValue;
				
				
				$logvaluestr = Table::createLogLine($tableID, $columns, $row, false);
				self::addLogLine($dbtable->logtable, $dbtable->tableID, $idValue, "RefAdd:" . $logvaluestr, Table::LOG_DELETE);
				if ($comments) echo "<br>Referencetable --> " . $tableID . " - " . $column->variablename . " - " . $dbtable->logtable;
			}
			
			
		}
		
		//echo "<br>--------------- RowID - " . $rowID;
		return $rowID;
	}
	
	
	
	


	private static function addRowPrivate($tablename, $row, $comments = false, $override = false) {
	
		global $mysqli;
	
		$table = Table::getTable($tablename,$comments);
		$columns = $table->getColumns();
		if ($comments) echo "<br>Lisätään tauluun " . $tablename;
		if ($comments) echo "<br>columncount " . count($columns);
		if ($comments) echo "<br>";
		foreach($columns as $columnID => $column) {
			if ($comments) echo "<br>column->name xxx - ". $column->columnname;
			
			if ($column->type != Column::COLUMNTYPE_KEY) {
				
			}
			
		}
		if ($comments) echo "<br>";
		if ($comments) print_r($values);
		if ($comments) echo "columns countti - " . count($columns);
	
		$acceptedvalues = array();
		$keycolumn = null;
	
		if ($table->systemspecific == 1) {
			if ($comments) echo "<br>System specific yes";
			if (!isset($values['SystemID'])) {
				if ($comments) echo "<br>-- systemID not setted";
				$values['SystemID'] = $_SESSION['systemID'];
			} else {
				if ($comments) echo "<br>-- systemID yes setted";
				$values['SystemID'] = $values['SystemID'];
			}
		} else {
			if ($comments) echo "<br>System specific no";
		}
			
	
		if ($comments) echo "<br>Columsn count " . count($columns);
	
		/** @var mixed $columnID */
		foreach($columns as $columnID => $column) {
			// InsertDate ei saa olla asetettu
			// jos insertdate niin lisätään automaattisesti acceptedvaluessiin value -- nämä menee logiin
			// jos insertuser niin lisätään automaattisesti acceptedvaluessiin insertuser -- nämä menee logiin
				
			if ($comments) echo "<br>column->name - ". $column->columnname;
				
			if ($column->type == 1) {	// viite taulu
				if (!isset($values[$column->columnname])) {
					if ($column->obligatory == 1) {
						echo "<br>Referencetaulun arvo on asetettava - " . $column->columnname;
						exit;
					} else {
						$values[$column->columnname] = 0;
						// viite saa olla nolla
					}
				}
			}
				
				
			if ($column->type == Column::COLUMNTYPE_KEY) {
				$keycolumn = $column;
					
				if (isset($values[$column->columnname])) {
					if ($comments) echo "<br>Keycolumn found " . $column->columnname;
					if ($override) $acceptedvalues[$column->columnname] = $values[$column->columnname];
						
					//return "Avaimelle" . $column->columnname . " ei saa asettaa arvoa";		// key on aina autoincrement
				}
			} else {
				if ($comments) echo "<br>Not key";
				if (isset($values[$column->columnname])) {
					if ($comments) echo "<br>Column - " . $column->columnname . " - "  . $values[$column->columnname] ;
						
					$acceptedvalues[$column->columnname] = $values[$column->columnname];
				} else {
					if ($comments) echo "<br>Not set";
	
					if (($column->obligatory == 1) && ($column->type != Column::COLUMNTYPE_ORDER)) {
	
						if ($column->columnname == "SystemID") {
							$acceptedvalues["SystemID"] = $_SESSION['systemID'];
						} else {
							echo "<br>Kenttä " . $column->columnname . " on pakollinen";
							exit;
							return "Kenttä " . $column->columnname . " on pakollinen";
						}
	
					}
				}
			}
		}
	
	
	
	
		$sqlcolumns = "";
		$sqlvalues = "";
		$logvalues = "";
		$delimiter = null;
	
		foreach($acceptedvalues as $columnname => $columnvalue) {
			$sqlcolumns = $sqlcolumns . $delimiter . $columnname;
			$sqlvalues = $sqlvalues . $delimiter . "'" . $columnvalue . "'";
			$logvalues = $logvalues . $delimiter . $columnname . "=" . $columnvalue;
			if ($delimiter == null) $delimiter = ",";
		}
	
		$sql = "INSERT INTO " . $table->name . " (" . $sqlcolumns . ") VALUES (" . $sqlvalues . ")";
		if ($comments) echo "<br>SQL - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) {
			if ($comments) echo "<br> - insert rows failed - " . $mysqli->error;
			return "insert rows failed - " . $mysqli->error;
		}
		$rowID = $mysqli->insert_id;
		$logvalues = "(" . $rowID . ") " . $logvalues;
	
	
	
	
		if ($rowID > 0) {
				
				
			$sql = "SELECT * FROM " . $table->name . " WHERE " . $keycolumn->columnname . "=" . $rowID . "";
			if ($comments) echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if ($result->num_rows == 0) return 0;
			$row = $result->fetch_array();
				
				
				
			$references = array();
				
			foreach($columns as $index => $column) {
				// Joissakin tapauksissa mainserveriltä ladatut tableID:t eroavat lokaalista, tämän takia reftableID:t on joisssakin tapauksissa 0
				// TODO: Synkkaa tableID:t
				if ($column->referencetableID != 0) {
					if ($column->type == 1) $references[$column->referencetableID] = $row[$column->columnname];
				}
			}
				
				
			// päivitetään sortID täsmäämään rowID:tä
			foreach($columns as $index => $column) {
				if ($column->type == Column::COLUMNTYPE_ORDER) {	// reference column
					$sql = "UPDATE " . $table->name . " SET " .  $column->columnname . "='" . $rowID . "' WHERE " . $keycolumn->columnname . "='". $rowID . "'";
					if ($comments) echo "<br>" . $sql;
					$result = $mysqli->query($sql);
					if (!$result) return("updateTable " . $tablename . " failed: " . $mysqli->connect_error);
				}
			}
	
			$logstr = Table::createLogLine($table->tableID, $columns, $row, false);
			self::addLogLine($table->logtable, $table->tableID, $rowID, "Add: " . $logstr, Table::LOG_INSERT);
				
				
	
			// logitus kaikkiin reference tableihin...
			foreach($references as $tableID => $idValue) {
				$dbtable = Table::getTableWithID($tableID);
				if ($comments) echo "<br>logstr - " .  $dbtable->logtable;
				if ($comments) echo "<br>tablename - " .  $dbtable->name;
				if ($comments) echo "<br>idValue bbb - " .  $idValue;
	
	
				$logvaluestr = Table::createLogLine($tableID, $columns, $row, false);
				self::addLogLine($dbtable->logtable, $dbtable->tableID, $idValue, "RefAdd:" . $logvaluestr, Table::LOG_DELETE);
				if ($comments) echo "<br>Referencetable --> " . $tableID . " - " . $column->variablename . " - " . $dbtable->logtable;
			}
				
				
		}
	
		//echo "<br>--------------- RowID - " . $rowID;
		return $rowID;
	}
	
	
	
	
	
	
	/**
	 * Tama funktio olettaa, etta sisalta on jo kertaalleen tarkistettua. Lisaksi values-taulussa asetettu oletusarvot mikali niita on maaritelty.
	 * 
	 */

//******************************************************************************************************************
//***
//***
//******************************************************************************************************************
	
	/*
	private static function addRowPrivate($table, $columns, $values, $comments = false) {
		
		global $mysqli;
		$currentdate = date('Y-m-d H:i:s');
		$userID = $_SESSION['userID'];
		$errormessages = array();
		
		$sqlcolumns = "";
		$sqlvalues = "";
		$first = true;
		
		foreach($values as $index => $value) {
			if ($first == true) {
				$sqlcolumns = $sqlcolumns . "" . $columns[$index]->columnname;
				$sqlvalues = $sqlvalues . "'" . $value . "'";
				$first = false;
			} else {
				$sqlcolumns = $sqlcolumns . "," . $columns[$index]->columnname;
				$sqlvalues = $sqlvalues . ",'" . $value . "'";
			}
		}
		
		$sql = "INSERT INTO " . $table->name . " (" . $sqlcolumns . ") VALUES (" . $sqlvalues . ")";
		if ($comments) echo "<br>SQL - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) {
			if ($comments) echo "<br> - insert rows failed - " . $mysqli->error;
			$errormessages[] = "insert rows failed - " . $mysqli->error;
			return $errormessages;
			// lisää käyttäjäkohtaiseen logiin tämä actioni
		} else {
			return true;
			// lisaa kayttajakohtaiseen logiin tama actioni
		}
		
		if (count($errormessages) == 0) return true;
		return false;
	}
	*/
	
	
//******************************************************************************************************************
//***
//***  $selector = keyID or Where clause (without WHERE keyword)
//***
//******************************************************************************************************************
	
	
//******************************************************************************************************************
//***
//***
//******************************************************************************************************************
	
	public static function removeTable($tableID) {

		global $mysqli;
		$removedate = date('Y-m-d H:i:s');
		$userID = $_SESSION['userID'];
		
		$sql = "UPDATE system_columns SET Removedate='" . $removedate . "', RemoveID='" . $userID . "' WHERE TableID='".$tableID."'";
		$result = $mysqli->query($sql);
		if (!$result) return 'removeTable failed: ' . $mysqli->connect_error;
		
		$sql = "UPDATE system_tables SET Removedate='" . $removedate . "', RemoveID='" . $userID . "' WHERE TableID='".$tableID."'";
		$result = $mysqli->query($sql);
		if (!$result) return 'removeTable failed: ' . $mysqli->connect_error;
		return true;
	}
	
	
	public static function removeColumn($columnID, &$tableID) {
	
		global $mysqli;
		$removedate = date('Y-m-d H:i:s');
		$userID = $_SESSION['userID'];

		$column = Column::load($columnID);
		$table = Table::loadTableStructWithID($column->tableID);
		// load column --> saadaan tableID
		
		$sql = "DELETE FROM system_columns WHERE ColumnID=".$columnID;
		echo "<br>--" . $sql;
		$result = $mysqli->query($sql);
		if (!$result) {
			echo 'removeTable failed: ' . $mysqli->connect_error;
			return 'removeTable failed: ' . $mysqli->connect_error;
		}
	
		// tähän pitäisi tehdä myäs tietokanta taulun rakenteen muutos (mutta logitus on hankalampaa)
		$sql = "ALTER TABLE " . $table->name . " DROP COLUMN " . $column->columnname;
		echo "<br>sql - " . $sql;
		
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "<br>removeTable failed: " . $mysqli->connect_error;
			return 'removeTable failed: ' . $mysqli->connect_error;
		}
		
		$tableID = $table->tableID;
		
		return true;
	}
	

//******************************************************************************************************************
//***
//***
//******************************************************************************************************************
	
	public static function exists($tablename) {
		global $mysqli;
		$sql = "SELECT * FROM system_tables WHERE Name='" . $tablename . "'";
		$result = $mysqli->query($sql);
		if (!$result) return 'exits failed: ' . $mysqli->connect_error;
		if ($result->num_rows == 0) return false;
		return true;
	}
	
	
//******************************************************************************************************************
//***
//***
//******************************************************************************************************************

	
	private static function addLogLine($logtablename, $tableID, $rowID, $valuestr, $logtype, $userID = 0) {
		global $mysqli;
		
		//echo "<br>Logtablename - " . $logtablename . " - "  . $tableID;
		
		if (($logtablename == null) || ($logtablename == "")) {
			
			echo "<br>Logtablename - " . $logtablename . " - "  . $tableID;
			
			// TODO: Tämä on toistaiseksi poistettu käytöstä, koska synkronisointi ei aseta ReferencetableID:tä oikein, jää kokonaan asettamatta
			echo "<br>Logtable pitää olla asetettuna 1506.";
			return;
			//exit();
		}

		if ($userID == 0) $userID = $_SESSION['userID'];
		$timestamp = date("Y-m-d H:i:s");
		$sql = "INSERT INTO " . $logtablename . " (UserID, TableID, KeyID, LogtypeID, Logtime, Description) "
				. " VALUES (" . $userID . "," . $tableID . "," .  $rowID . ",". $logtype . ",'" . $timestamp . "','" . $valuestr  . "')";
		//echo "<br>sql - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "<br>Sql - "  .$sql;
			echo "<br>logtable insert222  " . $logtablename . " failed: " . $mysqli->error;
			return false;
		}
		return true;
	}
	
	/*
	 * 	public static function updateoldRow($tablename, $columnorvalue, $whereorvalue, $where = null) {
		
		if (is_numeric($whereorvalue)) {
			echo "<br>updateRow parameter 3 should be where clause";
			exit();
		}
		
		if ($where == null) {
			if (is_array($columnorvalue)) {
				return Table::updateFullRow($tablename, $columnorvalue, $whereorvalue);					
			} else {
				return("updateRow invalid parameters");
			}
		}
		
		//echo "<br>isarray xx - " . is_array($columnorvalue);
		//echo is_array($columnorvalue) ? 'Array' : 'not an Array';
		if (is_array($columnorvalue)) {
			return Table::updateFullRow($tablename, $columnorvalue, $whereorvalue);
		} else {
			echo "<br>OLD: Table::updateRow Single($tablename, $columnorvalue, $whereorvalue, $whereorvalue);";
		}
	}
	*/
	
	/*
	public static function updateIDRow($tablename, $columnvalues, $id) {
		
		global $mysqli;
		$dbtable = Table::getTable($tablename);
		
		$columns = $dbtable->getColumns();
		
		$keycolumn = 0;
		$punctutation = "";
		foreach($columns as $columnID => $column) {
			if ($column->type == 2) $keycolumn = $column;
			if (isset($columnvalues[$column->columnname])) {
				$valuestr = $valuestr . $punctutation . $column->columnname . "='" . $columnvalues[$column->columnname] . "'";
				if ($punctutation == "") $punctutation = ",";
			}
		}
		
		$sql = "UPDATE " . $tablename . " SET " . $valuestr . " WHERE " . $keycolumn->columnname . "='" . $id . "'";
		//echo "<br>Sql - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) return("updateTable " . $tablename . " failed: " . $mysqli->connect_error);
		return true;
	}
	*/
	
	
	// TODO: Siisti koodi ja kuvaavammat muuttujien nimet
	// TODO: Olisi mahdollista ehkä poistaa reference tablet automatic, mutta jossain pitäisi pitääkä näin tehdä
	//		 (foreign key constraint delete tms.), tätä ei voida tietää, mutta linkkitauluista kyllä pitäisi ainakin, se
	// 		ei poista varsinaista targettia vain linkin sinne
	//
	public static function deleteRow($tablename, $id, $comments = false) {
	
		global $mysqli;
	
		if ($id == null) {
			echo "<br>ID on nulli - " . $tablename;
			$comments = true;
		}
		
		if (!is_numeric($id)) {
			return Table::deleteRowsWhere($tablename, $id, $comments);
		}
		if ($comments) echo "<br>Removetable - " . $tablename;
		$table = Table::getTable($tablename);
		$columns = $table->getColumns();
		$keycolumn = null;
		foreach($columns as $columnID => $column) {
			if ($column->type == 2) $keycolumn = $column;
		}
	
		if ($table->systemspecific == 1) {
			$sql = "SELECT * FROM " . $table->name . " WHERE SystemID=" . $_SESSION['systemID'] . " AND " . $keycolumn->columnname . "=" . $id . "";
		} else {
			$sql = "SELECT * FROM " . $table->name . " WHERE " . $keycolumn->columnname . "=" . $id . "";
		}
		if ($comments) echo "<br>sql - " . $sql;
		$result = $mysqli->query($sql);
		if ($result->num_rows == 0) {
			//echo "<br>RowCount - 0";
			return 0;
		}
		$row = $result->fetch_array();
		//if ($comments) var_dump($row);
	
		$references = array();
		foreach($columns as $columnID => $column) {
			if ($column->type == 2) $keycolumn = $column;
			if ($column->type == 1) {
				if ($row[$column->columnname] != null) {
					if ($comments) echo "<br>Adding to references - " . $column->columnname . " - value:" . $row[$column->columnname] . " - " . $column->referencetableID;
					$references[$column->referencetableID] = $row[$column->columnname];
				} else {
					if ($comments) echo "<br>Adding to references no value found - " . $column->columnname . " - value:" . $row[$column->columnname];
					//echo "<br>Column - " . $column->columnname . " null";
					//$references[$column->referencetableID] = $row[$column->columnname];
				}
			}
		}
	
		if ($table->systemspecific == 1) {
			$sql = "DELETE FROM " . $table->name . " WHERE SystemID=" . $_SESSION['systemID'] . " AND ". $keycolumn->columnname . "=" . $id;
		} else {
			$sql = "DELETE FROM " . $table->name . " WHERE ". $keycolumn->columnname . "=" . $id;
		}
		if ($comments) echo "<br>sql - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "deletetable  " . $table->name . " failed: " . $mysqli->connect_error;
			exit();
		}
	
		//echo "<br>Delete log line creation. ";
		$logvaluestr = Table::createLogLine($table->tableID, $columns, $row, $comments);
		if ($comments) echo "<br>log delid - " . $id;
		if ($comments) echo "<br>log table - " . $table->name . " - " . $table->logtable;
		self::addLogLine($table->logtable, $table->tableID, $id, "RowDel: " . $logvaluestr, Table::LOG_DELETE);
	
		// logitus kaikkiin reference tableihin...
		foreach($references as $tableID => $idValue) {
			
			if ($comments) echo "<br>greating log reference - " .  $tableID;
				
			$dbtable = Table::getTableWithID($tableID);
			if ($comments) echo "<br>referencetable - " . $dbtable->name . " - "  .  $dbtable->logtable;
			//if ($comments) echo "<br>tablename - " .  $dbtable->name;
			if ($comments) echo "<br>idValue ccc - " .  $idValue;
	
			$logvaluestr = Table::createLogLine($tableID, $columns, $row, false);
			self::addLogLine($dbtable->logtable, $dbtable->tableID, $idValue, "RefDel:" . $logvaluestr, Table::LOG_DELETE);
			if ($comments) echo "<br>Referencetable --> " . $tableID . " - " . $column->variablename . " - " . $dbtable->logtable;
		}
		return true;
	}
	
	
	
	// TODO: tähän tarvitaan systemID mukaan
	public static function deleteRowsWhere($tablename,$whereclause, $comments = false) {
	
		if ($whereclause == null) {
			echo "<br>deleteRowsWhere - where clause is null";
			exit;
		}
		$wherepos = strpos($whereclause, "WHERE");
		if ($wherepos === false) {
			echo "<br>deleteRowsWhere - where clause missing - " . $whereclause . " - " . $wherepos;
			exit;
		}
		
		$rows = Table::load($tablename, $whereclause, $comments);
		$counter = 0;
		if ($rows == null) {
			if ($comments) echo "<br>deleteRowsWhere - " . $tablename . " - " . $whereclause . " - no rows found";
			return 0;
		} else {
			if ($comments) echo "<br>deleteRowsWhere - " . $tablename . " - " . $whereclause . " - " . count($rows);
			foreach($rows as $index => $row) {
				$success = Table::deleteRow($tablename, $row->getID(), $comments);
				if ($success) $counter++;
			}
		}
		return $counter;
	}
	
	

	/**
	 * Multipurpose row update
	 *
	 * Usage:
	 * 		updateRow('tablename', 'columname=value', '4');			// not implemented
	 * 		updateRow('tablename', 'columname=value', 'WHERE ID=4');
	 * 		updateRow('tablename', 'columname', 'value', '4');			// not implemented
	 * 		updateRow('tablename', 'columname', 'value', 'WHERE ID=4');
	 * 		updateRow('tablename', ('colum1' => 'value1', 'column2'=>'value2'), '4'); // not implemented
	 * 		updateRow('tablename', ('colum1' => 'value1', 'column2'=>'value2'), 'WHERE ID=4');
	 *
	 */
	
	// TODO: Mietintä, pitäisikä updatenkin näkyä reference tablen logissa? Mahdollisesti ainakin silloin
	//       jos muuttuva sarake on reference. Tälläin oikeastaan pitäisi päivittää sekä source referenceen
	//       RefMoveFrom ja target referenceen RefMoveTo. Vastaavasti LogtypeID pitää lisätä nuo tavat.
	//
	public static function updateRow($tablename, $columnvalues, $id, $comments = false) {
	
		global $mysqli;
		
		if (!is_numeric($id)) {
			if ($comments) echo "<br>updaterows, non-numeric";
			return Table::updateRowsWhere($tablename, $columnvalues, $id, $comments, 1);
		}
		
		$dbtable = Table::getTable($tablename);
		$columns = $dbtable->getColumns();
		$keycolumn = 0;
		foreach($columns as $columnID => $column) {
			if ($column->type == 2) $keycolumn = $column;
		}
	
		// pitäisikä täällä ladata vanha arvo? logitusta varten ehkä kyllä
		if ($dbtable->systemspecific == 1) {
			$sql = "SELECT * FROM " . $dbtable->name . " WHERE " . $keycolumn->columnname . "='" . $id . "' AND SystemID=" . $_SESSION['systemID'];
		} else {
			$sql = "SELECT * FROM " . $dbtable->name . " WHERE " . $keycolumn->columnname . "='" . $id . "'";
		}
		if ($comments) echo "<br>sql33 - " . $sql;
		$result = $mysqli->query($sql);
		if ($result->num_rows == 0) return null;
		$row = $result->fetch_array();
	
		$delimiter = null;
		$valuestr = "";
		$logvaluestr = "";
		$changed = false;
		if ($comments) echo "<br>columncount - " . count($columns);
	
		foreach($columns as $columnID => $column) {
			if ($column->type == 2) {
				if (isset($columnvalues[$column->columnname])) {
					echo "<br>TableID cannot be updated -- use delete and add.";
					exit();
				}
				$keycolumn = $column;
			}
			if (isset($columnvalues[$column->columnname])) {
	
				if ($comments) echo "<br>Columnname - " . $column->columnname;
				//if ($columnvalues[$column->columnname] == null) echo "<br>columnvalue null";
				//if ($columnvalues[$column->columnname] == "") echo "<br>columnvalue tyhjä";
				//if ($row[$column->columnname] == null) echo "<br>row null";
				//if ($row[$column->columnname] == "") echo "<br>row tyhjä";
	
				if ($columnvalues[$column->columnname] === $row[$column->columnname]) {
					if ($comments) echo "<br>same1 - " . $columnvalues[$column->columnname] . " - " . $row[$column->columnname];
				} else {
					if ((($columnvalues[$column->columnname] === null) || ($columnvalues[$column->columnname] == ''))
							&& (($row[$column->columnname] === null) || ($row[$column->columnname] == ''))) {
									
								if (($row[$column->columnname] === null) && ($columnvalues[$column->columnname] == 0)) {
									if ($comments) echo "<br>Columnanem null diffrence";
									$valuestr = $valuestr . $delimiter . $column->columnname . "='" . $columnvalues[$column->columnname] . "'";
									if ($delimiter == null) $delimiter = ",";
									$logvaluestr = $logvaluestr . $delimiter . $column->columnname . ":" . $columnvalues[$column->columnname] . "->" . $row[$column->columnname];
									$changed = true;
								}
								if ($comments) echo "<br>same2 - " . $columnvalues[$column->columnname] . " - " . $row[$column->columnname] . " - " . $column->columnname;
							} else {
								if ($comments) echo "<br>differ - " . $columnvalues[$column->columnname] . " - " . $row[$column->columnname];
								$valuestr = $valuestr . $delimiter . $column->columnname . "='" . $columnvalues[$column->columnname] . "'";
								$logvaluestr = $logvaluestr . $delimiter . $column->columnname . ":" . $columnvalues[$column->columnname] . "->" . $row[$column->columnname];
								if ($delimiter == null) $delimiter = ",";
								$changed = true;
							}
				}
			} else {
	
			}
		}
	
		if ($changed == true) {
			
			if ($dbtable->systemspecific == 1) {
				$sql = "UPDATE " . $tablename . " SET " . $valuestr . " WHERE SystemID=" . $_SESSION['systemID'] . " AND " . $keycolumn->columnname . "='" . $id . "'";
			} else {
				$sql = "UPDATE " . $tablename . " SET " . $valuestr . " WHERE " . $keycolumn->columnname . "='" . $id . "'";
			}
			if ($comments) echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) {
				//echo "<br>error - " . $mysqli->connect_error;
				return("updateTable " . $tablename . " failed: " . $mysqli->connect_error);
			}
			self::addLogLine($dbtable->logtable, $dbtable->tableID, $id, "logx: " . $logvaluestr, Table::LOG_UPDATE);
		} else {
			if ($comments) echo "<br>Not changed";
		}
		//echo "<br>Success";
		return true;
	}
	
	
	
	
	// Olisiko tämä nopeampi toteuttaa, että sovelletaan where ehtoa suoraa pudate lauseeseen?
	//		- Ei varmaankaan sovi kuvioon, koska updateRow tekee logituksen
	public static function updateRowsWhere($tablename, $columnvalues, $whereclause, $comments = false, $source = 0) {
	
		global $mysqli;
		
		//$comments = true;
		
		// where clause tsekataan loadissa uudelleen...
		$rows = Table::load($tablename, $whereclause, $comments);
		
		if (count($rows) == 0) {
			echo "<br>Nothing to update, no rows found";
			return false;
		}
		// jos tultiin updateRow -taulusta, niin päivitettäviä rivejä saisi olla vain yksi...
		if ($source != 0) {
			if (count($rows) > 1) {
				echo "<br>Only one row should be updated, multible found";
				return false;				
			}
		}
		
		$dbtable = Table::getTable($tablename);
		$columns = $dbtable->getColumns();
		
		// Merkitään muutokset logiin
		/*
		foreach($rows as $index => $row) {
			
			foreach($columns as $columnID => $column) {
				if ($column->type == 2) {
					if (isset($columnvalues[$column->columnname])) {
						echo "<br>TableID cannot be updated -- use delete and add.";
						exit();
					}
					$keycolumn = $column;
				}
				if (isset($columnvalues[$column->columnname])) {
			
					if ($comments) echo "<br>Columnname - " . $column->columnname;
			
					
					$var = $column->variablename;
					if ($columnvalues[$column->columnname] === $row->$var) {
						if ($comments) echo "<br>same1 - " . $columnvalues[$column->columnname] . " - " . $row->$var;
					} else {
						
						$changed = false;
						if ($column->type == Column::COLUMNTYPE_INTEGERARRAY) {
							if ($columnvalues[$column->columname] === null) {
								if (count($row->$var) > 0) {
									$valuestr = $valuestr . $delimiter . $column->columnname . "='" . $columnvalues[$column->columnname] . "'";
									if ($delimiter == null) $delimiter = ",";
									$logvaluestr = $logvaluestr . $delimiter . $column->columnname . ":" . $columnvalues[$column->columnname] . "->" . implode(":",$row->$var);
									$changed = true;
								}
							}
						} else {
							if ($columnvalues[$column->columnname] === null) {
								if ($row->$var === null) {
									
								} else {
									$changed = true;
								}
							} else {
								
							}
						}
						
						if ((($columnvalues[$column->columnname] === null) || ($columnvalues[$column->columnname] == ''))
								&& (($row->$var === null) || ($row->$var == ''))) {
										
									if (($row->$var === null) && ($columnvalues[$column->columnname] == 0)) {
										if ($comments) echo "<br>Columnanem null diffrence";
										$valuestr = $valuestr . $delimiter . $column->columnname . "='" . $columnvalues[$column->columnname] . "'";
										if ($delimiter == null) $delimiter = ",";
										$logvaluestr = $logvaluestr . $delimiter . $column->columnname . ":" . $columnvalues[$column->columnname] . "->" . $row[$column->columnname];
										$changed = true;
									}
									if ($comments) echo "<br>same2 - " . $columnvalues[$column->columnname] . " - " . $row->$var . " - " . $column->columnname;
								} else {
									if ($comments) echo "<br>differ - " . $columnvalues[$column->columnname] . " - " . $row->$var;
									$valuestr = $valuestr . $delimiter . $column->columnname . "='" . $columnvalues[$column->columnname] . "'";
									$logvaluestr = $logvaluestr . $delimiter . $column->columnname . ":" . $columnvalues[$column->columnname] . "->" . $row->$var;
									if ($delimiter == null) $delimiter = ",";
									$changed = true;
								}
					}
				} else {
					if ($comments) echo "<br>no value to update for column - " . $column->columnname;
				}
			}
			
			if ($changed == true) {
				self::addLogLine($dbtable->logtable, $dbtable->tableID, $id, "logx: " . $logvaluestr, Table::LOG_UPDATE);
			} else {
				if ($comments) echo "<br>Not changed";
			}
		}
		*/
		
		$valuestr = "";
		$delimiter = null;
		
		foreach($columns as $columnID => $column) {
			//echo "<br>columnname - " . $column->columnname;
			if ($column->type == 2) {
				if (isset($columnvalues[$column->columnname])) {
					echo "<br>TableID cannot be updated -- use delete and add.";
					exit();
				}
			}
			if (isset($columnvalues[$column->columnname])) {
				//echo "<br>--columnname - " . $column->columnname;
				$valuestr = $valuestr . $delimiter . $column->columnname . "='" . $columnvalues[$column->columnname] . "'";
				if ($delimiter == null) $delimiter = ",";
			}
		}
		
		
		$sql = "UPDATE " . $tablename . " SET " . $valuestr . " " . $whereclause;
		if ($comments) echo "<br>sql2xxx - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "<br>error 1 - " . $mysqli->connect_error;
			exit();
			return("updateTable " . $tablename . " failed: " . $mysqli->connect_error);
		}
		return true;
	}
	
	
	
	private static function createLogLine($tableID, $columns, $row, $comments) {
		
		//$comments = true;
		if ($comments) echo "<br>creating log line - " . $tableID;
		
		$delimiter = null;
		$logvaluestr = "";
		//$references = array();
		
		if ($comments) echo "<br>Columnscount - " . count($columns);
		if ($comments) echo "<br>Columnscount - " . count($columns);
		
		foreach($columns as $columnID => $column) {
			if ($column->type == 1) {	// viite taulu
				//$references[$column->referencetableID] = $row[$column->columnname];
				if ($comments) echo "<br>**************** Refcolumn - ";
				if ($comments) echo "<br>Tablename2 - " . $column->variablename;
				if ($comments) echo "<br>Tablename3 - " . $column->referencetableID;
				
				// 14.12.2019 Tätä pitää miettiä lisää, aika raskas operaatio
				// reftable 0 lisätty koska mainserveriltä ladatut tableID:t ei välttämättä ole synkassa
				if ($column->referencetableID == null) {
					$logvaluestr = $logvaluestr . $delimiter . " reftable: null";
				} else 
				if ($column->referencetableID == 0) {
					$logvaluestr = $logvaluestr . $delimiter . " reftable not found " . $column->variablename . "-" . $column->referencetableID . ": null";
				} else {
					$refdbtable = Table::getTableWithID($column->referencetableID);
					//var_dump($dbtable);
					if ($comments) echo "<br>Tablename4 - " . $refdbtable->name;
					if (($row[$column->columnname] != null) && ($column->referencetableID != $tableID)) {
						$refrow = Table::loadRow($refdbtable->name, $row[$column->columnname]);
						if ($refrow != null) {
							$refcolumns = $refdbtable->getColumns();
							foreach($refcolumns as $index => $refcolumn) {
								if($refcolumn->logvalue == 1) {
									if ($comments) echo "<br>Logvalue found - " . $refcolumn->columnname;
									$colname = $refcolumn->variablename;
									if ($comments) echo "<br>Logvalue found - " . $refrow->$colname;
									$logvaluestr = $logvaluestr . $delimiter . " " . $column->columnname . ":" . $refrow->getID() . " (" . $refrow->$colname . ")";
									if ($delimiter == null) $delimiter = ",";
								}
							}
						} else {
							if ($comments) echo "<br>nulli ...";
							//$logvaluestr = $logvaluestr . $delimiter . " " . $column->columnname . ": null";
						}
					}
				}
			}
		}
		foreach($columns as $columnID => $column) {
			if ($comments) echo "<br> --- --- --- - -- column - " . $column->name;
			if ($column->type == 2) {
				//$keycolumn = $column;
				if ($comments) echo "<br>Creating logstr A - columntype=2, key";
				$logvaluestr = $column->columnname . ":" . $row[$column->columnname] . $delimiter . $logvaluestr;
				if ($delimiter == null) $delimiter = ",";
			} elseif ($column->type == 1) {
				if ($comments) echo "<br>Creating logstr b - columntype=1, ref - " . $column->columnname;
				if (($row[$column->columnname] != null) && ($column->referencetableID != $tableID)) {
					if ($comments) echo "<br>  aaa -- -- " . $column->columnname . " -- " . $row[$column->columnname];
					if ($comments) echo "<br>--- ref not found - " . $column->referencetableID . " - " . $tableID;
				} else {
					if ($comments) echo "<br>  ccc -- -- " . $column->columnname . " -- " . $row[$column->columnname];
					$logvaluestr = $logvaluestr . $delimiter . " " . $column->columnname . "=". $row[$column->columnname];
					if ($comments) echo "<br>--- ref found - " . $column->referencetableID . " - " . $tableID . " - " . $logvaluestr;
					if ($delimiter == null) $delimiter = ",";
				}
			} else {
				if (isset($row[$column->columnname])) {
					if ($comments) echo "<br>Creating logstr c - columnamefound - " . $column->columnname;
					$logvaluestr = $logvaluestr . $delimiter . " " . $column->columnname . "=". $row[$column->columnname];
					if ($delimiter == null) $delimiter = ",";
				} else {
					if ($comments) echo "<br>Creating logstr - no value found columnamefound - " . $column->columnname;
				}
			}
		}
		if ($comments) echo "<br>logstra - " . $logvaluestr;
		if ($comments) echo "<br>logstrb - " .  $tableID;
		//if ($comments) echo "<br>logstr - " .  $table->logtable;
		return $logvaluestr;
	}
	
	
	
	/*
	public static function updateTable($tablename, $id, $columnvalues) {

		global $mysqli;
		//echo "<br>Tablename - " . $tablename;
		//echo "<br>ID - " . $id;
		
		$dbtable = Table::getTable($tablename);
		$valuestr = "";
		
		//echo "<br>jee - " . $id;
		
		$columns = $dbtable->getColumns();
		$keycolumn = 0;
		$punctutation = "";
		foreach($columns as $columnID => $column) {
			if ($column->type == 2) $keycolumn = $column;
			if (isset($columnvalues[$column->columnname])) {
				$valuestr = $valuestr . $punctutation . $column->columnname . "='" . $columnvalues[$column->columnname] . "'";
				if ($punctutation == "") $punctutation = ",";		
			}
		}
		
		$sql = "UPDATE " . $tablename . " SET " . $valuestr . " WHERE " . $keycolumn->columnname . "='" . $id . "'";
		//echo "<br>Sql - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) return("updateTable " . $tablename . " failed: " . $mysqli->connect_error);
		return true;
	}
	*/
		
//******************************************************************************************************************
//***
//***
//******************************************************************************************************************

	public static function tableExistsInDatabase($tablename) {
		global $mysqli;
		$sql = "SHOW tables";
		$result = $mysqli->query($sql);
		$exists = false;
		while($row = $result->fetch_array()) {
			foreach($row as $index => $value) {
				//$subi = substr($value,0,7);
				if ($value == $tablename) {
					//echo "<br>ttt- " . $value . " - " . $tablename;
					$exists = true;
				}
			}
			//echo "<br>ttt-aaaa " . $value;
				
		}
		//echo "<br>Exists --- " . $exists;
		return $exists;
	}
	
	
//******************************************************************************************************************
//***
//***
//******************************************************************************************************************
	
	// näitä ei saisi kutsua muutakuin system-luokista
	public static function createTable($tablename, $idcolumnname, $moduleID, $logtable, $systemspecific) {

		global $mysqli;
		//$insertdate = date('Y-m-d H:i:s');
		$userID = $_SESSION['userID'];
		
		// tahan tsekkaus onko taulu jo olemassa tietokannassa...
		$sql = "SHOW tables";
		$result = $mysqli->query($sql);
		$exists = false;
		while($row = $result->fetch_array()) {
			foreach($row as $index => $value) {
				//$subi = substr($value,0,7);
				if ($value == $tablename) $exists = true;
			}
		}
		
		if ($exists) {
			// Logiin kirjaus
			echo "<br>Tietokantataulu on jo olemassa";
			return Table::getTableID($tablename);
		}
		
		$systemTableID = Table::getTableID("system_tables");
		//echo "<br>Phpversion - " . phpversion();
		
		$sql = "CREATE TABLE " . $tablename . " (" . $idcolumnname . " int NOT NULL AUTO_INCREMENT, PRIMARY KEY (" . $idcolumnname . "))";
		$result = $mysqli->query($sql);
		if (!$result) {
			
			echo "sql - " . $sql;
			echo 'insert failed 12:  ' . $mysqli->connect_error;
			exit;
		}
		
		$sql = "INSERT INTO system_tables (Name, Logtable, Tabletype, ModuleID, Systemspecific) VALUES ('" . $tablename . "', '" . $logtable . "', '0'," . $moduleID . "," . $systemspecific . ")";
		echo "<br>" . $sql;
		$result = $mysqli->query($sql);
		$tableID = $mysqli->insert_id;
		if (!$result) {
			echo 'insert failed 23: ' . $mysqli->connect_error;
			exit;
		}
		
		Table::addLogLine('system_log', $systemTableID, 0, "Table created: " . $tablename, Table::LOG_CREATE, $userID);
		Table::addLogLine($logtable, $tableID, 0, "Table created", Table::LOG_CREATE, $userID);
		
		$sql = "INSERT INTO system_columns (TableID, Variablename, Columnname, Name, Type, Obligatory) VALUES ('" . $tableID . "','" . lcfirst($idcolumnname) . "','" . $idcolumnname . "','" . $idcolumnname . "','2','1')";
		$result = $mysqli->query($sql);
		if (!$result) return 'insert failed 34 : ' . $mysqli->connect_error;
		$columnID = $mysqli->insert_id;
		
		Table::loadTableStruct($tablename, true, true);
		
		Table::addLogLine('system_log', $tableID, 0, "Column added: " . lcfirst($idcolumnname) , Table::LOG_CREATE, $userID);
		return $tableID;
	}
	
	
	// TODO: Täällä pitäisi ehkä lisätä myös taulut system_accessrights ja system_usergroups
	public static function createSystemTables($userID, $username, $systemID, $systemname, $databasename, $comments) {

		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		global $mysqli;
		
		$systemsTableCreated = false;
		if (!Table::tableExistsInDatabase('system_systems')) {
			echo "<br>No system_systems-table exits";
		
			$sql = "CREATE TABLE system_systems (" .
					" SystemID int NOT NULL AUTO_INCREMENT, " .
					" Name VARCHAR(40), " .
					" Databasename VARCHAR(100), " .
					" Frontpage TEXT, " .
					" PRIMARY KEY (SystemID))";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
		
			$sql = "INSERT INTO system_systems(SystemID, Name, Databasename, Frontpage) VALUES ('" . $systemID . "','" . $systemname . "', '" . $databasename . "', 'system/frontpage/index')";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$systemID = $mysqli->insert_id;
			$systemsTableCreated = true;
		
		} else {
			echo "<br>system_systems-table exits";
		}
		
		
		$modulesTableCreated = false;
		if (!Table::tableExistsInDatabase('system_modules')) {
			echo "<br>No system_modules-table exits";
		
			$sql = "CREATE TABLE system_modules (" .
					" ModuleID int NOT NULL AUTO_INCREMENT, " .
					" Name VARCHAR(40), " .
					" Modulename VARCHAR(50), " .
					" Defaultlog VARCHAR(50), " .
					" Active INT DEFAULT 1, " .
				//	" SystemID INT, " .
					" PRIMARY KEY (ModuleID))";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
		
			$sql = "INSERT INTO system_modules(Name, Modulename, Defaultlog, SystemID) VALUES ('System','system', 'system_log', " . $systemID . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$systemModuleID = $mysqli->insert_id;
			$modulesTableCreated = true;
				
		} else {
			echo "<br>System_modules-table exits";
			
			$sql = "SELECT ModuleID FROM system_modules WHERE Modulename='System'";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$row = $result->fetch_array();
			$systemModuleID = $row[0];
			
			echo "<br>system_modules-table id = " . $systemModuleID;
		}
		
		
		$tablesTableCreated = false;
		if (!Table::tableExistsInDatabase('system_tables')) {
			echo "<br>No system_tables-table exits";
				
			$sql = "CREATE TABLE system_tables (" .
						" TableID int NOT NULL AUTO_INCREMENT, " .
						" Name VARCHAR(40), " .
						" Logtable VARCHAR(50), " . 
						" Tabletype INT, " .
						" ModuleID INT, " . 
						" Description TEXT, " . 
						" Systemspecific INT, " . 
						" PRIMARY KEY (TableID))";
			$result = $mysqli->query($sql);
			$tablesTableCreated = true;
				
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
		} else {
			echo "<br>system_tables-table exits";
		}

		

		$columnsTableCreated = false;
		if (!Table::tableExistsInDatabase('system_columns')) {
			echo "<br>No system_columns-table exits";
		
			$sql = "CREATE TABLE system_columns (" .
					" ColumnID int NOT NULL AUTO_INCREMENT, " .
					" TableID INT NOT NULL, " .
					" Variablename VARCHAR(40) NOT NULL, " .
					" Columnname VARCHAR(40) NOT NULL, " .
					" Name Text NOT NULL, " .
					" Type INT NOT NULL, " .
					" Obligatory INT DEFAULT 0, " .
					" ReferencetableID INT DEFAULT 0, " .
					" Editable INT DEFAULT 1, " .
					" Min INT DEFAULT 0, " .
					" Max INT DEFAULT NULL, " .
					" Defaultvalue Text DEFAULT '', " .
					" Tablevisibility INT DEFAULT 1, " .
					" Sectionvisibility INT DEFAULT 1, " .
					" RemoveID INT DEFAULT 0, " .
					" Logvalue INT DEFAULT 0, " .
					" Sortorder INT DEFAULT 0, " .
					" PRIMARY KEY (ColumnID))";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnsTableCreated = true;
				
		} else {
			echo "<br>system_columns-table exits";
		}
		
		
		$usersTableCreated = false;
		if (!Table::tableExistsInDatabase('system_users')) {
			echo "<br>No system_users-table exits";
		
			// TODO: SystemID pitää lisätä
			$sql = "CREATE TABLE system_users (" .
					" UserID int NOT NULL, " .
					" UsergroupID int NOT NULL, " .
					" Username VARCHAR(100) NOT NULL, " .
					" Password VARCHAR(100), " .
					" Firstname VARCHAR(40), " .
					" Lastname VARCHAR(80), " .
					" Phonenumber VARCHAR(20), " .
					" Email VARCHAR(100), " .
					" SystemID INT, " .
					" PRIMARY KEY (UserID, UsergroupID))";
			$result = $mysqli->query($sql);
			if (!$result) {
				echo "<br>sql - " . $sql;
				echo "<br>sql - " . $mysqli->error;
				exit();
			}
			$usersTableCreated = true;
		} else {
			echo "<br>system_users-table exists";
		}
		
		
		$logTableCreated = false;
		if (!Table::tableExistsInDatabase('system_log')) {
			echo "<br>No system_log-table exits";
			
			$sql = "CREATE TABLE system_log (" .
					" RowID int NOT NULL AUTO_INCREMENT, " .
					" UserID INT DEFAULT 0, " .
					" TableID INT DEFAULT 0, " .
					" KeyID INT DEFAULT 0, " .
					" LogtypeID INT DEFAULT 0, " .
					" Logtime DATETIME, " .
					" Description TEXT DEFAULT '', " .
					" PRIMARY KEY (RowID))";
			echo "<br>Sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) {
				echo "<br>sql - " . $sql;
				echo "<br>sql - " . $mysqli->error;
				exit();
			}
			$logTableCreated = true;
		} else {
			echo "<br>System_log-table exits";
		}
		
		
		$array = Table::loadRowRaw("system_tables", "WHERE Name='system_systems'");
		if (count($array) > 0) {
			echo "<br>system_systems found - " . $array[0]['TableID'];
			$systemSystemsTableID = $array[0]['TableID'];
		} else {
			echo "<br>No system_systems found";
		
			$sql = "INSERT INTO system_tables(Name, Logtable, Tabletype, ModuleID, Systemspecific) VALUES ('system_systems','system_log',1," . $systemModuleID . ",1)";
			if ($comments) echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$systemSystemsTableID = $mysqli->insert_id;
		}
		
		
		$array = Table::loadRowRaw("system_tables", "WHERE Name='system_modules'");
		if (count($array) > 0) {
			echo "<br>system_modules found - " . $array[0]['TableID'];
			$systemModulesTableID = $array[0]['TableID'];
		} else {
			echo "<br>No system_modules found";
		
			$sql = "INSERT INTO system_tables(Name, Logtable, Tabletype, ModuleID, Systemspecific) VALUES ('system_modules','system_log',1," . $systemModuleID . ",0)";
			if ($comments) echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$systemModulesTableID = $mysqli->insert_id;
		}
		
		
		
		$array = Table::loadRowRaw("system_tables", "WHERE Name='system_tables'");
		if (count($array) > 0) {
			echo "<br>system_tables found - " . $array[0]['TableID'];
			$systemTablesTableID = $array[0]['TableID'];
		} else {
			echo "<br>No system_tables found";
			$sql = "INSERT INTO system_tables(Name, Logtable, Tabletype, ModuleID, Systemspecific) VALUES ('system_tables','system_log',1," . $systemModuleID . ",0)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$systemTablesTableID = $mysqli->insert_id;
		}
		
		
		$array = Table::loadRowRaw("system_tables", "WHERE Name='system_columns'");
		if (count($array) > 0) {
			echo "<br>system_columns found b - " . $array[0]['TableID'];
			$systemColumnsTableID = $array[0]['TableID'];
		} else {
			echo "<br>No system_columns found";
			
			$sql = "INSERT INTO system_tables(Name, Logtable, Tabletype, ModuleID, Systemspecific) VALUES ('system_columns','system_log',1," . $systemModuleID . ",1)";
			if ($comments) echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$systemColumnsTableID = $mysqli->insert_id;
		}
		
		

		

		$array = Table::loadRowRaw("system_tables", "WHERE Name='system_users'");
		if (count($array) > 0) {
			echo "<br>system_users found - " . $array[0]['TableID'];
			$systemUserTableID = $array[0]['TableID'];
		} else {
			echo "<br>No system_users found";
		
			$sql = "INSERT INTO system_tables(Name, Logtable, Tabletype, ModuleID, Systemspecific) VALUES ('system_users','system_log',1," . $systemModuleID . ",1)";
			if ($comments) echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$systemUserTableID = $mysqli->insert_id;
		}
		
		
		
		$array = Table::loadRowRaw("system_tables", "WHERE Name='system_log'");
		if (count($array) > 0) {
			echo "<br>system_log found - " . $array[0]['TableID'];
			$systemLogTableID = $array[0]['TableID'];
		} else {
			echo "<br>No system_log found";
		
			$sql = "INSERT INTO system_tables(Name, Logtable, Tabletype, ModuleID, Systemspecific) VALUES ('system_log','system_log',1," . $systemModuleID . ",0)";
			if ($comments) echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$systemLogTableID = $mysqli->insert_id;
		}
		

		

		if ($systemsTableCreated == true) {
				
			Table::addLogLine('system_log', $systemModulesTableID, 0, "Table created: system_systems", Table::LOG_CREATE, $userID);
			Table::addLogLine('system_log', $systemModulesTableID, $systemModuleID, "Add column - system_systems", Table::LOG_CREATE, $userID);
				
			// system_modules columnien lisäys
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Obligatory) VALUES ('" . $systemSystemsTableID . "','systemID','SystemID','SystemID'," . Column::COLUMNTYPE_KEY . ",1)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemModulesTableID, $columnID, "Add column - ModuleID", Table::LOG_CREATE, $userID);
				
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Max, Obligatory) VALUES ('" . $systemSystemsTableID . "','name','Name','Name'," . Column::COLUMNTYPE_STRING . ", 40, 1)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemModulesTableID, $columnID, "Add column - Name", Table::LOG_CREATE, $userID);
				
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Max, Obligatory) VALUES ('" . $systemSystemsTableID . "','databasename','Databasename','Databasename'," . Column::COLUMNTYPE_STRING . ", 100, 1)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemModulesTableID, $columnID, "Add column - Databasename", Table::LOG_CREATE, $userID);
		
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemSystemsTableID . "','frontpage','Frontpage','Frontpage'," . Column::COLUMNTYPE_TEXT . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemModulesTableID, $columnID, "Add column - Frontpage", Table::LOG_CREATE, $userID);
			
		}
		
		
		
		
		if ($modulesTableCreated == true) {
			
			Table::addLogLine('system_log', $systemModulesTableID, 0, "Table created: system_modules", Table::LOG_CREATE, $userID);
			Table::addLogLine('system_log', $systemModulesTableID, $systemModuleID, "Add column - system", Table::LOG_CREATE, $userID);
			
			// system_modules columnien lisäys
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Obligatory) VALUES ('" . $systemModulesTableID . "','moduleID','ModuleID','ModuleID'," . Column::COLUMNTYPE_KEY . ",1)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemModulesTableID, $columnID, "Add column - ModuleID", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Max, Obligatory) VALUES ('" . $systemModulesTableID . "','name','Name','Name'," . Column::COLUMNTYPE_STRING . ", 40, 1)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemModulesTableID, $columnID, "Add column - Name", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Max, Obligatory) VALUES ('" . $systemModulesTableID . "','modulename','Modulename','Modulename'," . Column::COLUMNTYPE_STRING . ", 40, 1)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemModulesTableID, $columnID, "Add column - Modulename", Table::LOG_CREATE, $userID);
				
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Max) VALUES ('" . $systemModulesTableID . "','defaultlog','Defaultlog','Defaultlog'," . Column::COLUMNTYPE_STRING . ", 40)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemModulesTableID, $columnID, "Add column - Defaultlog", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Obligatory, Defaultvalue) VALUES ('" . $systemModulesTableID . "','active','Active','Active'," . Column::COLUMNTYPE_INTEGER . ",1,'0')";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemModulesTableID, $columnID, "Add column - Active", Table::LOG_CREATE, $userID);
			
			//$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Obligatory) VALUES ('" . $systemModulesTableID . "','systemID','SystemID','SystemID'," . Column::COLUMNTYPE_INTEGER . ",1)";
			//$result = $mysqli->query($sql);
			//if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			//$columnID = $mysqli->insert_id;
			//Table::addLogLine('system_log', $systemModulesTableID, $columnID, "Add column - Active", Table::LOG_CREATE, $userID);
		}

		
		if ($tablesTableCreated == true) {
			
			Table::addLogLine('system_log', $systemTablesTableID, 0, "Table created: system_tables", Table::LOG_CREATE, $userID);
				
			// creating system_table columns
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Obligatory) VALUES ('" . $systemTablesTableID . "','tableID','TableID','TableID'," . Column::COLUMNTYPE_KEY . ",1)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemTablesTableID, $columnID, "Add column - TableID", Table::LOG_CREATE, $userID);
				
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Obligatory, Max) VALUES ('" . $systemTablesTableID . "','name','Name','Name'," . Column::COLUMNTYPE_STRING . ",1,60)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemTablesTableID, $columnID, "Add column - Name", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Max) VALUES ('" . $systemTablesTableID . "','logtable','Logtable','Logtable'," . Column::COLUMNTYPE_STRING . ", 60)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemTablesTableID, $columnID, "Add column - Logtable", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemTablesTableID . "','tabletype','Tabletype','Tabletype'," . Column::COLUMNTYPE_INTEGER . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemTablesTableID, $columnID, "Add column - Tabletype", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, ReferencetableID) VALUES ('" . $systemTablesTableID . "','moduleID','ModuleID','ModuleID'," . Column::COLUMNTYPE_TABLEREFERENCE . ", " . $systemModulesTableID . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemTablesTableID, $columnID, "Add column - ModuleID", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemTablesTableID . "','description','Description','Description'," . Column::COLUMNTYPE_TEXT . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemTablesTableID, $columnID, "Add column - ModuleID", Table::LOG_CREATE, $userID);
				
		}
		
		
		if ($columnsTableCreated == true) {
			
			Table::addLogLine('system_log', $systemColumnsTableID, 0, "Table created: system_columns", Table::LOG_CREATE, $userID);
				
			// creating columns_table columns
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Obligatory) VALUES ('" . $systemColumnsTableID . "','columnID','ColumnID','ColumnID'," . Column::COLUMNTYPE_KEY . ",1)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - ColumnID", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, ReferencetableID, Obligatory) VALUES ('" . $systemColumnsTableID . "','tableID','TableID','TableID'," . Column::COLUMNTYPE_TABLEREFERENCE . ", " . $systemTablesTableID . ",1)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - TableID", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Max, Obligatory) VALUES ('" . $systemColumnsTableID . "','variablename','Variablename','Variablename'," . Column::COLUMNTYPE_STRING . ", 40,1)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - Variablename", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Max, Obligatory) VALUES ('" . $systemColumnsTableID . "','columnname','Columnname','Columnname'," . Column::COLUMNTYPE_STRING . ", 40,1)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - Columnname", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Obligatory) VALUES ('" . $systemColumnsTableID . "','name','Name','Name'," . Column::COLUMNTYPE_TEXT . ",1)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - Name", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Obligatory) VALUES ('" . $systemColumnsTableID . "','type','Type','Type'," . Column::COLUMNTYPE_INTEGER . ",1)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - Type", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Defaultvalue) VALUES ('" . $systemColumnsTableID . "','obligatory','Obligatory','Obligatory'," . Column::COLUMNTYPE_INTEGER . ",'0')";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - Obligatory", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Defaultvalue) VALUES ('" . $systemColumnsTableID . "','referencetableID','ReferencetableID','ReferencetableID'," . Column::COLUMNTYPE_INTEGER . ",'0')";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - ReferencetableID", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemColumnsTableID . "','editable','Editable','Editable'," . Column::COLUMNTYPE_INTEGER . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - Editable", Table::LOG_CREATE, $userID);
				
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemColumnsTableID . "','max','Max','Max'," . Column::COLUMNTYPE_INTEGER . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - Max", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Obligatory) VALUES ('" . $systemColumnsTableID . "','type','Type','Type'," . Column::COLUMNTYPE_INTEGER . ",1)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - Type", Table::LOG_CREATE, $userID);
				
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Defaultvalue) VALUES ('" . $systemColumnsTableID . "','min','Min','Min'," . Column::COLUMNTYPE_INTEGER . ",'0')";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - Min", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemColumnsTableID . "','defaultvalue','Defaultvalue','Defaultvalue'," . Column::COLUMNTYPE_TEXT . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - Defaultvalue", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemColumnsTableID . "','tablevisibility','Tablevisibility','Tablevisibility'," . Column::COLUMNTYPE_INTEGER . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - Tablevisibility", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemColumnsTableID . "','sectionvisibility','Sectionvisibility','Sectionvisibility'," . Column::COLUMNTYPE_INTEGER . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - Sectionvisibility", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Defaultvalue) VALUES ('" . $systemColumnsTableID . "','removeID','RemoveID','RemoveID'," . Column::COLUMNTYPE_REMOVEID . ",0)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - RemoveID", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Defaultvalue,Max) VALUES ('" . $systemColumnsTableID . "','logvalue','Logvalue','Logvalue'," . Column::COLUMNTYPE_BOOLEAN . ",'0',50)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - Logvalue", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Defaultvalue,Max) VALUES ('" . $systemColumnsTableID . "','sortorder','Sortorder','Sortorder'," . Column::COLUMNTYPE_ORDER . ",'0',NULL)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemColumnsTableID, $columnID, "Add column - Sortorder", Table::LOG_CREATE, $userID);
				
		}
			
				
		if ($usersTableCreated == true) {
			
			Table::addLogLine('system_log', $systemUserTableID, 0, "Table created: system_users", Table::LOG_CREATE, $userID);
			
			// system_users columnien lisäys
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Obligatory) VALUES ('" . $systemUserTableID . "','userID','UserID','UserID'," . Column::COLUMNTYPE_KEY . ",1)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemUserTableID, $columnID, "Add column - UserID", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Obligatory, Max) VALUES ('" . $systemUserTableID . "','username','Username','Username'," . Column::COLUMNTYPE_STRING . ", 1, 100)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemUserTableID, $columnID, "Add column - Username", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Obligatory, Min, Max) VALUES ('" . $systemUserTableID . "','password','Password','Password'," . Column::COLUMNTYPE_STRING . ", 1, 4, 100)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemUserTableID, $columnID, "Add column - Password", Table::LOG_CREATE, $userID);
				
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Max) VALUES ('" . $systemUserTableID . "','firstname','Firstname','Firstname'," . Column::COLUMNTYPE_STRING . ", 40)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemUserTableID, $columnID, "Add column - Firstname", Table::LOG_CREATE, $userID);
				
				
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Max) VALUES ('" . $systemUserTableID . "','lastname','Lastname','Lastname'," . Column::COLUMNTYPE_STRING . ", 80)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemUserTableID, $columnID, "Add column - Lastname", Table::LOG_CREATE, $userID);
				
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Max) VALUES ('" . $systemUserTableID . "','phonenumber','Phonenumber','Phonenumber'," . Column::COLUMNTYPE_STRING . ", 20)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemUserTableID, $columnID, "Add column - Phonenumber", Table::LOG_CREATE, $userID);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Max) VALUES ('" . $systemUserTableID . "','email','Email','Email'," . Column::COLUMNTYPE_STRING . ", 100)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemUserTableID, $columnID, "Add column - Email", Table::LOG_CREATE, $userID);
				
		}
		
		
		if ($logTableCreated == true) {
			
			Table::addLogLine('system_log', $systemLogTableID, 0, "Table created: system_log", Table::LOG_CREATE, 1);
			
			// creating system_table columns
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Obligatory) VALUES ('" . $systemLogTableID . "','rowID','RowID','RowID'," . Column::COLUMNTYPE_KEY . ",1)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemLogTableID, $columnID, "Table system_log, add column RowID", Table::LOG_CREATE);
			
			// TODO: reference to system_users
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemLogTableID . "','userID','UserID','UserID'," . Column::COLUMNTYPE_INTEGER . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemLogTableID, $columnID, "Table system_log, add column UserID", Table::LOG_CREATE);
				
			// TODO: reference to system_tables
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemLogTableID . "','tableID','TableID','TableID'," . Column::COLUMNTYPE_INTEGER . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemLogTableID, $columnID, "Table system_log, add column TableID", Table::LOG_CREATE);
			
			
			// TODO: reference to system_tables
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemLogTableID . "','keyID','KeyID','KeyID'," . Column::COLUMNTYPE_INTEGER . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemLogTableID, $columnID, "Table system_log, add column KeyID", Table::LOG_CREATE);
				
			// TODO: logtypes pitäisi kehittää oma taulukkonsa, vakoita tai oma taulukko
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemLogTableID . "','logtypeID','LogtypeID','LogtypeID'," . Column::COLUMNTYPE_INTEGER . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemLogTableID, $columnID, "Table system_log, add column LogtypeID", Table::LOG_CREATE);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemLogTableID . "','logtime','Logtime','Logtime'," . Column::COLUMNTYPE_DATETIME . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemLogTableID, $columnID, "Table system_log, add column Logtime", Table::LOG_CREATE);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemLogTableID . "','description','Description','Description'," . Column::COLUMNTYPE_TEXT . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemLogTableID, $columnID, "Table system_log, add column Description", Table::LOG_CREATE);
			
		}
		
			
				
			
				
			
		// Luodaan transitiontable
		$transitionsTableCreated = false;
		if (!Table::tableExistsInDatabase('system_transitions')) {
			echo "<br>No system_transitionstable-table exits";
				
			$sql = "CREATE TABLE system_transitions (" .
					" RowID int NOT NULL AUTO_INCREMENT, " .
					" LocalmoduleID INT DEFAULT 0, " .
					" RemotemoduleID INT DEFAULT 0, " .
					" LocaltableID INT DEFAULT 0, " .
					" RemotetableID INT DEFAULT 0, " .
					" LocalrowID INT DEFAULT 0, " .
					" RemoterowID INT DEFAULT 0, " .
					" PRIMARY KEY (RowID))";
			echo "<br>Sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) {
				echo "<br>sql - " . $sql;
				echo "<br>sql - " . $mysqli->error;
				exit();
			}
			$transitionsTableCreated = true;
		} else {
			echo "<br>System_transition-table exits";
		}
		

		$array = Table::loadRowRaw("system_tables", "WHERE Name='system_transitions'");
		if (count($array) > 0) {
			echo "<br>system_log found - " . $array[0]['TableID'];
			$systemTransitionsTableID = $array[0]['TableID'];
		} else {
			echo "<br>No system_transitions found";
		
			$sql = "INSERT INTO system_tables(Name, Logtable, Tabletype, ModuleID, Systemspecific) VALUES ('system_transitions','system_log',1," . $systemModuleID . ",0)";
			if ($comments) echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$systemTransitionsTableID = $mysqli->insert_id;
		}
		
		
		if ($transitionsTableCreated == true) {
			
			Table::addLogLine('system_log', $systemTransitionsTableID, 0, "Table created: system_transitions", Table::LOG_CREATE, $userID);
				
			// creating system_transitions columns
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Obligatory) VALUES ('" . $systemTransitionsTableID . "','rowID','RowID','RowID'," . Column::COLUMNTYPE_KEY . ",1)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemLogTableID, $columnID, "Table system_transitions, add column RowID", Table::LOG_CREATE);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, ReferencetableID) VALUES ('" . $systemTransitionsTableID . "','localmoduleID','LocalmoduleID','LocalmoduleID'," . Column::COLUMNTYPE_TABLEREFERENCE . "," . $systemModulesTableID .")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemLogTableID, $columnID, "Table system_transitions, add column LocalmoduleID", Table::LOG_CREATE);
			
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemTransitionsTableID . "','remotemoduleID','RemotemoduleID','RemotemoduleID'," . Column::COLUMNTYPE_INTEGER . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemLogTableID, $columnID, "Table system_transitions, add column RemotemoduleID", Table::LOG_CREATE);
				
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, ReferencetableID) VALUES ('" . $systemTransitionsTableID . "','localtableID','LocaltableID','LocaltableID'," . Column::COLUMNTYPE_TABLEREFERENCE . "," . $systemTablesTableID . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemLogTableID, $columnID, "Table system_transitions, add column LocaltableID", Table::LOG_CREATE);
				
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemTransitionsTableID . "','remotetableID','RemotetableID','RemotetableID'," . Column::COLUMNTYPE_INTEGER . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemLogTableID, $columnID, "Table system_transitions, add column RemotetableID", Table::LOG_CREATE);
				
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemTransitionsTableID . "','localrowID','LocalrowID','LocalrowID'," . Column::COLUMNTYPE_INTEGER . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemLogTableID, $columnID, "Table system_transitions, add column LocalrowID", Table::LOG_CREATE);
				
			$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $systemTransitionsTableID . "','remoterowID','RemoterowID','RemoterowID'," . Column::COLUMNTYPE_INTEGER . ")";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			$columnID = $mysqli->insert_id;
			Table::addLogLine('system_log', $systemLogTableID, $columnID, "Table system_transitions, add column RemoterowID", Table::LOG_CREATE);
			
			$sql = "INSERT INTO system_transitions(LocalmoduleID, RemotemoduleID, LocaltableID, RemotetableID, LocalrowID, RemoterowID) VALUES (1,1,0,0,0,0)";
			$result = $mysqli->query($sql);
			if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
			
		} else {
			echo "<br>System_transition-table not created";
		}
	}
	
	
	/**
	 * Tätä ennen pitää olla luotuna system_tables. system_modules ja system_columns taulut.
	 * Ehkä tarvittaisiin myäs usertablen ID-numero ja system_tables ID-numero.
	 * 
	 * Oletusarvoja tablesTableID = 0 ja columnsTableID = 0 käytetään silloin kun luondaan ensimmäistä
	 * kertaa logs-taulua, silloin kun columns_tablea ei ole vielä täysin luotu 
	 * 
	 * @param string $logtablename
	 */
	private static function createLogTable($logtablename, $moduleID, $tablesTableID = 0, $columnsTableID = 0) {
		
		global $mysqli;
		
		if (Table::tableExistsInDatabase($logtablename)) {
			return false;
		}

		if ($tablesTableID == 0) $tablesTableID = Table::getTableID("system_tables");
		if ($columnsTableID == 0) $columnsTableID = Table::getTableID("system_columns");
		
		$sql = "CREATE TABLE " . $logtablename  ." (" .
				" RowID int NOT NULL AUTO_INCREMENT, " .
				" UserID INT DEFAULT 0, " .
				" TableID INT DEFAULT 0, " .
				" KeyID INT DEFAULT 0, " .
				" LogtypeID INT DEFAULT 0, " .
				" Logtime DATETIME, " .
				" Description TEXT DEFAULT '', " .
				" PRIMARY KEY (RowID))";
		echo "<br>Sql - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "<br>Error - 2 " . $mysqli->connect_error;
			exit;
			return 'CREATE failed: ' . $mysqli->connect_error;
		}
			
		
		// TODO: korvaa 2 tabletype_system
		$sql = "INSERT INTO system_tables(Name, Logtable, Tabletype, ModuleID) VALUES ('" . $logtablename . "','',2," . $moduleID . ")";
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "<br>Sql - " . $sql;
			echo "<br>Error - 3 " . $mysqli->error;
			exit;
				
			return 'CREATE failed: ' . $mysqli->connect_error;
		}
		$logTableID = $mysqli->insert_id;
		Table::addLogLine('system_log', $tablesTableID, $logTableID, "Table created: system_log", Table::LOG_CREATE, 1);
		
		
		// creating system_table columns
		$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $logTableID . "','rowID','RowID','RowID'," . Column::COLUMNTYPE_KEY . ")";
		$result = $mysqli->query($sql);
		if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
		$columnID = $mysqli->insert_id;
		Table::addLogLine('system_log', $columnsTableID, $columnID, "Table system_log, add column RowID", Table::LOG_CREATE);
		
		// TODO: reference to system_tables
		$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $logTableID . "','tableID','TableID','TableID'," . Column::COLUMNTYPE_INTEGER . ")";
		$result = $mysqli->query($sql);
		if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
		$columnID = $mysqli->insert_id;
		Table::addLogLine('system_log', $columnsTableID, $columnID, "Table system_log, add column TableID", Table::LOG_CREATE);
		
		// TODO: reference to system_users
		$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $logTableID . "','userID','UserID','UserID'," . Column::COLUMNTYPE_INTEGER . ")";
		$result = $mysqli->query($sql);
		if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
		$columnID = $mysqli->insert_id;
		Table::addLogLine('system_log', $columnsTableID, $columnID, "Table system_log, add column UserID", Table::LOG_CREATE);
		
		// TODO: logtypes pitäisi kehittää oma taulukkonsa, vakoita tai oma taulukko
		$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $logTableID . "','logtypeID','LogtypeID','LogtypeID'," . Column::COLUMNTYPE_INTEGER . ")";
		$result = $mysqli->query($sql);
		if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
		$columnID = $mysqli->insert_id;
		Table::addLogLine('system_log', $columnsTableID, $columnID, "Table system_log, add column LogtypeID", Table::LOG_CREATE);
		
		$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $logTableID . "','logtime','Logtime','Logtime'," . Column::COLUMNTYPE_DATETIME . ")";
		$result = $mysqli->query($sql);
		if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
		$columnID = $mysqli->insert_id;
		Table::addLogLine('system_log', $columnsTableID, $columnID, "Table system_log, add column Logtime", Table::LOG_CREATE);
		
		$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type) VALUES ('" . $logTableID . "','description','Description','Description'," . Column::COLUMNTYPE_TEXT . ")";
		$result = $mysqli->query($sql);
		if (!$result) return 'CREATE failed: ' . $mysqli->connect_error;
		$columnID = $mysqli->insert_id;
		Table::addLogLine('system_log', $columnsTableID, $columnID, "Table system_log, add column Description", Table::LOG_CREATE);
		
		return $logTableID;
	}
	
	
	// näitä ei saisi kutsua muutakuin system-luokista
	public static function insertColumn($tableID, $variablename, $columnname, $name, $type, $obligatory, $referencetableID, $min, $max, $defaultvalue, $comments = false) {
		
		global $mysqli;
		$table = Table::getTableWithID($tableID);
		
		$sql = "DESCRIBE " . $table->name;
		if ($comments) echo "<br> - " . $sql;
		$result = $mysqli->query($sql);
		$rowNumber = 1;
		$columnexists = false;
		
		while($row = $result->fetch_array()) {
			foreach($row as $index => $value) {
				//$subi = substr($value,0,7);
				if (($index == 0) && ($value == $columnname)) {
					echo "<br>[{\"success\":\"Taulu on nimi on jo olemassa tietokannassa 22 (" .  $table->name . "  - " . $columnname . " - " . $index . ")\"}]";
					$columnexists = true;
				}
			}
			$rowNumber++;
		}
		
		//$columns = $table->getColumns();
		$sql = null;
		
		
		if (($max == null) || ($max == 0)) $max = "NULL";
		else $max = "" . $max . "";
		if ($comments) echo "<br>Type - " . $type;
		if ($columnexists == false) {
			switch($type) {
			
				case 1 :
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " INT";
					break;
			
					//echo "[{\"success\":\"Tablereference not implemented\"}]";
					//exit;
					//break;
				case Column::COLUMNTYPE_INTEGER:
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " INT";
					break;
				case Column::COLUMNTYPE_INSERTID:
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " INT";
					break;
				case Column::COLUMNTYPE_MODIFIEDID:
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " INT";
					break;
				case Column::COLUMNTYPE_REMOVEID:
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " INT";
					break;
				case Column::COLUMNTYPE_BOOLEAN:
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " INT";
					break;
				case Column::COLUMNTYPE_FLOAT:
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " FLOAT";
					break;
				case Column::COLUMNTYPE_STRING:
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " VARCHAR("  . $max . ")";
					break;
				case Column::COLUMNTYPE_DATE:
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " DATE";
					break;
				case Column::COLUMNTYPE_DATETIME:
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " DATETIME";
					break;
				case Column::COLUMNTYPE_MODIFIEDDATE:
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " DATETIME";
					break;
				case Column::COLUMNTYPE_REMOVEDATE:
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " DATETIME";
					break;
				case Column::COLUMNTYPE_TEXT:
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " TEXT";
					break;
				case Column::COLUMNTYPE_INTEGERARRAY:
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " TEXT";
					break;
				case Column::COLUMNTYPE_KEYVALUELIST:
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " TEXT";
					break;
				case Column::COLUMNTYPE_ATTACHMENT:
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " TEXT";
					break;
				case Column::COLUMNTYPE_MULTILANG:
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " TEXT";
					break;
				case Column::COLUMNTYPE_ORDER:
					$sql = "ALTER TABLE " . $table->name . " ADD " . $columnname . " INT";
					break;
				default :
					echo "[{\"success\":\"Tuntematon tyyppi - " . $type . "\"}]";
					exit;
					break;
			}
			
			if ($comments) echo "<br>Sql - " . $sql;
			
			//$insertdate = date('Y-m-d H:i:s');
			//$userID = $_SESSION['userID'];
			$result = $mysqli->query($sql);
			if (!$result) {
				print_r($mysqli->error_list);
				echo "<br>error - ". $mysqli->connect_error;
				return 'Column Add failed: ' . $mysqli->connect_error;
			}
			
		}
		
		$sql = "SELECT * FROM system_columns WHERE TableID='" . $tableID . "' AND ColumnName='" . $columnname . "'";
		echo "<br>Sql - " . $sql;
		$result = $mysqli->query($sql);
		$rowexists = true;
		if ($result == null) {
			echo "<br>Result nulli";
			$rowexists = false;
		}
		if ($result->num_rows == 0) {
			echo "<br>Result nolla";
			$rowexists = false;
		}

		if ($rowexists == false) {
			echo "<br>Column not found in system_columns, adding";
			$sql = "INSERT INTO system_columns (TableID, Variablename, Columnname, Name, Type, Obligatory, ReferencetableID, Min, Max, Defaultvalue) VALUES ('" . $tableID . "','" . $variablename . "','" . $columnname . "','" . $name . "','" . $type . "','" . $obligatory . "','" . $referencetableID . "','" . $min . "'," . $max . ",'" . $defaultvalue . "')";
			if ($comments) echo "<br>" . $sql;
			$result = $mysqli->query($sql);
			if (!$result) {
				echo 'sql : ' . $sql;
				echo 'insert failed 56: ' . $mysqli->connect_error;
				return 'insert failed 65: ' . $mysqli->connect_error;
			}
			$columnID = $mysqli->insert_id;
			
			$sql = "UPDATE system_columns SET Sortorder='" . $columnID . "' WHERE ColumnID='". $columnID . "'";
			$result = $mysqli->query($sql);
			if (!$result) {
				return 'insert failed 6444: ' . $mysqli->connect_error;
			}
		}
		
		Table::loadTableStruct($table->name, true, true);
		
		return $columnID;
	}
	
	
//******************************************************************************************************************
//***
//***
//******************************************************************************************************************
	
	public function renameTable($newtablename) {
	
		global $mysqli;
		//$insertdate = date('Y-m-d H:i:s');
		//$userID = $_SESSION['userID'];

		if ($this->tableExistsInDatabase($newtablename)) {
			return "Taulu on jo olemassa";
		}
		
		$sql = "ALTER TABLE " . $this->name . " RENAME " . $newtablename . "";
		
		$result = $mysqli->query($sql);
		
		if (!$result) return 'RENAME TABLE  failed: ' . $mysqli->connect_error;

		$sql = "UPDATE system_tables SET Name='" . $newtablename . "' WHERE TableID='"  . $this->tableID . "'";
		
		$result = $mysqli->query($sql);
		if (!$result) return 'system_tables updatefailed: ' . $mysqli->connect_error;

		$this->name = $newtablename;
		return true;
	}
	
	
	
	public function getKeyColumn() {
		//echo "<br>Tablename - " . $this->name;
		$columns = self::$columns[$this->name];
		foreach($columns as $columnID => $column) {
			if ($column->type == 2) return $column;
		}
		return null;		
	}
}




	




?>