<?php

namespace Lkn\BBPix\Helpers;

abstract class Formatter
{
    /**
     * @since 2.0.0
     * @see https://stackoverflow.com/a/40081879/16530764
     *
     * @param array $array
     *
     * @return array
     */
    final public static function stripTagsArray(array $array): array
    {
        $data = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::stripTagsArray($value);
            } else {
                $data[$key] = trim(strip_tags($value));
            }
        }

        return $data;
    }

    final public static function removeNonNumber(string $value): string
    {
        return preg_replace('/[^0-9]/', '', $value);
    }

    public static function name(string $name): string
    {
        return ucwords(mb_strtolower($name));
    }
}
