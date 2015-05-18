@extends('layout')

@section('content')
<div class="container">
	@include('logs.tabs')
	<h1>Error Logs</h1>
	@if(file_exists(ERROR_LOG))
		<pre>{{{File::get(ERROR_LOG)}}}</pre>
	@else
		<div class="alert alert-info">
			No errors so far.
		</div>
	@endif
</div>
@stop