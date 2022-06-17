<?php


//  Tämä on vanha purchases lista, eriytetään nämä laskulistaan ja ostoreskontraan. 
//
//	Ostoreskontrassa ollaan kiinnostuneita laskun maksutilasta, laskulista on enemmän
//  asioiden kuikuiluun. Käyttöliittymä voi arvata, että mitä sivua yleensä käytetään 
//  ja muuttaa oletussivua. Tämä voisi päteä kaikkeen päätason menuihin, framework hoitaa,
//  käyttäjäkohtainen asetus.
//
//

/*
$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->projectID, $this->registry->selection, "accounting/receipts/showreceipts", "Kaikki",$this->registry->selectedindex, "name");
$filterbox->addSelectFilter($this->registry->projectID, $this->registry->receiptsets, "accounting/receipts/showreceipts", "Kaikki","projectID", "name");
$filterbox->setEmptySelect(false);
*/
echo "<br>Jeejee";

echo "<table style='width:800px;'>";
echo "	<tr>";
echo "		<td style='width:100%;text-align:right;'>";
//$field = new UISelectField("","rowID","name", $this->registry->selection, "name");
//$field = new UISelectField("Käyttäjä","userID","UserID", $registry->users, "username");
//$field->show($this->registry->selectedindex);

echo "<select id=periodselectfield class='field-select' style='width:120px;margin-right:5px;'>";
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




echo "<select id=supplierselectfield class='field-select' style='width:120px;margin-right:5px;'>";
echo "<option value='0'></option>";
foreach($this->registry->suppliers as $index => $supplier) {
	if ($this->registry->supplierID ==  $supplier->supplierID) {
		echo "<option  selected='selected' value='" . $supplier->supplierID . "'>" . $supplier->name . "</option>";
	} else {
		echo "<option value='" . $supplier->supplierID . "'>" . $supplier->name . "</option>";
	}
}
echo "</select>";

echo "	<script>";
echo "		$('#supplierselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/purchases/showpurchases')."&supplierID='+this.value;";
echo "		});";
echo "	</script>";




echo "<select id=selectionselectfield class='field-select' style='width:90px;margin-right:5px;'>";
echo "<option value='0'></option>";
foreach($this->registry->selection as $index => $value) {
	if ($this->registry->selectionID ==  $value->rowID) {
		echo "<option  selected='selected' value='" . $value->rowID . "'>" . $value->name . "</option>";
	} else {
		echo "<option value='" . $value->rowID . "'>" . $value->name . "</option>";
	}
}
echo "</select>";



echo "	<script>";
echo "		$('#selectionselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/purchases/showpurchases')."&selectionID='+this.value;";
echo "		});";
echo "	</script>";



echo "			<input class=uitextfield id=startdatefield type='text'";
echo " 				style='width:83px;margin-right:3px;' value='" . sqlDateToStr($this->registry->startdate) . "' readonly>";
echo "-";
echo "			<input class=uitextfield id=enddatefield type='text'";
echo " 				style='width:83px;' value='" . sqlDateToStr($this->registry->enddate) . "' readonly>";

echo "	<script>";
echo "		$('#startdatefield').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
echo "	</script>";

echo "	<script>";
echo "		$('#enddatefield').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
echo "	</script>";

echo "		</td>";
echo "	</tr>";
echo "</table>";




$insertsection = new UISection('Ostolaskun lisäys','605px');
$insertsection->setDialog(true);
$_SESSION['global_sectionID'] = $insertsection->getID();
//echo "<br>SectionID - " . $_SESSION['global_sectionID'];

$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/purchases/insertpurchase');

$insertsection->setCustomContent('purchasepanelDiv');
$insertsection->show();


function purchasepanelDiv() {

	global $registry;
	$sectionID = $_SESSION['global_sectionID'];


	
	echo "	<table style='width:500px;padding-top:10px;'>";
	

	if (count($registry->dimensions) > 0) {
		foreach($registry->dimensions as $index => $dimension) {
			echo "		<tr>";
			echo "			<td class=field-text style='width:150px;'>" . $dimension->name . "</td>";
			echo "			<td style='width:250px;'>";
			echo "				<select id=dimensionfield-" . $sectionID . "-" . $dimension->dimensionID . " class=field-select style='width:200px;'>";
			echo "					<option value='0' selected></option>";
			foreach ($registry->dimensionvalues[$dimension->dimensionID] as $ind => $dimensionvalue) {
				echo "				<option value=" . $dimensionvalue->dimensionvalueID . ">" . $dimensionvalue->name . "</option>";
			}
			echo "				</select>";
			echo " 			</td>";
			echo "			<td id='messagefield-" . $sectionID . "-1 style='width:100px;'></td>";
			echo "		</tr>";
		}
	}
	
	
	
	echo "		<tr>";
	echo "			<td class=field-text style='width:150px;'>Myyjä</td>";
	echo "			<td style='width:250px;'>";
	echo "				<select id=field-" . $sectionID . "-1 class=field-select style='width:200px;'>";
	echo "					<option value='0' selected></option>";
	foreach ($registry->suppliers as $ind => $supplier) {
		echo "				<option value=" . $supplier->supplierID . ">" . $supplier->name . "</option>";
	}
	echo "				</select>";
	echo "				<button id='rowdeletebutton-" . $sectionID . "-1-7' class=section-button-header style='width:28px;height:25px;padding-top:3px;margin-left:2px;' onclick='unknown(1)'><i class='fa fa-external-link' ></i></button>";
	echo " 			</td>";
	echo "			<td id='messagefield-" . $sectionID . "-1 style='width:100px;'></td>";
	echo "		</tr>";


	echo "		<tr>";
	echo "			<td class=field-text>Maksutapa</td>";
	echo "			<td>";
	echo "				<select id=field-" . $sectionID . "-2 class=field-select style='width:200px;'>";
	echo "					<option value='0' selected></option>";
	foreach ($registry->paymentmethods as $ind => $paymentmethod) {
		echo "				<option value=" . $paymentmethod->paymentmenthodID . ">" . $paymentmethod->name . "</option>";
	}
	echo "				</select>";
	echo " 			</td>";
	echo "			<td></td>";
	echo "		</tr>";

	echo "		<tr>";
	echo "			<td class=field-text>Ostopäivä</td>";
	echo "			<td>";
	echo "				<div>";
	echo "					<table style='padding-left:0px;' cellpadding=0 cellspacing=0>";
	echo "						<tr>";
	echo "							<td>";
	echo "								<input id=field-" . $sectionID . "-3 class=uitextfield tabindex=3 style='text-align:center;width:100px;' type='text'														";
	//echo "									onkeyup='editfieldchanged_" . $fieldID . "()' onfocusin='editfielddeactivated_" . $fieldID . "()'";
	//echo " 									onfocusout='editfielddeactivated_" .$fieldID . "()'";
	$lastdatestr = $registry->lastdate;
	$defaultdate = substr($lastdatestr,8,2).".".substr($lastdatestr,5,2).".".substr($lastdatestr,0,4);
	echo " 									value='" . $defaultdate . "' readonly>";
	echo "							</td>";
	echo "							<td style='padding-left:4px;'>";
	echo "								<input type=button class='section-button' value='Tyhjennä' onClick='cleardatefield_" . $sectionID . "_3()'>";
	echo "							</td>";
	echo "						<tr>";
	echo "					</table>";
	echo "			  </div>";
	echo " 			</td>";
	echo "			<td></td>";
	echo "		</tr>";

	echo "	<script>";
	echo "		$('#field-" . $sectionID . "-3').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
	echo "	</script>";

	echo "		<tr>";
	echo "			<td class='field-text'>Eräpäivä</td>";
	echo "			<td>";
	echo "				<div>";
	echo "					<table style='padding-left:0px;width:100px;' cellpadding=0 cellspacing=0>";
	echo "						<tr>";
	echo "							<td style='width:50px;'>";
	echo "								<input id=field-" . $sectionID . "-4 class=uitextfield tabindex=3 style='text-align:center;width:100px;' type='text'";
	//echo "									onkeyup='editfieldchanged_" . $fieldID . "()' onfocusin='editfielddeactivated_" . $fieldID . "()'";
	//echo " 									onfocusout='editfielddeactivated_" .$fieldID . "()'";
	$lastdatestr = $registry->lastdate;
	$defaultdate = substr($lastdatestr,8,2).".".substr($lastdatestr,5,2).".".substr($lastdatestr,0,4);
	echo " 									value='" . $defaultdate . "' readonly>";
	echo "							</td>";
	echo "							<td style='padding-left:4px;'>";
	echo "								<input type=button class='section-button' value='Tyhjennä' onClick='cleardatefield_" . $sectionID . "_4()'>";
	echo "							</td>";
	echo "						<tr>";
	echo "					</table>";
	echo "				  </div>";
	echo " 			</td>";
	echo "			<td></td>";
	echo "		</tr>";

	echo "	<script>";
	echo "		$('#field-" . $sectionID . "-4').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1 });";
	echo "	</script>";

	echo "		<tr>";
	echo "			<td colspan=3 style='height:8px;'>";
	echo "		</tr>";

	echo "		<tr>";
	echo "			<td colspan=3 style='border-top:2px solid;border-color:#ccc;height:6px;'>";
	echo "		</tr>";

	echo "		<tr>";
	echo "			<td colspan=3>";
	echo "				<table id=purchaserowtable style='width:460px;'  cellpadding=0 cellspacing=0>";

	echo "					<tr>";
	echo "						<td class='listtable-header-nounderline'></td>";
	echo "						<td class='listtable-header-nounderline'>Kustannuspaikka</td>";
	echo "						<td class='listtable-header-nounderline'>ALV%</td>";
	echo "						<td class='listtable-header-nounderline'>ALV</td>";
	echo "						<td class='listtable-header-nounderline'>Netto</td>";
	echo "						<td class='listtable-header-nounderline'>Brutto</td>";
	echo "						<td class='listtable-header-nounderline'></td>";
	echo "					</tr>";


	echo "					<tr id='purchaserow-" . $sectionID . "-1' >";
	echo "						<td>";
	echo "							<button id='button-" . $sectionID . "-1-1' class=section-button-header style='width:26px;height:26px;margin-bottom:2px;'><i class='fa fa-plus' onclick='addbuttonclicked(1)'></i></button>";
	echo "						</td>";
	echo "						<td>";
	echo "							<select id='field-" . $sectionID . "-1-2' class=field-select style='width:250px;margin-left:4px;margin-bottom:2px;'>";
	echo "								<option value='0' selected></option>";
	foreach ($registry->costpools as $ind => $costpool) {
		echo "							<option value=" . $costpool->costpoolID . ">" . $costpool->name . "</option>";
	}
	echo "							</select>";
	echo "						</td>";
	echo "						<td>";
	echo "							<select id='field-" . $sectionID . "-1-3' class=field-select style='width:60px;margin-left:4px;margin-bottom:2px;'>";
	echo "								<option value='0' selected></option>";
	foreach ($registry->vats as $ind => $vat) {
		echo "							<option value=" . $vat->vatID . ">" . $vat->short . "</option>";
	}
	echo "							</select>";
	echo "						</td>";


	echo "						<td>";
	echo "							<input class=uitextfield  id='field-" . $sectionID . "-1-4' type='text' onblur='recalculate_" . $sectionID . "(1,1)' class=sectioninputfield style='width:60px;text-align:left;margin-left:4px;text-align:right;padding-right:6px;margin-bottom:2px;' type='text' >";
	echo "						</td>";

	echo "						<td>";
	echo "							<input class=uitextfield  id='field-" . $sectionID . "-1-5' type='text' onblur='recalculate_" . $sectionID . "(1,2)' class=sectioninputfield style='width:60px;text-align:left;margin-left:4px;;text-align:right;padding-right:6px;margin-bottom:2px;' type='text' >";
	echo "						</td>";

	echo "						<td>";
	echo "							<input class=uitextfield  id='field-" . $sectionID . "-1-6' type='text' onblur='recalculate_" . $sectionID . "(1,3)' class=sectioninputfield style='width:60px;text-align:left;margin-left:4px;;text-align:right;padding-right:6px;margin-bottom:2px;' type='text' >";
	echo "						</td>";

	echo "						<td style='width:100%;'>";
	echo "							<button id='rowdeletebutton-" . $sectionID . "-1-7' class=section-button-header style='width:26px;height:26px;margin-left:4px;margin-bottom:2px;' onclick='removebuttonclicked(1)'><i class='fa fa-close' ></i></button>";
	echo "						</td>";
	echo "					</tr>";

	echo "					<tr>";
	echo "						<td colspan=7 style='width:100%;height:6px;'>";
	echo "					</tr>";

	echo "					<tr>";
	echo "						<td class='listtable-header-nounderline'></td>";
	echo "						<td class='listtable-header-nounderline'>Yhteensä: </td>";
	echo "						<td class='listtable-header-nounderline'></td>";
	echo "						<td id='vatsumtd-" . $sectionID . "' class='listtable-header-nounderline' style='text-align:right;padding-right:4px;'>0.00</td>";
	echo "						<td id='netsumtd-" . $sectionID . "'class='listtable-header-nounderline' style='text-align:right;padding-right:4px;'>0.00</td>";
	echo "						<td id='grosssumtd-" . $sectionID . "'class='listtable-header-nounderline' style='text-align:right;padding-right:4px;'>0.00</td>";
	echo "						<td class='listtable-header-nounderline'></td>";
	echo "					</tr>";



	echo "				</table>";

	echo "			</td>";
	echo "		</tr>";

	echo "		<tr>";
	echo "			<td colspan=3 style='height:19px;'>";
	echo "		</tr>";

	echo "		<td colspan=3 style='padding-left:4px;text-align:right;'>";
	echo "			<button  class=section-button onclick='closeDialog" . $sectionID . "()'>Sulje</button>";
		
	echo "			<input type=button class='section-button' value='Tallenna' onClick='savepurchaseinvoice_" . $sectionID . "()'>";
	echo "		</td>";

	echo "	</table>";

	echo "<script>";
	echo "		function closeDialog" . $sectionID . "() {";
	echo "  		$('#sectiondialog-" . $sectionID . "').dialog('close');";
	echo "		};";
	echo "	</script>";
		
	
	echo "	<script>";

	echo "	var rowcounter" . $sectionID . " = 1;";
	echo "	var rowarray" . $sectionID . " = [ 1 ];";
	echo "	var edited" . $sectionID . " = [ [0,0,0], [0,0,0] ];";

	echo "	$('#field-" . $sectionID . "-1').change(function() {";
	echo "		var supplierID = $('#field-" . $sectionID . "-1').val();";
	echo "		console.log('supplier changed - " . getUrl('purchases/suppliers/getdefaultrows') . "&supplierID='+supplierID);";

	// Haetaan dataa jsonilla
	echo "			$.getJSON('" . getUrl('purchases/suppliers/getdefaultrows') . "&supplierID='+supplierID,'',function(data) {";
	echo "				console.log('supplier return');";
	echo "					console.log('paymentmethod - '+data.paymentmethod);";
	echo "					if (data.paymentmethod == '') {";
	echo "						$('#field-" . $sectionID . "-2').val(0);";
	echo "					} else {";
	echo "						$('#field-" . $sectionID . "-2').val(data.paymentmethod);";
	echo "					}";

	echo "					console.log('rowcount - ' + data.rows.length);";
	echo "					if (data.rows.length > 0) {";
	echo "						cleartable();";
	echo "						$.each(data.rows, function(index) {";
	echo "							rowcounter" . $sectionID . "++;";
	echo "							rowarray" . $sectionID . ".push(rowcounter" . $sectionID . ");";
	echo "							edited" . $sectionID . "[rowcounter" . $sectionID . "] = [ 0,0,0 ];";
	echo "							console.log('row - '+data.rows[index].costpoolID+' - '+data.rows[index].vatID+' - '+data.rows[index].accountID);";
	echo "							var selectedCostpoolID = data.rows[index].costpoolID;";
	echo "							var selectedVatID = data.rows[index].vatID;";
	echo "							var table = document.getElementById('purchaserowtable');";
	echo "							var tr = table.insertRow(1);";
	echo "							var row = createTableRow(rowcounter" . $sectionID . ",selectedCostpoolID,selectedVatID, tr);";
	//echo "							var row = createTableRow(rowcounter" . $sectionID . ",selectedCostpoolID,selectedVatID, tr);";
	//echo "							table.appendChild(row);";
	echo "						});";
	echo "					} else {";
	echo "						cleartable();";		// TODO: clear empty lines (ei oletuksena lisätyt)
	echo "						rowcounter" . $sectionID . "++;";
	echo "						rowarray" . $sectionID . ".push(rowcounter" . $sectionID . ");";
	echo "						edited" . $sectionID . "[rowcounter" . $sectionID . "] = [ 0,0,0 ];";
	echo "						var table = document.getElementById('purchaserowtable');";
	echo "						var tr = table.insertRow(1);";
	echo "						var row = createTableRow(rowcounter" . $sectionID . ",0,0,tr);";
	//echo "						table.appendChild(row);";
	echo "					}";
	echo "					console.log('rowcounter - '+rowcounter" . $sectionID . ");";
	//echo "					printarray();";
	echo "			}); ";

	echo "	});";
	echo "</script>";


	echo "	<script>";
	echo "		function recalculate_" . $sectionID . "(rowID, columnID) {";

	echo "			console.log('- recalculating...'+rowID);";
	//echo "			console.log('- columnID...'+columnID);";

	echo "			if (columnID == 1) {";		// ollaan editoimassa alv-riviä
	echo "				var valuestr = $('#field-" . $sectionID . "-'+rowID+'-4').val();";
	echo "				if (valuestr == '') {";
	echo "					return;";
	echo "				}";
	//echo "				console.log('alv - '+valuestr);";
	echo "				valuestr = valuestr.replace(',','.');";
	echo "				value = parseFloat(valuestr);";
	//echo "				console.log('value - '+value);";
	echo "				$('#field-" . $sectionID . "-'+rowID+'-4').val(value.toFixed(2));";
	//echo "				console.log('tttt');";
	echo "				edited" . $sectionID . "[rowID][0] = 1;";
	//echo "				console.log('tttt');";

	echo "				if (edited" . $sectionID . "[rowID][1] == 0) {";
	// Jos netto ei ole asetettu

	//echo "					console.log('netto not edited');";
	echo "					if (edited" . $sectionID . "[rowID][2] == 0) {";
	// sekä netto että brutto on asettamatta
	//echo "						console.log('brutto not edited');";
	echo "						var selectedVatID = $('#field-" . $sectionID . "-'+rowID+'-3').val();";
	echo "						console.log('vatID 222 - '+selectedVatID+'');";
	
	echo "						var netto = value / 0.24;";
	echo "						var brutto = value + netto;";
	//echo "						console.log('netto - '+netto+', brutto - '+brutto);";
	echo "						$('#field-" . $sectionID . "-'+rowID+'-5').val(netto.toFixed(2));";
	echo "						$('#field-" . $sectionID . "-'+rowID+'-6').val(brutto.toFixed(2));";

	echo "					} else {";
	//						brutto on asetettu, mutta netto ei, päivitetään netto
	echo "						var brutto = $('#field-" . $sectionID . "-'+rowID+'-6').val();";
	echo "						var netto = brutto - value;";
	echo "						$('#field-" . $sectionID . "-'+rowID+'-5').val(netto.toFixed(2));";
	echo "					}";
	echo "				} else {";
	echo "					if (edited" . $sectionID . "[rowID][2] == 0) {";		// netto on asetettu, mutta brutto ei ole
	echo "						var nettostr = $('#field-" . $sectionID . "-'+rowID+'-5').val();";
	echo "						netto = parseFloat(nettostr);";
	//echo "						console.log('netto - '+netto+', value - '+value);";
	echo "						var brutto = netto + value;";
	echo "						$('#field-" . $sectionID . "-'+rowID+'-6').val(brutto.toFixed(2));";
	echo "					} else {";			// netto on asetettu, ja brutto on asetettu, pitää tsekata täsmääkö summa
	echo "						var bruttostr = $('#field-" . $sectionID . "-'+rowID+'-6').val();";
	echo "						var nettostr = $('#field-" . $sectionID . "-'+rowID+'-5').val();";
	echo "						var netto = parseFloat(nettostr);";
	echo "						var brutto = netto + value;";
	echo "						if (brutto.toFixed(2) == bruttostr) {";
	echo "							console.log('summa täsmää');";
	echo "							$('#field-" . $sectionID . "-'+rowID+'-6').css('background-color', '#F6F6F6');";
	echo "						} else {";
	//echo "							console.log('summa ei täsmää - '+brutto.toFixed(2)+' - '+bruttostr);";
	echo "							$('#field-" . $sectionID . "-'+rowID+'-6').css('background-color', 'pink');";
	echo "						}";
	echo "					}";

	echo "				}";

	echo "			} else if (columnID == 2) {";		// ollaan editoimassa netto riviä

	echo "				var valuestr = $('#field-" . $sectionID . "-'+rowID+'-5').val();";
	echo "				if (valuestr == '') {";
	echo "					return;";
	echo "				}";
	//echo "				console.log('netto - '+valuestr);";
	echo "				valuestr = valuestr.replace(',','.');";
	echo "				value = parseFloat(valuestr);";
	//echo "				console.log('value - '+value);";
	echo "				$('#field-" . $sectionID . "-'+rowID+'-5').val(value.toFixed(2));";
	echo "				edited" . $sectionID . "[rowID][1] = 1;";

	echo "				if (edited" . $sectionID . "[rowID][0] == 0) {";
	// Jos alv ei ole asetettu

	//echo "					console.log('alv not edited');";
	echo "					if (edited" . $sectionID . "[rowID][2] == 0) {";
	// sekä alv että brutto on asettamatta
	//echo "						console.log('brutto not edited');";
	echo "						var selectedVatID = $('#field-" . $sectionID . "-'+rowID+'-3').val();";
	echo "						console.log('vatID 333 - '+selectedVatID+'');";
	
	echo "						var alv = value * 0.24;";
	echo "						var brutto = value + alv;";
	//echo "						console.log('netto - '+value+', brutto - '+brutto);";
	echo "						$('#field-" . $sectionID . "-'+rowID+'-4').val(alv.toFixed(2));";
	echo "						$('#field-" . $sectionID . "-'+rowID+'-6').val(brutto.toFixed(2));";

	echo "					} else {";
	//						brutto on asetettu, mutta alv ei, päivitetään alv
	echo "						var bruttostr = $('#field-" . $sectionID . "-'+rowID+'-6').val();";
	echo "						brutto = parseFloat(bruttostr);";
	echo "						var alv = brutto - value;";
	echo "						$('#field-" . $sectionID . "-'+rowID+'-4').val(alv.toFixed(2));";
	echo "					}";
	echo "				} else {";

	// alv on asetettu
	echo "					if (edited" . $sectionID . "[rowID][2] == 0) {";
	// alv on asetettu, mutta brutto ei ole -> päivitetään brutto
	echo "						var alvstr = $('#field-" . $sectionID . "-'+rowID+'-4').val();";
	echo "						alv = parseFloat(alvstr);";
	echo "						var brutto = alv + value;";
	echo "						$('#field-" . $sectionID . "-'+rowID+'-6').val(brutto.toFixed(2));";
	echo "					} else {";

	// alv on asetettu, ja brutto on asetettu, pitää tsekata täsmääkö summa
	echo "						var bruttostr = $('#field-" . $sectionID . "-'+rowID+'-6').val();";
	echo "						var alvstr = $('#field-" . $sectionID . "-'+rowID+'-4').val();";
	echo "						var alv = parseFloat(nettostr);";
	echo "						var brutto = alv + value;";
	echo "						if (brutto.toFixed(2) == bruttostr) {";
	//echo "							console.log('summa täsmää');";
	echo "							$('#field-" . $sectionID . "-'+rowID+'-6').css('background-color', '#F6F6F6');";
	echo "						} else {";
	//echo "							console.log('summa ei täsmää - '+brutto.toFixed(2)+' - '+bruttostr);";
	echo "							$('#field-" . $sectionID . "-'+rowID+'-6').css('background-color', 'pink');";
	echo "						}";
	echo "					}";

	echo "				}";
	echo "			} else {";							// ollaan editoimassa brutto riviä
	echo "				var bruttostr = $('#field-" . $sectionID . "-'+rowID+'-6').val();";
	echo "				if (bruttostr == '') {";
	echo "					return;";
	echo "				}";
	//echo "				console.log('bruttostr - '+bruttostr);";
	echo "				bruttostr = bruttostr.replace(',','.');";
	echo "				brutto = parseFloat(bruttostr);";
	//echo "				console.log('brutto - '+brutto);";
	echo "				$('#field-" . $sectionID . "-'+rowID+'-6').val(brutto.toFixed(2));";
	echo "				edited" . $sectionID . "[rowID][2] = 1;";

	echo "				if (edited" . $sectionID . "[rowID][0] == 0) {";
	// Jos alv ei ole asetettu

	//echo "					console.log('alv not edited');";
	echo "					if (edited" . $sectionID . "[rowID][1] == 0) {";
	// sekä alv että netto on asettamatta
	//echo "						console.log('netto not edited');";
	echo "						var selectedVatID = $('#field-" . $sectionID . "-'+rowID+'-3').val();";
	echo "						console.log('vatID 444 - '+selectedVatID+'');";
	echo "						var percent = getVatPercent_" . $sectionID . "(selectedVatID);";
	echo "						console.log('vatID 444 - '+selectedVatID+' - '+percent);";
	echo "						var netto = brutto / (1 + percent);";
	echo "						var alv = brutto - netto;";

	//echo "						console.log('alv - '+alv+', brutto - '+brutto);";
	echo "						$('#field-" . $sectionID . "-'+rowID+'-4').val(alv.toFixed(2));";
	echo "						$('#field-" . $sectionID . "-'+rowID+'-5').val(netto.toFixed(2));";

	echo "					} else {";
	//						netto on asetettu, mutta alv ei, päivitetään alv
	//echo "						console.log('netto asetettu, brutto editoitu -> päivitetään alv');";
	echo "						var nettostr = $('#field-" . $sectionID . "-'+rowID+'-5').val();";
	echo "						netto = parseFloat(nettostr);";
	echo "						var alv = brutto - netto;";
	echo "						$('#field-" . $sectionID . "-'+rowID+'-4').val(alv.toFixed(2));";
	echo "					}";
	echo "				} else {";

	// alv on asetettu
	echo "					if (edited" . $sectionID . "[rowID][1] == 0) {";
	// alv on asetettu, mutta netto ei ole -> päivitetään netto
	echo "						var alvstr = $('#field-" . $sectionID . "-'+rowID+'-4').val();";
	echo "						alv = parseFloat(alvstr);";
	echo "						var netto = brutto - alv;";
	echo "						$('#field-" . $sectionID . "-'+rowID+'-5').val(netto.toFixed(2));";
	echo "					} else {";

	// alv on asetettu, ja netto on asetettu, pitää tsekata täsmääkö summa
	echo "						var nettostr = $('#field-" . $sectionID . "-'+rowID+'-5').val();";
	echo "						var alvstr = $('#field-" . $sectionID . "-'+rowID+'-4').val();";
	echo "						var alv = parseFloat(nettostr);";
	echo "						var netto = parseFloat(nettostr);";
	echo "						var newbrutto = alv + netto;";
	echo "						if (brutto == newbrutto) {";
	//echo "							console.log('summa täsmää');";
	echo "							$('#field-" . $sectionID . "-'+rowID+'-6').css('background-color', '#F6F6F6');";
	echo "						} else {";
	//echo "							console.log('summa ei täsmää - '+brutto.toFixed(2)+' - '+newbrutto.toFixed(2));";
	echo "							$('#field-" . $sectionID . "-'+rowID+'-6').css('background-color', 'pink');";
	echo "						}";
	echo "					}";

	echo "				}";
	echo "			}";


	// lasketaan summat...
	echo "			var length = rowarray" . $sectionID . ".length;";
	echo "			var alvsum = 0;";
	echo "			var nettosum = 0;";
	echo "			var bruttosum = 0;";

	echo "			for(var i = 0; i < length; i++ ) {";
	echo "				var looprowID = rowarray" . $sectionID . "[i];";

	echo "				var alvstr = $('#field-" . $sectionID . "-'+looprowID+'-4').val();";
	echo "				if (alvstr == '') {";
	//echo "					console.log('alv - empty');";
	echo "				} else {";
	echo "					var alv = parseFloat(alvstr);";
	echo "					alvsum = alvsum + alv;";
	//echo "					console.log('alv - '+alv);";
	echo "				}";

	echo "				var nettostr = $('#field-" . $sectionID . "-'+looprowID+'-5').val();";
	echo "				if (nettostr == '') {";
	//echo "					console.log('nettostr - empty');";
	echo "				} else {";
	echo "					var netto = parseFloat(nettostr);";
	echo "					nettosum = nettosum + netto;";
	//echo "					console.log('netto - '+netto);";
	echo "				}";


	echo "				var bruttostr = $('#field-" . $sectionID . "-'+looprowID+'-6').val();";
	echo "				if (bruttostr == '') {";
	//echo "					console.log('bruttostr - empty');";
	echo "				} else {";
	echo "					var brutto = parseFloat(bruttostr);";
	echo "					bruttosum = bruttosum + brutto;";
	//echo "					console.log('brutto - '+brutto);";
	echo "				}";
	echo "			}";

	//echo "			console.log('alvsum - '+alvsum);";
	//echo "			console.log('nettosum - '+nettosum);";
	//echo "			console.log('bruttosum - '+bruttosum);";

	echo "			$('#vatsumtd-" . $sectionID . "').html(alvsum.toFixed(2));";
	echo "			$('#netsumtd-" . $sectionID . "').html(nettosum.toFixed(2));";
	echo "			$('#grosssumtd-" . $sectionID . "').html(bruttosum.toFixed(2));";

	echo "		}";
	echo "</script>";

	echo "	<script>";
	echo "		function getVatPercent_" . $sectionID . "(vatID) {";
	foreach ($registry->vats as $ind => $vat) {
		echo "		if (vatID == " . $vat->vatID . ") return " . $vat->percent . "/100;";
	}
	
	echo "		}";
	echo "</script>";
	

	echo "	<script>";
	echo "		function savepurchaseinvoice_" . $sectionID . "() {";

	//echo "			console.log('----------- save');";
	echo "			var params = '';";
	echo "			var dimensionID = 0;";
	
	if (count($registry->dimensions) > 0) {
		foreach($registry->dimensions as $index => $dimension) {
			echo "			dimensionID = $('#dimensionfield-" . $sectionID . "-" . $dimension->dimensionID . "').val();";
			echo "			params = params + '&dimension" . $dimension->dimensionID . "='+dimensionID;";
		}
	}
	
	echo "			var supplierID = $('#field-" . $sectionID . "-1').val();";
	echo "			if (supplierID == 0) {";
	echo "				alert('Maskun saaja puuttu');";
	echo "				return;";
	echo "			}";
	//echo "			console.log('supplierID - '+supplierID);";
	echo "			params = params + '&supplierID='+supplierID;";

	echo "			var paymentmethodID = $('#field-" . $sectionID . "-2').val();";
	echo "			if ((paymentmethodID == 0) || (paymentmethodID == '')) {";
	echo "				alert('Maksutapa puuttu');";
	echo "				return;";
	echo "			}";
	//echo "			console.log('paymentmethodID - '+paymentmethodID);";
	echo "			params = params + '&paymentmethodID='+paymentmethodID;";

	echo "			var value = $('#field-" . $sectionID . "-3').val();";
	echo "			var purchasedate = value.substring(6,10)+'-'+value.substring(3,5)+'-'+value.substring(0,2);";
	echo "			params = params + '&purchasedate='+purchasedate;";

	echo "			var value = $('#field-" . $sectionID . "-4').val();";
	echo "			var duedate = value.substring(6,10)+'-'+value.substring(3,5)+'-'+value.substring(0,2);";
	echo "			params = params + '&duedate='+duedate;";

	echo "			var length = rowarray" . $sectionID . ".length;";
	echo "			var costpoolID = '';";
	echo "			var vatID = '';";
	echo "			var alvstr = '';";
	echo "			var nettostr = '';";
	echo "			var bruttostr = '';";
	echo "			var rowID = 0;";
	echo "			var counter = 0;";
	//echo "			console.log('length - '+length);";

	echo "			for(var index = 0; index < length; index++ ) {";

	echo "				rowID = rowarray" . $sectionID . "[index];";
	//echo "				console.log('- loopID - '+rowID+' - '+index);";
	echo "				costpoolID = $('#field-" . $sectionID . "-'+rowID+'-2').val();";
	//echo "				console.log('costpoolID -'+costpoolID);";
	echo "				if (costpoolID == 0) {";
	echo "					alert('Kustanuspaikka puuttu');";
	//echo "					return;";
	echo "				}";

	echo "				vatID = $('#field-" . $sectionID . "-'+rowID+'-3').val();";
	echo "				if (vatID == 0) {";
	echo "					alert('ALV-prosentti puuttu');";
	//echo "					return;";
	echo "				}";
	//echo "				console.log('vatID - '+vatID);";

	echo "				alvstr = $('#field-" . $sectionID . "-'+rowID+'-4').val();";
	echo "				nettostr = $('#field-" . $sectionID . "-'+rowID+'-5').val();";
	echo "				bruttostr = $('#field-" . $sectionID . "-'+rowID+'-6').val();";

	echo "				if ((alvstr == '') && (nettostr == '') && (bruttostr == '')) {";
	//echo "					console.log('all empty');";
	echo "				} else {";
	echo "					params = params + '&costpoolID-'+counter+'='+costpoolID;";
	echo "					params = params + '&vatID-'+counter+'='+vatID;";
	echo "					params = params + '&vat-'+counter+'='+alvstr;";
	echo "					params = params + '&net-'+counter+'='+nettostr;";
	echo "					params = params + '&gross-'+counter+'='+bruttostr;";
	echo "					counter++;";
	echo "				}";
	echo "			}";
	echo "			if (counter == 0) {";
	echo "				alert('Ei yhtään ostolaskuriviä');";
	echo "			}";
	echo "			params = params + '&count='+counter;";
	echo "			console.log('params - '+params);";
	echo "			alert('implementation disabled');";
	//echo "			window.location = '".getUrl('accounting/purchases/insertpurchasewithrows')."' + params;";
	echo "		}";
	echo "</script>";


	echo "	<script>";
	echo "		function createTableRow(rowID, costpoolID = 0,vatID = 0, tr) {";

	echo "			console.log('rowwi - '+rowID);";
	echo "			var option;";
	echo "			var button;";
	echo "			var td;";
	echo "			var select;";
	echo "			var buttoncontent;";
	echo "			var selectedID;";
	echo "			var selectedVatID;";

	echo "			tr.id = 'purchaserow-" . $sectionID . "-'+rowID;";
	echo "			td = document.createElement('td');";
	echo "			button = document.createElement('button');";
	echo "			button.className = 'section-button-header';";
	echo "			button.style.height = '26px';";
	echo "			button.style.width = '26px';";
	echo "			button.style.marginBottom = '3px';";
	echo "			buttoncontent = document.createElement('i');";
	echo "			buttoncontent.className = 'fa fa-plus';";
	echo "			button.setAttribute('onClick', 'addbuttonclicked(\''+rowID+'\')');";
	echo "			button.appendChild(buttoncontent);";
	echo "			td.appendChild(button);";
	echo "			tr.appendChild(td);";

	echo "			td = document.createElement('td');";
	echo "			select = document.createElement('select');";
	echo "			select.className = 'field-select';";
	echo "			select.id = 'field-" . $sectionID . "-'+rowID+'-2';";
	echo "			select.style.width = '250px';";
	echo "			select.style.marginLeft = '4px';";
	echo "			select.style.marginBottom = '3px';";
	echo "			option = document.createElement('option');";
	echo "			option.value = 0;";
	echo "			option.textContent = '';";
	echo "			if (costpoolID == 0) option.selected = 'selected';";
	echo "			select.appendChild(option);";
	foreach($registry->costpools as $costpoolID => $costpool) {
		echo "		option = document.createElement('option');";
		echo "		option.value = " . $costpool->costpoolID . ";";
		echo "		option.textContent = '" . $costpool->name . "';";
		echo "		if (costpoolID == " . $costpool->costpoolID . ") option.selected = 'selected';";
		echo "		select.appendChild(option);";
	}
	echo "			td.appendChild(select);";
	echo "			tr.appendChild(td);";

	echo "			td = document.createElement('td');";
	echo "			select = document.createElement('select');";
	echo "			select.className = 'field-select';";
	echo "			select.id = 'field-" . $sectionID . "-'+rowID+'-3';";
	echo "			select.style.width = '60px';";
	echo "			select.style.marginLeft = '4px';";
	echo "			select.style.marginBottom = '3px';";
	echo "			option = document.createElement('option');";
	echo "			option.value = 0;";
	echo "			option.textContent = '';";
	echo "			if (vatID == 0) option.selected = 'selected';";
	echo "			select.appendChild(option);";
	foreach($registry->vats as $vatID => $vat) {
		echo "		option = document.createElement('option');";
		echo "		option.value = " . $vat->vatID . ";";
		echo "		option.textContent = '" . $vat->short . "';";
		echo "		if (vatID == " . $vat->vatID . ") option.selected = 'selected';";
		echo "		select.appendChild(option);";
	}
	echo "			td.appendChild(select);";
	echo "			tr.appendChild(td);";

	echo "			td = document.createElement('td');";
	echo "			textfield = document.createElement('input');";
	echo "			textfield.id = 'field-" . $sectionID . "-'+rowID+'-4';";
	echo "			textfield.setAttribute('onblur', 'recalculate_" . $sectionID . "('+rowID+',1)');";
	echo "			textfield.type = 'text';";
	echo "			textfield.className = 'uitextfield';";
	echo "			textfield.style.width = '60px';";
	echo "			textfield.style.marginLeft = '4px';";
	echo "			textfield.style.marginBottom = '3px';";
	echo "			textfield.style.textAlign = 'right';";
	echo "			textfield.style.paddingRight = '6px';";
	echo "			td.appendChild(textfield);";
	echo "			tr.appendChild(td);";

	echo "			td = document.createElement('td');";
	echo "			textfield = document.createElement('input');";
	echo "			textfield.id = 'field-" . $sectionID . "-'+rowID+'-5';";
	echo "			textfield.setAttribute('onblur', 'recalculate_" . $sectionID . "('+rowID+',2)');";
	echo "			textfield.type = 'text';";
	echo "			textfield.className = 'uitextfield';";
	echo "			textfield.style.width = '60px';";
	echo "			textfield.style.marginLeft = '4px';";
	echo "			textfield.style.marginBottom = '3px';";
	echo "			textfield.style.textAlign = 'right';";
	echo "			textfield.style.paddingRight = '6px';";
	echo "			td.appendChild(textfield);";
	echo "			tr.appendChild(td);";

	echo "			td = document.createElement('td');";
	echo "			textfield = document.createElement('input');";
	echo "			textfield.id = 'field-" . $sectionID . "-'+rowID+'-6';";
	echo "			textfield.setAttribute('onblur', 'recalculate_" . $sectionID . "('+rowID+',3)');";
	echo "			textfield.type = 'text';";
	echo "			textfield.className = 'uitextfield';";
	echo "			textfield.style.width = '60px';";
	echo "			textfield.style.marginLeft = '4px';";
	echo "			textfield.style.marginBottom = '3px';";
	echo "			textfield.style.textAlign = 'right';";
	echo "			textfield.style.paddingRight = '6px';";
	echo "			td.appendChild(textfield);";
	echo "			tr.appendChild(td);";

	echo "			td = document.createElement('td');";
	echo "			button = document.createElement('button');";
	echo "			button.className = 'section-button-header';";
	echo "			button.style.height = '26px';";
	echo "			button.style.width = '26px';";
	echo "			button.style.marginLeft = '4px';";
	echo "			button.style.marginBottom = '3px';";
	echo "			button.setAttribute('onClick', 'removebuttonclicked(\''+rowID+'\')');";
	echo "			buttoncontent = document.createElement('i');";
	echo "			buttoncontent.className = 'fa fa-close';";
	echo "			button.appendChild(buttoncontent);";
	echo "			td.appendChild(button);";
	echo "			tr.appendChild(td);";

	echo "			return tr;";
	echo "		}";
	echo "	</script>";


	echo "	<script>";
	echo "		function cleartable() {";
	echo "			var table = document.getElementById('purchaserowtable');";
	echo "			var deleteindex = 0;";
	echo "			var row;";
	echo "			for (var i = table.rows.length-2; i > 0; i--)  {";
	echo "				row = table.rows[i];";
	echo "				console.log('clear - '+row.id+' - '+i);";
	echo "				console.log('remove - '+i);";
	echo "				table.deleteRow(i);";
	echo "			}";
	echo "			rowarray" . $sectionID . " = [];";
	echo "		}";
	echo "	</script>";


	echo "	<script>";
	echo "		function removebuttonclicked(rowID) {";
	echo "			var table = document.getElementById('purchaserowtable');";
	echo "			var deleteindex = 0;";
	echo "			for (var i = 0, row; row = table.rows[i]; i++)  {";
	echo "				if (row.id == 'purchaserow-" . $sectionID . "-'+rowID) {";
	echo "					deleteindex = i;";
	echo "				}";
	echo "			}";
	echo "			if (deleteindex > 0) {";
	echo "				table.deleteRow(deleteindex);";
	echo "			}";
	echo "			removefromarray(rowID);";
	echo "		}";
	echo "	</script>";


	echo "	<script>";
	echo "		function addbuttonclicked(rowID) {";
	echo "			console.log('addbuttonclicked - '+rowID);";
	echo "			var table = document.getElementById('purchaserowtable');";
	echo "			var addindex = 0;";
	echo "			for (var i = 0, row; row = table.rows[i]; i++)  {";
	echo "				if (row.id == 'purchaserow-" . $sectionID . "-'+rowID) {";
	echo "					addindex = i;";
	echo "				}";
	echo "			}";
	echo "			addindex++;";
	echo "			if (addindex == table.rows.length) {";
	echo "				console.log('lastrow');";
	echo "			}";
	echo "			rowcounter" . $sectionID . "++;";
	echo "			rowarray" . $sectionID . ".push(rowcounter" . $sectionID . ");";
	echo "			edited" . $sectionID . "[rowcounter" . $sectionID . "] = [ 0,0,0 ];";
	echo "			var tr = table.insertRow(addindex);";
	echo "			var row = createTableRow(rowcounter" . $sectionID . ",0,0, tr);";
	echo "			console.log('addbuttonclicked finish - '+rowID);";
	echo "		}";
	echo "	</script>";


	echo "	<script>";
	echo "		function removefromarray(rowID) {";
	echo "			console.log('removearray - '+rowID);";
	echo "			console.log(rowarray" . $sectionID . ");";
	echo "			for(var i = rowarray" . $sectionID . ".length - 1; i >= 0; i--) {";
	echo "				console.log(rowarray" . $sectionID . "[i] + ' vs. '+rowID);";
	echo "				if(rowarray" . $sectionID . "[i] == rowID) {";
	echo "					console.log('remmmi');";
	echo "					rowarray" . $sectionID . ".splice(i, 1);";
	echo "				}";
	echo "			}";
	echo "			console.log(rowarray" . $sectionID . ");";
	echo "		}";
	echo "	</script>";
}




$table = new UITableSection("Ostolaskut","800px");
$table->setOpen(true);
$table->setFramesVisible(false);
$table->setShowSumRow(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Uusi ostolasku');
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"accounting/purchases/showpurchase","purchaseID");

$column = new UISortColumn("#", "purchaseID");
$table->addColumn($column);

$column = new UISortColumn("Laskupäivä", "purchasedate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);


if (count($this->registry->dimensions) > 0) {
	foreach($this->registry->dimensions as $index => $dimension) {
		$column = new UISelectColumn($dimension->abbreviation, "name", "dimension" . $dimension->dimensionID, $registry->dimensionvalues[$dimension->dimensionID]);
		$column->setUndefinedString("-");
		$table->addColumn($column);
	}
}


$column = new UISelectColumn("Toimittaja", "name", "supplierID", $registry->suppliers);
$table->addColumn($column);

$column = new UISortColumn("Eräpäivä", "duedate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);


$column = new UISortColumn("ALV", "alvamount");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$column = new UISortColumn("Netto", "netamount");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$column = new UISortColumn("Brutto", "grossamount");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$column = new UISelectColumn("Tila", null, "state", $registry->purchasestates);
$table->addColumn($column);

$table->setData($registry->invoices);
$table->show();

?>
