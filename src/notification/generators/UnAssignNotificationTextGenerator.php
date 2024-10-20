<?php

declare(strict_types=1);

namespace src\notification\generators;

use src\notification\generators\interfaces\NotificationTextGeneratorInterface;
use src\ticket\entities\Ticket;

class UnAssignNotificationTextGenerator implements NotificationTextGeneratorInterface
{
    private Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function generateTitle(): string
    {
        return "С вас снята заявка {$this->ticket->number}";
    }

    public function generateBody(): string
    {
        return 'Вы больше не выполняете данную заявку.';
    }
}