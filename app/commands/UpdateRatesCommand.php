<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateRatesCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'app:update-rates';
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update exchange rates.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$url = 'https://api.bitcoinaverage.com/all';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_PROXY, "http://127.0.0.1:9150/");
		// curl_setopt($ch, CURLOPT_PROXYTYPE, 7);
		$output = curl_exec($ch);
		$curl_error = curl_error($ch);
		curl_close($ch);

		$rates = json_decode($output,true);

		if($rates===null)
			throw new Exception('Invalid rates data');

		$rates_clean = [];

		foreach($rates as $currency=>$rate)
			if(isset($rate['averages']['ask']))
				$rates_clean[$currency] = $rate['averages']['ask'];

		file_put_contents(base_path().'/data/rates.json',json_encode($rates_clean));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
