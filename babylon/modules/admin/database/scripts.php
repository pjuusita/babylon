<?php


$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->moduleID, $this->registry->modules, "admin/database/scripts", "","moduleID", "name");


echo "<table style='width:600px;'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$filterbox->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";


if ($this->registry->moduleID == 0) {
	

	$table = new UITableSection("Tietokantataulut - " . $_SESSION['database'], "500px");
	
	$nameColumn = new UISortColumn("Name", "name");
	$nameColumn->setLink('admin/database/scripts','moduleID');
	$table->addColumn($nameColumn);
	
	$table->setData($registry->modules);
	$table->show();
	
} else {

	$table = new UITableSection("Scriptit - " . $registry->module->modulename, "500px");
	
	$nameColumn = new UISortColumn("Name", "name");
	$nameColumn->setLink('admin/database/executescript&moduleID=' . $registry->module->moduleID,'name');
	$table->addColumn($nameColumn);
	
	$table->setData($registry->files);
	$table->show();
	
}



?>