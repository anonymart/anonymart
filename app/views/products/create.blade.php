@extends('layout')

@section('content')
<div class="container">
	<h1>Add a New Product</h1>
	@include('form.open')
		@include('products.fields')
	@include('form.close')
</div>
@stop