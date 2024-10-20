<?php

declare(strict_types=1);

namespace src\notification\generators;

use src\notification\generators\interfaces\NotificationTextGeneratorInterface;
use src\ticket\entities\Ticket;

class AssignNotificationTextGenerator implements NotificationTextGeneratorInterface
{
    private Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function generateTitle(): string
    {
        return "На вас назначена заявка {$this->ticket->number}";
    }

    public function generateBody(): string
    {
        $text = "Номер: {$this->ticket->number}\n";
        $text .= "Тип: {$this->ticket->type->name}\n";
        $text .= "Описание: {$this->ticket->description}\n";
        $text .= "Дом: {$this->ticket->house->number}\n";

        if ($apartment = $this->ticket->apartment) {
            $text .= "Квартира: {$apartment->number}\n";
        }

        $text .= "Создал: {$this->ticket->author->getFullName()}\n";

        return $text;
    }
}