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
	
	
	$insertsection = new UISection('Ostolaskun lisäys 2','605px');
	$insertsection->setDialog(true);
	$_SESSION['global_sectionID'] = $insertsection->getID();
	
	$insertsection->setMode(UIComponent::MODE_INSERT);
	$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/purchases/insertpurchase');
	
	$insertsection->setCustomContent('purchasepanelDiv');
	$insertsection->show();
	
	
	function purchasepanelDiv() {
	
		global $registry;
		$sectionID = $_SESSION['global_sectionID'];
	
	
	
		echo "	<table style='width:500px;padding-top:10px;'>";
	
		/*
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
		*/

		echo "		<tr>";
		echo "			<td class=field-text style='width:150px;'>Myyjä/toimittaja</td>";
		echo "			<td style='width:250px;'>";
		echo "				<div id='supplierdiv-" . $sectionID . "' class='modal fade' tabindex='-1' role='dialog' aria-hidden='true'>";
		echo "					<select id=supplierfield-" . $sectionID . " class=field-select style='width:200px;'>";
		echo "						<option value='0' selected> </option>";
		foreach ($registry->suppliers as $ind => $supplier) {
			echo "					<option value=" . $supplier->supplierID . ">" . $supplier->name . "</option>";
		}
		echo "					</select>";
		echo "				</div>";
		echo " 			</td>";
		echo "			<td id='messagefield-" . $sectionID . "-1 style='width:100px;'></td>";
		echo "		</tr>";
		
		echo "	<script>";
		echo "		var createCounter = 0;";
		
		echo "		$(document).ready(function() {";
		echo "			console.log('select2 initialized');";
		
		echo "			$('#supplierfield-" . $sectionID . "').select2({";
		echo "			  	tags: true,";
		echo "			  	createTag: function(params) {";
		echo "			  		var term = $.trim(params.term);";
		echo "					if (term === '') return null;";
		echo "					createCounter--;";
		echo "			  		return { id: createCounter, text: term, newTag:true };";
		echo "			  	},";
		echo "				dropdownParent: $('#supplierdiv-" . $sectionID . "')";
		echo "			});";
		
		echo "			$('#supplierfield-" . $sectionID . "').on('select2:select', function (e) {";
		echo "				var selectedID = $('#supplierfield-" . $sectionID . "').val();";
		echo "				if (selectedID < 0) {";
		echo "					console.log('new value, no payment time fetch');";
		echo "				} else {";
		
		echo "				}";
		echo "			});";
		
		echo "		});";
		echo "	</script>";
		
		
		
		echo "		<tr>";
		echo "			<td class=field-text style='width:150px;'>Summa</td>";
		echo "			<td style='width:250px;'>";
		echo "				<input type='text' id=amountfield-" . $sectionID . " class=field-select style='width:200px;'>";
		echo " 			</td>";
		echo "			<td id='messagefield-" . $sectionID . "-1 style='width:100px;'></td>";
		echo "		</tr>";
		
		
		
	
		echo "		<tr>";
		echo "			<td class=field-text>Ostopäivä</td>";
		echo "			<td>";
		echo "				<div>";
		echo "					<table style='padding-left:0px;' cellpadding=0 cellspacing=0>";
		echo "						<tr>";
		echo "							<td>";
		echo "								<input id=purchasedatefield-" . $sectionID . " class=uitextfield tabindex=3 style='text-align:center;padding-left:0px;padding-right:0px;width:100px;' type='text'														";
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
		echo "		$('#purchasedatefield-" . $sectionID . "').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
		echo "	</script>";
		
		
		
		echo "		<tr>";
		echo "			<td class=field-text>Ostotapa</td>";
		echo "			<td>";
		echo "				<select id=purchasetype-" . $sectionID . " class=field-select style='width:200px;'>";
		echo "					<option value='0' selected></option>";
		foreach($registry->paymentmethods as $index => $paymentmethod) {
			echo "					<option value='" . $index . "'>" . $paymentmethod->name . "</option>";
		}
		echo "				</select>";
		echo " 			</td>";
		echo "			<td></td>";
		echo "		</tr>";

		
		
		/*
		echo "		<tr id='paymenttypecash-" . $sectionID . "' style='display:none'>";
		echo "			<td class=field-text>Maksutapa</td>";
		echo "			<td>";
		echo "				<select id=paymentcashfield-" . $sectionID . " class=field-select style='width:200px;'>";
		echo "					<option value='0' selected></option>";
		echo "					<option value='1'>Firman käteiskassa</option>";
		echo "					<option value='2'>Henkilön oma korttimaksu</option>";
		echo "					<option value='3'>Henkilön oma käteismaksu</option>";
		//echo "					<option value='1'>Saapunut lasku</option>";
		echo "				</select>";
		echo " 			</td>";
		echo "			<td></td>";
		echo "		</tr>";
		
		echo "		<tr id='paymenttypecard-" . $sectionID . "' style='display:none'>";
		echo "			<td class=field-text>Maksukortti</td>";
		echo "			<td>";
		echo "				<select id=paymentcardfield-" . $sectionID . " class=field-select style='width:200px;'>";
		echo "					<option value='0' selected></option>";
		foreach($registry->paymentcards as $index => $card) {
			echo "					<option value='" . $card->cardID . "'>" . $card->name . "</option>";
		}		
		echo "					<option value='-1'>Henkilön oma kortti</option>";
		echo "				</select>";
		echo " 			</td>";
		echo "			<td></td>";
		echo "		</tr>";
		
		echo "		<tr id='paymenttypenet-" . $sectionID . "' style='display:none'>";
		echo "			<td class=field-text>Pankkitili</td>";
		echo "			<td>";
		echo "				<select id=bankaccountfield-" . $sectionID . " class=field-select style='width:200px;'>";
		echo "					<option value='0' selected></option>";
		foreach ($registry->bankaccounts as $index => $bankaccount) {
			echo "				<option value=" . $bankaccount->bankaccountID . ">" . $bankaccount->name . "</option>";
		}
		echo "					<option value='x'>Henkilön oma pankkitili</option>";
		echo "				</select>";
		echo " 			</td>";
		echo "			<td></td>";
		echo "		</tr>";
		
		
		echo "		<tr id='personsection-" . $sectionID . "' style='display:none'>";
		echo "			<td class=field-text>Maksaja</td>";
		echo "			<td>";
		echo "				<select id=personselect-" . $sectionID . " class=field-select style='width:200px;'>";
		echo "					<option value='0' selected></option>";
		foreach ($registry->persons as $index => $person) {
			echo "				<option value=" . $person->workerID . ">" . $person->lastname . " " . $person->firstname . "</option>";
		}
		echo "				</select>";
		echo " 			</td>";
		echo "			<td></td>";
		echo "		</tr>";
		
		
		echo "		<tr id='paybacksection-" . $sectionID . "' style='display:none'>";
		echo "			<td class=field-text>Takaisinmaksu</td>";
		echo "			<td>";
		echo "				<select id=paybackselect-" . $sectionID . " class=field-select style='width:200px;'>";
		echo "					<option value='0' selected></option>";
		foreach ($registry->paybacktypes as $index => $value) {
			echo "				<option value=" . $index . ">" . $value . "</option>";
		}
		echo "				</select>";
		echo " 			</td>";
		echo "			<td></td>";
		echo "		</tr>";
		*/

		echo "		<tr id='paymentdate-" . $sectionID . "' style='display:none'>";
		echo "			<td id='paymentdatetitle-" . $sectionID . "' class=field-text>Eräpäivä</td>";
		echo "			<td>";
		echo "				<div>";
		echo "					<table style='padding-left:0px;' cellpadding=0 cellspacing=0>";
		echo "						<tr>";
		echo "							<td>";
		echo "								<input id=paymentdatefield-" . $sectionID . " class=uitextfield tabindex=3 style='text-align:center;padding-left:0px;padding-right:0px;width:100px;' type='text'														";
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
		echo "		$('#paymentdatefield-" . $sectionID . "').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
		echo "	</script>";
		
			
		echo "		<tr>";
		echo "			<td colspan=3 style='height:19px;'>";
		echo "		</tr>";
	
		echo "		<td colspan=3 style='padding-left:4px;text-align:right;'>";
		echo "			<button  class=section-button onclick='closeDialog" . $sectionID . "()'>Sulje</button>";
	
		echo "			<button id='invoiceaddi-" . $sectionID . "' type=button class='section-button' value='Lisää' onClick='savepurchaseinvoice_" . $sectionID . "()' disabled='disabled'>Lisää</button>";
		echo "		</td>";
	
		echo "	</table>";
	
		echo "<script>";
		echo "		function closeDialog" . $sectionID . "() {";
		echo "  		$('#sectiondialog-" . $sectionID . "').dialog('close');";
		echo "		};";
		echo "	</script>";
	
		
		
		echo "	<script>";
		echo "	$('#field-" . $sectionID . "-1').change(function() {";
		//echo "		console.log('supplier changed - " . getUrl('purchases/suppliers/getdefaultrows') . "&supplierID='+supplierID);";
		echo "		console.log('supplier changed');";
		echo "	});";
		echo "</script>";
	
		echo "	<script>";
		echo "	$('#purchasedatefield-" . $sectionID . "').change(function() {";
		echo "		console.log('date changed');";
		echo "		var date = $('#purchasedatefield-" . $sectionID . "').val();";
		echo "		console.log('date - '+date);";
		echo "		$('#paymentdatefield-" . $sectionID . "').val(date);";
		echo "	});";
		echo "</script>";
		
		echo "	<script>";
		echo "	$('#purchasetype-" . $sectionID . "').change(function() {";
		//echo "		var supplierID = $('#field-" . $sectionID . "-1').val();";
		//echo "		console.log('supplier changed - " . getUrl('purchases/suppliers/getdefaultrows') . "&supplierID='+supplierID);";
		//echo "		console.log('ostotapa valittu');";
		echo "		var buytype = $('#purchasetype-" . $sectionID . "').val();";
		
		echo "		$('#paymenttypecash-" . $sectionID . "').hide();";
		echo "		$('#paymentcashfield-" . $sectionID . "').val(0);";
		echo "		$('#paymenttypecard-" . $sectionID . "').hide();";
		echo "		$('#paymentcardfield-" . $sectionID . "').val(0);";
		echo "		$('#paymenttypenet-" . $sectionID . "').hide();";
		echo "		$('#bankaccountfield-" . $sectionID . "').val(0);";
		echo "		$('#personsection-" . $sectionID . "').hide();";
		echo "		$('#personselect-" . $sectionID . "').val(0);";
		echo "		$('#paybacksection-" . $sectionID . "').hide();";
		echo "		$('#paybackselect-" . $sectionID . "').val(0);";
		echo "		$('#paymentdate-" . $sectionID . "').hide();";
		echo "		activateButton(0);";
		
		echo "		if (buytype == '0') {";
		echo "			console.log('ostotapa valittu - none');";
		echo "			activateButton(0);";
		echo "		}";	
		echo "		if (buytype == '" . Collections::PURCHASETYPE_CASHRECEIPT . "') {";
		echo "			console.log('ostotapa valittu - käteiskuitti');";
		echo "			$('#paymenttypecash-" . $sectionID . "').show();";
		echo "		}";
		echo "		if (buytype == '" . Collections::PURCHASETYPE_CARD . "') {";
		echo "			console.log('ostotapa valittu - korttimaksu');";
		echo "			$('#paymenttypecard-" . $sectionID . "').show();";
		echo "		}";
		echo "		if (buytype == '" . Collections::PURCHASETYPE_BANKACCOUNT . "') {";
		echo "			console.log('ostotapa valittu - nettimaksu');";
		echo "			$('#paymenttypenet-" . $sectionID . "').show();";
		echo "		}";
		echo "		if (buytype == '" . Collections::PURCHASETYPE_INVOICE . "') {";
		echo "			console.log('ostotapa valittu - paperilasku');";
		echo "			$('#paymentdatetitle-" . $sectionID . "').html('Eräpäivä');";
		echo "			var date = $('#purchasedatefield-" . $sectionID . "').val();";
		echo "			console.log('date - '+date);";
		echo "			$('#paymentdatefield-" . $sectionID . "').val(date);";
		echo "			$('#paymentdate-" . $sectionID . "').show();";
		echo "			activateButton(1);";
		echo "		}";
		echo "		if (buytype == '" . Collections::PURCHASETYPE_PERSON . "') {";
		echo "			console.log('ostotapa valittu - henkilön maksu');";
		echo "			$('#personselect-" . $sectionID . "').val(0);";
		echo "			$('#personsection-" . $sectionID . "').show();";
		echo "		}";
		
		echo "	});";
		echo "</script>";
		
		/*
		echo "	<script>";
		echo "	$('#paymenttypecash-" . $sectionID . "').change(function() {";
		echo "		var cashtype = $('#paymentcashfield-" . $sectionID . "').val();";
		echo "		console.log('käteismaksu changed - '+cashtype);";
		
		echo "		$('#personsection-" . $sectionID . "').hide();";
		echo "		$('#personselect-" . $sectionID . "').val(0);";
		echo "		$('#paybacksection-" . $sectionID . "').hide();";
		echo "		$('#paybackselect-" . $sectionID . "').val(0);";
		echo "		$('#paymentdate-" . $sectionID . "').hide();";
		
		echo "		if (cashtype == '0') {";
		echo "			console.log('käteismaksu - none');";
		echo "			activateButton(0);";
		echo "		}";
		echo "		if (cashtype == '1') {";
		echo "			console.log('firman käteiskassa');";
		echo "			activateButton(1);";
		echo "		}";
		echo "		if (cashtype == '2') {";
		echo "			console.log('oma luottokorttimaksu');";
		echo "			$('#personselect-" . $sectionID . "').val(0);";
		echo "			$('#personsection-" . $sectionID . "').show();";
		echo "			activateButton(0);";
		echo "		}";
		echo "		if (cashtype == '3') {";
		echo "			console.log('oma käteismaksu');";
		echo "			$('#personselect-" . $sectionID . "').val(0);";
		echo "			$('#personsection-" . $sectionID . "').show();";
		echo "			activateButton(0);";
		echo "		}";
		
		echo "	});";
		echo "</script>";
		*/

		/*
		echo "<script>";
		echo "	function isOwnCard(cardID) {";
		foreach($registry->paymentcards as $index => $card) {
			echo "	if(cardID == " . $card->cardID . ") {";
			if ($card->bankaccountID == 0) {
				echo "return 1;";
			} else {
				echo "return 0;";
			}
			echo "	}";
		}
		echo "	}";
		echo "</script>";
		*/
		
		/*
		echo "	<script>";
		echo "	$('#paymentcardfield-" . $sectionID . "').change(function() {";
		echo "		var card = $('#paymentcardfield-" . $sectionID . "').val();";
		echo "		console.log('paymentcard changed - '+card);";
		
		echo "		$('#personselect-" . $sectionID . "').val(0);";
		echo "		$('#personsection-" . $sectionID . "').hide();";
		echo "		$('#paybackselect-" . $sectionID . "').val(0);";
		echo "		$('#paybacksection-" . $sectionID . "').hide();";
		echo "		$('#paymentdate-" . $sectionID . "').hide();";
		
		echo "		if (card == '0') {";
		echo "			console.log('korttimaksu - none');";
		echo "			activateButton(0);";
		echo "			return;";
		echo "		}";
		
		echo "		if (card == '-1') {";
		echo "			console.log('korttimaksu - oma kortti');";
		echo "			$('#personsection-" . $sectionID . "').show();";
		echo "			activateButton(0);";
		echo "			return;";
		echo "		}";
		
		echo "		var own = isOwnCard(card);";
		echo "		if (own == 1) {";
		echo "			console.log('maksukortti valittu - '+own);";
		echo "			$('#paybacksection-" . $sectionID . "').show();";
		echo "			activateButton(0);";
		echo "		} else {";
		echo "			activateButton(1);";
		echo "		}";
		echo "	});";
		echo "</script>";
		*/
		
		/*
		echo "	<script>";
		echo "	$('#personselect-" . $sectionID . "').change(function() {";
		echo "		var person = $('#personselect-" . $sectionID . "').val();";
		echo "		console.log('henkilö changed - '+person);";
		echo "		if (person == '0') {";
		echo "			console.log('käteismaksu - none');";
		echo "			$('#paybacksection-" . $sectionID . "').hide();";
		echo "			$('#paymentdate-" . $sectionID . "').hide();";
		echo "			$('#paybackselect-" . $sectionID . "').val(0);";
		echo "			activateButton(0);";
		echo "		} else {";
		echo "			console.log('henkilövalittu');";
		echo "			$('#paybacksection-" . $sectionID . "').show();";
		echo "			activateButton(0);";
		echo "		}";
		echo "	});";
		echo "</script>";
		*/
		
		/*
		echo "	<script>";
		echo "	$('#paymenttypenet-" . $sectionID . "').change(function() {";
		echo "		var bankaccount = $('#bankaccountfield-" . $sectionID . "').val();";
		echo "		console.log('bankaccount changed - '+bankaccount);";
		
		echo "		$('#personsection-" . $sectionID . "').hide();";
		echo "		$('#paybacksection-" . $sectionID . "').hide();";
		echo "		$('#paymentdate-" . $sectionID . "').hide();";
		
		echo "		if (bankaccount == '0') {";
		echo "			console.log('paymenttypenet - none');";
		echo "			activateButton(0);";
		echo "		} else {";
		echo "			if (bankaccount == 'x') {";
		echo "				console.log('paymenttypenet - omamaksu');";
		echo "				$('#personselect-" . $sectionID . "').val(0);";
		echo "				$('#personsection-" . $sectionID . "').show();";
		echo "				activateButton(0);";
		echo "			} else {";
		echo "				console.log('pankki valittu');";
		echo "				activateButton(1);";
		echo "			}";
		echo "		}";
		echo "	});";
		echo "</script>";
		*/
		
		/*
		echo "	<script>";
		echo "	$('#paybackselect-" . $sectionID . "').change(function() {";
		echo "		var paybacktype = $('#paybackselect-" . $sectionID . "').val();";
		echo "		console.log('paybacktype - '+paybacktype);";
		echo "		$('#paymentdate-" . $sectionID . "').hide();";
		echo "		if (paybacktype == '0') {";
		echo "			console.log('takaisinmaksuvalittu - 0');";
		echo "			activateButton(0);";
		echo "		}";
		echo "		if (paybacktype == '1') {";
		echo "			console.log('takaisinmaksuvalittu - 1');";
		echo "			var date = $('#purchasedatefield-" . $sectionID . "').val();";
		echo "			console.log('date - '+date);";
		echo "			$('#paymentdatefield-" . $sectionID . "').val(date);";
		echo "			$('#paymentdate-" . $sectionID . "').show();";
		echo "			activateButton(1);";
		echo "		}";
		echo "		if (paybacktype == '2') {";
		echo "			console.log('takaisinmaksuvalittu - 2');";
		echo "			activateButton(1);";
		echo "		}";
		echo "		if (paybacktype == '3') {";
		echo "			console.log('oma takaisinmaksuvalittu - 3');";
		echo "			$('#paymentdatetitle-" . $sectionID . "').html('Maksupäivä');";
		echo "			var date = $('#purchasedatefield-" . $sectionID . "').val();";
		echo "			console.log('date - '+date);";
		echo "			$('#paymentdatefield-" . $sectionID . "').val(date);";
		echo "			$('#paymentdate-" . $sectionID . "').show();";
		echo "			activateButton(1);";
		echo "		}";
		echo "		if (paybacktype == '4') {";
		echo "			console.log('takaisinmaksuvalittu - 4');";
		echo "			activateButton(1);";
		echo "		}";
		echo "	});";
		echo "</script>";
		*/
		
		
		echo "<script>";
		echo "		function activateButton(boole) {";
		
		echo "			if (boole == 1) {";
		//echo "				$('#invoiceaddi-" . $sectionID . "').value = 'jeejee';";
		//echo "				var x = $('#invoiceaddi-" . $sectionID . "').val();";
		//echo "				console.log('xx - '+x);";
		//echo "				$('#invoiceaddi-" . $sectionID . "').prop('disabled','');";
		//echo "				$('#invoiceaddi-" . $sectionID . "').attr('disabled','');";
		echo "				$('#invoiceaddi-" . $sectionID . "').removeAttr('disabled');";
		echo "				console.log('activate button 22');";
		echo "			} else {";
		echo "				console.log('disable button');";
		echo "				$('#invoiceaddi-" . $sectionID . "').attr('disabled','disabled');";
		//echo "				$('#invoiceaddi-" . $sectionID . "').attr('disabled',false);";
		echo "			}";
		echo "		}";
		echo "</script>";
		
		/*
		echo "	<script>";
		
		echo "	var rowcounter" . $sectionID . " = 1;";
		echo "	var rowarray" . $sectionID . " = [ 1 ];";
		echo "	var edited" . $sectionID . " = [ [0,0,0], [0,0,0] ];";
	
		echo "	$('#field-" . $sectionID . "-1').change(function() {";
		echo "		var supplierID = $('#field-" . $sectionID . "-1').val();";
		echo "		console.log('supplier changed - " . getUrl('purchases/suppliers/getdefaultrows') . "&supplierID='+supplierID);";
	
		echo "	});";
		echo "</script>";
		*/
	
		echo "	<script>";
		echo "		function savepurchaseinvoice_" . $sectionID . "() {";
		echo "			console.log('lisää pressed');";
		
		echo "			var params = '';";
		
		echo "			var supplierID = $('#supplierfield-" . $sectionID . "').val();";
		echo "			if (supplierID == 0) {";
		echo "				alert('Maksun saaja puuttu');";
		echo "				return;";
		echo "			}";
		echo "			params = params + '&supplierID='+supplierID;";
		
		echo "			var amount = $('#amountfield-" . $sectionID . "').val();";
		echo "			if (amount == '') {";
		echo "				alert('Maksun määrä puuttu');";
		echo "				return;";
		echo "			}";
		echo "			params = params + '&amount='+amount;";
		
		echo "			var purchasedate = $('#purchasedatefield-" . $sectionID . "').val();";
		echo "			if (purchasedate == '') {";
		echo "				alert('Ostopäivä puuttu');";
		echo "				return;";
		echo "			}";
		echo "			params = params + '&purchasedate='+purchasedate;";
		
		echo "			var purchasetype = $('#purchasetype-" . $sectionID . "').val();";
		echo "			if (purchasetype == '') {";
		echo "				alert('Ostotapa puuttu');";
		echo "				return;";
		echo "			}";
		echo "			params = params + '&purchasetype='+purchasetype;";
		
		//echo "			console.log('purchasetype const - " . Collections::PURCHASETYPE_INVOICE . "');";
		
		echo "			if (purchasetype == '" . Collections::PURCHASETYPE_INVOICE  . "') {";
		echo "				console.log('invoice');";
		echo "				var paymentdate = $('#paymentdatefield-" . $sectionID . "').val();";
		echo "				if (paymentdate == '') {";
		echo "					alert('Eräpäivä puuttu');";
		echo "					return;";
		echo "				}";
		echo "				params = params + '&paymentdate='+paymentdate;";
		echo "				console.log('params - '+params);";
		echo "				window.location = '".getUrl('accounting/purchases/insertpurchase')."' + params;";
		echo "				return;";
		echo "			}";
		
		echo "			if (purchasetype == '" . Collections::PURCHASETYPE_CARD . "') {";
		echo "				var cardID = $('#paymentcardfield-" . $sectionID . "').val();";
		echo "				if (cardID == 0) {";
		echo "					alert('maksukortti puuttuu');";
		echo "					return;";
		echo "				}";
		
		echo "				if (cardID == -1) {";		// oma maksu
		echo "					var personID = $('#personselect-" . $sectionID . "').val();";
		echo "					if (personID == 0) {";
		echo "						alert('maksaja puuttuu');";
		echo "						return;";
		echo "					}";
		echo "					var paybacktype = $('#paybackselect-" . $sectionID . "').val();";
		echo "					if (paybacktype == 0) {";
		echo "						alert('takaisinmaksu puuttuu');";
		echo "						return;";
		echo "					}";
		echo "					var paymentdate = $('#paymentdatefield-" . $sectionID . "').val();";
		echo "					if (paymentdate == '') {";
		echo "						alert('Eräpäivä puuttu');";
		echo "						return;";
		echo "					}";
		echo "					params = params + '&cardID='+cardID;";
		echo "					params = params + '&personID='+personID;";
		echo "					params = params + '&paybacktype='+paybacktype;";
		echo "					params = params + '&paymentdate='+paymentdate;";
		
		echo "					console.log('params - '+params);";
		//echo "					console.log('not implemented 2');";
		echo "					window.location = '".getUrl('accounting/purchases/insertpurchase')."' + params;";
		echo "					return;";
		echo "				} else {";
		
		echo "					var owncard = isOwnCard(cardID);";
		echo "					if (owncard == 1) {";
		echo "						console.log('owncard - '+cardID);";
		echo "						var paybacktype = $('#paybackselect-" . $sectionID . "').val();";
		echo "						if (paybacktype == 0) {";
		echo "							alert('takaisinmaksu puuttuu');";
		echo "							return;";
		echo "						}";
		echo "						var paymentdate = $('#paymentdatefield-" . $sectionID . "').val();";
		echo "						if (paymentdate == '') {";
		echo "							alert('Eräpäivä puuttu');";
		echo "							return;";
		echo "						}";
		echo "						params = params + '&cardID='+cardID;";
		echo "						params = params + '&paybacktype='+paybacktype;";
		echo "						params = params + '&paymentdate='+paymentdate;";
		
		echo "						console.log('params - '+params);";
		echo "						console.log('not implemented 3');";
		//echo "					window.location = '".getUrl('accounting/purchases/insertpurchase')."' + params;";
		//echo "					return;";
		
		echo "					} else {";
		echo "						console.log('companycard - '+cardID);";
		echo "						params = params + '&cardID='+cardID;";
		echo "						console.log('params - '+params);";
		echo "						window.location = '".getUrl('accounting/purchases/insertpurchase')."' + params;";
		echo "					}";
		echo "					return;";
		echo "				}";
		echo "			}";
		
		
		echo "			if (purchasetype == '" . Collections::PURCHASETYPE_PERSON . "') {";
		
		echo "					var personID = $('#personselect-" . $sectionID . "').val();";
		echo "					if (personID == 0) {";
		echo "						alert('maksaja puuttuu');";
		echo "						return;";
		echo "					}";
		echo "					var paybacktype = $('#paybackselect-" . $sectionID . "').val();";
		echo "					if (paybacktype == 0) {";
		echo "						alert('takaisinmaksu puuttuu');";
		echo "						return;";
		echo "					}";
		echo "					var paymentdate = $('#paymentdatefield-" . $sectionID . "').val();";
		echo "					if (paymentdate == '') {";
		echo "						alert('Eräpäivä puuttu');";
		echo "						return;";
		echo "					}";
		echo "					params = params + '&cardID='+cardID;";
		echo "					params = params + '&personID='+personID;";
		echo "					params = params + '&paybacktype='+paybacktype;";
		echo "					params = params + '&paymentdate='+paymentdate;";
		
		echo "					console.log('params - '+params);";
		echo "					window.location = '".getUrl('accounting/purchases/insertpurchase')."' + params;";
		echo "					return;";
		
		echo "			}";
		
		echo "			console.log('purchasetype - '+purchasetype);";
		echo "			console.log('params - '+params);";
		echo "			alert('purchasetype - '+purchasetype+' ei toteutettu - '+params);";
		
		echo "		}";
		echo "</script>";
	}
	
	
	
	
	$tabsection = new UITabSection("","900px");
	
	foreach($this->registry->selection as $index => $selection) {
		$tabIndex = $tabsection->addTab($selection->name, "accounting/purchases/showpurchases&selectionID=" . $selection->selectionID);
		//echo "<br>" . $selection->selectionID . " - " . $this->registry->selectionID;
		if ($selection->selectionID == $this->registry->selectionID){
			$tabsection->setActiveIndex($tabIndex);
		}
	}
	


	//$table = new UITableSection("Ostolaskut","800px");
	$table = new UITableSection("Ostolaskut","850px");
	$table->setOpen(true);
	$table->setFramesVisible(false);
	$table->showTitle(false);
	$table->setShowSumRow(true);
	
	
	
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Uusi ostolasku');
	$table->addButton($button);
	
	$table->setLineAction(UIComponent::ACTION_FORWARD,"accounting/purchases/showpurchase","purchaseID");
	
	$column = new UISortColumn("#", "purchaseID");
	$table->addColumn($column);
	
	$column = new UISortColumn("Laskupäivä", "purchasedate");
	$column->setFormatter(Column::COLUMNTYPE_DATE);
	$table->addColumn($column);
	
	/*
	if (count($this->registry->dimensions) > 0) {
		foreach($this->registry->dimensions as $index => $dimension) {
			$column = new UISelectColumn($dimension->abbreviation, "name", "dimension" . $dimension->dimensionID, $registry->dimensionvalues[$dimension->dimensionID]);
			$column->setUndefinedString("-");
			$table->addColumn($column);
		}
	}
	*/
	
	
	$column = new UISelectColumn("Toimittaja", "name", "supplierID", $registry->suppliers);
	$table->addColumn($column);
	
	//$column = new UISelectColumn("Kustannuspaikka", "name", "costpoolID", $registry->costpools);
	//$table->addColumn($column);
	
	
	$column = new UISortColumn("Eräpäivä", "duedate");
	$column->setFormatter(Column::COLUMNTYPE_DATE);
	$table->addColumn($column);
	
	
	$column = new UISortColumn("ALV", "vatamount");
	$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
	$column->setAlign(Column::ALIGN_RIGHT);
	$table->addColumn($column);
	
	//$column = new UISortColumn("Netto", "netamount");
	//$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
	//$column->setAlign(Column::ALIGN_RIGHT);
	//$table->addColumn($column);
	
	$column = new UISortColumn("Brutto", "grossamount");
	$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
	$column->setAlign(Column::ALIGN_RIGHT);
	$table->addColumn($column);
	
	

	$column = new UILinkColumn("Tosite", "file", "file","accounting/purchases/download");
	$table->addColumn($column);
	
	$column = new UISortColumn("Tila", "statestr");
	$column->setAlign(Column::ALIGN_RIGHT);
	$table->addColumn($column);
	
	//$column = new UISelectColumn("Tila", null, "state", $registry->purchasestates);
	//$table->addColumn($column);
	
	$table->setData($registry->invoices);
	//$table->show();
	
	$tabsection->setContent($table);
	$tabsection->show();
	
	
?>
