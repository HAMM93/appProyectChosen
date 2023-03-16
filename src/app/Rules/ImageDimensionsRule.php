<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ImageDimensionsRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (
            $value['height'] > config('files.dimensions.max.height') ||
            $value['width'] > config('files.dimensions.max.width') ||
            $value['height'] < config('files.dimensions.min.height') ||
            $value['width'] < config('files.dimensions.min.width')
        )
            return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.imageFromBase64.dimension');
    }
}
