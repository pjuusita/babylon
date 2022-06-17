<?php
	

	$filterbox = new UIFilterBox();
	$filterbox->addSelectFilter($this->registry->moduleID, $this->registry->modules, "admin/database/showdatabasetables", "","moduleID", "name");
	
	
	echo "<table style='width:600px;'>";
	echo "	<tr>";
	echo "		<td style='width:60%;vertical-align:bottom'>";
	echo "		</td>";
	echo "		<td style='width:40%;text-align:right;'>";
	$filterbox->show();
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	


	$table = new UITableSection("Tietokantataulut - " . $_SESSION['database'], "500px");
	$table->showLineNumbers(true);
	$nameColumn = new UISortColumn("Name", "name");
	$nameColumn->setLink('admin/database/showdatabasetable','tableID');
	$table->addColumn($nameColumn);
	
	$button = new UIButton(UIComponent::ACTION_FORWARD,'admin/database/showinserttable', "Lisää");
	$table->addButton($button);
	
	$table->setData($registry->tables);
	$table->show();
	
	echo "<br>count: " . count($registry->tables);
?>