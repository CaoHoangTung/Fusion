<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller{

    public function __construct(){
        $this->middleware(['admin']);
    }

    public function index(){
        return view('admin.index');
    }

    public function newcontest(){
        $arr = array();
        $newid = DB::table('contests')->orderBy('ContestID','desc')->get()->first();

        $arr['newid'] = $newid->ContestID+1;
        return view('admin.newcontest',$arr);
    }

    public function pastcontest(){
        $arr = array();
        $pastContests = DB::table('contests')->orderBy('ContestID','desc')->take(20)->get();
        $arr['contests'] = $pastContests;
        return view('admin.pastcontest',$arr);
    }

    public function createcontest(Request $req){
        if(DB::table('contests')->insert(['ContestName'=>$req['ContestName'],'Description'=>$req['Description'],
                                        'Creator'=>Auth::user()->id,'ContestBegin'=>$req['StartAt'],
                                        'ContestEnd'=>$req['EndAt'],]))
            return redirect()->back()->with('success', 'Contest created successfully');
        return redirect()->back()->with('error', 'Error creating new contest');
    }

    public function deleteContest(Request $req, $ContestID){
        if(DB::table('contests')->where('ContestID','=',$ContestID)->delete())
            return redirect()->back()->with('success','Contest deleted');
        return redirect()->back()->with('error','Error deleting contest '.$ContestID);
    }

    public function addProblem(Request $req){
        $ContestID = $req->ContestID;
        $QuestionName = $req->QuestionName;
        $Question = $req->Question;
        $Answer = $req->Answer;
        if (DB::table('problems')->insert(['ContestID'=>$ContestID,'QuestionName'=>$QuestionName,'Question'=>$Question,'Answer'=>$Answer]))
            return redirect()->back()->with('success','Problem created');
        return redirect()->back()->with('error','Error creating problem');
    }

    public function getProblem($ProblemID){
        $problem =  DB::table('problems')->where('ProblemID','=',$ProblemID)->get()->toArray();
        return json_encode($problem[0]);
    }

    public function changeProblem(Request $req, $ProblemID){
        $QuestionName = $req->QuestionName;
        $Question = $req->Question;
        $Answer = $req->Answer;
        if (DB::table('problems')->where('ProblemID','=',$ProblemID)->update([
                            'QuestionName'=>$QuestionName,
                            'Question'=>$Question,
                            'Answer'=>$Answer]))
            return redirect()->back()->with('success','Problem updated');
    
        return redirect()->back()->with('error','Error updating problem'); 
    }

    static private function getProblems($ContestID){
        $problems;
        if ($ContestID === '*'){
            $problems = DB::table('problems')->join('contests','problems.ContestID','=','contests.ContestID')->get()->toArray();
        }
        else{
            $problems = DB::table('problems')->where('problems.ContestID','=',$ContestID)->join('contests','problems.ContestID','=','contests.ContestID')->get()->toArray();
        }
        return $problems;
    }

    public function viewProblemsByContest(Request $req, $ContestID){
        $arr = [];
        $problems = self::getProblems($ContestID);
        $arr['problems'] = $problems;
        $arr['ContestID'] = $ContestID;
        return view('admin.viewcontest',$arr);
    }

    public function deleteProblem(Request $req, $ProblemID){
        if (DB::table('problems')->where('ProblemID','=',$ProblemID)->delete())
            return redirect()->back()->with('success','Problem deleted');
        return redirect()->back()->with('error','Error deleting problem');
    }

    public function problems(){
        $arr = [];
        $problems = self::getProblems('*');
        $arr['problems'] = $problems;

        return view('admin.problems',$arr);
    }

    public function createannouncement(Request $req){
        if(DB::table('posts')->insert(['Header'=>$req['Header'],'Content'=>$req['Content'],
                                        'Creator'=>Auth::user()->id,'Type'=>'announcement']));
            return redirect()->back()->with('success', 'Announcement created successfully');
        return redirect()->back()->with('error', 'Error creating new announcement');
    }

    public function announcement(){
        return view('admin.announcement');
    }

    public function blog(){
        return view('admin.blog');
    }

    public function lecture(){
        return view('admin.lecture');
    }
}
