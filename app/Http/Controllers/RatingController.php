<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class RatingController extends Controller{
    public function __construct(){
        $this->middleware('admin');
    }

    static private function getUserFromID($UserID){
        return DB::table('users')->where('id',$UserID)->get()->first();
    }

    static private $k ; // base number used in elo calculation
    // Ra , Rb : points
    // Ea = Qa / (Qa + Qb)
    // Eb = Qb / (Qa + Qb)
    // Qa = 10^(Ra/400)
    // Ra' = Ra + k(Aa - Ea)
    // Aa: real point ; Ea: expected point

    static public function rateContest(Request $req){
        $now = Date('Y-m-d H:i:s');

        $ContestID = $req->ContestID;
        $contest = DB::table('contests')->where('ContestID',$ContestID)->get()->first();
        if (!isset($contest)){
            return "No contest";
        }

        $totalUser = DB::table('submissions')->where([['ContestID',$ContestID]])->groupBy('UID')->get()->count();
        $maxPointEachProblem = $totalUser+1;
        
        $problems = DB::table('problems')->where('ContestID',$ContestID)->get()->toArray();
        $problemPool = array();

        foreach($problems as $key=>$problem){
            $problemPool[$problem->ProblemID] = 0;
        }
        
        $users = DB::table('submissions')
                ->where('ContestID',$ContestID)
                ->join('users','users.id','=','submissions.UID')
                ->groupBy('UID')
                ->distinct()
                ->get()
                ->toArray();
        $userPool = array();
        
        if (!isset($users)) 
            return "No users";

        foreach($users as $key=>$user){
            $userPool[$user->UID] = 0;
        }

        $Submissions = DB::table('submissions')
                    // ->join('contests','contests.ContestID','=','submissions.ContestID')
                    ->where([['submissions.SubmitAt','<',$contest->ContestEnd],['submissions.ContestID',$ContestID],['submissions.Status',1],['submissions.Judged',0]])
                    ->orderBy('submissions.SubmitAt')->groupBy('submissions.ProblemID','submissions.UID')
                    ->join('users','users.id','=','submissions.UID')
                    ->get()
                    ->toArray();
        foreach($Submissions as $key=>$submission){
            Log::info($submission->SID);
        }
        // return $Submissions;

        if (sizeof($Submissions) == 0)
            return "No submissions";

        $userPoint = array();
        $userActualPoint = array();
        $userRating = array();
        $userNewRating = array();

        foreach($users as $key=>$user){
            $userRating[$user->UID] = $user->Rating;
            $userPool[$user->UID] = 0;
        }

        foreach($Submissions as $key=>$submission){
            
            $pointGained = ($maxPointEachProblem - $problemPool[$submission->ProblemID]);
            $problemPool[$submission->ProblemID]++;
            $userPool[$submission->UID] += $pointGained;
            // $userRating[$submission->UID] = $submission->Rating;
        }
        
        arsort($userPool);

        if (sizeof($userPool) == 0)
            return "User Pool empty";

        foreach($userPool as $key1=>$user1){
            $userExpectedPoint[$key1] = 0;
            $userActualPoint[$key1] = 0;

            foreach($userPool as $key2=>$user2){
                if ($key1 === $key2)
                    continue;
                $userExpectedPoint[$key1] += 1 / ( 1+10**(($userRating[$key2]-$userRating[$key1])/400) );
                if ($userPool[$key1] > $userPool[$key2])
                    $userActualPoint[$key1] += 1;
                else if ($userPool[$key1] == $userPool[$key2])
                    $userActualPoint[$key1] += 0.5;
            }
        }
        foreach($userPool as $key=>$user){
            if ($userRating[$key] < 1600)
                self::$k = 25;
            else if ($userRating[$key] < 2000)
                self::$k = 20;
            else if ($userRating[$key] < 2400)
                self::$k = 15;
            else if ($userRating[$key] < 1600)
                self::$k = 10;
            
            $userNewRating[$key] = (int) ($userRating[$key] + self::$k*($userActualPoint[$key]-$userExpectedPoint[$key]));
        }

        // add to hall of fame
        $name1 = self::getUserFromID(array_keys($userPool)[0])->name;
        $name2 = (isset(array_keys($userPool)[1])) ? self::getUserFromID(array_keys($userPool)[1])->name:"N/A";
        $name3 = (isset(array_keys($userPool)[2])) ? self::getUserFromID(array_keys($userPool)[2])->name:"N/A";

        DB::table('halloffame')->insert(['ContestID'=>$ContestID,
                                        'First'=>(isset(array_keys($userPool)[0]))?array_keys($userPool)[0]:null,
                                        'Second'=>(isset(array_keys($userPool)[1]))?array_keys($userPool)[1]:null,
                                        'Third'=>(isset(array_keys($userPool)[2]))?array_keys($userPool)[2]:null,
                                        'FirstName'=>$name1,
                                        'SecondName'=>$name2,
                                        'ThirdName'=>$name3]);

        // update user new rating
        foreach($userNewRating as $key=>$newRating){
            DB::table('users')->where('id',$key)->update(['Rating'=>$newRating]);
        }
        DB::table('submissions')->where([['ContestID',$ContestID]])->update(['Judged'=>1]);

        // return array($userActualPoint,$userExpectedPoint,$userRating,$userNewRating);
        return 1;
    }


}