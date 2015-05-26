<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Address;
use BitWasp\Bitcoin\Key\HierarchicalKeyFactory;

class CreateAddressCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'app:create-address';

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
		$math = Bitcoin::getMath();
		$network = Bitcoin::getNetwork();
		$master = HierarchicalKeyFactory::generateMasterKey();
		echo "Master key (m)\n";
		echo "   " . $master->toExtendedPrivateKey($network) . "\n";
		echo "   Address: " . $master->getPublicKey()->getAddress()->getAddress() . "\n\n";
		echo "Derive sequential keys:\n";
		$key1 = $master->deriveChild($master->getHardenedSequence(0));
		echo " - m/0' " . $key1->toExtendedPrivateKey($network) . "\n";
		echo "   Address: " . $key1->getPublicKey()->getAddress()->getAddress() . "\n\n";
		$key2 = $key1->deriveChild(999999);
		echo " - m/0'/999999 " . $key2->toExtendedPublicKey($network) . "\n";
		echo "   Address: " . $key2->getPublicKey()->getAddress()->getAddress() . "\n\n";
		echo "Directly derive path\n";
		$sameKey2 = $master->derivePath("0'/999999");
		echo " - m/0'/999999 " . $sameKey2->toExtendedPublicKey() . "\n";
		echo "   Address: " . $sameKey2->getPublicKey()->getAddress()->getAddress() . "\n\n";

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
