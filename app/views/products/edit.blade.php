@extends('layout')

@section('content')
<div class="container">
	<h1>Edit {{{$product->title}}}</h1>
	@include('form.open',['model'=>$product])
		@include('products.fields')
		<tr>
			<td></td>
			<td>
				{{$product->img}}
			</td>
		</tr>
	@include('form.close')
	<h1>Archive {{{$product->title}}}</h1>
	<p>After archiving this product will dissappear from your public site but still remain in your database. This is to avoid breaking existing orders.</p>
	<form action="{{{$product->destroy_url}}}" method="post" id="archiveForm">
		{{Form::token()}}
		<button class="btn btn-danger">Archive</button>
	</form>
</div>
@stop