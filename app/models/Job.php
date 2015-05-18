<?php

class Job extends \Eloquent {
	protected $fillable = ['name'];
	protected $dates = ['completed_at'];
	const MAXIMUM_JOBS = 100;

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
		$names = self::getUniqueNames();

		foreach($names as $name){
			$jobsCount = Job::where('name','=',$name)->count();
			if($jobsCount <= self::MAXIMUM_JOBS) continue;

			$name = mysqli::real_escape_string($name);
			$limit = (int) $jobsCount-self::MAXIMUM_JOBS;

			DB::delete("DELETE FROM jobs WHERE name = '{$name}' ORDER BY created_at ASC LIMIT $limit");
		}

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