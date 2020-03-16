<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceCategoryRequest;
use App\ServiceArea;
use App\ServiceCategory;
use App\ServiceCreator;
use App\ServiceGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_19'] == "on") {
            $categories = ServiceCategory::orderBy('id', 'DESC')->paginate(20);
            $areas = ServiceArea::all();

            (new LogHistoryController)->logSave(Auth::user()->id, 19, true, false, false, false, false, false);

            return view('manage.pages.serviceCategory', compact('categories', 'areas'));
        } else {
            abort(404);
        }
    }

    public function getGroup(Request $request){
        $groups = ServiceGroup::where('area_id', '=', $request->id)->get();
        $groupSelector = "";
        foreach ($groups as $group){
            $groupSelector .= '<option value="' . $group->id . '">' . $group->title . '</option>';
        }
        return $groupSelector;
    }

    public function save(ServiceCategoryRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['insert_19'] == "on") {
            ServiceCategory::create([
                'title' => trim($request->nameId),
                'group_id' => trim($request->groupId),
                'area_id' => trim($request->areaId),
                'timestamp' => time(),
            ]);

            $categories = ServiceCategory::orderBy('id', 'DESC')->paginate(20);
            $areas = ServiceArea::all();

            (new LogHistoryController)->logSave(Auth::user()->id, 19, false, false, false, true, false, false);
            return view('manage.pages.serviceCategory', compact('categories', 'areas'));
        } else {
            abort(404);
        }
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_19'] == "on") {
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
                $categories = ServiceCategory::where('title', 'like', '%' . $name . '%')
                    ->where('group_id', '=', $groupId)
                    ->where('area_id', '=', $areaId)->get();
            } else if ($name != null && $areaId != null) {
                $categories = ServiceCategory::where('title', 'like', '%' . $name . '%')
                    ->where('area_id', '=', $areaId)->get();
            } else if ($groupId != null) {
                $categories = ServiceCategory::where('group_id', '=', $groupId)->get();
            } else if ($areaId != null) {
                $categories = ServiceCategory::where('area_id', '=', $areaId)->get();
            } else if ($name != null) {
                $categories = ServiceCategory::where('title', 'like', '%' . $name . '%')->get();
            }

            if ($categories != null) {
                $result = "";

                foreach ($categories as $category) {
                    $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td>' . $category->area->title . '</td>
                            <td>' . $category->group->title . '</td>
                            <td>' . $category->title . '</td>';
                    if ($accessList['update_19'] == "on"){
                        $result .= '<td>
                                <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                        onclick="startUpdateServiceCategory(' . $category->id . ', document.getElementById(\'token\').value, \'#categoryId\', \'#areaId\', \'#groupId\', \'#nameId\')">
                                        <i class="icon-search4"></i>
                                </button>
                            </td>';
                    } if ($accessList['delete_19'] == "on") {
                        $result .= '<td>
                                <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                        onclick="deleteServiceCategory(' . $category->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-trash"></i>
                                </button>
                            </td>';
                    }
                    $result .= '</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 19, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $areas = ServiceArea::all();
        $category = ServiceCategory::find($request->id);
        $groups = ServiceGroup::where('area_id', '=', $category->area_id)->get();

        $areaSelector = '<option value="">انتخاب کنید</option>';

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

        $groupSelector = '<option value="">انتخاب کنید</option>';

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

        $result = $category->id . "_:_" . $category->title . "_:_" . $areaSelector . "_:_" . $groupSelector;

        return $result;
    }

    public function update(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['update_19'] == "on") {
            $category = ServiceCategory::find($request->id);

            $category->title = $request->name;
            $category->area_id = $request->areaId;
            $category->group_id = $request->groupId;

            $category->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 19, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function delete(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['delete_19'] == "on") {
            $count = ServiceCreator::where('category_id', '=', $request->id)->count();

            if($count == 0){
                $category = ServiceCategory::find($request->id);

                $category->delete();

                (new LogHistoryController)->logSave(Auth::user()->id, 19, false, false, false, false, true, false);

                return 1;
            } else {
                return 0;
            }
        } else {
            abort(404);
        }
    }

}