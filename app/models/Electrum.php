<?php

class Electrum{
	
	public static function exec($command){
		$results = exec('electrum '.$command);
		$resultsDecoded = json_decode($results,false);

		var_dump($results);

		if($resultsDecoded)
			return $resultsDecoded;
		else
			return $results;
	}

	public static function create(){
		self::exec('create');
	}

	public static function getMneumonic(){
		return self::exec('getseed')->mneumonic;
	}


}