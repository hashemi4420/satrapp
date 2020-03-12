<?php

namespace App\Http\Controllers;

use App\ArticleArea;
use App\ServiceArea;
use App\State;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkPassword(Request $request){
        $user = User::find($request->id);

        if(Hash::check(trim($request->password), $user->password)){
            return "ok";
        } else {
            return "no";
        }
    }

    public function changePass(Request $request){
        $user = User::find($request->id);

        $user->password = Hash::make(trim($request->password));

        $user->save();
    }

    public function changeTheme(Request $request){
        $user = User::find($request->id);

        $user->theme = trim($request->theme);

        $user->save();
    }

    public function show()
    {
        $states = State::all();
        $articles = ArticleArea::all();
        $services = ServiceArea::all();

        return view('manage.pages.profile', compact('states', 'articles', 'services'));
    }

    public function save(Request $request){
        $workFieldArticle = Auth::user()->workFieldArticle;
        $workFieldService = Auth::user()->workFieldService;
        $workLocal = Auth::user()->workLocal;

        if ($request->article != "") {
            $workFieldArticle = json_encode($request->article);
        }
        if ($request->service != "") {
            $workFieldService = json_encode($request->service);
        }
        if ($request->state != "") {
            $workLocal = json_encode($request->state);
        }
        $avatar = null;
        if ($request->avatar != "") {
            $image = $request->file('avatar');
            $name = time() . Auth::user()->phone . '.' . $image->getClientOriginalExtension();

            $destination = '/images/avatar';
            (new FileController)->save($destination, $name, $image);

            $avatar = trim($destination.'/'.$name);
        }

        $user = User::find(Auth::user()->id);

        $user->name = $request->name;
        $user->family = $request->family;
        $user->title_company = $request->company;
        $user->url_avatar = $avatar;
        $user->workFieldArticle = $workFieldArticle;
        $user->workFieldService = $workFieldService;
        $user->workLocal = $workLocal;

        $user->save();

        $states = State::all();
        $articles = ArticleArea::all();
        $services = ServiceArea::all();

        return view('manage.pages.profile', compact('states', 'articles', 'services'));
    }
}