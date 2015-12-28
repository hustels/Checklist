<?php
use App\Events\taskHasOwned;
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     return view('welcome');

// });

Route::get('/' , 'homeController@home');
Route::post('/tasks' , 'homeController@ShowTodayTasks');
Route::post('/ownTask' , 'homeController@ownTask');
Route::post('/completeTask' , 'homeController@completeTask');

Route::post('/unOwn' , 'homeController@unOwnTask');

Route::post('/uncompleteTask' , 'homeController@uncompleteTask');
Route::post('/inProgressBy' , 'homeController@inProgressBy');
Route::post('/whoOwn' , 'homeController@whoOwn');
Route::post('/userCompleteTask' , 'homeController@userCompleteTask');
Route::post('/completedBy' , 'homeController@completedBy');
Route::post('/isLocked' , 'homeController@isLocked');
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
/*
Route::get('/test', function () {
    //event(new App\Events\taskHasOwned());
    //return "event fired";
    event(new taskHasOwned('Cheikh Ndiaye'));
}); */

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
});
