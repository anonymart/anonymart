<?php

class MessagesController extends \BaseController {

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
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($order_id)
	{

		$inputs = Input::all();
		$rules = Message::getRules();

		$validator = Validator::make($inputs,$rules);
		if($validator->fails())
			return get_form_redirect('errors',$validator->messages()->all());

		$order = Order::find($order_id);

		if(!$order)
			throw new Exception('Order not found');

		$message = new Message;
		$message->text = $inputs['text'];
		if(Auth::check())
			$message->sender = 'vendor';
		else
			$message->sender = 'buyer';

		$order->messages()->save($message);

		return get_form_redirect('successes',['Message sent']);

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
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
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


}
