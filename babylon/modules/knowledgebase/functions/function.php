<?php


echo "<h1>" . $registry->function->name. "</h1>";

$section = new UISection("Function");
$section->setOpen(true);
$section->editable(true);


$section->setUpdateAction(UIComponent::ACTION_FORWARD,'knowledgebase/functions/updatefunction', 'functionID');

$bookIDfield = new UIFixedTextField("BookID", $registry->function->functionID);
$section->addField($bookIDfield);

$field = new UITextField("Nimi", "name", 'name');
$section->addField($field);

$field = new UISelectField("Parent", "parentID", "parentID", $this->registry->functions, 'name');
//$field->setPredictable(true);
$section->addField($field);

$field = new UITextAreaField("Description", "description", 'description');
$section->addField($field);

$section->setData($registry->function);
$section->show();




$section = new UITableSection("Propositions", "600px");		// labels
$section->setOpen(true);
$section->setFramesVisible(true);

//$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'books/books/removelabel&bookID=' . $registry->book->bookID, 'labelID');

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $labelinsertsection->getID(), "Lisää");
//$section->addButton($button);

$column = new UISortColumn("#", "propositionID", "", null, "10%");
$section->addColumn($column);

//$column = new UISelectColumn("Name", "name", "labelID", $registry->labels);
//$section->addColumn($column);

$section->setData($registry->propositions);
$section->show();




// Entailments tarkoittaa kahta asiaa...
// (1) kun tähän funktioon syötetään uusi ilmentymä, niin tietokantaan voidaan periaatteessa syöttää
//     myös entailmenttin mukainen propositio, mikäli sellaista ei siellä jo ole. 
//			Esim. 	funktio: isLocatedIn	- Helsinki on suomessa		--> Suomi on lokaatio
//	   tai vaihtoehtoisesti pitäisi tarkistaa onko suomi lokaatio ennenkuin ko. propositio voidaan lisätä
// (2) Jos entailmentin mukainen tieto on jo, niin voiko näistä päätellä tämän faktan olemassaolon?
//	   ei ehkä aina, mutta joskus kyllä. Esim. Helsinki on pääkaupunki + Helsinki on suomessa -->
//	   helsinki on suomen pääkaupunki. Vastaavasti jos Helsinki on suomen pääkaupunki, niin entails
//	   johtaa siihen, että helsinki on suomessa ja helsinki on pääkaupunki. Tämä ei mielestäni kuitenkaan
//	   toimi tuohon isLocatedIn tyyppiseen, eli siitä, että suomi on lokaatio ei seuraa se, että helsinki
//	   on suomessa. Nämä kaksi eri entailment tyyppiä pitäisi erottaa jotenkin toisistaan.

$section = new UITableSection("Entailments", "600px");		
$section->setOpen(true);
$section->setFramesVisible(true);

//$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'books/books/removelabel&bookID=' . $registry->book->bookID, 'labelID');

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $labelinsertsection->getID(), "Lisää");
//$section->addButton($button);

$column = new UISortColumn("#", "propositionID", "", null, "10%");
$section->addColumn($column);

//$column = new UISelectColumn("Name", "name", "labelID", $registry->labels);
//$section->addColumn($column);

$section->setData($registry->propositions);
$section->show();






$section = new UISection("Hallinta");
$section->editable(false);

$button = new UIButton(UIComponent::ACTION_FORWARD, "knowledgebase/functions/removefunction&id=" . $registry->function->functionID, "Poista");
$section->addButton($button);

$section->show();



?>