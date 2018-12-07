<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:api'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/home',function(){
    $json = json_encode([1,2,3]);
    return $json;
});

// Route::get('/home','HomeController@index');
Route::get('/home/posts','HomeController@posts');
Route::get('/home/nextcontests','HomeController@nextcontests');
Route::get('/home/topusers','HomeController@topusers');

// Route::get('/contests','ContestsController@index');
Route::get('/contests/upcoming','ContestsController@upcoming');
Route::get('/contests/history','ContestsController@history');
// Route::get('/contests/search/{query}','ContestsController@search');
// Route::get('/contests/{contestsid}','ContestsController@contests');
// Route::get('/contests/{contestsid}/problems/','ContestsController@problems');
// Route::get('/contests/{contestsid}/problems/{problemid}','ContestsController@problem');
// Route::post('/contests/{contestsid}/problems/{problemid}','ContestsController@submit');
// Route::get('/contests/{contestsid}/ranking','ContestsController@ranking');

// Route::get('/ranking','RankingController@index');
// Route::get('/ranking/{query}','RankingController@show');
// Route::get('/ranking/search/{query}','RankingController@search');
// Route::get('/ranking/hof','RankingController@halloffame');

// Route::get('/learn','LearnController@index');
// Route::get('/learn/lectures','LearnController@lectures');
// Route::get('/learn/archive','LearnController@archive');
// Route::get('/learn/archive/{contestsid}/problems','LearnController@archiveProblems');
// Route::get('/learn/archive/tags/{tags}','LearnController@problemsWithTags'); 