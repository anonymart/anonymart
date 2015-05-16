@extends('layout')

@section('content')
<div class="container">
	<h1>Login</h1>
	@include('form.open')
		<tr>
			<td>Password</td>
			<td>
				{{Form::password('password',['class'=>'form-control'])}}
			</td>
		</tr>
		@include('form.captcha')
	@include('form.close')
</div>
@stop