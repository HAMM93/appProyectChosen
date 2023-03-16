<?php

namespace App\Http\Requests;

use App\Rules\DateRule;
use App\Types\PackageTypes;
use Illuminate\Foundation\Http\FormRequest;
use phpDocumentor\Reflection\Utils;

class PackageRequest extends FormRequest
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
        $name_controller = $this->route()->action['controller'];

        if (strpos($name_controller, '@index') !== false)
            return $this->validationIndex();

        if (strpos($name_controller, '@show') !== false)
            return $this->validationShow();

        if (strpos($name_controller, '@update') !== false)
            return $this->validationUpdate();
    }

    private function validationShow(): array
    {
        return [
            'pg' => 'required|numeric|min:1',
            'pp' => 'required|numeric|min:1'
        ];
    }

    private function validationIndex(): array
    {
        return [
            'term' => 'sometimes|min:1|max:128',
            'pg' => 'required|numeric|min:1',
            'pp' => 'required|numeric|min:1',
            'status' => 'required|in:' . PackageTypes::ALL . ','
                . PackageTypes::PENDING . ','
                . PackageTypes::SCHEDULED . ','
                . PackageTypes::ACCOMPLISHED
        ];
    }

    private function validationUpdate(): array
    {
        return [
            'event_date' => ['required', 'date_format:Y-m-d H:i:s', new DateRule()]
        ];
    }
}
