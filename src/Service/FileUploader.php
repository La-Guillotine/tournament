<?php
// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDirectory(), $fileName);

        return $fileName;
    }

    public function removeUpload($file)
    {
        $file = $this->getTargetDirectory() . '/' . $file;
        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}