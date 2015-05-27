@extends('layout')

@section('content')
<div class="container">
	<h1>{{{$product->title}}}</h1>
	<div class="row">
		<div class="col-sm-8">
			{{Markdown::parse($product->info)}}
		</div>
		<div class="col-sm-4">
			<h4 id="prices">Price: {{{$product->prices}}}</h4>
			@if(Auth::check())
			<div class="alert alert-info text-center">You must log out before ordering</div>
			@endif
			<a class="btn btn-success btn-lg text-center {{{Auth::check()?'disabled':''}}}" href="{{{Auth::guest()?$product->order_url:''}}}" style="width:100%;">
				Order This Product
			</a>
		</div>
	</div>
</div>
@stop