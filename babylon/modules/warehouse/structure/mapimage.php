<?php

$warehouselength = $this->registry->hall->length;
$warehousewidth = $this->registry->hall->width;
 
$imagewidth = 800;
$imageheight = intval(($imagewidth * $warehousewidth) / $warehouselength);

$xrate = $imagewidth / $warehouselength;
$yrate = $imageheight / $warehousewidth;

$my_img = imagecreate( $imagewidth, $imageheight);

if ($this->registry->shade == 1) {
	$background_color = imagecolorallocate( $my_img, 180,180,180 );
} elseif ($this->registry->shade == 2)  {
	$background_color = imagecolorallocate( $my_img, 0,0,0 );
} elseif ($this->registry->shade == 2)  {
	$background_color = imagecolorallocate( $my_img, 230,130,230 );
}

if ($this->registry->shade == 1) {
	
	$text_colour = imagecolorallocate( $my_img, 255, 255, 0 );
	$line_colour = imagecolorallocate( $my_img, 128, 255, 0 );
	$colors = array();
	
	if ($this->registry->shade == 1) {
		$colors[0] = imagecolorallocate( $my_img, 102,194,164 );
		$colors[1] = imagecolorallocate( $my_img, 140,150,198 );
		$colors[2] = imagecolorallocate( $my_img, 123,204,296 );
		$colors[3] = imagecolorallocate( $my_img, 252,141,89 );
		$colors[4] = imagecolorallocate( $my_img, 116,169,207 );
		$colors[5] = imagecolorallocate( $my_img, 103,169,207 );
		$colors[6] = imagecolorallocate( $my_img, 223,101,176 );
	} else {
		$colors[0] = imagecolorallocate( $my_img, 72,164,144 );
		$colors[1] = imagecolorallocate( $my_img, 120,130,178 );
		$colors[2] = imagecolorallocate( $my_img, 103,184,276 );
		$colors[3] = imagecolorallocate( $my_img, 232,121,69 );
		$colors[4] = imagecolorallocate( $my_img, 96,149,187 );
		$colors[5] = imagecolorallocate( $my_img, 83,149,187 );
		$colors[6] = imagecolorallocate( $my_img, 203,81,156 );
	}
	
	$clusters = array();
	$clustercount = 0;
	foreach($this->registry->zones as $index => $zone) {
		if (!isset($clusters[$zone->cluster])) {
			$clusters[$zone->cluster] = $clustercount;
			$clustercount++;
		}
	}

	$black_colour = imagecolorallocate( $my_img, 0, 0, 0 );
	//imagestring( $my_img, 2, 30, 70, "" . $imagewidth . " - "  . $imageheight. ", shade - " . $this->registry->shade , $black_colour );
	//imagestring( $my_img, 2, 30, 140,"" . $imagewidth . " - "  . $imageheight . " vs. " . $warehouselength . " - " . $warehousewidth, $black_colour );
	//imagestring( $my_img, 2, 30, 150,"" . $xrate . " - "  . $yrate . "", $black_colour );
	if ($this->registry->shade == 1) {
		imagesetthickness($my_img, 1);
	} else {
		imagesetthickness($my_img, 1);
	}
	$counter = 160;
	foreach($this->registry->zones as $index => $zone) {
		
		if ($zone->type == 1) {
			
			$text_colour = $colors[$clusters[$zone->cluster]];
			
			$xpos1 = $xrate * $zone->posX;
			$ypos1 = $yrate * $zone->posY;
			
			$xpos2 = $xpos1 + $xrate * $zone->length;
			$ypos2 = $ypos1 + $yrate * $zone->width;
			
			imagefilledrectangle($my_img, $xpos1, $ypos1, $xpos2, $ypos2, $text_colour );
			imagerectangle($my_img, $xpos1, $ypos1, $xpos2, $ypos2, $black_colour );
			imagestring( $my_img, 1, $xpos1+4, $ypos1+2, "" . $zone->name . "", $black_colour );
			
			//imagestring( $my_img, 2, 40, $counter, "" . $zone->name . " - " . $zone->posX . " - "  . $zone->posY . " - (" . $xpos1 ."," .  $ypos1 .") - (" .  $xpos2 ."," .  $ypos2 . ")", $black_colour );
			//$counter = $counter + 20;
		}	
	}
}

header( "Content-type: image/png" );
imagepng( $my_img );
//imagecolordeallocate( $line_color );
//imagecolordeallocate( $text_color );
//imagecolordeallocate( $background );
imagedestroy( $my_img );

?>