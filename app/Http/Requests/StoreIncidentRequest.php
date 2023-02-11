<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncidentRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'district_id' => 'required|string',
            'address' => 'required|string',
            'name' => 'string|nullable',
            'document_type_other' => 'string|nullable',
            'document_type' => 'string|nullable',
            'document' => 'string|nullable',
            'car_number' => 'string|nullable',
            'car_type' => 'string|nullable',
            'car_brand' => 'string|nullable',
            'car_model' => 'string|nullable',
            'car_color' => 'string|nullable',
            'comment' => 'required|string',
        ];
    }
}
