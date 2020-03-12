<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceProviderRequest;
use App\ServiceArea;
use App\ServiceBrand;
use App\ServiceCategory;
use App\ServiceCreator;
use App\ServiceGroup;
use App\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_21'] == "on") {
            $providers = null;
            if (Auth::user()->userRole == 2) {
                $providers = ServiceProvider::where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->paginate(20);
            } else if (Auth::user()->userRole == 3) {
                $providers = ServiceProvider::where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->paginate(20);
            } else {
                $providers = ServiceProvider::orderBy('id', 'DESC')->paginate(20);
            }
            if (Auth::user()->userRole == 3) {
                $workField = json_decode(Auth::user()->workFieldService);
                if($workField != null){
                    $areas = ServiceArea::whereIn('id', $workField)->get();
                }
            } else {
                $areas = ServiceArea::all();
            }
            $brands = ServiceBrand::all();

            (new LogHistoryController)->logSave(Auth::user()->id, 21, true, false, false, false, false, false);

            return view('manage.pages.serviceProvider', compact('providers', 'areas', 'brands'));
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

    public function getService(Request $request){
        $services = ServiceCreator::where('brand_id', '=', $request->brand)
            ->where('area_id', '=', $request->area)
            ->where('group_id', '=', $request->group)
            ->where('category_id', '=', $request->category)->get();
        $serviceSelector = "";
        foreach ($services as $service){
            $serviceSelector .= '<option value="' . $service->id . '">' . $service->service->title . '</option>';
        }
        return $serviceSelector;
    }

    public function save(ServiceProviderRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['insert_21'] == "on") {
//        date_default_timezone_set("Asia/Tehran");

            ServiceProvider::create([
                'service_id' => trim($request->service),
                'price' => $request->price,
                'status' => $request->status,
                'user_id' => Auth::user()->id,
                'accept' => 'تایید شده',
                'timestamp' => time(),
                'active' => 0,
            ]);

            $providers = null;
            if (Auth::user()->userRole == 2) {
                $providers = ServiceProvider::where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->paginate(20);
            } else if (Auth::user()->userRole == 3) {
                $providers = ServiceProvider::where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->paginate(20);
            } else {
                $providers = ServiceProvider::orderBy('id', 'DESC')->paginate(20);
            }
            if (Auth::user()->userRole == 3) {
                $workField = json_decode(Auth::user()->workFieldService);
                if($workField != null){
                    $areas = ServiceArea::whereIn('id', $workField)->get();
                }
            } else {
                $areas = ServiceArea::all();
            }
            $brands = ServiceBrand::all();

            (new LogHistoryController)->logSave(Auth::user()->id, 21, false, false, false, true, false, false);

            return view('manage.pages.serviceProvider', compact('providers', 'areas', 'brands'));
        } else {
            abort(404);
        }
    }

    public function active(Request $request){
        $serviceProvider = ServiceProvider::find($request->id);

        if($serviceProvider->active){
            $active = 0;
        } else {
            $active = 1;
        }

        $serviceProvider->active = $active;

        $serviceProvider->save();
    }

    public function search(Request $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_21'] == "on") {
            $brand = null;
            $area = null;
            $group = null;
            $category = null;
            $service = null;
            $price = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;
            $services = null;

            $select = 'SELECT 
                        service_providers.id, 
                        service_providers.service_id, 
                        service_providers.price, 
                        service_providers.user_id, 
                        service_providers.`timestamp`,
                        service_providers.active, 
                        service_brands.title AS brand, 
                        service_areas.title AS area, 
                        service_groups.title AS `group`, 
                        service_categories.title AS category, 
                        services.title AS title, 
                        service_creators.url_avatar AS url_avatar, 
                        users.email AS users
                    FROM service_providers 
                    INNER JOIN service_creators ON service_creators.id = service_providers.service_id 
                    INNER JOIN service_brands ON service_brands.id = service_creators.brand_id 
                    INNER JOIN service_areas ON service_areas.id = service_creators.area_id 
                    INNER JOIN service_groups ON service_groups.id = service_creators.group_id 
                    INNER JOIN service_categories ON service_categories.id = service_creators.category_id 
					INNER JOIN services ON services.id = service_creators.service_id
                    INNER JOIN users ON users.id = service_providers.user_id 
                    WHERE 1 =1';

            if (trim($request->brand) != "") {
                $brand = trim($request->brand);
                $select .= ' AND service_creators.brand_id = ' . $brand;
            }

            if(Auth::user()->userRole == 3) {
                $select .= ' AND service_providers.user_id = ' . trim(Auth::user()->id);
            }

            if (trim($request->area) != "") {
                $area = trim($request->area);
                $select .= ' AND service_creators.area_id = ' . $area;
            }

            if (trim($request->group) != "") {
                $group = trim($request->group);
                $select .= ' AND service_creators.group_id = ' . $group;
            }

            if (trim($request->category) != "") {
                $category = trim($request->category);
                $select .= ' AND service_creators.category_id = ' . $category;
            }

            if (trim($request->service) != "") {
                $service = trim($request->service);
                $select .= ' AND service_creators.service_id = ' . $service;
            }

            if (trim($request->price) != "") {
                $price = trim($request->price);
                $select .= ' AND service_providers.price = "' . $price . '"';
            }

            if (strpos($select, 'AND') !== false) {
                $services = DB::select(DB::raw($select));
            }

            if ($services != null) {
                $result = "";

                foreach ($services as $service) {
                    $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td>
                            <div class="list-unstyled row clearfix aniimated-thumbnials">
                                                                <a href="' . $service->url_avatar . '" data-sub-html="' . $service->title . '">
                                                                    <img class="img-responsive thumbnail" src="' . $service->url_avatar . '" alt="' . $service->title . '" width="35">
                                                                </a>
                                                            </div>
                            <td>' . $service->title . '</td>
                            <td>' . $service->brand . '</td>
                            <td>' . $service->area . '</td>
                            <td>' . $service->group . '</td>
                            <td>' . $service->category . '</td>
                            <td>' . $service->price . '</td>';

                    if (Auth::user()->userRole == 3) {
                        $result .= '<td>' . $service->users . '</td>';
                    }

                    if ($accessList['update_21'] == "on") {
                        $result .= '<td>
                                <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                        onclick="startUpdateServiceProvider(' . $service->id . ', document.getElementById(\'token\').value, \'#providerId\', \'#brand\', \'#area\', \'#group\', \'#category\', \'#service\', \'#price\', \'#status\')">
                                        <i class="icon-search4"></i>
                                </button>
                            </td>';
                    }
                    if ($service->active) {
                        $result .= '<td>
                                <button type="button" class="btn btn-success btn-icon btn-rounded"
                                        onclick="activeServiceProvider(' . $service->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-user-check"></i>
                                </button>
                            </td>';
                    } else {
                        $result .= '<td>
                                <button type="button" class="btn-secondary btn-icon btn-rounded"
                                        onclick="activeServiceProvider(' . $service->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-user-block"></i>
                                </button>
                            </td>';
                    }

                    $result .= '</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 21, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $provider = ServiceProvider::find($request->id);

        $brands = ServiceBrand::all();
        $brandSelector = '<option value="">انتخاب کنید</option>';

        foreach ($brands as $brand)
        {
            if($brand->id == $provider->service->brand_id)
            {
                $brandSelector .= '<option value="' . $brand->id . '" selected>' . $brand->title . '</option>';
            }
            else
            {
                $brandSelector .= '<option value="' . $brand->id . '">' . $brand->title . '</option>';
            }
        }

        $areas = ServiceArea::all();
        $areaSelector = '<option value="">انتخاب کنید</option>';

        foreach ($areas as $area)
        {
            if($area->id == $provider->service->area_id)
            {
                $areaSelector .= '<option value="' . $area->id . '" selected>' . $area->title . '</option>';
            }
            else
            {
                $areaSelector .= '<option value="' . $area->id . '">' . $area->title . '</option>';
            }
        }

        $groups = ServiceGroup::where('area_id', '=', $provider->service->area_id)->get();
        $groupSelector = '<option value="">انتخاب کنید</option>';

        foreach ($groups as $group)
        {
            if($group->id == $provider->service->group_id)
            {
                $groupSelector .= '<option value="' . $group->id . '" selected>' . $group->title . '</option>';
            }
            else
            {
                $groupSelector .= '<option value="' . $group->id . '">' . $group->title . '</option>';
            }
        }

        $categories = ServiceCategory::where('group_id', '=', $provider->service->group_id)->get();
        $categorySelector = '<option value="">انتخاب کنید</option>';

        foreach ($categories as $category){
            if($category->id == $provider->service->category_id)
            {
                $categorySelector .= '<option value="' . $category->id . '" selected>' . $category->title . '</option>';
            }
            else
            {
                $categorySelector .= '<option value="' . $category->id . '">' . $category->title . '</option>';
            }
        }

        $services = ServiceCreator::where('brand_id', '=', $provider->service->brand_id)
            ->where('area_id', '=', $provider->service->area_id)
            ->where('group_id', '=', $provider->service->group_id)
            ->where('category_id', '=', $provider->service->category_id)->get();
        $serviceSelector = "";

        foreach ($services as $service){
            if($service->id == $provider->service->id)
            {
                $serviceSelector .= '<option value="' . $service->id . '" selected>' . $service->service->title . '</option>';
            }
            else {
                $serviceSelector .= '<option value="' . $service->id . '">' . $service->service->title . '</option>';
            }
        }

        $result = $provider->id . "_:_" . $provider->price . "_:_" . $provider->status . "_:_" . $brandSelector
            . "_:_" . $areaSelector . "_:_" . $groupSelector . "_:_" . $categorySelector . "_:_" . $serviceSelector;

        return $result;
    }

    public function update(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['update_21'] == "on") {
            $service = ServiceProvider::find($request->id);

            $service->price = $request->price;
            $service->status = $request->sharh;
            $service->service_id = $request->service;

            $service->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 21, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }
}