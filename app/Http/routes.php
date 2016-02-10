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
    return view('welcome');
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
Route::get('map', 'MapController@index');


/*
| Provides the route for the participant listing
*/
Route::get('participant', 'ParticipantController@index');


/*
| Provides the route for the study listing
*/
Route::get('study', 'StudyController@index');


/*
| Provides the route for the well sample listing
*/
Route::get('wellsample', 'WellSampleController@index');
Route::get('wellsample/haven', 'WellSampleController@haven');
Route::get('wellsample/smith', 'WellSampleController@smith');
Route::get('wellsample/harrison', 'WellSampleController@harrison');
Route::get('wellsample/wwtp', 'WellSampleController@wwtp');
Route::get('wellsample/des', 'WellSampleController@des');

/*
| Provides the routes for the well sample charts by well
*/
Route::get('wellsample/haven/chart', 'WellSampleController@havenChart');
Route::get('wellsample/smith/chart', 'WellSampleController@smithChart');
Route::get('wellsample/harrison/chart', 'WellSampleController@harrisonChart');
Route::get('wellsample/wwtp/chart', 'WellSampleController@wwtpChart');
Route::get('wellsample/des/chart', 'WellSampleController@desChart');

/*
| Provides the routes for the well sample charts by pfc
*/
Route::get('wellsample/PFOA/chart', 'WellSampleController@pfoaChart');
Route::get('wellsample/PFOS/chart', 'WellSampleController@pfosChart');
Route::get('wellsample/PFHxS/chart', 'WellSampleController@pfhxsChart');
Route::get('wellsample/PFOSA/chart', 'WellSampleController@pfosaChart');
Route::get('wellsample/PFNA/chart', 'WellSampleController@pfnaChart');
Route::get('wellsample/PFPeA/chart', 'WellSampleController@pfpeaChart');
Route::get('wellsample/PFHxA/chart', 'WellSampleController@pfhxaChart');
Route::get('wellsample/PFBA/chart', 'WellSampleController@pfbaChart');

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
