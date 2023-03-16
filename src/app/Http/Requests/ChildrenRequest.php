<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChildrenRequest extends FormRequest
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

        if (strpos($name_controller, '@generateZipWithChildrenImages') !== false)
            return $this->validationGenerateZipWithChildrenImages();
    }

    private function validationGenerateZipWithChildrenImages(): array
    {
        return [
            'children_id' => 'required|array|min:1|max:15'
        ];
    }
}
