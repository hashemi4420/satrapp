<?php

namespace App\Http\Controllers;

use App\AccessLevel;
use App\ArticleBrand;
use App\BusinessNumber;
use App\Http\Requests\UserRequest;
use App\ServiceBrand;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $accessLevel;
    private $accessList;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $this->accessLevel = Auth::user()->usrRole;
        $this->accessList = json_decode($this->accessLevel->json, true);
        if($this->accessList['read_1'] == "on"){
            $users = User::where('userRole', '!=', '3')->orderBy('id', 'DESC')->paginate(20);
            $articleBrands = ArticleBrand::all();
            $serviceBrands = ServiceBrand::all();
            $accessLevels = AccessLevel::where('id', '!=', '3')->get();

            (new LogHistoryController)->logSave(Auth::user()->id, 1, true, false, false, false, false, false);

            return view('manage.pages.users', compact('users','articleBrands', 'serviceBrands', 'accessLevels'));
        } else {
            abort(404);
        }
    }

    public function save(UserRequest $request)
    {
        $this->accessLevel = Auth::user()->usrRole;
        $this->accessList = json_decode($this->accessLevel->json, true);
        if($this->accessList['insert_1'] == "on") {

            $avatar = null;
            if ($request->avatar != "") {
                $image = $request->file('avatar');
                $name = time() . $request['phone'] . '.' . $image->getClientOriginalExtension();

                $destination = '/images/avatar';
                (new FileController)->save($destination, $name, $image);

                $avatar = trim($destination.'/'.$name);
            }

            $user = User::create([
                'phone' => str_replace(" ", "", trim($request['phone'])),
                'email' => trim($request['email']),
                'name' => trim($request['name']),
                'family' => trim($request['family']),
                'active' => 1,
                'timestamp' => time(),
                'password' => Hash::make(trim($request['password'])),
                'userRole' => trim($request['userRole']),
                'email_verify_code' => rand(1000, 9999),
                'phone_verify_code' => rand(1000, 9999),
                'articleBrand_id' => $request['articleBrand_id'],
                'serviceBrand_id' => $request['serviceBrand_id'],
                'url_avatar' => $avatar,
                'news' => 0,
                'rools' => 1,
            ]);

            $number = $this->createBusinessNumber();

            BusinessNumber::create([
                'user_id' => $user->id,
                'number' => $number,
                'timestamp' => time(),
            ]);

            $users = User::where('userRole', '!=', '3')->orderBy('id', 'DESC')->paginate(20);
            $articleBrands = ArticleBrand::all();
            $serviceBrands = ServiceBrand::all();
            $accessLevels = AccessLevel::all();

            (new LogHistoryController)->logSave(Auth::user()->id, 1, false, false, false, true, false, false);

            return view('manage.pages.users', compact('users', 'articleBrands', 'serviceBrands', 'accessLevels'));
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
        if($this->accessList['read_1'] == "on") {
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
                $users = User::where('email', 'like', '%' . $email . '%')->where('phone', 'like', '%' . str_replace(" ", "", $phone) . '%')->where('userRole', '!=', '3')->get();
            } else if ($email != null && $phone == null) {
                $users = User::where('email', 'like', '%' . $email . '%')->where('userRole', '!=', '3')->get();
            } else if ($phone != null && $email == null) {
                $users = User::where('phone', 'like', '%' . str_replace(" ", "", $phone) . '%')->where('userRole', '!=', '3')->get();
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
                            <td>' . $user->type . '</td>';
                    if ($user->articleBrand_id != null) {
                        $result .= '<td>' . $user->articleBrand->title . '</td>';
                    } else {
                        $result .= '<td style="color: red">ندارد</td>';
                    }

                    if ($user->serviceBrand_id != null) {
                        $result .= '<td>' . $user->serviceBrand->title . '</td>';
                    } else {
                        $result .= '<td style="color: red">ندارد</td>';
                    }
                    if($this->accessList['update_1'] == "on") {
                        $result .= '<td>
                            <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                    onclick="startUpdateUser(' . $user->id . ', document.getElementById(\'token\').value, \'#userId\', \'#name\', \'#family\', \'#phone\', \'#email\', \'#password\', \'#articleBrand_id\', \'#serviceBrand_id\')">
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
                                        <i class=" icon-shield2"></i>
                                    </button>
                               </td>';
                    }
                    $result .= '</tr>';
                }
            }

            (new LogHistoryController)->logSave(Auth::user()->id, 1, false, true, false, false, false, false);

            return $result;
        } else {
            abort(404);
        }
    }

    public function startUpdate(Request $request){
        $user = User::find($request->id);

        $result = $user->id . "_:_" . $user->name . "_:_" . $user->family . "_:_" . $user->phone
            . "_:_" . $user->email . "_:_" . $user->password . "_:_" . $user->articleBrand_id . "_:_" . $user->serviceBrand_id . "_:_" . $user->userRole . "_:_" . $user->url_avatar;

        return $result;
    }

    public function update(Request $request){
        $this->accessLevel = Auth::user()->usrRole;
        $this->accessList = json_decode($this->accessLevel->json, true);
        if($this->accessList['update_1'] == "on") {
            $user = User::find($request->id);

            $user->name = $request->name;
            $user->family = $request->family;
            $user->articleBrand_id = $request->articleBrand;
            $user->serviceBrand_id = $request->serviceBrand;
            $user->userRole = $request->userRole;

            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $name = time() . Auth::user()->phone . '.' . $image->getClientOriginalExtension();
                $destination = '/images/avatar';

                (new FileController)->save($destination, $name, $image);

                (new FileController)->delete($user->url_avatar);

                $user->url_avatar = trim($destination.'/'.$name);
            }

            $user->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 1, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function active(Request $request){
        $this->accessLevel = Auth::user()->usrRole;
        $this->accessList = json_decode($this->accessLevel->json, true);
        if($this->accessList['update_1'] == "on") {
            $user = User::find($request->id);

            if ($user->active) {
                $active = 0;
            } else {
                $active = 1;
            }

            $user->active = $active;

            $user->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 1, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

    public function changePass(Request $request){
        $this->accessLevel = Auth::user()->usrRole;
        $this->accessList = json_decode($this->accessLevel->json, true);
        if($this->accessList['update_1'] == "on") {
            $user = User::find($request->id);

            $user->password = Hash::make(trim(implode($request->password)));

            $user->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 1, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }

}