<?php

namespace App\Http\Requests;

use App\Rules\LetterOfChildRule;
use Illuminate\Foundation\Http\FormRequest;

class RevelationRequest extends FormRequest
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
    public function rules(): array
    {
        $name_controller = $this->route()->action['controller'];

        if (strpos($name_controller, '@index') !== false)
            return $this->validationIndex();

        if (strpos($name_controller, '@store') !== false)
            return $this->validationStore();

        if (strpos($name_controller, '@show') !== false)
            return $this->validationShow();

        if (strpos($name_controller, '@getRevelationsOccurred') !== false)
            return $this->validationGetRevelationsOccurred();
    }

    private function validationIndex(): array
    {
        return [
            'term' => 'sometimes|min:1',
            'pp' => 'required|numeric|min:1',
            'pg' =>'required|numeric|min:1'
        ];
    }

    private function validationStore(): array
    {
        return [
            'donor_id' => 'required|numeric|min:1',
            'child_id' => 'required|string|min:1',
            'child_name' => 'required|string|min:1',
            'child_city' => 'required|string|min:1',
            'child_photo' => 'required|string:min1',
            'child_video' => 'required|string:min1',
            'letter_photo' => ['required', new LetterOfChildRule($this)]
        ];
    }

    private function validationShow(): array
    {
        return [
            'pp' => 'required|numeric|min:1',
            'pg' => 'required|numeric|min:1'
        ];
    }

    private function validationGetRevelationsOccurred(): array
    {
        return [
            'donor_id' => 'sometimes|int|min:1',
            'child_id' => 'sometimes|string|min:1',
        ];
    }
}
