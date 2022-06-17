<?php

$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($registry->functionID, $registry->functions, "knowledgebase/propositions/showpropositions", "", "functionID", "name");

echo "<table style='width:600px;'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$filterbox->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";



$insertsection = new UISection("Proposition lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'books/books/insertbook');

//$nimifield = new UITextField("Name", "name", 'name');
//$insertsection->addField($nimifield);

$insertsection->setCustomContent('conceptSearchDiv');

$insertsection->show();





function conceptSearchDiv() {

	global $registry;

	echo "	<table style='width:100%'>";
	
	echo "		<tr>";
	echo "			<td>Function</td>";
	echo "			<td style='padding-right:5px;'>";
	echo "				<select class=uitextfield  id=functionselect type='text' style='width:100%;'>";
	foreach($registry->functions as $functionID => $function) {
		echo "<option selected='selected' value='" . $functionID . "'>" . $function->name . "</option>";
	}
	echo " 				</select>";
	echo " 			</td>";
	echo "			<td style='padding-right:5px'>";
	echo "			</td>";
	echo "		</tr>";
	
	echo "		<tr>";
	echo "			<td>Param1</td>";
	echo "			<td style='padding-right:5px;'>";
	echo "				<input class=uitextfield  id=param1selectedstrfield type='text' style='width:100%;background-color:white;' type='text' disabled value=''>";
	echo " 			</td>";
	echo "			<td style='padding-right:5px'>";
	echo "				<input class=uitextfield  id=param1selectedfield type='text' style='width:70px;background-color:white;' type='text' disabled value=''>";
	echo "			</td>";
	echo "		</tr>";
	
	
	echo "		<tr>";
	echo "			<td>Param1</td>";
	echo "			<td style='padding-right:5px;'>";
	echo "				<input class=uitextfield  id=param1searchfield type='text' style='width:100%;' type='text' value=''>";
	echo " 			</td>";
	echo "			<td style='padding-right:5px'>";
	echo "				<div>";
	echo "					<button  class=section-button  onclick='param1searchbuttonpressed()'>Etsi</button>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	
	echo "		<tr>";
	echo "			<td colspan=2>";
	
	echo "				<div id=param1loadingdiv style='display:none;height:100%;width:100%;'>";
	echo "					<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
	echo "				</div>";
	
	echo "				<div id=param1loadeddiv style='display:none;height:100%;width:100%;overflow:hidden'>";
	echo "					<div style='overflow-y:scroll;max-height:100px;'>";
	echo "					<table id=param1resulttable style='width:100%;height:50px;'>";
	echo "						<tr><td>Empty</td></tr>";
	echo "					</table>";
	echo "					</div>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	

	echo "		<tr>";
	echo "			<td>Param1</td>";
	echo "			<td style='padding-right:5px;'>";
	echo "				<input class=uitextfield  id=param2selectedstrfield type='text' style='width:100%;background-color:white;' type='text' disabled value=''>";
	echo " 			</td>";
	echo "			<td style='padding-right:5px'>";
	echo "				<input class=uitextfield  id=param2selectedfield type='text' style='width:70px;background-color:white;' type='text' disabled value=''>";
	echo "			</td>";
	echo "		</tr>";
	
	echo "		<tr>";
	echo "			<td>Param2</td>";
	echo "			<td style='padding-right:5px;'>";
	echo "				<input class=uitextfield  id=param2searchfield type='text' style='width:100%;' type='text' value=''>";
	echo " 			</td>";
	echo "			<td style='padding-right:5px'>";
	echo "				<div>";
	echo "					<button  class=section-button  onclick='param2searchbuttonpressed()'>Etsi</button>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	
	
	echo "		<tr>";
	echo "			<td colspan=2>";
	
	echo "				<div id=param2loadingdiv style='display:none;height:100%;width:100%;'>";
	echo "					<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
	echo "				</div>";
	
	echo "				<div id=param2loadeddiv style='display:none;height:100%;width:100%;overflow:hidden'>";
	echo "					<div style='overflow-y:scroll;max-height:100px;'>";
	echo "					<table id=param2resulttable style='width:100%;height:50px;'>";
	echo "						<tr><td>Empty</td></tr>";
	echo "					</table>";
	echo "					</div>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	
	
	echo "		<tr>";
	
	echo "			<td colspan=2 style='padding-right:5px;'>";
	echo " 			</td>";
	
	echo "			<td style='padding-right:5px'>";
	echo "				<div>";
	echo "					<button  class=section-button  onclick='insertpressed()'>Lisää</button>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	
	
	echo "	</table>";
	
	echo "	<script>";
	echo "		$('#param1searchfield').keypress(function (e) {";
	echo "			if (e.which == 13) {";
	echo "				param1searchbuttonpressed();";
	echo "			};";
	echo "		})";
	echo "	</script>";
	
	
	echo "	<script>";
	echo "		$('#param2searchfield').keypress(function (e) {";
	echo "			if (e.which == 13) {";
	echo "				param2searchbuttonpressed();";
	echo "			};";
	echo "		})";
	echo "	</script>";

	
	echo "	<script>";
	echo "		function selectparam1(conceptID, name) {";
	echo "			$('#param1selectedstrfield').val(name);";
	echo "			$('#param1selectedfield').val(conceptID);";
	echo "		}";
	echo "	</script>";

	echo "	<script>";
	echo "		function selectparam2(conceptID, name) {";
	echo "			$('#param2selectedstrfield').val(name);";
	echo "			$('#param2selectedfield').val(conceptID);";
	echo "		}";
	echo "	</script>";
	

	echo "	<script>";
	echo "		function insertpressed() {";
	echo "			console.log('insertpressed');";
	
	echo "			var functionID = $('#functionselect').val();";
	echo "			var param1 = $('#param1selectedfield').val();";
	echo "			var param2 = $('#param2selectedfield').val();";
	
	echo "			window.location = '" . getUrl("knowledgebase/propositions/insertproposition") . "&functionID='+functionID+'&param1='+param1+'&param2='+param2;";
	echo "		}";
	echo "	</script>";
	
	
	echo "	<script>";
	echo "		function param1searchbuttonpressed() {";
	//echo "			console.log('search button pressed');";
	echo "			var searh = $('#param1searchfield').val();";
	echo "			if (searh == '') {";
	echo "				alert('ei saa olla tyhjä 2');";
	echo "			}";
	echo "			$('#param1loadingdiv').show();";
	echo "			$('#param1loadeddiv').hide();";
	echo "			var languageID = 2;";
	//echo "			console.log('languageid -'+languageID+'-');";
	echo "			console.log('" . getUrl('worder/words/searchconcepts') . "&search='+searh);";

	echo "			$.getJSON('" . getUrl('worder/words/searchconcepts') . "&search='+searh+'&languageID='+languageID,'',function(data) {";
	//echo "					console.log('data.length - '+data.length);";
	echo "					$('#param1loadingdiv').hide();";
	echo "					$('#param1loadeddiv').show();";
	echo "					$('#param1resulttable tr').remove();";
	echo "					$.each(data, function(index) {";
	//echo "						console.log('row - '+data[index].conceptID+' - '+data[index].name);";
	echo "						var row = '<tr>'";
	//echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordclassID+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].conceptID+'</td>'";
	echo "							+ '<td title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/words/showword') . "&lang='+languageID+'&id='+data[index].wordID+'\">'+data[index].name+'</a></td>'";
	echo "							+ '<td><button onclick=\"selectparam1(\''+data[index].conceptID+'\',\''+data[index].name+'\')\">valitse</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#param1resulttable').append(row);";
	echo "					});";
	echo "			}); ";
	////echo " 			console.log('finish');";
	echo "		}";
	echo "	</script>";


	echo "	<script>";
	echo "		function param2searchbuttonpressed() {";
	//echo "			console.log('search button pressed');";
	echo "			var searh = $('#param2searchfield').val();";
	echo "			if (searh == '') {";
	echo "				alert('ei saa olla tyhjä 2');";
	echo "			}";
	echo "			$('#param2loadingdiv').show();";
	echo "			$('#param2loadeddiv').hide();";
	echo "			var languageID = 2;";
	//echo "			console.log('languageid -'+languageID+'-');";
	echo "			console.log('" . getUrl('worder/words/searchconcepts') . "&search='+searh);";
	
	echo "			$.getJSON('" . getUrl('worder/words/searchconcepts') . "&search='+searh+'&languageID='+languageID,'',function(data) {";
	//echo "					console.log('data.length - '+data.length);";
	echo "					$('#param2loadingdiv').hide();";
	echo "					$('#param2loadeddiv').show();";
	echo "					$('#param2resulttable tr').remove();";
	echo "					$.each(data, function(index) {";
	//echo "						console.log('row - '+data[index].conceptID+' - '+data[index].name);";
	echo "						var row = '<tr>'";
	//echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordclassID+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].conceptID+'</td>'";
	echo "							+ '<td title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/words/showword') . "&lang='+languageID+'&id='+data[index].wordID+'\">'+data[index].name+'</a></td>'";
	echo "							+ '<td><button onclick=\"selectparam2(\''+data[index].conceptID+'\',\''+data[index].name+'\')\">valitse</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#param2resulttable').append(row);";
	echo "					});";
	echo "			}); ";
	////echo " 			console.log('finish');";
	echo "		}";
	echo "	</script>";
	
}







$table = new UITableSection("Prepositions", "600px");
$table->showLineNumbers(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$table->addButton($button);

$column = new UISelectColumn("Function", "name", "functionID", $this->registry->functions);
$table->addColumn($column);

$column = new UISelectColumn("Param1", "name", "param1", $this->registry->concepts);
$table->addColumn($column);

$column = new UISelectColumn("Param2", "name", "param2", $this->registry->concepts);
$table->addColumn($column);

$table->setData($registry->propositions);
$table->show();



?>