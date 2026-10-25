<?php

namespace App\Lab\Modules;

final class SlipImage
{
    public const MAX_BYTES = 5 * 1024 * 1024;
    public const MAX_JSON_BYTES = 7_100_000;

    public static function inspect(string $dataUrl): array
    {
        abort_unless(function_exists('imagecreatefromstring') && function_exists('imagepng'), 503, 'Payment slip image decoding is unavailable.');
        abort_unless(strlen($dataUrl) <= self::MAX_JSON_BYTES && preg_match('~^data:(image/(?:png|jpeg|webp));base64,([A-Za-z0-9+/=]+)$~D', $dataUrl, $match), 422, 'Choose a PNG, JPEG or WebP image.');
        $bytes = base64_decode($match[2], true);
        abort_unless(is_string($bytes) && strlen($bytes) >= 16 && strlen($bytes) <= self::MAX_BYTES, 422, 'Slip image must be under 5 MiB.');
        $size = @getimagesizefromstring($bytes);
        abort_unless(is_array($size) && ($size['mime'] ?? null) === $match[1] && $size[0] > 0 && $size[1] > 0
            && $size[0] <= 20000 && $size[1] <= 20000 && $size[0] * $size[1] <= 20_000_000, 422, 'Slip image is invalid or exceeds 20 megapixels.');
        $image = @imagecreatefromstring($bytes);
        abort_unless($image !== false, 422, 'Slip image could not be decoded.');
        try {
            imagesavealpha($image, true);
            ob_start();
            try { $encoded = imagepng($image, null, 6); $safe = ob_get_contents(); }
            finally { ob_end_clean(); }
            abort_unless($encoded && is_string($safe) && strlen($safe) <= self::MAX_BYTES, 422, 'Decoded slip image is too large.');
            return ['mime' => 'image/png', 'width' => $size[0], 'height' => $size[1], 'bytes' => strlen($safe),
                'sha256' => hash('sha256', $safe), 'contents' => $safe, 'data_url' => 'data:image/png;base64,'.base64_encode($safe)];
        } finally { unset($image); }
    }
}
