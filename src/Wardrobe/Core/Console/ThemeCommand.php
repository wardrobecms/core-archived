<?php namespace Wardrobe\Core\Console;

use Config;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ThemeCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'wardrobe:themes';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Publishes the default themes.';

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
		$assetPath = public_path().'/packages/wardrobe/core/themes';
		$themePath = public_path().'/'.Config::get('core::wardrobe.theme_dir');
		passthru("mv {$assetPath} {$themePath}");
	}

}
