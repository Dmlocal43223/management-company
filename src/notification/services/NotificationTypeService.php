<?php

declare(strict_types=1);

namespace src\notification\services;

use backend\forms\NotificationTypeForm;
use Exception;
use src\notification\entities\NotificationType;
use src\notification\repositories\NotificationTypeRepository;
use Yii;

class NotificationTypeService
{
    private NotificationTypeRepository $notificationTypeRepository;

    public function __construct(NotificationTypeRepository $notificationTypeRepository)
    {
        $this->notificationTypeRepository = $notificationTypeRepository;
    }

    public function create(NotificationTypeForm $form): NotificationType
    {
        $type = NotificationType::create($form->name);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->notificationTypeRepository->save($type);
            $transaction->commit();
            return $type;
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function edit(NotificationType $notificationType, NotificationTypeForm $form): void
    {
        $notificationType->edit($form->name);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->notificationTypeRepository->save($notificationType);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function remove(NotificationType $notificationType): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $notificationType->remove();
            $this->notificationTypeRepository->save($notificationType);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function restore(NotificationType $notificationType): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $notificationType->restore();
            $this->notificationTypeRepository->save($notificationType);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }
}