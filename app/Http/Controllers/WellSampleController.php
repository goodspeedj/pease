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
    public function wellChart($wellName) 
    {
        $wellSamples = WellSample::wellSampleByWell($wellName)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFCs in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfcChart($pfc) 
    {
        $wellSamples = WellSample::wellSampleByPfc($pfc)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
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
     * Show the well sample records for Collins
     *
     * @return \Illuminate\View\View
     */
    public function collins() 
    {
        $wellSamples = WellSample::crosstab(4)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }



    /**
     * Show the well sample records for Portsmouth
     *
     * @return \Illuminate\View\View
     */
    public function portsmouth() 
    {
        $wellSamples = WellSample::crosstab(5)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for WWTP
     *
     * @return \Illuminate\View\View
     */
    public function wwtp() 
    {
        $wellSamples = WellSample::crosstab(6)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for DES office
     *
     * @return \Illuminate\View\View
     */
    public function des() 
    {
        $wellSamples = WellSample::crosstab(7)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for GBK Pre
     *
     * @return \Illuminate\View\View
     */
    public function gbk_pre() 
    {
        $wellSamples = WellSample::crosstab(8)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for GBK Post
     *
     * @return \Illuminate\View\View
     */
    public function gbk_post1() 
    {
        $wellSamples = WellSample::crosstab(9)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for GBK Post
     *
     * @return \Illuminate\View\View
     */
    public function gbk_post2() 
    {
        $wellSamples = WellSample::crosstab(10)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for DSC Pre
     *
     * @return \Illuminate\View\View
     */
    public function dsc_pre() 
    {
        $wellSamples = WellSample::crosstab(11)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for DSC Post
     *
     * @return \Illuminate\View\View
     */
    public function dsc_post() 
    {
        $wellSamples = WellSample::crosstab(12)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for Firestation
     *
     * @return \Illuminate\View\View
     */
    public function firestation() 
    {
        $wellSamples = WellSample::crosstab(13)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for CSW-1D
     *
     * @return \Illuminate\View\View
     */
    public function csw1d() 
    {
        $wellSamples = WellSample::crosstab(14)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for CSW-1S
     *
     * @return \Illuminate\View\View
     */
    public function csw1s() 
    {
        $wellSamples = WellSample::crosstab(15)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for CSW-2R
     *
     * @return \Illuminate\View\View
     */
    public function csw2r() 
    {
        $wellSamples = WellSample::crosstab(16)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for HMW-3
     *
     * @return \Illuminate\View\View
     */
    public function hmw3() 
    {
        $wellSamples = WellSample::crosstab(17)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for HMW-8R
     *
     * @return \Illuminate\View\View
     */
    public function hmw8r() 
    {
        $wellSamples = WellSample::crosstab(18)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for HMW-14
     *
     * @return \Illuminate\View\View
     */
    public function hmw14() 
    {
        $wellSamples = WellSample::crosstab(19)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for HMW-15
     *
     * @return \Illuminate\View\View
     */
    public function hmw15() 
    {
        $wellSamples = WellSample::crosstab(20)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for SMW-A
     *
     * @return \Illuminate\View\View
     */
    public function smwa() 
    {
        $wellSamples = WellSample::crosstab(21)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for SMW-1
     *
     * @return \Illuminate\View\View
     */
    public function smw1() 
    {
        $wellSamples = WellSample::crosstab(22)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for SMW-13
     *
     * @return \Illuminate\View\View
     */
    public function smw13() 
    {
        $wellSamples = WellSample::crosstab(23)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PSW-1
     *
     * @return \Illuminate\View\View
     */
    public function psw1() 
    {
        $wellSamples = WellSample::crosstab(24)->get();        
        return view('pages.wellsample', compact('wellSamples'));
    }

}
