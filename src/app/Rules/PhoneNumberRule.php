<?php

namespace App\Rules;

use App\Services\Logging\Facades\Logging;
use Illuminate\Contracts\Validation\Rule;
use libphonenumber\PhoneNumberUtil;

class PhoneNumberRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        try
        {
            $phoneNumberUtil = PhoneNumberUtil::getInstance();
            $country = $phoneNumberUtil->getRegionCodeForCountryCode($value['country']);
            $parse_number = $phoneNumberUtil->parse($value['value'], $country);

            return $phoneNumberUtil->isValidNumber($parse_number);
        }catch (\Throwable $e){
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.phone-is-invalid');
    }
}
