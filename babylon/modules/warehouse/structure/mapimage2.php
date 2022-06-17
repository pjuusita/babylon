<?php

$warehouselength = $this->registry->hall->length;
$warehousewidth = $this->registry->hall->width;
 
$imagewidth = 800;
$imageheight = intval(($imagewidth * $warehousewidth) / $warehouselength);

$xrate = $imagewidth / $warehouselength;
$yrate = $imageheight / $warehousewidth;

/*
$imagewidth = 800;
$imageheight = 600;
*/

$my_img = imagecreate( $imagewidth, $imageheight);

if ($this->registry->shade == 1) {
	$background_color = imagecolorallocate( $my_img, 180,180,180 );
	
} elseif ($this->registry->shade == 2)  {
	$background_color = imagecolorallocate( $my_img, 120,120,120 );
	
} elseif ($this->registry->shade == 2)  {
	$background_color = imagecolorallocate( $my_img, 230,130,230 );
		
}
$text_colour = imagecolorallocate( $my_img, 255, 255, 0 );
$line_colour = imagecolorallocate( $my_img, 128, 255, 0 );
//imagestring( $my_img, 4, 30, 25, "thesitewizard.com", $text_colour );
//imagestring( $my_img, 4, 30, 55, "" . $this->registry->hallID , $text_colour );
//imagesetthickness ( $my_img, 5 );
//imageline( $my_img, 30, 45, 165, 45, $line_colour );


header( "Content-type: image/png" );
imagepng( $my_img );
imagecolordeallocate( $line_color );
imagecolordeallocate( $text_color );
imagecolordeallocate( $background );
imagedestroy( $my_img );

?>