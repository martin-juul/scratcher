<?php
declare(strict_types=1);

namespace App\Scanner;

use Illuminate\Support\Facades\File;
use Illuminate\Support\LazyCollection;

class FileScanner
{
    public function __construct(
        private string $path,
    )
    {
    }

    /**
     * @return LazyCollection<FileResult>
     */
    public function listContents(): LazyCollection
    {
        $files = File::allFiles($this->path);
        $res = [];

        foreach ($files as $file) {
            $basename = $file->getFilenameWithoutExtension();
            $directory = File::dirname($file->getPathname());
            $path = $file->getRealPath();
            $mime = File::mimeType($path);
            $size = $file->getSize();
            $hash = hash('sha256', serialize([$path, $size]));

            $res[] = new FileResult(
                basename: $basename,
                directory: $directory,
                path: $path,
                mime: $mime,
                size: $size,
                sha256: $hash,
            );
        }

        return LazyCollection::make($res);
    }

    public function parseId3(FileResult $file): array
    {
        $id3 = new \getID3;

        return $id3->analyze($file->path);
    }
}
