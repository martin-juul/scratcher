<?php

namespace App\Scanner\ID3;

use App\Scanner\ID3\Models\ByteRange;

abstract class MediaTagReader
{
    private array $tags = [];

    public function __construct(protected MediaFileReader $mediaFileReader)
    {
    }

    /**
     * Returns the byte range that needs to be loaded and fed to
     * @return ByteRange
     * @see canReadTagFormat in order to identify if the file contains tag
     * information that can be read.
     *
     */
    abstract public static function getTagIdentifierByteRange(): ByteRange;

    /**
     * @param array $tagIdentifier
     * @return bool
     */
    abstract public static function canReadTagFormat(array $tagIdentifier): bool;

    /**
     * @param array $tags
     * @return MediaTagReader
     */
    public function setTags(array $tags): static
    {
        $this->tags = $tags;
        return $this;
    }

    public function read(callable $callbacks)
    {

    }

    abstract public function getShortcuts(): array;

    abstract protected function loadData(callable $callbacks): void;

    abstract protected function parseData(MediaFileReader $data, array $tags);

    protected function expandShortcutTags(array $tagsWithShortcuts): ?array
    {
        if (count($tagsWithShortcuts) === 0) {
            return null;
        }

        $tags = [];
        $shortcuts = $this->getShortcuts();

        for ($i = 0; $tagOrShortcut = $tagsWithShortcuts[$i]; $i++) {
            /** @noinspection SlowArrayOperationsInLoopInspection */
            $tags = array_merge($tags, $shortcuts[$tagOrShortcut] || [$tagOrShortcut]);
        }

        return $tags;
    }
}
