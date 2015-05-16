@if(is_pgp_message($text))
<pre class="pgp_message">{{{$text}}}</pre>
@else
{{{$message->text}}}
@endif