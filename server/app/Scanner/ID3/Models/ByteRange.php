<?php

namespace App\Scanner\ID3\Models;

class ByteRange
{
    public function __construct(
        public int $offset,
        public int $length,
    )
    {}
}
