<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleGroupRequest extends FormRequest
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
            'areaId' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'nameId.required' => 'نام گروه کالا باید وارد شود',
            'areaId.required' => 'حوزه کالا باید انتخاب شود',
        ];
    }
}
