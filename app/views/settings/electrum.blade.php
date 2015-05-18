@extends('layout')

@section('content')
<div class="container">
	@include('settings.tabs',['tabId'=>'electrum'])
	<h1>Electrum Settings</h1>
	@include('form.open')
		{{Form::text('mnemonic',$mnemonic,['class'=>'form-control'])}}
	@include('form.close')
</div>
@stop