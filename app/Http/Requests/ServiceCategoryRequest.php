<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceCategoryRequest extends FormRequest
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
            'nameId' => ['required', 'string'],
            'areaId' => ['required', 'integer'],
            'groupId' => ['required', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'nameId.required' => 'نام دسته باید وارد شود',
            'areaId.required' => 'حوزه مورد نظر را انتخاب نمایید',
            'groupId.required' => 'گروه مورد نظر را انتخاب نمایید',
        ];
    }
}
