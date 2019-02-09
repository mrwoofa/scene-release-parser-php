<?php

namespace thcolin\SceneReleaseParser;

use Mhor\MediaInfo\MediaInfo;
use InvalidArgumentException;
use thcolin\SceneReleaseParser\ReleaseConstants as SrpRc;

class Release
{

    protected $release;
    protected $strict = true;
    protected $defaults = [];
    protected $type;
    protected $title;
    protected $year = 0;
    protected $language;
    protected $resolution;
    protected $source;
    protected $dub;
    protected $encoding;
    protected $group;
    protected $flags = [];
    protected $original;

    protected $season = 0;
    protected $episode = 0;

    public function __construct($name, $strict = true, $defaults = [])
    {
        $this->validateDefaults($defaults);

        $this->strict = $strict;
        $this->defaults = $defaults;

        // CLEAN
        $cleaned = $this->clean($name);

        $this->original = $name;
        $this->release = $cleaned;
        $title = $cleaned;

        // LANGUAGE
        $language = $this->parseLanguage($title);
        $this->setLanguage($language);

        // SOURCE
        $source = $this->parseSource($title);
        $this->setSource($source);

        // ENCODING
        $encoding = $this->parseEncoding($title);
        $this->setEncoding($encoding);

        // RESOLUTION
        $resolution = $this->parseResolution($title);
        $this->setResolution($resolution);

        // DUB
        $dub = $this->parseDub($title);
        $this->setDub($dub);

        // YEAR
        $year = $this->parseYear($title);
        $this->setYear($year);

        // FLAGS
        $flags = $this->parseFlags($title);
        $this->setFlags($flags);

        // TYPE
        $type = $this->parseType($title);
        $this->setType($type);

        // GROUP
        $group = $this->parseGroup($title);
        $this->setGroup($group);

        // TITLE
        $title = $this->parseTitle($title);
        $this->setTitle($title);
    }

    public function __toString()
    {
        $arrays = [];
        foreach ([
                     $this->getTitle(),
                     $this->getYear(),
                     ($this->getSeason() ? 'S' . sprintf('%02d', $this->getSeason()) : '') .
                     ($this->getEpisode() ? 'E' . sprintf('%02d', $this->getEpisode()) : ''),
                     ($this->getLanguage() !== SrpRc::LANGUAGE_DEFAULT ? $this->getLanguage() : ''),
                     ($this->getResolution() !== SrpRc::RESOLUTION_SD ? $this->getResolution() : ''),
                     $this->getSource(),
                     $this->getEncoding(),
                     $this->getDub()
                 ] as $array) {
            if (is_array($array)) {
                $arrays[] = implode('.', $array);
            } else if ($array) {
                $arrays[] = $array;
            }
        }
        return preg_replace('#\s+#', '.', implode('.', $arrays)) . '-' . ($this->getGroup() ? $this->getGroup() : 'NOTEAM');
    }

    public static function analyse($path, $config = [])
    {
        if (!is_file($path)) {
            throw new InvalidArgumentException("File '" . $path . "' not found");
        }

        // MEDIAINFO
        $mediainfo = new MediaInfo();

        foreach ($config as $key => $value) {
            $mediainfo->setConfig($key, $value);
        }

        $container = $mediainfo->getInfo($path);

        // RELEASE
        $basename = pathinfo($path, PATHINFO_FILENAME);
        $release = new Release($basename, false);

        foreach ($container->getVideos() as $video) {
            // CODEC
            if ($codec = $video->get('encoded_library_name')) {
                switch ($codec) {
                    case 'DivX':
                        $release->setEncoding(SrpRc::ENCODING_DIVX);
                        break;
                    case 'x264':
                        $release->setEncoding(SrpRc::ENCODING_X264);
                        break;
                    case 'x265':
                        $release->setEncoding(SrpRc::ENCODING_X265);
                        break;
                }
            }

            if ($codec = $video->get('writing_library_name')) {
                switch ($codec) {
                    case 'DivX':
                        $release->setEncoding(SrpRc::ENCODING_DIVX);
                        break;
                    case 'x264':
                        $release->setEncoding(SrpRc::ENCODING_X264);
                        break;
                    case 'x265':
                        $release->setEncoding(SrpRc::ENCODING_X265);
                        break;
                }
            }

            if ($codec = $video->get('codec_cc')) {
                switch ($codec) {
                    case 'DIVX':
                        $release->setEncoding(SrpRc::ENCODING_DIVX);
                        break;
                    case 'XVID':
                        $release->setEncoding(SrpRc::ENCODING_XVID);
                        break;
                    case 'hvc1':
                        $release->setEncoding(SrpRc::ENCODING_X265);
                        break;
                }
            }

            if (!$release->getEncoding()) {
                if ($codec = $video->get('internet_media_type')) {
                    switch ($codec) {
                        case 'video/H264':
                            $release->setEncoding(SrpRc::ENCODING_H264);
                            break;
                    }
                }
            }

            // RESOLUTION
            if (!$release->getResolution()) {
                $height = $video->get('height')->getAbsoluteValue();
                $width = $video->get('width')->getAbsoluteValue();

                if ($height >= 1000 || $width >= 1900) {
                    $release->setResolution(SrpRc::RESOLUTION_1080P);
                } else if ($height >= 700 || $width >= 1200) {
                    $release->setResolution(SrpRc::RESOLUTION_720P);
                } else {
                    $release->setResolution(SrpRc::RESOLUTION_SD);
                }
            }
        }

        // LANGUAGE
        $audios = $container->getAudios();

        if (count($audios) > 1) {
            $release->setLanguage(SrpRc::LANGUAGE_MULTI);
        } else if (count($audios) > 0) {
            $languages = $audios[0]->get('language');
            if ($languages) {
                $release->setLanguage(strtoupper($languages[1]));
            }
        }

        if (!$release->getLanguage()) {
            // default : VO
            $release->setLanguage(SrpRc::LANGUAGE_DEFAULT);
        }

        return $release;
    }

    public function getRelease($mode = SrpRc::ORIGINAL_RELEASE)
    {
        switch ($mode) {
            case SrpRc::GENERATED_RELEASE:
                return $this->__toString();
                break;
            default:
                return $this->release;
                break;
        }
    }

    private function clean($name)
    {
        $release = str_replace(['[', ']', '(', ')', ',', ';', ':', '!'], ' ', $name);
        $release = preg_replace('#[\s]+#', ' ', $release);
        $release = str_replace(' ', '.', $release);

        return $release;
    }

    public function guess()
    {
        $release = $this;

        if (!isset($release->year)) {
            $release->setYear($release->guessYear());
        }

        if (!isset($release->resolution)) {
            $release->setResolution($release->guessResolution());
        }

        if (!isset($release->language)) {
            $release->setLanguage($release->guessLanguage());
        }

        return $release;
    }

    private function parseAttribute(&$title, $attribute)
    {
        if (!in_array($attribute, [SrpRc::SOURCE, SrpRc::ENCODING, SrpRc::RESOLUTION, SrpRc::DUB])) {
            throw new InvalidArgumentException();
        }

        $attributes = SrpRc::getConstantByAttributeName($attribute);
        foreach ($attributes as $key => $patterns) {
            if (!is_array($patterns)) {
                $patterns = [$patterns];
            }

            foreach ($patterns as $pattern) {
                $title = preg_replace('#[\.|\-]' . preg_quote($pattern) . '([\.|\-| ]|$)#i', '$1', $title, 1, $replacements);
                if ($replacements > 0) {
                    return $key;
                }
            }
        }

        return null;
    }

    public function getType()
    {
        return $this->type;
    }

    private function parseType(&$title)
    {
        $type = null;

        $title = preg_replace_callback('#[\.\-]S(\d+)[\.\-]?(E(\d+))?([\.\-])#i', function ($matches) use (&$type) {
            $type = SrpRc::TVSHOW;
            // 01 -> 1 (numeric)
            $this->setSeason(intval($matches[1]));

            if ($matches[3]) {
                $this->setEpisode(intval($matches[3]));
            }
            return $matches[4];
        }, $title, 1, $count);

        if ($count == 0) {
            // Not a Release
            if (
                $this->strict &&
                !isset($this->resolution) &&
                !isset($this->source) &&
                !isset($this->dub) &&
                !isset($this->encoding)
            ) {
                throw new InvalidArgumentException('This is not a correct Scene Release name');
            }

            // movie
            $type = SrpRc::MOVIE;
        }

        return $type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getTitle()
    {
        return $this->title;
    }

    private function parseTitle(&$title)
    {
        $array = [];
        $return = '';
        $title = preg_replace('#\.?\-\.#', '.', $title);
        $title = preg_replace('#\(.*?\)#', '', $title);
        $title = preg_replace('#\.+#', '.', $title);
        $positions = explode('.', $this->release);

        foreach (array_intersect($positions, explode('.', $title)) as $key => $value) {
            $last = isset($last) ? $last : 0;

            if ($key - $last > 1) {
                $return = implode(' ', $array);
                break;
            }

            $array[] = $value;
            $return = implode(' ', $array);
            $last = $key;
        }

        $return = ucwords(strtolower($return));
        $return = trim($return);

        return $return;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getSeason()
    {
        return $this->season;
    }

    public function setSeason($season)
    {
        $this->season = $season;
    }

    public function getEpisode()
    {
        return $this->episode;
    }

    public function setEpisode($episode)
    {
        $this->episode = $episode;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    private function parseLanguage(&$title)
    {
        $languages = [];

        foreach (SrpRc::LANGUAGES as $langue => $patterns) {
            if (!is_array($patterns)) {
                $patterns = [$patterns];
            }

            foreach ($patterns as $pattern) {
                $title = preg_replace('#[\.|\-]' . preg_quote($pattern) . '([\.|\-]|$)#i', '$1', $title, 1, $replacements);
                if ($replacements > 0) {
                    $languages[] = $langue;
                    break;
                }
            }
        }

        if (count($languages) == 1) {
            return $languages[0];
        } else if (count($languages) > 1) {
            return SrpRc::LANGUAGE_MULTI;
        } else {
            return null;
        }
    }

    public function guessLanguage()
    {
        if ($this->language) {
            return $this->language;
        } else if (isset($this->defaults['language'])) {
            return $this->defaults['language'];
        } else {
            return SrpRc::LANGUAGE_DEFAULT;
        }
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getResolution()
    {
        return $this->resolution;
    }

    private function parseResolution(&$title)
    {
        return $this->parseAttribute($title, SrpRc::RESOLUTION);
    }

    public function guessResolution()
    {
        if ($this->resolution) {
            return $this->resolution;
        } else if ($this->getSource() == SrpRc::SOURCE_BLURAY || $this->getSource() == SrpRc::SOURCE_BDSCR) {
            return SrpRc::RESOLUTION_1080P;
        } else if (isset($this->defaults['resolution'])) {
            return $this->defaults['resolution'];
        } else {
            return SrpRc::RESOLUTION_SD;
        }
    }

    public function setResolution($resolution)
    {
        $this->resolution = $resolution;
    }

    public function getSource()
    {
        return $this->source;
    }

    private function parseSource(&$title)
    {
        return $this->parseAttribute($title, SrpRc::SOURCE);
    }

    public function setSource($source)
    {
        $this->source = $source;
    }

    public function getDub()
    {
        return $this->dub;
    }

    private function parseDub(&$title)
    {
        return $this->parseAttribute($title, SrpRc::DUB);
    }

    public function setDub($dub)
    {
        $this->dub = $dub;
    }

    public function getEncoding()
    {
        return $this->encoding;
    }

    private function parseEncoding(&$title)
    {
        return $this->parseAttribute($title, SrpRc::ENCODING);
    }

    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    public function getYear()
    {
        return $this->year;
    }

    private function parseYear(&$title)
    {
        $year = null;

        $title = preg_replace_callback('#[\.|\-](\d{4})([\.|\-])?#', function ($matches) use (&$year) {
            if (isset($matches[1])) {
                $year = $matches[1];
            }

            return (isset($matches[2]) ? $matches[2] : '');
        }, $title, 1);

        return $year;
    }

    public function guessYear()
    {
        if ($this->year) {
            return $this->year;
        } else if (isset($this->defaults['year'])) {
            return $this->defaults['year'];
        } else {
            return date('Y');
        }
    }

    public function setYear($year)
    {
        $this->year = $year;
    }

    public function getGroup()
    {
        return $this->group;
    }

    private function parseGroup(&$title)
    {
        $group = null;

        $title = preg_replace_callback('#\-([a-zA-Z0-9_\.]+)$#', function ($matches) use (&$group) {
            if (strlen($matches[1]) > 12) {
                preg_match('#(\w+)#', $matches[1], $matches);
            }

            $group = preg_replace('#^\.+|\.+$#', '', $matches[1]);
            return '';
        }, $title);

        return $group;
    }

    public function setGroup($group)
    {
        $this->group = $group;
    }

    public function getFlags()
    {
        return $this->flags;
    }

    private function parseFlags(&$title)
    {
        $flags = [];

        foreach (SrpRc::FLAGS as $key => $patterns) {
            if (!is_array($patterns)) {
                $patterns = [$patterns];
            }

            foreach ($patterns as $pattern) {
                $title = preg_replace('#[\.|\-]' . preg_quote($pattern) . '([\.|\-]|$)#i', '$1', $title, 1, $replacements);
                if ($replacements > 0) {
                    $flags[] = $key;
                }
            }
        }

        return $flags;
    }

    public function setFlags($flags)
    {
        $this->flags = (is_array($flags) ? $flags : [$flags]);
    }

    public function getScore()
    {
        $score = 0;

        $score += ($this->getTitle() ? 1 : 0);
        $score += ($this->getYear() ? 1 : 0);
        $score += ($this->getLanguage() ? 1 : 0);
        $score += ($this->getResolution() ? 1 : 0);
        $score += ($this->getSource() ? 1 : 0);
        $score += ($this->getEncoding() ? 1 : 0);
        $score += ($this->getDub() ? 1 : 0);

        return $score;
    }

    /**
     * @param $defaults
     */
    private function validateDefaults($defaults)
    {
        foreach ($defaults as $key => $default) {
            $const = SrpRc::getConstantByAttributeName($key);

            if ($const !== [] && !in_array($default, array_keys($const))) {
                trigger_error('Default "' . $key . '" should be a value from Release::$' . $const);
            }
        }
    }

}
