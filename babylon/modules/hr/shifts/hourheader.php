<?php

$height = $this->registry->height;
$width = $this->registry->width;
$hours = $this->registry->hours;
//$bgcolor = $this->registry->bgcolor;

$im = imagecreatetruecolor($width,$height);

$white = imagecolorallocate($im, 255, 255, 255);
$background = imagecolorallocate($im, 223, 223, 223);
$textcolor = imagecolorallocate($im, 0, 0, 0);
$textfontsize = 2;

imagefill($im, 0, 0, $background);

//$im = imagecreatefrompng("/home/babelsoftf/domains/babelsoft.fi/public_html/babylon/modules/hr/shifts/redstar.png");


$step = $width / $hours;

$xpos = 0;
$first = true;
$counter = 0;
while($xpos < $width) {
	//imageline($im, $xpos, 0, $xpos, $height, $white);
	if ($first == false) {
		$timestr = '00:00';
		if ($counter < 10) {
			$timestr = '0' . $counter . ':00';
		} else {
			$timestr = '' . $counter . ':00';
		}
		
		//$timewidth = imagettfbbox ( $textfontsize, 0,  $timestr );
		$timewidth = imagefontwidth($textfontsize);
		$len = strlen($timestr);
		$xlenni = round(($timewidth * $len) / 2);
		
		imagestring($im, $textfontsize, $xpos - $xlenni, 3, $timestr, $textcolor);
		//imagestring($im, $textfontsize, $xpos, 16, ''.($timewidth * $len), $textcolor);
	} else {
		$first = false;
	}
	$counter++;
	$xpos = $xpos + $step;
}




header('Content-Type: image/png');
//imagetruecolortopalette($im, false, 255);
imagepng($im);
imagedestroy($im);
?>