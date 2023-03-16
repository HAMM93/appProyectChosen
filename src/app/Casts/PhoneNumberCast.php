<?php

namespace App\Casts;

use Exception;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class PhoneNumberCast implements CastsAttributes
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return array|string[]
     * @throws Exception
     */
    public function get($model, string $key, $value, array $attributes): array
    {
        try {
            $phoneNumberUtil = PhoneNumberUtil::getInstance();
            $parse_number = $phoneNumberUtil->parse('+' . $value);
            $country_string = $phoneNumberUtil->getRegionCodeForNumber($parse_number);
            $country = $phoneNumberUtil->getCountryCodeForRegion($country_string);
            $phone = $this->sanitizePhoneNumber($phoneNumberUtil->format($parse_number, PhoneNumberFormat::NATIONAL));

            return ['country' => (string)$country, 'value' => (string)$phone];

        } catch (NumberParseException $e) {
            return [];
        } catch (\Throwable $e) {
            throw new Exception('general.error-while-get-phone', $e->getCode());
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return string|void
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (isset($value['country']) && !empty(trim($value['country'])) && isset($value['value']) && !empty(trim(($value['value']))))
            return $this->sanitizePhoneNumber($value['country'] . $value['value']);
    }

    private function sanitizePhoneNumber(string $phone)
    {
        return str_replace([' ', '-', '(', ')'], '', $phone);
    }
}
