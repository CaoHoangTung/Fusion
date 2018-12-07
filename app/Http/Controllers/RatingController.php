<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller{
    public function __construct(){
        $this->middleware('admin');
    }

    static public function rateContest(Request $req){
        $ContestID = $req->ContestID;

        $totalUser = DB::table('submissions')->where([['ContestID',$ContestID]])->groupBy('UID')->get()->count();
        $problems = DB::table('problems')->where('ContestID',$ContestID)->get()->toArray();
        $problemPool = array();
        foreach($problems as $key=>$problem){
            $problemPool[$problem->ProblemID] = 0;
        }
        
        $users = DB::table('submissions')->select('UID')->where('ContestID',$ContestID)->groupBy('UID')->distinct()->get()->toArray();
        $userPool = array();
        
        foreach($users as $key=>$user){
            $userPool[$user->UID] = 0;
        }
        
        $maxPointEachProblem = $totalUser+1;

        $Submissions = DB::table('submissions')->where([['ContestID',$ContestID],['Status',1],['Judged',0]])->orderBy('SubmitAt')->groupBy('ProblemID','UID')->get()->toArray();
        foreach($Submissions as $key=>$submission){
            $pointGained = $maxPointEachProblem - $problemPool[$submission->ProblemID];
            $problemPool[$submission->ProblemID]++;
            $userPool[$submission->UID] += $pointGained;
        }
        arsort($userPool);
        return var_dump($userPool);
    }


}