<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ArticleBrandRequest extends FormRequest
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
        if(Auth::user()->type == 'admin')
        {
            return [
                'name' => ['required', 'string'],
                'avatar' => ['required', 'image', 'max:100', 'mimes:jpeg,png'],
            ];
        } else
        {
            return [
                'name' => ['required', 'string'],
                'avatar' => ['required', 'image', 'max:2048', 'mimes:jpeg,png'],
            ];
        }
    }

    public function messages()
    {
        if(Auth::user()->type == 'admin')
        {
            return [
                'name.required' => 'نام برند باید وارد شود',
                'avatar.required' => 'یک تصویر باید انتخاب شود',
                'avatar.uploaded' => 'حداکثر سایز مجاز 100 کیلوبایت می باشد',
                'avatar.mimes' => 'فرمت مجاز فقط JPEG و PNG می باشد',
                'avatar.image' => 'فرمت مجاز فقط JPEG و PNG می باشد',
            ];
        } else
        {
            return [
                'name.required' => 'نام برند باید وارد شود',
                'avatar.required' => 'یک تصویر باید انتخاب شود',
                'avatar.uploaded' => 'حداکثر سایز مجاز 2 مگابایت می باشد',
                'avatar.mimes' => 'فرمت مجاز فقط JPEG و PNG می باشد',
                'avatar.image' => 'فرمت مجاز فقط JPEG و PNG می باشد',
            ];
        }
    }
}