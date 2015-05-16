<?php

class Order extends \Eloquent {
	protected $fillable = ['pgp_public','quantity'];

	public function product(){
		return $this->belongsTo('Product')->withTrashed();;
	}

	public function messages(){
		return $this->hasMany('Message');
	}

	public function check(){
		$blockchain = get_blockchain();
		$balance_btc = $blockchain->Wallet->getAddressBalance($this->address)->balance;
		var_dump($balance_btc);
		$balance_btc = force_type($balance_btc,'float');
		
		$this->balance_btc = $balance_btc;
		if($balance_btc >= $this->total_amount_btc)
			$this->markAsPaid();
		$this->save();
	}

	public function getTotalAmountBtcAttribute(){
		return $this->product_amount_btc;
	}

	public function mark($status){
		switch($status){
			case 'paid':
				$this->markAsPaid();
				break;
			case 'shipped':
				$this->markAsShipped();
				break;
			case 'cancelled':
				$this->markAsCancelled();
				break;
		}
	}

	public function markAsPaid(){
		$this->is_paid = true;
		$this->save();
		
		$message = new Message;
		$message->sender = 'app';
		$message->template = 'paid';
		$this->messages()->save($message);
	}

	public function markAsShipped(){
		$this->is_shipped = true;
		$this->save();

		$message = new Message;
		$message->sender = 'app';
		$message->template = 'shipped';
		$this->messages()->save($message);
	}

	public function markAsCancelled(){
		$this->is_cancelled = true;
		$this->save();

		$message = new Message;
		$message->sender = 'app';
		if(Auth::check())
			$message->template = 'cancelled_vendor';
		else
			$message->template = 'cancelled_buyer';

		$this->messages()->save($message);
	}

	public function getStatusAttribute(){
		if($this->is_cancelled)
			return 'cancelled';
		if($this->is_shipped)
			return 'shipped';
		else if($this->is_paid)
			return 'paid';
		else
			return 'unpaid';
		return;
	}

	public function getStatusPrettyAttribute(){
		switch($this->status){
			case 'cancelled':
				return 'Cancelled';
				break;
			case 'shipped':
				return 'Shipped';
				break;
			case 'paid':
				return 'Paid but not shipped';
				break;
			case 'unpaid':
				return 'Unpaid';
				break;
			default:
				throw new Exception('Unknown status');
				break;
		}
	}

	public function getMessageUrlAttribute(){
		return URL::to("orders/{$this->id}/messages/create");
	}

	public static function getRules(){
		$rules = [
			'quantity'=>'integer|min:1'
			,'pgp_public'=>'required|pgp_public'
			,'text'=>'required|pgp_message'
//			,'donation_percentage'=>'required|integer|min:0'
			,'captcha'=>'required|captchaish'
		];
	
		return $rules;
	}

	public function getUrlAttribute(){
		if(Auth::guest())
			return $this->url_with_code;
		else
			return URL::to("/orders/{$this->id}");
	}

	public function getUrlWithCodeAttribute(){
		return URL::to("orders/{$this->id}?code={$this->code}");
	}

	public function getMarkUrlAttribute(){
		return URL::to("orders/{$this->id}/mark");
	}

	public static function queryByStatus($status){
		switch($status){
			case 'cancelled':
				return self::where('is_cancelled',1);
				break;
			case 'shipped':
				return self::where('is_shipped',1)
					->where('is_cancelled',0);
				break;
			case 'paid':
				return self::where('is_paid',1)
					->where('is_shipped',0)
					->where('is_cancelled',0);
				break;
			case 'unpaid':
				return self::where('is_paid',0)
					->where('is_shipped',0)
					->where('is_cancelled',0);
				break;
			default:
				throw new Exception('Unknown status');
				break;
		}
	}
}

Order::creating(function($order){
	$order->code = get_random_string(64);
});