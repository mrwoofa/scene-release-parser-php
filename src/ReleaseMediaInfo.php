<?php

namespace thcolin\SceneReleaseParser;

use InvalidArgumentException;
use thcolin\SceneReleaseParser\ReleaseConstants as SrpRc;
use Mhor\MediaInfo\MediaInfo;


class ReleaseMediaInfo
{
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
}