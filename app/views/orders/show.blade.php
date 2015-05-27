@extends('layout')

@section('content')
<div class="container">
	@if(Auth::guest())
	<div class="alert alert-info" style="text-align:center">
		<p>This is your order url. Save it somewhere safe. You will not be able to check or update your order if you lose it.</p>
		<pre style="display:inline-block;margin-top:10px;">{{{$order->url_with_code}}}</pre>
	</div>
	@endif
	<h1>Order for {{{$order->product->title}}} x {{{$order->quantity}}}: {{{$order->status_pretty}}}</h1>
	@if($order->is_expired === false && $order->status==='unpaid' && Auth::guest())
		<p>
			Please send {{{$order->total_amount_btc}}} BTC to <code id="address">{{{$order->address}}}</code> within {{{$order->ttl_minutes}}} minutes.			
		</p>
	@endif
	@if(Auth::check())
	<p><b>Address:</b> {{{$order->address}}}</p>
	@endif
	<p><b>Receieved:</b> {{{$order->balance_btc}}} BTC</p>
	@if($order->is_expired===true)
		<div class="alert alert-danger">
			This order has expired
		</div>
	@endif
	@if(($order->is_cancellable))
		<form action="{{{$order->mark_cancelled_url}}}" method="post" style="display:inline-block" id="markCancelledForm">
			@if(Auth::guest())
				{{Form::hidden('code',$order->code)}}
			@endif
			<input type="hidden" name="status" value="cancelled">
			{{Form::token()}}
			<button class="btn btn-danger">Cancel</button>
		</form>
	@endif
	@if(Auth::check())
		<form action="{{{$order->mark_shipped_url}}}" method="post" style="display:inline-block" id="markShippedForm">
			@if(Auth::guest())
				{{Form::hidden('code',$order->code)}}
			@endif
			<input type="hidden" name="status" value="shipped">
			{{Form::token()}}
			<button class="btn btn-primary">Mark as Shipped</button>
		</form>
	@endif
	<hr>
	<div class="row">
		<div class="col-sm-5">
			@if(Auth::guest())
				@include('pgp_public')
			@elseif($order->pgp_public)
				<h4>The buyer's public key</h4>
				<pre class="pgp_public">{{{$order->pgp_public}}}</pre>
			@else
				<h4>The buyer has not set a PGP Public Key</h4>
			@endif
		</div>
		<div class="col-sm-7">
			<h4>Messages</h4>
			@foreach($order->messages()->orderBy('created_at','ASC')->get() as $message)
				@include('message',['message'=>$message])
			@endforeach
			<hr>
			<h4>New Message</h4>
			@include('form.open',['action'=>$order->message_url])
				<tr>
					<td>Message <p class="explainer">PGP Encryption is Required</p></td>
					<td>
						{{Form::textarea('text',null,['class'=>'form-control'])}}
						{{Form::hidden('code',$order->code)}}
					</td>
				</tr>
				@include('form.captcha')
			@include('form.close')
		</div>
	</div>
</div>
@stop