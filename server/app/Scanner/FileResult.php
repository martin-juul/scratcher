<?php

namespace App\Scanner;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

class FileResult implements \JsonSerializable, Arrayable, \Stringable
{
    public $album;
    public $artist;
    public $trackName;
    public $trackNo;


    public function __construct(
        public string $basename,
        public string $directory,
        public string $path,
        public string $mime,
        public int $size,
        public string $sha256,
    )
    {
        $this->parseFromPath($this->path);
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

    #[ArrayShape([
        'artist' => "mixed",
        'album'  => "mixed",
        'track'  => "string",
        'no'     => "string",
    ])]
    private function parseFromPath(string $path): array
    {
        $directory = str_replace(DIRECTORY_SEPARATOR, '', Str::beforeLast($path, DIRECTORY_SEPARATOR));
        [$artist, $album] = preg_match('/^(<artist>.+)(?=-)|(?!<-)([^-]+)$/', $directory);
        $track = Str::after(Str::beforeLast(basename($path), '.'), '- ');
        $no = "{$track[0]}{$track[1]}";

        $this->artist = $artist;
        $this->album = $album;
        $this->trackName = $track;
        $this->trackNo = $no;
    }
}
