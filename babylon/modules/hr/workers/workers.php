<?php



// ---------------------------------------------------------------------------------------------------
// Add person dialog
// ---------------------------------------------------------------------------------------------------

$addpersondialog = new UISection('Lisää työntekijä','500px');
$addpersondialog->setDialog(true);
$addpersondialog->setMode(UIComponent::MODE_INSERT);
$addpersondialog->setInsertAction(UIComponent::ACTION_FORWARD, "hr/workers/insertworker");


$field = new UITextField("Etunimi", "Etunimi", 'Firstname');
$addpersondialog->addField($field);

$field = new UITextField("Sukunimi", "Sukunimi", 'Lastname');
$addpersondialog->addField($field);

$field = new UITextField("Puhelinnumero", "Puhelinnumero", 'Phonenumber');
$addpersondialog->addField($field);

$field = new UITextField("Email", "Email", 'Email');
$addpersondialog->addField($field);

//$field = new UISelectField("Titteli","jobtitleID","JobtitleID",$registry->jobtitles, "name");
//$addpersondialog->addField($field);

//$field = new UISelectField("Yritys","companyID","CompanyID",$registry->companies, "name");
//$addpersondialog->addField($field);

$addpersondialog->show();






$table = new UITableSection("Työntekijät","600px");
$table->setOpen(true);
$table->setFramesVisible(false);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addpersondialog->getID(), 'Lisää työntekijä');
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"hr/workers/showworker","workerID");

$column = new UISortColumn("Etunimi", "firstname", 'Firstanme');
$table->addColumn($column);

$column = new UISortColumn("Sukunimi", "lastname", 'Lastname');
$table->addColumn($column);

$column = new UISortColumn("Puhelinnumero", "phonenumber", 'Phonenumber');
$table->addColumn($column);

//$column = new UISortColumn("Email", "email", 'Email');
//$table->addColumn($column);

// $column = new UISelectColumn("Yritys", "name", "companyID", $registry->companies);
// $table->addColumn($column);

/*
$column = new UISelectColumn("Titteli", "name", "jobtitleID", $registry->jobtitles);
$table->addColumn($column);
*/

$table->setData($registry->workers);
$table->show();


?>