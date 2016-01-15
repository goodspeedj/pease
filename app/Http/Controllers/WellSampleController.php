<?php namespace App\Http\Controllers;

use App\WellSample;

class WellSampleController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Well Sample Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders the "well sample page" for the application.
    |
    */

    /**
     * Show the application well sample screen to the user.
     *
     * @return Response
     */
    public function index()
    {
 
        $wellSamples = WellSample::all();
        //return $wellSamples;
        return view('pages.wellsample', compact('wellSamples'));
    }
}
