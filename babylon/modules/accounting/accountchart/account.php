<?php

echo "<a href='".getUrl('accounting/accountchart/showaccountchart')."'>Palaa tilikarttaan</a><br>";
echo "<br>";
//echo "<h1>" . $registry->company->name . "</h1>";

$section = new UISection("Tilin tiedot");
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'accounting/accountchart/updateaccount', 'accountID');

$field = new UITextField("Tilinumero", "number", 'number');
$section->addField($field);

$field = new UITextField("Name", "name", 'name');
$section->addField($field);

// tämä voisi myös tulla parentista, mutta antaa toistaiseksi olla kun ei ole useimmissa asetettu
$field = new UISelectField("Tilityyppi", "accounttypeID", 'accounttypeID', $registry->accounttypes, 'name');	
$section->addField($field);

$field = new UISelectField("Ylätili", "parentID", 'parentID', $registry->accounts, 'fullname');
$field->setPredictable(true);
$section->addField($field);

$section->setData($registry->account);

$section->show();







$insertexpendituredialog = new UISection("Kustannuserän lisäys");
$insertexpendituredialog->setDialog(true);
$insertexpendituredialog->setMode(UIComponent::MODE_INSERT);
$insertexpendituredialog->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/accountchart/insertexpenditure&accountID=' . $registry->account->accountID);

$namefield = new UITextField("Name", "name", 'name');
$insertexpendituredialog->addField($namefield);

$insertexpendituredialog->show();





$expenditurestable = new UITableSection("Kustannuserät","600px");
$expenditurestable->setOpen(true);
$expenditurestable->setFramesVisible(true);

//$expenditurestable->setShowSumRow(true);


$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertexpendituredialog->getID(), "Lisää kustannuserä");
$expenditurestable->addButton($button);

$column = new UISortColumn("#", "expenditureID");
$expenditurestable->addColumn($column);

$column = new UISelectColumn("Kustannuspaikka", "name", "costpoolID", $registry->costpools);
$expenditurestable->addColumn($column);

$column = new UISortColumn("Nimi", "name", "name");
$expenditurestable->addColumn($column);

$expenditurestable->setData($registry->expenditures);
$expenditurestable->show();



?>