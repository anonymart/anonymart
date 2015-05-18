@extends('layout')

@section('content')
<div class="container">
	@include('logs.tabs',['tabId'=>'errors'])
	<h1>Error Logs</h1>
	@if(file_exists(ERROR_LOG_PATH))
		<pre>{{{File::get(ERROR_LOG_PATH)}}}</pre>
	@else
		<div class="alert alert-info">
			No errors so far.
		</div>
	@endif
</div>
@stop