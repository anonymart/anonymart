@extends('layout')

@section('content')
<div class="container">
	<h1>Submit your order for {{$product->title}}</h1>
	<div class="row">
		<div class="col-sm-5">
			@include('pgp_public')
		</div>
		<div class="col-sm-7">
			@include('form.open')
				<tr>
					<td>Quantity</td>
					<td>
						{{Form::number('quantity',1,['class'=>'form-control'])}}
					</td>
				</tr>
				<tr>
					<td>
						Your Shipping/Contact Info
						<p class="explainer">PGP Encryption is Required</p>
					</td>
					<td>
						{{Form::textarea('text',null,['class'=>'form-control'])}}
					</td>
				</tr>
				<tr>
					<td>
						Your PGP Public Key
					</td>
					<td>
						{{Form::textarea('pgp_public',null,['class'=>'form-control'])}}
					</td>
				</tr>
				@include('form.captcha')
			@include('form.close')
		</div>
	</div>
</div>
@stop