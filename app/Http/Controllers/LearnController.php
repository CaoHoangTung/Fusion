<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

class LearnController extends Controller{
    static private $resultPerPage = 30;
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        
    }

    public function lectures(){
        $arr = array();
        $arr['profile'] = HomeController::getuserprofile()[0];

        $lectures = DB::table('posts')
                    ->where('Type','lecture')
                    ->join('users','users.id','=','posts.Creator')
                    ->Paginate();
        $arr['lectures'] = $lectures;

        return view('lectures',$arr);
    }

    public function archived(){
        $UserID = Auth::user()->id;
        $arr = array();
        $arr['profile'] = HomeController::getuserprofile()[0];

        $userSubmissions = DB::table('submissions')->where([['UID',$UserID],['Status',1]])->groupBy('ProblemID');

        $now = Date('Y-m-d H:i:s');
        $problems = DB::table('problems')
                    ->where('ContestBegin','<',$now)
                    ->join('contests',[['contests.ContestID','=','problems.ContestID']])
                    ->Paginate(self::$resultPerPage);
        
        foreach ($problems as $key=>$problem){
            $problemDone = DB::table('submissions')
                            ->where([['UID',$UserID],['ProblemID',$problem->ProblemID],['Status',1]])
                            ->exists();
            if ($problemDone)
                $problem->status = 1;
        }       

        // return $problems;
        $arr['problems'] = $problems;

        return view('archivedProblems',$arr);
    }
}