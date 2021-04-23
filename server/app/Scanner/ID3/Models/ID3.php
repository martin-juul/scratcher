<?php

namespace App\Scanner\ID3\Models;

class ID3
{
    public function __construct(
        public string $tag,
        public ?string $version = '',
        public ?int $major = null,
        public ?int $revision = null,
        public array $flags = [],
        public ?int $size = null,
        public array $tags = [],
    )
    {
    }
}
