<?php

namespace App\MetaAudio\Modules;

use App\MetaAudio\Bom\Util as Bom;
use JetBrains\PhpStorm\{ArrayShape, Pure};

/**
 * Handle ID3v2.4 tags.
 */
class Id3v2 extends AbstractModule
{
    private const PREAMBLE = 'ID3';

    private const HAS_BOM = 1;
    private const UTF_16 = 2;
    private const UTF_8 = 3;

    /**
     * Get all the tags from the currently loaded file.
     *
     * @return array
     * @throws \Exception
     */
    protected function getTags(): array
    {
        $this->file->seekFromStart(0);

        $position = $this->file->getNextPosition(self::PREAMBLE);

        # If there is no ID3v2 tag then return no tags
        if ($position === null) {
            return [];
        }

        $this->file->seekFromStart($position);

        $header = $this->parseHeader();

        # Id3v2.2 is obsolete, ignore any old tags
        if ($header['version'] === 2) {
            return [];
        }

        $frames = $this->file->read($header['size']);

        $tags = [];
        while ($tag = $this->parseItem($frames)) {
            [$key, $value] = $tag;
            $tags[strtoupper($key)] = $value;
        }

        return $tags;
    }


    /**
     * Convert a synchsafe integer to a regular integer.
     *
     * In a synchsafe integer, the most significant bit of each byte is zero,
     * making seven bits out of eight available.
     * So a 32-bit synchsafe integer can only store 28 bits of information.
     *
     * @param string $string The synchsafe integer
     *
     * @return float|int
     */
    private function fromSynchsafeInt(string $string): float|int
    {
        $int = 0;
        for ($i = 1; $i <= 4; $i++) {
            $char = $string[$i - 1];
            $byte = ord($char);
            $int += $byte * (2 ** ((4 - $i) * 7));
        }

        return $int;
    }


    /**
     * Convert a regular integer to a synchsafe integer.
     *
     * @param int $int The integer
     *
     * @return string
     */
    private function toSynchsafeInt(int $int)
    {
        $string = '';
        while ($int > 0) {
            $float = $int / 128;
            $int = floor($float);
            $char = chr(ceil(($float - $int) * 127));

            $string = $char . $string;
        }

        return str_pad($string, 4, "\0", \STR_PAD_LEFT);
    }


    /**
     * Parse the header from the file.
     *
     * @return array
     * @throws \Exception
     */
    #[ArrayShape([
        "version" => "mixed",
        "flags"   => "mixed",
        "size"    => "float|int",
        "unsynch" => "bool",
        "footer"  => "bool",
    ])]
    private function parseHeader(): array
    {
        $preamble = $this->file->read(3);
        if ($preamble !== self::PREAMBLE) {
            throw new \Exception("Invalid ID3 tag, expected [" . self::PREAMBLE . "], got [{$preamble}]");
        }

        $version = unpack('S', $this->file->read(2))[1];
        $flags = unpack('C', $this->file->read(1))[1];

        $header = [
            'version' => $version,
            'flags'   => $flags,
            'size'    => $this->fromSynchsafeInt($this->file->read(4)),
            'unsynch' => (bool)($flags & 0x80),
            'footer'  => (bool)($flags & 0x10),
        ];

        # Skip the extended header
        if ($flags & 0x40) {
            $size = $this->fromSynchsafeInt($this->file->read(4));
            $this->file->read($size - 4);
            $header['size'] -= $size;
        }

        return $header;
    }


    /**
     * Get the next item tag from the file.
     *
     * @param string $frames The frames to parse the next one from
     *
     * @return array|void
     */
    private function parseItem(string &$frames)
    {
        if ($frames === '') {
            return;
        }

        $key = substr($frames, 0, 4);

        # Ensure a valid key was found
        if (!preg_match('/^[A-Z0-9]{4}$/', $key)) {
            return;
        }

        $size = $this->fromSynchsafeInt(substr($frames, 4, 4));

        $encoding = unpack("C", $frames[10])[1];
        $value = substr($frames, 11, $size - 1);

        switch ($encoding) {
            case self::UTF_8:
            case self::HAS_BOM:
                break;
            case self::UTF_16:
                $value = "\xFE\xFF" . $value;
                break;
            default:
                $value = utf8_encode($value);
        }

        $value = Bom::removeBom($value);

        # Strings are unreliably terminated with nulls, so just strip any that are present
        $value = rtrim($value, "\0");

        $frames = substr($frames, 10 + $size);

        return [$key, $value];
    }


    /**
     * Write the specified tags to the currently loaded file.
     *
     * @param array The tags to write as key/value pairs
     *
     * @return void
     * @throws \Exception
     */
    protected function putTags(array $tags): void
    {
        # Locate the existing id3 tags so we can strip them out
        $this->file->rewind();
        $start = $this->file->getNextPosition(self::PREAMBLE);
        if ($start !== null) {
            $this->file->seekFromStart($start);
            $header = $this->parseHeader();
            $end = $this->file->getCurrentPosition() + (int)$header['size'];
        }

        # Get the contents of the file (without the id3 tags)
        $contents = '';
        $this->file->rewind();

        # If we found an id3 tag
        if ($start !== null) {
            # If the id3 tag isn't at the start of the file then get the data preceding it
            if ($start > 0) {
                $contents .= $this->file->read($start);
            }
            # Position to the end of the id3 tag so we can start reading from there
            $this->file->seekFromStart($end);
        }

        # Read the rest of the file (following the id3 tag)
        $contents .= $this->file->readAll();

        $details = $this->createTagData($tags);

        $this->file->truncate(5);
        $this->file->rewind();
        $this->file->write($details);
        $this->file->write($contents);
    }


    /**
     * Create the tag content for the file.
     *
     * @param array $tags The key/value tags to use
     *
     * @return string
     */
    #[Pure]
    private function createTagData(array $tags): string
    {
        $header = self::PREAMBLE;

        # Version
        $header .= pack('S', 4);

        # Flags
        $header .= pack('C', 0);

        $details = '';
        foreach ($tags as $key => $value) {
            # Declare the contents as UTF-8 terminated by a single null character
            $data = pack('C', 3) . $value . "\0";

            $details .= $key;
            $details .= $this->toSynchsafeInt(strlen($data));
            $details .= "\0\0";
            $details .= $data;
        }

        $header .= $this->toSynchsafeInt(strlen($details));

        return $header . $details;
    }

    /**
     * Get the album picture
     *
     * @return string
     */
    public function getArtwork(): string
    {
        return $this->getTag('APIC');
    }


    /**
     * Get the track title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return (string)$this->getTag('TIT2');
    }


    /**
     * Get the track number.
     *
     * @return int
     */
    public function getTrackNumber(): int
    {
        return (int)$this->getTag('TRCK');
    }


    /**
     * Get the artist name.
     *
     * @return string
     */
    public function getArtist(): string
    {
        $tag = (string)$this->getTag('TPE1');

        # If there's no main artist available, see if the album artist tag is populated
        if ($tag === '') {
            $tag = (string)$this->getTag('TPE2');
        }

        return $tag;
    }


    /**
     * Get the album name.
     *
     * @return string
     */
    public function getAlbum(): string
    {
        return (string)$this->getTag('TALB');
    }


    /**
     * Get the release year.
     *
     * @return int
     */
    public function getYear(): int
    {
        return (int)$this->getTag('TDRC');
    }


    /**
     * Set the track title.
     *
     * @param string $title The title name
     *
     * @return $this
     */
    public function setTitle($title): static
    {
        return $this->setTag('TIT2', $title);
    }


    /**
     * Set the track number.
     *
     * @param int $track The track number
     *
     * @return $this
     */
    public function setTrackNumber($track): static
    {
        return $this->setTag('TRCK', $track);
    }


    /**
     * Set the artist name.
     *
     * @param string $artist The artist name
     *
     * @return $this
     */
    public function setArtist($artist): static
    {
        $this->setTag('TPE2', $artist);
        return $this->setTag('TPE1', $artist);
    }


    /**
     * Set the album name.
     *
     * @param string $album The album name
     *
     * @return $this
     */
    public function setAlbum($album): static
    {
        return $this->setTag('TALB', $album);
    }


    /**
     * Set the release year.
     *
     * @param int $year The release year
     *
     * @return $this
     */
    public function setYear($year): static
    {
        return $this->setTag('TDRC', $year);
    }
}
