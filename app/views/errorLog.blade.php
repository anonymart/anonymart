@extends('layout')

@section('content')
<div class="container">
	<h1>Error Logs</h1>
	<pre>
		{{{return base_path().'/app/storage/logs/laravel.log'}}}
	</pre>
</div>
@stop