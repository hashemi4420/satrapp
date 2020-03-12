<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AboutUsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('manage.pages.aboutUs');
    }

    public function save(Request $request){
        $user = User::find(Auth::user()->id);

        $user->about = trim($request->name);

        $user->save();

        Auth::user()->about = trim($request->name);

        return view('manage.pages.aboutUs');
    }
}
