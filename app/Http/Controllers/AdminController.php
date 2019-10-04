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
        $arr = array();
        $userCount = DB::table('users')->count();
        $contestCount = DB::table('contests')->count();
        $problemCount = DB::table('problems')->count();
        $subCount = DB::table('submissions')->count();
        
        $arr['userCount'] = $userCount;
        $arr['contestCount'] = $contestCount;
        $arr['problemCount'] = $problemCount;
        $arr['subCount'] = $subCount;

        return view('admin.index',$arr);
    }

    public function newcontest(){
        $arr = array();
        $newid = DB::table('contests')->orderBy('ContestID','desc')->get()->first();

        if (sizeof(DB::table('contests')->orderBy('ContestID','desc')->get()) > 0)
            $arr['newid'] = $newid->ContestID+1;
        else
            $arr['newid'] = 1;
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

    // public function createannouncement(Request $req){
    //     if(DB::table('posts')->insert(['Header'=>$req['Header'],'Content'=>$req['Content'],
    //                                     'Creator'=>Auth::user()->id,'Type'=>'announcement']));
    //         return redirect()->back()->with('success', 'Announcement created successfully');
    //     return redirect()->back()->with('error', 'Error creating new announcement');
    // }

    public function blog(){
        $resultPerPage = 20;
        $arr = array();
        
        $blogs = DB::table('posts')->where('Type','post')->Paginate($resultPerPage);
        $arr['posts'] = $blogs;

        return view('admin.blog',$arr);
    }

    public function  createBlog(Request $req){
        $Header = $req->Header;
        $Content = $req->Content;
        $Creator = Auth::user()->id;

        if (DB::table('posts')->insert(['Header'=>$Header,'Content'=>$Content,'Creator'=>$Creator,'Type','post']))
            return redirect()->back()->with('success','Blog added');
        return redirect()->back()->with('error','Error creating new blog');
    }

    public function changeBlog(Request $req, $PostID){
        $Header = $req->Header;
        $Content = $req->Content;

        if (DB::table('posts')->where('PostID',$PostID)->update(['Header'=>$Header,'Content'=>$Content]))
            return redirect()->back()->with('success','Post updated');
        return redirect()->back()->with('error','Error updating post'); 
    }

    public function deleteBlog(Request $req, $PostID){
        if (DB::table('posts')->where('PostID',$PostID)->delete())
            return redirect()->back()->with('success','Post deleted');
        return redirect()->back()->with('error','Error deleting post');
    }

    public function getBlog(Request $req, $PostID){
        $post =  DB::table('posts')->where('PostID','=',$PostID)->get()->toArray();
        return json_encode($post[0]);
    }

    public function lecture(){
        $resultPerPage = 20;
        $arr = array();
        
        $lectures = DB::table('posts')->where('Type','lecture')->Paginate($resultPerPage);
        $arr['posts'] = $lectures;

        return view('admin.lecture',$arr);
    }

    public function  createLecture(Request $req){
        $Header = $req->Header;
        $Content = $req->Content;
        $Creator = Auth::user()->id;

        if (DB::table('posts')->insert(['Header'=>$Header,'Content'=>$Content,'Creator'=>$Creator,'Type'=>'lecture']))
            return redirect()->back()->with('success','Lecture added');
        return redirect()->back()->with('error','Error creating new lecture');
    }

    public function announcement(){
        $resultPerPage = 20;
        $arr = array();
        
        $announcements = DB::table('posts')->where('Type','announcement')->Paginate($resultPerPage);
        $arr['posts'] = $announcements;

        return view('admin.announcement',$arr);
    }

    public function createAnnouncement(Request $req){
        $Header = $req->Header;
        $Content = $req->Content;
        $Creator = Auth::user()->id;

        if (DB::table('posts')->insert(['Header'=>$Header,'Content'=>$Content,'Creator'=>$Creator,'Type'=>'announcement']))
            return redirect()->back()->with('success','Announcement added');
        return redirect()->back()->with('error','Error creating new announcement');
    }

}
