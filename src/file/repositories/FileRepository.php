<?php

declare(strict_types=1);

namespace src\file\repositories;

use src\file\entities\File;
use src\file\entities\FileType;
use src\news\entities\News;
use src\ticket\entities\Ticket;
use src\user\entities\User;
use yii\db\Exception;

class FileRepository
{
    public function findById(int $id): ?File
    {
        return File::findOne($id);
    }

    public function save(File $file): void
    {
        if (!$file->save()) {
            $errors = get_class($file) . '. ' . implode(', ', $file->getErrors());
            throw new Exception("Ошибка сохранения {$errors}.");
        }
    }

    public function existsByHashAndNews(News $news, string $hash): bool
    {
        return File::find()
            ->innerJoin('news_file', "news_file.file_id = file.id and news_file.news_id = {$news->id}")
            ->andWhere(['file.hash' => $hash])
            ->andWhere(['file.deleted' => File::STATUS_ACTIVE])
            ->exists();
    }

    public function existsByHashAndTicket(Ticket $ticket, string $hash): bool
    {
        return File::find()
            ->innerJoin('ticket_file', "ticket_file.file_id = file.id and ticket_file.ticket_id = {$ticket->id}")
            ->andWhere(['file.hash' => $hash])
            ->andWhere(['file.deleted' => File::STATUS_ACTIVE])
            ->exists();
    }

    public function existsByHashAndUser(User $user, string $hash): bool
    {
        return File::find()
            ->innerJoin('user_information', "user_information.avatar_file_id = file.id and user_information.user_id = {$user->id}")
            ->andWhere(['file.hash' => $hash])
            ->andWhere(['file.deleted' => File::STATUS_ACTIVE])
            ->exists();
    }

    public function findFileByTypeForNews(News $news, int $fileTypeId): ?File
    {
        return File::find()
            ->innerJoin('news_file', "news_file.file_id = file.id and news_file.news_id = {$news->id}")
            ->andWhere(['file.type_id' => $fileTypeId])
            ->andWhere(['file.deleted' => File::STATUS_ACTIVE])
            ->one();
    }

    public function findFilesByNews(News $news, int $isDeleted = null): array
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