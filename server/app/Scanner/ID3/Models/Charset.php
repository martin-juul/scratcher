<?php

namespace App\Scanner\ID3\Models;

class Charset
{
    public const UTF_16 = 'utf-16';
    public const UTF_16_LE = 'utf-16le';
    public const UTF_16_BE = 'utf-16be';
    public const UTF_8 = 'utf-8';
    public const ISO_8859_1 = 'iso-8859-1';

    public const CHARSETS = [
        self::UTF_16,
        self::UTF_16_LE,
        self::UTF_16_BE,
        self::UTF_8,
        self::ISO_8859_1,
    ];

    private string $charset;

    public function __construct(string $charset)
    {
        if (!static::isValid($charset)) {
            throw new \InvalidArgumentException("$charset is not valid. Must be one of: " . implode(', ', static::CHARSETS));
        }

        $this->charset = $charset;
    }

    public static function isValid(string $charset): bool
    {
        return \in_array($charset, static::CHARSETS);
    }

    /**
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    public static function getTextEncoding($bite): string
    {
        return match ($bite) {
            0x01 => static::UTF_16,
            0x02 => static::UTF_16_BE,
            0x03 => static::UTF_8,
            default => static::ISO_8859_1,
        };
    }
}
