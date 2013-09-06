<?php namespace Wardrobe\Core\Console;

use Config, File;
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
		$this->call('asset:publish', array('package' => 'wardrobe/core'));
		$assetPath = public_path().'/packages/wardrobe/core/themes';
		$themePath = public_path().'/'.Config::get('core::wardrobe.theme_dir');
		$this->copyDir($assetPath, $themePath);
	}

	protected function copyDir($from, $to)
	{
		$files = File::allFiles($from);

		$this->checkDirectory($to);

		foreach ($files as $file) {
			$original = (string) $file;
			$filename = $file->getRelativePathname();
			$this->checkDirectory("{$to}/{$file->getRelativePath()}");
			File::copy($file, "{$to}/{$filename}");
			$this->info("Copied {$filename}");
		}
	}

	protected function checkDirectory($dir)
	{
		if (!File::isDirectory($dir)) {
			File::makeDirectory($dir, 0777, true);
		}
	}
}
