<?php



// ---------------------------------------------------------------------------------------------------
// Add person dialog
// ---------------------------------------------------------------------------------------------------

$addpersondialog = new UISection('Lisää henkilä','500px');
$addpersondialog->setDialog(true);
$addpersondialog->setMode(UIComponent::MODE_INSERT);
$addpersondialog->setInsertAction(UIComponent::ACTION_FORWARD, "crm/clients/insertclient");


$field = new UITextField("Etunimi", "Etunimi", 'Firstname');
$addpersondialog->addField($field);

$field = new UITextField("Sukunimi", "Sukunimi", 'Familyname');
$addpersondialog->addField($field);

$field = new UITextField("Puhelinnumero", "Puhelinnumero", 'Phonenumber');
$addpersondialog->addField($field);

$field = new UISelectField("Titteli","jobtitleID","JobtitleID",$registry->jobtitles, "title");
$addpersondialog->addField($field);

$field = new UISelectField("Yritys","companyID","CompanyID",$registry->companies, "name");
$addpersondialog->addField($field);

$addpersondialog->show();






$table = new UITableSection("Asiakkaat","600px");
$table->setOpen(true);
$table->setFramesVisible(false);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addpersondialog->getID(), 'Lisää henkilä');
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"crm/clients/showclient","clientpersonID");

$column = new UISortColumn("Etunimi", "firstname", 'Firstanme');
$table->addColumn($column);

$column = new UISortColumn("Sukunimi", "familyname", 'Familyname');
$table->addColumn($column);

$column = new UISortColumn("Puhelinnumero", "phonenumber", 'Phonenumber');
$table->addColumn($column);

$column= new UISelectColumn("Titteli", "title", "jobtitleID", $registry->jobtitles);
$table->addColumn($column);

$column= new UISelectColumn("Yritys", "name", "companyID", $registry->companies);
$table->addColumn($column);

$table->setData($registry->persons);
$table->show();


?>