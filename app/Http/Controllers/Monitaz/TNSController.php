<?php

namespace App\Http\Controllers\Monitaz;

use App\Http\Controllers\Controller;

class TNSController extends Controller
{
    public function dayIndex()
    {
        return view('monitaz.tns.day-type');
    }

    public function weekIndex()
    {
        return view('monitaz.tns.week-type');
    }
}
