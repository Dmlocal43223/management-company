<?php

declare(strict_types=1);

namespace src\file\services;

use RuntimeException;
use src\file\entities\File;
use src\file\entities\FileType;
use src\file\repositories\FileRepository;
use Yii;
use yii\web\UploadedFile;

class FileService
{
    public const FILE_SOURCE_TYPE_MAPPING = [
        FileType::PHOTO_TYPE_ID => 'photos',
        FileType::PREVIEW_TYPE_ID => 'previews',
        FileType::DOCUMENT_TYPE_ID => 'documents',
    ];
    private string $basePath;

    public function __construct()
    {
        $this->basePath = Yii::getAlias('@webroot');
    }

    public function create(UploadedFile $file, int $fileTypeId): File
    {
        $fileRepository = new FileRepository();
        $path = 'uploads/' . self::FILE_SOURCE_TYPE_MAPPING[$fileTypeId];
        $this->ensureDirectory($path);
        $source = $this->uploadFile($file, $path);
        $user = Yii::$app->user;
        $file = File::create($source, $fileTypeId, $user);

        $fileRepository->save($file);

        return $file;
    }

    public function uploadFile(UploadedFile $file, string $path): string
    {
        $filePath = $this->generateFilePath($file, $path);

        if (!$file->saveAs($filePath)) {
            throw new RuntimeException('Ошибка при сохранении файла.');
        }

        return $filePath;
    }

    private function generateFilePath(UploadedFile $file, string $path): string
    {
        $fullPath = $this->basePath . '/' . $path;
        $fileName = uniqid() . '.' . $file->extension;

        return $fullPath . '/' . $fileName;
    }

    public function ensureDirectory(string $path): void
    {
        $fullPath = $this->basePath . '/' . $path;

        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0777, true);
        }
    }
}