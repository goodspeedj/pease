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
 
        //$wellSamples = WellSample::all();
        $wellSamples = DB::table('WellSample')
            ->select(DB::raw("
                    sampleDate, max(if(chemID=1, pfcLevel, ' ')) as 'PFOA', max(if(chemID=1, noteAbr, ' ')) as 'PFOANote',
                    sampleDate, max(if(chemID=2, pfcLevel, ' ')) as 'PFOS', max(if(chemID=2, noteAbr, ' ')) as 'PFOSNote',
                    sampleDate, max(if(chemID=3, pfcLevel, ' ')) as 'PFHxS', max(if(chemID=3, noteAbr, ' ')) as 'PFHxSNote',
                    sampleDate, max(if(chemID=5, pfcLevel, ' ')) as 'PFOSA', max(if(chemID=5, noteAbr, ' ')) as 'PFOSANote',
                    sampleDate, max(if(chemID=6, pfcLevel, ' ')) as 'PFNA', max(if(chemID=6, noteAbr, ' ')) as 'PFNANote',
                    sampleDate, max(if(chemID=8, pfcLevel, ' ')) as 'PFPeA', max(if(chemID=8, noteAbr, ' ')) as 'PFPeANote',
                    sampleDate, max(if(chemID=9, pfcLevel, ' ')) as 'PFHxA', max(if(chemID=9, noteAbr, ' ')) as 'PFHxANote',
                    sampleDate, max(if(chemID=10, pfcLevel, ' ')) as 'PFBA', max(if(chemID=10, noteAbr, ' ')) as 'PFBANote'
                "))
            ->join('SampleNote', 'WellSample.noteID', '=', 'SampleNote.noteID')
            ->where('wellID', '=', 2)
            ->groupBy('sampleDate')
            ->get();

        return view('pages.wellsample', compact('wellSamples'));
        //return $wellSamples;
    }
}
