<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceCreatorRequest;
use App\ServiceArea;
use App\ServiceBrand;
use App\ServiceCategory;
use App\ServiceCreator;
use App\ServiceGroup;
use App\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceCreatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_20'] == "on") {
            $serviceCreators = null;
            if (Auth::user()->userRole == 2) {
                $serviceCreators = ServiceCreator::where('serviceBrand_id', '=', Auth::user()->serviceBrand_id)->orderBy('id', 'DESC')->paginate(20);
            } else if (Auth::user()->userRole == 3) {
                $serviceCreators = ServiceCreator::where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->paginate(20);
            } else {
                $serviceCreators = ServiceCreator::orderBy('id', 'DESC')->paginate(20);
            }

            if (Auth::user()->userRole == 3) {
                $workField = json_decode(Auth::user()->workFieldService);
                if($workField != null){
                    $areas = ServiceArea::whereIn('id', $workField)->get();
                }
            } else {
                $areas = ServiceArea::all();
            }
            $brands = null;
            if (Auth::user()->userRole == 2) {
                $brands = ServiceBrand::where('id', '=', Auth::user()->serviceBrand_id)->get();
            } else {
                $brands = ServiceBrand::all();
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 20, true, false, false, false, false, false);

            return view('manage.pages.serviceCreator', compact('serviceCreators', 'areas', 'brands'));
        } else {
            abort(404);
        }
    }

    public function getGroup(Request $request){
        $groups = ServiceGroup::where('area_id', '=', $request->id)->get();
        $groupSelector = "";
        foreach ($groups as $group){
            $groupSelector .= '<option value="' . $group->id . '">' . $group->title . '</option>';
        }
        return $groupSelector;
    }

    public function getCategories(Request $request){
        $categories = ServiceCategory::where('group_id', '=', $request->id)->get();
        $categorySelector = "";
        foreach ($categories as $category){
            $categorySelector .= '<option value="' . $category->id . '">' . $category->title . '</option>';
        }
        return $categorySelector;
    }

    public function save(ServiceCreatorRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['insert_20'] == "on") {
            $image = $request->file('avatar');
            $name = time() . Auth::user()->phone . '.' . $image->getClientOriginalExtension();
            $destination = '/images/product/service';

            (new FileController)->save($destination, $name, $image);

//        date_default_timezone_set("Asia/Tehran");

            $brand = null;
            if($request->brand != 0){
                $brand = $request->brand;
            }

            ServiceCreator::create([
                'title' => trim($request->name),
                'url_avatar' => trim($destination.'/'.$name),
                'brand_id' => $brand,
                'area_id' => $request->area,
                'group_id' => $request->group,
                'category_id' => $request->category,
                'user_id' => Auth::user()->id,
                'timestamp' => time(),
            ]);

            $serviceCreators = null;
            if (Auth::user()->userRole == 2) {
                $serviceCreators = ServiceCreator::where('serviceBrand_id', '=', Auth::user()->serviceBrand_id)->orderBy('id', 'DESC')->paginate(20);
            } else {
                $serviceCreators = ServiceCreator::orderBy('id', 'DESC')->paginate(20);
            }
            if (Auth::user()->userRole == 3) {
                $workField = json_decode(Auth::user()->workFieldService);
                if($workField != null){
                    $areas = ServiceArea::whereIn('id', $workField)->get();
                }
            } else {
                $areas = ServiceArea::all();
            }
            $brands = null;
            if (Auth::user()->userRole == 2) {
                $brands = ServiceBrand::where('id', '=', Auth::user()->serviceBrand_id)->get();
            } else {
                $brands = ServiceBrand::all();
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 20, false, false, false, true, false, false);

            return view('manage.pages.serviceCreator', compact('serviceCreators', 'areas', 'brands'));
        } else {
            abort(404);
        }
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_20'] == "on") {
            $services = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;

            $select = 'SELECT
                            service_creators.id AS id,
                            service_creators.url_avatar AS avatar,
                            service_creators.title AS title,
                            service_creators.active AS active,
                            service_brands.title AS brand,
                            service_areas.title AS area,
                            service_groups.title AS groups,
                            service_categories.title AS category,
                            users.email AS users
                        FROM service_creators
                        INNER JOIN service_areas ON service_areas.id = service_creators.area_id
                        INNER JOIN service_groups ON service_groups.id = service_creators.group_id
                        INNER JOIN service_categories ON service_categories.id = service_creators.category_id
                        LEFT JOIN service_brands ON service_brands.id = service_creators.brand_id
                        LEFT JOIN users ON users.id = service_creators.user_id
                        WHERE 1 =1';

            if (Auth::user()->userRole == 2) {
                $select .= ' AND service_creators.brand_id = ' . trim(Auth::user()->serviceBrand_id);
            } elseif (trim($request->brand) != "") {
                if($request->brand != 0){
                    $select .= ' AND service_creators.brand_id = ' . trim($request->brand);
                }else{
                    $select .= ' AND ISNULL(service_creators.brand_id) ';
                }
            }

            if(Auth::user()->userRole == 3) {
                $select .= ' AND service_creators.user_id = ' . trim(Auth::user()->id);
            }

            if (trim($request->area) != "") {
                $select .= ' AND service_creators.area_id = ' . trim($request->area);
            }

            if (trim($request->group) != "") {
                $select .= ' AND service_creators.group_id = ' . trim($request->group);
            }

            if (trim($request->category) != "") {
                $select .= ' AND service_creators.category_id = ' . trim($request->category);
            }

            if (trim($request->name) != "") {
                $select .= ' AND service_creators.title LIKE "%' . trim($request->name) .'%"';
            }

            if (strpos($select, 'AND') !== false) {
                $services = DB::select(DB::raw($select));
            }

            if ($services != null) {
                $result = "";

                foreach ($services as $service) {
                    $brand = "بدون برند";
                    if($service->brand != null){
                        $brand = $service->brand;
                    }
                    $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td><div class="list-unstyled row clearfix aniimated-thumbnials">
                                                                <a href="' . $service->avatar . '" data-sub-html="' . $service->title . '">
                                                                    <img class="img-responsive thumbnail" src="' . $service->avatar . '" alt="' . $service->title . '" width="35">
                                                                </a>
                                                            </div>
                            <td>' . $service->title . '</td>
                            <td>' . $brand . '</td>
                            <td>' . $service->area . '</td>
                            <td>' . $service->groups . '</td>
                            <td>' . $service->category . '</td>
                            <td>' . $service->users . '</td>';

                    if (Auth::user()->userRole == 3) {
                        $result .= '<td>' . $service->users . '</td>';
                    }

                    if ($accessList['update_20'] == "on") {
                        $result .= '<td>
                                <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                        onclick="startUpdateServiceCreator(' . $service->id . ', document.getElementById(\'token\').value, \'#serviceId\', \'#thumb-output\', \'#brand\', \'#name\', \'#area\', \'#group\', \'#category\')">
                                        <i class="icon-search4"></i>
                                </button>
                            </td>';

                        if ($service->active) {
                            $result .= '<td id="' . $service->id . '">
                                    <button type="button" class="btn btn-success btn-icon btn-rounded"
                                            onclick="activeService(' . $service->id . ', document.getElementById(\'token\').value)">
                                            <i class="icon-user-check"></i>
                                    </button>
                                </td>';
                        } else {
                            $result .= '<td id="' . $service->id . '">
                                    <button type="button" class="btn bg-orange-400 btn-icon btn-rounded"
                                            onclick="activeService(' . $service->id . ', document.getElementById(\'token\').value)">
                                            <i class="icon-user-block"></i>
                                    </button>
                                </td>';
                        }
                    } if ($accessList['delete_20'] == "on"){
                        $result .= '<td>
                                <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                        onclick="deleteServiceCreator(' . $service->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-trash"></i>
                                </button>
                            </td>';
                    }

                    $result .= '</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 20, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $serviceCreator = ServiceCreator::find($request->id);

        $brands = ServiceBrand::all();
        $brandSelector = "";
        foreach ($brands as $brand){
            if($serviceCreator->brand_id == null){
                $brandSelector .= '<option value="0" selected>بدون برند</option>';
            } else {
                $brandSelector .= '<option value="0">بدون برند</option>';
            }
            if($brand->id == $serviceCreator->brand_id)
            {
                $brandSelector .= '<option value="' . $brand->id . '" selected>' . $brand->title . '</option>';
            }
            else
            {
                $brandSelector .= '<option value="' . $brand->id . '">' . $brand->title . '</option>';
            }
        }

        $areas = ServiceArea::all();
        $areaSelector = "";
        foreach ($areas as $area){
            if($area->id == $serviceCreator->area_id)
            {
                $areaSelector .= '<option value="' . $area->id . '" selected>' . $area->title . '</option>';
            }
            else
            {
                $areaSelector .= '<option value="' . $area->id . '">' . $area->title . '</option>';
            }
        }

        $groups = ServiceGroup::where('area_id', '=', $serviceCreator->area_id)->get();
        $groupSelector = "";
        foreach ($groups as $group){
            if($group->id == $serviceCreator->group_id)
            {
                $groupSelector .= '<option value="' . $group->id . '" selected>' . $group->title . '</option>';
            }
            else
            {
                $groupSelector .= '<option value="' . $group->id . '">' . $group->title . '</option>';
            }
        }

        $categories = ServiceCategory::where('group_id', '=', $serviceCreator->group_id)->get();
        $categorySelector = "";
        foreach ($categories as $category){
            if($category->id == $serviceCreator->category_id)
            {
                $categorySelector .= '<option value="' . $category->id . '" selected>' . $category->title . '</option>';
            }
            else
            {
                $categorySelector .= '<option value="' . $category->id . '">' . $category->title . '</option>';
            }
        }

        $result = $serviceCreator->id . "_:_" . $serviceCreator->url_avatar . "_:_" . $serviceCreator->title . "_:_" . $brandSelector
            . "_:_" . $areaSelector . "_:_" . $groupSelector. "_:_" . $categorySelector;

        return $result;
    }

    public function update(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);

        $brand = null;
        if($request->brand != 0){
            $brand = $request->brand;
        }

        if($accessList['update_20'] == "on") {
            $service = ServiceCreator::find($request->id);

            $service->title = $request->name;
            $service->brand_id = $brand;
            $service->area_id = $request->area;
            $service->group_id = $request->group;
            $service->category_id = $request->category;

            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $name = time() . Auth::user()->phone . '.' . $image->getClientOriginalExtension();
                $destination = '/images/product/service';

                (new FileController)->save($destination, $name, $image);

                (new FileController)->delete($service->url_avatar);

                $service->url_avatar = trim('/images/product/service/' . $name);
            }

            $service->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 20, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function delete(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['delete_20'] == "on") {
            $count = ServiceProvider::where('service_id', '=', $request->id)->count();

            if($count == 0){
                $service = ServiceCreator::find($request->id);

                (new FileController)->delete($service->url_avatar);

                $service->delete();

                (new LogHistoryController)->logSave(Auth::user()->id, 20, false, false, false, false, true, false);

                return 1;
            } else {
                return 0;
            }
        } else {
            abort(404);
        }
    }

    public function active(Request $request){
        $this->accessLevel = Auth::user()->usrRole;
        $this->accessList = json_decode($this->accessLevel->json, true);
        if($this->accessList['update_20'] == "on") {
            $service = ServiceCreator::find($request->id);

            if ($service->active) {
                $active = 0;
            } else {
                $active = 1;
                if($this->accessList['access_20'] == "on") {
                    $service->accept = 'تایید شده';
                }
            }

            $service->active = $active;

            $service->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 20, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

}