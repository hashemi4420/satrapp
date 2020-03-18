<?php

namespace App\Http\Controllers;

use App\ActiveFreeCall;
use App\ArticleArea;
use App\ArticleBrand;
use App\ArticleCreator;
use App\ArticleProvider;
use App\BusinessNumber;
use App\City;
use App\District;
use App\Http\Requests\ResellerRequest;
use App\Location;
use App\ServiceArea;
use App\ServiceBrand;
use App\ServiceCreator;
use App\ServiceProvider;
use App\State;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CreateResellerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_35'] == "on"){

            $articles = ArticleArea::where('id', '=', 0);
            $services = ServiceArea::where('id', '=', 0);

            $articleBrand = "";
            $serviceBrand = "";
            $state = "";

            $users = null;

            if(Auth::user()->userRole == 1){
                $articles = ArticleArea::all();
                $services = ServiceArea::all();
                $users = User::where('userRole', '=', '3')->orderBy('id', 'DESC')->paginate(20);

                $brands = ArticleBrand::all();
                foreach ($brands as $brand){
                    if($articleBrand != ""){
                        $articleBrand .= ",";
                    }
                    $articleBrand .= $brand->id . ":" . $brand->title;
                }
                $brands = ServiceBrand::all();
                foreach ($brands as $brand){
                    if($serviceBrand != ""){
                        $serviceBrand .= ",";
                    }
                    $serviceBrand .= $brand->id . ":" . $brand->title;
                }
            } else {
                if(Auth::user()->articleBrand_id != null && Auth::user()->articleBrand_id != ""){
                    $articles = ArticleArea::all();
                    $users = User::where('userRole', '=', '3')->whereNotNull('articleBrand_id')->whereNull('serviceBrand_id')->orderBy('id', 'DESC')->paginate(20);
                    $brands = ArticleBrand::where('id', '=', Auth::user()->articleBrand_id)->get();
                    foreach ($brands as $brand){
                        if($articleBrand != ""){
                            $articleBrand .= ",";
                        }
                        $articleBrand .= $brand->id . ":" . $brand->title;
                    }
                }
                if(Auth::user()->serviceBrand_id != null && Auth::user()->serviceBrand_id != ""){
                    $services = ServiceArea::all();
                    $users = User::where('userRole', '=', '3')->whereNotNull('serviceBrand_id')->whereNull('articleBrand_id')->orderBy('id', 'DESC')->paginate(20);
                    $brands = ServiceBrand::where('id', '=', Auth::user()->serviceBrand_id)->get();
                    foreach ($brands as $brand){
                        if($serviceBrand != ""){
                            $serviceBrand .= ",";
                        }
                        $serviceBrand .= $brand->id . ":" . $brand->title;
                    }
                }
            }

            $states = State::all();
            foreach ($states as $brand){
                if($state != ""){
                    $state .= ",";
                }
                $state .= $brand->id . ":" . $brand->title;
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 35, true, false, false, false, false, false);

            return view('manage.pages.createReseller', compact('users','articles', 'services', 'serviceBrand', 'articleBrand', 'state'));
        } else {
            abort(404);
        }
    }

    public function productServicePage(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['read_35'] == "on"){

            $user = User::find($request->userId);
            if ($user->workFieldArticle != null && $user->workFieldArticle != "") {

                $articles = ArticleProvider::where('user_id', '=', $user->id)->orderBy('id', 'DESC')->get();

                foreach ($articles as $article){
                    $darsad = $request['darsad'.$article->id];
                    $pey = $request['pey'.$article->id];
                    $atless = $request['atless'.$article->id];
                    if( ($darsad != null && $darsad > 0) || ($pey != null && $pey > 0) || ($atless != null && $atless > 0)){

                        if($pey == null){
                            $article->price = 0;
                        }else {
                            $article->price = $pey;
                        }
                        if($darsad == null){
                            $article->status = 0;
                        }else {
                            $article->status = $darsad;
                        }
                        if($atless == null){
                            $article->atless = 0;
                        }else {
                            $article->atless = $atless;
                        }
                        $article->save();
                    }
                }

            } elseif ($user->workFieldService != null && $user->workFieldService != "") {
                $services = ServiceProvider::where('user_id', '=', $user->id)->orderBy('id', 'DESC')->get();

                foreach ($services as $service){
                    $darsad = $request['darsad'.$service->id];
                    $pey = $request['pey'.$service->id];
                    $atless = $request['atless'.$service->id];
                    if( ($darsad != null && $darsad > 0) || ($pey != null && $pey > 0) || ($atless != null && $atless > 0)){

                        if($pey == null){
                            $service->price = 0;
                        }else {
                            $service->price = $pey;
                        }
                        if($darsad == null){
                            $service->status = 0;
                        }else {
                            $service->status = $darsad;
                        }
                        if($atless == null){
                            $service->atless = 0;
                        }else {
                            $service->atless = $atless;
                        }
                        $service->save();
                    }
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 35, true, false, false, false, false, false);
            return redirect('/manage/createReseller');
        } else {
            abort(404);
        }
    }

    public function save(ResellerRequest $request)
    {
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['insert_35'] == "on"){

            $workFieldService = null;
            $workFieldArticle = null;
            if ($request['service'] != "" && $request['service'] != null) {
                $workFieldService = trim($request['service']);
            }
            if ($request['article'] != "" && $request['article'] != null) {
                $workFieldArticle = trim($request['article']);
            }
            $avatar = null;
            if ($request->avatar != "") {
                $image = $request->file('avatar');
                $name = time() . $request['phone'] . '.' . $image->getClientOriginalExtension();

                $destination = '/images/avatar';
                (new FileController)->save($destination, $name, $image);

                $avatar = trim($destination.'/'.$name);
            }

            $user = User::create([
                'phone' => trim($request['phone']),
                'email' => trim($request['email']),
                'name' => trim($request['name']),
                'family' => trim($request['family']),
                'title_company' => trim($request['company']),
                'numberFree' => trim($request['numberFree']),
                'numberPey' => trim($request['phone']),
                'cash' => trim($request['freeLogo']),
                'active' => 1,
                'timestamp' => time(),
                'password' => Hash::make(trim($request['password'])),
                'userRole' => 3,
                'url_avatar' => $avatar,
                'email_verify_code' => rand(1000, 9999),
                'phone_verify_code' => rand(1000, 9999),
                'workFieldService' => $workFieldService,
                'workFieldArticle' => $workFieldArticle,
                'news' => 1,
            ]);

            $number = $this->createBusinessNumber();

            if(trim($request['freeCall']) != null && trim($request['freeCall'] != "")){
                $freeCall = ActiveFreeCall::where('user_id', '=', $user->id)->get();
                if(1 == $freeCall->count()){
                    foreach ($freeCall as $item){
                        $item->pey = (int)$freeCall->pey + (int)trim($request['freeCall']);
                        $item->save();
                    }
                } else {
                    ActiveFreeCall::create([
                        'user_id' => $user->id,
                        'pey' => trim($request['freeCall']),
                        'active' => 1,
                    ]);
                }
            }

            BusinessNumber::create([
                'user_id' => $user->id,
                'number' => $number,
                'timestamp' => time(),
            ]);

            $articles = ArticleArea::where('id', '=', 0);
            $services = ServiceArea::where('id', '=', 0);

            $articleBrand = "";
            $serviceBrand = "";
            $state = "";

            $users = null;

            if(Auth::user()->userRole == 1){
                $articles = ArticleArea::all();
                $services = ServiceArea::all();
                $users = User::where('userRole', '=', '3')->orderBy('id', 'DESC')->paginate(20);

                $brands = ArticleBrand::all();
                foreach ($brands as $brand){
                    if($articleBrand != ""){
                        $articleBrand .= ",";
                    }
                    $articleBrand .= $brand->id . ":" . $brand->title;
                }
                $brands = ServiceBrand::all();
                foreach ($brands as $brand){
                    if($serviceBrand != ""){
                        $serviceBrand .= ",";
                    }
                    $serviceBrand .= $brand->id . ":" . $brand->title;
                }
            } else {
                if(Auth::user()->articleBrand_id != null && Auth::user()->articleBrand_id != ""){
                    $articles = ArticleArea::all();
                    $users = User::where('userRole', '=', '3')->whereNotNull('articleBrand_id')->whereNull('serviceBrand_id')->orderBy('id', 'DESC')->paginate(20);
                    $brands = ArticleBrand::where('id', '=', Auth::user()->articleBrand_id)->get();
                    foreach ($brands as $brand){
                        if($articleBrand != ""){
                            $articleBrand .= ",";
                        }
                        $articleBrand .= $brand->id . ":" . $brand->title;
                    }
                }
                if(Auth::user()->serviceBrand_id != null && Auth::user()->serviceBrand_id != ""){
                    $services = ServiceArea::all();
                    $users = User::where('userRole', '=', '3')->whereNotNull('serviceBrand_id')->whereNull('articleBrand_id')->orderBy('id', 'DESC')->paginate(20);
                    $brands = ServiceBrand::where('id', '=', Auth::user()->serviceBrand_id)->get();
                    foreach ($brands as $brand){
                        if($serviceBrand != ""){
                            $serviceBrand .= ",";
                        }
                        $serviceBrand .= $brand->id . ":" . $brand->title;
                    }
                }
            }

            $states = State::all();
            foreach ($states as $brand){
                if($state != ""){
                    $state .= ",";
                }
                $state .= $brand->id . ":" . $brand->title;
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 35, false, false, false, true, false, false);

            return view('manage.pages.createReseller', compact('users','articles', 'services', 'serviceBrand', 'articleBrand', 'state'));
        } else {
            abort(404);
        }
    }

    protected function createBusinessNumber(){
        while (1 == 1){
            $number = '019'.rand(10000000, 99999999);
            $check = BusinessNumber::where('number', '=', $number)->first();
            if($check === null){
                return $number;
            }
        }
    }

    public function search(Request $request)
    {
        $this->accessLevel = Auth::user()->usrRole;
        $this->accessList = json_decode($this->accessLevel->json, true);
        if($this->accessList['read_35'] == "on") {
            $email = null;
            $phone = null;
            $users = null;
            $result = "<tr><td>نتیجه ای یافت نشد...</td></tr>";
            $id = 1;

            if (trim($request->email) != "") {
                $email = trim($request->email);
            }
            if (trim($request->phone) != "") {
                $phone = trim($request->phone);
            }

            if($email != null && $phone != null){
                $users = User::where('email', 'like', '%' . $email . '%')->where('phone', 'like', '%' . str_replace(" ", "", $phone) . '%')->where('userRole', '=', '3')->get();
            } else if ($email != null && $phone == null) {
                $users = User::where('email', 'like', '%' . $email . '%')->where('userRole', '=', '3')->get();
            } else if ($phone != null && $email == null) {
                $users = User::where('phone', 'like', '%' . str_replace(" ", "", $phone) . '%')->where('userRole', '=', '3')->get();
            }

            if ($users != null) {
                $result = "";

                foreach ($users as $user) {
                    $result .= '<tr>
                            <td>' . $id++ . '</td>
                            <td>' . $user->name . '</td>
                            <td>' . $user->family . '</td>
                            <td>' . $user->phone . '</td>
                            <td>' . $user->email . '</td>
                            <td>' . $user->usrRole->title . '</td>';
                    if ($user->workFieldArticle != null) {
                        $result .= '<td>' . $user->articleArea->title . '</td>';
                    } else {
                        $result .= '<td style="color: red">ندارد</td>';
                    }

                    if ($user->workFieldService != null) {
                        $result .= '<td>' . $user->serviceArea->title . '</td>';
                    } else {
                        $result .= '<td style="color: red">ندارد</td>';
                    }
                    if($this->accessList['access_35'] == "on") {
                        $result .= '<td>
                            <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                    onclick="startUpdateUser('.$user->id.', document.getElementById(\'token\').value, \'#userId\', \'#name\', \'#family\', \'#company\', \'#email\', \'#password\', \'#phone\', \'#articleId\', \'#serviceId\', \'#numberFree\', \'#freeCall\', \'#freeLogo\', \'#thumb-output\')">
                                    <i class="icon-search4"></i>
                            </button>
                        </td>';

                        if ($user->active) {
                            $result .= '<td id="' . $user->id . '">
                                    <button type="button" class="btn btn-success btn-icon btn-rounded"
                                            onclick="activeUser(' . $user->id . ', document.getElementById(\'token\').value)">
                                            <i class="icon-user-check"></i>
                                    </button>
                                </td>';
                        } else {
                            $result .= '<td id="' . $user->id . '">
                                    <button type="button" class="btn bg-orange-400 btn-icon btn-rounded"
                                            onclick="activeUser(' . $user->id . ', document.getElementById(\'token\').value)">
                                            <i class="icon-user-block"></i>
                                    </button>
                                </td>';
                        }

                        $result .= '<td>
                                    <button type="button" class="btn btn-info btn-icon btn-rounded"
                                            onclick="changePass(' . $user->id . ', document.getElementById(\'token\').value)">
                                        <i class="icon-shield2"></i>
                                    </button>
                               </td>';

                        $result .= '<td>
                                    <button type="button" class="btn btn-info btn-icon btn-rounded"
                                            onclick="takhsis(';

                        if ($user->workFieldArticle != null) {
                            $result .= $user->workFieldArticle;
                        } else {
                            $result .= 'null';
                        }
                        $result .= ',';
                        if ($user->workFieldService != null) {
                            $result .= $user->workFieldService;
                        } else {
                            $result .= 'null';
                        }
                        $result .= ','. $user->id . ', \'#takhsis\', document.getElementById(\'token\').value)">
                                        <i class="fas fa-braille"></i>
                                    </button>
                               </td>';

                        $result .= '<td>
                                        <button type="button" class="btn btn-info btn-icon btn-rounded" 
                                                onclick="productService(' . $user->id . ', \'#takhsis\', document.getElementById(\'token\').value)">
                                                <i class="fas fa-clipboard-list"></i>
                                        </button>
                                   </td>';

                        $result .= '<td>
                                    <button type="button" class="btn btn-info btn-icon btn-rounded"
                                            onclick="covered(' . $user->id . ', \'#takhsis\', document.getElementById(\'token\').value)">
                                        <i class="flaticon-placeholder"></i>
                                    </button>
                               </td>';
                    }
                    $result .= '</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 35, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function active(Request $request){
        $this->accessLevel = Auth::user()->usrRole;
        $this->accessList = json_decode($this->accessLevel->json, true);
        if($this->accessList['update_35'] == "on") {
            $user = User::find($request->id);

            if ($user->active) {
                $active = 0;
            } else {
                $active = 1;
            }

            $user->active = $active;

            $user->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 35, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function changePass(Request $request){
        $this->accessLevel = Auth::user()->usrRole;
        $this->accessList = json_decode($this->accessLevel->json, true);
        if($this->accessList['update_35'] == "on") {
            $user = User::find($request->id);

            $user->password = Hash::make(trim(implode($request->password)));

            $user->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 35, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $user = User::find($request->id);
        $freeCall = ActiveFreeCall::where('user_id', '=', $request->id)->get();

        $workFieldArticle = null;
        $workFieldService = null;
        if($user->workFieldArticle != null){
            $workFieldArticle = trim($user->workFieldArticle);
        }
        if($user->workFieldService != null){
            $workFieldService = trim($user->workFieldService);
        }

        $data = "";
        foreach ($freeCall as $free){
            $data = $free->pey;
        }

        $result = $user->id . "_:_" . $user->name . "_:_" . $user->family . "_:_" . $user->title_company . "_:_" . $user->email
            . "_:_" . $user->password . "_:_" . $user->phone . "_:_" . $workFieldArticle . "_:_" . $workFieldService
            . "_:_" . $user->numberFree . "_:_" . $data  . "_:_" . $user->cash . "_:_" . $user->url_avatar;

        return $result;
    }

    public function update(Request $request){
        $this->accessLevel = Auth::user()->usrRole;
        $this->accessList = json_decode($this->accessLevel->json, true);

        if($this->accessList['update_35'] == "on") {

            $user = User::find($request->id);

            $workFieldService = null;
            $workFieldArticle = null;
            if ($request['serviceId'] != "" && $request['serviceId'] != null) {
                $workFieldService = trim($request['serviceId']);
            }
            if ($request['articleId'] != "" && $request['articleId'] != null) {
                $workFieldArticle = trim($request['articleId']);
            }

            $user->name = $request->name;
            $user->family = $request->family;
            $user->title_company = $request->companyId;
            $user->numberFree = $request->numberFreeId;
            $user->cash = $request->freeLogo;

            $user->workFieldArticle = $workFieldArticle;
            $user->workFieldService = $workFieldService;

            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $name = time() . $user->phone . '.' . $image->getClientOriginalExtension();
                $destination = '/images/avatar';

                (new FileController)->delete($user->url_avatar);

                (new FileController)->save($destination, $name, $image);

                $user->url_avatar = trim($destination.'/'.$name);
            }

            $user->save();

            if(trim($request['freeCallId']) != null && trim($request['freeCallId'] != "")){

                $freeCall = ActiveFreeCall::where('user_id', '=', $user->id)->get();
                if(1 == $freeCall->count()){
                    foreach ($freeCall as $item){
                        $item->pey = trim($request['freeCallId']);
                        $item->save();
                    }
                } else {
                    ActiveFreeCall::create([
                        'user_id' => $user->id,
                        'pey' => trim($request['freeCallId']),
                        'active' => 1,
                    ]);
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 35, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function takhsis(Request $request)
    {
        $brands = null;
        $searches = null;
        $result = "";

        $limit = 20;
        $skip = ($request->page - 1) * $limit;
        $id = $skip + 1;

        $count = 0;

        $user = User::find($request->userId);

        if ($user->workFieldArticle != null && $user->workFieldArticle != "") {
            $areas = trim($user->workFieldArticle);
            $count = ArticleCreator::where('brand_id', '=', $request->brand)->where('area_id', '=', $areas)->count();
            $searches = ArticleCreator::where('brand_id', '=', $request->brand)->where('area_id', '=', $areas)->orderBy('brand_id', 'DESC')->skip($skip)->take($limit)->get();
        } elseif ($user->workFieldService != null && $user->workFieldService != "") {
            $areas = trim($user->workFieldService);
            $count = ServiceCreator::where('brand_id', '=', $request->brand)->where('area_id', '=', $areas)->count();
            $searches = ServiceCreator::where('brand_id', '=', $request->brand)->where('area_id', '=', $areas)->orderBy('brand_id', 'DESC')->skip($skip)->take($limit)->get();
        }

        if ($searches != null) {
            if ($searches->count() > 0) {

                $result .= '<div class="card"><div class="card-body"><div class="table-responsive"><table class="table table-striped">';
                $result .= '<thead><tr><th>ردیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>';
                $result .= '<th>تصویر <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>';
                $result .= '<th>برند <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>';
                $result .= '<th>نوع <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>';
                $result .= '<th>انتخاب</th></tr></thead>';
                $result .= '<tbody id="dataTable">';

                foreach ($searches as $search) {
                    if ($user->workFieldArticle != null && $user->workFieldArticle != "") {
                        $result .= '<tr>';
                        $result .= '<td>' . $id++ . '</td>';
                        $result .= '<td><div class="list-unstyled row clearfix aniimated-thumbnials">
                                        <a href="' . $search->url_avatar . '" data-sub-html="' . $search->title . '">
                                            <img class="img-responsive thumbnail" src="' . $search->url_avatar . '" alt="' . $search->title . '" width="35">
                                        </a>
                                    </div></td>';
                        $result .= '<td>' . $search->brand->title . '</td>';
                        $result .= '<td>' . $search->title . '</td>';
                        $result .= '<td><input type="checkbox" onchange="saveArticle(' . $request->userId . ', '.$search->id.', \''.$request->token.'\')"></td>';
                        $result .= '</tr>';
                    } elseif ($user->workFieldService != null && $user->workFieldService != "") {
                        $result .= '<tr>';
                        $result .= '<td>' . $id++ . '</td>';
                        $result .= '<td><div class="list-unstyled row clearfix aniimated-thumbnials">
                                        <a href="' . $search->url_avatar . '" data-sub-html="' . $search->title . '">
                                            <img class="img-responsive thumbnail" src="' . $search->url_avatar . '" alt="' . $search->title . '" width="35">
                                        </a>
                                    </div></td>';
                        $result .= '<td>' . $search->brand->title . '</td>';
                        $result .= '<td>' . $search->title . '</td>';
                        $result .= '<td><input type="checkbox" onchange="saveService(' . $request->userId . ', '.$search->id.', \''.$request->token.'\')"></td>';
                        $result .= '</tr>';
                    }
                }

                $result .= '</tbody></table></div>';

                $result .= '<nav aria-label="Page navigation example"><ul class="pagination" role="navigation">';

                $step = $count / $limit;
                $step = (int)$step;
                if($count % $limit > 0){
                    $step = $step +1;
                }
                $step = round($step, 0, PHP_ROUND_HALF_DOWN);

                if (1 == ($request->page)) {
                    $result .= '<li class="page-item disabled" aria-disabled="true" aria-label="« قبلی"><span class="page-link" aria-hidden="true">‹</span></li>';
                } else {
                    $page = $request->page-1;
                    $result .= '<li class="page-item">
                                    <a class="page-link" href="#" onclick="takhsisPage('.$request->userId.', '. $page .', '.$request->brand.', \'#takhsis\', \''.$request->token.'\');" rel="prev" aria-label="« قبلی">‹</a>
                                </li>';
                }


                for ($i = 1; $i < 8; $i++) {
                    if ($step < 8) {
                        if($step <= $i){
                            if ($request->page == $i) {
                                $result .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                            } else {
                                $result .= '<li class="page-item"><a class="page-link" href="#" onclick="takhsisPage('.$request->userId.', '. $i .', '.$request->brand.', \'#takhsis\', \''.$request->token.'\');">' . $i . '</a></li>';
                            }
                        }
                    } else {
                        if($request->page <= 5){
                            if($i == 1){
                                if($request->page == $i){
                                    $result .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                                } else {
                                    $result .= '<li class="page-item"><a class="page-link" href="#" onclick="takhsisPage('.$request->userId.', '. $i .', '.$request->brand.', \'#takhsis\', \''.$request->token.'\');">' . $i . '</a></li>';
                                }
                            } else if($i == 7){
                                $result .= '<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>';
                                $result .= '<li class="page-item"><a class="page-link" href="#" onclick="takhsisPage('.$request->userId.', '. $step .', '.$request->brand.', \'#takhsis\', \''.$request->token.'\');">' . $step . '</a></li>';
                            } else {
                                if ($request->page == $i) {
                                    $result .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                                } else {
                                    $result .= '<li class="page-item"><a class="page-link" href="#" onclick="takhsisPage('.$request->userId.', '. $i .', '.$request->brand.', \'#takhsis\', \''.$request->token.'\');">' . $i . '</a></li>';
                                }
                            }
                        } /*else if($request->page > 5){
                            if ($i < 6){
                                if ($request->page == $i) {
                                    $result .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                                } else {
                                    $result .= '<li class="page-item"><a class="page-link" href="#" onclick="takhsisPage('.$request->userId.', '. $i .', '.$request->brand.', \'#takhsis\', \''.$request->token.'\');">' . $i . '</a></li>';
                                }
                            } else {
                                $page = $step+1;
                                $result .= '<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>';
                                $result .= '<li class="page-item"><a class="page-link" href="#" onclick="takhsisPage('.$request->userId.', '. $page .', '.$request->brand.', \'#takhsis\', \''.$request->token.'\');">' . $page . '</a></li>';
                            }
                        }*/ else if($request->page > 5){
                            if($request->page < $step- 5){
                                $page1 = $request->page - 2;
                                $page2 = $request->page -1;
                                $page3 = $request->page;
                                $page4 = $request->page + 1;
                                $page5 = $request->page + 2;

                                $result .= '<li class="page-item"><a class="page-link" href="#" onclick="takhsisPage('.$request->userId.', 1, '.$request->brand.', \'#takhsis\', \''.$request->token.'\');">1</a></li>';
                                $result .= '<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>';
                                $result .= '<li class="page-item"><a class="page-link" href="#" onclick="takhsisPage('.$request->userId.', '. $page1 .', '.$request->brand.', \'#takhsis\', \''.$request->token.'\');">' . $page1 . '</a></li>';
                                $result .= '<li class="page-item"><a class="page-link" href="#" onclick="takhsisPage('.$request->userId.', '. $page2 .', '.$request->brand.', \'#takhsis\', \''.$request->token.'\');">' . $page2 . '</a></li>';
                                $result .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $page3 . '</span></li>';
                                $result .= '<li class="page-item"><a class="page-link" href="#" onclick="takhsisPage('.$request->userId.', '. $page4 .', '.$request->brand.', \'#takhsis\', \''.$request->token.'\');">' . $page4 . '</a></li>';
                                $result .= '<li class="page-item"><a class="page-link" href="#" onclick="takhsisPage('.$request->userId.', '. $page5 .', '.$request->brand.', \'#takhsis\', \''.$request->token.'\');">' . $page5 . '</a></li>';
                                $result .= '<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>';
                                $result .= '<li class="page-item"><a class="page-link" href="#" onclick="takhsisPage('.$request->userId.', '. $step .', '.$request->brand.', \'#takhsis\', \''.$request->token.'\');">' . $step . '</a></li>';

                                $i = 7;
                            } else {
                                if($i == 1){
                                    $result .= '<li class="page-item"><a class="page-link" href="#" onclick="takhsisPage('.$request->userId.', 1, '.$request->brand.', \'#takhsis\', \''.$request->token.'\');">1</a></li>';
                                    $result .= '<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>';
                                } else if($i == 7){
                                    if ($request->page == $step) {
                                        $result .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $step . '</span></li>';
                                    } else {
                                        $result .= '<li class="page-item"><a class="page-link" href="#" onclick="takhsisPage('.$request->userId.', '. $step .', '.$request->brand.', \'#takhsis\', \''.$request->token.'\');">' . $step . '</a></li>';
                                    }
                                } else {
                                    $page = $step - (7 - $i);
                                    if($request->page == ($step - (7 - $i))){
                                        $result .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $page . '</span></li>';
                                    } else {
                                        $result .= '<li class="page-item"><a class="page-link" href="#" onclick="takhsisPage('.$request->userId.', '. $page .', '.$request->brand.', \'#takhsis\', \''.$request->token.'\');">' . $page . '</a></li>';
                                    }
                                }
                            }
                        }
                    }
                }

                $page = $request->page+1;
                if (($step) == ($request->page)) {
                    $result .= '<li class="page-item disabled" aria-disabled="true" aria-label="بعدی »"><span class="page-link" aria-hidden="true">›</span></li>';
                } else {
                    $result .= '<li class="page-item">
                                    <a class="page-link" href="#" onclick="takhsisPage('.$request->userId.', '. $page .', '.$request->brand.', \'#takhsis\', \''.$request->token.'\');" rel="next" aria-label="بعدی »">›</a>
                                </li>';
                }
                $result .= '</ul></nav>';

                $result .= '</div></div>';
            }
        }

        return $result;
    }

    public function productService(Request $request)
    {
        $searches = null;
        $result = "";

        $limit = 20;
        $skip = ($request->page - 1) * $limit;
        $id = $skip + 1;

        $count = 0;

        $user = User::find($request->userId);

        if ($user->workFieldArticle != null && $user->workFieldArticle != "") {
            $count = ArticleProvider::where('user_id', '=', $request->userId)->count();
            $searches = ArticleProvider::where('user_id', '=', $request->userId)->orderBy('id', 'DESC')->skip($skip)->take($limit)->get();
        } elseif ($user->workFieldService != null && $user->workFieldService != "") {
            $count = ServiceProvider::where('user_id', '=', $request->userId)->count();
            $searches = ServiceProvider::where('user_id', '=', $request->userId)->orderBy('id', 'DESC')->skip($skip)->take($limit)->get();
        }

        if ($searches != null) {
            if ($searches->count() > 0) {



                $result .= '<div class="card">';

                $result .= '<form class="form-horizontal" method="post" id="form" action="/manage/createReseller/productServicePage">';
                $result .= '<input type="hidden" id="token" name="_token" value="'.csrf_token().'">';
                $result .= '<input type="hidden" id="userId" name="userId" value="'.$request->userId.'">';

                $result .= '<div class="card-body"><div class="table-responsive"><table class="table table-striped">';
                $result .= '<thead><tr><th>ردیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>';
                $result .= '<th>تصویر <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>';
                $result .= '<th>برند <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>';
                $result .= '<th>نوع <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>';
                $result .= '<th>درصد تخفیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>';
                $result .= '<th>قیمت(تومان) <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>';
                $result .= '<th>حداقل خرید(تومان) <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>';
                $result .= '<th>حذف</th></tr></thead>';
                $result .= '<tbody id="dataTable">';

                foreach ($searches as $search) {
                    if ($user->workFieldArticle != null && $user->workFieldArticle != "") {
                        $result .= '<tr>';
                        $result .= '<td>' . $id++ . '</td>';
                        $result .= '<td><div class="list-unstyled row clearfix aniimated-thumbnials">
                                        <a href="' . $search->article->url_avatar . '" data-sub-html="' . $search->article->title . '">
                                            <img class="img-responsive thumbnail" src="' . $search->article->url_avatar . '" alt="' . $search->article->title . '" width="35">
                                        </a>
                                    </div></td>';
                        $result .= '<td>' . $search->article->brand->title . '</td>';
                        $result .= '<td>' . $search->article->title . '</td>';
                        $result .= '<td>
                                        <input type="tel" step="any" class="form-control" value="'.$search->status.'" onkeyup="validate_int(this.value, this.id)" name="darsad'.$search->id.'" id="darsat'.$search->id.'">
                                    </td>';
                        $result .= '<td>
                                        <input type="tel" step="any" class="form-control" value="'.$search->price.'" onkeyup="validate_int(this.value, this.id)" name="pey'.$search->id.'" id="pey'.$search->id.'">
                                    </td>';
                        $result .= '<td>
                                        <input type="tel" step="any" class="form-control" value="'.$search->atless.'" onkeyup="validate_int(this.value, this.id)" name="atless'.$search->id.'" id="atless'.$search->id.'">
                                    </td>';
                        $result .= '<td>
                                        <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                            onclick="deleteArticleProvider('.$search->id.', \''.$request->token.'\')">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </td>';
                        $result .= '</tr>';
                    } elseif ($user->workFieldService != null && $user->workFieldService != "") {
                        $result .= '<tr>';
                        $result .= '<td>' . $id++ . '</td>';
                        $result .= '<td><div class="list-unstyled row clearfix aniimated-thumbnials">
                                        <a href="' . $search->service->url_avatar . '" data-sub-html="' . $search->service->title . '">
                                            <img class="img-responsive thumbnail" src="' . $search->service->url_avatar . '" alt="' . $search->service->title . '" width="35">
                                        </a>
                                    </div></td>';
                        $result .= '<td>' . $search->service->brand->title . '</td>';
                        $result .= '<td>' . $search->service->title . '</td>';
                        $result .= '<td>
                                        <input type="tel" step="any" class="form-control" value="'.$search->status.'" onkeyup="validate_int(this.value, this.id)" name="darsad'.$search->id.'" id="darsat'.$search->id.'">
                                    </td>';
                        $result .= '<td>
                                        <input type="tel" step="any" class="form-control" value="'.$search->price.'" onkeyup="validate_int(this.value, this.id)" name="pey'.$search->id.'" id="pey'.$search->id.'">
                                    </td>';
                        $result .= '<td>
                                        <input type="tel" step="any" class="form-control" value="'.$search->atless.'" onkeyup="validate_int(this.value, this.id)" name="atless'.$search->id.'" id="atless'.$search->id.'">
                                    </td>';
                        $result .= '<td>
                                        <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                            onclick="deleteServiceProvider('.$search->id.', \''.$request->token.'\')">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </td>';
                        $result .= '</tr>';
                    }
                }

                $result .= '</tbody></table></div>';

                $result .= '<div class="row"><div class="col-md-12">';

                $result .= '<nav aria-label="Page navigation example"><ul class="pagination" role="navigation">';

                $step = $count / $limit;

                $step = (int)$step;

                if($count % $limit > 0){
                    $step = $step +1;
                }

                $step = round($step, 0, PHP_ROUND_HALF_DOWN);

                if (1 == ($request->page)) {
                    $result .= '<li class="page-item disabled" aria-disabled="true" aria-label="« قبلی"><span class="page-link" aria-hidden="true">‹</span></li>';
                } else {
                    $page = $request->page-1;
                    $result .= '<li class="page-item">
                                    <a class="page-link" href="#" onclick="productServicePage('.$request->userId.', '. $page .', \'#takhsis\', \''.$request->token.'\');" rel="prev" aria-label="« قبلی">‹</a>
                                </li>';
                }


                for ($i = 1; $i < 8; $i++) {
                    if ($step < 8) {
                        if($i <= $step){
                            if ($request->page == $i) {
                                $result .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                            } else {
                                $result .= '<li class="page-item"><a class="page-link" href="#" onclick="productServicePage('.$request->userId.', '. $i .', \'#takhsis\', \''.$request->token.'\');">' . $i . '</a></li>';
                            }
                        }
                    } else {
                        if($request->page <= 5){
                            if($i == 1){
                                if($request->page == $i){
                                    $result .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                                } else {
                                    $result .= '<li class="page-item"><a class="page-link" href="#" onclick="productServicePage('.$request->userId.', '. $i .', \'#takhsis\', \''.$request->token.'\');">' . $i . '</a></li>';
                                }
                            } else if($i == 7){
                                $result .= '<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>';
                                $result .= '<li class="page-item"><a class="page-link" href="#" onclick="productServicePage('.$request->userId.', '. $step .', \'#takhsis\', \''.$request->token.'\');">' . $step . '</a></li>';
                            } else {
                                if ($request->page == $i) {
                                    $result .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                                } else {
                                    $result .= '<li class="page-item"><a class="page-link" href="#" onclick="productServicePage('.$request->userId.', '. $i .', \'#takhsis\', \''.$request->token.'\');">' . $i . '</a></li>';
                                }
                            }
                        } else if($request->page > 5){
                            if($request->page < $step- 5){
                                $page1 = $request->page - 2;
                                $page2 = $request->page -1;
                                $page3 = $request->page;
                                $page4 = $request->page + 1;
                                $page5 = $request->page + 2;

                                $result .= '<li class="page-item"><a class="page-link" href="#" onclick="productServicePage('.$request->userId.', 1, \'#takhsis\', \''.$request->token.'\');">1</a></li>';
                                $result .= '<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>';
                                $result .= '<li class="page-item"><a class="page-link" href="#" onclick="productServicePage('.$request->userId.', '. $page1 .', \'#takhsis\', \''.$request->token.'\');">' . $page1 . '</a></li>';
                                $result .= '<li class="page-item"><a class="page-link" href="#" onclick="productServicePage('.$request->userId.', '. $page2 .', \'#takhsis\', \''.$request->token.'\');">' . $page2 . '</a></li>';
                                $result .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $page3 . '</span></li>';
                                $result .= '<li class="page-item"><a class="page-link" href="#" onclick="productServicePage('.$request->userId.', '. $page4 .', \'#takhsis\', \''.$request->token.'\');">' . $page4 . '</a></li>';
                                $result .= '<li class="page-item"><a class="page-link" href="#" onclick="productServicePage('.$request->userId.', '. $page5 .', \'#takhsis\', \''.$request->token.'\');">' . $page5 . '</a></li>';
                                $result .= '<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>';
                                $result .= '<li class="page-item"><a class="page-link" href="#" onclick="productServicePage('.$request->userId.', '. $step .', \'#takhsis\', \''.$request->token.'\');">' . $step . '</a></li>';

                                $i = 7;
                            } else {
                                if($i == 1){
                                    $result .= '<li class="page-item"><a class="page-link" href="#" onclick="productServicePage('.$request->userId.', 1, \'#takhsis\', \''.$request->token.'\');">1</a></li>';
                                    $result .= '<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>';
                                } else if($i == 7){
                                    if ($request->page == $step) {
                                        $result .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $step . '</span></li>';
                                    } else {
                                        $result .= '<li class="page-item"><a class="page-link" href="#" onclick="productServicePage('.$request->userId.', '. $step .', \'#takhsis\', \''.$request->token.'\');">' . $step . '</a></li>';
                                    }
                                } else {
                                    $page = $step - (7 - $i);
                                    if($request->page == ($step - (7 - $i))){
                                        $result .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $page . '</span></li>';
                                    } else {
                                        $result .= '<li class="page-item"><a class="page-link" href="#" onclick="productServicePage('.$request->userId.', '. $page .', \'#takhsis\', \''.$request->token.'\');">' . $page . '</a></li>';
                                    }
                                }
                            }
                        }
                    }
                }

                $page = $request->page+1;
                if (($step) == ($request->page)) {
                    $result .= '<li class="page-item disabled" aria-disabled="true" aria-label="بعدی »"><span class="page-link" aria-hidden="true">›</span></li>';
                } else {
                    $result .= '<li class="page-item">
                                    <a class="page-link" href="#" onclick="productServicePage('.$request->userId.', '. $page .', \'#takhsis\', \''.$request->token.'\');" rel="next" aria-label="بعدی »">›</a>
                                </li>';
                }
                $result .= '</ul></nav>';

                $result .= '</div></div>';

                $result .= '<div class="row" style="margin-top: 15px">
                                            <div class="col-md-3">
                                                <button type="submit" id="productServicePage" name="productServicePage" value="productServicePage" 
                                                style="display: block; width: 100%" class="btn btn-primary" onclick="$(\'#startSpinner\').click();">اعمال تغییرات</button>
                                            </div>
                                        </div>';

                $result .= '</div></form></div>';

            }
        }

        return $result;
    }

    public function saveArticle(Request $request){
        $article = ArticleProvider::where('article_id', '=', $request->id)->where('user_id', '=', $request->userId)->first();
        if($article == null){
            ArticleProvider::create([
                'article_id' => trim($request->id),
                'price' => trim($request->prise),
                'status' => trim($request->persend),
                'atless' => trim($request->atless),
                'user_id' => $request->userId,
                'active' => 1,
                'accept' => trim("منتظر تایید"),
                'timestamp' => time(),
            ]);
        } else {
            $article->price = $request->prise;
            $article->status = $request->persend;
            $article->atless = $request->atless;

            $article->save();
        }
    }

    public function saveService(Request $request){
        $service = ServiceProvider::where('service_id', '=', $request->id)->where('user_id', '=', $request->userId)->first();
        if($service == null){
            ServiceProvider::create([
                'service_id' => trim($request->id),
                'price' => trim($request->prise),
                'status' => trim($request->persend),
                'atless' => trim($request->atless),
                'user_id' => $request->userId,
                'active' => 1,
                'accept' => trim("منتظر تایید"),
                'timestamp' => time(),
            ]);
        } else {
            $service->price = $request->prise;
            $service->status = $request->persend;
            $service->atless = $request->atless;

            $service->save();
        }
    }

    public function covered(Request $request)
    {
        $code = $request->stateId;
        $cites = City::where('state_id', '=', $code)->get();
        $result = "";

        $result .= '<div class="card" style="width: 100%;">
                    <div class="card-body">
                    <div class="row"><div class="pretty p-default">
                    <input type="checkbox" onchange="selectAll(this.checked); saveCovered('.$code.', 0, 0, '.$request->userId.', \''.$request->token.'\')">
                    <div class="state p-primary">
                      <label>کل استان</label>
                    </div>
                  </div></div><div class="row" style="margin-top: 15px;">';

        foreach ($cites as $city){
            $districts = District::where('city_id', '=', $city->id)->get();

            $arr = '[';
            foreach ($districts as $district){
                $arr .= $district->id.',';
            }
            $arr = substr($arr, 0, -1);
            $arr .= ']';

            $result .= '<div class="card" style="width: 100%;">
                    <div class="card-body">
                    <div class="row"><div class="pretty p-default">
                    <input type="checkbox" name="asc" id="'.$city->id.'" onchange="selectAllCity('.$arr.', this.checked);saveCovered('.$code.', '.$city->id.', 0, '.$request->userId.', \''.$request->token.'\')">
                    <div class="state p-primary">
                      <label>کل '. $city->title .'</label>
                    </div>
                  </div></div><div class="row" style="margin-top: 15px;">';

            foreach ($districts as $district){
                $result .= '<div class="col-md-2"><div class="pretty p-default">
                    <input type="checkbox" name="asc" id="'.$district->id.'" onchange="saveCovered('.$code.', '.$city->id.', '.$district->id.', '.$request->userId.', \''.$request->token.'\')">
                    <div class="state p-primary">
                      <label>'. $district->title .'</label>
                    </div>
                  </div>
                  </div>';
            }
            $result .= '</div></div></div>';
        }
        return $result;
    }

    public function saveCovered(Request $request){
        $locations = Location::where('user_id', '=', $request->userId)->get();
        foreach ($locations as $location){
            if($location->state->id != $request->stateId){
                $location->delete();
            }
        }

        if($request->cityId == 0 && $request->districtId == 0){
            foreach ($locations as $location){
                $location->delete();
            }
            $allState = District::where('state_id', '=', $request->stateId)->get();
            foreach ($allState as $state){
                Location::create([
                    'user_id' => trim($request->userId),
                    'state_id' => $state->state->id,
                    'city_id' => $state->city->id,
                    'district_id' => $state->id,
                ]);
            }
        } else if($request->cityId != 0 && $request->districtId == 0){
            foreach ($locations as $location){
                if($location->city->id == $request->cityId){
                    $location->delete();
                }
            }
            $allCity = District::where('city_id', '=', $request->cityId)->get();
            foreach ($allCity as $city){
                Location::create([
                    'user_id' => trim($request->userId),
                    'state_id' => $city->state->id,
                    'city_id' => $city->city->id,
                    'district_id' => $city->id,
                ]);
            }
        } else {
            $location = Location::where('district_id', '=', $request->districtId)->first();
            if($location != null){
                $location->delete();
            } else {
                Location::create([
                    'user_id' => trim($request->userId),
                    'state_id' => $request->stateId,
                    'city_id' => $request->cityId,
                    'district_id' => $request->districtId,
                ]);
            }
        }
    }

    public function deleteArticle(Request $request){
        $article = ArticleProvider::find($request->id);
        $article->delete();
    }

    public function deleteService(Request $request){
        $service = ServiceProvider::find($request->id);
        $service->delete();
    }
}