<?php

namespace App\Helpers;

class WordHelper
{
    public static function getFirstName(string $fullname)
    {
        return explode(' ', $fullname)[0];
    }

    public static function getMiddleName(string $fullname): string
    {
        $middleName = '';
        $name = explode(' ', $fullname);
        if (count($name) > 2) {
            for ($i = 1; $i < count($name); $i++)
                $middleName .= $name[$i] . ' ';
        }

        return rtrim(ltrim($middleName));
    }

    public static function getLastName(string $fullname)
    {
        $name = explode(' ', $fullname);
        return $name[count($name) - 1];
    }
}
