<?php



$addaccountdialog = new UISection('Lisää kirjanpitotili','500px');
$addaccountdialog->setDialog(true);
$addaccountdialog->setMode(UIComponent::MODE_INSERT);
$addaccountdialog->setInsertAction(UIComponent::ACTION_FORWARD, "accounting/accountchart/insertaccount");

$field = new UITextField("Tilinumero", "number", 'number');
$addaccountdialog->addField($field);

$field = new UITextField("Name", "name", 'name');
$addaccountdialog->addField($field);

// tämä voisi myös tulla parentista, mutta antaa toistaiseksi olla kun ei ole useimmissa asetettu
$field = new UISelectField("Tilityyppi", "accounttypeID", 'accounttypeID', $registry->accounttypes, 'name');
$addaccountdialog->addField($field);

$field = new UISelectField("Ylätili", "parentID", 'parentID', $registry->allaccounts, 'fullname');
$field->setPredictive(true);
$addaccountdialog->addField($field);

$addaccountdialog->show();




$section = new UITreeSection("Tilikartta", "600px");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addaccountdialog->getID(), 'Lisää tili');
$section->addButton($button);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'accounting/accountchart/showaccount', 'accountID', UIComponent::ACTION_FORWARD);


//$column = new UIColumn("#", "accountID");
//$section->addColumn($column);

$column = new UIColumn("Nimi", "name");
$section->addColumn($column);

$column = new UIColumn("Tilinumero", "number");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "accountID", "accounting/accountchart/moveaccount");
$column->setIcon("fa fa-chevron-down");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "accountID", "accounting/accountchart/moveaccount");
$column->setIcon("fa fa-chevron-up");
$section->addColumn($column);

$section->setData($registry->accounts);
$section->show();





?>