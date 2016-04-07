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
//Route::get('map', 'MapController@index');


/*
| Provides the route for the exposure map
*/
Route::get('wellmap', 'WellController@index');
Route::get('wellmap/{pfc}', 'WellController@pfcMap');


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
| Provides the route for the well sample listing
*/
Route::get('wellsample', 'WellSampleController@index');
Route::get('wellsample/haven', 'WellSampleController@haven');
Route::get('wellsample/smith', 'WellSampleController@smith');
Route::get('wellsample/harrison', 'WellSampleController@harrison');
Route::get('wellsample/collins', 'WellSampleController@collins');
Route::get('wellsample/portsmouth', 'WellSampleController@portsmouth');
Route::get('wellsample/wwtp', 'WellSampleController@wwtp');
Route::get('wellsample/des', 'WellSampleController@des');
Route::get('wellsample/gbk_pre', 'WellSampleController@gbk_pre');
Route::get('wellsample/gbk_post1', 'WellSampleController@gbk_post1');
Route::get('wellsample/gbk_post2', 'WellSampleController@gbk_post2');
Route::get('wellsample/dsc_pre', 'WellSampleController@dsc_pre');
Route::get('wellsample/dsc_post', 'WellSampleController@dsc_post');
Route::get('wellsample/firestation', 'WellSampleController@firestation');
Route::get('wellsample/csw-1d', 'WellSampleController@csw-1d');
Route::get('wellsample/csw-1s', 'WellSampleController@csw-1s');
Route::get('wellsample/csw-2r', 'WellSampleController@csw-2r');
Route::get('wellsample/hmw-3', 'WellSampleController@hmw-3');
Route::get('wellsample/hmw-8r', 'WellSampleController@hmw-8r');
Route::get('wellsample/hmw-14', 'WellSampleController@hmw-14');
Route::get('wellsample/hmw-15', 'WellSampleController@hmw-15');
Route::get('wellsample/smw-a', 'WellSampleController@smw-a');
Route::get('wellsample/smw-1', 'WellSampleController@smw-1');
Route::get('wellsample/smw-13', 'WellSampleController@smw-13');
Route::get('wellsample/psw-1', 'WellSampleController@psw-1');
Route::get('wellsample/psw-2', 'WellSampleController@psw-2');




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
