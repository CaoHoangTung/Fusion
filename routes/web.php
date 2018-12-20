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

Route::get('/settings','ProfileController@settings');
Route::post('/settings/change/password','ProfileController@changePassword');

Route::get('/blog/entry/{postid}','PostController@viewPost');

Route::get('/learn/archive','LearnController@archived');
Route::get('/learn/lectures','LearnController@lectures');

Route::get('/ranking','RankingController@index');

Route::get('/search','SearchController@query');

Route::get('/sadmin/dashboard','AdminController@index');
Route::get('/sadmin/dashboard/contest/new','AdminController@newcontest');
Route::get('/sadmin/dashboard/contest/past','AdminController@pastcontest');
Route::get('/sadmin/dashboard/contest/{contestid}/delete','AdminController@deleteContest');
Route::get('/sadmin/dashboard/problem/{problemid}','AdminController@getProblem');
Route::get('/sadmin/dashboard/contest/{contestid}/detail','AdminController@viewProblemsByContest');
Route::post('/sadmin/dashboard/problem/new','AdminController@addProblem');
Route::post('/sadmin/dashboard/problem/{problemid}/change','AdminController@changeProblem');
Route::get('/sadmin/dashboard/problem/{problemid}/delete','AdminController@deleteProblem');
// Route::get('/sadmin/dashboard/announcement','AdminController@announcement');
Route::post('/sadmin/dashboard/contest/new','AdminController@createcontest');
// Route::post('/sadmin/dashboard/announcement/new','AdminController@createannouncement');

Route::get('/sadmin/dashboard/blog','AdminController@blog');
Route::post('/sadmin/dashboard/blog/new','AdminController@createBlog');
Route::post('/sadmin/dashboard/blog/{blogid}/change','AdminController@changeBlog');
Route::get('/sadmin/dashboard/blog/{blogid}/delete','AdminController@deleteBlog');
Route::get('/sadmin/dashboard/blog/entry/{blogid}','AdminController@getBlog');

Route::get('/sadmin/dashboard/lecture','AdminController@lecture');
Route::post('/sadmin/dashboard/lecture/new','AdminController@createLecture');

Route::get('/sadmin/dashboard/announcement','AdminController@announcement');
Route::post('/sadmin/dashboard/announcement/new','AdminController@createAnnouncement');