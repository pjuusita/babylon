<?php



// ---------------------------------------------------------------------------------------------------
// Add person dialog
// ---------------------------------------------------------------------------------------------------


$clienttypes = array();
$clienttypes[1] = "Yrityksen henkilö";
$clienttypes[2] = "Yksityisasiakas";


$addpersondialog = new UISection('Lisää henkilö','500px');
$addpersondialog->setDialog(true);
$addpersondialog->setMode(UIComponent::MODE_INSERT);
$addpersondialog->setInsertAction(UIComponent::ACTION_FORWARD, "crm/clients/insertclient");

$clienttypefield = new UISelectField("Henkilötyyppi", "clientpersontypeID", "clientpersontypeID", $clienttypes);
$clienttypefield->setOnChange("typechanged()");
$addpersondialog->addField($clienttypefield);

$firstnamefield = new UITextField("Etunimi", "Etunimi", 'Firstname');
$firstnamefield->setDisabled(true);
$addpersondialog->addField($firstnamefield);

$lastnamefield = new UITextField("Sukunimi", "Sukunimi", 'Lastname');
$lastnamefield->setDisabled(true);
$addpersondialog->addField($lastnamefield);

$phonenumberfield = new UITextField("Puhelin", "Puhelinnumero", 'Phonenumber');
$phonenumberfield->setDisabled(true);
$addpersondialog->addField($phonenumberfield);

$emailfield = new UITextField("Email", "Email", 'Email');
$emailfield->setDisabled(true);
$addpersondialog->addField($emailfield);

$companyfield = new UISelectField("Yritys","companyID","CompanyID",$registry->companies, "name");
$companyfield->setDisabled(true);
$addpersondialog->addField($companyfield);

$titlefield = new UISelectField("Titteli","jobtitleID","JobtitleID",$registry->jobtitles, "name");
$titlefield->setDisabled(true);

if (count($registry->jobtitles) > 0) {
	$titlefield = new UISelectField("Titteli","jobtitleID","JobtitleID",$registry->jobtitles, "name");
	$titlefield->setDisabled(true);
	$addpersondialog->addField($titlefield);
}


$addpersondialog->show();




echo "<script>";
echo "	function typechanged() {";

echo "		console.log('typechanged');";
echo "	 	var clienttypefield = '#".$clienttypefield->getEditFieldID()."';";
echo "	 	var firstnamefield = '#".$firstnamefield->getEditFieldID()."';";
echo "	 	var lastnamefield = '#".$lastnamefield->getEditFieldID()."';";
echo "	 	var phonenumberfield = '#".$phonenumberfield->getEditFieldID()."';";
echo "	 	var emailfield = '#".$emailfield->getEditFieldID()."';";
echo "	 	var titlefield = '#".$titlefield->getEditFieldID()."';";
echo "	 	var companyfield = '#".$companyfield->getEditFieldID()."';";

echo "	 		var type = $(clienttypefield).val();";
echo "			console.log('type is '+type);";

/*
echo "			$(clienttypefield).removeAttr('disabled');";
echo "			$(clienttypefield).removeClass('uitextfield-disabled');";
echo "			$(clienttypefield).addClass('uitextfield');";

echo "			$(firstnamefield).removeAttr('disabled');";
echo "			$(firstnamefield).removeClass('uitextfield-disabled');";
echo "			$(firstnamefield).addClass('uitextfield');";

echo "			$(maxvaluefieldID).removeAttr('disabled');";
echo "			$(maxvaluefieldID).removeClass('uitextfield-disabled');";
echo "			$(maxvaluefieldID).addClass('uitextfield');";

echo "			$(defaultvaluefieldID).removeAttr('disabled');";
echo "			$(defaultvaluefieldID).removeClass('uitextfield-disabled');";
echo "			$(defaultvaluefieldID).addClass('uitextfield');";
*/

echo "			switch(type) {";

echo "				case '1':";			// yrityksen henkilö

echo "					$(firstnamefield).removeAttr('disabled');";
echo "					$(firstnamefield).addClass('uitextfield');";
echo "					$(firstnamefield).removeClass('uitextfield-disabled');";

echo "					$(lastnamefield).removeAttr('disabled');";
echo "					$(lastnamefield).addClass('uitextfield');";
echo "					$(lastnamefield).removeClass('uitextfield-disabled');";

echo "					$(phonenumberfield).removeAttr('disabled');";
echo "					$(phonenumberfield).addClass('uitextfield');";
echo "					$(phonenumberfield).removeClass('uitextfield-disabled');";

echo "					$(emailfield).removeAttr('disabled');";
echo "					$(emailfield).addClass('uitextfield');";
echo "					$(emailfield).removeClass('uitextfield-disabled');";

echo "					$(titlefield).removeAttr('disabled');";
echo "					$(titlefield).addClass('uitextfield');";
echo "					$(titlefield).removeClass('uitextfield-disabled');";

echo "					$(companyfield).removeAttr('disabled');";
echo "					$(companyfield).addClass('uitextfield');";
echo "					$(companyfield).removeClass('uitextfield-disabled');";

echo "					break;";

echo "				case '2':";			// yksityishenkilö


echo "					$(firstnamefield).removeAttr('disabled');";
echo "					$(firstnamefield).addClass('uitextfield');";
echo "					$(firstnamefield).removeClass('uitextfield-disabled');";

echo "					$(lastnamefield).removeAttr('disabled');";
echo "					$(lastnamefield).addClass('uitextfield');";
echo "					$(lastnamefield).removeClass('uitextfield-disabled');";

echo "					$(phonenumberfield).removeAttr('disabled');";
echo "					$(phonenumberfield).addClass('uitextfield');";
echo "					$(phonenumberfield).removeClass('uitextfield-disabled');";

echo "					$(emailfield).removeAttr('disabled');";
echo "					$(emailfield).addClass('uitextfield');";
echo "					$(emailfield).removeClass('uitextfield-disabled');";

echo "					$(titlefield).attr('disabled', 'disabled');";
echo "					$(titlefield).removeClass('uitextfield');";
echo "					$(titlefield).addClass('uitextfield-disabled');";

echo "					$(companyfield).attr('disabled', 'disabled');";
echo "					$(companyfield).removeClass('uitextfield');";
echo "					$(companyfield).addClass('uitextfield-disabled');";

echo "					break;";


echo "				default:";			// none selected


echo "					$(firstnamefield).attr('disabled', 'disabled');";
echo "					$(firstnamefield).removeClass('uitextfield');";
echo "					$(firstnamefield).addClass('uitextfield-disabled');";

echo "					$(lastnamefield).attr('disabled', 'disabled');";
echo "					$(lastnamefield).removeClass('uitextfield');";
echo "					$(lastnamefield).addClass('uitextfield-disabled');";

echo "					$(phonenumberfield).attr('disabled', 'disabled');";
echo "					$(phonenumberfield).removeClass('uitextfield');";
echo "					$(phonenumberfield).addClass('uitextfield-disabled');";

echo "					$(emailfield).attr('disabled', 'disabled');";
echo "					$(emailfield).removeClass('uitextfield');";
echo "					$(emailfield).addClass('uitextfield-disabled');";

echo "					$(titlefield).attr('disabled', 'disabled');";
echo "					$(titlefield).removeClass('uitextfield');";
echo "					$(titlefield).addClass('uitextfield-disabled');";

echo "					$(companyfield).attr('disabled', 'disabled');";
echo "					$(companyfield).removeClass('uitextfield');";
echo "					$(companyfield).addClass('uitextfield-disabled');";

echo "					break;";
echo "			}";

echo "	}";
echo "</script>";



$table = new UITableSection("Henkilöt","600px");
$table->setOpen(true);
$table->setFramesVisible(false);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addpersondialog->getID(), 'Lisää henkilö');
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"crm/clients/showclient","clientID");

$companies = $registry->companies;
$privateclient = new Row();
$privateclient->companyID = 0;
$privateclient->name = "Yksityisasiakas";
$companies[0] = $privateclient;

$column = new UISelectColumn("Yritys", "name", "companyID", $companies);
$table->addColumn($column);

$column = new UISortColumn("Nimi", "fullname", 'fullname');
$table->addColumn($column);

$column = new UISortColumn("Puhelinnumero", "phonenumber", 'Phonenumber');
$table->addColumn($column);

//$column = new UISortColumn("Email", "email", 'Email');
//$table->addColumn($column);

$column = new UISelectColumn("Titteli", "name", "jobtitleID", $registry->jobtitles);
$table->addColumn($column);




$table->setData($registry->clients);
$table->show();


?>