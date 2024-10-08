<?php

declare(strict_types=1);

namespace src\news\services;

use backend\forms\NewsForm;
use src\file\entities\FileType;
use src\file\entities\NewsFile;
use src\file\services\FileService;
use src\news\entities\News;
use src\news\repositories\NewsRepository;

class NewsService
{
    private NewsRepository $newsRepository;
    private FileService $fileService;

    public function __construct(NewsRepository $newsRepository, FileService $fileService)
    {
        $this->newsRepository = $newsRepository;
        $this->fileService = $fileService;
    }

    public function create(NewsForm $form): News
    {
        $news = News::create(
            $form->title,
            $form->content
        );

        $this->newsRepository->save($news);

        $fileService = new FileService();

        if ($form->previewImage) {
            $file = $fileService->create($form->previewImage, FileType::PREVIEW_TYPE_ID);
            NewsFile::create($news, $file);
        }

        if ($form->photos) {
            foreach ($form->photos as $photo) {
                $file = $fileService->create($photo, FileType::PHOTO_TYPE_ID);
                NewsFile::create($news, $file);
            }
        }

        if ($form->documents) {
            $file = $fileService->create($form->documents, FileType::DOCUMENT_TYPE_ID);
            NewsFile::create($news, $file);
        }

        return $news;
    }
}
