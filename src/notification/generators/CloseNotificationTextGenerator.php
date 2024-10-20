<?php

declare(strict_types=1);

namespace src\notification\generators;

use src\notification\generators\interfaces\NotificationTextGeneratorInterface;
use src\ticket\entities\Ticket;

class CloseNotificationTextGenerator implements NotificationTextGeneratorInterface
{
    private Ticket $ticket;
    private string $comment;

    public function __construct(Ticket $ticket, string $comment)
    {
        $this->ticket = $ticket;
        $this->comment = $comment;
    }

    public function generateTitle(): string
    {
        return "Заявка {$this->ticket->number} закрыта";
    }

    public function generateBody(): string
    {
        return "Результат выполнения: {$this->comment}";
    }
}