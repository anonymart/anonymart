<?php

class Message extends \Eloquent {
	protected $fillable = [];

	public static function getRules(){
		$rules = [
			'text'=>'required|pgp_message'
			,'captcha'=>'required|captchaish'
		];
	
		return $rules;
	}

	public function getTextAttribute(){
		if(!$this->template)
			return $this->attributes['text'];

		switch ($this->template) {
			case 'paid':
				return 'The buyer has paid the full order';
				break;
			case 'shipped':
				return Settings::get('site_name').' has shipped the order';
				break;
			case 'cancelled_buyer':
				return 'The buyer has cancelled the order';
				break;
			case 'cancelled_vendor':
				return Settings::get('site_name').' has cancelled the order';
				break;
			default:
				throw new Exception('Unkown message template');
				break;
		}
	}

	public function getHeadingAttribute(){
		switch($this->sender){
			case 'buyer':
				if(Auth::check())
					return 'The buyer wrote:';
				else
					return 'You wrote:';
				break;
			case 'vendor':
				if(Auth::check())
					return 'You wrote:';
				else
					return Settings::get('site_name').' wrote:';
				break;
			case 'app':
				return 'This is an automated message';
				break;
			default:
				throw new Exception('Unknown sender type');
				break;
		}

	}

	public function getClassAttribute(){
		if($this->sender == 'app')
			return 'panel-info';
		else if(Auth::check() && $this->sender == 'vendor' || Auth::guest() && $this->sender == 'buyer')
			return 'panel-success message-push-right';
		else
			return 'panel-primary message-push-left';

	}
}