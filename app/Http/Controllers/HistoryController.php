<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryController extends Controller
{
    function show()
    {
        return view('api.index')
    }
}
