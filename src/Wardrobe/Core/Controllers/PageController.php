<?php namespace Wardrobe\Core\Controllers;

use View, Config, Input, App;
use Wardrobe\Core\Repositories\PostRepositoryInterface;

class PageController extends BaseController {

	/**
	 * Create a new API Pages controller.
	 *
	 * @return PageController
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param string $slug
	 *
	 * @return Response
	 */
	public function show($slug)
	{
		try
		{
			return View::make($this->theme.'/pages/'.$slug);
		}
		catch (\InvalidArgumentException $e)
		{
			return App::abort(404, 'Page not found');
		}
	}
}
