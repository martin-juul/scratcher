<?php

namespace App\Scanner;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

class FileResult implements \JsonSerializable, Arrayable, \Stringable
{
    public function __construct(
        public string $basename,
        public string $directory,
        public string $path,
        public string $mime,
        public int $size,
        public string $sha256,
    )
    {
    }

    #[ArrayShape([
        'basename'  => "string",
        'directory' => "string",
        'path'      => "string",
        'mime'      => "string",
        'size'      => "int",
        'sha256'    => "string",
    ])]
    public function toArray(): array
    {
        return [
            'basename'  => $this->basename,
            'directory' => $this->directory,
            'mime'      => $this->mime,
            'path'      => $this->path,
            'size'      => $this->size,
            'sha256'    => $this->sha256,
        ];
    }

    public function __toString(): string
    {
        return $this->basename;
    }

    #[ArrayShape([
        'basename'  => "string",
        'directory' => "string",
        'path'      => "string",
        'mime'      => "string",
        'size'      => "int",
        'sha256'    => "string",
    ])]
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function isAudio(): bool
    {
        return Str::startsWith($this->mime, 'audio/');
    }
}
