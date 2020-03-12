<?php

namespace App\Http\Controllers;

use App\ArticleArea;
use App\ArticleBrand;
use App\ArticleCategory;
use App\ArticleCreator;
use App\ArticleGroup;
use App\Http\Requests\ArticleCreatorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleCreatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_13'] == "on") {
            $articles = null;
            if (Auth::user()->userRole == 2) {
                $articles = ArticleCreator::where('brand_id', '=', Auth::user()->articleBrand_id)->orderBy('id', 'DESC')->paginate(20);
            } else if (Auth::user()->userRole == 3) {
                $articles = ArticleCreator::where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->paginate(20);
            } else {
                $articles = ArticleCreator::orderBy('id', 'DESC')->paginate(20);
            }
            if (Auth::user()->userRole == 3) {
                $workField = json_decode(Auth::user()->workFieldArticle);
                if($workField != null){
                    $areas = ArticleArea::whereIn('id', $workField)->get();
                }
            } else {
                $areas = ArticleArea::all();
            }
            $brands = null;
            if (Auth::user()->userRole == 2) {
                $brands = ArticleBrand::where('id', '=', Auth::user()->articleBrand_id)->get();
            } else {
                $brands = ArticleBrand::all();
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 13, true, false, false, false, false, false);

            return view('manage.pages.articleCreator', compact('articles', 'areas', 'brands'));
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

    public function save(ArticleCreatorRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['insert_13'] == "on") {
            $image = $request->file('avatar');
            $name = time() . Auth::user()->phone . '.' . $image->getClientOriginalExtension();
            $destination = '/images/product/article';

            (new FileController)->save($destination, $name, $image);

//        date_default_timezone_set("Asia/Tehran");

            ArticleCreator::create([
                'title' => trim($request->name),
                'url_avatar' => trim($destination.'/'.$name),
                'brand_id' => $request->brand,
                'area_id' => $request->area,
                'group_id' => $request->group,
                'category_id' => $request->category,
                'timestamp' => time(),
                'user_id' => Auth::user()->id,
            ]);

            $articles = null;
            if (Auth::user()->userRole == 2) {
                $articles = ArticleCreator::where('brand_id', '=', Auth::user()->articleBrand_id)->orderBy('id', 'DESC')->paginate(20);
            } else if (Auth::user()->userRole == 3) {
                $articles = ArticleCreator::where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->paginate(20);
            } else {
                $articles = ArticleCreator::orderBy('id', 'DESC')->paginate(20);
            }
            if (Auth::user()->userRole == 3) {
                $workField = json_decode(Auth::user()->workFieldArticle);
                if($workField != null){
                    $areas = ArticleArea::whereIn('id', $workField)->get();
                }
            } else {
                $areas = ArticleArea::all();
            }
            $brands = null;
            if (Auth::user()->userRole == 2) {
                $brands = ArticleBrand::where('id', '=', Auth::user()->articleBrand_id)->get();
            } else {
                $brands = ArticleBrand::all();
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 13, false, false, false, true, false, false);
            return view('manage.pages.articleCreator', compact('articles', 'areas', 'brands'));
        } else {
            abort(404);
        }
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_13'] == "on") {
            $articles = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;

            $select = 'SELECT
                            article_creators.id AS id,
                            article_creators.url_avatar AS avatar,
                            article_creators.title AS title,
                            article_brands.title AS brand,
                            article_areas.title AS area,
                            article_groups.title AS groups,
                            article_categories.title AS category,
                            article_categories.active AS active,
                            users.email AS users
                        FROM article_creators
                        INNER JOIN article_areas ON article_areas.id = article_creators.area_id
                        INNER JOIN article_groups ON article_groups.id = article_creators.group_id
                        INNER JOIN article_categories ON article_categories.id = article_creators.category_id
                        INNER JOIN article_brands ON article_brands.id = article_creators.brand_id
                        LEFT JOIN users ON users.id = article_creators.user_id
                        WHERE 1 =1';

            if (Auth::user()->userRole == 2) {
                $select .= ' AND article_creators.brand_id = ' . trim(Auth::user()->articleBrand_id);
            } elseif (trim($request->brand) != "") {
                $select .= ' AND article_creators.brand_id = ' . trim($request->brand);
            }

            if(Auth::user()->userRole == 3) {
                $select .= ' AND article_creators.user_id = ' . trim(Auth::user()->id);
            }

            if (trim($request->area) != "") {
                $select .= ' AND article_creators.area_id = ' . trim($request->area);
            }

            if (trim($request->group) != "") {
                $select .= ' AND article_creators.group_id = ' . trim($request->group);
            }

            if (trim($request->category) != "") {
                $select .= ' AND article_creators.category_id = ' . trim($request->category);
            }

            if (trim($request->name) != "") {
                $select .= ' AND article_creators.title LIKE "%' . trim($request->name) . '%"';
            }

            if (strpos($select, 'AND') !== false) {
                $articles = DB::select(DB::raw($select));
            }

            if ($articles != null) {
                $result = "";

                foreach ($articles as $article) {
                    $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td><div class="list-unstyled row clearfix aniimated-thumbnials">
                                                                <a href="' . $article->avatar . '" data-sub-html="' . $article->title . '">
                                                                    <img class="img-responsive thumbnail" src="' . $article->avatar . '" alt="' . $article->title . '" width="35">
                                                                </a>
                                                            </div>
                            <td>' . $article->title . '</td>
                            <td>' . $article->brand . '</td>
                            <td>' . $article->area . '</td>
                            <td>' . $article->groups . '</td>
                            <td>' . $article->category . '</td>';

                    if (Auth::user()->userRole != 3){
                        $result .= '<td>' . $article->users . '</td>';
                    }

                    if ($accessList['update_13'] == "on") {
                        $result .= '<td>
                                <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                        onclick="startUpdateArticleCreator(' . $article->id . ', document.getElementById(\'token\').value, \'#articleId\', \'#name\', \'#thumb-output\', \'#brand\', \'#area\', \'#group\', \'#category\')">
                                        <i class="icon-search4"></i>
                                </button>
                            </td>';

                        if ($article->active) {
                            $result .= '<td id="' . $article->id . '">
                                    <button type="button" class="btn btn-success btn-icon btn-rounded"
                                            onclick="activeArticle(' . $article->id . ', document.getElementById(\'token\').value)">
                                            <i class="icon-user-check"></i>
                                    </button>
                                </td>';
                        } else {
                            $result .= '<td id="' . $article->id . '">
                                    <button type="button" class="btn bg-orange-400 btn-icon btn-rounded"
                                            onclick="activeArticle(' . $article->id . ', document.getElementById(\'token\').value)">
                                            <i class="icon-user-block"></i>
                                    </button>
                                </td>';
                        }
                    }

                    if ($accessList['delete_13'] == "on"){
                        $result .= '<td>
                                <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                        onclick="deleteArticleCreator(' . $article->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-trash"></i>
                                </button>
                            </td>';
                    }

                    $result .= '</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 13, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $article = ArticleCreator::find($request->id);

        $areas = ArticleArea::all();
        $areaSelector = '<option value="">انتخاب کنید</option>';

        foreach ($areas as $area)
        {
            if($area->id == $article->area_id)
            {
                $areaSelector .= '<option value="' . $area->id . '" selected>' . $area->title . '</option>';
            }
            else
            {
                $areaSelector .= '<option value="' . $area->id . '">' . $area->title . '</option>';
            }
        }

        $groups = ArticleGroup::where('area_id', '=', $article->area_id)->get();
        $groupSelector = '<option value="">انتخاب کنید</option>';

        foreach ($groups as $group)
        {
            if($group->id == $article->group_id)
            {
                $groupSelector .= '<option value="' . $group->id . '" selected>' . $group->title . '</option>';
            }
            else
            {
                $groupSelector .= '<option value="' . $group->id . '">' . $group->title . '</option>';
            }
        }

        $categories = ArticleCategory::where('group_id', '=', $article->group_id)->get();
        $categorySelector = '<option value="">انتخاب کنید</option>';

        foreach ($categories as $category){
            if($category->id == $article->category_id)
            {
                $categorySelector .= '<option value="' . $category->id . '" selected>' . $category->title . '</option>';
            }
            else
            {
                $categorySelector .= '<option value="' . $category->id . '">' . $category->title . '</option>';
            }
        }

        $result = $article->id . "_:_" . $article->title . "_:_" . $article->url_avatar . "_:_" . $article->brand_id
            . "_:_" . $areaSelector . "_:_" . $groupSelector . "_:_" . $categorySelector;

        return $result;
    }

    public function update(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['update_13'] == "on") {
            $article = ArticleCreator::find($request->id);

            $article->title = $request->name;
            $article->brand_id = $request->brand;
            $article->area_id = $request->area;
            $article->group_id = $request->group;
            $article->category_id = $request->category;

            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $name = time() . Auth::user()->phone . '.' . $image->getClientOriginalExtension();
                $destination = '/images/product/article';

                (new FileController)->save($destination,$name,$image);

                (new FileController)->delete($article->url_avatar);

                $article->url_avatar = trim($destination.'/'.$name);
            }

            $article->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 13, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function delete(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['delete_13'] == "on") {
            $article = ArticleCreator::find($request->id);

            (new FileController)->delete($article->url_avatar);

            $article->delete();

            (new LogHistoryController)->logSave(Auth::user()->id, 13, false, false, false, false, true, false);
        } else {
            abort(404);
        }
    }

    public function active(Request $request){
        $this->accessLevel = Auth::user()->usrRole;
        $this->accessList = json_decode($this->accessLevel->json, true);
        if($this->accessList['update_13'] == "on") {
            $article = ArticleCreator::find($request->id);

            if ($article->active) {
                $active = 0;
            } else {
                $active = 1;
                if($this->accessList['access_13'] == "on") {
                    $article->accept = 'تایید شده';
                }
            }

            $article->active = $active;

            $article->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 13, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

}