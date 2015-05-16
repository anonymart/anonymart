<?php

class OrdersController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		if(Input::has('status'))
			$orders = Order::queryByStatus(Input::get('status'))->orderBy('created_at','DESC');
		else
			$orders = Order::orderBy('created_at','DESC');

		$orders = $orders->paginate(10);

		
		return View::make('orders.index',['orders'=>$orders,'status'=>Input::get('status')]);

	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($product_id)
	{
		$product = Product::find($product_id);
		return View::make('orders.create',['product'=>$product]);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($product_id)
	{

		$inputs = Input::all();
		$rules = array_merge(Order::getRules());

		$validator = Validator::make($inputs,$rules);
		
		if($validator->fails())
			return get_form_redirect('errors',$validator->messages()->all());

		$product = Product::find($product_id);
		$bitcoin = Bitcoin::make();

		$order = new Order;
		$order->fill($inputs);
		$order->product_id = $product_id;
		$order->product_amount_btc = $product->amount_btc;
		$order->address = get_blockchain()->Wallet->getNewAddress()->address;
		$order->save();

		$message = new Message;
		$message->order_id = $order->id;
		$message->sender = 'buyer';
		$message->text = $inputs['text'];
		$message->save();

		return Redirect::to($order->url);

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$order = Order::find($id);
		return View::make('orders.show',['order'=>$order]);
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

	public function mark($id){
		$order = Order::find($id);
		$order->mark(Input::get('status'));
		return get_form_redirect('successes',['Order has been marked as '.Input::get('status')]);
	}


}