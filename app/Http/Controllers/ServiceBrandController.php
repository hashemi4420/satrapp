<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceBrandRequest;
use App\ServiceBrand;
use App\ServiceCreator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceBrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_16'] == "on") {
            $brands = ServiceBrand::orderBy('id', 'DESC')->paginate(20);

            (new LogHistoryController)->logSave(Auth::user()->id, 16, true, false, false, false, false, false);

            return view('manage.pages.serviceBrand', compact('brands'));
        } else {
            abort(404);
        }
    }

    public function save(ServiceBrandRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['insert_16'] == "on") {
            $image = $request->file('avatar');
            $name = time() . Auth::user()->phone . '.' . $image->getClientOriginalExtension();
            $destination = '/images/brand/service';

            (new FileController)->save($destination, $name, $image);

            ServiceBrand::create([
                'title' => trim($request->name),
                'timestamp' => time(),
                'url_avatar' => trim($destination.'/'.$name),
            ]);

            $brands = ServiceBrand::orderBy('id', 'DESC')->paginate(20);

            (new LogHistoryController)->logSave(Auth::user()->id, 16, false, false, false, true, false, false);

            return view('manage.pages.serviceBrand', compact('brands'));
        } else {
            abort(404);
        }
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_16'] == "on") {
            $name = null;
            $brands = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;

            if (trim($request->name) != "") {
                $name = trim($request->name);
            }

            if ($name != null) {
                $brands = ServiceBrand::where('title', 'like', '%' . $name . '%')->get();
            }

            if ($brands != null) {
                $result = "";
            }

            foreach ($brands as $brand) {
                $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td><div class="list-unstyled row clearfix aniimated-thumbnials">
                                                                <a href="' . $brand->url_avatar . '" data-sub-html="' . $brand->title . '">
                                                                    <img class="img-responsive thumbnail" src="' . $brand->url_avatar . '" alt="' . $brand->title . '" width="35">
                                                                </a>
                                                            </div>
                            <td>' . $brand->title . '</td>';
                if ($accessList['update_16'] == "on"){
                    $result .= '<td>
                                <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                        onclick="startUpdateServiceBrand(' . $brand->id . ', document.getElementById(\'token\').value, \'#brandId\', \'#name\', \'#thumb-output\')">
                                        <i class="icon-search4"></i>
                                </button>
                            </td>';
                } if ($accessList['delete_16'] == "on") {
                    $result .= '<td>
                                <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                        onclick="deleteServiceBrand(' . $brand->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-trash"></i>
                                </button>
                            </td>';
                }
                $result .= '</tr>';
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 16, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $brand = ServiceBrand::find($request->id);

        $result = $brand->id . "_:_" . $brand->title . "_:_" . $brand->url_avatar;

        return $result;
    }

    public function update(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['update_16'] == "on") {
            $brand = ServiceBrand::find($request->id);

            $brand->title = $request->name;

            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $name = time() . Auth::user()->phone . '.' . $image->getClientOriginalExtension();
                $destination = '/images/brand/service';

                (new FileController)->save($destination, $name, $image);

                (new FileController)->delete($brand->url_avatar);

                $brand->url_avatar = trim('/images/brand/service/' . $name);
            }

            $brand->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 16, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function delete(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['delete_16'] == "on") {
            $count = ServiceCreator::where('brand_id', '=', $request->id)->count();

            if($count == 0){
                $brand = ServiceBrand::find($request->id);

                (new FileController)->delete($brand->url_avatar);

                $brand->delete();

                (new LogHistoryController)->logSave(Auth::user()->id, 16, false, false, false, false, true, false);

                return 1;
            } else {
                return 0;
            }
        } else {
            abort(404);
        }
    }

}