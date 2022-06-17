<?php


echo "<table>";
echo "	<tr>";
echo "		<td>";
echo "			<select>";
echo "				<option>Paiva</option>";
echo "				<option>Viikko</option>";
echo "				<option>Kuukausi</option>";
echo "				<option>Vuosineljannes</option>";
echo "				<option>Vuosi</option>";
echo "			</select>";
echo "		</td>;";
echo "			<div id=paivadiv>";
echo "			</div>";

echo "			<div id=viikkodiv>";
echo "				<select>";
for($index = 0;i<$index<52;$index++) {
	echo "					<option>vk " . $index . "</option>";
}
echo "				</select>";
echo "				<select>";
for($index = 2015;i<$index<2020;$index++) {
	echo "					<option> " . $index . "</option>";
}
echo "				</select>";
echo "			</div>";

echo "			<div id=kuukaisidiv>";
echo "				<select>";
for($index = 0;i<$index<52;$index++) {
	echo "					<option>vk " . $index . "</option>";
}
echo "				</select>";
echo "				<select>";
for($index = 2015;i<$index<2020;$index++) {
	echo "					<option> " . $index . "</option>";
}
echo "				</select>";
echo "			</div>";

echo "			<div id=vuosineljannesdiv>";
echo "			</div>";

echo "			<div id=vuosidiv>";
echo "			</div>";

echo "		<td>";
echo "		</td>;";
echo "		<td>";
echo "		</td>;";
echo "		<td>";
echo "		</td>;";
echo "	</tr>";
echo "</table>";



// Korvattu UI Table --> UITableSection
$table = new UITableSection("Tilitapahtumat");

$receptdatecolumn = new UISortColumn("Paivays", "entrydate", "accounting/journal/showjournal&sort=entrydate");
$receptdatecolumn->setColumnType(Column::COLUMNTYPE_DATE);
$receptdatecolumn->setLink('accounting/journal/showentry','entryID');

$receiptnumbercolumn = new UISortColumn("Tositeumero", "receiptnumber", "accounting/journal/showjournal&sort=receiptnumber");
$receiptnumbercolumn->setColumnType(Column::COLUMNTYPE_STRING);

$suppliercolumn = new UISortColumn("Tositeumero", "receiptnumber", "accounting/journal/showjournal&sort=receiptnumber");
$suppliercolumn->setColumnType(Column::COLUMNTYPE_STRING);

$suppliercolumn = new UISelectColumn("Saaja", "supplierID", $this->registry->suppliers, "accounting/journal/showjournal");
$suppliercolumn->setColumnType(Column::COLUMNTYPE_INTEGER);


$table->addColumn($receptdatecolumn);
$table->addColumn($receiptnumbercolumn);
$table->addColumn($suppliercolumn);


$table->setData($this->registry->journalentries);
$table->show();





// tasta pitaa tehda compactlist tyyppinen ratkaisu

$table = new UITableLevel2("Tilitapahtumat", "800px");

$receptdatecolumn = new UISortColumn("Paivays", "entrydate", "accounting/journal/showjournal&sort=entrydate");
$receptdatecolumn->setColumnType(Column::COLUMNTYPE_DATE);
$receptdatecolumn->setLink('accounting/journal/showentry','entryID');

$receiptnumbercolumn = new UISortColumn("Tositeumero", "receiptnumber", "accounting/journal/showjournal&sort=receiptnumber");
$receiptnumbercolumn->setColumnType(Column::COLUMNTYPE_STRING);

$suppliercolumn = new UISortColumn("Tositeumero", "receiptnumber", "accounting/journal/showjournal&sort=receiptnumber");
$suppliercolumn->setColumnType(Column::COLUMNTYPE_STRING);

$suppliercolumn = new UISelectColumn("Saaja", "supplierID", $this->registry->suppliers, "accounting/journal/showjournal");
$suppliercolumn->setColumnType(Column::COLUMNTYPE_INTEGER);


$table->addColumn($receptdatecolumn,1);
$table->addColumn($receiptnumbercolumn,1);
$table->addColumn($suppliercolumn,1);



$accountnumbercolumn = new UISelectColumn("Tilinro", "accountID", $this->registry->accountnumbers, "", 100);
$accountnamecolumn = new UISelectColumn("Tilinnimi", "accountID", $this->registry->accountnames,400);
$debetcolumn = new UISortColumn("Debet","debet","Debet",100);
$creditcolumn = new UISortColumn("Credit","credit","Credit",100);

$table->addColumn($accountnumbercolumn,2);
$table->addColumn($accountnamecolumn,2);
$table->addColumn($debetcolumn,2);
$table->addColumn($creditcolumn,2);

//$table->addButton("Lisaa uusi",  "accounting/journal/shownewentry");

$table->setData($this->registry->journalentries);


$table->show();


foreach($this->registry->journalentries as $index => $entry) {
	//echo "<br>Entry - " . $index;
	//echo "<br>childcount - " . count($entry->getChildren());
}




?>