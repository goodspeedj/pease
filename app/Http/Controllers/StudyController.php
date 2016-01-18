<?php namespace App\Http\Controllers;

use App\Study;

class StudyController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Study Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders the "study page" for the application.
    |
    */

    /**
     * Show the application study screen to the user.
     *
     * @return Response
     */
    public function index()
    {
 
        $studies = Study::all();
        return view('pages.study', compact('studies'));
    }
}
