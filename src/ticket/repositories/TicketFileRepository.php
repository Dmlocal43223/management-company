<?php

declare(strict_types=1);

namespace src\ticket\repositories;

use Exception;
use src\ticket\entities\TicketFile;

class TicketFileRepository
{
    public function save(TicketFile $ticketFile): void
    {
        if (!$ticketFile->save()) {
            $errors = implode(', ', $ticketFile->getErrors());
            throw new Exception("Ошибка сохранения {$errors}.");
        }
    }
}