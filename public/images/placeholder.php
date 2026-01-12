<?php
// Generate a placeholder image
header('Content-Type: image/png');

$width = isset($_GET['w']) ? intval($_GET['w']) : 300;
$height = isset($_GET['h']) ? intval($_GET['h']) : 300;
$text = isset($_GET['text']) ? $_GET['text'] : 'Product Image';

// Create image
$image = imagecreatetruecolor($width, $height);

// Colors
$bgColor = imagecolorallocate($image, 245, 245, 245);
$boxColor = imagecolorallocate($image, 224, 224, 224);
$textColor = imagecolorallocate($image, 158, 158, 158);
$iconColor = imagecolorallocate($image, 189, 189, 189);

// Fill background
imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

// Draw center box
$boxSize = min($width, $height) * 0.5;
$boxX = ($width - $boxSize) / 2;
$boxY = ($height - $boxSize) / 2;
imagefilledrectangle($image, $boxX, $boxY, $boxX + $boxSize, $boxY + $boxSize, $boxColor);

// Draw simple icon (box with circle)
$iconSize = $boxSize * 0.6;
$iconX = ($width - $iconSize) / 2;
$iconY = ($height - $iconSize) / 2;
imageellipse($image, $width/2, $height/2, $iconSize, $iconSize, $iconColor);
imagefilledellipse($image, $width/2, $height/2, $iconSize/3, $iconSize/3, $iconColor);

// Add text
$fontSize = 3;
$textWidth = imagefontwidth($fontSize) * strlen($text);
$textX = ($width - $textWidth) / 2;
$textY = $height - 30;
imagestring($image, $fontSize, $textX, $textY, $text, $textColor);

// Output image
imagepng($image);
imagedestroy($image);
