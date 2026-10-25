<?php
putenv('DB_CONNECTION=sqlite'); putenv('DB_DATABASE=:memory:');
require __DIR__.'/forms-bootstrap.php';
use App\Lab\Modules\SlipImage;
$invalid = function (string $image, string $label) {
    try { SlipImage::inspect($image); formCheck(false, $label); }
    catch (Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $error) { formCheck($error->getStatusCode() === 422, $label); }
};
formCheck(class_exists(SlipImage::class), 'Server decodes and re-encodes uploaded slip bytes');
$invalid('data:image/png;base64,'.base64_encode('<html>disguised executable image fixture</html>'), 'Disguised HTML rejected');
$invalid('data:image/svg+xml;base64,'.base64_encode('<svg/>'), 'SVG rejected');
$image = imagecreatetruecolor(180,120); imagefill($image,0,0,imagecolorallocate($image,40,120,80)); ob_start(); imagepng($image); $png = ob_get_clean(); imagedestroy($image);
$output = SlipImage::inspect('data:image/png;base64,'.base64_encode($png));
formCheck($output['mime'] === 'image/png' && $output['width'] === 180 && $output['height'] === 120 && $output['bytes'] > 0, 'Decoded dimensions and safe format retained');
$invalid('data:image/jpeg;base64,'.base64_encode($png), 'Declared MIME must match decoded bytes');
$oversized = substr_replace($png, pack('NN',10000,10000),16,8);
$invalid('data:image/png;base64,'.base64_encode($oversized), 'Pixel dimensions checked before decode allocation');
$polyglot = SlipImage::inspect('data:image/png;base64,'.base64_encode($png.'<script>unwanted appended metadata</script>'));
formCheck(!str_contains(base64_decode(explode(',', $polyglot['data_url'],2)[1]), '<script>'), 'Re-encoding strips appended content and metadata');
formResult('slip file content and dimensions');
