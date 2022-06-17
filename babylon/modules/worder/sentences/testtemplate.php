<?php



	echo "<br>TestTemplate2aaaa";
	
	echo "<table >";
	$counter=0;
	$divide = 0;
	foreach($this->registry->conceptrows as $wordID => $row) {

		if ($divide == 0) {
			$divide = 1;
			$backcolor = "#ffffff";
		} else {
			$divide = 0;
			$backcolor = "#dddddd";
		}
		
		
		echo "	<tr>";
		
		echo "		<td style='width:50px;background-color:" . $backcolor . ";'>";
		echo "" . $counter;
		echo "		</td>";
		$counter++;
		
		echo "		<td style='width:50px;background-color:" . $backcolor . ";'>";
		echo "" . $row->conceptID;
		echo "		</td>";
		
		echo "		<td style='width:300px;background-color:" . $backcolor . ";'>";
		echo "" . $row->name;
		echo "		</td>";
		
		echo "		<td style='width:200px;background-color:" . $backcolor . ";'>";
		$found = false;
		if ($row->answer == 1) {
			echo "<font style='font-weight:bold;'>OK</font>";
			$found = true;
		}
		if ($row->answer == 2) {
			echo "<span style=''>N/A</span>";
			$found = true;
		}
		if ($row->answer == 3) {
			echo "<span style=''>Split</span>";
			$found = true;
		}
		if ($row->answer == 4) {
			echo "<span style=''>Mieti</span>";
			$found = true;
		}
		
		echo "		</td>";
		
		if ($found == true) {
			echo "		<td style='width:200px;background-color:" . $backcolor . ";'>";
			echo "		</td>";
		} else {
			echo "		<td style='width:200px;background-color:" . $backcolor . ";'>";
			echo "		<button id=button" . $row->conceptID . "-1 onclick='addAnswerPressed(" . $row->conceptID . ",1)'>OK</button>";
			echo "		<button id=button" . $row->conceptID . "-2 onclick='addAnswerPressed(" . $row->conceptID . ",2)'>N/A</button>";
			echo "		<button id=button" . $row->conceptID . "-3 onclick='addAnswerPressed(" . $row->conceptID . ",3)'>Split</button>";
			echo "		<button id=button" . $row->conceptID . "-4 onclick='addAnswerPressed(" . $row->conceptID . ",4)'>Mieti</button>";
			echo "	</td>";
			
			
			/*
			$formstring = "--tuntematon--";
			if (isset($adessives[$wordID])) {
				$formstring = $adessives[$wordID];
				echo "<br>" . $lemma . " ... " . $wordID . " ... " . $formstring . " ..... ";
				echo " - <a id=button" . $wordID . "-1 onclick='addAdessiveRolePressed(" . $wordID . ",998)' style='text-decoration:underline;cursor:pointer;color:blue;'>Location</a>";
				echo " - <a id=button" . $wordID . "-2 onclick='addAdessiveRolePressed(" . $wordID . ",999)' style='text-decoration:underline;cursor:pointer;color:blue;'>Proximity</a>";
				echo " - <a id=button" . $wordID . "-3 onclick='addAdessiveRolePressed(" . $wordID . ",1000)' style='text-decoration:underline;cursor:pointer;color:blue;'>Surface</a>";
				echo " - <a id=button" . $wordID . "-4 onclick='addAdessiveRolePressed(" . $wordID . ",1001)' style='text-decoration:underline;cursor:pointer;color:blue;'>Instrument</a>";
				echo " - <a id=button" . $wordID . "-5 onclick='addAdessiveRolePressed(" . $wordID . ",1002)' style='text-decoration:underline;cursor:pointer;color:blue;'>Time</a>";
				echo " - <a id=button" . $wordID . "-6 onclick='addAdessiveRolePressed(" . $wordID . ",1003)' style='text-decoration:underline;cursor:pointer;color:blue;'>Owner</a>";
				echo " - <a id=button" . $wordID . "-7 onclick='addAdessiveRolePressed(" . $wordID . ",1004)' style='text-decoration:underline;cursor:pointer;color:blue;'>None</a>";
			} else {
				echo "<br>" . $lemma . " ... " . $wordID . " ... " . $formstring . " ..... ";
				echo " - <a id=button" . $wordID . "-1 onclick='addAdessiveRolePressed(" . $wordID . ",998)' style='text-decoration:underline;cursor:pointer;color:blue;'>Location</a>";
				echo " - <a id=button" . $wordID . "-2 onclick='addAdessiveRolePressed(" . $wordID . ",999)' style='text-decoration:underline;cursor:pointer;color:blue;'>Proximity</a>";
				echo " - <a id=button" . $wordID . "-3 onclick='addAdessiveRolePressed(" . $wordID . ",1000)' style='text-decoration:underline;cursor:pointer;color:blue;'>Surface</a>";
				echo " - <a id=button" . $wordID . "-4 onclick='addAdessiveRolePressed(" . $wordID . ",1001)' style='text-decoration:underline;cursor:pointer;color:blue;'>Instrument</a>";
				echo " - <a id=button" . $wordID . "-5 onclick='addAdessiveRolePressed(" . $wordID . ",1002)' style='text-decoration:underline;cursor:pointer;color:blue;'>Time</a>";
				echo " - <a id=button" . $wordID . "-6 onclick='addAdessiveRolePressed(" . $wordID . ",1003)' style='text-decoration:underline;cursor:pointer;color:blue;'>Owner</a>";
				echo " - <a id=button" . $wordID . "-7 onclick='addAdessiveRolePressed(" . $wordID . ",1004)' style='text-decoration:underline;cursor:pointer;color:blue;'>None</a>";
			}
			*/
		
				
			//echo " <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/features/addArticletype&wordID=" . $wordID . "&featureID=253'>A-type</a>";
			//echo " ..... <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/features/addArticletype&wordID=" . $wordID . "&featureID=254'>An-type</a>";
			//echo " ..... <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/features/addArticletype&wordID=" . $wordID . "&featureID=255'>The-type</a>";
			//echo " ..... <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/features/addArticletype&wordID=" . $wordID . "&featureID=256'>NoArticle</a>";
		}
		echo "		</td>";
		echo "	<tr>";
		
		
		//if ($counter > 100) break;
	}
	echo "	</tr>";
	echo "</table>";
	
	


	echo "	<script>";
	echo "		function addAnswerPressed(conceptID, answerID) {";
	echo "			var url = '" . getUrl('worder/sentences/addtemplateanswer') . "&templateID=" . $this->registry->testtemplate->templateID . "&conceptID='+conceptID+'&answer='+answerID;";
	echo "			console.log('url - '+url);";
	
	echo "			$('#button'+conceptID+'-1').hide();";
	echo "			$('#button'+conceptID+'-2').hide();";
	echo "			$('#button'+conceptID+'-3').hide();";
	echo "			$('#button'+conceptID+'-4').hide();";
	echo "			$.getJSON(url,function(data) {";
	echo "				console.log('success - '+data);";
	//echo "				$('#button'+wordID).hide();";
	echo "			}); ";
	echo "		}";
	echo "	</script>";
	

?>