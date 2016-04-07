<?php namespace App\Http\Controllers;

use App\Well;

class WellController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Well Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders generic well data for the application separate
    | from well sample data.
    |
    */



    /**
     * Show the well data
     *
     * @return \Illuminate\View\View
     */
    public function wellmap() 
    {
        $wellData = Well::wellData()->get();
        return view('pages.wellmap', compact('wellData'));
    }


    /**
     * Show the well sample average for PFC in a map
     *
     * @return \Illuminate\View\View
     */
    public function pfcMap($pfc) 
    {
        $wellData = Well::wellData($pfc)->get();
        return view('pages.wellpfcmap', compact('wellData'));
    }
}