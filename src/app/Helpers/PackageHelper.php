<?php


namespace App\Helpers;


use App\Exceptions\Package\PackageNotCreatedException;
use App\Models\Event;
use App\Types\PackageTypes;

class PackageHelper
{
    /**
     * @param Event $last_package
     * @return string
     * @throws PackageNotCreatedException
     */
    public static function makeTitle(Event $last_package): string
    {
        try {
            $title = preg_replace("/[^0-9]/", "", $last_package->title);

            $title = PackageTypes::CH_CODE . str_pad(((int)$title + 1), 4, '0', STR_PAD_LEFT);
        } catch (\Exception $e) {
            throw new PackageNotCreatedException($e);
        }

        return $title;
    }
}
