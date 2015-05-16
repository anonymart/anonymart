@if(Settings::get('pgp_public'))
	<h4>{{{Settings::get('site_name')}}}'s Public Key</h4>
	<pre class="pgp_public">{{{Settings::get('pgp_public')}}}</pre>
@else
	<h4>{{{Settings::get('site_name')}}} has not set a public key</h4>
@endif