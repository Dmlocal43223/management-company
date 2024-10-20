<?php

declare(strict_types=1);

namespace src\ticket\repositories;

use src\ticket\entities\Ticket;
use src\ticket\entities\TicketHistory;
use yii\db\Exception;

class TicketHistoryRepository
{
    public function findById(int $id): ?TicketHistory
    {
        return TicketHistory::findOne($id);
    }

    public function save(TicketHistory $ticketHistory): void
    {
        if (!$ticketHistory->save()) {
            throw new Exception('Ошибка сохранения.');
        }
    }
}