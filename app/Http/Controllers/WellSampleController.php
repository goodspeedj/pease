<?php namespace App\Http\Controllers;

use App\WellSample;
use DB;

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
        return "index page";
    }


    /**
     * Show the well sample records for Haven
     *
     * @return \Illuminate\View\View
     */
    public function haven() 
    {
        $wellSamples = WellSample::crosstab(1)->get();
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for Smith
     *
     * @return \Illuminate\View\View
     */
    public function smith() 
    {
        $wellSamples = WellSample::crosstab(2)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for Harrison
     *
     * @return \Illuminate\View\View
     */
    public function harrison() 
    {
        $wellSamples = WellSample::crosstab(3)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for WWTP
     *
     * @return \Illuminate\View\View
     */
    public function wwtp() 
    {
        $wellSamples = WellSample::crosstab(4)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for DES office
     *
     * @return \Illuminate\View\View
     */
    public function des() 
    {
        $wellSamples = WellSample::crosstab(5)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }
}
