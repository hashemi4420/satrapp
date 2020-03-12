<?php

namespace App\Http\Controllers;

use App\City;
use App\District;
use App\Http\Requests\DistrictRequest;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistrictController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_8'] == "on") {
            $districts = District::orderBy('id', 'DESC')->paginate(20);
            $states = State::all();

            (new LogHistoryController)->logSave(Auth::user()->id, 8, true, false, false, false, false, false);

            return view('manage.pages.district', compact('districts', 'states'));
        } else {
            abort(404);
        }
    }

    public function save(DistrictRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['insert_8'] == "on") {
            District::create([
                'title' => trim($request->name),
                'state_id' => $request->state,
                'city_id' => $request->city,
                'timestamp' => time(),
            ]);

            $districts = District::orderBy('id', 'DESC')->paginate(20);
            $states = State::all();

            (new LogHistoryController)->logSave(Auth::user()->id, 8, false, false, false, true, false, false);

            return view('manage.pages.district', compact('districts', 'states'));
        } else {
            abort(404);
        }
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_8'] == "on") {
            $stateId = null;
            $cityId = null;
            $name = null;
            $districts = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;

            if (trim($request->name) != "") {
                $name = trim($request->name);
            }

            if ($request->stateId != null) {
                $stateId = $request->stateId;
            }

            if ($request->cityId != null) {
                $cityId = $request->cityId;
            }

            if ($name != null && $stateId != null && $cityId != null) {
                $districts = District::where('title', 'like', '%' . $name . '%')
                    ->where('state_id', '=', $stateId)
                    ->where('city_id', '=', $cityId)->get();
            } else if ($name != null && $stateId != null) {
                $districts = District::where('title', 'like', '%' . $name . '%')
                    ->where('state_id', '=', $stateId)->get();
            } else if ($name != null && $cityId != null) {
                $districts = District::where('title', 'like', '%' . $name . '%')
                    ->where('city_id', '=', $cityId)->get();
            } else if ($name != null) {
                $districts = District::where('title', 'like', '%' . $name . '%')->get();
            } else if ($stateId != null && $cityId != null) {
                $districts = District::where('state_id', '=', $stateId)
                    ->where('city_id', '=', $cityId)->get();
            } else if ($stateId != null) {
                $districts = District::where('state_id', '=', $stateId)->get();
            } else if ($cityId != null) {
                $districts = District::where('city_id', '=', $cityId)->get();
            }

            if ($districts != null) {
                $result = "";

                foreach ($districts as $district) {
                    $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td>' . $district->state->title . '</td>
                            <td>' . $district->city->title . '</td>
                            <td>' . $district->title . '</td>';
                    if ($accessList['update_8'] == "on") {
                        $result .= '<td>
                                <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                        onclick="startUpdateDistrict(' . $district->id . ', document.getElementById(\'token\').value, \'#districtId\', \'#state\', \'#city\', \'#name\')">
                                        <i class="icon-search4"></i>
                                </button>
                            </td>';
                    } if($accessList['delete_8'] == "on") {
                        $result .= '<td>
                                <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                        onclick="deleteDistrict(' . $district->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-trash"></i>
                                </button>
                            </td>';
                    }
                        $result .= '</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 8, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function getCity(Request $request){
        $cities = City::where('state_id', '=', $request->id)->get();

        $citySelector = '<option value="">انتخاب کنید</option>';

        foreach ($cities as $city){
            $citySelector .= '<option value="' . $city->id . '">' . $city->title . '</option>';
        }

        return $citySelector;
    }

    public function startUpdate(Request $request){
        $states = State::all();
        $district = District::find($request->id);
        $cities = City::where('state_id', '=', $district->state_id)->get();

        $stateSelector = '<option value="">انتخاب کنید</option>';
        $citySelector = '<option value="">انتخاب کنید</option>';


        foreach ($states as $state){
            if($state->id == $district->state_id)
            {
                $stateSelector .= '<option value="' . $state->id . '" selected>' . $state->title . '</option>';
            }
            else
            {
                $stateSelector .= '<option value="' . $state->id . '">' . $state->title . '</option>';
            }
        }

        foreach ($cities as $city){
            if($city->id == $district->city_id)
            {
                $citySelector .= '<option value="' . $city->id . '" selected>' . $city->title . '</option>';
            }
            else
            {
                $citySelector .= '<option value="' . $city->id . '">' . $city->title . '</option>';
            }
        }

        $result = $district->id . "_:_" . $district->title . "_:_" . $stateSelector . "_:_" . $citySelector;

        return $result;
    }

    public function update(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['update_8'] == "on") {
            $district = District::find($request->id);

            $district->title = $request->name;
            $district->state_id = $request->stateId;
            $district->city_id = $request->cityId;

            $district->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 8, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function delete(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['delete_8'] == "on") {
            $district = District::find($request->id);

            $district->delete();

            (new LogHistoryController)->logSave(Auth::user()->id, 8, false, false, false, false, true, false);
        } else {
            abort(404);
        }
    }
}
