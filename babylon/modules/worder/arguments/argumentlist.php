<?php



echo "<table style='width:800px;'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->wordclassID, $this->registry->wordclasses, "worder/arguments/showarguments", "","wordclassID", "name");
$filterbox->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";




// ---------------------------------------------------------------------------------------------------
// Taulukko
// ---------------------------------------------------------------------------------------------------

$table = new UITableSection("Arguments", "800px");
$table->setFramesVisible(false);
//$table->setMode(UIComponent::MODE_EDIT);	// tämä vaaditaan, että editsectionille asetetaan kenttiin arvot
$table->setLineAction(UIComponent::ACTION_FORWARD,"worder/arguments/showargument","argumentID");

$column = new UISortColumn("ArgumentID", "argumentID");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$table->addColumn($column);

$column = new UISortColumn("Name", "name");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$table->addColumn($column);

//$column = new UISortColumn("Kuvaus", "description", "");
//$column->setColumnType(Column::COLUMNTYPE_STRING);
//$table->addColumn($column);

$column = new UISelectColumn("Sanaluokka", "name", "wordclassID", $this->registry->wordclasses, "");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$table->addColumn($column);


$column = new UISelectColumn("Arvojoukko", "name", "wordclassvalueID", $this->registry->wordclasses, "");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$table->addColumn($column);

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), 'Lisää argumentti');
//$table->addButton($button);

$table->setData($this->registry->arguments);
$table->show();


?>