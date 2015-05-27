<?PHP

Validator::extend('pgp_public', function($attribute, $value, $parameters){

    $value = trim($value);

    if(starts_with($value,PGP_PUBLIC_START)!==true)
    	return false;

    if(ends_with($value,PGP_PUBLIC_END)!==true)
    	return false;

    return true;
});

Validator::extend('pgp_message', function($attribute, $value, $parameters){
    return is_pgp_message($value);
});

Validator::extend('captchaish',function($attribute, $value, $parameters){
	if(Settings::get('is_testing')===true)
		return true;
	else
		return \Captcha::check($value);
});

Validator::extend('mpk', function($attribute, $value, $parameters){

    $value = trim($value);

    if(starts_with($value,MPK_START)!==true)
        return false;

    return true;
});
