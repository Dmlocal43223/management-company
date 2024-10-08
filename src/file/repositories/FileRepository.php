<?php

declare(strict_types=1);

namespace src\file\repositories;

use RuntimeException;
use src\file\entities\File;
use src\file\entities\FileType;
use src\news\entities\News;

class FileRepository
{
    public function findById(int $id): ?File
    {
        return File::findOne($id);
    }

    public function save(File $file): void
    {
        if (!$file->save()) {
            throw new RuntimeException('Ошибка сохранения.');
        }
    }

    public function remove(File $file): void
    {
        $file->remove();

        if (!$file->save()) {
            throw new RuntimeException('Ошибка удаления.');
        }
    }

    public function restore(File $file): void
    {
        $file->restore();

        if (!$file->save()) {
            throw new RuntimeException('Ошибка удаления.');
        }
    }

    public function existsByHashAndNews(News $news, string $hash): bool
    {
        return File::find()
            ->innerJoin('news_file', "news_file.file_id = file.id and news_file.news_id = {$news->id}")
            ->andWhere(['file.hash' => $hash])
            ->exists();
    }

    public function findFilesByNews(News $news, $isDeleted = null): array
    {
        return File::find()
            ->innerJoinWith('newsFiles')
            ->andWhere(['news_file.news_id' => $news->id])
            ->andFilterWhere(['file.deleted' => $isDeleted])
            ->all();
    }

    public function findPhotosByNews(News $news): array
    {
        return File::find()
            ->innerJoinWith('newsFiles')
            ->andWhere(['file.type_id' => FileType::PHOTO_TYPE_ID])
            ->andWhere(['news_file.news_id' => $news->id])
            ->andFilterWhere(['file.deleted' => File::STATUS_ACTIVE])
            ->all();
    }

    public function findDocumentsByNews(News $news): array
    {
        return File::find()
            ->innerJoinWith('newsFiles')
            ->andWhere(['file.type_id' => FileType::DOCUMENT_TYPE_ID])
            ->andWhere(['news_file.news_id' => $news->id])
            ->andFilterWhere(['file.deleted' => File::STATUS_ACTIVE])
            ->all();
    }
}