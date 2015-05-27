<?php

class Product extends \Eloquent {
	use SoftDeletingTrait;

	protected $fillable = ['title','info','amount_fiat'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];


	public function orders(){
		return $this->hasMany('Order');
	}

	public function getAmountBtcAttribute(){
		return convert_to_btc($this->amount_fiat);
	}

	public function getDonationAmountFiatAttribute(){
		return bcmul($this->amount_fiat,.01 * Settings::get('donation_percentage'),BC_SCALE);
	}

	public function getTotalAmountFiatAttribute(){
		return $this->amount_fiat + $this->donation_amount_fiat;
	}

	public function getTotalAmountBtcAttribute(){
		return convert_to_btc($this->total_amount_fiat);
	}

	public function getPricesAttribute(){
		$currency = Settings::get('currency');
		return "{$this->total_amount_fiat} {$currency}, {$this->total_amount_btc} BTC";
	}

	public function getUrlAttribute(){
		return URL::to("products/{$this->id}");
	}

	public function getImageUrlAttribute(){
		return URL::to("images/products/{$this->id}");
	}

	public function getEditUrlAttribute(){
		return URL::to("products/{$this->id}/edit");
	}

	public function getDestroyUrlAttribute(){
		return URL::to("products/{$this->id}/destroy");
	}

	public function getOrderUrlAttribute(){
		return URL::to("products/{$this->id}/orders/create");
	}

	public static function getRules(){
		return [
			'title'=>'required'
			,'amount_fiat'=>'numeric'
			,'image'=>'image'
		];
	}

	public function getImagePathAttribute(){
		return base_path()."/public/images/products/{$this->id}";
	}

}