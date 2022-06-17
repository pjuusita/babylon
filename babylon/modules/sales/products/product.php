<?php




echo "<a href='".getUrl('sales/products/showproducts')."'>Palaa tuotelistaan</a><br>";
echo "<h1>" . $registry->product->name . "</h1>";



$infosection = new UISection('Tuotteen perustiedot','550px');


$section = new UISection("Perustiedot");
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'sales/products/updateproduct', 'productID');

$field = new UITextField("Nimi", "name", 'name');
$section->addField($field);


$field = new UISelectField("Tyyppi","service","service",$registry->producttypes);
$section->addField($field);


if ($this->registry->productnumberused == 1) {
	$field = new UITextField("Productnumber", "productnumber", 'productnumber');
	$section->addField($field);
}

$field = new UISelectField("Kirjanpitotili","accountID","accountID",$registry->accounts, "fullname");
$section->addField($field);

$field = new UISelectField("Yksikö","unitID","unitID",$registry->units, "name");
$section->addField($field);

//$field = new UIDateField("Alkaa", "startdate", 'startdate');
//$insertsection->addField($field);

//$field = new UIDateField("Loppuu", "enddate", 'enddate');
//$insertsection->addField($field);



if (count($registry->productgroups) > 0) {
	$field = new UISelectField("Tuoteryhmä", "productgroupID", 'productgroupID', $registry->productgroups, 'name');
	$section->addField($field);
}


$column = new UISelectField("ALV", "vatID", "vatID", $registry->vats, "name");
$section->addField($column);

$section->setData($registry->product);
$section->show();



$section = new UISection("Hintatiedot");
$section->show();



$section = new UISection("Hallinta");
$section->show();



?>