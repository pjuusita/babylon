<?php



/*
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
*/


$table = new UITableSection("Logfiles", "500px");
	
$nameColumn = new UISortColumn("Name", "name");
$nameColumn->setLink('admin/timelog/analysefile','name');
$table->addColumn($nameColumn);
	
$table->setData($registry->files);
$table->show();



?>