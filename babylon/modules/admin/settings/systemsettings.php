<?php
	

	$section = new UISection("Järjestelmän yleisasetukset");
	$section->setOpen(true);
	$section->editable(true);
	$section->setUpdateAction(UIComponent::ACTION_FORWARD, 'admin/settings/updatesettings', 'systemID');
	
	$field = new UITextField("Järjestelmänimi", "systemname", "systemname");
	$section->addField($field);

	// TODO
	//$field = new UITextField("Ulkoasuteema", "", "");
	//$section->addField($field);
	
	// TODO
	//$field = new UITextField("Oletuskieli", "", "");
	//$section->addField($field);
	
	$section->setData($registry->generalsettings);
	$section->show();

	
	if (count($registry->companies) == 0) {
		// tähän lisäysikkuna
		//echo "<br>Ei companytietoja";
		
		$section = new UISection("Yritystiedot");
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
		
		$section = new UISection("Yritystiedot");
		$section->setOpen(true);
		$section->editable(true);
		$section->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/settings/updatecompany', 'companyID');
		
		
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
		echo "<br>useita compnaytietoja";		
		// näyttään tiedot listana		
	}
	
	
	// TODO: Yhteystiedot kenttä: Osoitetiedot, toimipiste, tehdas, postiosoite, tms.
	
	// TODO: lisää toimipisteet, tehtaat, työkohteet tms. tänne tarvittaessa. Toimialat
	
	// TODO: Toimialat
	
?>