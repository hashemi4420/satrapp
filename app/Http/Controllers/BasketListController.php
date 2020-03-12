<?php

namespace App\Http\Controllers;

use App\ArticleProvider;
use App\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        if (Auth::user()->workFieldArticle != null && Auth::user()->workFieldArticle != "") {

            $articles = ArticleProvider::where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->paginate(20);
            return view('manage.pages.basketList', compact('articles'));

        } elseif (Auth::user()->workFieldService != null && Auth::user()->workFieldService != "") {
            $services = ServiceProvider::where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->paginate(20);
            return view('manage.pages.basketList', compact('services'));
        }
    }

    public function save(Request $request)
    {
        if (Auth::user()->workFieldArticle != null && Auth::user()->workFieldArticle != "") {

            $articles = ArticleProvider::where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->get();

            foreach ($articles as $article){
                $darsad = $request['darsad'.$article->id];
                $pey = $request['pey'.$article->id];
                $atless = $request['atless'.$article->id];
                if( ($darsad != null && $darsad > 0) || ($pey != null && $pey > 0) || ($atless != null && $atless > 0)){

                    if($pey == null){
                        $article->price = 0;
                    }else {
                        $article->price = $pey;
                    }
                    if($darsad == null){
                        $article->status = 0;
                    }else {
                        $article->status = $darsad;
                    }
                    if($atless == null){
                        $article->atless = 0;
                    }else {
                        $article->atless = $atless;
                    }
                    $article->save();
                }
            }

        } elseif (Auth::user()->workFieldService != null && Auth::user()->workFieldService != "") {
            $services = ServiceProvider::where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->get();

            foreach ($services as $service){
                $darsad = $request['darsad'.$service->id];
                $pey = $request['pey'.$service->id];
                $atless = $request['atless'.$service->id];
                if( ($darsad != null && $darsad > 0) || ($pey != null && $pey > 0) || ($atless != null && $atless > 0)){

                    if($pey == null){
                        $service->price = 0;
                    }else {
                        $service->price = $pey;
                    }
                    if($darsad == null){
                        $service->status = 0;
                    }else {
                        $service->status = $darsad;
                    }
                    if($atless == null){
                        $service->atless = 0;
                    }else {
                        $service->atless = $atless;
                    }
                    $service->save();
                }
            }
        }

        return view('manage.index');
    }

    public function deleteArticle(Request $request){
        $article = ArticleProvider::find($request->id);
        $article->delete();
    }

    public function deleteService(Request $request){
        $service = ServiceProvider::find($request->id);
        $service->delete();
    }

    public function updateArticle(Request $request){
        $article = ArticleProvider::find($request->id);
        if ($article->active){
            $article->active = 0;
        } else {
            $article->active = 1;
        }
        $article->save();
    }

    public function updateService(Request $request){
        $service = ServiceProvider::find($request->id);
        if ($service->active){
            $service->active = 0;
        } else {
            $service->active = 1;
        }
        $service->save();
    }
}
