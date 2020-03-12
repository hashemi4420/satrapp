<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleProviderRequest extends FormRequest
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
            'article' => ['required', 'integer'],
            'price' => ['required', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'article.required' => 'کالای مورد نظر را انتخاب نمایید',
            'price.required' => 'قیمت کالا باید وارد شود',
        ];
    }
}