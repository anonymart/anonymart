@extends('layout')

@section('content')
<div class="container">
	<h1>Edit Your Settings</h1>
	@include('form.open',['model'=>new Settings])
		@include('settings.fields')
	@include('form.close')
</div>
@stop