<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceAreaRequest;
use App\ServiceArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceAreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_17'] == "on") {
            $areas = ServiceArea::orderBy('id', 'DESC')->paginate(20);

            (new LogHistoryController)->logSave(Auth::user()->id, 17, true, false, false, false, false, false);

            return view('manage.pages.serviceArea', compact('areas'));
        } else {
            abort(404);
        }
    }

    public function save(ServiceAreaRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['insert_17'] == "on") {
            ServiceArea::create([
                'title' => trim($request->name),
                'timestamp' => time(),
            ]);

            $areas = ServiceArea::orderBy('id', 'DESC')->paginate(20);

            (new LogHistoryController)->logSave(Auth::user()->id, 17, false, false, false, true, false, false);

            return view('manage.pages.serviceArea', compact('areas'));
        } else {
            abort(404);
        }
    }

    public function search(Request $request)
    {$accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_17'] == "on") {
            $name = null;
            $stateAreas = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;

            if (trim($request->name) != "") {
                $name = trim($request->name);
            }

            if ($name != null) {
                $stateAreas = ServiceArea::where('title', 'like', '%' . $name . '%')->get();
            }

            if ($stateAreas != null) {
                $result = "";

                foreach ($stateAreas as $stateArea) {
                    $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td>' . $stateArea->title . '</td>';
                    if ($accessList['update_17'] == "on"){
                        $result .= '<td>
                                <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                        onclick="startUpdateServiceArea(' . $stateArea->id . ', document.getElementById(\'token\').value, \'#serviceAreaId\', \'#name\')">
                                        <i class="icon-search4"></i>
                                </button>
                            </td>';
                    } if ($accessList['delete_17'] == "on") {
                        $result .= '<td>
                                <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                        onclick="deleteServiceArea(' . $stateArea->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-trash"></i>
                                </button>
                            </td>';
                    }
                    $result .= '</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 17, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $stateArea = ServiceArea::find($request->id);

        $result = $stateArea->id . "_:_" . $stateArea->title;

        return $result;
    }

    public function update(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['update_17'] == "on") {
            $stateArea = ServiceArea::find($request->id);

            $stateArea->title = $request->name;

            $stateArea->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 17, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function delete(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['delete_17'] == "on") {
            $stateArea = ServiceArea::find($request->id);

            $stateArea->delete();

            (new LogHistoryController)->logSave(Auth::user()->id, 17, false, false, false, false, true, false);
        } else {
            abort(404);
        }
    }

    public function active(Request $request){
        $this->accessLevel = Auth::user()->usrRole;
        $this->accessList = json_decode($this->accessLevel->json, true);
        if($this->accessList['update_17'] == "on") {
            $service = ServiceArea::find($request->id);

            if ($service->active) {
                $active = 0;
            } else {
                $active = 1;
            }

            $service->active = $active;

            $service->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 17, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }
}