@include('partials.tabs',[
	'tabId'=>$tabId
	,'tabs'=>[
		'general'=>new Tab('/settings/edit','General')
		,'electrum'=>new Tab('/settings/electrum','Electrum')
		,'password'=>new Tab('/settings/password','Password')
	]
])