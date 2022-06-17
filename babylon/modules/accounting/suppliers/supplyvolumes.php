<?php


echo "<select id=periodselectfield class='top-select' style='width:150px;margin-right:5px;margin-bottom:15px;'>";
foreach($this->registry->periods as $index => $period) {
	if ($this->registry->periodID ==  $period->periodID) {
		echo "<option selected='selected' value='" . $index . "'>" . $period->fullname . "</option>";
	} else {
		echo "<option value='" . $index . "'>" . $period->fullname . "</option>";
	}
}
echo "</select>";

echo "	<script>";
echo "		$('#periodselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/purchases/showpurchases')."&periodID='+this.value;";
echo "		});";
echo "	</script>";






$table = new UITableSection("Toimitusmäärät", "700px");
//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
//$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"accounting/suppliers/showsupplier","supplierID");

$column = new UISortColumn("#", "supplierID", "", null, "50px");
$table->addColumn($column);

$column = new UISortColumn("Nimi", "name", "", null, "90%");
$table->addColumn($column);

$column = new UISortColumn("Määrä", "amount", "", null, "10%");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$table->addColumn($column);

$table->setData($registry->suppliers);
$table->show();

