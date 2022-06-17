<?php


echo "<a href='".getUrl('admin/doctemplates/showdoctemplates')."'>Palaa tauluun</a><br>";
echo "<h1></h1>";

$insertsection = new UIInsertSection("Uuden dokumenttipohjaelementti lisays");
$insertsection->setInsertAction('admin/doctemplates/inserttemplate', false);

foreach($registry->columns as $index => $column) {
	if (($column->type != 2) && ($column->type != 1)) {
		$field = UIField::createUIField($column,null);
		
		$insertsection->addField($field);
	}
	
	if ($column->type == 1) {
		echo "<br>referencetable name muutettu referencetableID";
		exit();
		
		$referencetableID = $column->referencetableID;
		echo "<br>Referencetable - " . $column->name . " - " . $column->type . " - " . $referencetableID;
		$field = UIField::createUIField($column, $registry->$referencetable);
		$insertsection->addField($field);
	}
	
	
}

$insertsection->show();



?>