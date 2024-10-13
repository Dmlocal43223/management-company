<?php

declare(strict_types=1);

namespace src\ticket\services;

use backend\forms\TicketForm;
use Exception;
use frontend\forms\TicketFileForm;
use src\file\entities\FileType;
use src\file\repositories\FileRepository;
use src\ticket\entities\Ticket;
use src\ticket\repositories\TicketFileRepository;
use src\ticket\repositories\TicketRepository;
use Yii;

class TicketService
{
    private TicketRepository $ticketRepository;
    private TicketFileService $ticketFileService;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->ticketFileService = new TicketFileService(new TicketFileRepository(), new FileRepository());
    }

    public function create(TicketForm $ticketForm, TicketFileForm $ticketFileForm): Ticket
    {
        $ticket = Ticket::create(
            $ticketForm->description,
            $ticketForm->house_id,
            $ticketForm->apartment_id,
            $ticketForm->type_id
        );

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->ticketRepository->save($ticket);
            $this->saveFiles($ticket, $ticketFileForm);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return $ticket;
    }

    public function edit(Ticket $ticket, TicketForm $form): void
    {
        $ticket->edit($form->name);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->ticketRepository->save($ticket);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function remove(Ticket $ticket): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $ticket->remove();
            $this->ticketRepository->save($ticket);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function restore(Ticket $ticket): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $ticket->restore();
            $this->ticketRepository->save($ticket);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function saveFiles(Ticket $ticket, TicketFileForm $form): void
    {
        if ($form->photos) {
            foreach ($form->photos as $photo) {
                $this->ticketFileService->create($ticket, $photo, FileType::PHOTO_TYPE_ID);
            }
        }

        if ($form->documents) {
            foreach ($form->documents as $document) {
                $this->ticketFileService->create($ticket, $document, FileType::DOCUMENT_TYPE_ID);
            }
        }
    }
}