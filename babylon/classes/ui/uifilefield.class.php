<?php


/**
 * 
 * TODO
 *   - notemptya ei testattu
 *   - fixed length ei toteutettu, voi olla etta ei toteutetakkaan
 *   - onlynumbers ei toteutettu
 *   
 * @author Kapteeni
 *
 */

class UIFileField extends UIField {
	
	private $title;
	private $datavariablename;
	private $uploadaction;
	private $downloadaction;
	private $deleteaction;
	private $filelist;
	
	//private $urlparametername;
	//private $maxValue = null;
	//private $minValue = null;
	
	private $savecallback;
	
	// TODO yhdista acceptempty ja not empty, toinen naista on turha
	//private $acceptEmpty;
	//private $notEmpty = false;
	//private $editactive = false;
	
	public function __construct($title, $datavariablename, $uploadaction, $filelist, $downloadaction) {
		parent::__construct();
		$this->title = $title;
		$this->datavariablename = $datavariablename;
		$this->uploadaction = $uploadaction;
		$this->downloadaction = $downloadaction;
		$this->filelist = $filelist;
		
		//$this->urlparametername = $urlparametername;
		//$this->acceptEmpty = true;
	}
	
	
	public function setRemoveAction($deleteaction) {
		$this->deleteaction= $deleteaction;
	}
	
	
	public function getTitle() {
		return $this->title;
	}
	
	/*
	public function setEditActive($boole) {
		$this->editactive = $boole;
	}
	*/
	
	/*
	public function acceptEmpty($accept) {
		$this->acceptEmpty = $accept;		
	}
	*/
	
	/*
	public function setMaxLength($length) {
		$this->maxValue = $length;		
	}
	*/

	// tekstikentan tapauksessa tama on merkkijonon pituus
	/*
	public function setMinLength($length) {
		$this->minValue = $length;
	}
	*/
	
	// ei toteutettu
	/*
	function setSaveCallback($functionname) {
		$this->savecallback = $functionname;
	}
	*/
	
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
	
	
	

	protected function generateDeleteAction($itemID, $file) {
	
		echo "<script>";
		echo "  $('#" . $itemID . "').click(function () {";
	
		//echo "		console.log('pressed - " . $itemID . "');";
		//echo "		console.log('deleteaction - " . $this->deleteaction . "');";
		//echo "		console.log('" . getUrl($this->generateDeleteActionUrl($row)) . "');";
	
		echo "		window.location = '" . getUrl($this->deleteaction) . "&file=" . $file . "';";
		echo "			window.event.stopPropagation();";
		echo "	});";
		echo "</script>";
	}
	
	
	
	// nullia kaytetaan silloin kun ollaan insertoimassa, jolloin arvot on tyhjia (oletusarvot pitaa ehka jotenkin pystya asettamaan)
	function show($data = null) {
		
		//echo "<br>Datavariablename - " . $this->datavariablename;
		//echo "<br>dataclass - "  . get_class($data);
		
		$var = $this->datavariablename;
		if ($data == null) {
			$value = "";
		} else {
			if (is_array($data)) {
				$value = $data[$var];					
			} else {
				$value = $data->$var;
			}
		}

		
		
		//echo "<script type='text/javascript' src='fileuploader.js'></script>";
		//echo "<link rel='stylesheet' href='fileuploader.css' media='screen'/>";
		//echo "<link rel='stylesheet' href='loading-animation.css' media='screen'/>";
		
		
		/*
		echo "<script type='text/javascript'>";
		echo "	window.onload = function() {";
		
		echo "		console.log('" . getUrl($this->uploadaction) . "');";
		echo "		console.log('purchaseID - " . $value . "');";
		
		echo "		var uploader = new qq.FileUploader({";
		echo "			element: document.getElementById('file-uploader'),";
		echo "			uploadButtonText: 'Lataa',";
		echo "			action: '" . getUrl($this->uploadaction) . "&id=" . $value . "',";
		echo "				sizeLimit: 100000000,";
		echo "				minSizeLimit: 500,";
		echo "				debug: true";
		echo "		});";
		echo "		uploader.addExtraDropzone( document.getElementById('file-uploader2'));";
		echo "	};";
		echo "</script>";
		*/
		
		echo "<script type='text/javascript'>";
		echo "	function starfileupload() {";
		
		echo "		console.log('" . getUrl($this->uploadaction) . "');";
		echo "		console.log('purchaseID - " . $value . "');";
		
		echo "		var uploader = new qq.FileUploader({";
		echo "			element: document.getElementById('file-uploader'),";
		echo "			uploadButtonText: 'Lataa',";
		echo "			action: '" . getUrl($this->uploadaction) . "&id=" . $value . "',";
		echo "				sizeLimit: 100000000,";
		echo "				minSizeLimit: 500,";
		echo "				debug: true";
		echo "		});";
		echo "		uploader.addExtraDropzone( document.getElementById('file-uploader2'));";
		echo "	};";
		echo "</script>";
		
		
		echo "<div id='file-uploader2' style='display:none;height:50px;width:100%;text-align:center;padding-top:20px;font-size:24px;background-color:#75ff75;'>Liitä tiedosto";
		echo "</div>";
		
		echo "<table style='width:100%;'>";
		echo "	<tr>";
		echo "		<td class=field-text style='width:26%;vertical-align:top;padding-top:3px;'>" . getMultilangString($this->title) . "</td>";
		echo "		<td style='width:36%;'>";
		
		echo "<div id=fieldvalue-" . $this->getID() . " class=iu-middle-block style='height:100%;'>";
		$var = $this->filelist;
		$liststr = $data->$var;
		//echo "<br>" . $var;
		//echo "<br>" . $data->files;
		//echo "<br>" . $liststr;
		//echo "<br>";
		$items = null;
		if ($liststr == "") {
			echo "<font size=-1 style='font-style:italic;'>Ei asetettu</font>";
		} else {
			if ($liststr == "") {
				echo "<br>Empty";
			}
			
			$items = explode(",", $liststr);
			
			$first = true;
			foreach($items as $index => $linkvalue) {
				if ($first) {
					$first = false;
				} else {
					echo "<br>";
				}
				echo "<a target='_blank' href='" .getUrl($this->downloadaction) ."&id=" . $value . "&file=" . $linkvalue . "'>" . $linkvalue . "</a>";
			}		
		}
		echo "</div>";
	
		
		echo "<div id='editdiv-". $this->getID() ."' class=iu-middle-block style='height:100%;display:none;'>";
		

		if ($items != null) {
			echo "<table>";
			foreach($items as $index => $linkvalue) {
				if ($linkvalue == "") {
					echo "<br>Empty file";
					
				}
				echo "	<tr>";
				echo "		<td>";
				echo "<a target='_blank' href='" .getUrl($this->downloadaction) ."&id=" . $value . "&file=" . $linkvalue . "'>" . $linkvalue . "</a>";
				echo "		</td>";
				echo "		<td>";
				echo "<button  type='button' id='rowfiledeletebutton-" . $this->getID() . "-" . $index. "' class=section-button-header style='margin-left:3px;widht:22px;height:22px;'><i class='fa fa-ban' ></i></button>";
				$this->generateDeleteAction("rowfiledeletebutton-" . $this->getID() . "-" . $index, $linkvalue);
				echo "		</td>";
				echo "	</tr>";
			}
			echo "	</table>";
		}
		
		echo "<div id='file-uploader'>";
		echo "</div>";
		
		/*
		echo "<div  id='file-uploader-demo1'>";
		echo "</div>";
		
		/*
		echo "<div class='qq-upload-extra-drop-area' id='uploaddiv-" . $this->getID() ."'>";
		echo "Drop files here too</div>";
		echo "</div>";
		*/
		echo "			<div id='fieldvalue-". $this->getID() ."'>";
		echo "			</div>";
		echo "		</td>";
		echo "		<td id='fieldmessage-" . $this->getID() . "' style='width:36%;'>";
		echo "			<div class=errordiv id='errordiv-" . $this->getID() . "' style='display:none'>";
		echo "			</div>";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>";
		
		/*
		echo "<script>";
		echo "function createUploader(){";
		echo "	var uploader = new qq.FileUploader({";
		echo "		element: document.getElementById('file-uploader-demo1'),";
		echo "		action: 'do-nothing.htm',";
		echo "		debug: true,";
		echo "		extraDropzones: [qq.getByClass(document, 'qq-upload-extra-drop-area')[0]]";
		echo "	});";
		echo "}";
		echo "</script>";
		
		
		echo "<script>";
		echo "$( document ).ready(function() {";
		echo "		console.log('ready!');";
		echo "		window.onload = createUploader;";
		echo "});";
		echo "</script>																								";
		
		*/
		/*
		echo "<script>";
		echo "		var field" . $this->getID() . "loadedfiles = new Array();";
		echo "</script>																								";
		
		
		echo "<script>";
		echo  "		$(function() {";	
		echo "			var uploader = new qq.FileUploader({";
		echo "				element: document.getElementById('uploaddiv-" . $this->getID() . "'),";
		//echo "				action: '" . getUrl("/utils/upload") . "'";
		echo "				encoding: 'multipart',";
		echo "				onComplete: function (id, filename, response) {";
		echo "					alert('load success - '+id+', filename - '+filename+' - '+response.file);";
		echo "					field" . $this->getID() . "loadedfiles.push(response.file);";
		echo "				}, ";
		echo "				forceMultipart: 'true',";
		echo "				debug: 'true',";
		echo "				action: '" . ROOTPHP .  "?rt=utils/upload'";
		echo "			});";
		echo "		});";
		echo "</script>																								";
		
		echo "	<script>";
		echo "	</script>";
		*/
		
		echo "	<script>";
		echo "		function fieldCheckOnSave_" . $this->getID() . "() {";
		//echo "			alert('fieldCheckOnSave_" . $this->getID() . "()');";
		/*
		echo "			var value = $('#editfield-" . $this->getID() . "').val();";
		//echo "			alert('fieldvalue - '+value+' - '+value.length+' - " . $this->maxValue . "');";
		if ($this->maxValue != null) {
			echo "		if (value.length >" . $this->maxValue . ") return false;";
		}
		if ($this->minValue != null) {
			echo "		if (value.length <" . $this->minValue . ") return false;";
		}
		if ($this->notEmpty == true) {
			echo "		if (value == '') return false;";
		}
		*/
		echo "			return true;";
		echo "		}";
		echo "	</script>";
		
		echo "	<script>";
		echo "		function editfieldChanged" . $this->getID() . "() {";
		echo "			if (fieldCheckOnSave_" . $this->getID() . "() == true) {";
		echo "				$('#errordiv-"  .$this->getID() . "').hide();";
		echo "			} else {";
		echo "				fieldShowError_" . $this->getID() . "();";
		echo "			}";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldGetSaveParams_" . $this->getID() . "() {";
		/*
		echo "			var len = field" . $this->getID() . "loadedfiles.length;";
		echo "			var str = ':';";
		echo "			var i;";
		echo "			for (var i = 0;i < len; i++) {";
		echo "				alert('val - '+i+' = '+field" . $this->getID() . "loadedfiles[i]);";
		echo "				str = str + field" . $this->getID() . "loadedfiles[i] + ':';";
		echo "			}";
		echo "			return '" . $this->urlparametername . "=' + str;";
		*/
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldActivateEdit_" . $this->getID() . "() {";
		echo "			$('#fieldvalue-" . $this->getID() . "').hide();";
		echo "			$('#editdiv-" . $this->getID() . "').show();";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldCancelEdit_" . $this->getID() . "() {";
		echo "			$('#fieldvalue-" . $this->getID() . "').show();";
		echo "			$('#editdiv-" . $this->getID() . "').hide();";
		echo "			$('#errordiv-" . $this->getID() . "').hide();";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function acceptEditAndClose_" . $this->getID() . "() {";
		echo "			var value = $('#editfield-" . $this->getID() . "').val();";
		echo "			$('#fieldvalue-" . $this->getID() . "').html(value);";
		echo "			$('#fieldvalue-" . $this->getID() . "').show();";
		echo "			$('#editdiv-" . $this->getID() . "').hide();";
		echo "			$('#errordiv-" . $this->getID() . "').hide();";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldShowError_" . $this->getID() . "() {";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldFocus_" . $this->getID() . "() {";
		echo "			alert('calling showError_" . $this->getID() . "');";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		
		echo "<script>";
		echo "	function showerror_" . $this->getID() . "(errormessage) {";
		echo "		var textnode = document.createTextNode(errormessage);";
		echo "		textnode.id='errortext-" . $this->getID() . "';";
		echo "		$('#errordiv-" . $this->getID() . "').html('');";
		echo "		$('#errordiv-" . $this->getID() . "').append(textnode);";
		echo "		$('#errordiv-" . $this->getID() . "').show();";
		echo "	}";
		echo "</script>";
		
		echo "	<script>";
		echo "		starfileupload();";
		echo "	</script>";
		
		
	}
}

?>