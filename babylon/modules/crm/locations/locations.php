<?php




$insertsection = new UISection('Työkohteen lisäys','500px');
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'crm/locations/insertlocation');

$field = new UITextField("Nimi", "name", 'name');
$insertsection->addField($field);

$field = new UITextField("Osoite", "streetaddress", 'Streetaddress');
$insertsection->addField($field);

$field = new UITextField("Postinumero", "postalcode", 'Postalcode');
$insertsection->addField($field);

$field = new UITextField("Postitoimipaikka", "city", 'City');
$insertsection->addField($field);

$field = new UISelectField("Työkohdetyyppi", "name", 'locationtypeID', $registry->locationtypes, 'name');
$insertsection->addField($field);

$insertsection->show();




$table = new UITableSection("Työkohteet","600px");
$table->setOpen(true);
$table->setFramesVisible(false);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Lisää työkohde');
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"crm/locations/showlocation","locationID");

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addpersondialog->getID(), 'LisÃ¤Ã¤ henkilÃ¤');
//$table->addButton($button);

$column = new UISortColumn("Nimi", "name", 'crm/locations/showlocations&sort=nimi');
$table->addColumn($column);


$column= new UISelectColumn("Yritys", "name", "companyID", $registry->companies);
$table->addColumn($column);

//$column = new UISortColumn("Osoite", "streetaddress", 'crm/locations/showlocations&sort=streetaddress');
//$table->addColumn($column);


$column = new UISortColumn("Paikka", "city", 'crm/locations/showlocations&sort=city');
$table->addColumn($column);


$column= new UISelectColumn("Kohde", "name", "locationtypeID", $registry->locationtypes);
$table->addColumn($column);

$table->setData($registry->locations);
$table->show();


?>

