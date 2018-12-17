<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

class PostController extends Controller{
    static private $arr = array();
    public function __construct(){
        $this->middleware('auth');
        
    }

    public function index(){
        
    }

    public function viewPost(Request $req, $PostID = null){
        $user = HomeController::getuserprofile();
        self::$arr['profile'] = $user[0];

        $post = DB::table('posts')->where('PostID',$PostID)->get()->first();
        self::$arr['post'] = $post;

        return view('blogentry',self::$arr);
    }
}