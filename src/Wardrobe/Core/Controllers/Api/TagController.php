<?php namespace Wardrobe\Core\Controllers\Api;

use Wardrobe\Core\Controllers\BaseController;

use Response;
use Wardrobe\Core\Tag;
use Wardrobe\Core\Repositories\PostRepositoryInterface;

class TagController extends BaseController {

	/**
	 * The post repository implementation.
	 *
	 * @var \Wardrobe\PostRepositoryInterface  $posts
	 */
	protected $posts;

	/**
	 * Create a new API Tag controller.
	 *
	 * @param PostRepositoryInterface $posts
	 *
	 * @return ApiTagController
	 */
	public function __construct(PostRepositoryInterface $posts)
	{
		parent::__construct();

		$this->posts = $posts;

		$this->beforeFilter('wardrobe.auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Response::json($this->posts->allTags());
	}

}
