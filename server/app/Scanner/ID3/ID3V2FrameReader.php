<?php

namespace App\Scanner\ID3;

use App\Scanner\ID3\Models\Charset;

class ID3V2FrameReader
{
    public const FRAME_DESCRIPTIONS = [
        // v2.2
        'BUF'  => 'Recommended buffer size',
        'CNT'  => 'Play counter',
        'COM'  => 'Comments',
        'CRA'  => 'Audio encryption',
        'CRM'  => 'Encrypted meta frame',
        'ETC'  => 'Event timing codes',
        'EQU'  => 'Equalization',
        'GEO'  => 'General encapsulated object',
        'IPL'  => 'Involved people list',
        'LNK'  => 'Linked information',
        'MCI'  => 'Music CD Identifier',
        'MLL'  => 'MPEG location lookup table',
        'PIC'  => 'Attached picture',
        'POP'  => 'Popularimeter',
        'REV'  => 'Reverb',
        'RVA'  => 'Relative volume adjustment',
        'SLT'  => 'Synchronized lyric/text',
        'STC'  => 'Synced tempo codes',
        'TAL'  => 'Album/Movie/Show title',
        'TBP'  => 'BPM (Beats Per Minute)',
        'TCM'  => 'Composer',
        'TCO'  => 'Content type',
        'TCR'  => 'Copyright message',
        'TDA'  => 'Date',
        'TDY'  => 'Playlist delay',
        'TEN'  => 'Encoded by',
        'TFT'  => 'File type',
        'TIM'  => 'Time',
        'TKE'  => 'Initial key',
        'TLA'  => 'Language(s)',
        'TLE'  => 'Length',
        'TMT'  => 'Media type',
        'TOA'  => 'Original artist(s)/performer(s)',
        'TOF'  => 'Original filename',
        'TOL'  => 'Original Lyricist(s)/text writer(s)',
        'TOR'  => 'Original release year',
        'TOT'  => 'Original album/Movie/Show title',
        'TP1'  => 'Lead artist(s)/Lead performer(s)/Soloist(s)/Performing group',
        'TP2'  => 'Band/Orchestra/Accompaniment',
        'TP3'  => 'Conductor/Performer refinement',
        'TP4'  => 'Interpreted, remixed, or otherwise modified by',
        'TPA'  => 'Part of a set',
        'TPB'  => 'Publisher',
        'TRC'  => 'ISRC (International Standard Recording Code)',
        'TRD'  => 'Recording dates',
        'TRK'  => 'Track number/Position in set',
        'TSI'  => 'Size',
        'TSS'  => 'Software/hardware and settings used for encoding',
        'TT1'  => 'Content group description',
        'TT2'  => 'Title/Songname/Content description',
        'TT3'  => 'Subtitle/Description refinement',
        'TXT'  => 'Lyricist/text writer',
        'TXX'  => 'User defined text information frame',
        'TYE'  => 'Year',
        'UFI'  => 'Unique file identifier',
        'ULT'  => 'Unsychronized lyric/text transcription',
        'WAF'  => 'Official audio file webpage',
        'WAR'  => 'Official artist/performer webpage',
        'WAS'  => 'Official audio source webpage',
        'WCM'  => 'Commercial information',
        'WCP'  => 'Copyright/Legal information',
        'WPB'  => 'Publishers official webpage',
        'WXX'  => 'User defined URL link frame',
        // v2.3
        'AENC' => 'Audio encryption',
        'APIC' => 'Attached picture',
        'ASPI' => 'Audio seek point index',
        'CHAP' => 'Chapter',
        'CTOC' => 'Table of contents',
        'COMM' => 'Comments',
        'COMR' => 'Commercial frame',
        'ENCR' => 'Encryption method registration',
        'EQU2' => 'Equalisation (2)',
        'EQUA' => 'Equalization',
        'ETCO' => 'Event timing codes',
        'GEOB' => 'General encapsulated object',
        'GRID' => 'Group identification registration',
        'IPLS' => 'Involved people list',
        'LINK' => 'Linked information',
        'MCDI' => 'Music CD identifier',
        'MLLT' => 'MPEG location lookup table',
        'OWNE' => 'Ownership frame',
        'PRIV' => 'Private frame',
        'PCNT' => 'Play counter',
        'POPM' => 'Popularimeter',
        'POSS' => 'Position synchronisation frame',
        'RBUF' => 'Recommended buffer size',
        'RVA2' => 'Relative volume adjustment (2)',
        'RVAD' => 'Relative volume adjustment',
        'RVRB' => 'Reverb',
        'SEEK' => 'Seek frame',
        'SYLT' => 'Synchronized lyric/text',
        'SYTC' => 'Synchronized tempo codes',
        'TALB' => 'Album/Movie/Show title',
        'TBPM' => 'BPM (beats per minute)',
        'TCOM' => 'Composer',
        'TCON' => 'Content type',
        'TCOP' => 'Copyright message',
        'TDAT' => 'Date',
        'TDLY' => 'Playlist delay',
        'TDRC' => 'Recording time',
        'TDRL' => 'Release time',
        'TDTG' => 'Tagging time',
        'TENC' => 'Encoded by',
        'TEXT' => 'Lyricist/Text writer',
        'TFLT' => 'File type',
        'TIME' => 'Time',
        'TIPL' => 'Involved people list',
        'TIT1' => 'Content group description',
        'TIT2' => 'Title/songname/content description',
        'TIT3' => 'Subtitle/Description refinement',
        'TKEY' => 'Initial key',
        'TLAN' => 'Language(s)',
        'TLEN' => 'Length',
        'TMCL' => 'Musician credits list',
        'TMED' => 'Media type',
        'TMOO' => 'Mood',
        'TOAL' => 'Original album/movie/show title',
        'TOFN' => 'Original filename',
        'TOLY' => 'Original lyricist(s)/text writer(s)',
        'TOPE' => 'Original artist(s)/performer(s)',
        'TORY' => 'Original release year',
        'TOWN' => 'File owner/licensee',
        'TPE1' => 'Lead performer(s)/Soloist(s)',
        'TPE2' => 'Band/orchestra/accompaniment',
        'TPE3' => 'Conductor/performer refinement',
        'TPE4' => 'Interpreted, remixed, or otherwise modified by',
        'TPOS' => 'Part of a set',
        'TPRO' => 'Produced notice',
        'TPUB' => 'Publisher',
        'TRCK' => 'Track number/Position in set',
        'TRDA' => 'Recording dates',
        'TRSN' => 'Internet radio station name',
        'TRSO' => 'Internet radio station owner',
        'TSOA' => 'Album sort order',
        'TSOP' => 'Performer sort order',
        'TSOT' => 'Title sort order',
        'TSIZ' => 'Size',
        'TSRC' => 'ISRC (international standard recording code)',
        'TSSE' => 'Software/Hardware and settings used for encoding',
        'TSST' => 'Set subtitle',
        'TYER' => 'Year',
        'TXXX' => 'User defined text information frame',
        'UFID' => 'Unique file identifier',
        'USER' => 'Terms of use',
        'USLT' => 'Unsychronized lyric/text transcription',
        'WCOM' => 'Commercial information',
        'WCOP' => 'Copyright/Legal information',
        'WOAF' => 'Official audio file webpage',
        'WOAR' => 'Official artist/performer webpage',
        'WOAS' => 'Official audio source webpage',
        'WORS' => 'Official internet radio station homepage',
        'WPAY' => 'Payment',
        'WPUB' => 'Publishers official webpage',
        'WXXX' => 'User defined URL link frame',
    ];


    private const PICTURE_TYPE = [
        'Other', "32x32 pixels 'file icon' (PNG only)", 'Other file icon', 'Cover (front)',
        'Cover (back)', 'Leaflet page', 'Media (e.g. label side of CD)', 'Lead artist/lead performer/soloist',
        'Artist/performer', 'Conductor', 'Band/Orchestra', 'Composer', 'Lyricist/text writer', 'Recording Location',
        'During recording', 'During performance', 'Movie/video screen capture', 'A bright coloured fish',
        'Illustration', 'Band/artist logotype', 'Publisher/Studio logotype',
    ];

    public static function getFrameReader(string $frameId): callable
    {
        switch ($frameId) {
            case 'APIC':
                return static::readPictureFrame();
            default:
                throw new \InvalidArgumentException("Could not find method for frameId $frameId");
        }
    }

    private static function readPictureFrame()
    {
        return static function (int $offset, int $length, MediaFileReader $data, array $flags = [], array|null $id3header = null) {
            $start = $offset;
            $charset = Charset::getTextEncoding($data->getByteAt($offset));

            switch ($id3header && $id3header['major']) {
                case 2:
                    $format = $data->getStringAt($offset + 1, 3);
                    $offset += 4;
                    break;
                case 3:
                case 4:
                    $format = $data->getStringWithCharsetAt($offset + 1, $length - 1);
                    $offset += 1 + $format->bytesReadCount;
                    break;
                default:
                    throw new \RuntimeException('Could not read ID3v2 major version');
            }

            $bite = $data->getByteAt($offset);
            $type = self::PICTURE_TYPE[$bite];
            $desc = $data->getStringWithCharsetAt($offset + 1, $length - ($offset - $start), $charset);

            $offset += 1 + $desc->bytesReadCount;

            return [
                'format'      => (string)$format,
                'type'        => $type,
                'description' => (string)$desc,
                'data'        => $data->getBytesAt($offset, ($start + $length) - $offset),
            ];
        };
    }
}
