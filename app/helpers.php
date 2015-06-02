<?PHP

use BitWasp\BitcoinLib\BIP32;

function get_blockchain(){
	$blockchain = new \Blockchain\Blockchain();
	$blockchain->setUrl('https://blockchainbdgpzk.onion/');
	$blockchain->curl_setopt(CURLOPT_PROXY, "127.0.0.1:9050");
	$blockchain->curl_setopt(CURLOPT_PROXYTYPE, 7);
	$blockchain->curl_setopt(CURLOPT_RETURNTRANSFER, 1);
	$blockchain->curl_setopt(CURLOPT_VERBOSE, 0);
	return $blockchain;
}

function get_master(){
	$seed_buffer = get_seed_buffer();
    return BitWasp\Bitcoin\Key\HierarchicalKeyFactory::fromEntropy($seed_buffer);
}

function get_seed_buffer(){
	return BitWasp\Buffertools\Buffer::hex(Settings::get('seed'));
}

function update(){
	exec("/var/www/anonymart/bin/update.sh");
}

function get_form_boolean($name){
	return Form::select($name,[1=>'Yes',0=>'No'],null,['class'=>'form-control']);
}

function get_form_redirect($var,array $messages){
	return Redirect::back()->with($var,$messages)->withInput();
}

function round_amount($amount){
	return round($amount,AMOUNT_SCALE);
}

function get_currencies(){
	return array_keys(get_rates());
}

function get_address($index){
	$index = (int) $index;
	$mpk = Settings::get('mpk');
	return BIP32::build_address($mpk,"0/$index")[0];
}

function get_currency_options(){
	$options = [];
	
	foreach(get_currencies() as $currency)
		$options[$currency]=$currency;

	return $options;
}

function get_rate(){
	return get_rates()[Settings::get('currency')];
}

function convert_to_btc($amount_fiat){
	return bcdiv($amount_fiat,get_rate(),BC_SCALE);
}

function convert_to_fiat($amount_btc){
	return bcmul($amount_btc,get_rate(),BC_SCALE);
}

function get_rates(){
	return json_decode(file_get_contents(base_path().'/data/rates.json'),true);
}

function is_pgp_message($value){
	$value = trim($value);

    if(starts_with($value,PGP_MESSAGE_START)!==TRUE)
    	return false;

    if(ends_with($value,PGP_MESSAGE_END)!==TRUE)
    	return false;

    return true;
}

function force_type($var,$type){
	switch($type){
		case 'integer':
			return intval($var);
			break;
		case 'string':
			if(is_string($var)!==true)
				return '';
			else
				return $var;
			break;
		case 'boolean':
			return $var==='1';
			break;
		case 'float':
			return floatval($var);
			break;
		default:
			throw new Exception('Invalid type');
			break;
		}
}

function get_random_string($length = 32){
	
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $length; $i++)
        $randomString .= $characters[rand(0, strlen($characters)-1)];
    
    return $randomString;
}

function update_rates(){
	$blockchain = get_blockchain();
	$rates = $blockchain->Rates->get();
	
	if($rates===null)
		throw new Exception('Invalid rates data');

	$rates_clean = [];

	foreach($rates as $currency=>$rate)
		$rates_clean[$currency] = $rate->buy;

	file_put_contents(base_path().'/data/rates.json',json_encode($rates_clean));
}