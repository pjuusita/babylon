<?php


class UITree {
	
	private static $treecount = 0;
	protected $treeID;
	
	private $title;
	private $width;
	private $data = null;
	private $iconup;
	private $icondown;
	private $iconSize;
	private $columns = null;
	private $lineaction = null;
	private $treecolumnname;
	
	
	
	public function __construct($title, $treecolumnname, $width = "100%") {
		$this->treeID = self::$treecount;
		self::$treecount++;
		$this->title = $title;
		$this->width = $width;
		$this->treecolumnname = $treecolumnname;
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function setData($data) {
		$this->data = $data;
	}
	
	public function addColumn($column) {
		if ($this->columns == null) $this->columns = array();
		$this->columns[] = $column;
	}
	
	public function setIcons($upIcon,$downIcon,$size) {
		$this->iconup = $upIcon;
		$this->icondown = $downIcon;
		$this->iconsize = $size;
	}
	
	public function setLineAction($lineaction) {
		$this->lineaction = $lineaction;
	}
	 
	private function createUIFixedColumnheader($column) {
		echo "<th>".$column->name."</th>";
	}
	
	private function createUIFixedColumnTD($column,$row) {
		$variable = $column->datavariable;
		echo "<td class='uitree-td'>".$row->$variable."</td>";
	}
	
	private function showTree() {
		
		$this->createJavaScripts();
		
		echo "<table class='listtable' style='width:100%'>";
		echo "<th>".$this->title."</th>";
		
		foreach($this->columns as $index => $column) {
			if ($column instanceof UIFixedColumn) {
				$this->createUIFixedColumnHeader($column);
			}
		}
		
		// Nuolten TH:t
		echo "<th></th><th></th>";
		
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