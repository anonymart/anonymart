<tr>
	<td>Site Name</td>
	<td>{{Form::text('site_name',null,['class'=>'form-control'])}}</td>
</tr>
<tr>
	<td>Currency</td>
	<td>{{Form::select('currency',get_currency_options(),null,['class'=>'form-control'])}}</td>
</tr>
<tr>
	<td>Blockchain.info Guid</td>
	<td>{{Form::text('blockchain_guid',null,['class'=>'form-control'])}}</td>
</tr>
<tr>
	<td>Blockchain.info Password</td>
	<td>{{Form::text('blockchain_password',null,['class'=>'form-control'])}}</td>
</tr>
<tr>
	<td>Minimum Withdrawl (BTC)
		<p class="explainer">Smaller minimums reduce your risk, but result in higher mining fees.</p>
	</td>
	<td>{{Form::text('withdrawl_minimum_btc',null,['class'=>'form-control'])}}</td>
</tr>
<tr>
	<td>
		Cashout Address
		<p class="explainer">Where should we send your bitcoin?</p>
	</td>
	<td>{{Form::text('address',null,['class'=>'form-control'])}}</td>
</tr>
<tr>
	<td>
		Site Info
		<p class="explainer">Information about your site and your product. This field will be interepreted as markdown.</p>
	</td>
	<td>{{Form::textarea('site_info',null,['class'=>'form-control'])}}</td>
</tr>
<tr>
	<td>PGP Public Key
	</td>
	<td>
		{{Form::textarea('pgp_public',null,['class'=>'form-control'])}}
	</td>
</tr>
<tr>
	<td>Password</td>
	<td>{{Form::password('password',['class'=>'form-control'])}}</td>
</tr>
<tr>
	<td>Password Confirmation</td>
	<td>{{Form::password('password_confirmation',['class'=>'form-control'])}}</td>
</tr>