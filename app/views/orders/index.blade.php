@extends('layout')

@section('content')
<div class="container">
	<h1>Orders</h1>
	@include('orders.tabs',['status'=>$status])
	@if(count($orders)===0)
	<div class="alert alert-info">Nothing Here</div>
	@else
	<table class="table">
		<tr>
			<th>Product</th>
			<th>When</th>
			<th>Quantity</th>
			<th>Status</th>
			<th></th>
		</tr>
		@foreach($orders as $order)
		<tr>
			<td>
				{{{$order->product->title}}}
				@if($order->product->trashed())
					<b>(Archived)</b>
				@endif
			</td>
			<td>{{{$order->created_at->diffForHumans()}}}</td>
			<td>{{{$order->quantity}}}</td>
			<td>{{{$order->status_pretty}}}</td>
			<td><a href="{{{$order->url}}}">View</a></td>
		<tr>
		@endforeach
	</table>
	<center>
		<ul class="pagination" style="">
			{{(new Illuminate\Pagination\BootstrapPresenter($orders))->render()}}
		</ul>
	</center>
	@endif
</div>
@stop