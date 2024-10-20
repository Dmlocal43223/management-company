<?php

declare(strict_types=1);

namespace src\notification\factories;

use src\notification\entities\NotificationType;
use src\notification\generators\AssignNotificationTextGenerator;
use src\notification\generators\CancelNotificationTextGenerator;
use src\notification\generators\CloseNotificationTextGenerator;
use src\notification\generators\interfaces\NotificationTextGeneratorInterface;
use src\notification\generators\UnAssignNotificationTextGenerator;
use src\ticket\entities\Ticket;

class NotificationTextGeneratorFactory
{
    public static function create(NotificationType $notificationType, Ticket $ticket, ?string $comment = null): NotificationTextGeneratorInterface
    {
        return match ($notificationType->id) {
            NotificationType::TYPE_ASSIGN_TICKET_ID => new AssignNotificationTextGenerator($ticket),
            NotificationType::TYPE_UN_ASSIGN_TICKET_ID => new UnAssignNotificationTextGenerator($ticket),
            NotificationType::TYPE_CLOSE_TICKET_ID => new CloseNotificationTextGenerator($ticket, $comment),
            NotificationType::TYPE_CANCEL_TICKET_ID => new CancelNotificationTextGenerator($ticket, $comment)
        };
    }
}