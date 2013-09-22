<?php namespace Wardrobe\Core\Controllers;

use Response;
use Wardrobe\Core\Repositories\PostRepositoryInterface;

class RssController extends BaseController {

	/**
	 * The post repository implementation.
	 *
	 * @var Wardrobe\PostRepositoryInterface
	 */
	protected $posts;

	/**
	 * Create a new API Posts controller.
	 *
	 * @param PostRepositoryInterface $posts
	 *
	 * @return RssController
	 */
	public function __construct(PostRepositoryInterface $posts)
	{
		parent::__construct();

		$this->posts = $posts;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$posts = $this->posts->active(100);

		$data = array(
			'posts'   => $posts,
			'updated' => isset($posts[0]) ? $posts[0]->atom_date : date('Y-m-d H:i:s'),
		);

		return Response::view($this->theme.'.atom', $data, 200, array(
			'Content-Type' => 'application/rss+xml; charset=UTF-8',
		));
	}

}
