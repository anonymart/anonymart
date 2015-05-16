@extends('layout')

@section('content')
<div class="container">
	{{ Markdown::parse(Settings::get('site_info')) }}
	<hr>
	<div class="row">
	@foreach($products as $product)
		@include('product',['product'=>$product])
	@endforeach
	</div>
</div>
@stop