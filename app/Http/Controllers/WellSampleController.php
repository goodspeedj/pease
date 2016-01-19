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


    public function haven() 
    {
        $wellSamples = DB::table('WellSample')
            ->select(DB::raw("sampleDate,
                    max(if(chemID=1, pfcLevel, ' ')) as 'PFOA', max(if(chemID=1, noteAbr, ' ')) as 'PFOANote',
                    max(if(chemID=2, pfcLevel, ' ')) as 'PFOS', max(if(chemID=2, noteAbr, ' ')) as 'PFOSNote',
                    max(if(chemID=3, pfcLevel, ' ')) as 'PFHxS', max(if(chemID=3, noteAbr, ' ')) as 'PFHxSNote',
                    max(if(chemID=5, pfcLevel, ' ')) as 'PFOSA', max(if(chemID=5, noteAbr, ' ')) as 'PFOSANote',
                    max(if(chemID=6, pfcLevel, ' ')) as 'PFNA', max(if(chemID=6, noteAbr, ' ')) as 'PFNANote',
                    max(if(chemID=8, pfcLevel, ' ')) as 'PFPeA', max(if(chemID=8, noteAbr, ' ')) as 'PFPeANote',
                    max(if(chemID=9, pfcLevel, ' ')) as 'PFHxA', max(if(chemID=9, noteAbr, ' ')) as 'PFHxANote',
                    max(if(chemID=10, pfcLevel, ' ')) as 'PFBA', max(if(chemID=10, noteAbr, ' ')) as 'PFBANote'
                "))
            ->leftJoin('SampleNote', 'WellSample.noteID', '=', 'SampleNote.noteID')
            ->where('wellID', '=', 1)
            ->groupBy('sampleDate')
            ->get();

        return view('pages.wellsample', compact('wellSamples'));
    }

    public function smith() 
    {
        $wellSamples = DB::table('WellSample')
            ->select(DB::raw("sampleDate,
                    max(if(chemID=1, pfcLevel, ' ')) as 'PFOA', max(if(chemID=1, noteAbr, ' ')) as 'PFOANote',
                    max(if(chemID=2, pfcLevel, ' ')) as 'PFOS', max(if(chemID=2, noteAbr, ' ')) as 'PFOSNote',
                    max(if(chemID=3, pfcLevel, ' ')) as 'PFHxS', max(if(chemID=3, noteAbr, ' ')) as 'PFHxSNote',
                    max(if(chemID=5, pfcLevel, ' ')) as 'PFOSA', max(if(chemID=5, noteAbr, ' ')) as 'PFOSANote',
                    max(if(chemID=6, pfcLevel, ' ')) as 'PFNA', max(if(chemID=6, noteAbr, ' ')) as 'PFNANote',
                    max(if(chemID=8, pfcLevel, ' ')) as 'PFPeA', max(if(chemID=8, noteAbr, ' ')) as 'PFPeANote',
                    max(if(chemID=9, pfcLevel, ' ')) as 'PFHxA', max(if(chemID=9, noteAbr, ' ')) as 'PFHxANote',
                    max(if(chemID=10, pfcLevel, ' ')) as 'PFBA', max(if(chemID=10, noteAbr, ' ')) as 'PFBANote'
                "))
            ->leftJoin('SampleNote', 'WellSample.noteID', '=', 'SampleNote.noteID')
            ->where('wellID', '=', 2)
            ->groupBy('sampleDate')
            ->get();

        return view('pages.wellsample', compact('wellSamples'));
    }
}
