<?php

namespace App\Http\Controllers;

use App\ArticleArea;
use App\ArticleCategory;
use App\ArticleCreator;
use App\ArticleGroup;
use App\Http\Requests\ArticleCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_12'] == "on") {
            $categories = ArticleCategory::orderBy('id', 'DESC')->paginate(20);
            $areas = ArticleArea::all();

            (new LogHistoryController)->logSave(Auth::user()->id, 12, true, false, false, false, false, false);

            return view('manage.pages.articleCategory', compact('categories', 'areas'));
        } else {
            abort(404);
        }
    }

    public function getGroups(Request $request){
        $groups = ArticleGroup::where('area_id', '=', $request->id)->get();
        $groupSelector = "";
        foreach ($groups as $group){
            $groupSelector .= '<option value="' . $group->id . '">' . $group->title . '</option>';
        }
        return $groupSelector;
    }

    public function save(ArticleCategoryRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['insert_12'] == "on") {
            ArticleCategory::create([
                'title' => trim($request->nameId),
                'group_id' => trim($request->groupId),
                'area_id' => trim($request->areaId),
                'timestamp' => time(),
            ]);

            $categories = ArticleCategory::orderBy('id', 'DESC')->paginate(20);
            $areas = ArticleArea::all();

            (new LogHistoryController)->logSave(Auth::user()->id, 12, false, false, false, true, false, false);

            return view('manage.pages.articleCategory', compact('categories', 'areas'));
        } else {
            abort(404);
        }
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_12'] == "on") {
            $areaId = null;
            $groupId = null;
            $name = null;
            $categories = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;

            if (trim($request->name) != "") {
                $name = trim($request->name);
            }

            if ($request->areaId != null) {
                $areaId = $request->areaId;
            }

            if ($request->groupId != null) {
                $groupId = $request->groupId;
            }

            if ($name != null && $groupId != null && $areaId != null) {
                $categories = ArticleCategory::where('title', 'like', '%' . $name . '%')
                    ->where('group_id', '=', $groupId)
                    ->where('area_id', '=', $areaId)->get();
            } elseif ($name != null && $areaId != null) {
                $categories = ArticleCategory::where('title', 'like', '%' . $name . '%')
                    ->where('area_id', '=', $areaId)->get();
            } elseif ($groupId != null && $areaId != null) {
                $categories = ArticleCategory::where('group_id', '=', $groupId)
                    ->where('area_id', '=', $areaId)->get();
            } else if ($name != null) {
                $categories = ArticleCategory::where('title', 'like', '%' . $name . '%')->get();
            } else if ($areaId != null) {
                $categories = ArticleCategory::where('area_id', '=', $areaId)->get();
            }

            if ($categories != null) {
                $result = "";

                foreach ($categories as $category) {
                    $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td>' . $category->area->title . '</td>
                            <td>' . $category->group->title . '</td>
                            <td>' . $category->title . '</td>';
                    if ($accessList['update_12'] == "on"){
                        $result .= '<td>
                                <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                        onclick="startUpdateArticleCategory(' . $category->id . ', document.getElementById(\'token\').value, \'#categoryId\', \'#areaId\', \'#groupId\', \'#nameId\')">
                                        <i class="icon-search4"></i>
                                </button>
                            </td>';
                    }if ($accessList['delete_12'] == "on") {
                        $result .= '<td>
                                <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                        onclick="deleteArticleCategory(' . $category->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-trash"></i>
                                </button>
                            </td>';
                    }
                    $result .= '</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 12, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $areas = ArticleArea::all();
        $category = ArticleCategory::find($request->id);

        $groups = ArticleGroup::where('area_id', '=', $category->area->id)->get();

        $groupSelector = '<option value="">انتخاب کنید</option>';
        $areaSelector = '<option value="">انتخاب کنید</option>';


        foreach ($groups as $group){
            if($group->id == $category->group_id)
            {
                $groupSelector .= '<option value="' . $group->id . '" selected>' . $group->title . '</option>';
            }
            else
            {
                $groupSelector .= '<option value="' . $group->id . '">' . $group->title . '</option>';
            }
        }

        foreach ($areas as $area){
            if($area->id == $category->area_id)
            {
                $areaSelector .= '<option value="' . $area->id . '" selected>' . $area->title . '</option>';
            }
            else
            {
                $areaSelector .= '<option value="' . $area->id . '">' . $area->title . '</option>';
            }
        }

        $result = $category->id . "_:_" . $category->title . "_:_" . $groupSelector . "_:_" . $areaSelector;

        return $result;
    }

    public function update(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['update_12'] == "on") {
            $category = ArticleCategory::find($request->id);

            $category->title = $request->name;
            $category->area_id = $request->areaId;
            $category->group_id = $request->groupId;

            $category->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 12, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function delete(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['delete_12'] == "on") {
            $count = ArticleCreator::where('category_id', '=', $request->id)->count();

            if($count == 0){
                $category = ArticleCategory::find($request->id);

                $category->delete();

                (new LogHistoryController)->logSave(Auth::user()->id, 12, false, false, false, false, true, false);

                return 1;
            } else {
                return 0;
            }
        } else {
            abort(404);
        }
    }

}