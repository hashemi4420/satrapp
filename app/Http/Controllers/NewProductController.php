<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('manage.pages.newProduct');
    }
}
