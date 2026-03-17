<?php

namespace App\Support;

final class InputSanitizer
{
    public static function nullableSingleLine(mixed $value): ?string
    {
        $text = self::singleLine($value);

        return $text !== '' ? $text : null;
    }

    public static function nullableMultiLine(mixed $value): ?string
    {
        $text = self::multiLine($value);

        return $text !== '' ? $text : null;
    }

    public static function email(mixed $value): string
    {
        return mb_strtolower(self::singleLine($value));
    }

    public static function singleLine(mixed $value): string
    {
        $text = self::baseClean($value);
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        return trim($text);
    }

    public static function multiLine(mixed $value): string
    {
        $text = self::baseClean($value);
        $text = str_replace("\t", ' ', $text);
        $text = preg_replace('/[^\S\n]+/u', ' ', $text) ?? $text;
        $text = preg_replace("/ *\n */u", "\n", $text) ?? $text;
        $text = preg_replace("/\n{3,}/u", "\n\n", $text) ?? $text;

        return trim($text);
    }

    private static function baseClean(mixed $value): string
    {
        $text = is_scalar($value) ? (string) $value : '';
        $text = str_replace(["\r\n", "\r", "\xc2\xa0"], ["\n", "\n", ' '], $text);
        $text = strip_tags($text);

        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/u', '', $text) ?? $text;
    }
}
