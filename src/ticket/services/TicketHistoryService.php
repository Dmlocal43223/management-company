<?php

declare(strict_types=1);

namespace src\ticket\services;

use Exception;
use src\ticket\entities\Ticket;
use src\ticket\entities\TicketHistory;
use src\ticket\entities\TicketStatus;
use src\ticket\repositories\TicketHistoryRepository;
use src\ticket\repositories\TicketStatusRepository;
use src\user\entities\User;
use Yii;

class TicketHistoryService
{
    public TicketHistoryRepository $ticketHistoryRepository;
    private TicketStatusRepository $ticketStatusRepository;

    public function __construct(
        TicketHistoryRepository $ticketHistoryRepository,
        TicketStatusRepository $ticketStatusRepository
    )
    {
        $this->ticketHistoryRepository = $ticketHistoryRepository;
        $this->ticketStatusRepository = $ticketStatusRepository;
    }

    public function createNew(Ticket $ticket): TicketHistory
    {
        $ticketStatus = $this->ticketStatusRepository->findById(TicketStatus::STATUS_NEW_ID);
        $reason = 'Заявка создана.';
        $history = TicketHistory::create($ticket, $ticketStatus, $reason);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->ticketHistoryRepository->save($history);
            $transaction->commit();
            return $history;
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function createAssign(Ticket $ticket, User $worker): TicketHistory
    {
        $ticketStatus = $this->ticketStatusRepository->findById(TicketStatus::STATUS_PROCESSED_ID);
        $reason = "Назначен работник {$worker->username}[{$worker->id}].";
        $history = TicketHistory::create($ticket, $ticketStatus, $reason);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->ticketHistoryRepository->save($history);
            $transaction->commit();
            return $history;
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function createUnAssign(Ticket $ticket, User $worker): TicketHistory
    {
        $ticketStatus = $this->ticketStatusRepository->findById(TicketStatus::STATUS_NEW_ID);
        $reason = "Работник {$worker->username}[{$worker->id}] снят с заявки.";
        $history = TicketHistory::create($ticket, $ticketStatus, $reason);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->ticketHistoryRepository->save($history);
            $transaction->commit();
            return $history;
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function createClose(Ticket $ticket, string $comment): TicketHistory
    {
        $ticketStatus = $this->ticketStatusRepository->findById(TicketStatus::STATUS_CLOSED_ID);
        $reason = "Заявка закрыта. {$comment}";
        $history = TicketHistory::create($ticket, $ticketStatus, $reason);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->ticketHistoryRepository->save($history);
            $transaction->commit();
            return $history;
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function createCancel(Ticket $ticket, string $comment): TicketHistory
    {
        $ticketStatus = $this->ticketStatusRepository->findById(TicketStatus::STATUS_CANCELED_ID);
        $reason = "Заявка отменена. {$comment}";
        $history = TicketHistory::create($ticket, $ticketStatus, $reason);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->ticketHistoryRepository->save($history);
            $transaction->commit();
            return $history;
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function createDeleted(Ticket $ticket): TicketHistory
    {
        $ticketStatus = $this->ticketStatusRepository->findById(TicketStatus::STATUS_DELETED_ID);
        $reason = "Заявка удалена.";
        $history = TicketHistory::create($ticket, $ticketStatus, $reason);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->ticketHistoryRepository->save($history);
            $transaction->commit();
            return $history;
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function createRestore(Ticket $ticket): TicketHistory
    {
        $ticketStatus = $this->ticketStatusRepository->findById(TicketStatus::STATUS_NEW_ID);
        $reason = "Заявка восстановлена.";
        $history = TicketHistory::create($ticket, $ticketStatus, $reason);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->ticketHistoryRepository->save($history);
            $transaction->commit();
            return $history;
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

}