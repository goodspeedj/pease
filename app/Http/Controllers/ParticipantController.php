<?php namespace App\Http\Controllers;
class MapController extends Controller {
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
		return 'hello participant';
		//return view('participant');
	}
}