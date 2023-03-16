<?php

namespace App\Http\Requests;

use App\Helpers\SanitizeHelper;
use App\Rules\DocumentRule;
use App\Rules\PhoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class DonorRequest extends FormRequest
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
     * @return array|string[]|void
     */
    public function rules()
    {
        $name_controller = $this->route()->action['controller'];

        if (strpos($name_controller, '@store') !== false)
            return $this->validationStore();

        if (strpos($name_controller, '@update') !== false)
            return $this->validationUpdate();

        if (strpos($name_controller, '@listDonorsByLastDonation') !== false)
            return $this-> validationListDonorsByLastDonation();
    }

    private function validationStore(): array
    {
        $sanitize_rule = [
            'foreign_person' => 'bool'
        ];

        $sanitized = SanitizeHelper::make($this->all(), $sanitize_rule);

        $this->merge($sanitized);

        return [
            'first_name' => 'required|string|max:30',
            'last_name' => 'required|string|max:80',
            'email' => 'required|unique:donors|email:rfc,filter',
            'phone' => ['required', new PhoneNumberRule],
            'document_data' => ['sometimes', new DocumentRule],
            'ocupation' => 'sometimes|max:50',
            'birthdate' => 'sometimes|date_format:Y-m-d',
            'gender' => 'sometimes|in:F,M,none',
            'address_street' => 'required|max:160',
            'address_number' => 'required|string|max:10',
            'address_complement' => 'sometimes|max:255',
            'address_zipcode' => 'required|numeric',
            'address_neightborhood' => 'required|max:50',
            'address_city' => 'required|max:50',
            'address_state' => 'required|max:50',
            'foreign_person' => 'required|in:true,false'
        ];
    }

    private function validationUpdate(): array
    {
        return [
            'name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email:rfc,filter',
            'phone' => ['sometimes', new PhoneNumberRule],
            'document_data' => ['sometimes', new DocumentRule],
            'ocupation' => 'sometimes|max:50',
            'birthdate' => 'sometimes|date_format:Y-m-d',
            'gender' => 'sometimes|in:F,M,none',
            'address_street' => 'sometimes|max:160',
            'address_number' => 'sometimes|string|max:10',
            'address_complement' => 'sometimes|max:255',
            'address_zipcode' => 'sometimes|numeric',
            'address_neightborhood' => 'sometimes|max:50',
            'address_city' => 'sometimes|max:50',
            'address_state' => 'sometimes|max:50',
        ];
    }

    private function validationListDonorsByLastDonation(): array
    {
        return [
            'term' => 'sometimes|string|min:2|max:128',
            'donation_status' => 'required|in:all,pendent,refused,paid,processing',
            'pg' => 'required|numeric|min:1',
            'pp' => 'required|numeric|min:1'
        ];
    }
}
