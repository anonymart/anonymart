<tr>
	<td>Title</td>
	<td>
		{{Form::text('title',null,['class'=>'form-control'])}}
	</td>
</tr>
<tr>
	<td>Info
		<p class="explainer">This field will be interpreted as markdown.</p>
	</td>
	<td>
		{{Form::textarea('info',null,['class'=>'form-control'])}}
	</td>
</tr>
<tr>
	<td>
		Price ({{{Settings::get('currency')}}})
	</td>
	<td>
		{{Form::text('amount_fiat',null,['class'=>'form-control'])}}
	</td>
</tr>
<tr>
	<td>Image</td>
	<td>
		<div class="alert alert-info">Pictures may contain EXIF data that reveal your location or hardware information.</div>
		{{Form::file('image',['class'=>'form-control','id'=>'image'])}}
	</td>
</tr>