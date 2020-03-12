<?php

namespace App\Http\Controllers\Auth;

use App\BusinessNumber;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/manage/index';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'phone' => ['required', 'string', 'max:11', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'rools' => ['required'],
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:users'],
            'family' => ['required', 'string', 'min:4', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        //dd($data);
        if(key_exists('news', $data)){
            if($data['news'] == 'on'){
                $news = 1;
            } else{
                $news = 0;
            }
        } else{
            $news = 0;
        }
        if(key_exists('rools', $data)){
            if($data['rools'] == 'on'){
                $rolls = 1;
            } else{
                $rolls = 0;
            }
        } else{
            $rolls = 0;
        }

        $user = User::create([
            'phone' => trim($data['phone']),
            'email' => trim($data['email']),
            'name' => trim($data['name']),
            'family' => trim($data['family']),
            'password' => Hash::make(trim($data['password'])),
            'timestamp' => time(),
            'userRole' => 3,
            'email_verify_code' => rand(1000, 9999),
            'phone_verify_code' => rand(1000, 9999),
            'articleBrand_id' => null,
            'serviceBrand_id' => null,
            'news'=>$news,
            'rools'=>$rolls,
        ]);

        $number = $this->createBusinessNumber();

        BusinessNumber::create([
            'user_id' => $user->id,
            'number' => $number,
            'timestamp' => time(),
        ]);

        return $user;
    }

    protected function createBusinessNumber(){
        while (1 == 1){
            $number = '019'.rand(10000000, 99999999);
            $check = BusinessNumber::where('number', '=', $number)->first();
            if($check === null){
                return $number;
            }
        }
    }
}
