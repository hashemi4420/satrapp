<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceProviderRequest extends FormRequest
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
            'service' => ['required', 'integer'],
            'price' => ['required', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'service.required' => 'خدمات مورد نظر را انتخاب نمایید',
            'price.required' => 'قیمت خدمات باید وارد شود',
        ];
    }
}
