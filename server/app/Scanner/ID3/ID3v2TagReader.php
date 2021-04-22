<?php

namespace App\Scanner\ID3;

use App\Scanner\ID3\Models\ByteRange;
use App\Scanner\ID3\Models\ID3;
use JetBrains\PhpStorm\{ArrayShape, Pure};

class ID3v2TagReader extends MediaTagReader
{
    public const TAG = 'ID3';
    public const ID3_HEADER_SIZE = 10;

    #[Pure]
    public static function getTagIdentifierByteRange(): ByteRange
    {
        return new ByteRange(offset: 0, length: self::ID3_HEADER_SIZE);
    }

    public static function canReadTagFormat(array $tagIdentifier): bool
    {
        $id = StringUtil::fromCharCode($tagIdentifier[0], $tagIdentifier[1], $tagIdentifier[2]);

        return $id === self::TAG;
    }

    #[ArrayShape([
        'title'   => "string[]",
        'artist'  => "string[]",
        'album'   => "string[]",
        'year'    => "string[]",
        'comment' => "string[]",
        'track'   => "string[]",
        'genre'   => "string[]",
        'picture' => "string[]",
        'lyrics'  => "string[]",
    ])]
    public function getShortcuts(): array
    {
        return [
            'title'   => ['TIT2', 'TT2'],
            'artist'  => ['TPE1', 'TP1'],
            'album'   => ['TALB', 'TAL'],
            'year'    => ['TYER', 'TYPE'],
            'comment' => ['COMM', 'COM'],
            'track'   => ['TRCK', 'TRK'],
            'genre'   => ['TCON', 'TCO'],
            'picture' => ['APIC', 'PIC'],
            'lyrics'  => ['USLT', 'ULT'],
        ];
    }

    protected function loadData(callable $callbacks): void
    {
        $this->mediaFileReader->loadRange([6, 9], function () use ($callbacks) {
            $this->mediaFileReader->loadRange(
                [0, self::ID3_HEADER_SIZE + $this->mediaFileReader->getSyncSafeInteger32At(6) - 1],
                $callbacks,
            );
        });
    }

    protected function parseData(array $tags)
    {
        $offset = 0;
        $major = $this->mediaFileReader->getByteAt($offset + 3);
        if ($major > 4) {
            return [
                'type'    => static::TAG,
                'version' => '>2.4',
                'tags'    => [],
            ];
        }
        $revision = $this->mediaFileReader->getByteAt($offset + 4);
        $unsynch = $this->mediaFileReader->isBitSetAt($offset + 5, 7);
        $xheader = $this->mediaFileReader->isBitSetAt($offset + 5, 6);
        $xindicator = $this->mediaFileReader->isBitSetAt($offset + 5, 5);
        $size = $this->mediaFileReader->getSyncSafeInteger32At($offset + 6);
        $offset += 6;

        if ($xheader) {
            // We skip the extended header and don't offer support for it right now.
            if ($major === 4) {
                $xheadersize = $this->mediaFileReader->getSyncSafeInteger32At($offset);
                $offset += $xheadersize;
            } else {
                $xheadersize = $this->mediaFileReader->getLongAt($offset, true);
                // The 'Extended header size', currently 6 or 10 bytes, excludes itself.
                $offset += $xheadersize + 4;
            }
        }

        $id3 = new ID3(
            tag: self::TAG,
            version: "2.$major.$revision",
            major: $major,
            revision: $revision,
            flags: [
                'unsynchronisation'      => $unsynch,
                'extended_header'        => $xheader,
                'experimental_indicator' => $xindicator,
                'footer_present'         => false,
            ],
            size: $size,
            tags: []
        );

        if ($tags) {
            $expandedTags = $this->expandShortcutTags($tags);
        }

        if ($id3['flags']['unsynchronisation']) {

        }

        return $id3;
    }

    public function getFrameData(array $frames, array $ids)
    {
        for ($i = 0; $id = $ids[$i]; $i++) {
            if (in_array($id, $frames, true)) {
                if (is_array($frames[$id])) {
                    $frame = $frames[$id][0];
                } else {
                    $frame = $frames[$id];
                }

                return $frame['data'];
            }
        }

        return null;
    }
}
