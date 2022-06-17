<?php



class UITableLevel2 {
	
	private static $treecount = 0;
	protected $treeID;
	
	private $title;
	private $width;
	private $data = null;
	private $iconup;
	private $icondown;
	private $iconSize;
	private $columns = array();
	private $lineaction = null;
	private $treecolumnname;
	
	public function __construct($title, $width = "1000px") {
		$this->treeID = self::$treecount;
		self::$treecount++;
		$this->title = $title;
		$this->width = $width;
		//$this->treecolumnname = $treecolumnname;
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function setData($data) {
		$this->data = $data;
	}
	
	public function addColumn($column, $level) {
		if (!isset($this->columns[$level])) $this->columns[$level] = array();
		$levelarray = $this->columns[$level];
		$levelarray[] = $column;
		$this->columns[$level] = $levelarray;
	}
		
	public function setIcons($upIcon,$downIcon,$size) {
		$this->iconup = $upIcon;
		$this->icondown = $downIcon;
		$this->iconsize = $size;
	}
	
	public function setLineAction($lineaction) {
		$this->lineaction = $lineaction;
	}
	
	private function createColumnHeader($column) {
		echo "<th>".$column->name."</th>";
	}
	
	private function createUIFixedColumnTD($column,$row, $width = '') {
		$variable = $column->datavariable;
		
		if ($width != '') $width = "width:" . $width;
		//echo "<br>variable - " . $column->datavariable;
		//echo "<br>variable - " . get_class($column);
		
		if ($column instanceof UISelectColumn) {
			
			$selectID = $row->$variable;
			//echo "<br>SelectID - " . $selectID;

			if ($selectID == 0) {
				echo "<td class='uitree-td' style='" . $width . "'></td>";
			} else {
				$value = $column->data[$selectID];
				echo "<td class='uitree-td' style='" . $width . "'>" . $value . "</td>";
			}
			
				
		} else {
			echo "<td class='uitree-td' style='" . $width . "'>" . $row->$variable . "</td>";
		}
		
	}
	
	private function showTree() {
	
		$this->createJavaScripts();
		
		//echo "<br>column1count - " . count($this->columns[1]);
		//echo "<br>column2count - " . count($this->columns[2]);
		$columncount = 0;
				
		echo "<table class='listtable' style='width:" . $this->width . "'>";
		echo "<tr>";
		foreach($this->columns[1] as $index => $column) {
			//echo "<br>name - " . $column->name;
			$this->createColumnHeader($column);
			$columncount++;
		}
		echo "</tr>";
		
		echo "<tr>";
		echo "	<td colspan=" . ($columncount+1) . ">";
		echo "		<table>";
		echo "			<tr>";	
		echo "				<td style='width:20px;'></td>";
		foreach($this->columns[2] as $index => $column) {
			//echo "<br>name2 - " . $column->name;
			$this->createColumnHeader($column, $column->width);
		}
		echo "			</tr>";	
		echo "		</table>";
		echo "	</td>";
		echo "</tr>";
		
		foreach ($this->data as $index => $row) {
			
			echo "<tr>";
			foreach($this->columns[1] as $index2 => $column) {
				$this->createUIFixedColumnTD($column,$row);
			}
			echo "			</tr>";
				
			//echo "<br>Childcount - " . count($row->getChildren());
			
			$childs = $row->getChilds();
			foreach($childs as $index3 => $childrow) {
				echo "<tr>";
				echo "	<td colspan=" . ($columncount+1) . ">";
				echo "		<table style='width:100%;'>";
				echo "			<tr>";	
				echo "				<td style='width:20px;'></td>";
				foreach ($this->columns[2] as $index2 => $column2) {
					$this->createUIFixedColumnTD($column2,$childrow, $column2->width);
				}
				echo "			</tr>";
				echo "		</table>";
				echo "	</td>";
				echo "</tr>";
			}
		}
		
		
		
		
		/*
		foreach($this->data as $index => $row) {
				
			if ($this->lineaction!=null) echo "<tr onclick='".$this->lineaction."(".$row->getID().")'>";
			if ($this->lineaction==null) echo "<tr>";
				
			echo "<td class='uitree-td'><b>".$row->name."</b></td>";
	
			foreach($this->columns as $index => $column) {
				if ($column instanceof UIFixedColumn) {
					$this->createUIFixedColumnTD($column,$row);
				}
			}
	
			echo "<td title='Siirra tili ylas' class='uitree-td'>";
			echo "<img onclick='event.cancelBubble=true;moveAccount(".$row->getID().",\"up\");' height=".$this->iconsize." width=".$this->iconsize." src='".$this->iconup."'>";
			echo "</td>";
			echo "<td title='Siirra tili alas' class='uitree-td'>";
			echo "<img onclick='event.cancelBubble=true;moveAccount(".$row->getID().",\"down\");' height=".$this->iconsize." width=".$this->iconsize." src='".$this->icondown."'>";
			echo "</td>";
	
			echo "</tr>";
				
			$this->showChilds($row,1);
		}
		*/
		
		echo "</table>";
	}
	
	
	
	private function showChilds($row,$depth) {
	
		if (count ($row->getChildren()) > 0) {
			$depth++;
			foreach ($row->getChildren() as $index => $child) {
					
				if ($this->lineaction!=null) echo "<tr onclick='".$this->lineaction."(".$child->getID().")'>";
				if ($this->lineaction==null) echo "<tr>";
	
				echo "<td class='uitree-td'>";
					
				for($spacing=0;$spacing<$depth;$spacing++) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
	
				$datavar = $this->treecolumnname;
				echo "".$child->$datavar . "</td>";
				foreach($this->columns as $index => $column) {
					if ($column instanceof UIFixedColumn) {
						$this->createUIFixedColumnTD($column,$child,$column->datavariable);
					}
				}
					
				echo "<td title='Siirra tili ylas' class='uitree-td'>";
				echo "<img onclick='event.cancelBubble=true;moveAccount(".$child->getID().",\"up\");' height=".$this->iconsize." width=".$this->iconsize." src='".$this->iconup."'>";
				echo "</td>";
				echo "<td title='Siirra tili alas' class='uitree-td'>";
				echo "<img onclick='event.cancelBubble=true;moveAccount(".$child->getID().",\"down\");' height=".$this->iconsize." width=".$this->iconsize." src='".$this->icondown."'>";
				echo "</td>";
				echo "</tr>";
					
				$this->showChilds($child,$depth);
			}
		}
	}
	
	
	private function createJavaScripts() {
	
		echo "<script>																				";
		echo "																						";
		echo "	function moveAccount(id,direction) {												";
		echo " 		var url = '".getUrl('sandbox/moveaccount')."';									";
		echo "		url = url + '&id=' + id + '&move=' + direction;									";
		echo "		window.location = url;															";
		echo "	}																					";
		echo "																						";
		echo "</script>																				";
	
		echo "<script>																				";
		echo "																						";
		echo "	function showAccount(id) {															";
		echo "		window.location='".getUrl('accounting/accountchart/showaccount')."&id='+id;		";
		echo "	}																					";
		echo "																						";
		echo "</script>																				";
	
	}
	
	function show() {
		$this->showTree();
	}
}
?>