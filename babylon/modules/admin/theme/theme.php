<?php
	
	echo "<br>ThemeID - " . $registry->theme->themeID;

	$themeSection = new UISection("Teema");
	$themeSection->setData($registry->theme);
	$themeSection->setUpdateAction(UIComponent::ACTION_FORWARD,"admin/thememanager/updatetheme","themeID");
	
	$nimiField = new UITextField("Nimi","name","Name");
	$themeSection->addField($nimiField);
	
	$themeSection->setOpen(true);
	$themeSection->show();	
	
	// Korvattu UI Table --> UITableSection
	$themeitemtable = new UITableSection("", "100%");
	$themeitemtable->setData($registry->themeitems);
	$themeitemtable->innerTable(true);
	
	
	$nameColumn = new UIColumn("Itemin nimi", "itemname");
	$nameColumn->setLink("admin/thememanager/showthemeitemvalue&themeid=" . $registry->theme->themeID, "themeitemID");
	//$nameColumn = new UIArrayColumn("Itemi", "themeitemID", $this->registry->themeitems, "admin/cssmanager/showitemvalue");
	$valueColumn = new UIArrayColumn("Arvo", "themeitemID", $this->registry->themeitemvalues);
	//$nameLinkString = 'admin/cssmanager/showcssdefinition&cssclassid='.$registry->cssclass->cssclassID;
	//$nameColumn->setLink($nameLinkString,'cssdefinitionID');
	
	$themeitemtable->addColumn($nameColumn);
	$themeitemtable->addColumn($valueColumn);
	//$themeitemtable->show();
	
	// 1.12.2019 Tämä hasAccess on tainnut vanhentua, ei muistikuvaa miten tämä pitäisi toimia
	if (hasAccess("system.thememanager.viewglobalcolors")) {
		$itemsSection = new UISection("Varit");
		foreach($registry->themeitems as $themeitemID => $themeitem) {
			if ($themeitem->publicvalue ==  1) {
				//$value = $registry->themeitemvalues[$themeitemID];
				
				// TODO: 22.10.21 UIColorField-konstruktori lienee vanhentunut
				$itemField = new UIColorField($themeitem->itemname,"name","Name");
				$itemsSection->addField($itemField);
			}
		}
		$itemsSection->setOpen(true);
		$itemsSection->show();
	}


	// 1.12.2019 Tämä hasAccess on tainnut vanhentua, ei muistikuvaa miten tämä pitäisi toimia
	if (hasAccess("system.thememanager.viewstaticcolors")) {
		$itemsSection = new UISection("Varit (static)");
		foreach($registry->themeitems as $themeitemID => $themeitem) {
			if ($themeitem->publicvalue ==  0) {
				//$value = $registry->themeitemvalues[$themeitemID];
				// TODO: 22.10.21 UIColorField-konstruktori lienee vanhentunut
				$itemField = new UIColorField($themeitem->itemname,"name","Name");
				$itemsSection->addField($itemField);
			}
		}
		$itemsSection->setOpen(true);
		$itemsSection->show();
	}
	
	
	
		
	
?>