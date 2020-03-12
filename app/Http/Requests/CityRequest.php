<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'state' => ['required', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام شهر باید وارد شود',
            'state.required' => 'استان مورد نظر را انتخاب نمایید',
        ];
    }
}
