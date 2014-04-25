<?php namespace Wardrobe\Core;

use Illuminate\Support\ServiceProvider;
use Config;

class WardrobeServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('wardrobe/core');
		$this->setConnection();
		$this->bindRepositories();
		$this->bootCommands();

		require_once __DIR__.'/../../themeHelpers.php';
		require_once __DIR__.'/../../routes.php';
		require_once __DIR__.'/../../filters.php';
	}

	/**
	 * Bind repositories.
	 *
	 * @return  void
	 */
	protected function bindRepositories()
	{
		$this->app->singleton('Wardrobe\Core\Repositories\PostRepositoryInterface', 'Wardrobe\Core\Repositories\DbPostRepository');

		$this->app->singleton('Wardrobe\Core\Repositories\UserRepositoryInterface', 'Wardrobe\Core\Repositories\DbUserRepository');

		$this->app->bind('Wardrobe', function()
		{
			return new \Wardrobe\Core\Facades\Wardrobe(new Repositories\DbPostRepository);
		});
	}

	protected function bootCommands()
	{
		$this->app['wardrobe.console.theme'] = $this->app->share(function($app)
		{
			return new Console\ThemeCommand;
		});

		$this->app['wardrobe.console.config'] = $this->app->share(function($app)
		{
			return new Console\ConfigCommand;
		});

		$this->app['wardrobe.console.migrate'] = $this->app->share(function($app)
		{
			return new Console\MigrateCommand;
		});

		$this->app['wardrobe.console.user'] = $this->app->share(function($app)
		{
			return new Console\UserCommand;
		});

		$this->commands('wardrobe.console.theme', 'wardrobe.console.config', 'wardrobe.console.migrate', 'wardrobe.console.user');
	}

	public function register(){}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

	/**
	 * Set up the db connection
	 *
	 * @return  void
	 */
	public function setConnection()
	{
		$connection = Config::get('core::database.default');

		if ($connection !== 'default')
		{
			$wardrobeConfig = Config::get('core::database.connections.'.$connection);
		}
		else
		{
			$connection = Config::get('database.default');
			$wardrobeConfig = Config::get('database.connections.'.$connection);
		}

		Config::set('database.connections.wardrobe', $wardrobeConfig);
	}

}
