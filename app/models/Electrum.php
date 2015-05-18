<?php

class Electrum{
	
	public static function exec($command){
        $resultLines = [];	
		exec(ELECTRUM_PATH.' '.$command,$resultLines);
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

	public static function removeWallet(){
		if(file_exists(ELECTRUM_WALLET_PATH))
			unlink(ELECTRUM_WALLET_PATH);
	}

	public static function getMnemonic(){
		return self::exec('getseed')->mnemonic;
	}


}