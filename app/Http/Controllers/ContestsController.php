<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HomeController;

class ContestsController extends Controller{

    public function __construct(){
        $this->middleware('auth',['only'=>['submit']]);
    }

    public function index(){
        $user = HomeController::getuserprofile();
        $arr = array();
        $arr['profile'] = $user[0];
        return view('contests',$arr);
    }

    public function upcoming(){
        $from = 0;
        $limit = 15;
        $now = date('Y-m-d H:i:s');
        $contests = DB::table('contests')->where([['ContestEnd','>',$now]])->join('users','users.id','=','contests.Creator')->offset($from)->limit($limit)->get()->toArray();
        return json_encode($contests);
    }

    public function history(){
        $from = 0;
        $limit = 15;
        $now = date('Y-m-d H:i:s');
        $contests = DB::table('contests')->where([['ContestEnd','<',$now]])->join('users','users.id','=','contests.Creator')->offset($from)->limit($limit)->get()->toArray();
        return json_encode($contests);
    }

    static private function getUserCorrect($ContestID=null){
        $userStatus = array();
        if (!isset(Auth::user()->id))
            return $userStatus;

        $UID = Auth::user()->id;
        if ($ContestID != null){
            $userStatus = DB::table('submissions')->select('ProblemID','Status')->distinct()->where([['UID',$UID],['ContestID',$ContestID],['Status',1]])->get()->toArray();
            return $userStatus;
        }
        else{

        }
    }
    
    public function viewContest($ContestID){
        $arr = array();
        $contest = array();

        $user = HomeController::getuserprofile();
        $arr['profile'] = $user[0];

        $contest = DB::table('contests')->where('ContestID','=',$ContestID)->get()->first();
        $arr['contest'] = $contest;

        $UID = isset(Auth::user()->id) ? Auth::user()->id : null;

        $userStatus = self::getUserCorrect($ContestID);
// return $userStatus;
        $problems = DB::table('problems')->where([['problems.ContestID','=',$ContestID]])->get();
        foreach($problems as $key=>$problem){
            foreach($userStatus as $key=>$status){
                if ($status->ProblemID === $problem->ProblemID){
                    $problem->status = 1;
                }
            }
        }
        
        $arr['problems'] = $problems;

        return view('viewcontest',$arr);
    }

    public function viewProblem($ContestID = null,$ProblemID){
        $arr = array();
        $contest = array();

        $user = HomeController::getuserprofile();
        $arr['profile'] = $user[0];

        $problem = DB::table('problems')->where('ProblemID','=',$ProblemID)->get()->first();
        $arr['problem'] = $problem;

        $contest = DB::table('contests')->where('ContestID','=',$ContestID)->get()->first();
        $arr['contest'] = $contest;

        if ($problem==null || $contest==null)
            return redirect('/404');

        return view('viewproblem',$arr);
    }

    public function submit(Request $req, $ContestID = null , $ProblemID){
        if (!isset($req->Answer) || !isset(Auth::user()->id)) 
            return redirect('/login');

        $UserAnswer = $req->Answer;
        $Problem = DB::table('problems')->where('ProblemID','=',$ProblemID)->get()->first();
        if ($Problem->StartAt)
        if ($ContestID == null){
            $ContestID = $Problem->ContestID;
        }
        
        $correct = $UserAnswer === $Problem->Answer;
        DB::table('submissions')->insert(['UID'=>Auth::user()->id,'ContestID'=>$ContestID,'ProblemID'=>$ProblemID,'Output'=>$UserAnswer,'Status'=>$correct]);
        $subCount = DB::table('submissions')->where([['UID',Auth::user()->id],['ProblemID',$ProblemID]])->count();

        if ($correct)
            return redirect()->back()->with(array('success'=>'Correct','sub'=>$subCount));
        return redirect()->back()->with(array('error'=>'Wrong Answer','sub'=>$subCount));
    }
}