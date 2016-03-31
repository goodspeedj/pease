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
     * Show the well sample records for Collins in a chart
     *
     * @return \Illuminate\View\View
     */
    public function collinsChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(4)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for Portsmouth in a chart
     *
     * @return \Illuminate\View\View
     */
    public function portsmouthChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(5)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for WWTP in a chart
     *
     * @return \Illuminate\View\View
     */
    public function wwtpChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(6)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for DES in a chart
     *
     * @return \Illuminate\View\View
     */
    public function desChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(7)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for GBK Pre in a chart
     *
     * @return \Illuminate\View\View
     */
    public function gbk_preChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(8)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for GBK Post in a chart
     *
     * @return \Illuminate\View\View
     */
    public function gbk_post1Chart() 
    {
        $wellSamples = WellSample::wellSampleByWell(9)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for GBK Post in a chart
     *
     * @return \Illuminate\View\View
     */
    public function gbk_post2Chart() 
    {
        $wellSamples = WellSample::wellSampleByWell(10)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for DSC Pre in a chart
     *
     * @return \Illuminate\View\View
     */
    public function dsc_preChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(11)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for DSC Post in a chart
     *
     * @return \Illuminate\View\View
     */
    public function dsc_postChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(12)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for Firestation in a chart
     *
     * @return \Illuminate\View\View
     */
    public function firestationChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(13)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for csw-1d in a chart
     *
     * @return \Illuminate\View\View
     */
    public function csw1dChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(14)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for csw-1s in a chart
     *
     * @return \Illuminate\View\View
     */
    public function csw1sChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(15)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for csw-2r in a chart
     *
     * @return \Illuminate\View\View
     */
    public function csw2rChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(16)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for HMW-3 in a chart
     *
     * @return \Illuminate\View\View
     */
    public function hmw3Chart() 
    {
        $wellSamples = WellSample::wellSampleByWell(17)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for HMW-8R in a chart
     *
     * @return \Illuminate\View\View
     */
    public function hmw8rChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(18)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for HMW-14 in a chart
     *
     * @return \Illuminate\View\View
     */
    public function hmw14Chart() 
    {
        $wellSamples = WellSample::wellSampleByWell(19)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for HMW-15 in a chart
     *
     * @return \Illuminate\View\View
     */
    public function hmw15Chart() 
    {
        $wellSamples = WellSample::wellSampleByWell(20)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for SMW-A in a chart
     *
     * @return \Illuminate\View\View
     */
    public function smwaChart() 
    {
        $wellSamples = WellSample::wellSampleByWell(21)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for SMW-1 in a chart
     *
     * @return \Illuminate\View\View
     */
    public function smw1Chart() 
    {
        $wellSamples = WellSample::wellSampleByWell(22)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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
     * Show the well sample records for SMW-13 in a chart
     *
     * @return \Illuminate\View\View
     */
    public function smw13Chart() 
    {
        $wellSamples = WellSample::wellSampleByWell(23)->get();
        return view('pages.wellsamplechart_bywell', compact('wellSamples'));
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


    /**
     * Show the well sample records for PSW-1 in a chart
     *
     * @return \Illuminate\View\View
     */
    public function psw1Chart() 
    {
        $wellSamples = WellSample::wellSampleByWell(24)->get();
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
     * Show the well sample records for PFHxA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfhxaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(4)->get();
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
     * Show the well sample records for PFBA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfbaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(6)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFBS in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfbsChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(7)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFDA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfdaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(8)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFDS in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfdsChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(9)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFDeA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfdeaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(10)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFDoA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfdoaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(11)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFHpS in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfhpsChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(12)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFHpA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfhpaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(13)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFNA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfnaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(14)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFPeA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfpeaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(15)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFTeDA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pftedaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(16)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFTrDA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pftrdaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(17)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for PFUnA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function pfunaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(18)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for 6:2 FTS in a chart
     *
     * @return \Illuminate\View\View
     */
    public function fts62Chart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(19)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for 8:2 FTS in a chart
     *
     * @return \Illuminate\View\View
     */
    public function fts82Chart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(20)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for EtFOSA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function etfosaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(21)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for EtFOSE in a chart
     *
     * @return \Illuminate\View\View
     */
    public function etfoseChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(22)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for MEFOSA in a chart
     *
     * @return \Illuminate\View\View
     */
    public function mefosaChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(23)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }


    /**
     * Show the well sample records for MEFOSE in a chart
     *
     * @return \Illuminate\View\View
     */
    public function mefoseChart() 
    {
        $wellSamples = WellSample::wellSampleByPfc(24)->get();
        return view('pages.wellsamplechart_bypfc', compact('wellSamples'));
    }



}
