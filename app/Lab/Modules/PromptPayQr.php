<?php

namespace App\Lab\Modules;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

final class PromptPayQr
{
    public static function sanitizeRecipient(string $recipientType, string $recipient): string
    {
        $digits = preg_replace('/\D+/', '', $recipient) ?? '';
        return match ($recipientType) {
            'phone' => self::validatePhone($digits),
            'national_id' => self::validateNationalId($digits),
            'ewallet' => self::validateEwallet($digits),
            default => abort(422, 'Unsupported PromptPay recipient type.'),
        };
    }

    public static function maskRecipient(string $recipientType, string $recipient): string
    {
        $digits = preg_replace('/\D+/', '', $recipient) ?? '';
        if ($digits === '') {
            return '';
        }
        return match ($recipientType) {
            'phone' => substr($digits, 0, 3).'-XXX-'.substr($digits, -4),
            'national_id' => substr($digits, 0, 1).'-XXXX-XXXXX-'.substr($digits, -3),
            'ewallet' => substr($digits, 0, 3).'-XXXX-XXXX-'.substr($digits, -4),
            default => 'XXXX',
        };
    }

    public static function payload(string $recipientType, string $recipient, int $amountSatang): string
    {
        abort_unless($amountSatang >= 100 && $amountSatang <= 100_000_000, 422, 'Order amount is out of PromptPay range.');
        $sanitized = self::sanitizeRecipient($recipientType, $recipient);

        $subTag = match ($recipientType) {
            'phone' => ['01', '0066'.substr($sanitized, 1)],
            'national_id' => ['02', $sanitized],
            'ewallet' => ['03', $sanitized],
        };

        $merchantAccount = self::tlv('00', 'A000000677010111').self::tlv($subTag[0], $subTag[1]);
        $amountBaht = number_format($amountSatang / 100, 2, '.', '');

        $data = [
            self::tlv('00', '01'),
            self::tlv('01', '12'),
            self::tlv('29', $merchantAccount),
            self::tlv('58', 'TH'),
            self::tlv('53', '764'),
            self::tlv('54', $amountBaht),
        ];

        $raw = implode('', $data).'6304';
        return $raw.self::crc16($raw);
    }

    public static function svg(string $payload, int $size = 240): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size, 1),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        return $writer->writeString($payload);
    }

    private static function tlv(string $id, string $value): string
    {
        return $id. str_pad((string) strlen($value), 2, '0', STR_PAD_LEFT).$value;
    }

    private static function crc16(string $data): string
    {
        $crc = 0xFFFF;
        $length = strlen($data);
        for ($i = 0; $i < $length; $i++) {
            $x = (($crc >> 8) ^ ord($data[$i])) & 0xFF;
            $x ^= $x >> 4;
            $crc = (($crc << 8) ^ ($x << 12) ^ ($x << 5) ^ $x) & 0xFFFF;
        }
        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }

    private static function validatePhone(string $digits): string
    {
        if (strlen($digits) === 11 && str_starts_with($digits, '66')) {
            $digits = '0'.substr($digits, 2);
        }
        abort_unless(preg_match('/^0\d{9}$/', $digits) === 1, 422, 'PromptPay phone number must be a 10-digit Thai number (e.g. 0812345678).');
        return $digits;
    }

    private static function validateNationalId(string $digits): string
    {
        abort_unless(preg_match('/^\d{13}$/', $digits) === 1, 422, 'PromptPay Tax / National ID must be 13 digits.');
        return $digits;
    }

    private static function validateEwallet(string $digits): string
    {
        abort_unless(preg_match('/^\d{15}$/', $digits) === 1, 422, 'PromptPay E-Wallet ID must be 15 digits.');
        return $digits;
    }
}
