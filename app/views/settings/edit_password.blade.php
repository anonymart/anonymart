@extends('layout')

@section('content')
<div class="container">
	@include('settings.tabs',['tabId'=>'password'])
	<h1>Edit Your Password</h1>
	@include('form.open')
		@include('settings.password_fields')
	@include('form.close')
</div>
@stop