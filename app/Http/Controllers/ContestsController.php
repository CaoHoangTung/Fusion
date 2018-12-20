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

        $now = date('Y-m-d H:i:s');
        $arr['Countdown'] = max(0,strtotime($contest->ContestEnd) - strtotime($now));

        $now = date('Y-m-d H:i:s');
        if ($now < $contest->ContestBegin){
            $arr['ContestBegin'] = $contest->ContestBegin;
            $arr['TimeLeft'] = strtotime($contest->ContestBegin)-strtotime($now);
            // return $now." <br>".$contest->ContestBegin." <Br>".$arr['TimeLeft'];
            return view('contestnotopen',$arr);
        }
        else{
            $UID = isset(Auth::user()->id) ? Auth::user()->id : null;

            $userStatus = self::getUserCorrect($ContestID);

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
    }

    public function viewProblem($ContestID = null,$ProblemID){
        $arr = array();
        $contest = array();

        $user = HomeController::getuserprofile();
        $arr['profile'] = $user[0];

        $contest = DB::table('contests')->where('ContestID','=',$ContestID)->get()->first();
        $arr['contest'] = $contest;

        $now = Date('Y-m-d H:i:s');
        if ($now < $contest->ContestBegin){
            $arr['ContestBegin'] = $contest->ContestBegin;
            $arr['TimeLeft'] = Date($contest->ContestBegin)-Date($now);
            return view('contestnotopen',$arr);
        }
        else{
            $problem = DB::table('problems')->where('ProblemID','=',$ProblemID)->get()->first();
            $arr['problem'] = $problem;

            if ($problem==null || $contest==null)
                return redirect('/404');

            return view('viewproblem',$arr);
        }
    }

    static private function contestHasNotOpen($ContestID){
        $now = date('Y-m-d H:i:s');
        $contest = DB::table('contests')->where('ContestID',$ContestID)->get()->first();
        $Begin = $contest->ContestBegin;
        return $now < $Begin;
    }

    static private function contestIsOver($ContestID){
        $now = date('Y-m-d H:i:s');
        $contest = DB::table('contests')->where('ContestID',$ContestID)->get()->first();
        $End = $contest->ContestEnd;
        return $now > $End;
    }

    public function submit(Request $req, $ContestID = null , $ProblemID){
        if (!isset($req->Answer) || !isset(Auth::user()->id)) 
            return redirect('/login');

        $UserAnswer = $req->Answer;
        $Problem = DB::table('problems')->where('ProblemID','=',$ProblemID)->get()->first();

        if ($ContestID == null){
            $ContestID = $Problem->ContestID;
        }

        if (self::contestHasNotOpen($ContestID))
            return redirect()->back();
        
        $correct = $UserAnswer === $Problem->Answer;
        $due = self::contestIsOver($ContestID);

        DB::table('submissions')->insert(['UID'=>Auth::user()->id,'ContestID'=>$ContestID,'ProblemID'=>$ProblemID,'Output'=>$UserAnswer,'Status'=>$correct,'Judged'=>$due]);
        $subCount = DB::table('submissions')->where([['UID',Auth::user()->id],['ProblemID',$ProblemID]])->count();

        if ($correct)
            return redirect()->back()->with(array('success'=>'Correct','sub'=>$subCount));
        return redirect()->back()->with(array('error'=>'Wrong Answer','sub'=>$subCount));
    }
}