<?php

namespace App\Http\Controllers;

use App\ArticleArea;
use App\Http\Requests\ArticleAreaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleAreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_10'] == "on") {
            $areas = ArticleArea::orderBy('id', 'DESC')->paginate(20);

            (new LogHistoryController)->logSave(Auth::user()->id, 10, true, false, false, false, false, false);

            return view('manage.pages.articleArea', compact('areas'));
        } else {
            abort(404);
        }
    }

    public function save(ArticleAreaRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['insert_10'] == "on") {
            ArticleArea::create([
                'title' => trim($request->name),
                'timestamp' => time(),
            ]);

            $areas = ArticleArea::orderBy('id', 'DESC')->paginate(20);

            (new LogHistoryController)->logSave(Auth::user()->id, 10, false, false, false, true, false, false);

            return view('manage.pages.articleArea', compact('areas'));
        } else {
            abort(404);
        }
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_10'] == "on") {
            $name = null;
            $articleAreas = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;

            if (trim($request->name) != "") {
                $name = trim($request->name);
            }

            if ($name != null) {
                $articleAreas = ArticleArea::where('title', 'like', '%' . $name . '%')->get();
            }

            if ($articleAreas != null) {
                $result = "";

                foreach ($articleAreas as $articleArea) {
                    $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td>' . $articleArea->title . '</td>';
                    if($accessList['update_10'] == "on") {
                        $result .= '<td>
                                <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                        onclick="startUpdateArticleArea(' . $articleArea->id . ', document.getElementById(\'token\').value, \'#areaId\', \'#name\')">
                                        <i class="icon-search4"></i>
                                </button>
                            </td>';
                    }
                    if ($accessList['delete_10'] == "on") {
                        $result .= '<td>
                                <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                        onclick="deleteArticleArea(' . $articleArea->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-trash"></i>
                                </button>
                            </td>';
                    }
                    $result .= '</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 10, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $articleArea = ArticleArea::find($request->id);

        $result = $articleArea->id . "_:_" . $articleArea->title;

        return $result;
    }

    public function update(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['update_10'] == "on") {
            $articleArea = ArticleArea::find($request->id);

            $articleArea->title = $request->name;

            $articleArea->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 10, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function delete(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_4'] == "on") {
            $articleArea = ArticleArea::find($request->id);

            $articleArea->delete();

            (new LogHistoryController)->logSave(Auth::user()->id, 10, false, false, false, false, true, false);
        } else {
            abort(404);
        }
    }

    public function active(Request $request){
        $this->accessLevel = Auth::user()->usrRole;
        $this->accessList = json_decode($this->accessLevel->json, true);
        if($this->accessList['update_10'] == "on") {
            $article = ArticleArea::find($request->id);

            if ($article->active) {
                $active = 0;
            } else {
                $active = 1;
            }

            $article->active = $active;

            $article->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 10, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }
}