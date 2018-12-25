<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimeController extends Controller{

    public function NOW(){
        $now = date('Y-m-d H:i:s');
        return $now;
    }
}
