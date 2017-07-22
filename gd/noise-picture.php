<?php
session_start();
$image = imagecreatefromjpeg('./images/noise.jpg');
$color = imagecolorallocate($image, 64,64,64);
imageantialias($image, true);
$nCars = 5;
$capcha = substr(md5(uniqid()), 0, $nCars);
$_SESSION['randStr'] = $capcha;
$x = 20;
$y = 30;
$deltaX = 40;
for ($i = 0; $i < $nCars; $i++){
	$size = rand(18,30);
	$angel = -30 + rand(0, 60);
	imagettftext($image, $size, $angel, $x, $y, $color, 'fonts/bellb.ttf', $capcha{$i});
	$x += $deltaX;
}
header('Content-Type: image/jpeg');
imagejpeg($image, null, 50);
?>
