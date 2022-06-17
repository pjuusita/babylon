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
echo "			window.location='".getUrl('sales/invoices/showinvoices')."&periodID='+this.value;";
echo "		});";
echo "	</script>";



$insertsection = new UISection('Myyntilaskun lisäys','550px');
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'sales/invoices/insertinvoice');


if (count($this->registry->dimensions) > 0) {

	foreach($this->registry->dimensions as $index => $dimension) {
		$field = new UISelectField($dimension->name,"dimension" . $dimension->dimensionID,"dimension" . $dimension->dimensionID,$registry->dimensionvalues[$dimension->dimensionID], "name");
		$insertsection->addField($field);
	}
}

$invoicetypefield = new UISelectField("Laskutyyppi","invoicetypeID","invoicetypeID",$registry->invoicetypes, "name");
$insertsection->addField($invoicetypefield);

$clienttypefield = new UISelectField("Asiakastyyppi","clienttypeID","clienttypeID",$registry->clienttypes, "name");
$clienttypefield->setOnChange("clienttypechanged()");
$insertsection->addField($clienttypefield);

$companyfield = new UISelectField("Asiakasyritys","companyID","clientcompanyID",$registry->companies, "name");
$companyfield->setDisabled(true);
$insertsection->addField($companyfield);

$contactpersonfield = new UISelectField("Yhteyshenkilö","clientID","contactpersonID",$registry->contactpersons, "name");
$contactpersonfield->setDisabled(true);
$insertsection->addField($contactpersonfield);

$privateclientfield = new UISelectField("Asiakas","clientID","clientpersonID",$registry->privateclients, "name");
$privateclientfield->setDisabled(true);
$insertsection->addField($privateclientfield);

$generalsalefield = new UISelectField("Myyntitili","saletypeID","saletypeID",$registry->saletypes, "name");
$generalsalefield->setDisabled(true);
$insertsection->addField($generalsalefield);

$invoicedatefield = new UIDateField("Laskupäivä", "invoicedate", 'invoicedate');
$invoicedatefield->setDisabled(true);
$insertsection->addField($invoicedatefield);

$duedatefield = new UIDateField("Eräpäivä", "duedate", 'duedate');
$duedatefield->setDisabled(true);
$insertsection->addField($duedatefield);


// tämä tulee asiakkaan oletustiedoista, seuraava arkipäivä 14pv
//$field = new UIDateField("Eräpäivä", "duedate", 'duedate');
//$insertsection->addField($field);
//echo "<br>Lastdate - " . $registry->lastdate;

$defaultinvoice = new Row();
$defaultinvoice->invoicedate = $registry->lastdate;
$defaultinvoice->duedate = $registry->lastdate;

$insertsection->setData($defaultinvoice);
$insertsection->show();



echo "<script>";
echo "	function clienttypechanged() {";

echo "		console.log('clienttypechanged');";
echo "	 	var clienttypefield = '#".$clienttypefield->getEditFieldID()."';";
echo "	 	var companyfield = '#".$companyfield->getEditFieldID()."';";
echo "	 	var contactpersonfield = '#".$contactpersonfield->getEditFieldID()."';";
echo "	 	var privateclientfield = '#".$privateclientfield->getEditFieldID()."';";
echo "	 	var generalsalefield = '#".$generalsalefield->getEditFieldID()."';";
echo "	 	var invoicedatefield = '#".$invoicedatefield->getEditFieldID()."';";
echo "	 	var duedatefield = '#".$duedatefield->getEditFieldID()."';";

echo "	 		var clienttype = $(clienttypefield).val();";
echo "			console.log('clienttype is '+clienttype);";

echo "			switch(clienttype) {";

echo "				case '1':";			// yritysasiakas

echo "					$(companyfield).removeAttr('disabled');";
echo "					$(companyfield).addClass('uitextfield');";
echo "					$(companyfield).removeClass('uitextfield-disabled');";

echo "					$(contactpersonfield).removeAttr('disabled');";
echo "					$(contactpersonfield).addClass('uitextfield');";
echo "					$(contactpersonfield).removeClass('uitextfield-disabled');";

echo "					$(privateclientfield).attr('disabled', 'disabled');";
echo "					$(privateclientfield).removeClass('uitextfield');";
echo "					$(privateclientfield).addClass('uitextfield-disabled');";

echo "					$(generalsalefield).attr('disabled', 'disabled');";
echo "					$(generalsalefield).removeClass('uitextfield');";
echo "					$(generalsalefield).addClass('uitextfield-disabled');";

echo "					break;";

echo "				case '2':";			// kuluttaja-asiakas

echo "					$(companyfield).attr('disabled', 'disabled');";
echo "					$(companyfield).removeClass('uitextfield');";
echo "					$(companyfield).addClass('uitextfield-disabled');";

echo "					$(contactpersonfield).attr('disabled', 'disabled');";
echo "					$(contactpersonfield).removeClass('uitextfield');";
echo "					$(contactpersonfield).addClass('uitextfield-disabled');";

echo "					$(privateclientfield).removeAttr('disabled');";
echo "					$(privateclientfield).addClass('uitextfield');";
echo "					$(privateclientfield).removeClass('uitextfield-disabled');";

echo "					$(generalsalefield).attr('disabled', 'disabled');";
echo "					$(generalsalefield).removeClass('uitextfield');";
echo "					$(generalsalefield).addClass('uitextfield-disabled');";

echo "					break;";

echo "				case '3':";			// ei asiakasta

echo "					$(companyfield).attr('disabled', 'disabled');";
echo "					$(companyfield).removeClass('uitextfield');";
echo "					$(companyfield).addClass('uitextfield-disabled');";

echo "					$(contactpersonfield).attr('disabled', 'disabled');";
echo "					$(contactpersonfield).removeClass('uitextfield');";
echo "					$(contactpersonfield).addClass('uitextfield-disabled');";

echo "					$(privateclientfield).attr('disabled', 'disabled');";
echo "					$(privateclientfield).removeClass('uitextfield');";
echo "					$(privateclientfield).addClass('uitextfield-disabled');";

echo "					$(generalsalefield).removeAttr('disabled');";
echo "					$(generalsalefield).addClass('uitextfield');";
echo "					$(generalsalefield).removeClass('uitextfield-disabled');";

echo "					break;";


echo "				default:";			// none selected

echo "					$(companyfield).attr('disabled', 'disabled');";
echo "					$(companyfield).removeClass('uitextfield');";
echo "					$(companyfield).addClass('uitextfield-disabled');";

echo "					$(contactpersonfield).attr('disabled', 'disabled');";
echo "					$(contactpersonfield).removeClass('uitextfield');";
echo "					$(contactpersonfield).addClass('uitextfield-disabled');";

echo "					$(privateclientfield).attr('disabled', 'disabled');";
echo "					$(privateclientfield).removeClass('uitextfield');";
echo "					$(privateclientfield).addClass('uitextfield-disabled');";

echo "					$(generalsalefield).attr('disabled', 'disabled');";
echo "					$(generalsalefield).removeClass('uitextfield');";
echo "					$(generalsalefield).addClass('uitextfield-disabled');";

echo "					break;";
echo "			}";

echo "	}";
echo "</script>";











$tabsection = new UITabSection("","900px");

foreach($this->registry->selection as $index => $selection) {
	$tabIndex = $tabsection->addTab($selection->name, "sales/invoices/showinvoices&selectionID=" . $selection->selectionID);
	//echo "<br>" . $selection->selectionID . " - " . $this->registry->selectionID;
	if ($selection->selectionID == $this->registry->selectionID){
		$tabsection->setActiveIndex($tabIndex);
	}
}



$table = new UITableSection("Myyntilaskut","850px");
$table->setOpen(true);
$table->setFramesVisible(false);
$table->setShowSumRow(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Uusi lasku');
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"sales/invoices/showinvoice","invoiceID");


$column = new UISortColumn("Laskupäivä", "invoicedate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);


if (count($this->registry->dimensions) > 0) {
	foreach($this->registry->dimensions as $index => $dimension) {
		$column = new UISelectColumn($dimension->abbreviation, "name", "dimension" . $dimension->dimensionID, $registry->dimensionvalues[$dimension->dimensionID]);
		$column->setUndefinedString("-");
		$table->addColumn($column);
	}
}


$column = new UISortColumn("Eräpäivä", "duedate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

$column = new UISortColumn("Asiakas", "description");
$table->addColumn($column);

$column = new UISortColumn("Viite", "referencenumber");
$table->addColumn($column);

$column = new UISortColumn("Brutto", "grossamount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$table->addColumn($column);

$column = new UISortColumn("Netto", "netamount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$table->addColumn($column);

//$column = new UISortColumn("Tila", "state");
//$table->addColumn($column);

$column = new UISelectColumn("Tila", null, "state", $registry->invoicestates);
$table->addColumn($column);


$table->setData($registry->invoices);


$tabsection->setContent($table);
$tabsection->show();


?>