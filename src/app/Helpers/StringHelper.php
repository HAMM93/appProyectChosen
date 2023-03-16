<?php

namespace App\Helpers;

use App\Models\ManagementCode;
use App\Types\DonationTypes;
use Illuminate\Support\Str;

class StringHelper
{
    const DIGITS_QUANTITY = 6;

    public static function getNewCHCode(): string
    {
        $code = ManagementCode::orderByDesc('id')->first();

        $new = new ManagementCode();
        if ($code) {

            $code_id_length = strlen($code->id);

            if ($code_id_length < self::DIGITS_QUANTITY || ($code_id_length + 1) < self::DIGITS_QUANTITY) {
                $str_random = Str::random(self::DIGITS_QUANTITY);
            } else {
                $diff = self::DIGITS_QUANTITY - $code_id_length;
                $diff = $diff === 0 ? 1 : $diff;
                $diff = $diff < 0 ? ($diff * -1) : $diff;
                $str_random = Str::random(self::DIGITS_QUANTITY + $diff);
            }

            $new->code_str = $str_random;
            $new->save();

            $str_random = substr($str_random, 0, strlen($str_random) - strlen($new->id));

            return DonationTypes::CH_TRACKING_PREFIX . sprintf('%s%s', $str_random, $new->id);
        } else {
            $str_random = Str::random(self::DIGITS_QUANTITY);
            $new->code_str = $str_random;
            $new->save();

            $str_random = substr($str_random, 0, strlen($str_random) - strlen($new->id));

            return DonationTypes::CH_TRACKING_PREFIX . sprintf('%s%s', $str_random, $new->id);
        }
    }
}
