<?php

class Job extends \Eloquent {
	protected $fillable = ['name'];
	protected $dates = ['completed_at'];

	public function markCompleted(){
		$this->completed_at = new DateTime;
		$this->save();
	}

	public function markFailed($message = null){
		if(!$this->completed_at) $this->completed_at = new DateTime;
		$this->is_failed = true;
		$this->message = $message;
		$this->save();
	}
}