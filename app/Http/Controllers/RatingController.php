<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $ContestID = $req->ContestID;

        $totalUser = DB::table('submissions')->where([['ContestID',$ContestID]])->groupBy('UID')->get()->count();
        $maxPointEachProblem = $totalUser+1;
        
        $problems = DB::table('problems')->where('ContestID',$ContestID)->get()->toArray();
        $problemPool = array();

        foreach($problems as $key=>$problem){
            $problemPool[$problem->ProblemID] = 0;
        }
        
        $users = DB::table('submissions')->select('UID')->where('ContestID',$ContestID)->groupBy('UID')->distinct()->get()->toArray();
        $userPool = array();
        
        if (!isset($users)) 
            return 0;

        foreach($users as $key=>$user){
            $userPool[$user->UID] = 0;
        }

        $Submissions = DB::table('submissions')->where([['ContestID',$ContestID],['Status',1],['Judged',0]])->orderBy('SubmitAt')->groupBy('ProblemID','UID')->join('users','users.id','=','submissions.UID')->get()->toArray();
        // return $Submissions;

        if (sizeof($Submissions) == 0)
            return 0;

        $userPoint = array();
        $userActualPoint = array();
        $userRating = array();
        $userNewRating = array();

        foreach($Submissions as $key=>$submission){
            $pointGained = ($maxPointEachProblem - $problemPool[$submission->ProblemID]);
            $problemPool[$submission->ProblemID]++;
            $userPool[$submission->UID] += $pointGained;
            $userRating[$submission->UID] = $submission->Rating;
        }
        arsort($userPool);

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
        $name2 = self::getUserFromID(array_keys($userPool)[1])->name;
        $name3 = self::getUserFromID(array_keys($userPool)[2])->name;
        
        DB::table('halloffame')->insert(['ContestID'=>$ContestID,'First'=>array_keys($userPool)[0],'Second'=>array_keys($userPool)[1],'Third'=>array_keys($userPool)[2],'FirstName'=>$name1,'SecondName'=>$name2,'ThirdName'=>$name3]);

        // update user new rating
        foreach($userNewRating as $key=>$newRating){
            DB::table('users')->where('id',$key)->update(['Rating'=>$newRating]);
        }
        DB::table('submissions')->where([['ContestID',$ContestID]])->update(['Judged'=>1]);

        // return array($userActualPoint,$userExpectedPoint,$userRating,$userNewRating);
        return 1;
    }


}