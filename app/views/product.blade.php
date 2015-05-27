<div class="col col-xs-6 col-sm-4">
	<div class="panel panel-default product">
		<div class="panel-heading">
			<div class="panel-title">{{{$product->title}}}</div>
		</div>
		<div class="panel-body">
			{{$product->img}}
		</div>
		<div class="panel-footer">
			<div style="float:left">{{{$product->prices}}}</div>
			@if(Auth::guest())
				<a class="btn btn-success btn-xs panel-btn" href="{{$product->order_url}}">Order</a>
			@endif
			<a class="btn btn-primary btn-xs panel-btn" href="{{$product->url}}">Info</a>
			@if(Auth::check())
				<a class="btn btn-info btn-xs" style="float:right;margin-left:5px;" href="{{$product->edit_url}}">Edit</a>
			@endif
			<div style="clear:both"></div>
		</div>
	</div>
</div>