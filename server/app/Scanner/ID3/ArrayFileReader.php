<?php


namespace App\Scanner\ID3;


class ArrayFileReader extends MediaFileReader
{
    private array $byteArray;

    public function __construct(array $byteArray)
    {
        $this->byteArray = $byteArray;
        $this->size = count($byteArray);
        $this->isInitialized = true;
    }

    public static function canReadFile($file): bool
    {
        return is_array($file);
    }

    public function getByteAt(int $offset): int
    {
        return $this->byteArray[$offset];
    }

    public function loadRange(array $range, callable $callbacks): void
    {
        // TODO: Implement loadRange() method.
    }

    public function getByteAt(int $offset): int
    {
        if ($offset >= count($this->byteArray)) {
            throw new \RuntimeException("Offset $offset has not been loaded yet");
        }

        return $this->byteArray[$offset];
    }
}
