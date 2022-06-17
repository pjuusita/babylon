<?php


	
	$insertStageDialog = new UISection("Dokumentin lisäys");
	$insertStageDialog->setDialog(true);
	$insertStageDialog->setMode(UIComponent::MODE_INSERT);
	$insertStageDialog->setSaveAction(UIComponent::ACTION_FORWARD, 'wiki/documents/insertdocument&parentID=0');
	
	$nimifield = new UITextField("Otsikko", "name", 'name');
	$insertStageDialog->addField($nimifield);

	$nimifield = new UITextField("Path", "path", 'path');
	$insertStageDialog->addField($nimifield);
	
	$insertStageDialog->show();
	

	echo "<h1>Dokumentit</h2>";

	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertStageDialog->getID(), "Lisää");
	$button->show();
	
	echo "<ul>";
	foreach($this->registry->documents as $index => $document) {
		echo "<li><a href='" . getUrl("wiki/documents/showdocument") . "&documentID=" . $document->documentID . "'>" . $document->name . "</a></li>";
	}
	echo "</ul>";
	
?>