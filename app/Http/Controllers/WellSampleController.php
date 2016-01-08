<?php namespace App\Http\Controllers;
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
		return 'hello well sample';
		//return view('wellsample');
	}
}
