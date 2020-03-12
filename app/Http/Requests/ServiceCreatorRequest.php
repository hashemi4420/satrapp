<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ServiceCreatorRequest extends FormRequest
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
                'brand' => ['required', 'integer'],
                'area' => ['required', 'integer'],
                'group' => ['required', 'integer'],
                'category' => ['required', 'integer'],
                'avatar' => ['required', 'image', 'max:100', 'mimes:jpeg,png'],
            ];
        } else
        {
            return [
                'name' => ['required', 'string'],
                'brand' => ['required', 'integer'],
                'area' => ['required', 'integer'],
                'group' => ['required', 'integer'],
                'category' => ['required', 'integer'],
                'avatar' => ['required', 'image', 'max:2048', 'mimes:jpeg,png'],
            ];
        }
    }

    public function messages()
    {
        if(Auth::user()->type == 'admin') {
            return [
                'name.required' => 'نام خدمات مورد نظر را وارد نمایید',
                'brand.required' => 'برند مورد نظر را انتخاب نمایید',
                'area.required' => 'حوزه مورد نظر را انتخاب نمایید',
                'group.required' => 'گروه مورد نظر را انتخاب نمایید',
                'category.required' => 'دسته مورد نظر را انتخاب نمایید',
                'avatar.required' => 'یک تصویر باید انتخاب شود',
                'avatar.uploaded' => 'حداکثر سایز مجاز 100 کیلوبایت می باشد',
                'avatar.mimes' => 'فرمت مجاز فقط JPEG و PNG می باشد',
                'avatar.image' => 'فرمت مجاز فقط JPEG و PNG می باشد',
            ];
        } else
        {
            return [
                'name.required' => 'نام خدمات مورد نظر را وارد نمایید',
                'brand.required' => 'برند مورد نظر را انتخاب نمایید',
                'area.required' => 'حوزه مورد نظر را انتخاب نمایید',
                'group.required' => 'گروه مورد نظر را انتخاب نمایید',
                'category.required' => 'دسته مورد نظر را انتخاب نمایید',
                'avatar.required' => 'یک تصویر باید انتخاب شود',
                'avatar.uploaded' => 'حداکثر سایز مجاز 2 مگابایت می باشد',
                'avatar.mimes' => 'فرمت مجاز فقط JPEG و PNG می باشد',
                'avatar.image' => 'فرمت مجاز فقط JPEG و PNG می باشد',
            ];
        }
    }
}