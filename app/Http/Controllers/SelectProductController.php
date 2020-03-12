<?php

namespace App\Http\Controllers;

use App\ArticleBrand;
use App\ArticleCreator;
use App\ArticleProvider;
use App\ServiceBrand;
use App\ServiceCreator;
use App\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SelectProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {
        $selected = null;
        if (Auth::user()->workFieldArticle != null && Auth::user()->workFieldArticle != "") {
            $brands = ArticleBrand::all();
            $areas = trim(Auth::user()->workFieldArticle);
            $results = ArticleCreator::where('brand_id', '=', $request->searchBrand)->where('area_id', '=', $areas)->orderBy('id', 'DESC')->paginate(20);
            return view('manage.pages.selectProduct', compact('brands', 'results', 'selected'));
        } elseif (Auth::user()->workFieldService != null && Auth::user()->workFieldService != "") {
            $brands = ServiceBrand::all();
            $areas = trim(Auth::user()->workFieldService);
            $results = ServiceCreator::where('brand_id', '=', $request->searchBrand)->where('area_id', '=', $areas)->orderBy('id', 'DESC')->paginate(20);
            return view('manage.pages.selectProduct', compact('brands', 'results', 'selected'));
        }
    }

    public function search(Request $request)
    {
        $selected = $request->searchBrand;
        if (Auth::user()->workFieldArticle != null && Auth::user()->workFieldArticle != "") {
            $brands = ArticleBrand::all();
            $areas = trim(Auth::user()->workFieldArticle);
            $results = ArticleCreator::where('brand_id', '=', $request->searchBrand)->where('area_id', '=', $areas)->orderBy('id', 'DESC')->paginate(20);
            return view('manage.pages.selectProduct', compact('brands', 'results', 'selected'));
        } elseif (Auth::user()->workFieldService != null && Auth::user()->workFieldService != "") {
            $brands = ServiceBrand::all();
            $areas = trim(Auth::user()->workFieldService);
            $results = ServiceCreator::where('brand_id', '=', $request->searchBrand)->where('area_id', '=', $areas)->orderBy('id', 'DESC')->paginate(20);
            return view('manage.pages.selectProduct', compact('brands', 'results', 'selected'));
        }
    }

    public function searchProduct(Request $request){
        $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
        $searches = array();
        $limit = 20;
        $skip = $request->page * $limit;
        $id = $skip + 1;

        if (Auth::user()->workFieldArticle != null && Auth::user()->workFieldArticle != "") {
            $areas = trim(Auth::user()->workFieldArticle);
            $searches = ArticleCreator::where('brand_id', '=', $request->id)->where('area_id', '=', $areas)->skip($skip)->take($limit)->get();
        } elseif (Auth::user()->workFieldService != null && Auth::user()->workFieldService != "") {
            $areas = trim(Auth::user()->workFieldService);
            $searches = ServiceCreator::where('brand_id', '=', $request->id)->whereIn('area_id', '=', $areas)->skip($skip)->take($limit)->get();
        }

        if ($searches != null) {
            $result = "";

            if (Auth::user()->workFieldArticle != null && Auth::user()->workFieldArticle != "") {
                foreach ($searches as $search){
                    $result .= '<tr>';
                    $result .= '<td>' . $id++ . '</td>';
                    $result .= '<td>
<div class="list-unstyled row clearfix aniimated-thumbnials">
                                                                <a href="' . $search->url_avatar . '" data-sub-html="' . $search->title . '">
                                                                    <img class="img-responsive thumbnail" src="' . $search->url_avatar . '" alt="' . $search->title . '" width="35">
                                                                </a>
                                                            </div>
                                                            </td>';
                    $result .= '<td>' . $search->brand->title . '</td>';
                    $result .= '<td>' . $search->title . '</td>';
                    $result .= '<td><input id="prise'.$search->id.'" type="number" min="0" value="0" style="width: 100px; color: #000000"></td>';
                    $result .= '<td><input id="persend'.$search->id.'" type="number" min="0" value="0" style="width: 50px; color: #000000"></td>';
                    $result .= '<td><input id="atless'.$search->id.'" type="number" min="0" value="0" style="width: 50px; color: #000000"></td>';
                    $result .= '<td><input type="checkbox" onchange="saveArticle('.$search->id.', document.getElementById(\'prise'.$search->id.'\').value, document.getElementById(\'persend'.$search->id.'\').value, document.getElementById(\'atless'.$search->id.'\').value, document.getElementById(\'token\').value)"></td>';
                    $result .= '</tr>';
                }
            } elseif (Auth::user()->workFieldService != null && Auth::user()->workFieldService != "") {
                foreach ($searches as $search){
                    $result .= '<tr>';
                    $result .= '<td>' . $id++ . '</td>';
                    $result .= '<td>
    <div class="list-unstyled row clearfix aniimated-thumbnials">
                                                                <a href="' . $search->url_avatar . '" data-sub-html="' . $search->service->title . '">
                                                                    <img class="img-responsive thumbnail" src="' . $search->url_avatar . '" alt="' . $search->service->title . '" width="35">
                                                                </a>
                                                            </div>
                                                            </td>';
                    $result .= '<td>' . $search->brand->title . '</td>';
                    $result .= '<td>' . $search->service->title . '</td>';
                    $result .= '<td><input id="prise'.$search->id.'" type="number" min="0" value="0" style="width: 100px; color: #000000"></td>';
                    $result .= '<td><input id="persend'.$search->id.'" type="number" min="0" value="0" style="width: 50px; color: #000000"></td>';
                    $result .= '<td><input id="atless'.$search->id.'" type="number" min="0" value="0" style="width: 50px; color: #000000"></td>';
                    $result .= '<td><input type="checkbox" onchange="saveService('.$search->id.', document.getElementById(\'prise'.$search->id.'\').value, document.getElementById(\'persend'.$search->id.'\').value, document.getElementById(\'atless'.$search->id.'\').value, document.getElementById(\'token\').value)"></td>';
                    $result .= '</tr>';
                }
            }
        }

        return $result;
    }

    public function saveArticle(Request $request){
        $article = ArticleProvider::where('article_id', '=', $request->id)->where('user_id', '=', Auth::user()->id)->first();
        if($article == null){
            ArticleProvider::create([
                'article_id' => trim($request->id),
                'price' => trim($request->prise),
                'status' => trim($request->persend),
                'atless' => trim($request->atless),
                'user_id' => Auth::user()->id,
                'active' => 1,
                'accept' => trim("تایید شده"),
                'timestamp' => time(),
            ]);
        } else {
            $article->price = $request->prise;
            $article->status = $request->persend;
            $article->atless = $request->atless;

            $article->save();
        }
    }

    public function saveService(Request $request){
        $service = ServiceProvider::where('service_id', '=', $request->id)->where('user_id', '=', Auth::user()->id)->first();
        if($service == null){
            ServiceProvider::create([
                'service_id' => trim($request->id),
                'price' => trim($request->prise),
                'status' => trim($request->persend),
                'atless' => trim($request->atless),
                'user_id' => Auth::user()->id,
                'active' => 1,
                'accept' => trim("تایید شده"),
                'timestamp' => time(),
            ]);
        } else {
            $service->price = $request->prise;
            $service->status = $request->persend;
            $service->atless = $request->atless;

            $service->save();
        }
    }
}
