<?php

class Electrum{
	
	public static function exec($command){
        $resultLines = [];	
		exec('electrum '.$command,$resultLines);
		$result = implode(' ',$resultLines);
		$resultDecoded = json_decode($result,false);

		if($resultDecoded)
			return $resultDecoded;
		else
			return $result;
	}

	public static function create(){
		self::exec('create');
	}

	public static function getMnemonic(){
		return self::exec('getseed')->mnemonic;
	}


}