<?php

namespace App\Scanner\Metadata;

class StringUtil
{
    public static function fromCharCode(...$code): string
    {
        return implode(array_map('mb_chr', $code));
    }

    /**
     * @param array $bytes
     * @param int|null $maxBytes
     * @return DecodedString
     */
    public static function readUTF8String(array $bytes, ?int $maxBytes = null): DecodedString
    {
        $byteCount = count($bytes);
        $ix = 0;
        $maxBytes = min($maxBytes ?? $byteCount, $byteCount);
        unset($byteCount);

        if ($bytes[0] === 0xEF && $bytes[1] === 0xBB && $bytes[2] === 0xBF) {
            $ix = 3;
        }

        $collector = [];

        for ($j = 0; $ix < $maxBytes; $j++) {
            $firstByte = $bytes[$ix++];

            if ($firstByte === 0x00) {
                break;
            }

            if ($firstByte < 0x80) {
                $collector[$j] = static::fromCharCode($firstByte);
            } else if ($firstByte >= 0xC2 && $firstByte < 0xE0) {
                $secondByte = $bytes[$ix++];
                $collector[$j] = static::fromCharCode((($firstByte & 0x1F) << 6) + ($secondByte & 0x3F));
            } else if ($firstByte >= 0xE0 && $firstByte < 0xF0) {
                $secondByte = $bytes[$ix++];
                $thirdByte = $bytes[$ix++];
                $collector[$j] = static::fromCharCode((($firstByte & 0xFF) << 12) + (($secondByte & 0x3F) << 6) + ($thirdByte & 0x3F));
            } else if ($firstByte >= 0xF0 && $firstByte < 0xF5) {
                $secondByte = $bytes[$ix++];
                $thirdByte = $bytes[$ix++];
                $fourthByte = $bytes[$ix++];
                $codepoint = (($firstByte & 0x07) << 18) + (($secondByte & 0x3F) << 12) + (($thirdByte & 0x3F) << 6) + ($fourthByte & 0x3F) - 0x10000;

                $collector[$j] = static::fromCharCode(($codepoint >> 10) + 0xD800, ($codepoint & 0x3FF) + 0xDC00);
            }
        }

        return new DecodedString(implode('', $collector), $ix);
    }

    /**
     * @param int[] $bytes
     * @param bool $bigEndian
     * @param int|null $maxBytes
     * @return DecodedString
     */
    public static function readUTF16String(array $bytes, bool $bigEndian, ?int $maxBytes = null): DecodedString
    {
        $ix = 0;
        $firstOffset = 1;
        $secondOffset = 0;

        $maxBytes = min($maxBytes || count($bytes), count($bytes));

        if ($bytes[0] === 0xFE && $bytes[1] === 0xFF) {
            $bigEndian = true;
            $ix = 2;
        } else if ($bytes[0] === 0xFF && $bytes[1] === 0xFE) {
            $bigEndian = false;
            $ix = 2;
        }

        if ($bigEndian) {
            $firstOffset = 0;
            $secondOffset = 1;
        }

        $collector = [];

        for ($j = 0; $ix < $maxBytes; $j++) {
            $firstByte = $bytes[$ix + $firstOffset];
            $secondByte = $bytes[$ix + $secondOffset];
            $firstWord = ($firstByte << 8) + $secondByte;
            $ix += 2;

            if ($firstWord === 0x0000) {
                break;
            }

            if ($firstByte < 0xD8 || $firstWord >= 0xE0) {
                $collector[$j] = static::fromCharCode($firstWord);
            } else {
                $thirdByte = $bytes[$ix + $firstOffset];
                $fourthByte = $bytes[$ix + $secondOffset];
                $secondWord = ($thirdByte << 8) + $fourthByte;
                $ix += 2;
                $collector[$j] = static::fromCharCode($firstWord, $secondWord);
            }
        }

        return new DecodedString(implode('', $collector), $ix);
    }

    public static function readNullTerminatedString(array $bytes, ?int $maxBytes = null)
    {
        $collector = [];
        $maxBytes = $maxBytes ?? count($bytes);

        for ($i = 0; $i < $maxBytes;) {
            $firstByte = $bytes[$i++];
            if ($firstByte === 0x00) {
                break;
            }

            $collector[$i - 1] = static::fromCharCode($firstByte);
        }

        return new DecodedString(implode('', $collector), $i);
    }
}
