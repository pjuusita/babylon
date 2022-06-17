<?php
	
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

	/*
	echo "<div>";
	echo "<div class='header-selection-left' style='float:left;'>";
	echo "Yritys";
	echo "</div>";
	echo "<div class='header-selection-center' style='float:left;'>";
	echo "Asiakashallinta";
	echo "</div>";
	echo "<div class='header-selection-center' style='float:left;'>";
	echo "Kirjanpito";
	echo "</div>";
	echo "<div class='header-selection-center' style='float:left;'>";
	echo "Myynti";
	echo "</div>";
	echo "<div class='header-selection-right' style='float:left;'>";
	echo "Tilitoimisto";
	echo "</div>";
	echo "</div>";
	*/
	
	
	//echo "<br>";
	
	/*
	echo "		<table  class='header-selection' style='width:600px;'>";
	echo "			<tr>";
	echo "				<td class=header-selection-item style='width:100px;'>Yritys<td>";
	echo "				<td class=section-title style='width:100px;'>Asiakashallinta<td>";
	echo "				<td class=section-title style='width:100px;'>Kirjanpito<td>";
	echo "				<td class=section-title style='width:100px;'>Myynti<td>";
	echo "				<td class=section-title style='width:100px;'>Tilitoimisto<td>";
	echo "			</tt>";
	echo "		</table>";
	*/
	
	

	echo "<h1>Yritysasetukset</h1>";
	
	if (count($registry->companies) == 0) {
		// tähän lisäysikkuna
		//echo "<br>Ei companytietoja";
	
		$section = new UISection("Yritys");
		$section->setOpen(true);
		$section->editable(true);
		$section->setMode(UIComponent::MODE_INSERT);
		// TODO: pitäisi estää peruuta-nappulan toimita tällaisessa tilanteessa
		
			
		$section->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/settings/insertcompany');
	
		$field = new UITextField("Nimi","name","name");
		$section->addField($field);
	
		$field = new UITextField("Y-Tunnus","businesscode","businesscode");
		$section->addField($field);
	
		$field = new UISelectField("Kotimaa","countryID","countryID", $registry->countries, "name");
		$section->addField($field);
	
		$section->show();
	}
	
	
	if (count($registry->companies) == 1) {
	
		// Tähän pitäisi lisätä, että tämä on maksullinen lisätoiminto
		$insertsection = new UISection('Yrityksen lisäys','500px');
		$insertsection->setDialog(true);
		$insertsection->setMode(UIComponent::MODE_INSERT);
		$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/settings/insertcompany');
		
		$field = new UITextField("Nimi", "name", 'name');
		$insertsection->addField($field);
		
		$field = new UITextField("Y-tunnus", "businesscode", 'businesscode');
		$insertsection->addField($field);
		
		$insertsection->show();
		
				
		$section = new UISection("Yritysten tiedot");
		$section->setOpen(true);
		$section->editable(true);
		$section->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/settings/updatecompany', 'companyID');
		$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää uusi");
		$section->addButton($button);
		
		
		$field = new UITextField("Nimi","name","name");
		$section->addField($field);
	
		$field = new UITextField("Y-Tunnus","businesscode","businesscode");
		$section->addField($field);
	
		$field = new UISelectField("Kotimaa","countryID","countryID", $registry->countries, "name");
		$section->addField($field);
	
	
		foreach($registry->companies as $index => $company) {}
		$section->setData($company);
		$section->show();
	}
	
	if (count($registry->companies) > 1) {

		$insertsection = new UISection('Yrityksen lisäys','500px');
		$insertsection->setDialog(true);
		$insertsection->setMode(UIComponent::MODE_INSERT);
		$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/settings/insertcompany');
		
		$field = new UITextField("Nimi", "name", 'name');
		$insertsection->addField($field);
		
		$field = new UITextField("Y-tunnus", "businesscode", 'businesscode');
		$insertsection->addField($field);
		
		$insertsection->show();
		
		
		$table = new UITableSection("Yritykset", '600px');
		$table->setOpen(true);
		$table->setFramesVisible(true);
		
		// TODO: Poisto-toiminto
		// TODO: Editointitoiminto
		
		$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää uusi");
		$table->addButton($button);
		
		$nimicolumn = new UISortColumn("Nimi", "name", "");
		$table->addColumn($nimicolumn);
		
		$nimicolumn = new UISortColumn("Y-Tunnus", "businesscode", "");
		$table->addColumn($nimicolumn);
		
		$table->setData($registry->companies);
		$table->show();
		
	
		// näyttään tiedot listana
	}
	
	$insertsection = new UISection('Toimipisteen lisäys','500px');
	$insertsection->setDialog(true);
	$insertsection->setMode(UIComponent::MODE_INSERT);
	$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/settings/insertoffice');
	
	/*
	if (count($registry->offices) == 0) {
		$field = new UINoteField("Olet luomassa ensimmäistä toimipistettä. Kaikki olemassaoleva tieto kiinnitetään tähän ensimmäiseen toimipisteeseen.");
		$insertsection->addField($field);
	}
	*/
	
	$field = new UITextField("Toimipiste", "name", 'name');
	$insertsection->addField($field);
	
	$field = new UITextField("Lyhenne", "shortname", 'shortname');
	$insertsection->addField($field);
	
	$insertsection->show();
	
	
	$udpatesection = new UISection('Toimipisteen muokkaus','500px');
	$udpatesection->setDialog(true);
	$udpatesection->setMode(UIComponent::MODE_EDIT);
	$udpatesection->setUpdateAction(UIComponent::ACTION_FORWARD, 'admin/settings/updateoffice', 'officeID');
	
	$field = new UITextField("Toimipiste", "name", 'name');
	$udpatesection->addField($field);
	
	$field = new UITextField("Lyhenne", "shortname", 'shortname');
	$udpatesection->addField($field);
	
	$udpatesection->show();
	
	
	
	$table = new UITableSection("Toimipisteet", '600px');
	$table->setOpen(true);
	$table->setTableHeaderVisible(false);
	$table->setFramesVisible(true);
	$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $udpatesection->getID(), "officeID");
	
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää uusi");
	$table->addButton($button);
	
	$column = new UISortColumn("Nimi", "name", "");
	$table->addColumn($column);
	
	$column = new UISortColumn("Lyhenne", "shortname", "");
	$table->addColumn($column);
	
	$column = new UIHiddenColumn("OfficeID", "officeID", "");
	$table->addColumn($column);
	
	$table->setData($registry->offices);
	$table->show();
	
	
	
	$insertsection = new UISection('Toimialan lisäys','500px');
	$insertsection->setDialog(true);
	$insertsection->setMode(UIComponent::MODE_INSERT);
	$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/settings/insertbranch');
	
	/*
	if (count($registry->branches) == 0) {
		$field = new UINoteField("Olet luomassa ensimmäistä toimialaa. Kaikki olemassaoleva tieto kiinnitetään tähän ensimmäiseen toimialaan.");
		$insertsection->addField($field);
	}
	*/
	
	$field = new UITextField("Nimi", "name", 'name');
	$insertsection->addField($field);
	
	$field = new UITextField("Lyhenne", "shortname", 'shortname');
	$insertsection->addField($field);
	
	$insertsection->show();
	


	$udpatesection = new UISection('Toimialan muokkaus','500px');
	$udpatesection->setDialog(true);
	$udpatesection->setMode(UIComponent::MODE_EDIT);
	$udpatesection->setUpdateAction(UIComponent::ACTION_FORWARD, 'admin/settings/updatebranch', 'branchID');
	
	$field = new UITextField("Toimiala", "name", 'name');
	$udpatesection->addField($field);
	
	$field = new UITextField("Lyhenne", "shortname", 'shortname');
	$udpatesection->addField($field);
	
	$udpatesection->show();
	
	
	
	$table = new UITableSection("Toimialat", '600px');
	$table->setOpen(true);
	$table->setTableHeaderVisible(false);
	$table->setFramesVisible(true);
	$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $udpatesection->getID(), "branchID");
	
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää uusi");
	$table->addButton($button);
	
	$column = new UISortColumn("Nimi", "name", "");
	$table->addColumn($column);
	
	$column = new UISortColumn("Lyhenne", "shortname", "");
	$table->addColumn($column);
	
	$column = new UIHiddenColumn("BranchID", "branchID", "");
	$table->addColumn($column);
	
	$table->setData($registry->branches);
	$table->show();
	
	
	


	$insertsection = new UISection('Osoitteen lisäys','500px');
	$insertsection->setDialog(true);
	$insertsection->setMode(UIComponent::MODE_INSERT);
	$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/settings/insertaddress');
	
	$field = new UISelectField("Tyyppi","addresstype","addresstype",$registry->addresstypes);
	$insertsection->addField($field);
	
	if (count($registry->offices) > 1) {
		$field = new UISelectField("Toimisto","officeID","officeID",$registry->offices, "name");
		$insertsection->addField($field);
	}

	if (count($registry->branches) > 1) {
		$field = new UISelectField("Toimiala","branchID","branchID",$registry->branches, "name");
		$insertsection->addField($field);
	}
		
	$field = new UITextField("Katuosoite", "streetaddress", 'streetaddress');
	$insertsection->addField($field);
	
	$field = new UITextField("Kunta", "city", 'city');
	$insertsection->addField($field);
	
	$field = new UITextField("Postinumero", "postalcode", 'postalcode');
	$insertsection->addField($field);
	
	$field = new UISelectField("Maa","countryID","countryID",$registry->countries, "name");
	$insertsection->addField($field);
	
	$insertsection->show();
	

	$table = new UITableSection("Osoitetiedot", '600px');
	$table->setOpen(true);
	$table->setTableHeaderVisible(true);
	$table->setFramesVisible(true);
	$table->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/settings/', 'companyID');
	
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää uusi");
	$table->addButton($button);

	if (count($registry->companies) > 1) {
		$column = new UISelectColumn("Yritys", "name", "companyID", $registry->companies);
		$table->addColumn($column);
	}
	
	$column = new UISelectColumn("Tyyppi", null, "addresstype", $registry->addresstypes);
	$table->addColumn($column);
	
	if (count($registry->offices) > 0) {
		$column = new UISelectColumn("Tsto", "shortname", "officeID", $registry->offices);
		$table->addColumn($column);		
	}
	if (count($registry->branches) > 0) {
		$column = new UISelectColumn("Ala", "shortname", "branchID", $registry->branches);
		$table->addColumn($column);		
	}
	
	$column = new UISortColumn("Osoite", "streetaddress", "");
	$table->addColumn($column);
	
	$column = new UISortColumn("Postitoimipaikka", "fullpostal", "");
	$table->addColumn($column);

	$column = new UISortColumn("Maa", "countrycode", "");
	$table->addColumn($column);
	
	$table->setData($registry->addresses);
	$table->show();
	
	
	// TODO: Jotenkin pitäisi määrittää yhteystiedon näkyvyys tiedot, ei mietitty
	// - Tämä näkyy lähinnä työntekijöille tiedoksi yhteystiedoissa.
	// - Liittynee käyttäjiin
	
	$insertsection = new UISection('Yhteystiedon lisäys','500px');
	$insertsection->setDialog(true);
	$insertsection->setMode(UIComponent::MODE_INSERT);
	$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/settings/insertaddress');

	if (count($registry->offices) > 1) {
		$field = new UISelectField("Toimisto","officeID","officeID",$registry->offices, "name");
		$insertsection->addField($field);
	}
	
	if (count($registry->branches) > 1) {
		$field = new UISelectField("Toimiala","branchID","branchID",$registry->branches, "name");
		$insertsection->addField($field);
	}
	
	$field = new UITextField("Nimi", "name", 'name');
	$insertsection->addField($field);
	
	$insertsection->show();
	
	
	
	
	

	$table = new UITableSection("Yhteystiedot", '600px');
	$table->setOpen(true);
	$table->setTableHeaderVisible(false);
	$table->setFramesVisible(true);
	$table->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/settings/', 'companyID');
	
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää uusi");
	$table->addButton($button);
	
	$nimicolumn = new UISortColumn("Nimi", "name", "");
	$table->addColumn($nimicolumn);
	
	
	$table->setData(array());
	$table->show();
	
	
?>