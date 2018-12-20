<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\HomeController;

class ProfileController extends Controller{

    public function __construct(){
        $this->middleware('auth');
    }

    static public function index(){
        $user = HomeController::getuserprofile()[0];
        $UserID = $user->id;
        
        if ($user->Avatar == null)
            $user->Avatar = "/images/avatar.png";

        $listProblems = DB::table('submissions')->join('problems','problems.ProblemID','=','submissions.ProblemID')->where([['submissions.UID',$UserID]])->groupBy('submissions.ProblemID')->get();

        $solvedProblems = DB::table('submissions')->join('problems','problems.ProblemID','=','submissions.ProblemID')->where([['submissions.UID',$UserID],['submissions.Status',1]])->groupBy('submissions.ProblemID')->get();
        $user->solvedProblems = $solvedProblems;
           
        $listSolved = array();
        $unsolvedProblems = array();
        foreach($solvedProblems as $key=>$problem){
            $listSolved[$problem->ProblemID] = 1;
        }
        foreach($listProblems as $key=>$problem){
            if (!isset($listSolved[$problem->ProblemID]) || $listSolved[$problem->ProblemID] != 1)
                array_push($unsolvedProblems,$problem);
        }
        $user->unsolvedProblems = $unsolvedProblems;
    
        // $solved = DB::table('submissions')->where([['UID',$UserID],['Status',1]])->groupBy('ProblemID')->get()->count();
        $user->SolveCount = $solvedProblems->count();

        $subs = DB::table('submissions')->where('UID',$UserID)->count();
        $user->SubCount = $subs;
        
        $fails = DB::table('submissions')->where([['UID',$UserID],['Status',0]])->count();
        $user->FailCount = $fails;

        $user->SuccessCount = $subs-$fails;

        $arr = array();
        $arr['profile'] = $user;
        
        return view('profile',$arr);
    }

    public function changeAvatar(Request $req){
        $img = $req->base64img;
        if (!isset(Auth::user()->id))
            return 0;
        
        $file = Input::all();

        // $image = imagecreatefromjpeg($img);
        // $image = imagescale($image , 100);
        // ob_start();
        // imagejpeg($image);
        // $contents = ob_get_contents();
        // ob_end_clean();
        // $dataUri = "data:image/jpeg;base64,".base64_encode($contents);

        $UserID = Auth::user()->id;
        if(DB::table('users')->where('id',$UserID)->update(['Avatar'=>$img]));
            return redirect()->back()->with('success','Avatar changed');
        
        return redirect()->back()->with('error','Something is wrong, your avatar has not been changed. Please try again later');
    }

    public function viewProfile($UserID){
        if ($UserID == Auth::user()->id)
            return self::index();
        
        $user = DB::table('users')->where('id',$UserID)->get()[0];

        if ($user->Avatar == null)
            $user->Avatar = "/images/avatar.png";

        $listProblems = DB::table('submissions')->join('problems','problems.ProblemID','=','submissions.ProblemID')->where([['submissions.UID',$UserID]])->groupBy('submissions.ProblemID')->get();

        $solvedProblems = DB::table('submissions')->join('problems','problems.ProblemID','=','submissions.ProblemID')->where([['submissions.UID',$UserID],['submissions.Status',1]])->groupBy('submissions.ProblemID')->get();
        $user->solvedProblems = $solvedProblems;
       
        $listSolved = array();
        $unsolvedProblems = array();
        foreach($solvedProblems as $key=>$problem){
            $listSolved[$problem->ProblemID] = 1;
        }
        foreach($listProblems as $key=>$problem){
            if (!isset($listSolved[$problem->ProblemID]) || $listSolved[$problem->ProblemID] != 1)
                array_push($unsolvedProblems,$problem);
        }
        $user->unsolvedProblems = $unsolvedProblems;

        // $solved = DB::table('submissions')->where([['UID',$UserID],['Status',1]])->groupBy('ProblemID')->get()->count();
        $user->SolveCount = $solvedProblems->count();

        $subs = DB::table('submissions')->where('UID',$UserID)->count();
        $user->SubCount = $subs;
        
        $fails = DB::table('submissions')->where([['UID',$UserID],['Status',0]])->count();
        $user->FailCount = $fails;

        $user->SuccessCount = $subs-$fails;

        $arr = array();
        $arr['profile'] = $user;
        
// return var_dump($user->solvedProblems);

        return view('viewProfile',$arr);
    }

    public function settings(){
        $arr = array();
        
        $user = HomeController::getuserprofile()[0];
        $arr['profile'] = $user;

        return view('settings',$arr);
    }

    public function changePassword(Request $req){
        $oldPwd = $req->oldpassword;        
        $true = Auth::user()->password;

        $pwdMatch = Hash::check($oldPwd,$true);
        if (!$pwdMatch)
            return redirect()->back()->with('error','Your old password is incorrect');

        $newPwd = $req->newpassword;
        if ($newPwd === $oldPwd)
            return redirect()->back()->with('error','New password must be different from old password');
        
        if (strlen($newPwd) < 8)
            return redirect()->back()->with('error','Password must have 8 or more characters');

        $UserID = Auth::user()->id;
        $newPwd = Hash::make($newPwd);
        DB::table('users')->where('id',$UserID)->update(['password'=>$newPwd]);
        return redirect()->back()->with('success','Password changed');
    }
}