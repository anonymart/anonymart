<?php

class Electrum{

	public function __construct($path,$walletPath,$usesSudo = false){
		$this->path = $path;
		$this->walletPath = $walletPath;
		$this->usesSudo = $usesSudo;
		$this->password = 'password';
	}
	
	public function exec($command,$inputs = null,$usesExistingWallet = true){
        $command = "{$this->path} $command";

        if($usesExistingWallet)
        	$command.="  --wallet={$this->walletPath}";

        if($this->usesSudo)
        	$command = "sudo $command";

        if($inputs !== null)
        	$command = 'echo -ne \''.implode('\n',$inputs).'\n\' | '.$command;

        var_dump($command);

        $resultLines = [];
		$lastResultLine = exec($command,$resultLines);
		$result = implode(' ',$resultLines);

		if(stripos($result,'Error:')!==FALSE)
			throw new Exception($result);

		$resultDecoded = json_decode($result,false);

		if($resultDecoded)
			return $resultDecoded;
		else
			return $lastResultLine;
	}

	public function create(){
		self::exec('create',[$this->password],false);
	}

	public function removeWallet(){
		if(file_exists($this->walletPath))
			unlink($this->walletPath);
	}

	public function restore($mnemonic){
		self::removeWallet();
		self::exec('restore',[$this->password,$mnemonic],false);
	}

	public function getMnemonic(){
		$seed = self::exec('getseed',[$this->password]);

		if(gettype($seed) ==='string')
			return explode(':',$seed)[1];
		else
			return $seed->mnemonic;
	}


}