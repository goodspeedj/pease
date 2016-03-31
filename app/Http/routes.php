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
| Provides the routes for the well sample charts by well
*/
Route::get('wellsample/haven/chart', 'WellSampleController@havenChart');
Route::get('wellsample/smith/chart', 'WellSampleController@smithChart');
Route::get('wellsample/harrison/chart', 'WellSampleController@harrisonChart');
Route::get('wellsample/collins/chart', 'WellSampleController@collinsChart');
Route::get('wellsample/portsmouth/chart', 'WellSampleController@portsmouthChart');
Route::get('wellsample/wwtp/chart', 'WellSampleController@wwtpChart');
Route::get('wellsample/des/chart', 'WellSampleController@desChart');
Route::get('wellsample/gbk_pre/chart', 'WellSampleController@gbk_preChart');
Route::get('wellsample/gbk_post1/chart', 'WellSampleController@gbk_post1Chart');
Route::get('wellsample/gbk_post2/chart', 'WellSampleController@gbk_post2Chart');
Route::get('wellsample/dsc_pre/chart', 'WellSampleController@dsc_preChart');
Route::get('wellsample/dsc_post/chart', 'WellSampleController@dsc_postChart');
Route::get('wellsample/firestation/chart', 'WellSampleController@firestationChart');
Route::get('wellsample/csw-1d/chart', 'WellSampleController@csw-1dChart');
Route::get('wellsample/csw-1s/chart', 'WellSampleController@csw-1sChart');
Route::get('wellsample/csw-2r/chart', 'WellSampleController@csw-2rChart');
Route::get('wellsample/hmw-3/chart', 'WellSampleController@hmw-3Chart');
Route::get('wellsample/hmw-8r/chart', 'WellSampleController@hmw-8rChart');
Route::get('wellsample/hmw-14/chart', 'WellSampleController@hmw-14Chart');
Route::get('wellsample/hmw-15/chart', 'WellSampleController@hmw-15Chart');
Route::get('wellsample/smw-a/chart', 'WellSampleController@smw-aChart');
Route::get('wellsample/smw-1/chart', 'WellSampleController@smw-1Chart');
Route::get('wellsample/smw-13/chart', 'WellSampleController@smw-13Chart');
Route::get('wellsample/psw-1/chart', 'WellSampleController@psw-1Chart');
Route::get('wellsample/psw-2/chart', 'WellSampleController@psw-2Chart');

/*
| Provides the routes for the well sample charts by pfc
*/
Route::get('wellsample/pfoa/chart', 'WellSampleController@pfoaChart');
Route::get('wellsample/pfos/chart', 'WellSampleController@pfosChart');
Route::get('wellsample/pfhxs/chart', 'WellSampleController@pfhxsChart');
Route::get('wellsample/pfhxa/chart', 'WellSampleController@pfhxaChart');
Route::get('wellsample/pfosa/chart', 'WellSampleController@pfosaChart');
Route::get('wellsample/pfba/chart', 'WellSampleController@pfbaChart');
Route::get('wellsample/pfbs/chart', 'WellSampleController@pfbsChart');
Route::get('wellsample/pfda/chart', 'WellSampleController@pfdaChart');
Route::get('wellsample/pfds/chart', 'WellSampleController@pfdsChart');
Route::get('wellsample/pfdea/chart', 'WellSampleController@pfdeaChart');
Route::get('wellsample/pfdoa/chart', 'WellSampleController@pfdoaChart');
Route::get('wellsample/pfhps/chart', 'WellSampleController@pfhpsChart');
Route::get('wellsample/pfna/chart', 'WellSampleController@pfnaChart');
Route::get('wellsample/pfpea/chart', 'WellSampleController@pfpeaChart');
Route::get('wellsample/pfteda/chart', 'WellSampleController@pftedaChart');
Route::get('wellsample/pftrda/chart', 'WellSampleController@pftrdaChart');
Route::get('wellsample/pfuna/chart', 'WellSampleController@pfunaChart');
Route::get('wellsample/62fts/chart', 'WellSampleController@62ftsChart');
Route::get('wellsample/82fts/chart', 'WellSampleController@82ftsChart');
Route::get('wellsample/etfosa/chart', 'WellSampleController@etfosaChart');
Route::get('wellsample/etfose/chart', 'WellSampleController@etfoseChart');
Route::get('wellsample/mefosa/chart', 'WellSampleController@mefosaChart');
Route::get('wellsample/mefose/chart', 'WellSampleController@mefoseChart');


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
