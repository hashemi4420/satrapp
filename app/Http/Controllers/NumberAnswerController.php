<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NumberAnswerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('manage.pages.numberAnswer');
    }

    public function save(Request $request){
        $user = User::find(Auth::user()->id);

        $user->numberFree = trim($request->free);
        $user->numberPey = trim($request->pey);

        $user->save();

        Auth::user()->numberFree = trim($request->free);
        Auth::user()->numberPey = trim($request->pey);

        return view('manage.pages.numberAnswer');
    }
}
