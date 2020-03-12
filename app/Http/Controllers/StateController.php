<?php

namespace App\Http\Controllers;

use App\Http\Requests\StateRequest;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_6'] == "on") {
            $states = State::orderBy('id', 'DESC')->paginate(20);

            (new LogHistoryController)->logSave(Auth::user()->id, 6, true, false, false, false, false, false);

            return view('manage.pages.state', compact('states'));
        } else {
            abort(404);
        }
    }

    public function save(StateRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['insert_6'] == "on") {
            State::create([
                'title' => trim($request->name),
                'timestamp' => time(),
            ]);

            $states = State::orderBy('id', 'DESC')->paginate(20);

            (new LogHistoryController)->logSave(Auth::user()->id, 6, false, false, false, true, false, false);

            return view('manage.pages.state', compact('states'));
        } else {
            abort(404);
        }
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_6'] == "on") {
            $name = null;
            $states = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;

            if (trim($request->name) != "") {
                $name = trim($request->name);
            }

            if ($name != null) {
                $states = State::where('title', 'like', '%' . $name . '%')->get();
            }

            if ($states != null) {
                $result = "";

                foreach ($states as $state) {
                    $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td>' . $state->title . '</td>
                            <td>';
                    if($accessList['update_6'] == "on") {
                        $result .= '<button type="button" class="btn btn-primary btn-icon btn-rounded"
                                        onclick="startUpdateState(' . $state->id . ', document.getElementById(\'token\').value, \'#stateId\', \'#name\')">
                                        <i class="icon-search4"></i>
                                </button>
                            </td>
                            <td>';
                    }
                    if($accessList['delete_6'] == "on") {
                        $result .= '<button type="button" class="btn btn-danger btn-icon btn-rounded"
                                        onclick="deleteState(' . $state->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-trash"></i>
                                </button>
                            </td>';
                    }
                    $result .= '</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 6, false, false, false, true, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $state = State::find($request->id);

        $result = $state->id . "_:_" . $state->title;

        return $result;
    }

    public function update(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['update_6'] == "on") {
            $state = State::find($request->id);

            $state->title = $request->name;

            $state->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 6, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function delete(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['delete_6'] == "on") {
            $state = State::find($request->id);

            $state->delete();

            (new LogHistoryController)->logSave(Auth::user()->id, 6, false, false, false, false, true, false);
        } else {
            abort(404);
        }
    }
}
