<?php

declare(strict_types=1);

namespace src\ticket\services;

use backend\forms\TicketStatusForm;
use Exception;
use src\ticket\entities\TicketStatus;
use src\ticket\repositories\TicketStatusRepository;
use Yii;

class TicketStatusService
{
    private TicketStatusRepository $ticketStatusRepository;

    public function __construct(TicketStatusRepository $ticketStatusRepository)
    {
        $this->ticketStatusRepository = $ticketStatusRepository;
    }

    public function create(TicketStatusForm $form): TicketStatus
    {
        $fileType = TicketStatus::create($form->name);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->ticketStatusRepository->save($fileType);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return $fileType;
    }

    public function edit(TicketStatus $ticketStatus, TicketStatusForm $form): void
    {
        $ticketStatus->edit($form->name);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->ticketStatusRepository->save($ticketStatus);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function remove(TicketStatus $ticketStatus): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $ticketStatus->remove();
            $this->ticketStatusRepository->save($ticketStatus);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function restore(TicketStatus $ticketStatus): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $ticketStatus->restore();
            $this->ticketStatusRepository->save($ticketStatus);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }
}