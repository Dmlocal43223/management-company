<?php

declare(strict_types=1);

namespace src\notification\services;

use Exception;
use src\notification\entities\Notification;
use src\notification\entities\NotificationType;
use src\notification\repositories\NotificationRepository;
use src\notification\repositories\NotificationTypeRepository;
use src\user\entities\User;
use Yii;

class NotificationService
{
    private NotificationRepository $notificationRepository;
    private NotificationTypeRepository $notificationTypeRepository;

    public function __construct(
        NotificationRepository $notificationRepository,
        NotificationTypeRepository $notificationTypeRepository
    )
    {
        $this->notificationRepository = $notificationRepository;
        $this->notificationTypeRepository = $notificationTypeRepository;
    }

    public function create(string $title, string $body, User $user, NotificationType $type): Notification
    {
        $notification = Notification::create($title, $body, $user, $type);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->notificationRepository->save($notification);
            $transaction->commit();
            return $notification;
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function read(Notification $notification): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $notification->read();
            $this->notificationRepository->save($notification);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }
}