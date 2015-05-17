@extends('layout')

@section('content')
<div class="container">
	@include('logs.tabs')
	<h1>Error Logs</h1>
	<pre>{{{File::get(base_path().'/app/storage/logs/laravel.log')}}}</pre>
</div>
@stop