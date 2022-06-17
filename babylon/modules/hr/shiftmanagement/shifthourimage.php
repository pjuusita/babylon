<?php


$imagewidth = 800;
$imageheight = 800;

$my_img = imagecreate( $imagewidth, $imageheight);

$background_color = imagecolorallocate( $my_img, 180,180,180 );
	
$text_colour = imagecolorallocate( $my_img, 255, 255, 0 );
$line_colour = imagecolorallocate( $my_img, 128, 255, 0 );
$black_colour = imagecolorallocate( $my_img, 0, 0, 0 );

//imagefilledrectangle($my_img, 10,10,20,20, $background_color );
	

header( "Content-type: image/png" );
imagepng( $my_img );
imagecolordeallocate( $line_color );
imagecolordeallocate( $text_color );
imagecolordeallocate( $background );
imagedestroy( $my_img );

?>