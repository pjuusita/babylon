<?php
class UIDateField extends UIField {


	private $title;
	private $propertyname = null;
	private $propertyvalue = null;
	private $urlparam = null;
	private $date;
	private $onchangecallback = null;
	
	
	public function __construct($title, $propertyname, $urlparam) {
		parent::__construct();
		$this->title = $title;
		$this->propertyname = $propertyname;
		$this->urlparam = $urlparam;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getVariable() {
		return $this->propertyname;
	}
	
	
	public function getPropertyName() {
		return $this->propertyname;
	}
	
	public function getDate() {
		return $this->date;
	}
	

	public function setOnChange($functioncall) {
		$this->onchangecallback = $functioncall;
	}
	
	
	function setSaveCallback($functionname) {
		$this->savecallback = $functionname;
	}
	

	function setValueJSFunction() {
		return "setFieldValue_" . $this->getID() . "";
	}
	
	function getCheckOnSaveJSFunction() {
		return "fieldCheckOnSave_" . $this->getID() . "()";
	}
	
	function getSaveParamsJSFunction() {
		return "fieldGetSaveParams_" . $this->getID() . "()";
	}
	
	function getActivateEditJSFunction() {
		return "fieldActivateEdit_" . $this->getID() . "()";
	}
	
	function getCancelEditJSFunction() {
		return "fieldCancelEdit_" . $this->getID() . "()";
	}
	
	function getSaveAcceptedJSFunction() {
		return "acceptEditAndClose_" . $this->getID() . "()";
	}
	
	function getShowErrorJSFunction() {
		return "fieldShowError_" . $this->getID() . "()";
	}
	
	function getSetFocusJSFunction() {
		return "fieldFocus_" . $this->getID() . "()";
	}

	
	public function show($data = null) {


		$var = $this->propertyname;
		if ($data == null) {
			$value = "";
			$valuestr = "<font size=-1 style='font-style:italic;'>Ei asetettu</font>";
		} else {
			if (is_array($data)) {
				$value = $data[$var];
				$valuestr = $data[$var];
			} else {
				$value = $data->$var;	
				$valuestr = $data->$var;
			}
		}

		//echo "<br>datevalue - " . $value;
		
		$valuestr = "";
		
		if ($value != '') {
			
			if ($value == '0000-00-00') {
				$valuestr = "Ei asetttu";
			} else {
				$day = substr($value, 8);
				//echo "<br>OutputValue day - " . $day;
				$month = substr($value, 5, 2);
				//echo "<br>OutputValue month - " . $month;
				$year = substr($value, 0, 4);
				//echo "<br>OutputValue year - " . $year;
				$datestr = $year . "-" . $month . "-" . $day ;
				$value = $day . "." . $month . "." . $year;
				$valuestr = $day . "." . $month . "." . $year;
			}
		} 
		
		//echo "<br>OutputValue - " . $value;
		//echo "<br>OutputValue - " . $valuestr;
		
		echo "<table style='width:100%;'>";
		echo "<tr>";
		echo "	<td width=26% class=field-text>";
		echo "		".$this->title;
		echo "	</td>";
		echo "	<td width=26%>";
		
		$display = "";
		if ($this->editactive == false) {
			$display = "display:none";
		}
		
		echo "<div id=editdiv-" . $this->getID() . " style='" . $display . "'>";
		echo "<table>";
		echo "	<tr>";
		echo "		<td>";
		if ($this->tabindex > 0) {
			echo "			<input class=uitextfield tabindex=" . $this->tabindex . " id=editfield-" . $this->getID() . " type='text'														";
		} else {
			echo "			<input class=uitextfield id=editfield-" . $this->getID() . " type='text'														";
		}
		echo "				onkeyup='editfieldchanged_" . $this->getID() . "()' onfocusin='editfielddeactivated_" . $this->getID() . "()'";
		echo " 				onfocusout='editfielddeactivated_" .$this->getID() . "()'";
		echo " 				style='width:123px;' value='" . $value . "' readonly>";
		
		echo "			<input id='editfield-" . $this->getID() . "original' type='hidden' value='" . $value ."'>";
		
		echo "		</td>";
		echo "		<td>";
		echo "			<input type=button class='section-button' value='Tyhjennä' onClick='cleardatefield_" . $this->getID() . "()'>";
		echo "		</td>";
		echo "	<tr>";
		echo "</table>";
		echo "	  </div>";
		
		if ($this->editactive == true) {
			echo "<div id=fieldvalue-" . $this->getID() . " class='iu-middle-block field-value'  style='display:none;'>";
		} else {
			echo "<div id=fieldvalue-" . $this->getID() . " class='iu-middle-block field-value'  style='display:block;'>";
		}
		echo "". $valuestr;
		echo "	  </div>";
		
		
		echo "	</td>";
		echo "	<td>";
		echo "	</td>";
		echo "</tr>";
		echo "</table>";
		
		echo "	<script>";
		echo "		$('#editfield-" . $this->getID() . "').datepicker({";
		if ($this->onchangecallback != null) {
			echo "			onSelect: function(dateText, event) {";
			//echo "				console.log('Selected date: ' + dateText + '; inputs current value: ' + this.value);";
			echo "			" . $this->onchangecallback . ";";
			//echo "			event.stopPropagation();";
			//echo "			return;";
			echo "			$('#editfield-" . $this->getID() . "original').val(dateText);";
			echo "			},";
		}
		echo "			dateFormat: 'dd.mm.yy', ";
		echo "			firstDay: 1";
		echo "		});";
		echo "	</script>";	
		
		echo "	<script>";
		echo "		function setFieldValue_" . $this->getID() . "(value) {";
		//echo "			console.log('datefield settiiiiii');";
		//echo "			alert('not implemented setFieldValue');";
		echo "			var newdate = value.substring(8,11)+'.'+value.substring(5,7)+'.'+value.substring(0,4);";
		echo "			$('#editfield-" . $this->getID() . "').val(newdate);";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function cleardatefield_" . $this->getID() . "() {";
		echo "			$('#editfield-" . $this->getID() . "').val('');";
		echo "		}";
		echo "	</script>";

		
		echo "	<script>";
		echo "		function fieldCheckOnSave_" . $this->getID() . "() {";
		echo "			return true;";
		echo "		}";
		echo "	</script>";

		
		echo "	<script>";
		echo "		function editfieldchanged_" . $this->getID() . "() {";
		echo "			return true;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldGetSaveParams_" . $this->getID() . "() {";
		echo "			var param_string 	= '" . $this->urlparam . "=';";
		
		// parsetetaan datefieldi sql muotoon lähetettäessä
		echo "			var datestr = $('#editfield-" . $this->getID() . "').val();";
		echo "			var dayindex = datestr.indexOf('.',0);";
		echo "			var monthindex = datestr.indexOf('.',dayindex+1);";
		echo "			var day = datestr.substring(0,dayindex);";
		echo "			var month = datestr.substring(dayindex+1,monthindex);";
		echo "			var year = datestr.substring(monthindex+1);";
		
		echo "			param_string += year + '-' + month + '-' + day;";
		echo "			return param_string;";
		echo "		}";
		echo "	</script>";
		
		echo "	<script>";
		echo "		function fieldGetFieldValue_" . $this->getID() . "() {";
		echo "			var value = $('#editfield-0-" . $this->getID() . "').val();";
		echo "			return value;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldActivateEdit_" . $this->getID() . "() {";
		echo "			$('#fieldvalue-" . $this->getID() . "').hide();";
		echo "			$('#editdiv-" . $this->getID() . "').show();";
		echo "			return true;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldCancelEdit_" . $this->getID() . "() {";
		echo "			$('#fieldvalue-" . $this->getID() . "').show();";
		echo "			$('#editdiv-" . $this->getID() . "').hide();";
		//echo "			$('#errordiv-" . $this->getID() . "').hide();";
		echo "			return true;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function acceptEditAndClose_" . $this->getID() . "() {";
		echo "			var value = $('#editfield-" . $this->getID() . "').val();";
		echo "			$('#fieldvalue-" . $this->getID() . "').html(value);";
		echo "			$('#fieldvalue-" . $this->getID() . "').show();";
		echo "			$('#editdiv-" . $this->getID() . "').hide();";
		echo "			return true;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldShowError_" . $this->getID() . "() {";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldFocus_" . $this->getID() . "() {";
		echo "		}";
		echo "	</script>";
		

		echo "	<script>";
		echo "		function editfielddeactivated_" . $this->getID() . "() {";
		echo "		}";
		echo "	</script>";
		

		echo "	<script>";
		echo "		function editfieldactivated_" . $this->getID() . "() {";
		echo "		}";
		echo "	</script>";
		
		
		echo "<script>";
		echo "	function showerror_" . $this->getID() . "(errormessage) {";
		echo "	}";
		echo "</script>";
	}
	
}
?>