<?php

namespace App\Http\Requests;

use App\Rules\CardRule;
use App\Rules\CVCRule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'card' => 'required|array',
            'card.exp_month' => 'required|string|min:2|max:2',
            'card.exp_year' => 'required|string|min:2|max:2',
            'card.number' => ['required', 'string', 'min:15', 'max:16', new CardRule()],
            'card.holder_name' => 'required|string|min:2|max:40',
            'card.cvc' => ['required', 'string', 'min:3', 'max:4', new CVCRule($this)],
            'child_quantity' => 'required',
            'items_id' => 'array'
        ];
    }
}
