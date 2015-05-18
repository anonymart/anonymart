<ul class="nav nav-tabs">
	@foreach($tabs as $id=>$tab)
		<li class="{{{$id===$tabId?'active':''}}}"><a href="{{{$tab->href}}}">{{{$tab->text}}}</a></li>
	@endforeach
</ul>