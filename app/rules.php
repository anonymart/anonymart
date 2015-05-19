<?PHP

Validator::extend('pgp_public', function($attribute, $value, $parameters){

    $value = trim($value);

    if(!starts_with($value,PGP_PUBLIC_START))
    	return false;

    if(!ends_with($value,PGP_PUBLIC_END))
    	return false;

    return true;
});

Validator::extend('pgp_message', function($attribute, $value, $parameters){
    return is_pgp_message($value);
});

Validator::extend('captchaish',function($attribute, $value, $parameters){
	if(is_testing())
		return true;
	else
		return \Captcha::check($value);
});