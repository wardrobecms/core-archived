<?php namespace Wardrobe\Core\Repositories;

use Auth, Hash, Validator, Wardrobe, Config;
use Illuminate\Support\Contracts\MessageProviderInterface;

class FileStorageRepository implements FileStorageRepositoryInterface {

    /**
     * The directory in which to store the files
     *
     * @var string
     */
    protected $storageDirectory;

    /**
     * The full destination path of the file
     *
     * @var string
     */
    protected $destinationPath;

    /**
     * The directory in which to store the files
     *
     * @var string
     */
    protected $messsages;

    /**
     * The array of storage directories keyed by file type in the Wardrobe config
     *
     * @var array
     */
    protected $storageDirectories;

    /**
     * Constructor
     *
     * @param  Illuminate\Support\Contracts\MessageProviderInterface  $messages
     * 
     * @return \Wardrobe\Core\Repositories\FileStorageRepositoryInterface
     */
    public function __construct(MessageProviderInterface $messages)
    {
        $this->messages = $messages;
        $this->storageDirectories = Config::get('core::wardrobe.storage_directories');
    }

    /**
     * Saves file in chosen storage 
     *
     * @param  string  $content
     * @param  string  $last_name
     * @param  string  $type
     * 
     * @return \Illuminate\Support\MessageBag
     */
    public function store($content, $filename, $type)
    {
        //@note - this is meant to be overridden.
        return $this->messages->getMessageBag();
    }

    /**
     * Sets destination path based on file type
     *
     * @param  string  $type
     * 
     * @return void
     */
    protected function setDestinationPath($type)
    {
        switch($type)
        {
            case "image":
                $this->storageDirectory = $this->storageDirectories['image'];
                if(!$this->storageDirectory || $this->storageDirectory == "") $this->storageDirectory = "images";
                break;
            default:
                $this->storageDirectory = $this->storageDirectories['default'];
                if(!$this->storageDirectory || $this->storageDirectory == "") $this->storageDirectory = "files";
                break;
        }

        $this->destinationPath = public_path(). "/{$this->storageDirectory}/";
    }
}
