<?php
// Synthetic image only: not a bank statement, transaction receipt or payment proof.
$image = imagecreatetruecolor(480, 320);
for ($x = 0; $x < 480; $x++) for ($y = 0; $y < 320; $y++) {
    imagesetpixel($image, $x, $y, imagecolorallocate($image, 220 + random_int(0, 35), 220 + random_int(0, 35), 220 + random_int(0, 35)));
}
$ink = imagecolorallocate($image, 80, 0, 0);
imagestring($image, 5, 25, 50, 'FICTIONAL QA IMAGE - NO REAL PAYMENT', $ink);
imagestring($image, 5, 25, 100, 'NOT A BANK SLIP / DO NOT TRANSFER', $ink);
imagestring($image, 5, 25, 150, 'Synthetic browser upload fixture', $ink);
imagepng($image, dirname(__DIR__, 3).'/.agent-work/qa/fictional-slip.png');
unset($image);
