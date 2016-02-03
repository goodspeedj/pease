<?php namespace App\Http\Controllers;

use App\Participant;
use DB;

class ParticipantController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Participant Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders the "participant page" for the application.
    |
    */

    /**
     * Show the application participant screen to the user.
     *
     * @return Response
     */
    public function index()
    {
        $participants = Participant::crosstab()->get();
        return view('pages.participant', compact('participants'));
    }
}
