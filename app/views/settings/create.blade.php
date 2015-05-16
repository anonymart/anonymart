@extends('layout')

@section('content')
<div class="container">
	<h1>Your site is almost ready</h1>
	@include('form.open')
		@include('settings.fields')
		@include('settings.password_fields')
	@include('form.close')
</div>
@stop