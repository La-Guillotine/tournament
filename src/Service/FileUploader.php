<?php
// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;
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

    public function uploadImage(File $file, string $folder): string
    {   
        $newFilename = md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->getTargetDirectory().'/'.$folder,
            $newFilename
        );
        return $newFilename;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}