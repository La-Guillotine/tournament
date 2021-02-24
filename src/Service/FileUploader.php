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

    public function upload(UploadedFile $file, string $folder)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDirectory().'/'.$folder, $fileName);

        return $fileName;
    }

    public function removeUpload(string $file, string $folder)
    {
        $file = $this->getTargetDirectory() . '/' .$folder. $file;
        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}