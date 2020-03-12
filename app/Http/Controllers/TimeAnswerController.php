<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeAnswerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('manage.pages.timeAnswer');
    }

    public function save(Request $request){
        $user = User::find(Auth::user()->id);

        $user->saateKariFrom = trim($request->saateKariFrom);
        $user->saateKariTo = trim($request->saateKariTo);

        $user->save();

        Auth::user()->saateKariFrom = trim($request->saateKariFrom);
        Auth::user()->saateKariTo = trim($request->saateKariTo);

        return view('manage.pages.timeAnswer');
    }
}
