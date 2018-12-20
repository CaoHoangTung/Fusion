<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arr = array();

        $user = self::getuserprofile()[0];
        if (isset($user->id)){
            if ($user->Avatar == null)
                $user->Avatar = "/images/avatar.png";

            $arr['profile'] = $user;
        }
        return view('home',$arr);
    }

    public function nextcontests(){
        $take = 2;
        $now = date('Y-m-d H:i:s');
        $contests = DB::table('contests')->where([['ContestEnd','>',$now]])->take($take)->get()->toArray();
        foreach($contests as $key=>$contest){
            $contest->TimeLeft = strtotime($contest->ContestBegin) - strtotime($now);
        }
        return json_encode($contests);
    }

    public function topusers(){
        $take = 10;
        $users = DB::table('users')->orderBy('Rating','desc')->take($take)->get();
        return json_encode($users);
    }

    public function posts(){
        $take = 10;
        $posts = DB::table('posts')->where('Type','announcement')->join('users','posts.Creator','=','users.id')->orderBy('CreateDate','desc')->take($take)->get();
        
        return json_encode($posts);
    }

    static public function getuserprofile(){
        if (!isset(Auth::user()->id))
            return null;
        $user = DB::table('users')->where('id','=',Auth::user()->id)->get()->toArray();
        return $user;
    }
}
