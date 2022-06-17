<?php



echo "	<table  class=logintable align=\"center\" style='width:100%'>";
echo "	 				<tr>";
echo "						<td></td>";
echo "	 					<td class=title colspan=\"3\" style='padding-left:6px;'>Valitse järjestelmä</td>";
echo "	 					<td></td>";
echo "	   					<td></td>";
echo "	  					<td></td>";
echo "	   					<td></td>";
echo "	    			</tr>";

$counter = 0;


if (count($this->registry->roles) == 1) {
	
	header("Location: target-url");
	
	foreach ($this->registry->roles as $index => $loginuser) {
		header("Location: " . getUrl('system/login/roleselected', array('loginid' => $loginuser->loginID, 'userid' => $loginuser->userID )));
	}
	
	//echo "		document.location.href='" . getUrl('system/login/roleselected', array('loginid' => $loginuser->loginID, 'userid' => $loginuser->userID )) . "';";
	exit;
}

foreach ($this->registry->roles as $index => $loginuser) {

	//echo "<br>logintype - " . $loginuser->logintype . " - " . $index . " - " . $loginuser->database;
	
	if ($loginuser->logintype == 1) {
		echo "					<tr>";
		echo "						<td></td>";
		//echo "	 					<td colspan=3 id=role" . $counter . " style='padding: 6px 4px;text-align:left;'><input id=loginbutton" . $counter . " class=loginbutton style='width:490px;text-align:left;cursor:pointer;' type='button' value=\"" . $loginuser->description  . " / " . $loginuser->databasename . " / " . $loginuser->username .  "\"></td>";
		echo "	 					<td colspan=3 id=role" . $counter . " style='padding: 6px 4px;text-align:left;'><input id=loginbutton" . $counter . " class=loginbutton style='width:490px;text-align:left;cursor:pointer;' type='button' value=\"" . $loginuser->description  . " / " . $loginuser->usergroupname .  "\"></td>";
		echo "	 					<td></td>";
		echo "	 				</tr>";
		
		echo "<script>";
		echo "	$('#role" . $counter . "').click(function() {";
		// TODO: tämän url generoinnin voisi tehdä saman malliseksi kuin muualla, ei tuota array systeemiä misään käytetä...
		echo "		document.location.href='" . getUrl('system/login/roleselected') . "&loginID=" . $loginuser->loginID . "&userID=" . $loginuser->userID . "&usergroupID=" . $loginuser->usergroupID . "';";
		echo "	});";
		echo "</script>";
		
	} elseif ($loginuser->logintype == 2) {
		
		echo "					<tr>";
		echo "						<td></td>";
		echo "	 					<td colspan=3 id=role" . $counter . " style='padding: 6px 4px;text-align:left;'><input id=loginbutton" . $counter . " class=loginbutton style='width:490px;text-align:left;cursor:pointer;background-color:pink' type='button' value=\"" . $loginuser->description  . " / " . $loginuser->database .  "\"></td>";
		echo "	 					<td></td>";
		echo "	 				</tr>";
		
		
		/*
		$insertsection = new UISection("Anna asennuskoodi");
		$insertsection->setDialog(true);
		$insertsection->setMode(UIComponent::MODE_INSERT);
		$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/service/install');

		$nimifield = new UITextField("Asennuskoodi", "code", 'code');
		$insertsection->addField($nimifield);
		
		$nimifield = new UITextField("Admin", "username", 'username');
		$insertsection->addField($nimifield);

		$nimifield = new UITextField("Database", "database", 'database');
		$insertsection->addField($nimifield);
		
		$loginuser->code = "";
		$insertsection->setData($loginuser);
		$insertsection->show();
		*/
		
		echo "<script>";
		echo "	$('#role" . $counter . "').click(function() {";
		//echo "  	$('#sectiondialog-" . $insertsection->getID() . "').dialog('open');";
		echo "		document.location.href='" . getUrl('admin/service/install', array('username' => $loginuser->username, 'database' => $loginuser->database, 'systemID' => $loginuser->systemID, 'systemname' => $loginuser->systemname  )) . "';";
		echo "	});";
		echo "</script>";
		
		
		/*
		
		echo "<script>";
		echo "	$('#role" . $counter . "').click(function() {";
		echo "		document.location.href='" . getUrl('admin/service/install', array('username' => $loginuser->username, 'database' => $loginuser->database )) . "';";
		echo "	});";
		echo "</script>";
		
		*/
		
		
		
	}
	
	
	$counter++;
}
echo "					<tr>";
echo "						<td style=\"width:20px;height:10px\"></td>";
echo "						<td></td>";
echo "						<td></td>";
echo "						<td></td>";
echo "						<td></td>";
echo "					</tr>";

$admin = false;

if ($admin) {

	echo "	<table  class=logintable align=\"center\" style='width:100%;margin-top:15px;'>";
	echo "	 				<tr>";
	echo "						<td style='height:5px;'></td>";
	echo "	 					<td></td>";
	echo "	 					<td></td>";
	echo "	   					<td></td>";
	echo "	  					<td></td>";
	echo "	   					<td></td>";
	echo "	    			</tr>";
	
	echo "					<tr>";
	echo "						<td></td>";
	echo "	 					<td colspan=3 id=role" . $counter . " style='padding: 6px 4px;text-align:left;'><input id=loginbutton" . $counter . " class=loginbutton style='width:490px;text-align:left;cursor:pointer;background-color:pink' type='button' value=\"Create new database\"></td>";
	echo "	 					<td></td>";
	echo "	 				</tr>";
	
	echo "<script>";
	echo "	$('#role" . $counter . "').click(function() {";
	echo "		document.location.href='" . getUrl('system/login/newdialog', array('username' => $loginuser->username)) . "';";
	echo "	});";
	echo "</script>";

	echo "					<tr>";
	echo "						<td style=\"width:20px;height:10px\"></td>";
	echo "						<td></td>";
	echo "						<td></td>";
	echo "						<td></td>";
	echo "						<td></td>";
	echo "					</tr>";
	
}


echo "	</table>";


?>