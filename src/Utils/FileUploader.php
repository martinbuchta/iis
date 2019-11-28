<?php

namespace App\Utils;

use App\Entity\Play;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /**
     * @var string
     */
    private $targetDir;

    public function __construct(string $targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function uploadFile(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = preg_replace('/[^a-z0-9]+/', '-', strtolower($originalFilename));
        $fileName = $safeFilename.'-'.uniqid('', true).'.'.$file->guessExtension();

        try {
            $file->move($this->targetDir, $fileName);
        } catch (FileException $e) {
            throw new \Exception("Soubor nelze nahrát.");
        }

        return $fileName;
    }

    public function removeImage(string $image): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->targetDir.'/'.$image);
    }
}
