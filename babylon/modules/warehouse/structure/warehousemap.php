<?php



function generateWarehouseMapCoordinates($registry) {

	$clusters = array();
	$clustersXmin = array();
	$clustersXmax = array();
	$clustersYmin = array();
	$clustersYmax = array();
	
	$warehouselength = $registry->hall->length;
	$warehousewidth = $registry->hall->width;
	
	$imagewidth = 800;
	$imageheight = intval(($imagewidth * $warehousewidth) / $warehouselength);
	
	$xrate = $imagewidth / $warehouselength;
	$yrate = $imageheight / $warehousewidth;
	
	
	foreach($registry->zones as $index => $zone) {

		if ($zone->type == 1) {
		
			if (isset($clustersXmin[$zone->cluster])) {
				$xmin = $xrate * $zone->posX;;
				if ($xmin < $clustersXmin[$zone->cluster]) $clustersXmin[$zone->cluster] = $xmin;
			} else {
				$clustersXmin[$zone->cluster] = $xrate * $zone->posX;
			}
	
			if (isset($clustersXmax[$zone->cluster])) {
				$xmax = $xrate * $zone->posX  + $xrate * $zone->length;
				if ($xmax > $clustersXmax[$zone->cluster]) $clustersXmax[$zone->cluster] = $xmax;
			} else {
				$clustersXmax[$zone->cluster] = $xrate * $zone->posX  + $xrate * $zone->length;
			}
			
			
	
			if (isset($clustersYmin[$zone->cluster])) {
				$ymin = $yrate * $zone->posY;
				if ($ymin < $clustersYmin[$zone->cluster]) $clustersYmin[$zone->cluster] = $ymin;
			} else {
				$clustersYmin[$zone->cluster] = $yrate * $zone->posY;
			}
			
			if (isset($clustersYmax[$zone->cluster])) {
				$ymax = $yrate * $zone->posY;
				if ($ymax > $clustersYmax[$zone->cluster]) $clustersYmax[$zone->cluster] = $ymax;
			} else {
				$clustersYmax[$zone->cluster] =  $yrate * $zone->posY + $yrate * $zone->width;;
			}
		}
	}
	
	echo "<map name=\"shape1\">";
	foreach ($clustersXmin as $index => $value) {
		echo "<area href=\"javascript:mapselect(" . $index . ")\" color=\"" . $index . "\" shape=\"poly\" coords=\"". $clustersXmin[$index].",".$clustersYmin[$index].",".($clustersXmax[$index]+1).",".$clustersYmin[$index]. ",".($clustersXmax[$index]+1).",".($clustersYmax[$index]+1).",".$clustersXmin[$index].",".($clustersYmax[$index]+1)."\" alt=\"\">\n";
	}
	echo "<area shape=\"default\" nohref>";
	echo "</map>";
}


echo "<div style='height:100%;width:100%;vertical-align:center;padding: 6px 6px 6px 2px;'>";
echo "	<img  id=\"shape1\"  alt=\"shape1\" usemap=\"#shape1\" style='vertical-align:text-bottom' src='".getNoframesUrl('warehouse/structure/mapimage')."&hallid=1&shade=1'>";
echo "</div>";

generateWarehouseMapCoordinates($this->registry);

echo "<script>";
echo "$(document).ready(function ()";
echo "{";
echo "	$('#shape1').mapster({";
echo "		singleSelect : true,";
echo "		render_highlight : { altImage : '".getNoframesUrl('warehouse/structure/mapimage')."&hallid=1&shade=2' },";
echo "		mapKey: 'color',";
echo "		mouseoutDelay: 0,";
echo "		clickNavigate: true,";
echo "		fill : true,";
echo "		fillColor : '000000',";
echo "		fillOpacity : 0.4,";
echo "	});";
echo "});";
echo "</script>";


echo "<script>";
echo "	function mapselect(data) {";
echo "		alert('map pressed - '+data);";
echo "	}";
echo "</script>";

?>