<?php

namespace App\Http\Requests;

use http\Env\Request;
use Illuminate\Foundation\Http\FormRequest;

class ResellerRequest extends FormRequest
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
            'family' => ['required', 'string'],
            'company' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['required', 'string', 'max:11', 'unique:users'],
            'numberFree' => ['required', 'string', 'max:11'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'avatar' => ['required', 'image', 'max:100', 'mimes:jpeg,png'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام کاربر باید وارد شود',
            'family.required' => 'نام خانوادگی کاربر باید وارد شود',
            'company.required' => 'نام شرکت باید وارد شود',
            'phone.required' => 'شماره همراه کاربر باید وارد شود',
            'numberFree.required' => 'شماره پاسخگو کاربر باید وارد شود',
            'phone.unique' => 'شماره همراه نمیتواند تکراری باشد',
            'email.required' => 'ایمیل کاربر باید وارد شود',
            'email.unique' => 'ایمیل نمیتواند تکراری باشد',
            'email.email' => 'فرمت ایمیل اشتباه است',
            'password.required' => 'کلمه عبور باید وارد شود',
            'password.min' => 'کلمه عبور باید بیشتر از 8 کاراکتر باشد',
            'avatar.image' => 'فرمت مجاز فقط JPEG و PNG می باشد',
        ];
    }
}
