<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpLogoRequest;
use App\UpgradeLogo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpLogoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $error = '';
        $success = '';
        return view('manage.pages.upLogo', compact('error', 'success'));
    }

    public function save(Request $request)
    {
        $cash = Auth::user()->cash;
        $error = '';
        $success = '';
        $upLogo = null;

        if($cash >= $request->number){

            if(isset($request->reseller)){
                if($request->reseller == 'on'){
                    $upLogo = UpgradeLogo::where('user_id', '=', Auth::user()->id)->where('page', '=', 'reseller')->get();
                    if($upLogo->count() == 0){
                        UpgradeLogo::create([
                            'user_id' => trim(Auth::user()->id),
                            'page' => 'reseller',
                            'active' => 1,
                            'pey' => 1,
                            'timestamp' => time(),
                        ]);
                    } else {
                        foreach ($upLogo as $item){
                            $count = $item->pey + 1;
                            $item->pey = $count;
                            $item->timestamp = time();
                            $item->save();
                        }
                    }
                }
            }

            if(isset($request->allReseller)){
                if($request->allReseller == 'on'){
                    $upLogo = UpgradeLogo::where('user_id', '=', Auth::user()->id)->where('page', '=', 'allReseller')->get();
                    if($upLogo->count() == 0){
                        UpgradeLogo::create([
                            'user_id' => trim(Auth::user()->id),
                            'page' => 'allReseller',
                            'active' => 1,
                            'pey' => 1,
                            'timestamp' => time(),
                        ]);
                    } else {
                        foreach ($upLogo as $item){
                            $count = $item->pey + 1;
                            $item->pey = $count;
                            $item->timestamp = time();
                            $item->save();
                        }
                    }
                }
            }

            if(isset($request->freeCall)){
                if($request->freeCall == 'on'){
                    $upLogo = UpgradeLogo::where('user_id', '=', Auth::user()->id)->where('page', '=', 'freeCall')->get();
                    if($upLogo->count() == 0){
                        UpgradeLogo::create([
                            'user_id' => trim(Auth::user()->id),
                            'page' => 'freeCall',
                            'active' => 1,
                            'pey' => 1,
                            'timestamp' => time(),
                        ]);
                    } else {
                        foreach ($upLogo as $item){
                            $count = $item->pey + 1;
                            $item->pey = $count;
                            $item->timestamp = time();
                            $item->save();
                        }
                    }
                }
            }

            if(isset($request->allFreeCall)){
                if($request->brand == 'on'){
                    $upLogo = UpgradeLogo::where('user_id', '=', Auth::user()->id)->where('page', '=', 'allFreeCall')->get();
                    if($upLogo->count() == 0){
                        UpgradeLogo::create([
                            'user_id' => trim(Auth::user()->id),
                            'page' => 'allFreeCall',
                            'active' => 1,
                            'pey' => 1,
                            'timestamp' => time(),
                        ]);
                    } else {
                        foreach ($upLogo as $item){
                            $count = $item->pey + 1;
                            $item->pey = $count;
                            $item->timestamp = time();
                            $item->save();
                        }
                    }
                }
            }

            if(isset($request->sagest)){
                if($request->sagest == 'on'){
                    $upLogo = UpgradeLogo::where('user_id', '=', Auth::user()->id)->where('page', '=', 'sagest')->get();
                    if($upLogo->count() == 0){
                        UpgradeLogo::create([
                            'user_id' => trim(Auth::user()->id),
                            'page' => 'sagest',
                            'active' => 1,
                            'pey' => 1,
                            'timestamp' => time(),
                        ]);
                    } else {
                        foreach ($upLogo as $item){
                            $count = $item->pey + 1;
                            $item->pey = $count;
                            $item->timestamp = time();
                            $item->save();
                        }
                    }
                }
            }

            if(isset($request->allSagest)){
                if($request->allSagest == 'on'){
                    $upLogo = UpgradeLogo::where('user_id', '=', Auth::user()->id)->where('page', '=', 'allSagest')->get();
                    if($upLogo->count() == 0){
                        UpgradeLogo::create([
                            'user_id' => trim(Auth::user()->id),
                            'page' => 'allSagest',
                            'active' => 1,
                            'pey' => 1,
                            'timestamp' => time(),
                        ]);
                    } else {
                        foreach ($upLogo as $item){
                            $count = $item->pey + 1;
                            $item->pey = $count;
                            $item->timestamp = time();
                            $item->save();
                        }
                    }
                }
            }

            $user = User::find(Auth::user()->id);

            $cash = $cash - $request->number;

            $user->cash = $cash;
            $user->save();

            $success = 'با موفقیت اعمال گردید.';
        } else {
            $error = 'موجودی شما کافی نمی باشد.';
        }

        return view('manage.pages.upLogo', compact('error', 'success'));
    }
}
