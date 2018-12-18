<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();
Route::get('/', function () {
    return redirect('/home');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/contests','ContestsController@index')->name('contests');
Route::get('/contests/{contestid}','ContestsController@viewContest')->name('viewContest');
Route::get('/contests/{contestid}/{problemid}','ContestsController@viewProblem')->name('viewProblem');
Route::post('/submit/{contestid}/{problemid}','ContestsController@submit');
Route::post('/judge','RatingController@rateContest');

Route::get('/profile','ProfileController@index');
Route::get('/profile/{UserID}','ProfileController@viewProfile');
Route::post('/profile/change/avatar','ProfileController@changeAvatar');

Route::get('/blog/entry/{postid}','PostController@viewPost');

Route::get('/learn/archive','LearnController@archived');

Route::get('/ranking','RankingController@index');

Route::get('/search','SearchController@query');

Route::get('/admin/dashboard','AdminController@index');
Route::get('/admin/dashboard/contest/new','AdminController@newcontest');
Route::get('/admin/dashboard/contest/past','AdminController@pastcontest');
Route::get('/admin/dashboard/contest/{contestid}/delete','AdminController@deleteContest');
Route::get('/admin/dashboard/problem/{problemid}','AdminController@getProblem');
Route::get('/admin/dashboard/contest/{contestid}/detail','AdminController@viewProblemsByContest');
Route::post('/admin/dashboard/problem/new','AdminController@addProblem');
Route::post('/admin/dashboard/problem/{problemid}/change','AdminController@changeProblem');
Route::get('/admin/dashboard/problem/{problemid}/delete','AdminController@deleteProblem');
Route::get('/admin/dashboard/announcement','AdminController@announcement');
Route::post('/admin/dashboard/contest/new','AdminController@createcontest');
Route::post('/admin/dashboard/announcement/new','AdminController@createannouncement');