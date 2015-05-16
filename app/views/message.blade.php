<div class="panel message {{$message->class}}">
	<div class="panel-heading">
		{{{$message->heading}}}
	</div>
	<div class="panel-body">
		@include('text',['text'=>$message->text])
	</div>
	<div class="panel-footer">
		{{{$message->created_at->diffForHumans()}}}
	</div>
</div>