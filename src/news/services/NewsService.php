<?php

declare(strict_types=1);

namespace src\news\services;

use backend\forms\NewsFileForm;
use backend\forms\NewsForm;
use Exception;
use RuntimeException;
use src\file\entities\File;
use src\file\entities\FileType;
use src\file\repositories\FileRepository;
use src\file\services\FileService;
use src\news\entities\News;
use src\news\entities\NewsFile;
use src\news\repositories\NewsFileRepository;
use src\news\repositories\NewsRepository;
use Yii;
use yii\web\UploadedFile;

class NewsService
{
    private FileService $fileService;
    private NewsRepository $newsRepository;
    private NewsFileRepository $newsFileRepository;
    private FileRepository $fileRepository;
    private NewsFileService $newsFileService;

    public function __construct(
        FileService $fileService,
        NewsRepository $newsRepository,
        NewsFileRepository $newsFileRepository,
        FileRepository $fileRepository
    )
    {
        $this->fileService = $fileService;
        $this->newsRepository = $newsRepository;
        $this->newsFileRepository = $newsFileRepository;
        $this->fileRepository = $fileRepository;
        $this->newsFileService = new NewsFileService($this->newsFileRepository, $this->fileRepository);
    }

    public function create(NewsForm $newsForm, NewsFileForm $newsFileForm): News
    {
        $news = News::create(
            $newsForm->title,
            $newsForm->content
        );

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->newsRepository->save($news);
            $this->saveFiles($news, $newsFileForm);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return $news;
    }

    public function edit(News $news, NewsForm $form): void
    {
        $news->edit($form->title, $form->content, $form->deleted);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->newsRepository->save($news);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function remove(News $news): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->newsRepository->remove($news);
            $files = $this->fileRepository->findFilesByNews($news, File::STATUS_ACTIVE);

            foreach ($files as $file) {
                $this->fileService->remove($file);
            }
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function restore(News $news): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->newsRepository->restore($news);
            $files = $this->fileRepository->findFilesByNews($news, File::STATUS_DELETED);

            foreach ($files as $file) {
                $this->fileService->restore($file);
            }
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function saveFiles(News $news, NewsFileForm $form): void
    {
        if ($form->previewImage) {
            $previewFile = $this->fileRepository->findFileByTypeForNews($news, FileType::PREVIEW_TYPE_ID);
            if ($previewFile) {
                $this->fileService->remove($previewFile);
            }

            $this->newsFileService->create($news, $form->previewImage, FileType::PREVIEW_TYPE_ID);
        }

        if ($form->photos) {
            foreach ($form->photos as $photo) {
                $this->newsFileService->create($news, $photo, FileType::PHOTO_TYPE_ID);
            }
        }

        if ($form->documents) {
            foreach ($form->documents as $document) {
                $this->newsFileService->create($news, $document, FileType::DOCUMENT_TYPE_ID);
            }
        }
    }
}