<?php

declare(strict_types=1);

namespace src\file\services;

use Exception;
use RuntimeException;
use src\file\entities\File;
use src\file\entities\FileType;
use src\file\repositories\FileRepository;
use Yii;
use yii\web\UploadedFile;

class FileService
{
    public const FILE_TYPE_TO_DIRECTORY_MAPPING = [
        FileType::PHOTO_TYPE_ID => 'photos',
        FileType::PREVIEW_TYPE_ID => 'previews',
        FileType::DOCUMENT_TYPE_ID => 'documents',
        FileType::AVATAR_TYPE_ID => 'avatars'
    ];

    private string $basePath;
    private FileRepository $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->basePath = Yii::getAlias('@webroot');
        $this->fileRepository = $fileRepository;
    }

    public function create(UploadedFile $file, string $hash, int $fileTypeId): File
    {
        $directoryPath = $this->getDirectoryPath($fileTypeId);
        $size = filesize($file->tempName);
        $source = $this->uploadFile($file, $directoryPath);
        $file = File::create($source, $hash, $size, $fileTypeId);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->fileRepository->save($file);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return $file;
    }

    public function uploadFile(UploadedFile $file, string $directoryPath): string
    {
        $filePath = $this->generateFilePath($file, $directoryPath);

        if (!$file->saveAs($filePath)) {
            throw new RuntimeException("Ошибка при сохранении файла: {$file->name} в путь: {$filePath}");
        }

        $relativePath = str_replace(Yii::getAlias('@webroot'), '', $filePath);
        return Yii::$app->request->hostInfo . $relativePath;
    }

    private function generateFilePath(UploadedFile $file, string $directoryPath): string
    {
        $fullPath = $this->basePath . '/' . $directoryPath . '/' . date('Y/m/d');
        $fileName = uniqid('file_', true) . '.' . $file->extension;
        $this->ensureDirectory($fullPath);

        return $fullPath . '/' . $fileName;
    }

    public function ensureDirectory(string $path): void
    {
        if (!is_dir($path)) {
            if (!mkdir($path, 0777, true) && !is_dir($path)) {
                throw new RuntimeException("Не удалось создать директорию: {$path}");
            }
        }
    }

    private function getDirectoryPath(int $fileTypeId): string
    {
        $directory = self::FILE_TYPE_TO_DIRECTORY_MAPPING[$fileTypeId]
            ?? throw new RuntimeException("Ошибка получения директории по типу файла {$fileTypeId}");

        return 'uploads/' . $directory;
    }

    public function remove(File $file): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $file->remove();
            $this->fileRepository->save($file);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function restore(File $file): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $file->restore();
            $this->fileRepository->save($file);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function generateHash(UploadedFile $file): string
    {
        return hash_file('sha256', $file->tempName) ?: throw new RuntimeException('Ошибка генерации хэша');
    }
}
