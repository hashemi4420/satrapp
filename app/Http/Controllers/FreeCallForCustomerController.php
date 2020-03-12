<?php

namespace App\Http\Controllers;

use App\FreeCallCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPExcel_IOFactory;

class FreeCallForCustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $customers = FreeCallCustomer::where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->paginate(20);
        return view('manage.pages.freeCallForCustomer', compact('customers'));
    }

    public function save(Request $request)
    {
        if($request->name != ''){
            $number = $this->checkNumber($request->name);
            if($number != null){
                $this->saveNumber($number);
            }
        }

        if($request->name1 != ''){
            $number = $this->checkNumber($request->name1);
            if($number != null){
                $this->saveNumber($number);
            }
        }

        if($request->name2 != ''){
            $number = $this->checkNumber($request->name2);
            if($number != null){
                $this->saveNumber($number);
            }
        }

        if($request->name3 != ''){
            $number = $this->checkNumber($request->name3);
            if($number != null){
                $this->saveNumber($number);
            }
        }

        if($request->name4 != ''){
            $number = $this->checkNumber($request->name4);
            if($number != null){
                $this->saveNumber($number);
            }
        }

        if($request->name5 != ''){
            $number = $this->checkNumber($request->name5);
            if($number != null){
                $this->saveNumber($number);
            }
        }

        if($request->name6 != ''){
            $number = $this->checkNumber($request->name6);
            if($number != null){
                $this->saveNumber($number);
            }
        }

        if($request->name7 != ''){
            $number = $this->checkNumber($request->name7);
            if($number != null){
                $this->saveNumber($number);
            }
        }

        if($request->name8 != ''){
            $number = $this->checkNumber($request->name8);
            if($number != null){
                $this->saveNumber($number);
            }
        }

        if($request->name9 != ''){
            $number = $this->checkNumber($request->name9);
            if($number != null){
                $this->saveNumber($number);
            }
        }

        $file = null;
        if ($request->excel != "" && $request->excel != null) {
            $excel = $request->file('excel');
            if($excel != null){
                include_once(app_path()."/Classes/PHPExcel/IOFactory.php");

                $name = time() . Auth::user()->phone . '.' . $excel->getClientOriginalExtension();
                $destination = '/images/upload';
                $path = (new FileController)->save($destination, $name, $excel);

                $objPHPExcel = PHPExcel_IOFactory::load($path);

                $arr = array();
                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet){
                    $highestRow = $worksheet->getHighestRow();
                    for ($row = 1; $row <= $highestRow; $row++){
                        $number = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $number = $this->checkNumber($number);
                        if($number != null){
                            if($number != null){
                                $this->saveNumber($number);
                            }
                        }
                    }
                }
            }

        }

        $customers = FreeCallCustomer::where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->paginate(20);
        return view('manage.pages.freeCallForCustomer', compact('customers'));
    }

    private function checkNumber($number){
        $result = null;
        if(is_numeric($number)){
            if(strlen($number) == 11){
                $result = strval($number);
            } else if(strlen($number) == 10){
                $result = '0'.strval($number);
            } else if(substr(strval($number), 0, 3) == "+98"){
                $number = str_replace("+98", "0", $number);
                if(strlen($number) == 11){
                    $result = strval($number);
                }
            }
        } else if(substr(strval($number), 0, 3) == "+98"){
            $number = str_replace("+98", "0", $number);
            if(strlen($number) == 11){
                $result = strval($number);
            }
        }

        return $result;
    }

    private function saveNumber($number){

        if(FreeCallCustomer::where('user_id', '=', Auth::user()->id)->where('phone', '=', $number)->count() == 0){
            FreeCallCustomer::create([
                'user_id' => Auth::user()->id,
                'phone' => $number,
                'active' => 1,
            ]);
        }
    }

    public function delete(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['delete_28'] == "on") {
            $number = FreeCallCustomer::find($request->id);

            $number->delete();

            (new LogHistoryController)->logSave(Auth::user()->id, 28, false, false, false, false, true, false);
        } else {
            abort(404);
        }
    }

    public function active(Request $request){
        $accessLevel = Auth::user()->usrRole;
        $accessList = json_decode($accessLevel->json, true);
        if($accessList['update_28'] == "on") {
            $number = FreeCallCustomer::find($request->id);

            if ($number->active) {
                $active = 0;
            } else {
                $active = 1;
            }

            $number->active = $active;

            $number->save();

            (new LogHistoryController)->logSave(Auth::user()->id, 28, false, false, true, false, false, false);
        } else {
            abort(404);
        }
    }
}
