<?php

declare(strict_types=1);

namespace src\ticket\services;

use backend\forms\TicketTypeForm;
use Exception;
use src\ticket\entities\TicketType;
use src\ticket\repositories\TicketTypeRepository;
use Yii;

class TicketTypeService
{
    private TicketTypeRepository $ticketTypeRepository;

    public function __construct(TicketTypeRepository $ticketTypeRepository)
    {
        $this->ticketTypeRepository = $ticketTypeRepository;
    }

    public function create(TicketTypeForm $form): TicketType
    {
        $type = TicketType::create($form->name);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->ticketTypeRepository->save($type);
            $transaction->commit();
            return $type;
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function edit(TicketType $ticketType, TicketTypeForm $form): void
    {
        $ticketType->edit($form->name);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->ticketTypeRepository->save($ticketType);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function remove(TicketType $ticketType): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $ticketType->remove();
            $this->ticketTypeRepository->save($ticketType);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function restore(TicketType $ticketType): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $ticketType->restore();
            $this->ticketTypeRepository->save($ticketType);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }
}