<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceGroupRequest extends FormRequest
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
            'areaId' => ['required', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام گروه خدمات باید وارد شود',
            'areaId.required' => 'حوزه مورد نظر را انتخاب نمایید',
        ];
    }
}
