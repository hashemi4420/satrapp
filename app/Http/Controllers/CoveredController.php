<?php

namespace App\Http\Controllers;

use App\City;
use App\District;
use App\Location;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Integer;

class CoveredController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $states = State::all();
        $selected = Location::where('user_id', '=', Auth::user()->id)->pluck('state_id')->first();

        return view('manage.pages.covered', compact('states', 'selected'));
    }

    public function showCity(Request $request)
    {
        $code = $request->stateId;
        $cites = City::where('state_id', '=', $code)->get();
        $result = "";

        $result .= '<div class="card" style="width: 100%;">
                    <div class="card-body">
                    <div class="row"><div class="pretty p-default">
                    <input type="checkbox" onchange="selectAll(this.checked); save('.$code.', 0, 0 ,document.getElementById(\'token\').value)">
                    <div class="state p-primary">
                      <label>کل استان</label>
                    </div>
                  </div></div><div class="row" style="margin-top: 15px;">';

        foreach ($cites as $city){
            $districts = District::where('city_id', '=', $city->id)->get();

            $arr = '[';
            foreach ($districts as $district){
                $arr .= $district->id.',';
            }
            $arr = substr($arr, 0, -1);
            $arr .= ']';
            $result .= '<div class="card" style="width: 100%;" id="'.$city->id.'">
                    <div class="card-body">
                    <div class="row"><div class="pretty p-default">
                    <input type="checkbox" name="asc" id="'.$city->id.'" onchange="selectAllCity('.$arr.', this.checked);save('.$code.', '.$city->id.', 0 ,document.getElementById(\'token\').value)">
                    <div class="state p-primary">
                      <label>کل '. $city->title .'</label>
                    </div>
                  </div></div><div class="row" style="margin-top: 15px;">';

            foreach ($districts as $district){
                $result .= '<div class="col-md-2"><div class="pretty p-default">
                    <input type="checkbox" name="asc" id="'.$district->id.'" onchange="save('.$code.', '.$city->id.', '.$district->id.' ,document.getElementById(\'token\').value)">
                    <div class="state p-primary">
                      <label>'. $district->title .'</label>
                    </div>
                  </div>
                  </div>';
            }
            $result .= '</div></div></div>';
        }
        return $result;
    }

    public function showSelectedCity()
    {
        $locations = Location::where('user_id', '=', Auth::user()->id)->orderBy('district_id', 'ASC')->get();
        $state = Location::where('user_id', '=', Auth::user()->id)->pluck('state_id')->first();
        $cites = City::where('state_id', '=', $state)->get();

        $allDistrict = District::where('state_id', '=', $state)->count();

        $count = $locations->count();

        $result = "";

        if($count == $allDistrict && $count > 0){
            $result .= '<div class="card" style="width: 100%;">
                    <div class="card-body">
                    <div class="row"><div class="pretty p-default">
                    <input type="checkbox" checked onchange="selectAll(this.checked); save('.$state.', 0, 0 ,document.getElementById(\'token\').value)">
                    <div class="state p-primary">
                      <label>کل استان</label>
                    </div>
                  </div></div><div class="row" style="margin-top: 15px;">';
        } else if ($count > 0) {
            $result .= '<div class="card" style="width: 100%;">
                    <div class="card-body">
                    <div class="row"><div class="pretty p-default">
                    <input type="checkbox" onchange="selectAll(this.checked); save('.$state.', 0, 0 ,document.getElementById(\'token\').value)">
                    <div class="state p-primary">
                      <label>کل استان</label>
                    </div>
                  </div></div><div class="row" style="margin-top: 15px;">';
        }

        foreach ($cites as $city){
            $districts = District::where('city_id', '=', $city->id)->get();

            $arr = '[';
            foreach ($districts as $district){
                $arr .= $district->id.',';
            }
            $arr = substr($arr, 0, -1);
            $arr .= ']';

            if($districts->count() == Location::where('city_id', '=', $city->id)->where('user_id', '=', Auth::user()->id)->count()){
                $result .= '<div class="card" style="width: 100%;">
                    <div class="card-body">
                    <div class="row"><div class="pretty p-default">
                    <input type="checkbox" name="asc" checked id="'.$city->id.'" onchange="selectAllCity('.$arr.', this.checked);save('.$state.', '.$city->id.', 0 ,document.getElementById(\'token\').value)">
                    <div class="state p-primary">
                      <label>کل '. $city->title .'</label>
                    </div>
                  </div></div><div class="row" style="margin-top: 15px;">';
            } else {
                $result .= '<div class="card" style="width: 100%;">
                    <div class="card-body">
                    <div class="row"><div class="pretty p-default">
                    <input type="checkbox" name="asc" id="'.$city->id.'" onchange="selectAllCity('.$arr.', this.checked);save('.$state.', '.$city->id.', 0 ,document.getElementById(\'token\').value)">
                    <div class="state p-primary">
                      <label>کل '. $city->title .'</label>
                    </div>
                  </div></div><div class="row" style="margin-top: 15px;">';
            }

            foreach ($districts as $district){
                if(Location::where('district_id', '=', $district->id)->where('user_id', '=', Auth::user()->id)->count() == 1){
                    $result .= '<div class="col-md-2"><div class="pretty p-default">
                    <input type="checkbox" name="asc" id="'.$district->id.'" checked onchange="save('.$state.', '.$city->id.', '.$district->id.' ,document.getElementById(\'token\').value)">
                    <div class="state p-primary">
                      <label>'. $district->title .'</label>
                    </div>
                  </div>
                  </div>';
                } else {
                    $result .= '<div class="col-md-2"><div class="pretty p-default">
                    <input type="checkbox" name="asc" id="'.$district->id.'" onchange="save('.$state.', '.$city->id.', '.$district->id.' ,document.getElementById(\'token\').value)">
                    <div class="state p-primary">
                      <label>'. $district->title .'</label>
                    </div>
                  </div>
                  </div>';
                }
            }
            $result .= '</div></div></div>';
        }
        return $result;
    }

    public function save(Request $request){
        $locations = Location::where('user_id', '=', Auth::user()->id)->get();
        foreach ($locations as $location){
            if($location->state->id != $request->stateId){
                $location->delete();
            }
        }

        if($request->cityId == 0 && $request->districtId == 0){
            foreach ($locations as $location){
                $location->delete();
            }
            $allState = District::where('state_id', '=', $request->stateId)->get();
            foreach ($allState as $state){
                Location::create([
                    'user_id' => trim(Auth::user()->id),
                    'state_id' => $state->state->id,
                    'city_id' => $state->city->id,
                    'district_id' => $state->id,
                ]);
            }
        } else if($request->cityId != 0 && $request->districtId == 0){
            foreach ($locations as $location){
                if($location->city->id == $request->cityId){
                    $location->delete();
                }
            }
            $allCity = District::where('city_id', '=', $request->cityId)->get();
            foreach ($allCity as $city){
                Location::create([
                    'user_id' => trim(Auth::user()->id),
                    'state_id' => $city->state->id,
                    'city_id' => $city->city->id,
                    'district_id' => $city->id,
                ]);
            }
        } else {
            $location = Location::where('district_id', '=', $request->districtId)->first();
            if($location != null){
                $location->delete();
            } else {
                Location::create([
                    'user_id' => trim(Auth::user()->id),
                    'state_id' => $request->stateId,
                    'city_id' => $request->cityId,
                    'district_id' => $request->districtId,
                ]);
            }
        }
    }
}
