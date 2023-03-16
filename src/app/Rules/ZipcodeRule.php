<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ZipcodeRule implements Rule
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
        if (config('app.locale') === 'pt-br')
            return strlen($value) === 8;

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.zipcode.length');
    }
}
