<?php

namespace App\Scanner\Metadata;

use JetBrains\PhpStorm\Pure;

class DecodedString
{
    public int $length;

    #[Pure]
    public function __construct(public string $value, public int $bytesReadCount)
    {
        $this->length = strlen($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
