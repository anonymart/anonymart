<?php

class SettingsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('settings.create',['settings'=>new Settings]);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
		$inputs = Input::all();
		$rules = array_merge(Settings::make()->rules,[
			'password'=>'required|confirmed'
		]);

		$validator = Validator::make($inputs,$rules);

		if($validator->fails())
			return get_form_redirect('errors',$validator->messages()->all());

		Settings::set($inputs);

		$user = User::where('username','admin')->first();
		if($user) $user->delete();

		$user = new User;
		$user->is_admin = true;
		$user->username = 'admin';
		$user->password = $inputs['password'];
		$user->save();

		return Redirect::to('/');

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit()
	{
		return View::make('settings.edit');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
		$inputs = Input::all();
		$rules = array_merge(Settings::make()->rules,[
			'password'=>'confirmed'
			,'password_confirmation'=>'same:password'
		]);

		$validator = Validator::make($inputs,$rules);

		if($validator->fails())
			return get_form_redirect('errors',$validator->messages()->all());

		Settings::set($inputs);

		if(Input::has('password')){
			Auth::user()->password = Input::get('password');
			Auth::user()->save();
		}

		return get_form_redirect('successes',['Settings updated']);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	public function postPassword()
	{
		return View::make('settings.edit_password');
	}


}
