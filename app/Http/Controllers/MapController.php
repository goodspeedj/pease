<?php namespace App\Http\Controllers;
class MapController extends Controller {
	/*
	|--------------------------------------------------------------------------
	| Map Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "map page" for the application.
	|
	*/

	/**
	 * Show the application map screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return 'hello map';
		//return view('map');
	}
}