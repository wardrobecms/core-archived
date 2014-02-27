<?php namespace Wardrobe\Core\Repositories;

use Auth, Hash, Validator, Wardrobe, Config;
use Illuminate\Support\Contracts\MessageProviderInterface;

class FileStorageRepository implements FileStorageRepositoryInterface {

    protected $storageDirectory;

    protected $destinationPath;

    protected $messsages;

    protected $storageDirectories;

    public function __construct(MessageProviderInterface $messages)
    {
        $this->messages = $messages;
        $this->storageDirectories = Config::get('core::wardrobe.storage_directories');
    }

    /**
     * Saves file in chosen storge 
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

    protected function setDestinationPath($type)
    {
        switch($type)
        {
            case "image":
                $this->storageDirectory = $this->storageDirectories['image'];
                break;
            default:
                $this->storageDirectory = $this->storageDirectories['default'];
                if($dir == "") $this->storageDirectory = "files";
                break;
        }

        $this->destinationPath = public_path(). "/{$this->storageDirectory}/";
    }
}
