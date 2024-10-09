<?php

declare(strict_types=1);

namespace src\file\repositories;

use RuntimeException;
use src\file\entities\File;
use src\file\entities\NewsFile;
use src\news\entities\News;

class NewsFileRepository
{
    public function save(NewsFile $newsFile): void
    {
        if (!$newsFile->save()) {
            $errors = implode(', ', $newsFile->getErrors());
            throw new RuntimeException("Ошибка сохранения {$errors}.");
        }
    }

    public function findFilesByNews(News $news, $isDeleted = null): array
    {
        return NewsFile::find()
            ->innerJoinWith('file')
            ->andWhere(['news_file.news_id' => $news->id])
            ->andFilterWhere(['file.deleted' => $isDeleted])
            ->all();
    }
}