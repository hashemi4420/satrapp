<?php

namespace App\Http\Controllers;

use App\City;
use App\District;
use App\Http\Requests\CityRequest;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_7'] == "on") {
            $cities = City::orderBy('id', 'DESC')->paginate(20);
            $states = State::all();

            (new LogHistoryController)->logSave(Auth::user()->id, 7, true, false, false, false, false, false);

            return view('manage.pages.city', compact('cities', 'states'));
        } else {
            abort(404);
        }
    }

    public function save(CityRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['insert_7'] == "on") {
            City::create([
                'title' => trim($request->name),
                'state_id' => trim($request->state),
                'timestamp' => time(),
            ]);

            $cities = City::orderBy('id', 'DESC')->paginate(20);
            $states = State::all();

            (new LogHistoryController)->logSave(Auth::user()->id, 7, false, false, false, true, false, false);

            return view('manage.pages.city', compact('cities', 'states'));
        } else {
            abort(404);
        }
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_7'] == "on") {
            $stateId = null;
            $name = null;
            $cities = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;

            if (trim($request->name) != "") {
                $name = trim($request->name);
            }

            if ($request->stateId != null) {
                $stateId = $request->stateId;
            }

            if ($name != null && $stateId != null) {
                $cities = City::where('title', 'like', '%' . $name . '%')
                    ->where('state_id', '=', $stateId)->get();
            } else if ($name != null) {
                $cities = City::where('title', 'like', '%' . $name . '%')->get();
            } else if ($stateId != null) {
                $cities = City::where('state_id', '=', $stateId)->get();
            }

            if ($cities != null) {
                $result = "";

                foreach ($cities as $city) {
                    $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td>' . $city->state->title . '</td>
                            <td>' . $city->title . '</td>';
                    if($accessList['update_7'] == "on"){
                        $result .= '<td>
                                <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                        onclick="startUpdateCity(' . $city->id . ', document.getElementById(\'token\').value, \'#cityId\', \'#state\', \'#name\')">
                                        <i class="icon-search4"></i>
                                </button>
                            </td>';
                    }
                    if($accessList['delete_7'] == "on"){
                        $result .= '<td>
                                <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                        onclick="deleteCity(' . $city->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-trash"></i>
                                </button>
                            </td>';
                    }

                    $result .= '</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 7, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $states = State::all();
        $city = City::find($request->id);

        $stateSelector = '<option value="">انتخاب کنید</option>';


        foreach ($states as $state){
            if($state->id == $city->state_id)
            {
                $stateSelector .= '<option value="' . $state->id . '" selected>' . $state->title . '</option>';
            }
            else
            {
                $stateSelector .= '<option value="' . $state->id . '">' . $state->title . '</option>';
            }
        }

        $result = $city->id . "_:_" . $city->title . "_:_" . $stateSelector;

        return $result;
    }

    public function update(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['update_7'] == "on") {
            $city = City::find($request->id);

            $city->title = $request->name;
            $city->state_id = $request->stateId;

            $city->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 7, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function delete(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['delete_7'] == "on") {
            $count = District::where('city_id', '=', $request->id)->count();

            if($count == 0){
                $city = City::find($request->id);

                $city->delete();

                (new LogHistoryController)->logSave(Auth::user()->id, 7, false, false, false, false, true, false);

                return 1;
            } else {
                return 0;
            }
        } else {
            abort(404);
        }
    }
}