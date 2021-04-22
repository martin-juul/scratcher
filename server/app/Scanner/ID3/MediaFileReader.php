<?php

namespace App\Scanner\ID3;

abstract class MediaFileReader
{
    protected bool $isInitialized = false;
    protected int $size = 0;

    public function __construct(protected string $path)
    {
    }

    abstract public static function canReadFile(string $file);

    public function getSize(): int
    {
        if (!$this->isInitialized) {
            throw new \LogicException('Uninitialized');
        }

        return $this->size;
    }

    abstract public function getByteAt(int $offset): int;

    public function getBytesAt(int $offset, int $length): array
    {
        $bytes = [];

        for ($i = 0; $i < $length; $i++) {
            $bytes[$i] = $this->getByteAt($offset + $i);
        }

        return $bytes;
    }

    public function isBitSetAt(int $offset, int $bit): bool
    {
        $byte = $this->getByteAt($offset);

        return ($byte & (1 << $bit)) !== 0;
    }

    public function getSByteAt(int $offset): int
    {
        $byte = $this->getByteAt($offset);

        if ($byte > 127) {
            return $byte - 256;
        }

        return $byte;
    }

    public function getShortAt(int $offset, bool $isBigEndian): int
    {
        $short = $isBigEndian
            ? ($this->getByteAt($offset) << 8) + $this->getByteAt($offset + 1)
            : ($this->getByteAt($offset + 1) << 8) + $this->getByteAt($offset);

        if ($short < 0) {
            $short += 65536;
        }

        return $short;
    }

    public function getSShortAt(int $offset, bool $isBigEndian): int
    {
        $UShort = $this->getShortAt($offset, $isBigEndian);

        if ($UShort > 32767) {
            return $UShort - 65536;
        }

        return $UShort;
    }

    public function getLongAt(int $offset, bool $isBigEndian): int
    {
        $firstByte = $this->getByteAt($offset);
        $secondByte = $this->getByteAt($offset + 1);
        $thirdByte = $this->getByteAt($offset + 2);
        $fourthByte = $this->getByteAt($offset + 3);

        $long = $isBigEndian
            ? ((((($firstByte << 8) + $secondByte) << 8) + $thirdByte) << 8) + $fourthByte
            : ((((($firstByte << 8) + $thirdByte) << 8) + $secondByte) << 8) + $firstByte;

        if ($long < 0) {
            $long += 4294967296;
        }

        return $long;
    }

    public function getSLongAt(int $offset, bool $isBigEndian): int
    {
        $ULong = $this->getLongAt($offset, $isBigEndian);

        if ($ULong > 2147483647) {
            return $ULong - 4294967296;
        }

        return $ULong;
    }

    public function getInteger24At(int $offset, bool $isBigEndian): int
    {
        $firstByte = $this->getByteAt($offset);
        $secondByte = $this->getByteAt($offset + 1);
        $thirdByte = $this->getByteAt($offset + 2);

        $integer = $isBigEndian
            ? (((($firstByte << 8) + $secondByte) << 8) + $thirdByte)
            : (((($thirdByte << 8) + $secondByte) << 8) + $firstByte);

        if ($integer < 0) {
            $integer += 16777216;
        }

        return $integer;
    }

    public function getStringAt(int $offset, int $length): string
    {
        $string = [];

        for ($i = $offset, $j = 0; $i < $offset + $length; $i++, $j++) {
            $string[$j] = StringUtil::fromCharCode($this->getByteAt($i));
        }

        return implode('', $string);
    }

    public function getStringWithCharsetAt(int $offset, int $length, ?string $charset = null): DecodedString
    {
        $bytes = $this->getBytesAt($offset, $length);

        if (!$charset) {
            return StringUtil::readNullTerminatedString($bytes);
        }

        switch ($charset) {
            case 'utf-16':
            case 'utf-16le':
            case 'utf-16be':
                return StringUtil::readUTF16String($bytes, $charset === 'utf-16be');
            case 'utf-8':
                return StringUtil::readUTF8String($bytes);
        }

        throw new \LogicException('unknown charset');
    }

    public function getCharAt(int $offset): string
    {
        return StringUtil::fromCharCode($this->getByteAt($offset));
    }

    /**
     * The ID3v2 tag/frame size is encoded with four bytes where the most
     * significant bit (bit 7) is set to zero in every byte, making a total of 28
     * bits. The zeroed bits are ignored, so a 257 bytes long tag is represented
     * as $00 00 02 01.
     *
     * @param int $offset
     * @return int
     */
    public function getSyncSafeInteger32At(int $offset): int
    {
        $size1 = $this->getByteAt($offset);
        $size2 = $this->getByteAt($offset + 1);
        $size3 = $this->getByteAt($offset + 2);
        $size4 = $this->getByteAt($offset + 3);

        return $size4 & 0x7f
            | (($size3 & 0x7f) << 7)
            | (($size2 & 0x7f) << 14)
            | (($size1 & 0x7f) << 21);
    }

    abstract public function loadRange(array $range, callable $callbacks): void;
}
