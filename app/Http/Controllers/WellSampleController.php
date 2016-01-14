<?php namespace App\Http\Controllers;

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
        $samples = DB::select('select * from WellSample', [1]);
        return view('pages.wellsample');
    }
}
