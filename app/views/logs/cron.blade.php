@extends('layout')

@section('content')
<style>
	.job-failed{background-color:#fcf8e3;}
</style>
<div class="container">
	<h1>Cron Jobs</h1>
	<form class="form-inline">
   		{{Form::select('name',$jobNameOptions,Input::get('name'),['class'=>'form-control'])}}
	    <button class="btn btn-primary">Submit</button>
	</form>
	<table class="table">
		<th>
			<td>Job Name</td>
			<td>Duration</td>
			<td>Error Message</td>
		</th>
		@foreach($jobs as $job)
		<tr class="{{{$job->is_failed?'job-failed':''}}}">
			<td>{{{$job->id}}}.</td>
			<td>{{{$job->name}}}</td>
			<td>{{{$job->completed_at->diffInSeconds($job->created_at)}}}</td>
			<td>
				@if($job->message)
					<code>{{{$job->message}}}</code>
				@endif
			</td>
		</tr>
		@endforeach
	</table>
</div>
@stop