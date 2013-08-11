<?php namespace Wardrobe\Core\Facades;

use Config;
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
		$per_page = isset($params['per_page']) ? $params['per_page'] : Config::get('wardrobe.per_page');

		return $this->postsRepo->active($per_page);
	}

	/**
	 * Fetch all tags
	 */
	public function tags()
	{
		return $this->postsRepo->allTags();
	}

	public function getWardrobeAuth()
	{
		$provider = $this->createEloquentProvider();

		return new Illuminate\Auth\Guard($provider, App::make('session'));
	}

	protected function createEloquentProvider()
	{
		$model = 'Wardrobe\Core\Models\User';

		return new Illuminate\Auth\EloquentUserProvider(App::make('hash'), $model);
	}

}
