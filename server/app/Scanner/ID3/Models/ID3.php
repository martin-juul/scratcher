<?php

namespace App\Scanner\ID3\Models;

class ID3
{
    public function __construct(
        public string $tag,
        public string $version,
        public int $major,
        public int $revision,
        public array $flags,
        public int $size,
        public array $tags,
    )
    {
    }
}
