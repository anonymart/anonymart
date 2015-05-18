<?php

class Job extends \Eloquent {
	protected $fillable = ['name'];
	protected $dates = ['completed_at'];

	public function getDurationAttribute(){
		return $this->completed_at->diffInSeconds($this->created_at);
	}

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

	public static function clearOldJobs(){

	}

	public static function getUniqueNames(){
		$nameRows = self::select('name')->groupBy('name')->get()->toArray();
		$names = [];

		foreach($nameRows as $row)
			$names[] = $row['name'];

		return $names;
	}

	public static function getNameOptions(){
		$names = self::getUniqueNames();
		$jobNameOptions = [''=>'all jobs'];

		foreach($names as $name)
			$jobNameOptions[$name] = $name;

		return $jobNameOptions;
	}
}