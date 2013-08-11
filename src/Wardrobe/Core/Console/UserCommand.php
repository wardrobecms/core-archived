<?php namespace Wardrobe\Core\Console;

use Config, File;
use Illuminate\Console\Command;
use Wardrobe\Core\Repositories\DbUserRepository as UserRepositoryInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UserCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'wardrobe:user:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates a user for WardrobeCMS.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->userRepo = new UserRepositoryInterface;
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$first_name = $this->argument('first_name');
		$last_name = $this->argument('last_name');
		$email = $this->argument('email');
		$password = $this->argument('password');

		if ($this->userRepo->create($first_name, $last_name, $email, true, $password)) {
			$this->info('User Created Successfully');
			return;
		} else {
			return $this->error('User Could Not Be Created');
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('first_name', InputArgument::REQUIRED, 'User First Name.'),
			array('last_name', InputArgument::REQUIRED, 'User Last Name.'),
			array('email', InputArgument::REQUIRED, 'User Email.'),
			array('password', InputArgument::REQUIRED, 'User Password.'),
		);
	}
}
