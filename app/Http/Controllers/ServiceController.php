<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_15'] == "on") {
            $services = Service::orderBy('id', 'DESC')->paginate(20);

            (new LogHistoryController)->logSave(Auth::user()->id, 15, true, false, false, false, false, false);

            return view('manage.pages.service', compact('services'));
        } else {
            abort(404);
        }
    }

    public function save(ServiceRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['insert_15'] == "on") {
            Service::create([
                'title' => trim($request->name),
                'timestamp' => time(),
            ]);

            $services = Service::orderBy('id', 'DESC')->paginate(20);

            (new LogHistoryController)->logSave(Auth::user()->id, 15, false, false, false, true, false, false);

            return view('manage.pages.service', compact('services'));
        } else {
            abort(404);
        }
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_15'] == "on") {
            $name = null;
            $services = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;

            if (trim($request->name) != "") {
                $name = trim($request->name);
            }

            if ($name != null) {
                $services = Service::where('title', 'like', '%' . $name . '%')->get();
            }

            if ($services != null) {
                $result = "";

                foreach ($services as $service) {
                    $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td>' . $service->title . '</td>';
                    if ($accessList['update_15'] == "on"){
                        $result .= '<td>
                                <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                        onclick="startUpdateService(' . $service->id . ', document.getElementById(\'token\').value, \'#serviceId\', \'#name\')">
                                        <i class="icon-search4"></i>
                                </button>
                            </td>';
                    }
                    if ($accessList['delete_15'] == "on") {
                        $result .= '<td>
                                <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                        onclick="deleteService(' . $service->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-trash"></i>
                                </button>
                            </td>';
                    }
                    $result .= '</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 15, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $service = Service::find($request->id);

        $result = $service->id . "_:_" . $service->title;

        return $result;
    }

    public function update(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['update_15'] == "on") {
            $service = Service::find($request->id);

            $service->title = $request->name;

            $service->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 15, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function delete(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['delete_15'] == "on") {
            $service = Service::find($request->id);

            $service->delete();

            (new LogHistoryController)->logSave(Auth::user()->id, 15, false, false, false, false, true, false);
        } else {
            abort(404);
        }
    }
}