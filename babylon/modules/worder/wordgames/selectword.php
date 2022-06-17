<?php

// tämä on perus käännässanan valinta vaihtoehtojen joukosta. Tämän luokan nimi voisi olla selecttranslation, mutta lauseiden
// kääntäminen saattaa olla hieman haastavampaa ja vaatii mahdollisesti monimutkaisemman käyttäliittymän


echo "<br><br>";

echo "<button  class=section-button  onclick='startgame()'>Aloita</button>";
echo "<br>";


echo "<table style='width:400px;background-color:pink;'>";

echo "<tr>";
echo "	<td style='width:200px;height:40px;background-color:orange'></td>";
echo "	<td style='width:200px;height:40px;background-color:lightblue'></td>";
echo "</tr>";

echo "<tr>";
echo "	<td colspan=2 style='height:80px;background-color:yellow'></td>";
echo "</tr>";

echo "<tr>";
echo "	<td colspan=2 style='height:180px;background-color:green;'></td>";
echo "</tr>";

echo "</table>";

echo "<script>";
echo "		function startgame() {";
echo "			var url = '" . getUrl("worder/wordgames/selectwordquestion") . "';";
//echo "			console.log('startgame - '+url);";
echo "			$.getJSON(url,'count=4',function(reply) { ";
//echo "				console.log('reply:' + reply);";
//echo "				console.log('question:' + reply.question);";
//echo "				console.log('answer:' + reply.answer);";
//echo "				console.log('option-1:' + reply.options[0]);";
//echo "				console.log('option-2:' + reply.options[1]);";
//echo "				console.log('option-3:' + reply.options[2]);";
//echo "				console.log('option-4:' + reply.options[3]);";
echo "			});";

echo "		};";
echo "	</script>";

		

?>
