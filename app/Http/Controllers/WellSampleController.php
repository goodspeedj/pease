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
     * Show the well sample records for Haven in a chart
     *
     * @return \Illuminate\View\View
     */
    public function havenChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(1)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for Smith in a chart
     *
     * @return \Illuminate\View\View
     */
    public function smithChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(2)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for Harrison in a chart
     *
     * @return \Illuminate\View\View
     */
    public function harrisonChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(3)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for WWTP in a chart
     *
     * @return \Illuminate\View\View
     */
    public function wwtpChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(4)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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


    /**
     * Show the well sample records for DES in a chart
     *
     * @return \Illuminate\View\View
     */
    public function desChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(5)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFOA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfoaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(1)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFOS in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfosChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(2)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFHxS in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfhxsChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(3)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFOSA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfosaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(5)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFNA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfnaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(6)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFPeA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfpeaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(8)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFHxA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfhxaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(9)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFBA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfbaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(10)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }
}
