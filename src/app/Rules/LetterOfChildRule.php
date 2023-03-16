<?php

namespace App\Rules;

use App\Types\DonorMediaTypes;
use App\Types\RegionTypes;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class LetterOfChildRule implements Rule
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function passes($attribute, $value): bool
    {
        $extension = $this->getExtensionByBase64($value);

        if (config('app.default_country') === RegionTypes::BRAZIL &&
            array_key_exists($extension, config('app.countries.' . RegionTypes::BRAZIL . '.letter_of_child.types')) &&
            config('app.countries.' . RegionTypes::BRAZIL . '.letter_of_child.types.' . $extension) === DonorMediaTypes::CONFIG_ENABLED) {
            $this->request->merge(['letter_type' => $extension]);
            return true;
        }

        if (config('app.default_country') === RegionTypes::MEXICO &&
            array_key_exists($extension, config('app.countries.' . RegionTypes::MEXICO . '.letter_of_child.types')) &&
            config('app.countries.' . RegionTypes::MEXICO . '.letter_of_child.types.' . $extension) === DonorMediaTypes::CONFIG_ENABLED) {
            $this->request->merge(['letter_type' => $extension]);
            return true;
        }

        return false;
    }

    private function getExtensionByBase64(string $file): string
    {
        $extension = explode('/', $file);
        $extension = explode(';', $extension[1]);

        return strtolower($extension[0]);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('exception.general.invalid_type_file');
    }
}
