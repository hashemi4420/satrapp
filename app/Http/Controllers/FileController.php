<?php

namespace App\Http\Controllers;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function save($Path, $name, $image){
        $destinationPath = public_path($Path);
        $image->move($destinationPath, $name);
        return $destinationPath.'/'.$name;
    }

    public function delete($image){
        if (file_exists(public_path($image))) {
            unlink(public_path($image));
        }
    }

//    public function save($Path, $name, $image){
//        $destinationPath = public_path('/../../public_html/'.$Path);
//        $image->move($destinationPath, $name);
//        return $destinationPath.'/'.$name;
//    }
//
//    public function delete($image){
//        if (file_exists(public_path('/../../public_html'.$image))) {
//            unlink(public_path('/../../public_html'.$image));
//        }
//    }
}
