<?php namespace Wardrobe\Core\Controllers\Api;

use Wardrobe\Core\Controllers\BaseController;

use Response;
use Wardrobe\Core\Meta;
use Wardrobe\Core\Repositories\PostRepositoryInterface;

class MetaController extends BaseController {

  /**
   * The post repository implementation.
   *
   * @var \Wardrobe\PostRepositoryInterface  $posts
   */
  protected $posts;

  /**
   * Create a new API Meta controller.
   *
   * @param PostRepositoryInterface $posts
   *
   * @return ApiMetaController
   */
  public function __construct(PostRepositoryInterface $posts)
  {
    parent::__construct();

    $this->posts = $posts;

    $this->beforeFilter('auth');
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    return Response::json($this->posts->allMeta());
  }

}
