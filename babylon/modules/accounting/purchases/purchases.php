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
	
	
	$insertsection = new UISection('Ostolaskun lisäys','605px');
	$insertsection->setDialog(true);
	$_SESSION['global_sectionID'] = $insertsection->getID();
	
	//$insertsection->setMode(UIComponent::MODE_INSERT);
	//$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/purchases/insertpurchase');
	
	$insertsection->setCustomContent('purchasepanelDiv');
	$insertsection->show();
	
	
	function purchasepanelDiv() {
	
		global $registry;
		$sectionID = $_SESSION['global_sectionID'];
	
		echo "	<table style='width:570px;padding-top:10px;'>";
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
		echo "				console.log('supplier changed');";
		echo "				if (selectedID < 0) {";
		echo "					console.log('new value, no payment time fetch');";
		echo "				} else {";
		echo "					console.log('supplier - '+selectedID);";
		echo "					var url = '" . getUrl('accounting/suppliers/getsupplierJSON') . "&supplierID='+selectedID;";
		echo "					console.log(url);";
		echo "					$.getJSON('" . getUrl('accounting/suppliers/getsupplierJSON') . "&supplierID='+selectedID,'',function(data) {";
		echo "						console.log('data - '+data.name);";
		
		echo "						if (data.paymenttime > 0) {";
		echo "							console.log('paymenttime - '+data.paymenttime);";
		echo "							console.log('paymentmethod - '+data.paymentmethodID);";
		echo "						} else {";
		echo "							console.log('paymenttime is zero');";
		echo "							console.log('paymentmethod - '+data.paymentmethodID);";
		echo "						}";
		
		echo "		if (data.paymentmethodID == 0) {";
		echo "	 		var purchasetypefieldID = '#purchasetype-" . $sectionID . "';";
		echo "		 	$(purchasetypefieldID).val(data.paymentmethodID);";
		echo "			$('#paymentdate-" . $sectionID . "').hide();";
		
		echo "	 		var datefieldID = '#purchasedatefield-" . $sectionID . "';";
		echo "	 		var duedatefieldID = '#duedatefield-" . $sectionID . "';";
		echo "	 		var date = $(datefieldID).val();";
		echo "		 	$(duedatefieldID).val(date);";
		
		echo "		}";
		
		
		foreach($registry->paymentmethods as $index => $paymentmethod) {
			echo "		if (data.paymentmethodID == " . $paymentmethod->paymentmethodID . ") {";
			if ($paymentmethod->duedateusage == Collections::DUEDATEUSAGE_NODUEDATE) {
				echo "			console.log(' - no due date');";
				echo "			$('#paymentdate-" . $sectionID . "').hide();";
				echo "	 		var purchasetypefieldID = '#purchasetype-" . $sectionID . "';";
				echo "		 	$(purchasetypefieldID).val(data.paymentmethodID);";
			}
			if ($paymentmethod->duedateusage == Collections::DUEDATEUSAGE_CURRENTDATE) {
				echo "			console.log(' - current due date');";
				echo "			$('#paymentdate-" . $sectionID . "').show();";
				echo "	 		var purchasetypefieldID = '#purchasetype-" . $sectionID . "';";
				echo "		 	$(purchasetypefieldID).val(data.paymentmethodID);";
			}
			if ($paymentmethod->duedateusage == Collections::DUEDATEUSAGE_FROMSUPPLIER) {
				echo "			console.log(' - supplier due date');";
				echo "			$('#paymentdate-" . $sectionID . "').show();";
				
				echo "	 		var datefieldID = '#purchasedatefield-" . $sectionID . "';";
				echo "	 		var duedatefieldID = '#duedatefield-" . $sectionID . "';";
				echo "	 		var purchasetypefieldID = '#purchasetype-" . $sectionID . "';";
					
				echo "	 		var date = $(datefieldID).val();";
				echo "			var year = date.substring(6,10);";
				echo "			var month = parseInt(date.substring(3,5))-1;";
				echo "			var day = date.substring(0,2);";
				echo "			console.log('D-'+day+',M-'+month+',Y-'+year);";
				
				echo "			var days = 24 * 60 * 60 * 1000 * data.paymenttime;";
				echo "			console.log('days - '+days);";
				echo "			var paymenttime = 0;";
				echo "			if (data.paymenttime != '') paymenttime = data.paymenttime;";
				echo "			console.log('paymenttime - '+paymenttime);";
				
				echo "			var firstDate = new Date(year, month, day);";
				echo "			var dueDate = new Date(year, month, day);";
				echo "			dueDate.setDate(firstDate.getDate() + parseInt(paymenttime));";
				echo "			console.log('firstDate - '+firstDate.getDate());";
				
				echo "			var nyear = dueDate.getYear()+1900;";
				echo "			var nmonth = dueDate.getMonth()+1;";
				echo "			if (nmonth < 10) nmonth = '0'+nmonth;";
				echo "			var nday = dueDate.getDate();";
				echo "			if (nday < 10) nday = '0'+nday;";
				
				echo "			var newduedate = nday + '.' + nmonth + '.' + nyear;";
				echo "			console.log('newduedate - '+newduedate);";
					
				echo "		 	$(duedatefieldID).val(newduedate);";
				echo "		 	$(purchasetypefieldID).val(data.paymentmethodID);";
				echo "			console.log('paymentmethodID - '+data.paymentmethodID);";
				
			}
			echo "		}";
		}
		echo "					});";
		echo "				}";
		echo "			});";
		echo "		});";
		echo "	</script>";
		
		
		echo "		<tr>";
		echo "			<td class=field-text style='width:150px;'>Summa</td>";
		echo "			<td style='width:250px;'>";
		echo "				<input type='text' id=amountfield-" . $sectionID . " class=field-select style='width:200px;padding-left:6px;'>";
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
		echo "								<input id=purchasedatefieldoriginal-" . $sectionID . " type='hidden' value='" . $defaultdate . "'>";
		echo "							</td>";
		echo "							<td style='padding-left:4px;'>";
		//echo "								<input type=button class='section-button' value='Tyhjennä' onClick='cleardatefield_" . $sectionID . "_3()'>";
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

		
		echo "		<tr id='paymentdate-" . $sectionID . "' style='display:none'>";
		echo "			<td id='paymentdatetitle-" . $sectionID . "' class=field-text>Eräpäivä</td>";
		echo "			<td>";
		echo "				<div>";
		echo "					<table style='padding-left:0px;' cellpadding=0 cellspacing=0>";
		echo "						<tr>";
		echo "							<td>";
		echo "								<input id=duedatefield-" . $sectionID . " class=uitextfield tabindex=3 style='text-align:center;padding-left:0px;padding-right:0px;width:100px;' type='text'														";
		//echo "									onkeyup='editfieldchanged_" . $fieldID . "()' onfocusin='editfielddeactivated_" . $fieldID . "()'";
		//echo " 									onfocusout='editfielddeactivated_" .$fieldID . "()'";
		$lastdatestr = $registry->lastdate;
		$defaultdate = substr($lastdatestr,8,2).".".substr($lastdatestr,5,2).".".substr($lastdatestr,0,4);
		echo " 									value='" . $defaultdate . "' readonly>";
		echo "							</td>";
		echo "							<td style='padding-left:4px;'>";
		//echo "								<input type=button class='section-button' value='Tyhjennä' onClick='cleardatefield_" . $sectionID . "_3()'>";
		echo "							</td>";
		echo "						<tr>";
		echo "					</table>";
		echo "			  </div>";
		echo " 			</td>";
		echo "			<td></td>";
		echo "		</tr>";
				
		echo "	<script>";
		echo "		$('#duedatefield-" . $sectionID . "').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
		echo "	</script>";
		
			
		echo "		<tr>";
		echo "			<td colspan=3 style='height:19px;'>";
		echo "		</tr>";
	
		echo "		<td colspan=3 style='padding-left:4px;text-align:right;'>";
		echo "			<button  class=section-button onclick='closeDialog" . $sectionID . "()'>Sulje</button>";
	
		echo "			<button id='invoiceaddi-" . $sectionID . "' type=button class='section-button' onClick='savepurchaseinvoice_" . $sectionID . "()'>Tallenna</button>";
		echo "		</td>";
	
		echo "	</table>";
	
		echo "<script>";
		echo "		function closeDialog" . $sectionID . "() {";
		echo "  		$('#sectiondialog-" . $sectionID . "').dialog('close');";
		echo "		};";
		echo "	</script>";
	
		
		// Jos ostopäivää muutetaan niin siirretään eräpäivää vastaavasti eteenpäin. Yhtä monella päivällä
		// kuin aiemminkin oli maksuaikaa asetettu. 
		// TODO: mikäli eräpäivä on manuaalisesti asetettu, sitä ei mennä enää automaattisesti 
		//		 muuttamaan. Paitsi ehkä silloin jos eräpäivä jää ostopäivän jälkeen
		// TODO: virhetila mikäli eräpäivä on ennen ostopäivää
		echo "	<script>";
		echo "	$('#purchasedatefield-" . $sectionID . "').change(function() {";
		echo "		console.log('date changed');";
		
		echo "	 	var datefieldID = '#purchasedatefield-" . $sectionID . "';";
		echo "	 	var duedatefieldID = '#duedatefield-" . $sectionID . "';";
		echo "	 	var originaldatefield = '#purchasedatefieldoriginal-" . $sectionID . "';";

		
		echo "	 	var date = $(datefieldID).val();";
		echo "	 	var duedate = $(duedatefieldID).val();";
		echo "	 	var original = $(originaldatefield).val();";
		
		echo "		console.log(' - currentdate - '+date);";
		echo "		console.log(' - originaldate - '+original);";
		
		echo "		var year = date.substring(6,10);";
		echo "		var month = parseInt(date.substring(3,5))-1;";
		echo "		var day = date.substring(0,2);";
		
		echo "		var oyear = original.substring(6,10);";
		echo "		var omonth = parseInt(original.substring(3,5))-1;";
		echo "		var oday = original.substring(0,2);";
		
		echo "		var oneDay = 24 * 60 * 60 * 1000;";
		echo "		var firstDate = new Date(year, month, day);";
		echo "		var secondDate = new Date(oyear, omonth, oday);";
		echo "		diffDays = Math.round(((firstDate.getTime() - secondDate.getTime()) / oneDay));";
		
		echo "		var dyear = duedate.substring(6,10);";
		echo "		var dmonth = duedate.substring(3,5);";
		echo "		var dday = duedate.substring(0,2);";
		
		echo "		var dueDate = new Date(dyear, dmonth-1, dday);";
		echo "		dueDate.setDate(dueDate.getDate() + diffDays);";
		
		echo "		var nyear = dueDate.getYear()+1900;";
		echo "		var nmonth = dueDate.getMonth()+1;";
		echo "		if (nmonth < 10) nmonth = '0'+nmonth;";
		echo "		var nday = dueDate.getDate();";
		echo "		if (nday < 10) nday = '0'+nday;";
		
		echo "		var newduedate = nday + '.' + nmonth + '.' + nyear;";
		echo "	 	$(duedatefieldID).val(newduedate);";
		echo "	 	$(originaldatefield).val(date);";
		
		echo "	});";
		echo "</script>";
		
		echo "	<script>";
		echo "	$('#purchasetype-" . $sectionID . "').change(function() {";
		//echo "		var supplierID = $('#field-" . $sectionID . "-1').val();";
		//echo "		console.log('supplier changed - " . getUrl('purchases/suppliers/getdefaultrows') . "&supplierID='+supplierID);";
		//echo "		console.log('ostotapa valittu');";
		echo "		var buytype = $('#purchasetype-" . $sectionID . "').val();";
		echo "		console.log('ostotapa - '+buytype);";
		
		foreach($registry->paymentmethods as $index => $paymentmethod) {
			echo "		if (buytype == " . $paymentmethod->paymentmethodID . ") {";
			if ($paymentmethod->duedateusage == Collections::DUEDATEUSAGE_NODUEDATE) {
				echo "			console.log(' - no due date');";
				echo "			$('#paymentdate-" . $sectionID . "').hide();";
			}
			if ($paymentmethod->duedateusage == Collections::DUEDATEUSAGE_CURRENTDATE) {
				echo "			console.log(' - current due date');";
				echo "			$('#paymentdate-" . $sectionID . "').show();";
			}
			if ($paymentmethod->duedateusage == Collections::DUEDATEUSAGE_FROMSUPPLIER) {
				echo "			console.log(' - supplier due date');";
				echo "			$('#paymentdate-" . $sectionID . "').show();";
			}
			echo "		}";
		}
		echo "	});";
		echo "</script>";
		
		
	
		
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
		echo "			if (isNaN(parseFloat(amount))) {";
		echo "				alert('Maksun määrä ei ole numero');";
		echo "				return;";
		echo "			}";
		echo "			params = params + '&amount='+amount;";
		
		echo "			var purchasetype = $('#purchasetype-" . $sectionID . "').val();";
		echo "			if (purchasetype == '') {";
		echo "				alert('Ostotapa puuttu');";
		echo "				return;";
		echo "			}";
		echo "			params = params + '&purchasetype='+purchasetype;";
		
		echo "			var purchasedate = $('#purchasedatefield-" . $sectionID . "').val();";
		echo "			if (purchasedate == '') {";
		echo "				alert('Ostopäivä puuttu');";
		echo "				return;";
		echo "			}";
		echo "			params = params + '&purchasedate='+purchasedate;";
		
		
		echo "			var duedate = $('#duedatefield-" . $sectionID . "').val();";
		echo "			if (duedate == '') {";
		echo "				alert('Eräpäivä puuttu');";
		echo "				return;";
		echo "			}";
		echo "			params = params + '&duedate='+duedate;";
		
		echo "			console.log('params - '+params);";
		echo "			window.location = '".getUrl('accounting/purchases/insertpurchase')."' + params;";
		echo "			return;";
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
	
	$column = new UISortColumn("#", "receiptnumber");
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
