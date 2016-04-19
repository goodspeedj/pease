<?php namespace App\Http\Controllers;

use App\WellSample;
use App\Chemical;
use App\Well;
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
     * Show the well sample records in crosstab format
     *
     * @return \Illuminate\View\View
     */
    public function wellSample($wellName) 
    {
        $wellSamples = WellSample::crosstab($wellName)->get();
        $wells = Well::allWells()->get();
        return view('pages.wellsample', compact('wellSamples', 'wells'));
    }


    /**
     * Show the well sample records for Haven in a chart
     *
     * @return \Illuminate\View\View
     */
    public function wellChart($wellName) 
    {
        $wellSamples = WellSample::wellSampleByWell($wellName)->get();
        $well = Well::well($wellName)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples', 'well'));
    }


    /**
     * Show the well sample records for PFCs in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfcChart($pfc) 
    {
        $wellSamples = WellSample::wellSampleByPfc($pfc)->get();
        $chem = Chemical::pfc($pfc)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples', 'chem'));
    }
}
