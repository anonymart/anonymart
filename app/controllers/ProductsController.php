<?php

class ProductsController extends \BaseController {

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
		return View::make('products.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$inputs = Input::all();
		$rules = Product::getRules();

		$rules['image'].='|required';

		$validator = Validator::make($inputs,$rules);
		if($validator->fails())
			return get_form_redirect('errors',$validator->messages()->all());

		$product = new Product;
		$product->title = $inputs['title'];
		$product->info = $inputs['info'];
		$product->amount_fiat  = $inputs['amount_fiat'];
		$product->save();

		move_uploaded_file($_FILES['image']['tmp_name'], $product->image_path);



		$product->save();

		return get_form_redirect('successes',["\"{$product->title}\" added to products list"]);

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$product = Product::find($id);
		return View::make('products.show',['product'=>$product]);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$product = Product::find($id);
		return View::make('products.edit',['product'=>$product]);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$inputs = Input::all();
		$rules = Product::getRules();

		$validator = Validator::make($inputs,$rules);
		if($validator->fails())
			return get_form_redirect('errors',$validator->messages()->all());

		$product = Product::find($id);
		$product->title = $inputs['title'];
		$product->info = $inputs['info'];
		$product->amount_fiat  = $inputs['amount_fiat'];
		$product->save();

		if(isset($inputs['image'])){
			move_uploaded_file($_FILES['image']['tmp_name'], $product->image_path);
		}

		$product->save();

		return get_form_redirect('successes',["\"{$product->title}\" updated"]);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$product = Product::find($id);
		$product->delete();
		return Redirect::to('/')->with(['successes'=>["\"{$product->title}\" archived"]]);
	}


}
