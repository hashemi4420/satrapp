<?php

namespace App\Http\Controllers;

use App\BusinessNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BusinessNumberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_2'] == "on"){
            $numbers = BusinessNumber::orderBy('id', 'DESC')->paginate(20);

            (new LogHistoryController)->logSave(Auth::user()->id, 2, true, false, false, false, false, false);

            return view('manage.pages.businessNumber', compact('numbers'));
        } else {
            abort(404);
        }
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_3'] == "on") {
            $number = null;
            $user = null;
            $businessNumbers = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;

            $select = "SELECT
                            number.number AS number,
                            user.email AS email
                        FROM business_numbers AS number
                        INNER JOIN users AS user ON number.user_id = user.id";

            if (trim($request->number) != "") {
                $number = trim($request->number);
                $select .= ' AND number.number = ' . $number;
            }

            if (trim($request->user) != "") {
                $user = trim($request->user);
                $select .= ' AND user.email LIKE "%' . $user .'%"';
            }

            if (strpos($select, 'AND') !== false) {
                $businessNumbers  = DB::select(DB::raw($select));
            }

            if ($businessNumbers != null) {
                $result = "";

                foreach ($businessNumbers as $businessNumber) {
                    $result .= '<tr>
                                    <td>' . $id++ . '</td>
                                    <td>' . $businessNumber->email . '</td>
                                    <td>' . $businessNumber->number . '</td>
                                </tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 2, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }
}
