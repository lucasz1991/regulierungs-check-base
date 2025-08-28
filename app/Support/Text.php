<?php
namespace App\Support;

class Text {
    public static function utf8($value) {
        if (is_array($value)) return array_map([self::class, 'utf8'], $value);
        if (is_string($value)) {
            $clean = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
            return $clean !== false ? $clean : mb_convert_encoding($value, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
        }
        return $value;
    }
}
