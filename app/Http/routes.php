<?php

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

Route::get('/', function () {
    //return view('welcome');
    return view('pages.home');
});


/*
| Provides the route for the chemical listing
*/
Route::get('chemical', 'ChemicalController@index');


/*
| Provides the route for the exposure map
*/
Route::get('info', 'InfoController@index');


/*
| Provides the route for the exposure map
*/
Route::get('wellmap', 'WellController@index');
Route::get('wellmap/{pfc}', 'WellController@pfcMap');


/*
| Provides the route for the well sample listing
*/
Route::get('wellsample/{wellName}', 'WellSampleController@wellSample');


/*
| Provides the routes for the well sample charts by well
*/
Route::get('wellsample/well/{wellName}/chart', 'WellSampleController@wellChart');


/*
| Provides the routes for the well sample charts by pfc
*/
Route::get('wellsample/pfc/{pfc}/chart', 'WellSampleController@pfcChart');


/*
| Provides the route for the participant listing
*/
Route::get('participant', 'ParticipantController@index');


/*
| Provides the route for the study listing
*/
Route::get('study', 'StudyController@index');


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
