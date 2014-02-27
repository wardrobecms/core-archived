<?php namespace Wardrobe\Core\Controllers\Api;

use Wardrobe\Core\Controllers\BaseController;
use Wardrobe\Core\Repositories\FileStorageRepositoryInterface;

use Input, Config, Response, Exception, File;
use Carbon\Carbon;
use Symfony\Component\Yaml\Parser;
use Intervention\Image\Image;

class DropzoneController extends BaseController {

	/**
	 * The file storage driver
	 *
	 * @var Wardrobe\Core\Repositories\FileStorageRepositoryInterface
	 */
	protected $fileStorage;

	/**
	 * Create a new API Dropzone controller.
	 *
	 * @return \ApiDropzoneController
	 */
	public function __construct(FileStorageRepositoryInterface $fileStorage)
	{
		parent::__construct();

		$this->beforeFilter('wardrobe.auth');

		$this->fileStorage = $fileStorage;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @throws Exception
	 * @return Response
	 */
	public function postIndex()
	{
		if ( ! Input::hasFile('file'))
		{
			return Response::json(array('error' => 'File is required'), 400);
		}

		$contents = trim(File::get(Input::file('file')->getRealPath()));

		if (substr($contents, 0, 3) !== '---')
		{
			throw new Exception('Bad Markdown Formatting');
		}

		if ( ! ($pos = strpos($contents, '---', 3)))
		{
			throw new Exception('Bad Markdown Formatting');
		}

		$frontMatter = trim(substr($contents, 3, $pos - 3));
		$contents = trim(substr($contents, $pos + 3));

		$yaml = new Parser();

		$fields = $yaml->parse($frontMatter);

		return Response::json(array(
			'fields' => $fields,
			'content' => $contents
		));
	}

	/**
	 * Post an image from the admin
	 *
	 * @return Json
	 */
	public function postImage()
	{
		$file = Input::file('file');
		$filename = $file->getClientOriginalName();
		$resizeEnabled = Config::get('core::wardrobe.image_resize.enabled');

		//move the file to tmp location
		$file->move(sys_get_temp_dir(), 'Wardrobe_'.$filename);
		$tmpPath = sys_get_temp_dir().'/Wardrobe_'.$filename;
		
		if ($resizeEnabled)
		{
			$resizeWidth = Config::get('core::wardrobe.image_resize.width');
			$resizeHeight = Config::get('core::wardrobe.image_resize.height');
			$image = Image::make($tmpPath)->resize($resizeWidth, $resizeHeight, true);
			$image->save($tmpPath);
		}

		$result = $this->fileStorage->store(file_get_contents($tmpPath), $filename, "image");

		return Response::json($result);
	}
}
