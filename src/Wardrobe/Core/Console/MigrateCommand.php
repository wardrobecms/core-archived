<?php namespace Wardrobe\Core\Console;

use Config, File;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MigrateCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'wardrobe:migrate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Performs migrations for WardrobeCMS.';

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
	 * @return void
	 */
	public function fire()
	{
		$this->call('migrate', array('--package' => 'wardrobe/core', '--database' => 'wardrobe'));
	}
}
