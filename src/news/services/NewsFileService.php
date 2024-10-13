<?php

declare(strict_types=1);

namespace src\news\services;

use Exception;
use RuntimeException;
use src\file\repositories\FileRepository;
use src\file\services\FileService;
use src\news\entities\News;
use src\news\entities\NewsFile;
use src\news\repositories\NewsFileRepository;
use Yii;
use yii\web\UploadedFile;

class NewsFileService
{
    private NewsFileRepository $newsFileRepository;
    private FileRepository $fileRepository;
    private FileService $fileService;

    public function __construct(NewsFileRepository $newsFileRepository, FileRepository $fileRepository)
    {
        $this->newsFileRepository = $newsFileRepository;
        $this->fileRepository = $fileRepository;
        $this->fileService = new FileService($this->fileRepository);
    }

    public function create(News $news, UploadedFile $file, int $fileTypeId): void
    {
        $hash = $this->fileService->generateHash($file);

        if ($this->fileRepository->existsByHashAndNews($news, $hash)) {
            throw new RuntimeException("Файл {$file->baseName} уже загружен.");
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $file = $this->fileService->create($file, $hash, $fileTypeId);
            $newsFile = NewsFile::create($news, $file);
            $this->newsFileRepository->save($newsFile);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }
}
