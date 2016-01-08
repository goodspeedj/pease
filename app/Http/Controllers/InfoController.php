<?php namespace App\Http\Controllers;
class InfoController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Info Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders the "info page" for the application.
    |
    */

    /**
     * Show the application well sample screen to the user.
     *
     * @return Response
     */
    public function index()
    {
        return view('info');
    }
}
