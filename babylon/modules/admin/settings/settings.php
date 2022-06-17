<?php

//echo "<br>Modulecount - " . count($registry->modules);

echo "<table  cellspacing='0' cellpadding='0' style='width:600px;border-spacing:0px;border-collapse:collapse;'>";
echo "<tr>";


$counter = 0;
foreach($registry->modules as $index => $module) {
	
	echo "	<td>";
	if ($counter == 0) {
		if ($registry->selectedmoduleID == $module->moduleID) {
			echo "<div onclick=\"moduleopen(" . $module->moduleID . ")\" class='header-selection-left-selected'>";
			echo $module->name;
			echo "</div>";
		} else {
			echo "<div onclick=\"moduleopen(" . $module->moduleID . ")\"  class='header-selection-left'>";
			echo $module->name;
			echo "</div>";
		}
	} elseif ($counter == (count($registry->modules)-1)) {
		if ($registry->selectedmoduleID == $module->moduleID) {
			echo "<div onclick=\"moduleopen(" . $module->moduleID . ")\"  class='header-selection-right-selected'>";
			echo $module->name;
			echo "</div>";
		} else {
			echo "<div onclick=\"moduleopen(" . $module->moduleID . ")\"  class='header-selection-right'>";
			echo $module->name;
			echo "</div>";
		}
	} else {
		if ($registry->selectedmoduleID == $module->moduleID) {
			echo "<div onclick=\"moduleopen(" . $module->moduleID . ")\"  class='header-selection-center-selected'>";
			echo $module->name;
			echo "</div>";
		} else {
			echo "<div onclick=\"moduleopen(" . $module->moduleID . ")\"  class='header-selection-center'>";
			echo $module->name;
			echo "</div>";
		}
	}
	echo "	</td>";
	$counter++;
}
echo "	</table>";

echo "<script>";
echo "	function moduleopen(moduleID) {";
//echo "		alert('module - '+moduleID);";
echo "			var url = '" .  getUrl("admin/settings/showsettings") . "&settingsmoduleID='+moduleID;";
echo "			console.log('ulr - '+url);";
echo "			window.location = url;";
//echo "		$('#sectiondialog-" . $this->getID() . "').dialog({ open: function(event,ui) { " . $openfunctionstring . " } , modal:true, autoOpen: false, width: \"" . $this->sectionwidth . "\"});";
echo "	}";
echo "</script>";
	

if ($registry->selectedmoduleID == 0) {
	include "companysettings.php";
} else {
	$settingscontroller = $registry->module->generateSettingsView($registry);	
}


/*
echo "<table  cellspacing='0' cellpadding='0' style='width:600px;border-spacing:0px;border-collapse:collapse;'>";
echo "<tr>";
echo "	<td>";
echo "<div class='header-selection-left'>";
echo "Yritys";
echo "</div>";
echo "	</td>";
echo "	<td>";
echo "<div class='header-selection-center'>";
echo "Asiakashallinta";
echo "</div>";
echo "	</td>";
echo "	<td>";
echo "<div class='header-selection-center-selected'>";
echo "Kirjanpito";
echo "</div>";
echo "	</td>";
echo "	<td>";
echo "<div class='header-selection-center'>";
echo "Myynti";
echo "</div>";
echo "	</td>";
echo "	<td>";
echo "<div class='header-selection-right'>";
echo "Tilitoimisto";
echo "</div>";
echo "	</td>";
echo "</tr>";
echo "</table>";
*/
// TODO: teoriassa contentti voitaisiin luoda kutsumalla modulin metodia?


//$menupath = SITE_PATH . 'modules' . DIRECTORY_SEPARATOR . 'admin/settings/companysettings.php';
//include ($menupath);



/*
echo "<h1>Yleisasetukset</h1>";

$tabsection = new UITabSection("","900px");

$tabsection->addTab("Yritystiedot", "accounting/purchases/index");
//$tabsection->addTab("Myynti", "accounting/purchases/index");
$tabsection->addTab("Asiakasryhmät", "accounting/purchases/index");
$tabsection->addTab("Tehtävänimikkeet", "accounting/purchases/index");
$tabsection->addTab("Tuotteet", "accounting/purchases/index");
$tabsection->addTab("Tuoteyksiköt", "accounting/purchases/index");


$table = new UITableSection("Ostolaskut","850px");
$table->setOpen(true);
$table->setFramesVisible(false);
$table->showTitle(false);
$table->setShowSumRow(true);

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Uusi ostolasku');
//$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"accounting/purchases/showpurchase","purchaseID");

$column = new UISortColumn("#", "purchaseID");
$table->addColumn($column);

$column = new UISortColumn("Laskupäivä", "purchasedate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

$column = new UISelectColumn("Toimittaja", "name", "supplierID", $registry->suppliers);
$table->addColumn($column);

$column = new UISortColumn("Maksupäivä", "duedate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

$column = new UISortColumn("ALV", "alvamount");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$column = new UISortColumn("Brutto", "grossamount");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$column = new UILinkColumn("Tosite", "file", "file","accounting/purchases/download");
$table->addColumn($column);

$table->setData($registry->invoices);

$tabsection->setContent($table);
$tabsection->show();

	echo "<h1>Taloushallintoasetukset</h1>";

	
	
	$tabsection = new UITabSection("","900px");
	
	$tabsection->addTab("Kirjanpitoasetukset", "accounting/purchases/index");
	$tabsection->addTab("Tositesarjat", "accounting/purchases/index");
	$tabsection->addTab("Tilikaudet", "accounting/purchases/index");
	$tabsection->addTab("ALV", "accounting/purchases/index");
	$tabsection->addTab("Maksuliikenne", "accounting/purchases/index");
	$tabsection->addTab("Palkanlaskenta", "accounting/purchases/index");
	$tabsection->addTab("Tilikartta", "accounting/purchases/index");

		
	$table = new UITableSection("Ostolaskut","850px");
	$table->setOpen(true);
	$table->setFramesVisible(false);
	$table->showTitle(false);
	$table->setShowSumRow(true);
	
	//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Uusi ostolasku');
	//$table->addButton($button);
	
	$table->setLineAction(UIComponent::ACTION_FORWARD,"accounting/purchases/showpurchase","purchaseID");
	
	$column = new UISortColumn("#", "purchaseID");
	$table->addColumn($column);
	
	$column = new UISortColumn("Laskupäivä", "purchasedate");
	$column->setFormatter(Column::COLUMNTYPE_DATE);
	$table->addColumn($column);

	$column = new UISelectColumn("Toimittaja", "name", "supplierID", $registry->suppliers);
	$table->addColumn($column);
	
	$column = new UISortColumn("Maksupäivä", "duedate");
	$column->setFormatter(Column::COLUMNTYPE_DATE);
	$table->addColumn($column);
	
	$column = new UISortColumn("ALV", "alvamount");
	$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
	$column->setAlign(Column::ALIGN_RIGHT);
	$table->addColumn($column);
	
	$column = new UISortColumn("Brutto", "grossamount");
	$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
	$column->setAlign(Column::ALIGN_RIGHT);
	$table->addColumn($column);
	
	$column = new UILinkColumn("Tosite", "file", "file","accounting/purchases/download");
	$table->addColumn($column);
	
	$table->setData($registry->invoices);
	
	$tabsection->setContent($table);
	$tabsection->show();
	
	
	
	//echo "<br><br>Tänne lista käyttöoikeuksista";
	//echo "<br>Dimensioasetukset, oletusarvot";
	//echo "<br>Logo?";
	
	
	//$table = new UISection("Järjestelmäaasetukset", "600px");
	//$table->setLineAction(UIComponent::ACTION_FORWARD, "admin/settings/showsettings", "moduleID");
	
	//$column = new UISortColumn("Tallennushakemisto", "name", "modules/modules/showmodules&sort=nimi", "320px");
	//$table->addColumn($column);
	
	/*
	$section = new UISection("Järjestelmäaasetukset");
	$section->setOpen(true);
	$section->editable(true);
	//$section->setMode(UIComponent::MODE_INSERT);
	
	$section->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/settings/updatesettings');
	
	$field = new UITextField("Tallennushakemisto","savedir","savedir");
	$section->addField($field);
	
	$section->setData($this->registry->settings);
	$section->show();
*/


?>