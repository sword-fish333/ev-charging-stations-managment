<?php

namespace App\Http\Requests\Api;

use App\Traits\ApiValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreStationRequest extends FormRequest
{
    use ApiValidation;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required|string|max:250',
            'address'=>'required|string|max:250',
            'company_id'=>'required|exists:company,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ];
    }
}
