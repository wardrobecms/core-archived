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

		require __DIR__.'/../../themeHelpers.php';
		require __DIR__.'/../../routes.php';
		require __DIR__.'/../../filters.php';
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

        $this->commands('wardrobe.console.theme');
	}

	public function register()
	{

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

	public function setConnection()
	{
		$connection = Config::get('core::database.default');

		if ($connection !== 'default') {
			$wardrobeConfig = Config::get('core::database.connections.'.$connection);
		} else {
			$connection = Config::get('database.default');
			$wardrobeConfig = Config::get('database.connections.'.$connection);
		}

		Config::set('database.connections.wardrobe', $wardrobeConfig);
	}

}
