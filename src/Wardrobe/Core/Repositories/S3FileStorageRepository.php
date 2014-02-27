<?php namespace Wardrobe\Core\Repositories;

use Config, File;
use Illuminate\Support\Contracts\MessageProviderInterface;
use Aws\S3\S3Client;

class S3FileStorageRepository extends FileStorageRepository {

    /**
     * AWS S3 Client 
     *
     * @var AWS\S3\S3Client
     */
    protected $s3Client;

    public function __construct(MessageProviderInterface $messages)
    {
        parent::__construct($messages);

        $this->s3Client = S3Client::factory(array(
            'key'    => Config::get('core::wardrobe.s3_creds.api_key'),
            'secret' => Config::get('core::wardrobe.s3_creds.api_secret')
        ));
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
        $this->setDestinationPath($type);

        $file = File::put($this->destinationPath.$filename, $content);

        if(File::exists($this->destinationPath.$filename))
        {
            try
            {
                $result = $this->s3Client->putObject(array(
                    'Bucket' => Config::get('core::wardrobe.s3_creds.bucket'),
                    'Key'    => $this->storageDirectory."/".$filename,
                    'Body'   => fopen($this->destinationPath.$filename, 'r+'),
                    'ACL'    => 'public-read'
                ));

                $this->messages->add('filename', $result['ObjectURL']);
            }
            catch(Aws\S3\Exception\S3Exception $e)
            {
                $this->messages->add('error', 'Upload failed. Please verify your S3 settings in the config.');
            }

            File::delete($this->destinationPath.$filename);
        }
        else
        {
            $this->messages->add('error', 'Upload failed. Please ensure the ' . $this->destinationPath . ' directory is writable.');
        }

        return $this->messages->getMessageBag();
    }
}