<?php

namespace App\Http\Controllers;

use App\ActiveFreeCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FreeCallController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $freeCall = ActiveFreeCall::where('user_id', '=', Auth::user()->id)->first();
        return view('manage.pages.freeCall', compact('freeCall'));
    }

    public function save(Request $request){
        $freeCall = ActiveFreeCall::where('user_id', '=', Auth::user()->id)->first();

        if ($freeCall != null){
            $freeCall->pey = $freeCall->pey + $request->price;
            $freeCall->save();
        } else {
            ActiveFreeCall::create([
                'user_id' => trim(Auth::user()->id),
                'pey' => $request->price,
                'active' => 1,
            ]);
        }
    }

    public function changeActivity(Request $request){
        $freeCall = ActiveFreeCall::where('user_id', '=', Auth::user()->id)->first();
        if ($freeCall != null){
            if($freeCall->active == 1){
                $freeCall->active = 0;
            } else {
                $freeCall->active = 1;
            }
            $freeCall->save();
        }
    }
}
