<?php

declare(strict_types=1);

namespace src\file\repositories;

use src\file\entities\NewsFile;
use src\news\entities\News;
use yii\db\Exception;

class NewsFileRepository
{
    public function save(NewsFile $newsFile): void
    {
        if (!$newsFile->save()) {
            $errors = implode(', ', $newsFile->getErrors());
            throw new Exception("Ошибка сохранения {$errors}.");
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