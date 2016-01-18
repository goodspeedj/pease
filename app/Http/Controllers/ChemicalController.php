<?php namespace App\Http\Controllers;

use App\Chemical;

class ChemicalController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Chemical Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders the "chemical page" for the application.
    |
    */

    /**
     * Show the application chemical screen to the user.
     *
     * @return Response
     */
    public function index()
    {
 
        $chemicals = Chemical::all();
        return view('pages.chemical', compact('chemicals'));
    }
}
