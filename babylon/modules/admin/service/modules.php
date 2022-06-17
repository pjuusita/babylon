<?php
	


$activatesection = new UISection('Modulin aktivointi','500px');
$activatesection->setDialog(true);
$activatesection->setMode(UIComponent::MODE_NOEDIT);
//$activatesection->setMode(UIComponent::MODE_INSERT);
$activatesection->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/service/updatemodule', 'moduleID');

$field = new UITextField("Name", "name", 'name');
//$field->setVisible(false);
$activatesection->addField($field);

$field = new UITextField("ModuleID", "moduleID", 'moduleID');
$field->setVisible(false);
$activatesection->addField($field);

//$button = new UIButton(UIComponent::ACTION_JAVASCRIPT, "disablemodule", "Poista käytöstä");
//$activatesection->addButton($button);

$button = new UIButton(UIComponent::ACTION_JAVASCRIPT, "activatemodule", "Ota käyttöön");
$activatesection->addButton($button);

/*
$field = new UISelectField("Projekti","projectID","projectID",$registry->projects, "name");
$activatesection->addField($field);
*/

$activatesection->show();


echo "	<script>";
echo "		function activatemodule(itemID) {";

echo "			var val = getValue_" . $activatesection->getID() . "('moduleID');";
echo "			console.log('aktivevalue - '+val);";

echo "			var url = '" .  getUrl("admin/service/activatemodule") . "';";
echo "			url = url + '&moduleID=' + val;";
echo "			console.log('ulr - '+url);";
echo "			window.location = url;";
echo "		}";
echo "	</script>";



echo "	<script>";
echo "		function disablemodule(itemID) {";
//echo "			$('#fsfss')";
echo "			alert('disablenappulaa painettu - '+itemID);";
echo "		}";
echo "	</script>";


echo "<h1>Palvelunhallinta</h1>";

$selection = array();
$row = new Row();
$row->itemID = 0;
$row->name = "Ei käytössä";
$selection[0] = $row;
$row = new Row();
$row->itemID = 1;
$row->name = "Käytössä";
$selection[1] = $row;


$inactivemodules = array();
foreach($registry->modules as $index => $module) {
	//echo "<br>Module - " . $module->name . " - " . $module->active . " - " . $module->moduleID;
	if ($module->active == 1) {
		$section = new UISection($module->name . "");
		
		$section->setOpen(true);
		//$section->setMode(UIComponent::MODE_EDIT);
		$section->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/service/updateservice', 'moduleID');
		$data = new Row();
		$data->moduleID = $module->moduleID;
		
		$editable = false;
		if ($module->modulename == "system") {
			$field = new UITextField("Nimi","appname", "appname");
			$section->addField($field);
			$data->appname = $registry->appname;
			$editable = true;
		}
		
		//$field = new UITextField("moduleID","moduleID");
		//$section->addField($field);
		
		
		$field = new UIFixedTextField("Tila","Aktiivinen");
		$section->addField($field);
		
		$dimensioncounter = 0;
		foreach($registry->dimensions as $index => $dimension) {
			if ($dimension->moduleID == $module->moduleID) {
				$var = "active" . $dimension->dimensionID;
				$field = new UISelectField($dimension->name,$var,$var,$selection, 'name');
				$section->addField($field);
				$data->$var = $dimension->active;
				$dimensioncounter++;
				$editable = true;
			}
		}
		
		if ($module->moduletype > 0) {
			$button = new UIButton(UIComponent::ACTION_FORWARD, "admin/service/disablemodule&id=".$module->moduleID, "Poista käytöstä");
			$section->addButton($button);
		}
		
		$button = new UIButton(UIComponent::ACTION_FORWARD, "admin/service/reinstallmodule&id=".$module->moduleID, "Uudelleenasenna");
		$section->addButton($button);
		
		if ($editable == false) {
			$section->setMode(UIComponent::MODE_NOEDIT);
		}
		
		$section->setData($data);
		$section->show();
	} else {
		$inactivemodules[$module->moduleID] = $module;
	}
}



	$statuslist = array ( '0' => 'Ei käytössä', '1' => 'Aktiivinen' );

	echo "<br>";	
	$table = new UIItemTable("Saatavillaa olevat modulit", "600px");
	$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $activatesection->getID(), "moduleID");
		
	$column = new UISortColumn("#", "moduleID", "modules/modules/showmodules&sort=nimi", "50px");
	$table->addColumn($column);
	
	$column = new UISortColumn("Otsikko", "name", "modules/modules/showmodules&sort=nimi", "320px");
	$table->addColumn($column);
		
	$column= new UISelectColumn("Active", null, "active", $statuslist);
	$table->addColumn($column);
		
	$table->setData($inactivemodules);
	$table->show();
		
		
?>