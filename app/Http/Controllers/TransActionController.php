<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransActionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('manage.pages.transAction');
    }
}
