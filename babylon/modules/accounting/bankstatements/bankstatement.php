<?php

echo "<a href='".getUrl('accounting/bankstatements/showbankstatements')."'>Palaa tositelistalle</a><br>";
echo "<br>";

$width = "1000px";

$section = new UISection("Tiliote", $width);
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'accounting/bankstatements/updatebankstatement', 'bankstatementID');


$field = new UIDateField("Alkupäivä", "startdate", 'startdate');
$section->addField($field);

$field = new UIDateField("Loppupäivä", "enddate", 'enddate');
$section->addField($field);

$field = new UITextField("Alkusaldo", "startamount", 'startamount');
$section->addField($field);

$section->setData($registry->bankstatement);
$section->show();





$editstatementrow = new UISection("Tilioterivin muokkaus");
$editstatementrow->setDialog(true);
$editstatementrow->setMode(UIComponent::MODE_INSERT);
$editstatementrow->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/bankstatements/updatebankstatementrow&statementID=' . $registry->bankstatement->bankstatementID, "rowID");


//$field = new UISelectField("Maksajayritys","companyID","companyID",$registry->companies, "name");
//$editstatementrow->addField($field);

//$field = new UISelectField("Maksajahenkilö","clientID","clientID",$registry->clients, "name");
//$editstatementrow->addField($field);

//$field = new UISelectField("Maksun saaja","supplierID","supplierID",$registry->suppliers, "name");
//$editstatementrow->addField($field);

//$field = new UISelectField("Palkkamaksu","workerID","workerID",$registry->workers, "name");
//$editstatementrow->addField($field);

$field = new UITextField("Saaja/maksaja", "rowname", 'rowname');
$editstatementrow->addField($field);

$field = new UITextField("Viite", "reference", 'reference');
$editstatementrow->addField($field);

$field = new UIDateField("Vientipäivä", "entrydate", 'entrydate');
$editstatementrow->addField($field);

$field = new UITextField("Euromäärä", "amount", 'amount');
$editstatementrow->addField($field);

//$field = new UISelectField("Status","status","status",$registry->statuses);
//$editstatementrow->addField($field);

$editstatementrow->show();



$insertstatementrow = new UISection("Tilioterivin lisäys");
$insertstatementrow->setDialog(true);
$insertstatementrow->setMode(UIComponent::MODE_INSERT);
$insertstatementrow->setSaveAction(UIComponent::ACTION_FORWARD, "accounting/bankstatements/insertbankstatementrow&statementID=" . $registry->bankstatement->bankstatementID);

//$field = new UISelectField("Maksajayritys","companyID","companyID",$registry->companies, "name");
//$insertstatementrow->addField($field);

//$field = new UISelectField("Maksajahenkilö","clientID","clientID",$registry->clients, "name");
//$insertstatementrow->addField($field);

//$field = new UISelectField("Maksun saaja","supplierID","supplierID",$registry->suppliers, "name");
//$insertstatementrow->addField($field);

//$field = new UISelectField("Palkkamaksu","workerID","workerID",$registry->workers, "name");
//$insertstatementrow->addField($field);

$field = new UITextField("Saaja/Maksaja", "rowname", 'rowname');
$insertstatementrow->addField($field);

$field = new UITextField("Viite", "reference", 'reference');
$insertstatementrow->addField($field);

$field = new UIDateField("Päivämäärä", "entrydate", 'entrydate');
$insertstatementrow->addField($field);

$field = new UITextField("Euromäärä", "amount", 'amount');
$insertstatementrow->addField($field);

$lastdate = "";
foreach($registry->bankstatementrows as $index => $row) $lastdate = $row->entrydate;
$row = new Row();
$row->entrydate = $lastdate;
$insertstatementrow->setData($row);

$insertstatementrow->show();


	/*
	 $processentry = new UISection("Tilioterivin tiliöinti");
	 $processentry->setDialog(true);
	 $processentry->setMode(UIComponent::MODE_INSERT);
	 $processentry->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/bankstatements/updateentry&statementID=' . $registry->statementID, "rowID");

	 $field = new UITextField("Brutto", "amount", 'amount');
	 $processentry->addField($field);

	 $field = new UIDateField("Vientipäivä", "entrydate", 'entrydate');
	 $processentry->addField($field);

	 $field = new UISelectField("Status","status","status",$registry->statuses);
	 $processentry->addField($field);

	 $updateentry->show();
	 */



function linkingDiv() {

	global $registry;
	
	
	echo "	<table style='width:100%'>";
	
	echo "		<input class=uitextfield  id=linkingrowID type='hidden' style='width:100%;' type='text' value=''>";
	echo "		<input class=uitextfield  id=linkingentrydate type='hidden' style='width:100%;' type='text' value=''>";
	echo "		<input class=uitextfield  id=linkingreferenceID type='hidden' style='width:100%;' type='text' value=''>";
	echo "		<input class=uitextfield  id=linkingsupplierID type='hidden' style='width:100%;' type='text' value=''>";
	echo "		<input class=uitextfield  id=linkingcompanyID type='hidden' style='width:100%;' type='text' value=''>";
	echo "		<input class=uitextfield  id=linkingworkerID type='hidden' style='width:100%;' type='text' value=''>";
	echo "		<input class=uitextfield  id=linkingclientID type='hidden' style='width:100%;' type='text' value=''>";
	
	echo " 	<tr>";
	echo " 		<td class=field-text style='width:26%;'>Maksaja/Saaja</td>";
	echo " 		<td id=linkingsource class='iu-middle-block field-value' style='width:36%;'></td>";
	echo "		<td style'width:36%'></td>";
	echo " </tr>";
	
	echo " 	<tr>";
	echo " 		<td class=field-text style='width:26%;'>Viitetieto</td>";
	echo " 		<td id=linkingreference  class='iu-middle-block field-value' style='width:36%;'></td>";
	echo "		<td style'width:36%'></td>";
	echo " </tr>";
	
	echo " 	<tr>";
	echo " 		<td class=field-text style='width:26%;'>Maksupäivä</td>";
	echo " 		<td id=linkingpaymentdate  class='iu-middle-block field-value' style='width:36%;'></td>";
	echo "		<td style'width:36%'></td>";
	echo " </tr>";
	
	echo " 	<tr>";
	echo " 		<td class=field-text style='width:26%;'>Euromäärä</td>";
	echo " 		<td id=linkingamount   class='iu-middle-block field-value' style='width:36%;'></td>";
	echo "		<td style'width:36%'></td>";
	echo " </tr>";

	echo " 	<tr>";
	echo " 		<td class=field-text style='width:26%;'></td>";
	echo " 		<td class='iu-middle-block field-value' style='width:36%;'><button onclick=\"linkToOpen()\">kohdista avoimeen saldoon</button></td>";
	echo "		<td style'width:36%'></td>";
	echo " </tr>";
	
	
	echo "		<tr>";
	echo "			<td colspan=3 style='height:8px;'>";
	echo "		</tr>";
	
	echo "		<tr>";
	echo "			<td colspan=3 style='border-top:2px solid;border-color:#ccc;height:6px;'>";
	echo "		</tr>";
	
	echo "		<tr>";
	echo "			<td colspan=2>";

	echo "				<div id=linkingloadingdiv style='display:none;height:100%;width:100%;'>";
	echo "					<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
	echo "				</div>";

	echo "				<div id=linkingloadeddiv style='display:none;height:100%;width:570px;overflow:hidden'>";
	echo "					<div style='overflow-y:scroll;max-height:200px;width:570px;'>";
	echo "					<table id=linkingloadresulttable style='width:550px;height:50px;table-layout:fixed;'>";
	echo "						<tr><td>Empty</td></tr>";
	echo "					</table>";
	echo "					</div>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	echo "	</table>";

	echo "	<script>";
	echo "		function addItem(conceptID) {";
	echo "			alert('addconcept button pressed');";
	echo "		}";
	echo "	</script>";


	/*
	echo "	<script>";
	echo "		function wordsearchbuttonpressed() {";
	echo "			var searh = $('#wordsearchfield').val();";
	echo "			if (searh == '') {";
	echo "				alert('ei saa olla tyhjä');";
	echo "			}";
	echo "			$('#wordsearchloadingdiv').show();";
	echo "			$('#wordsearchloadeddiv').hide();";
	echo "			console.log('" . getUrl('worder/words/searchwordsJSON') . "&lang=" . $registry->languageID . "&search='+searh);";

	echo "			$.getJSON('" . getUrl('worder/words/searchwordsJSON') . "&lang=" . $registry->languageID . "&search='+searh,'',function(data) {";
	echo "					$('#wordsearchloadingdiv').hide();";
	echo "					$('#wordsearchloadeddiv').show();";
	echo "					$('#wordsearchresulttable tr').remove();";
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
	echo "		}";
	echo "	</script>";
	*/
}



$linkingdialog = new UISection("Tilioterivin linkitys","600px");
$linkingdialog->setDialog(true);

$linkingdialog->setCustomContent('linkingDiv');
$linkingdialog->show();


echo "	<script>";
echo "		function setValue_" . $linkingdialog->getID(). "(fieldname, value) {";
echo "			console.log('setting value - ' + fieldname + '='+value);";
echo "			if (fieldname =='rowID') {";
echo "				if (value == 0) {";
echo "					alert('Alkusaldoriviä ei voi linkittää');";
echo "					return 0;";
echo "				}";
echo "			}";

echo "			if (fieldname == 'rowID') {";
echo "				$('#linkingrowID').val(value);";
echo "			}";
echo "			if (fieldname == 'sourceID') {";
echo "				$('#linkingsource').html(value);";
echo "			}";
echo "			if (fieldname == 'entrydate') {";
echo "				$('#linkingentrydate').val(value);";
echo "				$('#linkingpaymentdate').html(value);";
echo "			}";
echo "			if (fieldname == 'referenceID') {";
echo "				$('#linkingreferenceID').val(value);";
echo "				if (value == '') {";
echo "					$('#linkingreference').html('<font size=-1><i>Ei asetettu</i></font>');";
echo "				} else {";
echo "					$('#linkingreference').html(value);";
echo "				}";
echo "			}";
echo "			if (fieldname == 'supplierID') {";
echo "				$('#linkingsupplierID').val(value);";
echo "			}";
echo "			if (fieldname == 'companyID') {";
echo "				$('#linkingcompanyID').val(value);";
echo "			}";

echo "			if (fieldname == 'clientID') {";
echo "				$('#linkingclientID').val(value);";
echo "			}";


echo "			if (fieldname == 'amount') {";
echo "				$('#linkingamount').html(value);";
echo "			}";

echo "			if (fieldname == 'workerID') {";
echo "				$('#linkingworkerID').val(value);";
echo "			}";

echo "			if (fieldname == 'clientID') {";
echo "				console.log('lastone load content');";
echo "				var companyID = $('#linkingcompanyID').val();";
echo "				var supplierID = $('#linkingsupplierID').val();";
echo "				var workerID = $('#linkingworkerID').val();";
echo "				var clientID = $('#linkingclientID').val();";
echo "				var rowID = $('#linkingrowID').val();";

echo "				console.log('load companyID - '+companyID);";
echo "				console.log('load supplierID - '+supplierID);";
echo "				console.log('load workerID - '+workerID);";
echo "				console.log('load clientID - '+clientID);";
echo "				console.log('load rowID - '+rowID);";


//					Haetaan linkittämättömät myyntilaskut, vastaanottaja saataisiin companyID:stä
echo "				if (companyID > 0) {";
echo "					console.log('" . getUrl('sales/invoices/getunaccountedinvoicesJSON') . "&companyID='+companyID);";
echo "					$.getJSON('" . getUrl('sales/invoices/getunaccountedinvoicesJSON') . "&companyID='+companyID,'',function(data) {";
echo "						$('#linkingloadingdiv').hide();";
echo "						$('#linkingloadeddiv').show();";
echo "						$('#linkingloadresulttable tr').remove();";
echo "						$.each(data, function(index) {";
echo "							console.log('data - '+data[index].invoicedate);";

echo "							var row = '<tr>'";
echo "								+ '<td style=\"padding-right:10px;\">'+data[index].invoiceID+'</td>'";
echo "								+ '<td style=\"padding-right:10px;\">'+data[index].invoicedate+'</td>'";
echo "								+ '<td style=\"padding-right:10px;\">'+data[index].duedate+'</td>'";
echo "								+ '<td style=\"padding-right:10px;\">'+data[index].grossamount+'</td>'";
//echo "								+ '<td style=\"width:300px;overflow:hidden;\" title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/concepts/showconcept') . "&id='+data[index].conceptID+'\">'+data[index].name+'</a></td>'";
echo "								+ '<td><button onclick=\"linksalesInvoice(\''+rowID+'\',\''+data[index].invoiceID+'\')\">kohdista</button></td>'";
echo "								+ '</tr>';";
echo "							$('#linkingloadresulttable').append(row);";
echo "						});";
echo "					}); ";
echo "					console.log('end');";
echo "				}";

//					Haetaan linkittämättömät palkkalaskelmat, supplierID saataisiin supplierID:stä
echo "				if (supplierID > 0) {";
echo "					console.log('load supplier');";
echo "					console.log('" . getUrl('sales/invoices/getunaccountedinvoicesJSON') . "&supplierID='+supplierID);";
echo "					$.getJSON('" . getUrl('sales/invoices/getunaccountedinvoicesJSON') . "&supplierID='+supplierID,'',function(data) {";
echo "						$('#linkingloadingdiv').hide();";
echo "						$('#linkingloadeddiv').show();";
echo "						$('#linkingloadresulttable tr').remove();";
echo "						$.each(data, function(index) {";
echo "							console.log('data - '+data[index].invoicedate);";

echo "							var row = '<tr>'";
echo "								+ '<td style=\"padding-right:10px;\">'+data[index].invoiceID+'</td>'";
echo "								+ '<td style=\"padding-right:10px;\">'+data[index].invoicedate+'</td>'";
echo "								+ '<td style=\"padding-right:10px;\">'+data[index].duedate+'</td>'";
echo "								+ '<td style=\"padding-right:10px;\">'+data[index].grossamount+'</td>'";
//echo "								+ '<td style=\"width:300px;overflow:hidden;\" title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/concepts/showconcept') . "&id='+data[index].conceptID+'\">'+data[index].name+'</a></td>'";
echo "								+ '<td><button onclick=\"linkPurchase(\''+rowID+'\',\''+data[index].invoiceID+'\')\">kohdista</button></td>'";
echo "								+ '</tr>';";
echo "							$('#linkingloadresulttable').append(row);";
echo "						});";
echo "					}); ";
echo "					console.log('end');";
echo "				}";


echo "				if (clientID > 0) {";
echo "					console.log('" . getUrl('sales/invoices/getunaccountedinvoicesJSON') . "&clientID='+clientID);";
echo "					$.getJSON('" . getUrl('sales/invoices/getunaccountedinvoicesJSON') . "&clientID='+clientID,'',function(data) {";
echo "						$('#linkingloadingdiv').hide();";
echo "						$('#linkingloadeddiv').show();";
echo "						$('#linkingloadresulttable tr').remove();";
echo "						$.each(data, function(index) {";
echo "							console.log('data - '+data[index].invoicedate);";

echo "							var row = '<tr>'";
echo "								+ '<td style=\"padding-right:10px;\">'+data[index].invoiceID+'</td>'";
echo "								+ '<td style=\"padding-right:10px;\">'+data[index].invoicedate+'</td>'";
echo "								+ '<td style=\"padding-right:10px;\">'+data[index].duedate+'</td>'";
echo "								+ '<td style=\"padding-right:10px;\">'+data[index].grossamount+'</td>'";
//echo "								+ '<td style=\"width:300px;overflow:hidden;\" title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/concepts/showconcept') . "&id='+data[index].conceptID+'\">'+data[index].name+'</a></td>'";
echo "								+ '<td><button onclick=\"linksalesInvoice(\''+rowID+'\',\''+data[index].invoiceID+'\')\">kohdista</button></td>'";
echo "								+ '</tr>';";
echo "							$('#linkingloadresulttable').append(row);";
echo "						});";
echo "					}); ";
echo "					console.log('end');";
echo "				}";

//					Haetaan linkittämättömät palkkalaskelmat, henkilöID saataisiin workerID:stä
echo "				if (workerID > 0) {";
echo "					console.log('load worker');";

echo "				}";
echo "			}";
echo "		}";
echo "	</script>";



echo "	<script>";
echo "		function linkToOpen() {";
echo "				var companyID = $('#linkingcompanyID').val();";
echo "				var supplierID = $('#linkingsupplierID').val();";
echo "				var workerID = $('#linkingworkerID').val();";
echo "				var clientID = $('#linkingclientID').val();";
echo "				var statementrowID = $('#linkingrowID').val();";

echo "				if (companyID > 0) {";
echo "					alert('link to open companyID not implemented');";
echo "				}";

echo "				if (supplierID > 0) {";
echo "					alert('link to open supplierID not implemented');";
echo "				}";

echo "				if (clientID > 0) {";
echo "					window.location='".getUrl('accounting/bankstatements/linksalesinvoicetoopenbalance')."&clientID='+clientID+'&statementrowID='+statementrowID;";
echo "				}";

echo "				console.log(' - linkToOpen - clientID - '+clientID);";
//echo "			window.location='".getUrl('accounting/bankstatements/linkpurchase')."&purchaseID='+purchaseID+'&statementrowID='+statementrowID;";
echo "		}";
echo "	</script>";


echo "	<script>";
echo "		function linkPurchase(statementrowID, purchaseID) {";
//echo "			alert('linkPurchase button pressed');";
echo "			console.log('linkpurhcase button pressed');";
echo "			console.log(' - statementrowID - '+statementrowID);";
echo "			console.log(' - purchaseID - '+purchaseID);";
echo "			window.location='".getUrl('accounting/bankstatements/linkpurchase')."&purchaseID='+purchaseID+'&statementrowID='+statementrowID;";
echo "		}";
echo "	</script>";

echo "	<script>";
echo "		function linksalesInvoice(statementrowID, invoiceID) {";
echo "			console.log('linksalesInvoice button pressed');";
echo "			console.log(' - statementrowID - '+statementrowID);";
echo "			console.log(' - invoiceID - '+invoiceID);";
echo "			window.location='".getUrl('accounting/bankstatements/linksalesinvoice')."&invoiceID='+invoiceID+'&statementrowID='+statementrowID;";
echo "		}";
echo "	</script>";

echo "	<script>";
echo "		function linkPaycheck(statementrowID, paycheckID) {";
echo "			alert('addconcept button pressed');";
echo "		}";
echo "	</script>";




$insertstatementreceipt = new UISection("Tositteen lisäys");
$insertstatementreceipt->setDialog(true);
$insertstatementreceipt->setMode(UIComponent::MODE_EDIT);
$insertstatementreceipt->setSaveAction(UIComponent::ACTION_FORWARD, "accounting/bankstatements/insertreceipt&bankstatementID=" . $registry->bankstatement->bankstatementID);

$field = new UITextField("#", "rowID", 'rowID');
$insertstatementreceipt->addField($field);

$field = new UISelectField("Tili","accountID","accountID",$registry->accounts, "fullname");
$field->setPredictive(true);
$insertstatementreceipt->addField($field);

$field = new UIDateField("Tositepäivä", "entrydate", 'entrydate');
$insertstatementreceipt->addField($field);

$field = new UITextField("Brutto", "amount", 'amount');
$insertstatementreceipt->addField($field);

$insertstatementreceipt->show();


$statementrowstable = new UITableSection("Tilioterivit",$width);
$statementrowstable->setOpen(true);
$statementrowstable->setFramesVisible(true);
$statementrowstable->setShowSumRow(true);

$statementrowstable->setLineAction(UIComponent::ACTION_OPENDIALOG, $editstatementrow->getID(),"rowID");
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertstatementrow->getID(), "Lisää tilioterivi");
$statementrowstable->addButton($button);

$column = new UISortColumn("RowID", "rowID");
$statementrowstable->addColumn($column);

$column = new UISortColumn("Päiväys", "entrydate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$statementrowstable->addColumn($column);

//$column = new UISelectColumn("Maksaja/Saaja", "name", "sourceID", $registry->sources);
//$statementrowstable->addColumn($column);

$column = new UISortColumn("Saaja/maksaja", "rowname");
$statementrowstable->addColumn($column);

$column = new UISortColumn("Viite/teksti", "reference");
$statementrowstable->addColumn($column);

$column = new UISortColumn("Määrä", "amount", "amount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$statementrowstable->addColumn($column);

$column = new UISortColumn("Saldo", "total", "total");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$statementrowstable->addColumn($column);

$column = new UISelectColumn("Tila", null, "status", $registry->statementrowstatuses);
$statementrowstable->addColumn($column);

//$column = new UISortColumn("ReceiptID", "receiptID");   // Voisi olla linkki
//$column->setLink('accounting/receipts/showreceipt','receiptID');
//$statementrowstable->addColumn($column);

$column = new UIHiddenColumn("SupplierID", "supplierID", "supplierID");
$statementrowstable->addColumn($column);

$column = new UIHiddenColumn("CompanyID", "companyID", "companyID");
$statementrowstable->addColumn($column);

$column = new UIHiddenColumn("WorkerID", "workerID", "workerID");
$statementrowstable->addColumn($column);

$column = new UIHiddenColumn("ClientID", "clientID", "clientID");
$statementrowstable->addColumn($column);


// Allaolevat nappulat voidaan toteuttaa vaihtoehtoisesti niin, että 
// laitettaisiin oikeaan laitaan täppä ja sitten ylärivillä olisi nappulat 'Linkitä' ja 'Luo tosite'
// Tämä ratkaisu säästäisi horisontaalista tilaaa

//$column = new UIButtonColumn(UIComponent::ACTION_OPENDIALOG, $linkingdialog->getID(), 'rowID');
//$column->setTitle("Linkitä");
//$statementrowstable->addColumn($column);

//$column = new UIButtonColumn(UIComponent::ACTION_OPENDIALOG, $insertstatementreceipt->getID(), 'rowID');
//$column->setTitle("Luo tosite");
//$statementrowstable->addColumn($column);

$statementrowstable->setData($registry->bankstatementrows);
$statementrowstable->show();


/*
$column = new UIStatusColumn("Tiliöity", "status");
$column->addStatus(1, "#66CC66");
$column->addStatus(3, "pink");
$column->addStatus(2, "yellow");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$entriestable->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "rowID", "accounting/bankstatements/bankstatementlinking&periodID=" . $this->registry->periodID);
$column->setTitle("Linkitä");
$entriestable->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "accounting/bankstatements/insertreceipt&periodID=" . $this->registry->periodID . "&bankaccountID=" . $selectedbankaccount->bankaccountID);
$column->setTitle("Luo tosite");
$entriestable->addColumn($column);

$column = new UIHiddenColumn("RowID", "rowID");
$entriestable->addColumn($column);

$entriestable->setData($registry->statementrows);
$entriestable->show();
*/




if ($registry->bankstatement->status > 0) {



	$editentryrow = new UISection("Viennin muokkaus");
	$editentryrow->setDialog(true);
	$editentryrow->setMode(UIComponent::MODE_INSERT);
	$editentryrow->setUpdateAction(UIComponent::ACTION_FORWARD, 'accounting/purchases/updateentry&purchaseID=' . $registry->purchase->purchaseID . '&receiptID=' . $registry->receipt->receiptID, "rowID");

	$field = new UITextField("EntryID", "entryID", 'entryID');
	$editentryrow->addField($field);

	$field = new UIDateField("Päiväys", "entrydate", 'entrydate');
	$editentryrow->addField($field);

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
	$insertentrysection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/purchases/insertentry&purchaseID=' . $registry->purchase->purchaseID . '&receiptID=' . $registry->receipt->receiptID);

	//$field = new UIDateField("Päiväys", "entrydate", 'entrydate');
	//$insertentrysection->addField($field);

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

	$empty = new Row();
	$empty->entrydate =$registry->receipt->receiptdate;
	$empty->amount =$registry->receipt->grossamount;

	$insertentrysection->setData($empty);
	$insertentrysection->show();
}



$receiptstable = new UITableSection("Tositteet", $width);
$receiptstable->setOpen(true);
$receiptstable->setFramesVisible(true);
$receiptstable->setShowSumRow(true);

$column = new UISortColumn("RowID", "receiptID");
$receiptstable->addColumn($column);

$column = new UISortColumn("Päiväys", "receiptdate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$receiptstable->addColumn($column);

$column = new UISortColumn("Tositenumero", "receiptnumber");
$receiptstable->addColumn($column);

$column = new UISortColumn("Määrä", "grossamount", "grossamount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$receiptstable->addColumn($column);


$receiptstable->setData($registry->receipts);
$receiptstable->show();


