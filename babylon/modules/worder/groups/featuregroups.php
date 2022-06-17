<?php


$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->languageID, $registry->languages, "worder/groups/showfeaturegroups", "Kieli", "languageID", "name");
$filterbox->addSelectFilter($this->registry->featureID, $this->registry->features, "worder/groups/showfeaturegroups", "Feature", "featureID", "name");
$filterbox->setEmptySelect(false);

echo "<table style='width:600px;'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
//$paginator->show();
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$filterbox->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";



$table = new UITableSection("Sanat", "600px");
//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
//$table->addButton($button);
//$table->setTableHeaderVisible(false);

$table->setLineAction(UIComponent::ACTION_FORWARD,"worder/words/showwords","wordID");

$column = new UISortColumn("#", "wordID", "worder/lessons/showlesson", null, "10%");
$table->addColumn($column);

$column = new UISortColumn("Lemma", "lemma", "worder/lessons/showlesson&sort=nimi", null, "25%");
$table->addColumn($column);

$table->setData($registry->words);
$table->show();




?>