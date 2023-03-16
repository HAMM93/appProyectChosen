<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonorMediaRequest extends FormRequest
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

        if (strpos($name_controller, '@generateZipWithDonorImages') !== false)
            return $this->validationGenerateZipWithDonorImages();

        if (strpos($name_controller, '@changeDonorPhoto') !== false)
            return $this-> validationChangeDonorPhoto();
    }

    private function validationIndex(): array
    {
        return [
            'term' => 'sometimes|string|min:2|max:128',
            'validation_status' => 'required|in:all,pending,approved,reproved',
            'donation_status' => 'required|in:all,pendent,refused,paid,processing',
            'pg' => 'required|numeric|min:1',
            'pp' => 'required|numeric|min:1'
        ];
    }

    private function validationStore(): array
    {
        return [
            'donor_photo_base64' => 'required|string',
        ];
    }

    private function validationChangeDonorPhoto(): array
    {
        return [
            'photo_b64' => 'required|string'
        ];
    }

    private function validationGenerateZipWithDonorImages(): array
    {
        return [
            'donors_id' => 'required|array|min:1|max:20'
        ];
    }
}
