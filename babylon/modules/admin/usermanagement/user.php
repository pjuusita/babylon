<?php



include ("header.php");


echo "<h1>" . $registry->user->lastname . " " . $registry->user->firstname . "</h1>";

//$registry->user->printContent();
// toteuta savecallback

$section = new UISection("Käyttäjätiedot");
$section->setData($registry->user);
$section->setOpen(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/usermanagement/updateuser', 'userID');


$field = new UITextField("Käyttäjätunnus", "username", 'username');
$section->addField($field);

$field = new UITextField("Etunimi", "firstname", 'firstname');
$section->addField($field);

$field = new UITextField("Sukunimi", "lastname", 'lastname');
$section->addField($field);

$field = new UITextField("Puhelinnumero", "phonenumber", 'phonenumber');
$section->addField($field);

$field = new UISelectField("Käyttäjäryhmä","usergroupID","usergroupID",$registry->usergroups, 'name');
$section->addField($field);

$section->show();



$section = new UISection("Asetukset");
$section->setOpen(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/usermanagement/updateusersettings&usergroupID=' . $registry->user->usergroupID, 'userID');

foreach($registry->dimensions as $index => $dimension) {
	$field = new UISelectField($dimension->name, "dimensionvalueID-" . $dimension->dimensionID,"dimensionvalue-" . $dimension->dimensionID, $dimension->content, 'name');
	$section->addField($field);
}
$section->setData($registry->user);
$section->show();


$section = new UISection("Näkyvyys");

$section->setOpen(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/usermanagement/updateusergroup', 'groupID');
$sectionID = $section->getID();
$_SESSION['global_sectionID'] = $sectionID;
//$_SESSION['global_usergroupID'] = $this->registry->usergroup->usergroupID;

function changeVisibilityDiv() {

	// TODO: lisäksi pitäisi toteutta virhe - ei oikeuksia käyttäjätietojen muuttamiseen.
	
	$warningdialog1 = new UISection("Näkyvyyden muokkaus", "700px");
	$warningdialog1->setDialog(true);
	$warningdialog1->addErrorMessage("Muutosta ei voida tehdä.");
	$warningdialog1->addErrorMessage("Näkyvyys on määritelty kiinteäksi käyttäjäryhmän asetuksissa.");
	$warningdialog1->show();
	
	
	
	$sectionID = $_SESSION['global_sectionID'];
	//$usergroupID = $_SESSION['global_usergroupID'];
	global $registry;

	$accesslevels = array();
	//$accesslevels[AbstractModule::VISIBILITY_NONE] = "Ei oikeuksia";
	$accesslevels[AbstractModule::VISIBILITY_USER] = "Käyttäjäkohtainen";
	$accesslevels[AbstractModule::VISIBILITY_SELECTED] = "Valitut";
	$accesslevels[AbstractModule::VISIBILITY_ALL] = "Kaikki";


	echo "	<table cellspacing=0 cellpadding=0 style='width:100%;'>";
	echo "		<tr>";

	echo "			<input type=hidden id=accessrightsmoduleid>";
	echo "			<input type=hidden id=accessrirghtsaccesskey>";

	echo "			<tr>";
	echo "				<td style='height:1px;width:150px;'></td>";
	echo "				<td style='width:350px;'></td>";
	echo "			</tr>";

	$firstdimension = true;
	foreach($registry->dimensions as $index => $dimension) {

		if ($firstdimension == true) {
			$firstdimension = false;			
		} else {
			echo "			<tr>";
			echo "				<td style='padding-top:4px;border-bottom: 2px solid #ccc'></td>";
			echo "				<td style='padding-top:4px;border-bottom: 2px solid #ccc'></td>";
			echo "			</tr>";
		}
		
		echo "			<tr>";
		echo "				<td class=field-text style='padding-top:4px;'>" . $dimension->plural . "</td>";
		echo "				<td class=field-text style='padding-top:4px;text-align:left;width:350px;'>";

		$first = true;
		foreach($dimension->content as $index => $contentitem) {
			if ($first == true) {
				$first = false;
			} else {
				echo "				<td class=field-text style='height:5px;'></td>";
				echo "				<td class=field-text style='height:5px;text-align:left;width:350px;'>";
			}
			echo "<div style=''>";
			if ($dimension->visibility == AbstractModule::VISIBILITY_USER) {
				if ($contentitem->visibleselected == 1) {
					echo "<div id='uservisiblechecked-" . $dimension->dimensionID . "-" . $contentitem->getID() . "' style='display:inline-block;padding-right:5px;padding-top:3px;' onclick='visibilityUserClicked(" . $dimension->dimensionID  . "," . $contentitem->getID()  . ")'>";
					echo "		<img width=16 height=16 src='" .  getImageUrl('checkbox_01.png')  . "'>";
					echo "</div>";
					echo "<div id='uservisibleunchecked-" . $dimension->dimensionID . "-" . $contentitem->getID() . "' style='display:inline-block;padding-right:5px;padding-top:3px;display:none' onclick='visibilityUserClicked(" . $dimension->dimensionID  . "," . $contentitem->getID()  . ")'>";
					echo "		<img width=16 height=16 src='" .  getImageUrl('checkbox_03.png')  . "'>";
					echo "</div>";
					//echo "<input style='padding-right:10px;' type='checkbox' checked='checked' onchange='dimensionchecked(this," . $dimension->dimensionID . "," . $contentitem->getID() . ")'>";
				} else {
					echo "<div id='uservisiblechecked-" . $dimension->dimensionID . "-" . $contentitem->getID() . "' style='display:inline-block;padding-right:5px;padding-top:3px;display:none' onclick='visibilityUserClicked(" . $dimension->dimensionID  . "," . $contentitem->getID()  . ")'>";
					echo "		<img width=16 height=16 src='" .  getImageUrl('checkbox_01.png')  . "'>";
					echo "</div>";
					echo "<div id='uservisibleunchecked-" . $dimension->dimensionID . "-" . $contentitem->getID() . "' style='display:inline-block;padding-right:5px;padding-top:3px;' onclick='visibilityUserClicked(" . $dimension->dimensionID  . "," . $contentitem->getID()  . ")'>";
					echo "		<img width=16 height=16 src='" .  getImageUrl('checkbox_03.png')  . "'>";
					echo "</div>";
						//echo "<input style='padding-right:10px;'  type='checkbox' onchange='dimensionchecked(this," . $dimension->dimensionID . "," . $contentitem->getID() . ")'>";
				}
				
			} elseif  ($dimension->visibility == AbstractModule::VISIBILITY_SELECTED) {
				if ($contentitem->visibleselected == 1) {
					echo "<div style='display:inline-block;padding-right:5px;padding-top:3px;' onclick='visibilitySelectedClicked()'>";
					echo "		<img width=16 height=16 src='" .  getImageUrl('checkbox_02.png')  . "'>";
					echo "</div>";
				} else {
					echo "<div style='display:inline-block;padding-right:5px;padding-top:3px;'>";
					echo "		<img width=16 height=16 src='" .  getImageUrl('checkbox_04.png')  . "' onclick='visibilitySelectedClicked()'>";
					echo "</div>";
				}
			} elseif  ($dimension->visibility == AbstractModule::VISIBILITY_ALL) {
				echo "<div style='display:inline-block;padding-right:5px;padding-top:3px;cursor:pointer;' onclick='visibilityAllClicked()'>";
				echo "		<img width=16 height=16 src='" .  getImageUrl('checkbox_02.png')  . "'>";
				echo "</div>";
			}
			echo "<div style='display:inline-block;vertical-align:top;'>" . $contentitem->name . "</div></td>";
			echo "</div>";
			echo "				</td>";
			echo "			</tr>";
		}
		echo "			</tr>";
	}
	
	echo "<script>";
	echo "	function visibilityUserClicked(dimensionID, contentID) {";
	echo "		console.log('clicked - '+dimensionID+' - '+contentID);";
	echo "		var checkedItem = '#uservisiblechecked-'+dimensionID+'-'+contentID;";
	echo "		var uncheckedItem = '#uservisibleunchecked-'+dimensionID+'-'+contentID;";
	
	echo "		if ($('#uservisiblechecked-'+dimensionID+'-'+contentID).is(':hidden')) {";
	echo "			console.log('checked state');";
	echo "			$('#uservisibleunchecked-'+dimensionID+'-'+contentID).hide();";
	echo "			$('#uservisiblechecked-'+dimensionID+'-'+contentID).css('display', 'inline-block');";
	echo "			var url = '" . getUrl('admin/usermanagement/checkuservisibility') . "&userID=" . $registry->user->userID . "&usergroupID=" . $registry->user->usergroupID . "&dimensionID='+dimensionID+'&contentID='+contentID;";
	echo "			console.log(url);";
	echo "			$.getJSON('" . getUrl('admin/usermanagement/checkuservisibility') . "&userID=" . $registry->user->userID . "&usergroupID=" . $registry->user->usergroupID . "&dimensionID='+dimensionID+'&contentID='+contentID,'',function(data) {";
	echo "				console.log('uncheckuservisibility - '+data);";
	echo "			}); ";
	echo "		} else {";
	echo "			console.log('unchecked state');";
	echo "			$('#uservisibleunchecked-'+dimensionID+'-'+contentID).css('display', 'inline-block');";
	echo "			$('#uservisiblechecked-'+dimensionID+'-'+contentID).hide();";
	echo "			$.getJSON('" . getUrl('admin/usermanagement/uncheckuservisibility') . "&userID=" . $registry->user->userID . "&usergroupID=" . $registry->user->usergroupID . "&dimensionID='+dimensionID+'&contentID='+contentID,'',function(data) {";
	echo "				console.log('uncheckuservisibility - '+data);";
	echo "			}); ";
	echo "		}";
	
	
	
	//echo "			var url = '" .  getUrl("admin/usermanagement/updatedimensionvisibility") . "&usergroupID=" . $usergroupID . "&dimensionID='+dimensionID+'&dimensionvalueID='+dimensionvalueID+'&accesslevel=1';";
	//echo "			console.log('ulr - '+url);";
	//echo "			window.location = url;";
	echo "	}";
	echo "</script>";
	
	
	echo "<script>";
	echo "	function visibilityAllClicked() {";
	echo "  	$('#sectiondialog-" . $warningdialog1->getID() . "').dialog('open');";
	//echo "		alert('not editable error 1');";
	echo "	}";
	echo "</script>";
	
	echo "<script>";
	echo "	function visibilitySelectedClicked() {";
	echo "  	$('#sectiondialog-" . $warningdialog1->getID() . "').dialog('open');";
	//echo "  	$('#sectiondialog-" . $this->action . "').dialog('open');";
	//echo "		alert('not editable error 2');";
	echo "	}";
	echo "</script>";
	
	
	echo "<script>";
	echo "	function dimensionchecked(checkbox, dimensionID, dimensionvalueID) {";
	echo "		if (checkbox.checked == true) {";
	//echo "			var url = '" .  getUrl("admin/usermanagement/updatedimensionvisibility") . "&usergroupID=" . $usergroupID . "&dimensionID='+dimensionID+'&dimensionvalueID='+dimensionvalueID+'&accesslevel=1';";
	//echo "			console.log('ulr - '+url);";
	//echo "			window.location = url;";
	echo "		} else {";
	//echo "			var url = '" .  getUrl("admin/usermanagement/updatedimensionvisibility") . "&usergroupID=" . $usergroupID . "&dimensionID='+dimensionID+'&dimensionvalueID='+dimensionvalueID+'&accesslevel=0';";
	//echo "			console.log('ulr - '+url);";
	//echo "			window.location = url;";
	echo "		}";
	echo "	}";
	echo "</script>";
	
	echo "<script>";
	echo "	function visibilitychanged(dimensionID, level) {";
	//echo "		var url = '" .  getUrl("admin/usermanagement/updatedimensionvisibility") . "&usergroupID=" . $usergroupID . "&dimensionID='+dimensionID+'&accesslevel='+level;";
	//echo "		console.log('ulr - '+url);";
	//echo "		window.location = url;";
	echo "	}";
	echo "</script>";
	
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



$section = new UISection("Salasanan muuttaminen");
$section->show();


$section = new UISection("Hallinta");

$button = new UIButton(UIComponent::ACTION_FORWARD, "admin/usermanagement/removeuserrights&id=".$registry->user->userID, "Poista käyttöoikeudet");
$section->addButton($button);

$section->show();



//$section = new UISection("Hallinta");
//$section->editable(false);
//$section->show();


?>