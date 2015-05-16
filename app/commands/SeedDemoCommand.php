<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SeedDemoCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'app:seed-demo';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Seed the demo.';

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

		Artisan::call('migrate:rollback --force');
		Artisan::call('migrate --force');

		$user = new User;
		$user->is_admin = 1;
		$user->username = 'admin';
		$user->password = $this->argument('password');
		$user->save();

		$settings = [
			'site_name'=>"Satoshi's Lemonade Stand"
			,'currency'=>'USD'
			,'address'=>'147BM4WmH17PPxhiH1kyNppWuyCAwn3Jm4'
			,'blockchain_guid'=>$this->argument('blockchain_guid')
			,'blockchain_password'=>$this->argument('blockchain_password')
			,'site_info'=>"## This is a demo for Anonymart\r\n\r\nNothing here is actually for sale, so you'll lose anything you spend here."
			,'pgp_public'=>
				'-----BEGIN PGP PUBLIC KEY BLOCK-----
				Version: BCPG C# v1.6.1.0

				mQENBFVXcGgBCACPqftNyL78wzlLOR8gGtVjI9y8s0y0s2YnZG2zkfXVY+AqnS4F
				lzAGKOZhYleZZWWEfBIVw64AXbom2uYI1Bg4scp0gMXFfWEqhzz9LcBUo5+J8ZKR
				/NA6m5OWL7n1DLM3VNUX2441pBC8e55JSQELFDO7DgDJwEq/2QsHgoEyb8DRzBUt
				xgMPm+SjEPkLHmNB1awllR6vjTK1UAy66uRefmzrIBQJVB7AcHHnDrF/eci9OtY0
				/QDwIR6v1IYb10fpFtJL4cPpkAk7ONzCt78CA1Mnx2nr7PSDSnWFFgFAbGvQgmbt
				0ZVYauTXQgX7hqD+OxxFlDo0mAuLZ6HjM4llABEBAAG0AIkBHAQQAQIABgUCVVdw
				aAAKCRC8JlL9Q1GWkhC0B/0av6bIG/W/hz0RsdhEthirGHH27ZIiugcPiYSxHScZ
				Hhx8U4cfHUhSBej70fQZ/lQJZTB9DjG6pbzVqLr1lyYZsTxysfWUH+6QQ0QLPEkI
				K/oTIfEkPoz81LVnJE1YbrG5EI3AWb96HG78QxK+aNN/DZBmdxA51Ts0dk1mvlPD
				x3I/kaKA1gUa/DGsGzrNOOdND0CPEwixwxGDjfxSGXi38ycBkBxoRDOcUCx5kd2q
				GN8wraTACCQNXbYz8c/yPF5Fe4k662pY/jeyzN4XxE8rzyYWLVm/19QzixtsRc8n
				CnBXLCfNAuBXBdUWEMC/iLSE1P3mGGHN7cAzB0EJTHke
				=SLpY
				-----END PGP PUBLIC KEY BLOCK-----'
		];
		file_put_contents(base_path().'/data/settings.json',json_encode($settings));

		(new Product([
			'title'=>'Original Lemonade'
			,'info'=>'Our classic beverage'
			,'amount_fiat'=>'.10'
		]))->save();

		(new Product([
			'title'=>'Rasberry Lemonade'
			,'info'=>'A tart twist'
			,'amount_fiat'=>'.15'
		]))->save();

		copy(base_path().'/assets/product_images/original.jpg',base_path().'/public/images/products/1');
		copy(base_path().'/assets/product_images/rasberry.jpg',base_path().'/public/images/products/2');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('password', InputArgument::REQUIRED, 'Site password.'),
			array('blockchain_guid', InputArgument::REQUIRED, 'Blockchain Guid.'),
			array('blockchain_password', InputArgument::REQUIRED, 'blockchain Password.'),
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
