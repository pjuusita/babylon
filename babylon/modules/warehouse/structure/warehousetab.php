<?php




echo "<div id='petetabheader'>";
echo "<h1>Tabs</h1>";
echo "<ul class='petetabulator'>";
echo "		<li id='tabu1'><a href='#tab1' onclick='tablclick(1)'>Kartta</a></li>";
echo "		<li id='tabu2' class='selectedtabu'><a href='#tab2' onclick='tablclick(2)'>Hyllyt</a></li>";
echo "		<li id='tabu3'><a href='#tab3' onclick='tablclick(3)'>Rivit</a></li>";
echo "		<li id='tabu4'><a href='#tab4' onclick='tablclick(4)'>Sarakkeet</a></li>";
echo "		<li id='tabu5'><a href='#tab5' onclick='tablclick(5)'>Hallit</a></li>";
echo "</ul>";
echo "</div>";

echo "<div id='petecontent' style='width:100%;'>";

echo "	<div id='tab1' style='display:none;height:100%;'>";
echo "	</div>";

echo "	<div id='tab2' style='display:block;height:100%;'>";
echo "	</div>";

echo "	<div id='tab3' style='display:none;height:100%;'>";
echo "	</div>";

echo "	<div id='tab4' style='display:none;height:100%;'>";
echo "	</div>";

echo "	<div id='tab5' style='display:none;height:100%;'>";
echo "	</div>";

echo "</div>";


echo "<script>";
echo "	function tablclick(tabid) {";
echo "		for(var index=1;index<6;index++) {";
echo "			$('#tab'+index).hide();";
echo "			$('#tabu'+index).removeClass('selectedtabu');";
echo "		}";
echo "		$('#tabu'+tabid).addClass('selectedtabu');";
echo "		$('#tab'+tabid).show();";
echo "		$('#tab'+tabid).html('".getNoframesUrl('warehouse/structure/showtab')."&tab='+tabid);";
echo "		$('#tab'+tabid).load('".getNoframesUrl('warehouse/structure/showtab')."&tab='+tabid);";
//echo "		$('#tab'+tabid).html(".getNoframesUrl('warehouse/structure/showtab')."?tab='+tabid);";
//echo "		$('#tab'+tabid).load(".getNoframesUrl('warehouse/structure/showtab')."?tab='+tabid);";
echo "	}";
echo "</script>";


?>