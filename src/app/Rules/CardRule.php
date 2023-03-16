<?php

namespace App\Rules;

use App\Types\AmexInitialTypes;
use Illuminate\Contracts\Validation\Rule;

class CardRule implements Rule
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
        if (strlen($value) === 15) {
            if (substr(trim($value), 0, 2) === AmexInitialTypes::FIRST ||
                substr(trim($value), 0, 2) === AmexInitialTypes::SECOND) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('payment.card.invalid_number');
    }
}
