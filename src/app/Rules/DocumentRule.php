<?php

namespace App\Rules;

use App\Exceptions\DonorDocument\DonorDocumentNotConfiguredException;
use App\Helpers\SanitizeHelper;
use App\Types\DocumentTypes;
use App\Types\RegionTypes;
use Illuminate\Contracts\Validation\Rule;

class DocumentRule implements Rule
{
    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws DonorDocumentNotConfiguredException
     */
    public function passes($attribute, $value) : bool
    {
        if (isset($value['type']) && isset($value['value'])){

            $sanitize_rule = [
                $value['type'] => 'string',
                $value['value'] => 'string'
            ];

            $sanitized = SanitizeHelper::make($value, $sanitize_rule);

            $value['type'] = strtolower($sanitized[$value['type']]);
            $value['value'] = $sanitized[$value['value']];
        }

        if (!is_array($value) ||
            !isset($value['type']) ||
            !isset($value['value']) ||
            empty(trim($value['type'])) ||
            empty(trim($value['value']))) {
            return false;
        }

        if (config('app.default_country') === RegionTypes::BRAZIL &&
            array_key_exists($value['type'], config('app.countries.'.RegionTypes::BRAZIL.'.documents.intern')) &&
            config('app.countries.'.RegionTypes::BRAZIL.'.documents.intern.'.$value['type']) === DocumentTypes::CONFIG_ENABLED) {
                return $this->validateDocumentBrazilian($value['type'], $value['value']);
        }

        if (config('app.default_country') === RegionTypes::MEXICO &&
            array_key_exists( $value['type'], config('app.countries.'.RegionTypes::MEXICO.'.documents.intern')) &&
            config('app.countries.'.RegionTypes::MEXICO.'.documents.intern.'.$value['type']) === DocumentTypes::CONFIG_ENABLED) {
            return $this->validateDocumentMexican($value['value']);
        }

        return false;
    }

    /**
     * @param string $type
     * @param string $document
     * @return bool
     * @throws DonorDocumentNotConfiguredException
     */
    function validateDocumentBrazilian(string $type, string $document): bool
    {
        if ($type == DocumentTypes::BRAZILIAN_CPF)
            return $this->validateCpfDocumentBrazilian($document);

        if ($type == DocumentTypes::BRAZILIAN_CNPJ)
            return $this->validateCnpjDocumentBrazilian($document);

        throw new DonorDocumentNotConfiguredException();
    }

    /**
     * @param string $document
     * @return bool
     */
    private function validateCpfDocumentBrazilian(string $document): bool
    {
        $document = preg_replace('/[^0-9]/is', '', $document);

        if (strlen($document) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $document)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $document[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($document[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $document
     * @return bool
     */
    function validateCnpjDocumentBrazilian(string $document): bool
    {
        $value = preg_replace('/[^0-9]/is', '', $document);

        if (strlen($value) != 14) {
            return false;
        }

        if (preg_match('/(\d)\1{13}/', $value)) {
            return false;
        }

        for ($t = 12; $t < 14; $t++) {
            for ($d = 0, $m = ($t - 7), $i = 0; $i < $t; $i++) {
                $d += $value[$i] * $m;
                $m = ($m == 2 ? 9 : --$m);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($value[$i] != $d) {
                return false;
            }
        }
        return true;
    }

        /**
     * @param string $rfc
     * @return bool
     */
    private function validateDocumentMexican(string $rfc): bool
    {
        if (preg_match('^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1]))((-)?([A-Z\d]{3}))?$^', $rfc))
            return true;

        if (strlen(trim($rfc)) == 13)
            return true;

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('exception.general.invalid_document');
    }
}
