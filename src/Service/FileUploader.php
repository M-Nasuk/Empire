<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    /**
     * FileUploader constructor.
     * @param $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file, $name)
    {

        try {
            $a = explode('.', $file->getClientOriginalName());
            $e = end($a);
            switch ($e) {
                case 'mp3':
                    $newName = str_replace(" ", "_", $name).'.mp3';
                    $file->move(
                        $this->getTargetDirectory().'/music/',
                        $newName
                    );
                    break;
                case 'jpeg':
                    $newName = str_replace(" ", "_", $name).'.jpeg';
                    $file->move(
                        $this->getTargetDirectory().'/image/',
                        $newName
                    );
                    break;
            }
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $newName;
    }

    /**
     * @return mixed
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }


}