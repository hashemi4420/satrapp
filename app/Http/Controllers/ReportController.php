<?php

namespace App\Http\Controllers;

use App\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_38'] == "on"){
            $reports = Report::orderBy('id', 'DESC')->paginate(20);

            (new LogHistoryController)->logSave(Auth::user()->id, 38, true, false, false, false, false, false);

            return view('manage.pages.report', compact('reports'));
        } else {
            abort(404);
        }
    }
}
