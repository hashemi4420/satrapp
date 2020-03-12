<?php

namespace App\Http\Controllers;

use App\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FromController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_4'] == "on"){
            $forms = Form::orderBy('id', 'DESC')->paginate(20);

            (new LogHistoryController)->logSave(Auth::user()->id, 4, true, false, false, false, false, false);

            return view('manage.pages.forms', compact('forms'));
        } else {
            abort(404);
        }
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_4'] == "on") {
            $name = null;
            $forms = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;

            if (trim($request->name) != "") {
                $name = trim($request->name);
            }

            if ($name != null) {
                $forms = Form::where('title', 'like', '%' . $name . '%')->get();
            }

            if ($forms != null) {
                $result = "";

                foreach ($forms as $form) {
                    $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td>' . $form->title . '</td>';
                    if($accessList['update_4'] == "on"){
                        $result .='<td>
                                <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                        onclick="startUpdateForm(' . $form->id . ', document.getElementById(\'token\').value, \'#areaId\', \'#formId\')">
                                        <i class="icon-search4"></i>
                                </button>
                            </td>';
                    }

                    $result .='</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 4, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $form = Form::find($request->id);

        $result = $form->id . "_:_" . $form->title;

        return $result;
    }

    public function update(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['update_4'] == "on") {
            $form = Form::find($request->id);

            $form->title = $request->name;

            $form->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 4, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }
}
