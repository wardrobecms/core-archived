<?php namespace Wardrobe\Core\Controllers\Api;

use Wardrobe\Core\Controllers\BaseController;

use Input, Config, Response, Exception, File;
use Carbon\Carbon;
use Symfony\Component\Yaml\Parser;
use Intervention\Image\Image;
use Aws\S3\S3Client;

class DropzoneController extends BaseController {

	/**
	 * The type of image storage to use.
	 *
	 * @var string
	 */
	protected $imageStorage;

	/**
	 * AWS S3 Client 
	 *
	 * @var AWS\S3\S3Client
	 */
	protected $s3Client;

	/**
	 * Create a new API Dropzone controller.
	 *
	 * @return \ApiDropzoneController
	 */
	public function __construct()
	{
		parent::__construct();

		$this->beforeFilter('wardrobe.auth');

		$this->imageStorage = Config::get('core::wardrobe.image_driver');

		if(strtolower($this->imageStorage) == "s3")
		{
			$this->s3Client = S3Client::factory(array(
			    'key'    => Config::get('core::wardrobe.image_s3_creds.api_key'),
			    'secret' => Config::get('core::wardrobe.image_s3_creds.api_secret')
			));
		}
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
		$imageDir = Config::get('core::wardrobe.image_dir');
		$destinationPath = public_path(). "/{$imageDir}/";
		$filename = $file->getClientOriginalName();
		$resizeEnabled = Config::get('core::wardrobe.image_resize.enabled');
		
		if ($resizeEnabled)
		{
			$resizeWidth = Config::get('core::wardrobe.image_resize.width');
			$resizeHeight = Config::get('core::wardrobe.image_resize.height');
			$image = Image::make($file->getRealPath())->resize($resizeWidth, $resizeHeight, true);
			$image->save($destinationPath.$filename);
		}
		else
		{
			$file->move($destinationPath, $filename);
		}

		if (File::exists($destinationPath.$filename))
		{
			// @note - If S3 is connected, move the resized image to it and delete the locally stored version
			if($this->s3Client)
			{
				$result = $this->s3Client->putObject(array(
				    'Bucket' => Config::get('core::wardrobe.image_s3_creds.bucket'),
				    'Key'    => $filename,
				    'Body'   => fopen($destinationPath.$filename, 'r+'),
				    'ACL'    => 'public-read'
				));

				File::delete($destinationPath.$filename);

				return Response::json(array('filename' => $result['ObjectURL']));
			}
			else
			{
				// @note - Using the absolute url so it loads images when ran in sub folder
				// this will make exporting less portable and may need to re-address at a later point.
				return Response::json(array('filename' => url("/{$imageDir}/".$filename)));
			}
		}
		return Response::json(array('error' => 'Upload failed. Please ensure your public/img directory is writable.'));
	}
}
