<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\User;

class RankingController extends Controller{
    static private $arr;
    static public $resultPerPage = 20;
    public function __construct(){

    }

    public function index(){
        $user = HomeController::getuserprofile();
        $arr = array();
        $arr['profile'] = $user[0];

        $arr['perpage'] = self::$resultPerPage;

        $users = DB::table('users')->orderBy('Rating','desc')->Paginate(self::$resultPerPage);
        $arr['users'] = $users;

        $halloffame = DB::table('halloffame')->orderBy('halloffame.ContestID','desc')->join('contests','halloffame.ContestID','=','contests.ContestID')->Paginate(self::$resultPerPage);
        $arr['hof'] = $halloffame;
        return view('ranking',$arr);
    }
}