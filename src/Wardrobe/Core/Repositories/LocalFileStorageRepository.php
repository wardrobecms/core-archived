<?php namespace Wardrobe\Core\Repositories;

class LocalFileStorageRepository extends FileStorageRepository {

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
        $this->setDestinationPath($type);

        if(file_put_contents($this->destinationPath . $filename, $content) !== false)
        {
            $this->messages->add('filename', url($this->storageDirectory."/".$filename));
        }
        else
        {
            $this->messages->add('error', 'Upload failed. Please ensure the ' . $this->destinationPath . ' directory is writable.');
        }

        return $this->messages->getMessageBag();
    }
}
