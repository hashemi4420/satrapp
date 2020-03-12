<?php

namespace App\Http\Controllers;

use App\ArticleArea;
use App\ArticleBrand;
use App\ArticleCategory;
use App\ArticleCreator;
use App\ArticleGroup;
use App\ArticleProvider;
use App\Http\Requests\ArticleProviderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_14'] == "on") {
            $articles = null;
            if (Auth::user()->userRole == 2) {
                $articles = ArticleProvider::where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->paginate(20);
            } else {
                $articles = ArticleProvider::orderBy('id', 'DESC')->paginate(20);
            }
            if (Auth::user()->userRole == 3) {
                $workField = json_decode(Auth::user()->workFieldArticle);
                if($workField != null){
                    $areas = ArticleArea::whereIn('id', $workField)->get();
                }
            } else {
                $areas = ArticleArea::all();
            }
            $brands = ArticleBrand::all();

            (new LogHistoryController)->logSave(Auth::user()->id, 14, true, false, false, false, false, false);

            return view('manage.pages.articleProvider', compact('articles', 'areas', 'brands'));
        } else {
            abort(404);
        }
    }

    public function getGroup(Request $request){
        $areas = ArticleGroup::where('area_id', '=', $request->id)->get();
        $areaSelector = "";
        foreach ($areas as $area){
            $areaSelector .= '<option value="' . $area->id . '">' . $area->title . '</option>';
        }
        return $areaSelector;
    }

    public function getCategories(Request $request){
        $categories = ArticleCategory::where('group_id', '=', $request->id)->get();
        $categorySelector = "";
        foreach ($categories as $category){
            $categorySelector .= '<option value="' . $category->id . '">' . $category->title . '</option>';
        }
        return $categorySelector;
    }

    public function getArticle(Request $request){
        $articles = ArticleCreator::where('brand_id', '=', $request->brand)
                                        ->where('area_id', '=', $request->area)
                                        ->where('group_id', '=', $request->group)
                                        ->where('category_id', '=', $request->category)->get();
        $articleSelector = "";
        foreach ($articles as $article){
            $articleSelector .= '<option value="' . $article->id . '">' . $article->title . '</option>';
        }
        return $articleSelector;
    }

    public function save(ArticleProviderRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['insert_14'] == "on") {

//        date_default_timezone_set("Asia/Tehran");

            ArticleProvider::create([
                'article_id' => trim($request->article),
                'price' => $request->price,
                'status' => $request->status,
                'user_id' => Auth::user()->id,
                'accept' => 'تایید شده',
                'timestamp' => time(),
                'active' => 1,
            ]);

            $articles = null;
            if (Auth::user()->userRole == 2) {
                $articles = ArticleProvider::where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->paginate(20);
            } else {
                $articles = ArticleProvider::orderBy('id', 'DESC')->paginate(20);
            }
            if (Auth::user()->userRole == 3) {
                $workField = json_decode(Auth::user()->workFieldArticle);
                if($workField != null){
                    $areas = ArticleArea::whereIn('id', $workField)->get();
                }
            } else {
                $areas = ArticleArea::all();
            }
            $brands = ArticleBrand::all();

            (new LogHistoryController)->logSave(Auth::user()->id, 14, false, false, false, true, false, false);

            return view('manage.pages.articleProvider', compact('articles', 'areas', 'brands'));
        } else {
            abort(404);
        }
    }

    public function active(Request $request){
        $articleProvider = ArticleProvider::find($request->id);

        if($articleProvider->active){
            $active = 0;
        } else {
            $active = 1;
        }

        $articleProvider->active = $active;

        $articleProvider->save();
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_14'] == "on") {
            $brand = null;
            $area = null;
            $group = null;
            $category = null;
            $article = null;
            $price = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;
            $articles = null;

            $select = 'SELECT 
                        article_providers.id, 
                        article_providers.article_id, 
                        article_providers.price, 
                        article_providers.user_id, 
                        article_providers.`timestamp`,
                        article_providers.active, 
                        article_brands.title AS brand, 
                        article_areas.title AS area, 
                        article_groups.title AS `group`, 
                        article_categories.title AS category, 
                        article_creators.title AS title, 
                        article_creators.url_avatar AS url_avatar, 
                        users.email AS users
                    FROM article_providers 
                    INNER JOIN article_creators ON article_creators.id = article_providers.article_id 
                    INNER JOIN article_brands ON article_brands.id = article_creators.brand_id 
                    INNER JOIN article_areas ON article_areas.id = article_creators.area_id 
                    INNER JOIN article_groups ON article_groups.id = article_creators.group_id 
                    INNER JOIN article_categories ON article_categories.id = article_creators.category_id 
                    INNER JOIN users ON users.id = article_providers.user_id 
                    WHERE 1 =1';

            if (trim($request->brand) != "") {
                $brand = trim($request->brand);
                $select .= ' AND article_creators.brand_id = ' . $brand;
            }

            if(Auth::user()->userRole == 3) {
                $select .= ' AND article_providers.user_id = ' . trim(Auth::user()->id);
            }

            if (trim($request->area) != "") {
                $area = trim($request->area);
                $select .= ' AND article_creators.area_id = ' . $area;
            }

            if (trim($request->group) != "") {
                $group = trim($request->group);
                $select .= ' AND article_creators.group_id = ' . $group;
            }

            if (trim($request->category) != "") {
                $category = trim($request->category);
                $select .= ' AND article_creators.category_id = ' . $category;
            }

            if (trim($request->article) != "") {
                $article = trim($request->article);
                $select .= ' AND article_providers.article_id = ' . $article;
            }

            if (trim($request->price) != "") {
                $price = trim($request->price);
                $select .= ' AND article_providers.price = "' . $price . '"';
            }

            if (strpos($select, 'AND') !== false) {
                $articles = DB::select(DB::raw($select));
            }

            if ($articles != null) {
                $result = "";

                foreach ($articles as $article) {
                    $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td>
                                <div class="list-unstyled row clearfix aniimated-thumbnials">
                                                                <a href="' . $article->url_avatar . '" data-sub-html="' . $article->title . '">
                                                                    <img class="img-responsive thumbnail" src="' . $article->url_avatar . '" alt="' . $article->title . '" width="35">
                                                                </a>
                                                            </div>
                                </td>
                            <td>' . $article->title . '</td>
                            <td>' . $article->brand . '</td>
                            <td>' . $article->area . '</td>
                            <td>' . $article->group . '</td>
                            <td>' . $article->category . '</td>
                            <td>' . $article->price . '</td>';

                    if (Auth::user()->userRole != 3) {
                        $result .= '<td>' . $article->users . '</td>';
                    }

                    if ($accessList['update_14'] == "on") {
                        $result .= '<td>
                                <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                        onclick="startUpdateArticleProvider(' . $article->id . ', document.getElementById(\'token\').value, \'#providerId\', \'#brand\', \'#area\', \'#group\', \'#category\', \'#article\', \'#price\', \'#status\')">
                                        <i class="icon-search4"></i>
                                </button>
                            </td>';
                    }
                    if ($article->active) {
                        $result .= '<td>
                                <button type="button" class="btn btn-success btn-icon btn-rounded"
                                        onclick="deleteArticleCreator(' . $article->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-user-check"></i>
                                </button>
                            </td>';
                    } else {
                        $result .= '<td>
                                <button type="button" class="btn-secondary btn-icon btn-rounded"
                                        onclick="deleteArticleCreator(' . $article->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-user-block"></i>
                                </button>
                            </td>';
                    }

                    $result .= '</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 14, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $provider = ArticleProvider::find($request->id);

        $brands = ArticleBrand::all();
        $brandSelector = '<option value="">انتخاب کنید</option>';

        foreach ($brands as $brand)
        {
            if($brand->id == $provider->article->brand_id)
            {
                $brandSelector .= '<option value="' . $brand->id . '" selected>' . $brand->title . '</option>';
            }
            else
            {
                $brandSelector .= '<option value="' . $brand->id . '">' . $brand->title . '</option>';
            }
        }

        $areas = ArticleArea::all();
        $areaSelector = '<option value="">انتخاب کنید</option>';

        foreach ($areas as $area)
        {
            if($area->id == $provider->article->area_id)
            {
                $areaSelector .= '<option value="' . $area->id . '" selected>' . $area->title . '</option>';
            }
            else
            {
                $areaSelector .= '<option value="' . $area->id . '">' . $area->title . '</option>';
            }
        }

        $groups = ArticleGroup::where('area_id', '=', $provider->article->area_id)->get();
        $groupSelector = '<option value="">انتخاب کنید</option>';

        foreach ($groups as $group)
        {
            if($group->id == $provider->article->group_id)
            {
                $groupSelector .= '<option value="' . $group->id . '" selected>' . $group->title . '</option>';
            }
            else
            {
                $groupSelector .= '<option value="' . $group->id . '">' . $group->title . '</option>';
            }
        }

        $categories = ArticleCategory::where('group_id', '=', $provider->article->group_id)->get();
        $categorySelector = '<option value="">انتخاب کنید</option>';

        foreach ($categories as $category){
            if($category->id == $provider->article->category_id)
            {
                $categorySelector .= '<option value="' . $category->id . '" selected>' . $category->title . '</option>';
            }
            else
            {
                $categorySelector .= '<option value="' . $category->id . '">' . $category->title . '</option>';
            }
        }

        $articles = ArticleCreator::where('brand_id', '=', $provider->article->brand_id)
            ->where('area_id', '=', $provider->article->area_id)
            ->where('group_id', '=', $provider->article->group_id)
            ->where('category_id', '=', $provider->article->category_id)->get();
        $articleSelector = "";

        foreach ($articles as $article){
            if($article->id == $provider->article->id)
            {
                $articleSelector .= '<option value="' . $article->id . '" selected>' . $article->title . '</option>';
            }
            else {
                $articleSelector .= '<option value="' . $article->id . '">' . $article->title . '</option>';
            }
        }

        $result = $provider->id . "_:_" . $provider->price . "_:_" . $provider->status . "_:_" . $brandSelector
            . "_:_" . $areaSelector . "_:_" . $groupSelector . "_:_" . $categorySelector . "_:_" . $articleSelector;

        return $result;
    }

    public function update(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['delete_14'] == "on") {
            $article = ArticleProvider::find($request->id);

            $article->price = $request->price;
            $article->status = $request->sharh;
            $article->article_id = $request->article;

            $article->save();
        } else {
            abort(404);
        }
    }
}