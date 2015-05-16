<?PHP $tabs = ['unpaid'=>'Unpaid','paid'=>'Paid','shipped'=>'Shipped','cancelled'=>'Cancelled']; ?>
<ul class="nav nav-tabs" style="margin-bottom:40px;">
	<li class="{{{$status === null ? 'active' : ''}}}"><a href="/orders">All ({{{Order::count()}}})</a>	</li>
	@foreach($tabs as $tab=>$value)
		<li class="{{{$tab === $status ? 'active' : ''}}}">
			<a href="/orders?status={{{$tab}}}">
				{{{$value}}}
				({{{Order::queryByStatus($tab)->count()}}})
			</a>
		</li>
	@endforeach
</ul>