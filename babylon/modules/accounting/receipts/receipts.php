<?php


echo "<table style='width:900px;'>";
echo "	<tr>";
echo "		<td>";
echo "			<div style='width:400px;display:flex;'>";

echo "<select id=periodselectfield class='top-select'  style='display:inline;width:150px;margin-right:5px;margin-bottom:15px;'>";
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
echo "			window.location='".getUrl('accounting/receipts/showreceipts')."&periodID='+this.value;";
echo "		});";
echo "	</script>";


echo "<select id=receiptserieselect class='top-select' style='display:inline;width:200px;margin-right:5px;margin-bottom:15px;'>";
echo "<option value='0'></option>";
foreach($this->registry->receiptsets as $index => $receiptset) {
	if ($this->registry->receiptsetID ==  $receiptset->receiptsetID) {
		echo "<option  selected='selected' value='" . $index . "'>" . $receiptset->name . "</option>";
	} else {
		echo "<option value='" . $index . "'>" . $receiptset->name . "</option>";
	}
}
echo "</select>";


echo "	<script>";
echo "		$('#receiptserieselect').on('change', function() {";
echo "			window.location='".getUrl('accounting/receipts/showreceipts')."&receiptsetID='+this.value;";
echo "		});";
echo "	</script>";

echo "</div>";
echo "			</td>";
echo "			<td style='float:right;display:flex;'>";


echo "<button class=section-button id='receiptsbuttonpdf' style='margin-right:5px;font-size:16px;font-weight:bold;'>PDF</button>";

echo "<script>";
echo "  $('#receiptsbuttonpdf').click(function () {";
echo "			alert('not implemented');";
//echo "		opennewtab('" . getPdfUrl("accounting/incomestatement/incomestatementpdf") . "');";
echo "	});";
echo "</script>";


echo "<div class=top-button style='display:inline;width:32px;height:32px;padding-top:6px;padding-left:5px;'><i class='fa fa-cog fa-lg' ></i></div>";
echo "			</td>";

echo "	</tr>";
echo "</table>";








$tabsection = new UITabSection("","900px");

$tabIndex = $tabsection->addTab("" . $registry->period->name, "accounting/receipts/showreceipts&selectionID=0");
foreach($this->registry->selection as $index => $selection) {
	$tabIndex = $tabsection->addTab($selection->name, "accounting/receipts/showreceipts&selectionID=" . $selection->selectionID);
	//echo "<br>" . $selection->selectionID . " - " . $this->registry->selectionID;
	if ($selection->selectionID == $this->registry->selectionID){
		$tabsection->setActiveIndex($tabIndex);
	}
}


$showdimension = true;
global $dimensionselect;
$dimensionselect = 1;

global $dimensionarray;

if (count($this->registry->dimensionvalues) == 0) {
	$dimensionarray = array();
} else {
	$dimensionarray = $this->registry->dimensionvalues[$dimensionselect];
}


//global $registry;
//$registry = $this->registry;

function tabcontent() {

	global $registry;
	//$registry = $this->registry;
	
	$insertsection = new UISection("Tositteen lisäys");
	$insertsection->setDialog(true);
	$insertsection->setMode(UIComponent::MODE_INSERT);
	$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/receipts/insertreceipt');
	
	//$field = new UISelectField("Maksun saaja", "receiverID", 'receiverID', $registry->receivers, 'name');
	//$insertsection->addField($field);
	
	$field = new UISelectField("Tositesarja", "receiptsetID", 'receiptsetID', $registry->receiptsets, 'name');
	$insertsection->addField($field);
	
	$field = new UIDateField("Tositepäivä", "receiptdate", 'receiptdate');
	$insertsection->addField($field);
	
	
	//$field = new UISelectField("Kustannuspaikka", "costpoolID", 'costpoolID', $registry->costpools, 'name');
	//$insertsection->addField($field);
	
	// Selite voitaisiin napata kyseisen laskuttajan olemassaolevista selityksistä
	// Tästä saattaa olla jokin esimerkki jossain vastaava toteutus
	//$nimifield = new UITextField("Selite", "otsikko", 'explanation');				// Tämä voisi olla ennakoivaa tekstinsyöttöä pudotusvalikko, jos ei löydy lisätään uusi
	//$insertsection->addField($nimifield);
	
	//$field = new UITextField("Brutto", "grossamount", 'grossamount');
	//$insertsection->addField($field);
	
	$empty = new Row();
	$empty->receiptsetID = $registry->receiptsetID;
	$empty->receiptdate = $registry->receiptlastdate;
	
	$insertsection->setData($empty);
	
	$insertsection->show();
	
	
	
	echo "<br>";
	
	if ($registry->receiptsetID > 0) {
		$table = new UITableSection($registry->receiptsets[$registry->receiptsetID]->name,"870px");
	} else {
		$table = new UITableSection("Tositteet","870px");
	}
	$table->setOpen(true);
	$table->setFramesVisible(false);
	$table->setShowSumRow(true);
	
	$table->setLineAction(UIComponent::ACTION_FORWARD,"accounting/receipts/showreceipt","receiptID");
	
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää tosite");
	$table->addButton($button);
	
	
	
	if (count($registry->dimensions) > 0) {
		foreach($this->registry->dimensions as $index => $dimension) {
			$column = new UISelectColumn($dimension->abbreviation, "name", "dimension" . $dimension->dimensionID, $registry->dimensionvalues[$dimension->dimensionID]);
			$column->setUndefinedString("-");
			$table->addColumn($column);
		}
	}
	
	
	$column = new UISortColumn("#", "receiptID");
	$table->addColumn($column);
	
	
	$column = new UISortColumn("Päiväys", "receiptdate");
	$column->setFormatter(Column::COLUMNTYPE_DATE);
	$table->addColumn($column);
	
	$column = new UISortColumn("Tositeumero", "receiptnumber");
	$column->setColumnType(Column::COLUMNTYPE_STRING);
	$table->addColumn($column);
	
	//$column = new UISelectColumn("Kustannuspaikka", "name", "costpoolID", $this->registry->costpools);
	//$table->addColumn($column);
	
	//$column = new UISortColumn("Selite", "explanation");
	//$column->setColumnType(Column::COLUMNTYPE_STRING);
	//$table->addColumn($column);
	
	
	$column = new UISortColumn("Selite", "explanation", 'explanation');
	$column->setColumnType(Column::COLUMNTYPE_STRING);
	$table->addColumn($column);
	
	//$column = new UISelectColumn("Maksun_saaja", "name", "receiverID", $this->registry->receivers);
	//$table->addColumn($column);
	
	$column = new UISortColumn("Debet", "debet");
	$column->setAlign(Column::ALIGN_RIGHT);
	$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
	$table->addColumn($column);
	
	$column = new UISortColumn("Credit", "credit");
	$column->setAlign(Column::ALIGN_RIGHT);
	$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
	$table->addColumn($column);
	
	/*
	 $column = new UIStatusColumn("Tiliöity", "status");
	 $column->addStatus(3, "pink");
	 $column->addStatus(1, "#66CC66");
	 $column->addStatus(2, "yellow");
	 $column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
	 $column->setAlign(Column::ALIGN_RIGHT);
	 $table->addColumn($column);
	
	 $column = new UIStatusColumn("Maksettu", "paymentstatus");
	 $column->addStatus(1, "#66CC66");
	 $column->addStatus(0, "pink");
	 $column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
	 $column->setAlign(Column::ALIGN_RIGHT);
	 $table->addColumn($column);
	*/
	
	$table->setData($registry->receipts);
	$table->show();
	
}
	
	
	
$tabsection->setCustomContent("tabcontent");
$tabsection->show();
	
	
	



?>