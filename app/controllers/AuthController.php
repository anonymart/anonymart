<?php

class AuthController extends \BaseController {

	public function getLogin(){
		return View::make('login');
	}

	public function postLogin(){
		$inputs = Input::all();

		$validator = Validator::make($inputs,[
			'password'=>'required'
			,'captcha'=>'required|captchaish'
		]);

		if($validator->fails())
			return get_form_redirect('errors',$validator->messages()->all());

		$user = User::first();
		if(Hash::check($inputs['password'],$user->password)!==true)
			return get_form_redirect('errors',['Wrong password']);

		Auth::login($user);
		return Redirect::intended();
	}

	public function getLogout(){
		Auth::logout();
		return Redirect::to('/');
	}

}
