<?php

namespace thcolin\SceneReleaseParser;

class ReleaseConstants
{

    const MOVIE = 'movie';
    const TVSHOW = 'tvshow';

    const ORIGINAL_RELEASE = 1;
    const GENERATED_RELEASE = 2;

    const SOURCE = 'source';
    const SOURCE_DVDRIP = 'DVDRip';
    const SOURCE_DVDSCR = 'DVDScr';
    const SOURCE_BDSCR = 'BDScr';
    const SOURCE_WEB_DL = 'WEB-DL';
    const SOURCE_BDRIP = 'BDRip';
    const SOURCE_DVD_R = 'DVD-R';
    const SOURCE_R5 = 'R5';
    const SOURCE_HDRIP = 'HDRip';
    const SOURCE_BLURAY = 'BLURAY';
    const SOURCE_PDTV = 'PDTV';
    const SOURCE_SDTV = 'SDTV';
    const SOURCE_HDTV = 'HDTV';
    const SOURCE_CAM = 'CAM';
    const SOURCE_TC = 'TC';

    const ENCODING = 'encoding';
    const ENCODING_XVID = 'XviD';
    const ENCODING_DIVX = 'DivX';
    const ENCODING_X264 = 'x264';
    const ENCODING_X265 = 'x265';
    const ENCODING_H264 = 'h264';

    const RESOLUTION = 'resolution';
    const RESOLUTION_SD = 'SD';
    const RESOLUTION_720P = '720p';
    const RESOLUTION_1080P = '1080p';

    const DUB = 'dub';
    const DUB_DUBBED = 'DUBBED';
    const DUB_AC3 = 'AC3';
    const DUB_MD = 'MD';
    const DUB_LD = 'LD';

    const LANGUAGE = 'language';
    const LANGUAGE_MULTI = 'MULTI';
    const LANGUAGE_DEFAULT = 'VO';

    const SOURCES = [
        self::SOURCE_CAM => [
            'cam',
            'camrip',
            'cam-rip',
            'ts',
            'telesync',
            'pdvd'
        ],
        self::SOURCE_TC => [
            'tc',
            'telecine'
        ],
        self::SOURCE_DVDRIP => [
            'dvdrip',
            'dvd-rip'
        ],
        self::SOURCE_DVDSCR => [
            'dvdscr',
            'dvd-scr',
            'dvdscreener',
            'screener',
            'scr',
            'DDC'
        ],
        self::SOURCE_BDSCR => [
            'bluray-scr',
            'bdscr'
        ],
        self::SOURCE_WEB_DL => [
            'webtv',
            'web-tv',
            'webdl',
            'web-dl',
            'webrip',
            'web-rip',
            'webhd',
            'web'
        ],
        self::SOURCE_BDRIP => [
            'bdrip',
            'bd-rip',
            'brrip',
            'br-rip'
        ],
        self::SOURCE_DVD_R => [
            'dvd',
            'dvdr',
            'dvd-r',
            'dvd-5',
            'dvd-9',
            'r6-dvd'
        ],
        self::SOURCE_R5 => [
            'r5'
        ],
        self::SOURCE_HDRIP => [
            'hdrip',
            'hdlight',
            'mhd',
            'hd'
        ],
        self::SOURCE_BLURAY => [
            'bluray',
            'blu-ray',
            'bdr'
        ],
        self::SOURCE_PDTV => [
            'pdtv'
        ],
        self::SOURCE_SDTV => [
            'sdtv',
            'dsr',
            'dsrip',
            'satrip',
            'dthrip',
            'dvbrip'
        ],
        self::SOURCE_HDTV => [
            'hdtv',
            'hdtvrip',
            'hdtv-rip'
        ]
    ];

    const ENCODINGS = [
        self::ENCODING_DIVX => [
            'divx'
        ],
        self::ENCODING_XVID => [
            'xvid'
        ],
        self::ENCODING_X264 => [
            'x264',
            'x.264'
        ],
        self::ENCODING_X265 => [
            'x265',
            'x.265'
        ],
        self::ENCODING_H264 => [
            'h264',
            'h.264'
        ]
    ];

    const RESOLUTIONS = [
        self::RESOLUTION_SD => [
            'sd'
        ],
        self::RESOLUTION_720P => [
            '720p'
        ],
        self::RESOLUTION_1080P => [
            '1080p'
        ]
    ];

    const DUBS = [
        self::DUB_DUBBED => [
            'dubbed'
        ],
        self::DUB_AC3 => [
            'ac3.dubbed',
            'ac3'
        ],
        self::DUB_MD => [
            'md',
            'microdubbed',
            'micro-dubbed'
        ],
        self::DUB_LD => [
            'ld',
            'linedubbed',
            'line-dubbed'
        ]
    ];

    const LANGUAGES = [
        'FRENCH' => [
            'FRENCH',
            'Français',
            'Francais',
            'FR'
        ],
        'TRUEFRENCH' => [
            'TRUEFRENCH',
            'VFF'
        ],
        'VFQ' => 'VFQ',
        'VOSTFR' => [
            'STFR',
            'VOSTFR',
        ],
        'PERSIAN' => 'PERSIAN',
        'AMHARIC' => 'AMHARIC',
        'ARABIC' => 'ARABIC',
        'CAMBODIAN' => 'CAMBODIAN',
        'CHINESE' => 'CHINESE',
        'CREOLE' => 'CREOLE',
        'DANISH' => 'DANISH',
        'DUTCH' => 'DUTCH',
        'ENGLISH' => [
            'ENGLISH',
            'EN',
            'VOA'
        ],
        'ESTONIAN' => 'ESTONIAN',
        'FILIPINO' => 'FILIPINO',
        'FINNISH' => 'FINNISH',
        'FLEMISH' => 'FLEMISH',
        'GERMAN' => 'GERMAN',
        'GREEK' => 'GREEK',
        'HEBREW' => 'HEBREW',
        'INDONESIAN' => 'INDONESIAN',
        'IRISH' => 'IRISH',
        'ITALIAN' => 'ITALIAN',
        'JAPANESE' => 'JAPANESE',
        'KOREAN' => 'KOREAN',
        'LAOTIAN' => 'LAOTIAN',
        'LATVIAN' => 'LATVIAN',
        'LITHUANIAN' => 'LITHUANIAN',
        'MALAY' => 'MALAY',
        'MALAYSIAN' => 'MALAYSIAN',
        'MAORI' => 'MAORI',
        'NORWEGIAN' => 'NORWEGIAN',
        'PASHTO' => 'PASHTO',
        'POLISH' => 'POLISH',
        'PORTUGUESE' => 'PORTUGUESE',
        'ROMANIAN' => 'ROMANIAN',
        'RUSSIAN' => 'RUSSIAN',
        'SPANISH' => 'SPANISH',
        'SWAHILI' => 'SWAHILI',
        'SWEDISH' => 'SWEDISH',
        'SWISS' => 'SWISS',
        'TAGALOG' => 'TAGALOG',
        'TAJIK' => 'TAJIK',
        'THAI' => 'THAI',
        'TURKISH' => 'TURKISH',
        'UKRAINIAN' => 'UKRAINIAN',
        'VIETNAMESE' => 'VIETNAMESE',
        'WELSH' => 'WELSH',
        self::LANGUAGE_MULTI => 'MULTI'
    ];

    const FLAGS = [
        'PROPER' => 'PROPER',
        'FASTSUB' => 'FASTSUB',
        'LIMITED' => 'LIMITED',
        'SUBFRENCH' => 'SUBFRENCH',
        'SUBFORCED' => 'SUBFORCED',
        'EXTENDED' => 'EXTENDED',
        'THEATRICAL' => 'THEATRICAL',
        'WORKPRINT' => [
            'WORKPRINT',
            'WP'
        ],
        'FANSUB' => 'FANSUB',
        'REPACK' => 'REPACK',
        'UNRATED' => 'UNRATED',
        'NFOFIX' => 'NFOFIX',
        'NTSC' => 'NTSC',
        'PAL' => 'PAL',
        'INTERNAL' => [
            'INTERNAL',
            'INT'
        ],
        'FESTIVAL' => 'FESTIVAL',
        'STV' => 'STV',
        'RETAIL' => 'RETAIL',
        'REMASTERED' => 'REMASTERED',
        'RATED' => 'RATED',
        'CHRONO' => 'CHRONO',
        'HDLIGHT' => 'HDLIGHT',
        'UNCUT' => 'UNCUT',
        'UNCENSORED' => 'UNCENSORED',
        'COMPLETE' => 'COMPLETE',
        'UNTOUCHED' => 'UNTOUCHED',
        'DC' => 'DC',
        'DUBBED' => 'DUBBED',
        'SUBBED' => 'SUBBED',
        'REMUX' => 'REMUX',
        'DUAL' => 'DUAL',
        'FINAL' => 'FINAL',
        'COLORIZED' => 'COLORIZED',
        'WS' => 'WS',
        'DL' => 'DL',
        'DOLBY DIGITAL' => 'DOLBY DIGITAL',
        'DTS' => 'DTS',
        'AAC' => 'AAC',
        'DTS-HD' => 'DTS-HD',
        'DTS-MA' => 'DTS-MA',
        'TRUEHD' => 'TRUEHD',
        '3D' => '3D',
        'HSBS' => 'HSBS',
        'HOU' => 'HOU',
        'DOC' => 'DOC',
        'RERIP' => [
            'rerip',
            're-rip'
        ],
        'DD5.1' => [
            'dd5.1',
            'dd51',
            'dd5-1',
            '5.1',
            '5-1'
        ],
        'READNFO' => [
            'READ.NFO',
            'READ-NFO',
            'READNFO'
        ]
    ];


    /**
     * Returns an array of
     * @param $attributeName
     * @return array
     */
    public static function getConstantByAttributeName($attributeName)
    {
        $constant = [];
        switch ($attributeName) {
            case self::SOURCE :
                $constant = self::SOURCES;
                break;
            case self::DUB :
                $constant = self::DUBS;
                break;
            case self::RESOLUTION :
                $constant = self::RESOLUTIONS;
                break;
            case self::ENCODING:
                $constant = self::ENCODINGS;
                break;
            case self::LANGUAGE :
                $constant = self::LANGUAGES;
                break;
        }

        return $constant;
    }

}
