<?php namespace Wardrobe\Core\Facades;

use Config, App, View;
use Illuminate\Auth\Guard;
use Illuminate\Auth\EloquentUserProvider;
use Wardrobe\Core\Repositories\PostRepositoryInterface;

class Wardrobe {

	/**
	 * The post repository implementation.
	 *
	 * @var Wardrobe\PostRepositoryInterface
	 */
	protected $postsRepo;

	/**
	 * Create a new wardrobe facade instance.
	 *
	 * @param \Wardrobe\Facades\Wardrobe\PostRepositoryInterface|\Wardrobe\Repositories\PostRepositoryInterface $postsRepo
	 *
	 * @return \Wardrobe\Facades\Wardrobe
	 */
	public function __construct(PostRepositoryInterface $postsRepo)
	{
		$this->postsRepo = $postsRepo;
	}

	/**
	 * Fetch Posts
	 *
	 * @param array $params
	 * @return Posts
	 */
	public function posts($params = array())
	{
		return $this->postsRepo->facadeSearch($params);
	}

	/**
	 * Fetch all tags
	 */
	public function tags()
	{
		return $this->postsRepo->allTags();
	}

	public function setupViews()
	{
		View::addLocation(public_path().'/'.Config::get('core::wardrobe.theme_dir'));
		foreach (Config::get('core::wardrobe.view_dirs') as $dir) {
			View::addLocation($dir);
		}
	}

	public function getWardrobeAuth()
	{
		$provider = $this->createEloquentProvider();

		$guard = new Guard($provider, App::make('session'));

		$guard->setCookieJar(App::make('cookie'));

		return $guard;
	}

	protected function createEloquentProvider()
	{
		$model = 'Wardrobe\Core\Models\User';

		return new EloquentUserProvider(App::make('hash'), $model);
	}

}
