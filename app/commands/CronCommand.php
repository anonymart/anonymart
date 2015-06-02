<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CronCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'app:cron';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
		$name = $this->argument('name');

		$job = new Job(['name'=>$name]);
		$job->save();

		try{
			switch($name){
				case 'update-rates':
					update_rates();
					break;
				case 'check-unpaid-orders':
					Order::checkUnpaidOrders();
					break;
				case 'expire-unpaid-orders':
					Order::expireUnpaidOrders();
					break;
				case 'clear-old-jobs':
					Job::clearOldJobs();
					break;
				case 'withdraw':
					withdraw();
					break;
				default:
					throw new Exception("Unkown job '$name'");
					break;
			}
		}catch(Exception $e){
			$job->markFailed($e->getMessage());
		}

		$job->markCompleted();

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'name'),
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
