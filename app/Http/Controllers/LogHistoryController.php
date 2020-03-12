<?php

namespace App\Http\Controllers;

use App\Form;
use App\LogHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogHistoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function logSave($userId, $formId, $view, $search, $update, $create, $delete, $accept){
        $action = "مشاهده فرم";
        if($view){
            $action = "مشاهده فرم";
        } else if($search){
            $action = "جستجو";
        } else if($update){
            $action = "ویرایش اطلاعات";
        } else if($create){
            $action = "ایجاد اطلاعات";
        } else if($delete){
            $action = "حذف اطلاعات";
        } else if($accept){
            $action = "تایید اطلاعات";
        }
        LogHistory::create([
            'user_id' => $userId,
            'form_id' => $formId,
            'timestamp' => time(),
            'action' => $action,
        ]);
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_3'] == "on"){
            $logHistories = LogHistory::orderBy('id', 'DESC')->paginate(20);

            $forms = Form::all();

            (new LogHistoryController)->logSave(Auth::user()->id, 3, true, false, false, false, false, false);

            return view('manage.pages.logHistory', compact('logHistories', 'forms'));
        } else {
            abort(404);
        }
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_3'] == "on") {
            $log = null;
            $form = null;
            $user = null;
            $logs = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;

            $select = "SELECT
                            user.email AS email,
                            form.title AS form,
                            log.action AS action
                        FROM log_histories AS log
                        INNER JOIN forms AS form ON log.form_id = form.id
                        INNER JOIN users AS user ON log.user_id = user.id
                        WHERE 1 = 1";

            if (trim($request->log) != "") {
                $log = trim($request->log);
                $select .= ' AND log.action = ' . $log;
            }

            if (trim($request->form) != "") {
                $form = trim($request->form);
                $select .= ' AND log.form_id = ' . $form;
            }

            if (trim($request->user) != "") {
                $user = trim($request->user);
                $select .= ' AND user.email LIKE "%' . $user .'%"';
            }

            if (strpos($select, 'AND') !== false) {
                $logs = DB::select(DB::raw($select));
            }

            if ($logs != null) {
                $result = "";

                foreach ($logs as $log_history) {
                    $result .= '<tr>
                                    <td>' . $id++ . '</td>
                                    <td>' . $log_history->email . '</td>
                                    <td>' . $log_history->form . '</td>
                                    <td>' . $log_history->action . '</td>
                                </tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 3, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }
}