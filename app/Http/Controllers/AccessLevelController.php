<?php

namespace App\Http\Controllers;

use App\AccessLevel;
use App\Form;
use App\Http\Requests\AccessLevelRequest;
use App\LogHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessLevelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
//        $table = State::all();
//        foreach ($table as $tt){
//            $field = State::find($tt->id);
//            $v = Verta($tt->created_at);
//            $field->timestamp = $v->timestamp;
//            $field->save();
//        }

        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_5'] == "on") {
            $accessLevels = AccessLevel::orderBy('id', 'DESC')->paginate(20);
            $forms = Form::all();

            LogHistory::create([
                'user_id' =>Auth::user()->id,
                'form_id' =>5,
                'timestamp' =>time(),
                'action' =>"مشاهده فرم",
            ]);

            (new LogHistoryController)->logSave(Auth::user()->id, 5, true, false, false, false, false, false);

            return view('manage.pages.accessLevel', compact('accessLevels', 'forms'));
        } else {
            abort(404);
        }
    }

    public function save(AccessLevelRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);

        if(isset($request->edit)){

            if($accessList['update_5'] == "on") {
                $access = $this->getAccess($request);

                $level = AccessLevel::find($request->accessId);

                $level->title = trim($request->name);
                $level->json = json_encode($access);
                $level->save();

                $accessLevels = AccessLevel::orderBy('id', 'DESC')->paginate(20);
                $forms = Form::all();

                LogHistory::create([
                    'user_id' =>Auth::user()->id,
                    'form_id' =>5,
                    'timestamp' =>time(),
                    'action' =>"ویرایش اطلاعات",
                ]);

                (new LogHistoryController)->logSave(Auth::user()->id, 5, false, false,true, false, false, false);

                return view('manage.pages.accessLevel', compact('accessLevels', 'forms'));
            } else {
                abort(404);
            }
        } else if(isset($request->save)){
            if($accessList['insert_5'] == "on") {
                $access = $this->getAccess($request);

                AccessLevel::create([
                    'title' => trim($request->name),
                    'json' => json_encode($access),
                ]);

                $accessLevels = AccessLevel::orderBy('id', 'DESC')->paginate(20);
                $forms = Form::all();

                LogHistory::create([
                    'user_id' =>Auth::user()->id,
                    'form_id' =>5,
                    'timestamp' =>time(),
                    'action' =>"ایجاد اطلاعات",
                ]);

                (new LogHistoryController)->logSave(Auth::user()->id, 5, false, false, false, true, false, false);

                return view('manage.pages.accessLevel', compact('accessLevels', 'forms'));
            } else {
                abort(404);
            }
        }
    }

    private function getAccess(AccessLevelRequest $request){
        return array(
            'read_1' => $request['read_1'],
            'insert_1' => $request['insert_1'],
            'update_1' => $request['update_1'],
            'delete_1' => $request['delete_1'],
            'access_1' => $request['access_1'],

            'read_2' => $request['read_2'],
            'insert_2' => $request['insert_2'],
            'update_2' => $request['update_2'],
            'delete_2' => $request['delete_2'],
            'access_2' => $request['access_2'],

            'read_3' => $request['read_3'],
            'insert_3' => $request['insert_3'],
            'update_3' => $request['update_3'],
            'delete_3' => $request['delete_3'],
            'access_3' => $request['access_3'],

            'read_4' => $request['read_4'],
            'insert_4' => $request['insert_4'],
            'update_4' => $request['update_4'],
            'delete_4' => $request['delete_4'],
            'access_4' => $request['access_4'],

            'read_5' => $request['read_5'],
            'insert_5' => $request['insert_5'],
            'update_5' => $request['update_5'],
            'delete_5' => $request['delete_5'],
            'access_5' => $request['access_5'],

            'read_6' => $request['read_6'],
            'insert_6' => $request['insert_6'],
            'update_6' => $request['update_6'],
            'delete_6' => $request['delete_6'],
            'access_6' => $request['access_6'],

            'read_7' => $request['read_7'],
            'insert_7' => $request['insert_7'],
            'update_7' => $request['update_7'],
            'delete_7' => $request['delete_7'],
            'access_7' => $request['access_7'],

            'read_8' => $request['read_8'],
            'insert_8' => $request['insert_8'],
            'update_8' => $request['update_8'],
            'delete_8' => $request['delete_8'],
            'access_8' => $request['access_8'],

            'read_9' => $request['read_9'],
            'insert_9' => $request['insert_9'],
            'update_9' => $request['update_9'],
            'delete_9' => $request['delete_9'],
            'access_9' => $request['access_9'],

            'read_10' => $request['read_10'],
            'insert_10' => $request['insert_10'],
            'update_10' => $request['update_10'],
            'delete_10' => $request['delete_10'],
            'access_10' => $request['access_10'],

            'read_11' => $request['read_11'],
            'insert_11' => $request['insert_11'],
            'update_11' => $request['update_11'],
            'delete_11' => $request['delete_11'],
            'access_11' => $request['access_11'],

            'read_12' => $request['read_12'],
            'insert_12' => $request['insert_12'],
            'update_12' => $request['update_12'],
            'delete_12' => $request['delete_12'],
            'access_12' => $request['access_12'],

            'read_13' => $request['read_13'],
            'insert_13' => $request['insert_13'],
            'update_13' => $request['update_13'],
            'delete_13' => $request['delete_13'],
            'access_13' => $request['access_13'],

            'read_14' => $request['read_14'],
            'insert_14' => $request['insert_14'],
            'update_14' => $request['update_14'],
            'delete_14' => $request['delete_14'],
            'access_14' => $request['access_14'],

            'read_15' => $request['read_15'],
            'insert_15' => $request['insert_15'],
            'update_15' => $request['update_15'],
            'delete_15' => $request['delete_15'],
            'access_15' => $request['access_15'],

            'read_16' => $request['read_16'],
            'insert_16' => $request['insert_16'],
            'update_16' => $request['update_16'],
            'delete_16' => $request['delete_16'],
            'access_16' => $request['access_16'],

            'read_17' => $request['read_17'],
            'insert_17' => $request['insert_17'],
            'update_17' => $request['update_17'],
            'delete_17' => $request['delete_17'],
            'access_17' => $request['access_17'],

            'read_18' => $request['read_18'],
            'insert_18' => $request['insert_18'],
            'update_18' => $request['update_18'],
            'delete_18' => $request['delete_18'],
            'access_18' => $request['access_18'],

            'read_19' => $request['read_19'],
            'insert_19' => $request['insert_19'],
            'update_19' => $request['update_19'],
            'delete_19' => $request['delete_19'],
            'access_19' => $request['access_19'],

            'read_20' => $request['read_20'],
            'insert_20' => $request['insert_20'],
            'update_20' => $request['update_20'],
            'delete_20' => $request['delete_20'],
            'access_20' => $request['access_20'],

            'read_21' => $request['read_21'],
            'insert_21' => $request['insert_21'],
            'update_21' => $request['update_21'],
            'delete_21' => $request['delete_21'],
            'access_21' => $request['access_21'],

            'read_22' => $request['read_22'],
            'insert_22' => $request['insert_22'],
            'update_22' => $request['update_22'],
            'delete_22' => $request['delete_22'],
            'access_22' => $request['access_22'],

            'read_23' => $request['read_23'],
            'insert_23' => $request['insert_23'],
            'update_23' => $request['update_23'],
            'delete_23' => $request['delete_23'],
            'access_23' => $request['access_23'],

            'read_24' => $request['read_24'],
            'insert_24' => $request['insert_24'],
            'update_24' => $request['update_24'],
            'delete_24' => $request['delete_24'],
            'access_24' => $request['access_24'],

            'read_25' => $request['read_25'],
            'insert_25' => $request['insert_25'],
            'update_25' => $request['update_25'],
            'delete_25' => $request['delete_25'],
            'access_25' => $request['access_25'],

            'read_26' => $request['read_26'],
            'insert_26' => $request['insert_26'],
            'update_26' => $request['update_26'],
            'delete_26' => $request['delete_26'],
            'access_26' => $request['access_26'],

            'read_27' => $request['read_27'],
            'insert_27' => $request['insert_27'],
            'update_27' => $request['update_27'],
            'delete_27' => $request['delete_27'],
            'access_27' => $request['access_27'],

            'read_28' => $request['read_28'],
            'insert_28' => $request['insert_28'],
            'update_28' => $request['update_28'],
            'delete_28' => $request['delete_28'],
            'access_28' => $request['access_28'],

            'read_29' => $request['read_29'],
            'insert_29' => $request['insert_29'],
            'update_29' => $request['update_29'],
            'delete_29' => $request['delete_29'],
            'access_29' => $request['access_29'],

            'read_30' => $request['read_30'],
            'insert_30' => $request['insert_30'],
            'update_30' => $request['update_30'],
            'delete_30' => $request['delete_30'],
            'access_30' => $request['access_30'],

            'read_31' => $request['read_31'],
            'insert_31' => $request['insert_31'],
            'update_31' => $request['update_31'],
            'delete_31' => $request['delete_31'],
            'access_31' => $request['access_31'],

            'read_32' => $request['read_32'],
            'insert_32' => $request['insert_32'],
            'update_32' => $request['update_32'],
            'delete_32' => $request['delete_32'],
            'access_32' => $request['access_32'],

            'read_33' => $request['read_33'],
            'insert_33' => $request['insert_33'],
            'update_33' => $request['update_33'],
            'delete_33' => $request['delete_33'],
            'access_33' => $request['access_33'],

            'read_34' => $request['read_34'],
            'insert_34' => $request['insert_34'],
            'update_34' => $request['update_34'],
            'delete_34' => $request['delete_34'],
            'access_34' => $request['access_34'],

            'read_35' => $request['read_35'],
            'insert_35' => $request['insert_35'],
            'update_35' => $request['update_35'],
            'delete_35' => $request['delete_35'],
            'access_35' => $request['access_35'],

            'read_36' => $request['read_36'],
            'insert_36' => $request['insert_36'],
            'update_36' => $request['update_36'],
            'delete_36' => $request['delete_36'],
            'access_36' => $request['access_36'],

            'read_37' => $request['read_37'],
            'insert_37' => $request['insert_37'],
            'update_37' => $request['update_37'],
            'delete_37' => $request['delete_37'],
            'access_37' => $request['access_37'],

            'read_38' => $request['read_38'],
            'insert_38' => $request['insert_38'],
            'update_38' => $request['update_38'],
            'delete_38' => $request['delete_38'],
            'access_38' => $request['access_38'],

            'read_39' => $request['read_39'],
            'insert_39' => $request['insert_39'],
            'update_39' => $request['update_39'],
            'delete_39' => $request['delete_39'],
            'access_39' => $request['access_39'],
        );
    }

    public function startUpdate(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_5'] == "on") {
            $accessLevel = AccessLevel::find($request->id);
            $forms = Form::all();

            $access = array(json_decode($accessLevel->json));

            $table = "";
            $i = 0;
            foreach ($forms as $form) {
                $table .= '<tr><td>' . $form->title . '</td>';
                if ($i == 0) {
                    if (current($access[0]) == 'on') {
                        $checked = ' checked';
                    } else {
                        $checked = '';
                    }
                } else {
                    if (next($access[0]) == 'on') {
                        $checked = ' checked';
                    } else {
                        $checked = '';
                    }
                }
                $table .= '<td align=\'center\'>
                        <input type=\'checkbox\' style="position: fixed !important;" class=\'custom-control-input\'' . $checked . ' id=\'read_' . $form->id . '\' name=\'read_' . $form->id . '\'>
                        <label class=\'custom-control-label\' for=\'read_' . $form->id . '\'></label>
                      </td>';

                if (next($access[0]) == 'on') {
                    $checked = ' checked';
                } else {
                    $checked = '';
                }
                $table .= '<td align=\'center\'>
                            <input type=\'checkbox\' style="position: fixed !important;" class=\'custom-control-input\'' . $checked . ' id=\'insert_' . $form->id . '\' name=\'insert_' . $form->id . '\'>
                            <label class=\'custom-control-label\' for=\'insert_' . $form->id . '\'></label>
                       </td>';

                if (next($access[0]) == 'on') {
                    $checked = ' checked';
                } else {
                    $checked = '';
                }
                $table .= '<td align=\'center\'>
                            <input type=\'checkbox\' style="position: fixed !important;" class=\'custom-control-input\'' . $checked . ' id=\'update_' . $form->id . '\' name=\'update_' . $form->id . '\'>
                            <label class=\'custom-control-label\' for=\'update_' . $form->id . '\'></label>
                       </td>';

                if (next($access[0]) == 'on') {
                    $checked = ' checked';
                } else {
                    $checked = '';
                }
                $table .= '<td align=\'center\'>
                            <input type=\'checkbox\' style="position: fixed !important;" class=\'custom-control-input\'' . $checked . ' id=\'delete_' . $form->id . '\' name=\'delete_' . $form->id . '\'>
                            <label class=\'custom-control-label\' for=\'delete_' . $form->id . '\'></label>
                       </td>';

                if (next($access[0]) == 'on') {
                    $checked = ' checked';
                } else {
                    $checked = '';
                }
                $table .= '<td align=\'center\'>
                            <input type=\'checkbox\' style="position: fixed !important;" class=\'custom-control-input\'' . $checked . ' id=\'access_' . $form->id . '\' name=\'access_' . $form->id . '\'>
                            <label class=\'custom-control-label\' for=\'access_' . $form->id . '\'></label>
                       </td></tr>';

                $i++;
            }

            $result = $accessLevel->id . "_:_" . $accessLevel->title . "_:_" . $table;

            LogHistory::create([
                'user_id' => Auth::user()->id,
                'form_id' => 5,
                'timestamp' => time(),
                'action' => "مشاهده فرم",
            ]);

            return $result;
        } else {
            abort(404);
        }
    }
}