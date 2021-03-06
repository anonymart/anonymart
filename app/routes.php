<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::group(['before'=>'settings.incomplete'],function(){
	Route::get('settings/create','SettingsController@create');

	Route::group(['before'=>'csrf'],function(){
		Route::post('settings/create','SettingsController@store');
	});
});

Route::group(['before'=>'settings.complete'],function(){
	Route::get('/',function(){
		return View::make('home',['products'=>Product::all()]);
	});

	Route::get('login','AuthController@getLogin');

	Route::group(['before'=>'guest'],function(){
		Route::get('products/{product_id}/orders/create','OrdersController@create');
		Route::group(['before'=>'csrf'],function(){
			Route::post('products/{product_id}/orders/create','OrdersController@store');
		});
	});

	Route::group(['before'=>'order.code'],function(){
		Route::get('orders/{order_id}','OrdersController@show');
		Route::group(['before'=>'csrf'],function(){
			Route::post('orders/{order_id}/markCancelled','OrdersController@markCancelled');
			Route::post('orders/{order_id}/messages/create','MessagesController@store');
		});
	});

	Route::group(['before'=>'csrf'],function(){
		Route::post('login','AuthController@postLogin');
	});

	Route::group(['before'=>'auth'],function(){
		Route::get('logout','AuthController@getLogout');
		Route::get('settings/edit','SettingsController@edit');
		Route::get('settings/password','SettingsController@edit_password');
		Route::get('orders','OrdersController@index');
		Route::get('products/create','ProductsController@create');
		Route::get('products/{product_id}/edit','ProductsController@edit');
		Route::get('logs/errors',function(){
			return View::make('logs.errors');
		});
		Route::get('logs/cron',function(){
			$jobsQuery = Job::orderBy('created_at','DESC')->take(100);

			if(Input::has('name'))
				$jobs = $jobsQuery->where('name',Input::get('name'))->get();
			else
				$jobs = $jobsQuery->get();

			return View::make('logs.cron',[
				'jobs'=>$jobs
				,'jobNameOptions'=>Job::getNameOptions()
			]);
		});

		Route::group(['before'=>'csrf'],function(){
			Route::post('settings/edit','SettingsController@update');
			Route::post('products/create','ProductsController@store');
			Route::post('products/{product_id}/edit','ProductsController@update');
			Route::post('products/{product_id}/destroy','ProductsController@destroy');
			Route::post('orders/{order_id}/markShipped','OrdersController@markShipped');
		});
	});

	Route::get('products/{product_id}','ProductsController@show');
});
