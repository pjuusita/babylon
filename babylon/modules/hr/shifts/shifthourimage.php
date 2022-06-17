<?php

$height = $this->registry->height;
$width = $this->registry->width;
$hours = $this->registry->hours;
//$bgcolor = $this->registry->bgcolor;

$im = imagecreatetruecolor($width,$height);

$white = imagecolorallocate($im, 255, 255, 255);
$background = imagecolorallocate($im, 223, 223, 223);
$textcolor = imagecolorallocate($im, 0, 0, 0);
imagefill($im, 0, 0, $background);

//$im = imagecreatefrompng("/home/babelsoftf/domains/babelsoft.fi/public_html/babylon/modules/hr/shifts/redstar.png");


$step = $width / $hours;

$xpos = 0;
$first = true;
$counter = 0;
while($xpos < $width) {
	imageline($im, $xpos, 0, $xpos, $height, $white);
	if ($first == false) {
		//imagestring($im, 2, $xpos, 3, '' . $counter . ':00', $textcolor);
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