<?php namespace Wardrobe\Core\Controllers\Api;

use Wardrobe\Core\Controllers\BaseController;

use Input, Response;
use Carbon\Carbon;
use Wardrobe\Core\Repositories\PostRepositoryInterface;

class PostController extends BaseController {

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
	 * @return ApiPostController
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
		return (string) $this->posts->all();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$messages = $this->posts->validForCreation(Input::get('title'), Input::get('slug'));

		if (count($messages) > 0)
		{
			return Response::json($messages->all(), 400);
		}

		$date = (Input::get('publish_date') == "") ? "Now" : Input::get('publish_date');

		$post = $this->posts->create(
			Input::get('title'),
			Input::get('content'),
			Input::get('slug'),
			explode(',', Input::get('tags')),
			(bool) Input::get('active'),
			Input::get('user_id', $this->auth->user()->id),
			Carbon::createFromTimestamp(strtotime($date))
		);

		return (string) $this->posts->find($post->id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return $this->posts->find($id);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return (string) $this->posts->find($id);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$messages = $this->posts->validForUpdate($id, Input::get('title'), Input::get('slug'));

		if (count($messages) > 0)
		{
			return Response::json($messages->all(), 400);
		}

		$post = $this->posts->update(
			$id,
			Input::get('title'),
			Input::get('content'),
			Input::get('slug'),
			explode(',', Input::get('tags')),
			(bool) Input::get('active'),
			(int) Input::get('user_id'),
			Carbon::createFromTimestamp(strtotime(Input::get('publish_date')))
		);

		return (string) $this->posts->find($id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->posts->delete($id);
	}

}
