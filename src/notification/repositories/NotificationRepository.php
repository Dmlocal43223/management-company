<?php

declare(strict_types=1);

namespace src\notification\repositories;

use src\notification\entities\Notification;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;

class NotificationRepository
{
    public function findById(int $id): ?Notification
    {
        return Notification::findOne($id);
    }

    public function save(Notification $notification): void
    {
        if (!$notification->save()) {
            throw new Exception('Ошибка сохранения.');
        }
    }

    public function getNoResultsQuery(): ActiveQuery
    {
        return Notification::find()->where('0=1');
    }

    public function getByUser(): ActiveQuery
    {
        return Notification::find()
            ->andWhere(['user_id' => Yii::$app->user->id])
            ->orderBy('id desc');
    }

    public function getUnReadNotificationCountByUser(int $userId): int
    {
        return Notification::find()
            ->andWhere(['is_read' => Notification::UN_READ])
            ->andWhere(['user_id' => $userId])
            ->count();
    }
}