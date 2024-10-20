<?php

declare(strict_types=1);

namespace src\notification\services;

use Exception;
use src\notification\entities\NotificationType;
use src\notification\generators\interfaces\NotificationTextGeneratorInterface;
use src\notification\repositories\NotificationRepository;
use src\notification\repositories\NotificationTypeRepository;
use src\user\entities\User;
use Yii;

class NotificationSender
{
    private NotificationTextGeneratorInterface $textGenerator;
    private User $user;
    private NotificationType $notificationType;
    private NotificationService $notificationService;

    public function __construct(
        NotificationTextGeneratorInterface $textGenerator,
        User $user,
        NotificationType $notificationType
    )
    {
        $this->textGenerator = $textGenerator;
        $this->user = $user;
        $this->notificationType = $notificationType;
        $this->notificationService = new NotificationService(
            new NotificationRepository(),
            new NotificationTypeRepository()
        );
    }

    public function sendSite(): void
    {
        $title = $this->textGenerator->generateTitle();
        $body = $this->textGenerator->generateBody();
        $this->notificationService->create($title, $body, $this->user, $this->notificationType);
    }

    public function sendEmail(): void
    {
        return;
        $title = $this->textGenerator->generateTitle();
        $body = $this->textGenerator->generateBody();

        $sent = Yii::$app->mailer->compose()
            ->setFrom('goodworld724@gmail.com')
            ->setTo($this->user->email)
            ->setSubject($title)
            ->setTextBody($body)
            ->send();

        if (!$sent) {
            throw new Exception('Ошибка отправки сообщения.');
        }
    }
}