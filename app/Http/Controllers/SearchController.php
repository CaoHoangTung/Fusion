<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

class SearchController extends Controller{

    public function __construct(){
        // $this->middleware('auth');
    }

    public function query(Request $req){
        $arr = array();
        $user = HomeController::getuserprofile()[0];
        $arr['profile'] = $user;

        $q = (string)$req->query('query');

        $arr['query'] = $q;
        
        if (!isset($req->contest) && !isset($req->problem) && !isset($req->post) && !isset($req->user)){
            $req->contest = 'on';
            $req->problem = 'on';
            $req->post = 'on';
            $req->user = 'on';
        }

        $settings = array();
        if (isset($req->contest) && $req->contest == 'on'){
            $settings['contest'] = 1;
            $arr['contests'] = DB::table('contests')
                            ->where('ContestID','like',$q.'%')
                            ->orWhere('ContestName','like',$q.'%')
                            ->get();
        }

        if (isset($req->problem) && $req->problem == 'on'){
            $now = Date('Y-m-d H:i:s');
            $settings['problem'] = 1;
 
            $arr['problems'] = DB::table('problems')
                            ->join('contests','contests.ContestID','=','problems.ContestID')
                            ->where([['ProblemID','like',$q.'%'],['contests.ContestBegin','<',$now]])
                            ->orWhere([['QuestionName','like',$q.'%'],['contests.ContestBegin','<',$now]])
                            ->get();
        }

        if (isset($req->post) && $req->post == 'on'){
            $settings['post'] = 1;
            $arr['posts'] = DB::table('posts')
                            ->where('PostID','like',$q.'%')
                            ->orWhere('Header','like',$q.'%')
                            ->get();
        }

        if (isset($req->user) && $req->user == 'on'){
            $settings['user'] = 1;
            $arr['users'] = DB::table('users')
                            ->where('name','like',$q.'%')
                            ->orWhere('email','like',$q.'%')
                            ->get();
        }
        
        $arr['settings'] = $settings;
        // return $arr;
        return view('search',$arr);
    }
    
}