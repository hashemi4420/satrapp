<?php

namespace App\Http\Controllers;

use App\ArticleArea;
use App\ArticleGroup;
use App\Http\Requests\ArticleGroupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_11'] == "on") {
            $groups = ArticleGroup::orderBy('id', 'DESC')->paginate(20);
            $areas = ArticleArea::all();

            (new LogHistoryController)->logSave(Auth::user()->id, 11, true, false, false, false, false, false);

            return view('manage.pages.articleGroup', compact('groups', 'areas'));
        } else {
            abort(404);
        }
    }

    public function save(ArticleGroupRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['insert_11'] == "on") {
            ArticleGroup::create([
                'title' => trim($request->nameId),
                'area_id' => trim($request->areaId),
                'timestamp' => time(),
            ]);

            $groups = ArticleGroup::orderBy('id', 'DESC')->paginate(20);
            $areas = ArticleArea::all();

            (new LogHistoryController)->logSave(Auth::user()->id, 11, false, false, false, true, false, false);

            return view('manage.pages.articleGroup', compact('groups', 'areas'));
        } else {
            abort(404);
        }
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_11'] == "on") {
            $areaId = null;
            $name = null;
            $groups = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;

            if (trim($request->name) != "") {
                $name = trim($request->name);
            }

            if ($request->areaId != null) {
                $areaId = $request->areaId;
            }

            if ($name != null && $areaId != null) {
                $groups = ArticleGroup::where('title', 'like', '%' . $name . '%')
                    ->where('area_id', '=', $areaId)->get();
            } else if ($name != null) {
                $groups = ArticleGroup::where('title', 'like', '%' . $name . '%')->get();
            } else if ($areaId != null) {
                $groups = ArticleGroup::where('area_id', '=', $areaId)->get();
            }

            if ($groups != null) {
                $result = "";

                foreach ($groups as $group) {
                    $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td>' . $group->area->title . '</td>
                            <td>' . $group->title . '</td>';
                    if ($accessList['update_11'] == "on") {
                        $result .= '<td>
                                <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                        onclick="startUpdateArticleGroup(' . $group->id . ', document.getElementById(\'token\').value, \'#groupId\', \'#areaId\', \'#nameId\')">
                                        <i class="icon-search4"></i>
                                </button>
                            </td>';
                    }
                    if ($accessList['delete_11'] == "on") {
                        $result .= '<td>
                                <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                        onclick="deleteArticleGroup(' . $group->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-trash"></i>
                                </button>
                            </td>';
                    }
                    $result .= '</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 11, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $areas = ArticleArea::all();
        $group = ArticleGroup::find($request->id);

        $areaSelector = '<option value="">انتخاب کنید</option>';


        foreach ($areas as $area){
            if($area->id == $group->area_id)
            {
                $areaSelector .= '<option value="' . $area->id . '" selected>' . $area->title . '</option>';
            }
            else
            {
                $areaSelector .= '<option value="' . $area->id . '">' . $area->title . '</option>';
            }
        }

        $result = $group->id . "_:_" . $group->title . "_:_" . $areaSelector;

        return $result;
    }

    public function update(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['update_11'] == "on") {
            $group = ArticleGroup::find($request->id);

            $group->title = $request->name;
            $group->area_id = $request->areaId;

            $group->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 11, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function delete(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['delete_11'] == "on") {
            $group = ArticleGroup::find($request->id);

            $group->delete();

            (new LogHistoryController)->logSave(Auth::user()->id, 11, true, false, false, false, false, false);
        } else {
            abort(404);
        }
    }

}