<?php


include ("header.php");

echo "<h1>" . $registry->usergroup->name . "</h1>";


$section = new UISection("Käyttäjäryhmän tiedot");

$section->setOpen(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/usermanagement/updateusergroup&usergroupID=' . $registry->usergroup->usergroupID, 'rowID');

$field = new UITextField("Nimi", "name", 'name');
$section->addField($field);

$section->setData($registry->usergroup);
$section->show();



if (count($registry->dimensions) > 0) {

	$section = new UISection("Näkyvyys");
	
	$section->setOpen(true);
	$section->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/usermanagement/updateusergroup', 'groupID');
	$sectionID = $section->getID();
	$_SESSION['global_sectionID'] = $sectionID;
	$_SESSION['global_usergroupID'] = $this->registry->usergroup->usergroupID;
	
	function changeVisibilityDiv() {
	
		$sectionID = $_SESSION['global_sectionID'];
		$usergroupID = $_SESSION['global_usergroupID'];
		global $registry;
	
		$accesslevels = array();
		//$accesslevels[AbstractModule::VISIBILITY_NONE] = "Ei oikeuksia";
		$accesslevels[AbstractModule::VISIBILITY_USER] = "Käyttäjäkohtainen";
		$accesslevels[AbstractModule::VISIBILITY_SELECTED] = "Valitut";
		$accesslevels[AbstractModule::VISIBILITY_ALL] = "Kaikki";
	
	
		echo "	<table style='width:100%;'>";
		echo "		<tr>";
	
		echo "			<input type=hidden id=accessrightsmoduleid>";
		echo "			<input type=hidden id=accessrirghtsaccesskey>";
	
		echo "			<tr>";
		echo "				<td style='width:50px;'></td>";
		echo "				<td style='width:350px;'></td>";
		echo "				<td style='width:200px;'></td>";
		echo "			</tr>";
	
		foreach($registry->dimensions as $index => $dimension) {
	
			echo "			<tr>";
			echo "				<td colspan=2 class=field-text style='height:5px;'>" . $dimension->plural . "</td>";
			echo "				<td style='height:5px;text-align:right;'>";
			echo "				<select id=rightselectfield" . $dimension->dimensionID . " onchange='visibilitychanged(" . $dimension->dimensionID . ",this.value)' class=field-select style='width:200px'>";
			foreach ($accesslevels as $level => $name) {
				if ($dimension->accesslevel == $level)
					if ($level == AbstractModule::VISIBILITY_SELECTED) {
						echo " 			<option selected value='" . $level . "'>" . $name . " " . lcfirst($dimension->plural) . "</option>";
					} else {
						echo " 			<option selected value='" . $level . "'>" . $name . "</option>";
					}
					else
						if ($level == AbstractModule::VISIBILITY_SELECTED) {
							echo " 			<option value='" . $level . "'>" . $name . " " . lcfirst($dimension->plural) . "</option>";
						} else {
							echo " 			<option value='" . $level . "'>" . $name . "</option>";
						}
			}
			echo "				</select>";
			echo "				</td>";
			echo "			</tr>";
				
			if ($dimension->accesslevel == AbstractModule::VISIBILITY_SELECTED) {
				foreach($dimension->content as $index => $accessitem) {
					echo "			<tr>";
					echo "				<td></td>";
					echo "				<td class=field-text style='height:5px;'>";
					echo "" . $accessitem->name . "</td>";
					echo "				<td style='height:5px;text-align:center;'>";
					if ($accessitem->accesslevel == 1) {
						echo "<input type='checkbox' checked='checked' onchange='dimensionchecked(this," . $dimension->dimensionID . "," . $accessitem->getID() . ")'>";
					} else {
						echo "<input type='checkbox' onchange='dimensionchecked(this," . $dimension->dimensionID . "," . $accessitem->getID() . ")'>";
					}
					echo "				</td>";
					echo "			</tr>";
				}
			}
	
			echo "<script>";
			echo "	function dimensionchecked(checkbox, dimensionID, dimensionvalueID) {";
			echo "		if (checkbox.checked == true) {";
			echo "			var url = '" .  getUrl("admin/usermanagement/updatedimensionvisibility") . "&usergroupID=" . $usergroupID . "&dimensionID='+dimensionID+'&dimensionvalueID='+dimensionvalueID+'&accesslevel=1';";
			echo "			console.log('ulr - '+url);";
			echo "			window.location = url;";
			echo "		} else {";
			echo "			var url = '" .  getUrl("admin/usermanagement/updatedimensionvisibility") . "&usergroupID=" . $usergroupID . "&dimensionID='+dimensionID+'&dimensionvalueID='+dimensionvalueID+'&accesslevel=0';";
			echo "			console.log('ulr - '+url);";
			echo "			window.location = url;";
			echo "		}";
			echo "	}";
			echo "</script>";
	
			echo "<script>";
			echo "	function visibilitychanged(dimensionID, level) {";
			echo "		var url = '" .  getUrl("admin/usermanagement/updatedimensionvisibility") . "&usergroupID=" . $usergroupID . "&dimensionID='+dimensionID+'&accesslevel='+level;";
			echo "		console.log('ulr - '+url);";
			echo "		window.location = url;";
			echo "	}";
			echo "</script>";
		}
		echo "			<tr>";
		echo "				<td colspan=2 style='height:15px;'>";
		echo "				</td>";
		echo "			</tr>";
		echo "	</table>";
	
		echo "<script>";
		echo "		function rightscloseDialog() {";
		echo "  		$('#sectiondialog-" . $sectionID . "').dialog('close');";
		echo "		};";
		echo "	</script>";
	
		echo "	<script>";
		echo "		function changerightscancel() {";
		echo "			alert('cancel');";
		echo "		}";
		echo "	</script>";
	}
	$section->setCustomContent('changeVisibilityDiv');
	$section->show();
}

/*
foreach($registry->dimensions as $index => $dimension) {
	$field = new UITextField($dimension->name, "value", 'value');
	$section->addField($field);
}

$section->setData($registry->usergroup);
$section->show();
*/






$section = new UISection('Käyttöoikeudet','600px');
$section->setMode(UIComponent::MODE_SHOW);
$section->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/usermanagement/saveusergroups&groupID=' . $this->registry->usergroup->usergroupID);



//echo "<br>ID - " . $changeaccesssection->getID();
$sectionID = $section->getID();
//echo "<br>sectionID - " . $sectionID;
$_SESSION['global_sectionID'] = $sectionID;
$_SESSION['global_usergroupID'] = $this->registry->usergroup->usergroupID;
//echo "<br>usergroupID - " .  $this->registry->usergroup->usergroupID;

function changeAccessRightsDiv() {

	$sectionID = $_SESSION['global_sectionID'];
	$usergroupID = $_SESSION['global_usergroupID'];
	global $registry;
	
	$accesslevels = array();
	$accesslevels[AbstractModule::ACCESSRIGHT_NONE] = "Ei oikeuksia";
	$accesslevels[AbstractModule::ACCESSRIGHT_READ] = "Lukuoikeudet";
	$accesslevels[AbstractModule::ACCESSRIGHT_CUSTOM] = "Muokatut oikeudet";
	$accesslevels[AbstractModule::ACCESSRIGHT_ALL] = "Kaikki oikeudet";

	
	$subaccesslevels = array();
	$subaccesslevels[AbstractModule::ACCESSRIGHT_NONE] = "Ei oikeuksia";
	$subaccesslevels[AbstractModule::ACCESSRIGHT_READ] = "Lukuoikeudet";
	$subaccesslevels[AbstractModule::ACCESSRIGHT_ALL] = "Kaikki oikeudet";
	
	
	echo "	<table style='width:100%;'>";
	echo "		<tr>";

	echo "			<input type=hidden id=accessrightsmoduleid>";
	echo "			<input type=hidden id=accessrirghtsaccesskey>";
	
	echo "			<tr>";
	echo "				<td style='width:50px;'></td>";
	echo "				<td style='width:350px;'></td>";
	echo "				<td style='width:200px;'></td>";
	echo "			</tr>";
	
	foreach($registry->modules as $index => $module) {

		if ($module->name == "System") {
			// Ei näytetä system modulen toimintoja
		} else {
			echo "			<tr>";
			echo "				<td colspan=2 class=field-text style='height:5px;'>" . $module->name . "</td>";
			echo "				<td style='height:5px;text-align:right;'>";
			echo "				<select id=rightselectfield" . $module->moduleID . " onchange='accessrightchanged(" . $module->moduleID . ",this.value)' class=field-select style='width:200px'>";
			foreach ($accesslevels as $level => $name) {
				if ($module->accesslevel == $level)
					echo " 			<option selected value='" . $level . "'>" . $name . "</option>";
				else
					echo " 			<option value='" . $level . "'>" . $name . "</option>";
			}
			echo "				</select>";
			echo "				</td>";
			echo "			</tr>";
			
			if ($module->accesslevel == AbstractModule::ACCESSRIGHT_CUSTOM) {
				$accessitems = $registry->moduleitems[$module->moduleID];
				foreach($accessitems as $index => $accessitem) {
					echo "			<tr>";
					echo "				<td></td>";
					echo "				<td class=field-text style='height:5px;'>" . $accessitem->name . "</td>";
					echo "				<td style='height:5px;text-align:right;'>";
					echo "				<select id=accessitemselectfield" . $accessitem->accesskeyID . " onchange='subaccessrightschanged(" . $module->moduleID . "," . $accessitem->accesskeyID . ",this.value)' class=field-select style='width:170px;'>";
					//echo " 					<option value='0'></option>";
					foreach ($subaccesslevels as $level => $name) {
						if ($accessitem->accesslevel == $level)
							echo " 			<option selected value='" . $level . "'>" . $name . "</option>";
						else
							echo " 			<option value='" . $level . "'>" . $name . "</option>";
					}
					echo "				</select>";
					echo "				</td>";
					echo "			</tr>";
				}
			}
			
			echo "<script>";
			echo "	function subaccessrightschanged(moduleID, accesskeyID, level) {";
			echo "		var url = '" .  getUrl("admin/usermanagement/updateusergroupmoduleaccess") . "&usergroupID=" . $usergroupID . "&moduleID='+moduleID+'&accesskeyID='+accesskeyID+'&accesslevel='+level;";
			echo "		console.log('ulr - '+url);";
			echo "		window.location = url;";
			echo "	}";
			echo "</script>";
			
			
			echo "<script>";
			echo "	function accessrightchanged(moduleID, level) {";
			echo "		var url = '" .  getUrl("admin/usermanagement/updateusergroupmoduleaccess") . "&usergroupID=" . $usergroupID . "&moduleID='+moduleID+'&accesslevel='+level;";
			echo "		console.log('ulr - '+url);";
			echo "		window.location = url;";
			echo "	}";
			echo "</script>";
		}
	}
	echo "			<tr>";
	echo "				<td colspan=2 style='height:15px;'>";
	echo "				</td>";
	echo "			</tr>";
	echo "	</table>";

	echo "<script>";
	echo "		function rightscloseDialog() {";
	echo "  		$('#sectiondialog-" . $sectionID . "').dialog('close');";
	echo "		};";
	echo "	</script>";
	
	echo "	<script>";
	echo "		function changerightscancel() {";
	echo "			alert('cancel');";
	echo "		}";
	echo "	</script>";
}

$section->setCustomContent('changeAccessRightsDiv');
$section->show();








// ---------------------------------------------------------------------------------------------------
// Hallinta
// ---------------------------------------------------------------------------------------------------

$managementSection = new UISection("Hallinta");
$managementSection->editable(false);
$managementSection->setDebug(true);

$button = new UIButton(UIComponent::ACTION_FORWARD, "admin/usermanagement/updateusergroupmenu&id=" . $registry->usergroup->usergroupID, "Update menu");
$managementSection->addButton($button);

$button = new UIButton(UIComponent::ACTION_FORWARD, "admin/usermanagement/removeusergroup&id=" . $registry->usergroup->usergroupID, "Poista käyttäjäryhmä");
$managementSection->addButton($button);

$managementSection->show();




?>