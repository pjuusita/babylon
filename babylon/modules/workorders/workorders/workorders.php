<?php




$insertsection = new UISection('Toimeksiannon lisäys','500px');
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'workorders/workorders/insertorder');

$field = new UISelectField("Yritys", "name", 'companyID', $registry->companies, 'name');
$insertsection->addField($field);

$field = new UISelectField("Työkohde", "name", 'locationID', $registry->locations, 'name');
$insertsection->addField($field);

$field = new UISelectField("Asiakas", "fullname", 'clientID', $registry->clients, 'fullname');
$insertsection->addField($field);

$field = new UIDateField("Aloitus", "startdate", 'startdate');
$insertsection->addField($field);

$insertsection->show();




$table = new UITableSection("Toimeksiannot","600px");
$table->setOpen(true);
$table->setFramesVisible(false);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Lisää toimeksianto');
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"workorders/workorders/showworkorder","orderID");

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addpersondialog->getID(), 'LisÃ¤Ã¤ henkilÃ¤');
//$table->addButton($button);

$column = new UISortColumn("Aloitus", "startdate", 'crm/companies/showcompanies&sort=nimi');
$table->addColumn($column);

$column= new UISelectColumn("Yritys", "name", "companyID", $registry->companies);
$table->addColumn($column);

$column= new UISelectColumn("Työkohde", "name", "locationID", $registry->locations);
$table->addColumn($column);

$table->setData($registry->orders);
$table->show();


?>

