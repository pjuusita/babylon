<?php




echo "<a href='".getUrl('sales/invoices/showinvoices')."'>Palaa laskulistaan</a><br>";



$section = new UISection("Myyntilaskun tiedot", "800px");
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'sales/invoices/updateinvoice', 'invoiceID');

if (count($this->registry->dimensions) > 0) {
	foreach($this->registry->dimensions as $index => $dimension) {
		$field = new UISelectField($dimension->name,"dimension" . $dimension->dimensionID,"dimension" . $dimension->dimensionID,$registry->dimensionvalues[$dimension->dimensionID], "name");
		$section->addField($field);
	}
}


$invoicetypefield = new UISelectField("Laskutyyppi","invoicetypeID","invoicetypeID",$registry->invoicetypes, "name");
$section->addField($invoicetypefield);

$clienttypefield = new UISelectField("Asiakastyyppi","clienttypeID","clienttypeID",$registry->clienttypes, "name");
$clienttypefield->setOnChange("clienttypechanged()");
$section->addField($clienttypefield);

$companyfield = new UISelectField("Asiakasyritys","clientcompanyID","clientcompanyID",$registry->companies, "name");
if ($registry->invoice->clienttypeID != 1) $companyfield->setHideFromView(true);
if ($registry->invoice->clienttypeID != 1) $companyfield->setHiddenFromEdit(true);
$section->addField($companyfield);

$contactpersonfield = new UISelectField("Yhteyshenkilö","contactpersonID","contactpersonID",$registry->contactpersons, "name");
if ($registry->invoice->clienttypeID != 1) $contactpersonfield->setHideFromView(true);
if ($registry->invoice->clienttypeID != 1) $contactpersonfield->setHiddenFromEdit(true);
$section->addField($contactpersonfield);

$privateclientfield = new UISelectField("Asiakas","clientpersonID","clientpersonID",$registry->privateclients, "name");
if ($registry->invoice->clienttypeID != 2) $privateclientfield->setHideFromView(true);
if ($registry->invoice->clienttypeID != 2) $privateclientfield->setHiddenFromEdit(true);
$section->addField($privateclientfield);

$generalsalefield = new UISelectField("Myyntityyppi","saletypeID","saletypeID",$registry->saletypes, "name");
if ($registry->invoice->clienttypeID != 3) $generalsalefield->setHideFromView(true);
if ($registry->invoice->clienttypeID != 3) $generalsalefield->setHiddenFromEdit(true);
$section->addField($generalsalefield);

$field = new UIDateField("Laskupäivä", "invoicedate", 'invoicedate');
$section->addField($field);

$field = new UIDateField("Eräpäivä", "duedate", 'duedate');
$section->addField($field);

$field = new UITextField("Brutto", "grossamount", 'grossamount');
$section->addField($field);

$field = new UITextField("Netto", "netamount", 'netamount');
$section->addField($field);

$field = new UITextField("Viitenumero", "referencenumber", 'referencenumber');
$section->addField($field);

$field = new UITextField("TositeID", "receiptID", 'receiptID');
$section->addField($field);

$field = new UITextField("Tositenumero", "receiptnumber", 'receiptnumber');
$section->addField($field);


$field = new UITextField("Maksamatta", "unpaidamount", 'unpaidamount');
$section->addField($field);


if ($registry->invoice->state == 2) {
	$registry->invoice->statetext = "Odottaa maksua";
	$field = new UITextField("Maksutila", "statetext", 'statetext');
	$section->addField($field);
}


if ($registry->invoice->state == 3) {
	$registry->invoice->statetext = "Osittain maksettu, maksamatta " . $registry->invoice->unpaidamount;
	$field = new UITextField("Maksutila", "statetext", 'statetext');
	$section->addField($field);
}

if ($registry->invoice->state == 4) {
	$registry->invoice->statetext = "Maksettu, " . $registry->invoice->paymentdate;
	$field = new UITextField("Maksutila", "statetext", 'statetext');
	$section->addField($field);
}

if ($registry->receipt != null) {
	$registry->invoice->receiptnumber = $registry->receipt->receiptnumber;
} else {
	$registry->invoice->receiptnumber = "";
}


$section->setData($registry->invoice);
$section->show();


echo "<script>";
echo "	function clienttypechanged() {";

echo "	 	var clienttypefield = '#".$clienttypefield->getEditFieldID()."';";
echo "	 	var clienttype = $(clienttypefield).val();";

echo "	 	var companyline = '#selectline-".$companyfield->getId()."';";
echo "	 	var contactpersonline = '#selectline-".$contactpersonfield->getId()."';";
echo "	 	var privateclientline = '#selectline-".$privateclientfield->getId()."';";
echo "	 	var generalsaleline = '#selectline-".$generalsalefield->getId()."';";

echo "		console.log('clienttypechanged - '+clienttype);";

echo "			switch(clienttype) {";

echo "				case '1':";			// yritysasiakas
echo "					$(companyline).show();";
echo "					$(contactpersonline).show();";
echo "					$(privateclientline).hide();";
echo "					$(generalsaleline).hide();";
echo "					break;";

echo "				case '2':";			// kuluttaja-asiakas

echo "					$(companyline).hide();";
echo "					$(contactpersonline).hide();";
echo "					$(privateclientline).show();";
echo "					$(generalsaleline).hide();";
echo "					break;";

echo "				case '3':";			// ei asiakasta
echo "					$(companyline).hide();";
echo "					$(contactpersonline).hide();";
echo "					$(privateclientline).hide();";
echo "					$(generalsaleline).show();";
echo "					break;";

echo "				default:";			// none selected
echo "					$(companyline).hide();";
echo "					$(contactpersonline).hide();";
echo "					$(privateclientline).hide();";
echo "					$(generalsaleline).hide();";
echo "					break;";
echo "			}";

echo "	}";
echo "</script>";



$insertinvoicerow = new UISection("Myyntilaskurivin lisäys");
$insertinvoicerow->setDialog(true);
$insertinvoicerow->setMode(UIComponent::MODE_INSERT);
$insertinvoicerow->setSaveAction(UIComponent::ACTION_FORWARD, 'sales/invoices/insertinvoicerow&invoiceID=' . $registry->invoice->invoiceID);


$defaultinvoicerow = new Row();
if (count($this->registry->dimensions) > 0) {
	foreach($this->registry->dimensions as $index => $dimension) {
		$variable = "dimension" . $dimension->dimensionID;
		$field = new UISelectField($dimension->name, $variable, $variable, $registry->dimensionvalues[$dimension->dimensionID], "name");
		$insertinvoicerow->addField($field);
		$defaultinvoicerow->$variable = $registry->invoice->$variable;
	}
}
$insertinvoicerow->setData($defaultinvoicerow);


$productfield = new UISelectField("Tuote","productID","productID",$registry->products, "name");
$productfield->setOnChange("productchanged_" . $insertinvoicerow->getID() . "()");
$insertinvoicerow->addField($productfield);

$unitamountfield = new UITextField("Yksikkömäärä", "unitamount", 'unitamount');
$unitamountfield->setOnBlur("unitamountchanged_" . $insertinvoicerow->getID() . "()");
$insertinvoicerow->addField($unitamountfield);

$unitpricefield = new UITextField("Yksikköhinta", "unitprice", 'unitprice');
$unitpricefield->setOnBlur("unitpricechanged_" . $insertinvoicerow->getID() . "()");
$insertinvoicerow->addField($unitpricefield);

$alvfield = new UISelectField("ALV","vatID","vatID",$registry->vats, "name");
$alvfield->setOnChange("alvchanged_" . $insertinvoicerow->getID() . "()");
$insertinvoicerow->addField($alvfield);

$insertnetfield = new UITextField("Netto", "netamount", 'netamount');
$insertinvoicerow->addField($insertnetfield);

$insertgrossfield = new UITextField("Brutto", "grossamount", 'grossamount');
$insertinvoicerow->addField($insertgrossfield);

$insertinvoicerow->show();


echo "<script>";
echo "	function alvchanged_" . $insertinvoicerow->getID() . "() {";
echo "		updateinsertamounts_" . $insertinvoicerow->getID() . "();";
echo "	}";
echo "</script>";


echo "<script>";
echo "	function productchanged_" . $insertinvoicerow->getID() . "() {";
echo "		console.log('productchanged changed');";
//echo "			console.log('hae yksikköhinta serveriltä, tarvitaan asiakasID ja tuoteID...);";
/*
echo "			$.getJSON('" . getUrl('worder/words/searchwordsJSON') . "&lang=" . $registry->languageID . "&search='+searh,'',function(data) {";
echo "					$.each(data, function(index) {";
echo "						var row = '<tr>'";
echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordclassID+'</td>'";
echo "							+ '<td style=\"padding-right:10px;\">'+data[index].conceptID+'</td>'";
echo "							+ '<td style=\"width:300px;overflow:hidden;\" title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/concepts/showconcept') . "&id='+data[index].conceptID+'\">'+data[index].name+'</a></td>'";
echo "							+ '<td><button onclick=\"addItem(\''+data[index].conceptID+'\')\">lisää</button></td>'";
echo "							+ '</tr>';";
echo "						$('#wordsearchresulttable').append(row);";
echo "					});";
echo "			}); ";
*/
echo "	}";
echo "</script>";


echo "<script>";
echo "	function unitpricechanged_" . $insertinvoicerow->getID() . "() {";
echo "		console.log('unitprice changed');";
echo "		updateinsertamounts_" . $insertinvoicerow->getID() . "();";
echo "	}";
echo "</script>";


echo "<script>";
echo "	function unitamountchanged_" . $insertinvoicerow->getID() . "() {";
echo "		console.log('unitamount changed');";
echo "		updateinsertamounts_" . $insertinvoicerow->getID() . "();";
echo "	}";
echo "</script>";


echo "<script>";
echo "	function updateinsertamounts_" . $insertinvoicerow->getID() . "() {";

echo "		var vatpercent = 0;";
echo "	 	var vatID = $('#" . $alvfield->getEditFieldID() . "').val();";
foreach($registry->vats as $index => $vat) {
	echo "	if (vatID == " . $vat->vatID . ") vatpercent = " . $vat->percent . ";";
}
echo "	 	var priceString = $('#" . $unitpricefield->getEditFieldID() . "').val();";
echo "	 	var amountString = $('#" . $unitamountfield->getEditFieldID() . "').val();";

echo "		console.log('vat -'+vatpercent);";
echo "		console.log('price - '+priceString);";
echo "		console.log('amount - '+amountString);";

echo "		var price = parseFloat(priceString);";
echo "		if (isNaN(price)) {";
echo "			console.log('price not number');";
echo "			return;";
echo "		}";
echo "		var amount = parseFloat(amountString);";
echo "		if (isNaN(amount)) {";
echo "			console.log('amount not number');";
echo "			return;";
echo "		}";

echo "		var net = amount * price;";
echo "		var gross = net * ((vatpercent/100)+1);";

echo "		console.log('-- net - '+net);";
echo "		console.log('-- gross - '+gross);";

echo "	 	$('#" . $insertnetfield->getEditFieldID() . "').val(net.toFixed(2));";
echo "	 	$('#" . $insertgrossfield->getEditFieldID() . "').val(gross.toFixed(2));";

echo "	}";
echo "</script>";



//if ($registry->invoice->state == 0) {

	$editinvoicerow = new UISection("Laskurivin muokkaus");
	$editinvoicerow->setDialog(true);
	$editinvoicerow->setMode(UIComponent::MODE_INSERT);
	$editinvoicerow->setUpdateAction(UIComponent::ACTION_FORWARD, 'sales/invoices/updateinvoicerow&invoiceID=' . $registry->invoice->invoiceID, "rowID");

	if (count($this->registry->dimensions) > 0) {
		foreach($this->registry->dimensions as $index => $dimension) {
			$variable = "dimension" . $dimension->dimensionID;
			$field = new UISelectField($dimension->name, $variable, $variable, $registry->dimensionvalues[$dimension->dimensionID], "name");
			$editinvoicerow->addField($field);
		}
	}
	
	$productfield = new UISelectField("Tuote","productID","productID",$registry->products, "name");
	$productfield->setOnChange("productchanged_" . $editinvoicerow->getID() . "()");
	$editinvoicerow->addField($productfield);

	$unitamountfield = new UITextField("Yksikkömäärä", "unitamount", 'unitamount');
	$unitamountfield->setOnBlur("unitamountchanged_" . $editinvoicerow->getID() . "()");
	$editinvoicerow->addField($unitamountfield);
	
	$unitpricefield = new UITextField("Yksikköhinta", "unitprice", 'unitprice');
	$unitpricefield->setOnBlur("unitpricechanged_" . $editinvoicerow->getID() . "()");
	$editinvoicerow->addField($unitpricefield);
	
	$alvfield = new UISelectField("ALV","vatID","vatID",$registry->vats, "name");
	$alvfield->setOnChange("alvchanged_" . $editinvoicerow->getID() . "()");
	$editinvoicerow->addField($alvfield);
	
	$insertnetfield = new UITextField("Netto", "netamount", 'netamount');
	$editinvoicerow->addField($insertnetfield);
	
	$insertgrossfield = new UITextField("Brutto", "grossamount", 'grossamount');
	$editinvoicerow->addField($insertgrossfield);
	
	
	
	/*
	 $field = new UISelectField("Tili","accountID","accountID",$registry->accounts, "fullname");
	 $editinvoicerow->addField($field);
	
	 $field = new UIDateField("Vientipäivä", "entrydate", 'entrydate');
	 $editinvoicerow->addField($field);
	
	 $field = new UITextField("Debet", "debet", 'debet');
	 $editinvoicerow->addField($field);
	
	 $field = new UITextField("Credit", "credit", 'credit');
	 $editinvoicerow->addField($field);
	*/
	
	
	
	$editinvoicerow->show();
	
	

	echo "<script>";
	echo "	function alvchanged_" . $editinvoicerow->getID() . "() {";
	echo "		updateinsertamounts_" . $editinvoicerow->getID() . "();";
	echo "	}";
	echo "</script>";
	
	
	echo "<script>";
	echo "	function productchanged_" . $editinvoicerow->getID() . "() {";
	echo "		console.log('productchanged changed');";
	echo "	}";
	echo "</script>";
	
	
	echo "<script>";
	echo "	function unitpricechanged_" . $editinvoicerow->getID() . "() {";
	echo "		console.log('unitprice changed');";
	echo "		updateinsertamounts_" . $editinvoicerow->getID() . "();";
	echo "	}";
	echo "</script>";
	
	
	echo "<script>";
	echo "	function unitamountchanged_" . $editinvoicerow->getID() . "() {";
	echo "		console.log('unitamount changed');";
	echo "		updateinsertamounts_" . $editinvoicerow->getID() . "();";
	echo "	}";
	echo "</script>";
	
	
	
	echo "<script>";
	echo "	function updateinsertamounts_" . $editinvoicerow->getID() . "() {";
	
	echo "		var vatpercent = 0;";
	echo "	 	var vatID = $('#" . $alvfield->getEditFieldID() . "').val();";
	foreach($registry->vats as $index => $vat) {
		echo "	if (vatID == " . $vat->vatID . ") vatpercent = " . $vat->percent . ";";
	}
	echo "	 	var priceString = $('#" . $unitpricefield->getEditFieldID() . "').val();";
	echo "	 	var amountString = $('#" . $unitamountfield->getEditFieldID() . "').val();";
	
	echo "		console.log('vat -'+vatpercent);";
	echo "		console.log('price - '+priceString);";
	echo "		console.log('amount - '+amountString);";
	
	echo "		var price = parseFloat(priceString);";
	echo "		if (isNaN(price)) {";
	echo "			console.log('price not number');";
	echo "			return;";
	echo "		}";
	echo "		var amount = parseFloat(amountString);";
	echo "		if (isNaN(amount)) {";
	echo "			console.log('amount not number');";
	echo "			return;";
	echo "		}";
	
	echo "		var net = amount * price;";
	echo "		var gross = net * ((vatpercent/100)+1);";
	
	echo "		console.log('-- net - '+net);";
	echo "		console.log('-- gross - '+gross);";
	
	echo "	 	$('#" . $insertnetfield->getEditFieldID() . "').val(net.toFixed(2));";
	echo "	 	$('#" . $insertgrossfield->getEditFieldID() . "').val(gross.toFixed(2));";
	
	echo "	}";
	echo "</script>";
	
//} else {
	
	/*
	$editinvoicerow = new UISection("Laskurivin muokkaus - ei voi muokata");
	$editinvoicerow->setDialog(true);
	$editinvoicerow->setMode(UIComponent::MODE_INSERT);
	$editinvoicerow->setUpdateAction(UIComponent::ACTION_FORWARD, 'sales/invoices/updateinvoicerow&invoiceID=' . $registry->invoice->invoiceID, "rowID");
	
	
	$editinvoicerow->show();
}
	*/







$invoicerowstable = new UITableSection("Laskurivit", '800px');
$invoicerowstable->setOpen(true);
$invoicerowstable->setFramesVisible(true);
$invoicerowstable->setShowSumRow(true);

$invoicerowstable->setDeleteAction(UIComponent::ACTION_FORWARD, 'sales/invoices/removeinvoicerow&invoiceID=' . $registry->invoice->invoiceID, 'rowID');

$invoicerowstable->setLineAction(UIComponent::ACTION_OPENDIALOG, $editinvoicerow->getID(), "invoicerowID");

if ($registry->invoice->state == 0) {
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertinvoicerow->getID(), "Lisää laskurivi");
	$invoicerowstable->addButton($button);
}

$column = new UISortColumn("#", "rowID", "rowID");
$invoicerowstable->addColumn($column);


if (count($this->registry->dimensions) > 0) {

	foreach($this->registry->dimensions as $index => $dimension) {

		//$field = new UISelectField($dimension->abbreviation,"dimension" . $dimension->dimensionID,"dimension" . $dimension->dimensionID,$registry->dimensionvalues[$dimension->dimensionID], "abbreviation");
		$column = new UISelectColumn($dimension->abbreviation, "name", "dimension" . $dimension->dimensionID, $registry->dimensionvalues[$dimension->dimensionID]);
		$column->setUndefinedString("-");
		$invoicerowstable->addColumn($column);
	}
}

$column = new UISortColumn("Tuote", "productname", "productname");
$invoicerowstable->addColumn($column);

$column = new UISelectColumn("ALV%", "short", "vatID", $registry->vats);
$column->setAlign(Column::ALIGN_RIGHT);
$invoicerowstable->addColumn($column);

$column = new UISortColumn("Määrä", "unitamount", "unitamount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$invoicerowstable->addColumn($column);

$column = new UISortColumn("á", "unitprice", "unitprice");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$invoicerowstable->addColumn($column);

$column = new UISortColumn("ALV", "vatamount", "vatamount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$invoicerowstable->addColumn($column);

$column = new UISortColumn("Netto", "netamount", "netamount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$invoicerowstable->addColumn($column);

$column = new UISortColumn("Brutto", "grossamount", "grossamount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$invoicerowstable->addColumn($column);

$column = new UIHiddenColumn("TuoteID", "productID", "productID");
$invoicerowstable->addColumn($column);

$invoicerowstable->setData($registry->invoicerows);
$invoicerowstable->show();




//if ($registry->invoice->state > 0) {

	$editentryrow = new UISection("Viennin muokkaus");
	$editentryrow->setDialog(true);
	$editentryrow->setMode(UIComponent::MODE_INSERT);
	$editentryrow->setUpdateAction(UIComponent::ACTION_FORWARD, 'sales/invoices/updateentry&invoiceID=' . $registry->invoice->invoiceID . '&receiptID=' . $registry->invoice->receiptID, "rowID");
	
	$field = new UITextField("EntryID", "entryID", 'entryID');
	$field->setHiddenFromEdit(true);
	$editentryrow->addField($field);
	
	//$field = new UIDateField("Päiväys", "entrydate", 'entrydate');
	//$editentryrow->addField($field);
	
	
	$field = new UISelectField("Kirjanpitotili","accountID","accountID",$registry->accounts, "fullname");
	$editentryrow->addField($field);
	
	$alvfield = new UISelectField("ALV-Koodi","vatcodeID","vatcodeID",$registry->vatcodes, "name");
	$editentryrow->addField($alvfield);
	
	$insertgrossfield = new UITextField("Debet", "debet", 'debet');
	$editentryrow->addField($insertgrossfield);
	
	$insertnetfield = new UITextField("Credit", "credit", 'credit');
	$editentryrow->addField($insertnetfield);
	
	$editentryrow->show();

	
	
	$insertentrysection = new UISection("Viennin lisäys");
	$insertentrysection->setDialog(true);
	$insertentrysection->setMode(UIComponent::MODE_INSERT);
	$insertentrysection->setSaveAction(UIComponent::ACTION_FORWARD, 'sales/invoices/insertentry&invoiceID=' . $registry->invoice->invoiceID . '&receiptID=' . $registry->invoice->receiptID);
	
	$field = new UISelectField("Tili","accountID","accountID",$registry->accounts, "fullname");
	$field->setPredictive(true);
	$insertentrysection->addField($field);
	
	$field = new UISelectField("Vatcode","vatcodeID","vatcodeID",$registry->vatcodes, "vatcode");
	$insertentrysection->addField($field);
	
	$field = new UITextField("Amount", "amount", 'amount');
	$insertentrysection->addField($field);
	
	//$field = new UITextField("Debet", "debet", 'debet');
	//$insertentrysection->addField($field);
	
	//$field = new UITextField("Credit", "credit", 'credit');
	//$insertentrysection->addField($field);
	
	//$empty = new Row();
	//$empty->entrydate =$registry->invoice->receiptdate;
	//$empty->amount =$registry->receipt->grossamount;
	
	//$insertentrysection->setData($empty);
	$insertentrysection->show();
//}


	
	
//echo "<br>State - " . $registry->invoice->state;

if ($registry->invoice->state > 0) {
	$entriestable = new UITableSection("Viennit", '800px');
	$entriestable->setDeleteAction(UIComponent::ACTION_FORWARD, 'sales/invoices/removeentry&invoiceID=' . $registry->invoice->invoiceID . '&receiptID=' . $registry->invoice->receiptID, 'entryID');
	$entriestable->setLineAction(UIComponent::ACTION_OPENDIALOG, $editentryrow->getID(), "entryID");
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertentrysection->getID(), "Lisää vienti");
	$entriestable->addButton($button);
} else {
	$entriestable = new UITableSection("Viennit (alustavat)", '800px');
}

$entriestable->setOpen(true);
$entriestable->setFramesVisible(true);
$entriestable->setShowSumRow(true);

//$column = new UISortColumn("Päiväys", "entrydate");
//$column->setFormatter(Column::COLUMNTYPE_DATE);
//$entriestable->addColumn($column);

$column = new UISortColumn("#", "entryID", "entryID");
$entriestable->addColumn($column);

if (count($this->registry->dimensions) > 0) {

	foreach($this->registry->dimensions as $index => $dimension) {

		//$field = new UISelectField($dimension->abbreviation,"dimension" . $dimension->dimensionID,"dimension" . $dimension->dimensionID,$registry->dimensionvalues[$dimension->dimensionID], "abbreviation");
		$column = new UISelectColumn($dimension->abbreviation, "name", "dimension" . $dimension->dimensionID, $registry->dimensionvalues[$dimension->dimensionID]);
		$column->setUndefinedString("-");
		$entriestable->addColumn($column);
	}
}

//$column = new UISelectColumn("Tilinro", "number", "accountID", $registry->accounts);
//$entriestable->addColumn($column);

$column = new UISelectColumn("Tili", "fullname", "accountID", $registry->accounts);
$entriestable->addColumn($column);


//$column = new UISortColumn("VatcodeID", "vatcodeID", "vatcodeID");
//$entriestable->addColumn($column);

$column = new UISelectColumn("Vatcode", "vatcode", "vatcodeID", $registry->vatcodes);
$entriestable->addColumn($column);


$column = new UISortColumn("Debet", "debet", "debet");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$entriestable->addColumn($column);

$column = new UISortColumn("Credit", "credit", "credit");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$entriestable->addColumn($column);

// TODO: 	ei muistikuvaa mihin tätä tarvitaan? Ehkä osittainmaksuun.
//			osittainmaksu pitää hoitaa toisella tavalla.

//$column = new UISortColumn("Tila", "state", "state");
//$entriestable->addColumn($column);

$column = new UIHiddenColumn("AccountID", "accountID", "accountID");
$entriestable->addColumn($column);

$column = new UIHiddenColumn("EnryID", "entryID", "entryID");
$entriestable->addColumn($column);

//$column = new UIHiddenColumn("Päiväys", "entrydate");
//$column->setFormatter(Column::COLUMNTYPE_DATE);
//$entriestable->addColumn($column);

$entriestable->setData($registry->entries);
$entriestable->show();



$copyinvoicesection = new UISection('Myyntilaskun kopiointi','550px');
$copyinvoicesection->setDialog(true);
$copyinvoicesection->setMode(UIComponent::MODE_INSERT);
$copyinvoicesection->setSaveAction(UIComponent::ACTION_FORWARD, "sales/invoices/copyinvoice&invoiceID=" . $registry->invoice->invoiceID);

$field = new UISelectField("Asiakas","companyID","companyID",$registry->companies, "name");
$copyinvoicesection->addField($field);

$invoicedatefield = new UIDateField("Laskupäivä", "invoicedate", 'invoicedate');
$invoicedatefield->setOnChange("invoicedatechanged()");
$copyinvoicesection->addField($invoicedatefield);

$invoiceduedatefield = new UIDateField("Eräpäivä", "duedate", 'duedate');
$copyinvoicesection->addField($invoiceduedatefield);


echo "<script>";
echo "	function invoicedatechanged(value, event) {";

//echo "		console.log('invoicedatechanged');";

echo "	 	var datefieldID 		 	 = '#".$invoicedatefield->getEditFieldID()."';";
echo "	 	var duedatefieldID 		 	 = '#".$invoiceduedatefield->getEditFieldID()."';";

echo "	 	var originaldatefield = '#editfield-" . $invoicedatefield->getID() . "original';";

echo "	 	var date = $(datefieldID).val();";
echo "	 	var duedate = $(duedatefieldID).val();";
echo "	 	var original = $(originaldatefield).val();";
//echo "		console.log('date -'+date+'-');";
//echo "		console.log('original -'+original+'-');";

echo "		var year = date.substring(6,10);";
echo "		var month = parseInt(date.substring(3,5))-1;";
//echo "		console.log('month - '+month);";
echo "		var day = date.substring(0,2);";

echo "		var oyear = original.substring(6,10);";
echo "		var omonth = parseInt(original.substring(3,5))-1;";
//echo "		console.log('omonth - '+omonth);";

echo "		var oday = original.substring(0,2);";

echo "		var oneDay = 24 * 60 * 60 * 1000;";
echo "		var firstDate = new Date(year, month, day);";
echo "		var secondDate = new Date(oyear, omonth, oday);";
echo "		diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime()) / oneDay));";
//echo "		console.log('diffDays -'+diffDays+'-');";


// diffDays laskee väärin tammi-helmikuun
echo "		if ((original == 1) && (month == 0)) {";
echo "		}";

echo "		var dyear = duedate.substring(6,10);";
echo "		var dmonth = duedate.substring(3,5);";
echo "		var dday = duedate.substring(0,2);";

echo "		var dueDate = new Date(dyear, dmonth-1, dday);";
echo "		dueDate.setDate(dueDate.getDate() + diffDays);";
//echo "		console.log('dueDate -'+dueDate+'-');";

//echo "		console.log('year -'+dueDate.getYear()+'-');";
//echo "		console.log('month -'+dueDate.getMonth()+'-');";
//echo "		console.log('day -'+dueDate.getDate()+'-');";

echo "		var nyear = dueDate.getYear()+1900;";
echo "		var nmonth = dueDate.getMonth()+1;";
echo "		if (nmonth < 10) nmonth = '0'+nmonth;";
echo "		var nday = dueDate.getDate();";
echo "		if (nday < 10) nday = '0'+nday;";

echo "		var newduedate = nday + '.' + nmonth + '.' + nyear;";
//echo "		console.log('newduedate - '+newduedate);";

echo "	 	$(duedatefieldID).val(newduedate);";


//echo "		console.log('year -'+year+'-');";
//echo "		console.log('month -'+month+'-');";
//echo "		console.log('day -'+day+'-');";

//echo "		console.log('date - '+date);";
//echo "		console.log('duedate - '+duedate);";
//echo "		console.log('original - '+original);";


echo "	}";
echo "</script>";



$row = new Row();
$row->companyID = $registry->invoice->clientcompanyID;
$row->clientcompanyID = $registry->invoice->clientcompanyID;
$row->invoicedate = $registry->invoice->invoicedate;
$row->duedate = $registry->invoice->duedate;

$copyinvoicesection->setData($row);
$copyinvoicesection->show();








$managementSection = new UISection("Hallinta","800px");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $copyinvoicesection->getID(), "Kopioi lasku");
$managementSection->addButton($button);

$states = array();
$row = new Row();
$row->state = 0;
$row->name = "Avoin";
$states[0] = $row;

$row = new Row();
$row->state = 1;
$row->name = "Hyväksytty";
$states[1] = $row;

$row = new Row();
$row->state = 2;
$row->name = "Odottaa maksua";
$states[2] = $row;

$row = new Row();
$row->state = 3;
$row->name = "Osittain maksettu";
$states[3] = $row;

$row = new Row();
$row->state = 4;
$row->name = "Maksettu";
$states[4] = $row;


$field = new UISelectField("Laskun tila","state","state",$states, "name");
$managementSection->addField($field);

$field = new UITextField("State", "state", 'state');
$managementSection->addField($field);


if ($registry->invoice->state == 0) {
	$button = new UIButton(UIComponent::ACTION_FORWARD, "sales/invoices/acceptinvoice&invoiceID=" . $registry->invoice->invoiceID, "Merkitse hyväksytyksi");
	$managementSection->addButton($button);
	
	$button = new UIButton(UIComponent::ACTION_FORWARD, "sales/invoices/removeinvoice&invoiceID=" . $registry->invoice->invoiceID, "Poista lasku");
	$managementSection->addButton($button);
}

if ($registry->invoice->state == 1) {

	$button = new UIButton(UIComponent::ACTION_FORWARD, "sales/invoices/removeinvoice&invoiceID=" . $registry->invoice->invoiceID, "Poista lasku");
	$managementSection->addButton($button);

	$button = new UIButton(UIComponent::ACTION_FORWARD, "sales/invoices/marksendinvoice&invoiceID=" . $registry->invoice->invoiceID, "Merkitse lähetetyksi");
	$managementSection->addButton($button);
	
	$button = new UIButton(UIComponent::ACTION_FORWARD, "sales/invoices/markpayedinvoice&invoiceID=" . $registry->invoice->invoiceID, "Merkitse maksetuksi");
	$managementSection->addButton($button);
	
	$button = new UIButton(UIComponent::ACTION_FORWARD, "sales/invoices/openinvoice&invoiceID=" . $registry->invoice->invoiceID, "Palauta avoimeksi");
	$managementSection->addButton($button);
}	

if ($registry->invoice->state == 2) {
	// tämä tulee oikaestaan kohdistuksesta vasta
	$button = new UIButton(UIComponent::ACTION_FORWARD, "sales/invoices/markpayedinvoice&invoiceID=" . $registry->invoice->invoiceID, "Merkitse maksetuksi");		
	$managementSection->addButton($button);
}


// onko enää muita tiloja laskulla, se jää möllöttämään maksettu tilaan. Mitään erillistä arkistointia ei mielestäni tarvita


$managementSection->setData($registry->invoice);
$managementSection->show();



?>