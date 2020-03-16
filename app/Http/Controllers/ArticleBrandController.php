<?php

namespace App\Http\Controllers;

use App\ArticleBrand;
use App\ArticleCreator;
use App\Http\Requests\ArticleBrandRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleBrandController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_9'] == "on") {
            $brands = ArticleBrand::orderBy('id', 'DESC')->paginate(20);

            (new LogHistoryController)->logSave(Auth::user()->id, 9, true, false, false, false, false, false);

            return view('manage.pages.articleBrand', compact('brands'));
        } else {
            abort(404);
        }
    }

    public function save(ArticleBrandRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['insert_9'] == "on") {
            $image = $request->file('avatar');
            $name = time() . Auth::user()->phone . '.' . $image->getClientOriginalExtension();
            $destination = '/images/brand/article';

            (new FileController)->save($destination, $name, $image);

            ArticleBrand::create([
                'title' => trim($request->name),
                'url_avatar' => trim($destination.'/'.$name),
                'timestamp' => time(),
            ]);

            (new LogHistoryController)->logSave(Auth::user()->id, 9, false, false, false, true, false, false);

            $brands = ArticleBrand::orderBy('id', 'DESC')->paginate(20);
            return view('manage.pages.articleBrand', compact('brands'));
        } else {
            abort(404);
        }
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_9'] == "on") {
            $name = null;
            $brands = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;

            if (trim($request->name) != "") {
                $name = trim($request->name);
            }

            if ($name != null) {
                $brands = ArticleBrand::where('title', 'like', '%' . $name . '%')->get();
            }

            if ($brands != null) {
                $result = "";

                foreach ($brands as $brand) {
                    $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td>
                            <div class="list-unstyled row clearfix aniimated-thumbnials">
                                                                <a href="' . $brand->url_avatar . '" data-sub-html="' . $brand->title . '">
                                                                    <img class="img-responsive thumbnail" src="' . $brand->url_avatar . '" alt="' . $brand->title . '" width="35">
                                                                </a>
                                                            </div>
                                </td>
                            <td>' . $brand->title . '</td>';
                    if ($accessList['update_9'] == "on") {
                        $result .= '<td>
                                <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                        onclick="startUpdateArticleBrand(' . $brand->id . ', document.getElementById(\'token\').value, \'#brandId\', \'#name\', \'#thumb-output\')">
                                        <i class="icon-search4"></i>
                                </button>
                            </td>';
                    }
                    if ($accessList['delete_9'] == "on") {
                        $result .= '<td>
                                <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                        onclick="deleteArticleBrand(' . $brand->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-trash"></i>
                                </button>
                            </td>';
                    }
                    $result .= '</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 9, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $brand = ArticleBrand::find($request->id);

        $result = $brand->id . "_:_" . $brand->title . "_:_" . $brand->url_avatar;

        return $result;
    }

    public function update(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['update_9'] == "on") {
            $brand = ArticleBrand::find($request->id);

            $brand->title = $request->name;

            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $name = time() . Auth::user()->phone . '.' . $image->getClientOriginalExtension();
                $destination = '/images/brand/article';

                (new FileController)->save($destination, $name, $image);

                (new FileController)->delete($brand->url_avatar);

                $brand->url_avatar = trim($destination.'/'.$name);
            }

            $brand->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 9, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function delete(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['delete_9'] == "on") {
            $count = ArticleCreator::where('brand_id', '=', $request->id)->count();

            if($count == 0){
                $brand = ArticleBrand::find($request->id);

                (new FileController)->delete($brand->url_avatar);

                $brand->delete();

                (new LogHistoryController)->logSave(Auth::user()->id, 9, false, false, false, false, true, false);

                return 1;
            } else {
                return 0;
            }

        } else {
            abort(404);
        }
    }
}