<?php

class Settings extends \Eloquent {

	public function __construct(){

		if(!$this->is_set) return;

		$dataJson = File::get($this->path);
		$data = json_decode($dataJson);

		if(!$data) return;

		foreach($data as $key=>$value)
			$this->$key = $value;
	}

	public function getIsSetAttribute(){
		return File::exists($this->path);
	}

	public function put(){
		$data = [];
		$types_keys = array_keys($this->types);

		foreach($this->attributes as $attribute => $value)
			if(in_array($attribute, $types_keys))
				$data[$attribute] = force_type($value,$this->types[$attribute]);


		File::put($this->path,json_encode($data));
	}

	public function getPathAttribute(){
		return base_path().'/data/settings.json';
	}

	public static function make(){
		return new self;
	}

	public static function get($value){
		$settings = self::make();
		return $settings->$value;
	}

	public static function set($values){
		
		$settings = self::make();
		$keys = $settings->keys;
		
		foreach($values as $key=>$value)
			$settings->$key = $value;
		
		$settings->put();
	}

	public function getTypesAttribute(){
		return [
			'site_name'=>'string'
			,'tagline'=>'string'
			,'currency'=>'string'
			,'site_info'=>'string'
			,'address'=>'string'
			,'blockchain_guid'=>'string'
			,'blockchain_password'=>'string'
			,'wallet_maximum_btc'=>'string'
			,'pgp_public'=>'string'
		];
	}

	public function getRulesAttribute(){
		return [
			'site_name'=>'required'
			,'address'=>'required'
			,'currency'=>'required'
			,'blockchain_guid'=>'required'
			,'blockchain_password'=>'required'
			,'wallet_maximum_btc'=>'string'
			,'pgp_public'=>'required|pgp_public'
		];
	}

}