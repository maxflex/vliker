<?php

namespace App\Utils;

class Url
{
    const REGEX = '/(photo|video|wall)[-]?[0-9]+[_][0-9]+([\?]reply=[0-9]+)?/';

    public static function shorten(string $url) : string
    {
        preg_match_all(self::REGEX, $url, $m);
        if (count($m[0]) > 0) {
            return $m[0][0];
        }
        return substr($url, strpos($url, 'vk.com') + 7);
    }

    public static function isLikeable(string $url) : bool
    {
        preg_match_all(self::REGEX, $url, $m);
        return count($m[0]) > 0;
    }

    public static function vk(string $url) : string
    {
        return 'https://vk.com/' . $url;
    }
}
