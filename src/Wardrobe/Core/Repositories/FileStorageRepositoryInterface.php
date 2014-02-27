<?php namespace Wardrobe\Core\Repositories;

interface FileStorageRepositoryInterface {

    /**
     * Saves file in chosen storge 
     *
     * @param  string  $content
     * @param  string  $last_name
     * @param  string  $type
     * 
     * @return \Illuminate\Support\MessageBag
     */
    public function store($content, $filename, $type);
}
