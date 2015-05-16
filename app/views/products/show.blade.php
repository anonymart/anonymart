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
			<a class="btn btn-success btn-lg" href="{{$product->order_url}}" style="width:100%;text-align:center;">
				Order This Product
			</a>
		</div>
	</div>
</div>
@stop