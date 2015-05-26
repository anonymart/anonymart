<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SetMpkCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'app:set-mpk';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Set a new MPK command.';

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
		$master = BitWasp\Bitcoin\Key\HierarchicalKeyFactory::generateMasterKey();
		$mpk = $master->derivePath("0'/999999")->toExtendedPublicKey();
		Settings::set(['mpk'=>$mpk]);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
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
		);
	}

}
