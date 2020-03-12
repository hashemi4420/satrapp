<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceGroupRequest;
use App\ServiceArea;
use App\ServiceGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceGroupController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_18'] == "on") {
            $areas = ServiceArea::all();
            $groups = ServiceGroup::orderBy('id', 'DESC')->paginate(20);

            (new LogHistoryController)->logSave(Auth::user()->id, 18, true, false, false, false, false, false);

            return view('manage.pages.serviceGroup', compact('areas', 'groups'));
        } else {
            abort(404);
        }
    }

    public function save(ServiceGroupRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['insert_18'] == "on") {
            ServiceGroup::create([
                'title' => trim($request->name),
                'area_id' => trim($request->areaId),
                'timestamp' => time(),
            ]);

            $areas = ServiceArea::all();
            $groups = ServiceGroup::orderBy('id', 'DESC')->paginate(20);

            (new LogHistoryController)->logSave(Auth::user()->id, 18, false, false, false, true, false, false);

            return view('manage.pages.serviceGroup', compact('areas', 'groups'));
        } else {
            abort(404);
        }
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_18'] == "on") {
            $name = null;
            $area = null;
            $stateGroups = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;

            if (trim($request->areaId) != "") {
                $area = trim($request->areaId);
            }

            if (trim($request->name) != "") {
                $name = trim($request->name);
            }

            if ($name != null && $area != null) {
                $stateGroups = ServiceGroup::where('title', 'like', '%' . $name . '%')
                    ->where('area_id', '=', $area)->get();
            } elseif ($name != null && $area == null) {
                $stateGroups = ServiceGroup::where('title', 'like', '%' . $name . '%')->get();
            } elseif ($name == null && $area != null) {
                $stateGroups = ServiceGroup::where('area_id', '=', $area)->get();
            }

            if ($stateGroups != null) {
                $result = "";

                foreach ($stateGroups as $stateGroup) {
                    $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td>' . $stateGroup->area->title . '</td>
                            <td>' . $stateGroup->title . '</td>';
                    if ($accessList['update_18'] == "on"){
                        $result .= '<td>
                                <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                        onclick="startUpdateServiceGroup(' . $stateGroup->id . ', document.getElementById(\'token\').value, \'#serviceGroupId\', \'#areaId\', \'#name\')">
                                        <i class="icon-search4"></i>
                                </button>
                            </td>';
                    } if ($accessList['delete_18'] == "on") {
                        $result .= '<td>
                                <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                        onclick="deleteServiceGroup(' . $stateGroup->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-trash"></i>
                                </button>
                            </td>';
                    }
                    $result .= '</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 18, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $areas = ServiceArea::all();
        $stateGroup = ServiceGroup::find($request->id);

        $areaSelector = '<option value="">انتخاب کنید</option>';

        foreach ($areas as $area){
            if($area->id == $stateGroup->area_id)
            {
                $areaSelector .= '<option value="' . $area->id . '" selected>' . $area->title . '</option>';
            }
            else
            {
                $areaSelector .= '<option value="' . $area->id . '">' . $area->title . '</option>';
            }
        }

        $result = $stateGroup->id . "_:_" . $stateGroup->title . "_:_" . $areaSelector;

        return $result;
    }

    public function update(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['update_18'] == "on") {
            $stateGroup = ServiceGroup::find($request->id);

            $stateGroup->title = $request->name;
            $stateGroup->area_id = $request->areaId;

            $stateGroup->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 18, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function delete(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['delete_18'] == "on") {
            $stateGroup = ServiceGroup::find($request->id);

            $stateGroup->delete();

            (new LogHistoryController)->logSave(Auth::user()->id, 18, false, false, false, false, true, false);
        } else {
            abort(404);
        }
    }

}