@extends('layout')

@section('content')
<div class="container">
	@include('settings.tabs',['tabId'=>'electrum'])
	<h1>Electrum Settings</h1>
	@include('form.open')
		<tr>
			<td>Mnemonic</td>
			<td>{{Form::text('mnemonic',$mnemonic,['class'=>'form-control'])}}</td>
		</tr>
	@include('form.close')
</div>
@stop