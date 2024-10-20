<?php

declare(strict_types=1);

namespace src\ticket\services;

use backend\forms\TicketForm;
use Exception;
use frontend\forms\TicketFileForm;
use RuntimeException;
use src\file\entities\FileType;
use src\file\repositories\FileRepository;
use src\location\repositories\ApartmentRepository;
use src\role\repositories\RoleRepository;
use src\ticket\entities\Ticket;
use src\ticket\entities\TicketStatus;
use src\ticket\repositories\TicketFileRepository;
use src\ticket\repositories\TicketHistoryRepository;
use src\ticket\repositories\TicketRepository;
use src\ticket\repositories\TicketStatusRepository;
use src\ticket\repositories\TicketTypeRepository;
use src\user\repositories\UserWorkerRepository;
use TicketCloseForm;
use Yii;

class TicketService
{
    private ApartmentRepository $apartmentRepository;
    private TicketRepository $ticketRepository;
    private UserWorkerRepository $userWorkerRepository;
    private RoleRepository $roleRepository;
    private TicketTypeRepository $ticketTypeRepository;
    private TicketStatusRepository $ticketStatusRepository;
    private TicketHistoryRepository $ticketHistoryRepository;
    private TicketFileService $ticketFileService;
    private TicketHistoryService $ticketHistoryService;

    public function __construct(
        TicketRepository $ticketRepository,
        ApartmentRepository $apartmentRepository,
        UserWorkerRepository $userWorkerRepository,
        TicketTypeRepository $ticketTypeRepository,
        TicketStatusRepository $ticketStatusRepository,
        TicketHistoryRepository $ticketHistoryRepository,
        RoleRepository $roleRepository
    )
    {
        $this->apartmentRepository = $apartmentRepository;
        $this->ticketRepository = $ticketRepository;
        $this->userWorkerRepository = $userWorkerRepository;
        $this->roleRepository = $roleRepository;
        $this->ticketTypeRepository = $ticketTypeRepository;
        $this->ticketStatusRepository = $ticketStatusRepository;
        $this->ticketHistoryRepository = $ticketHistoryRepository;
        $this->ticketFileService = new TicketFileService(new TicketFileRepository(), new FileRepository());
        $this->ticketHistoryService = new TicketHistoryService($this->ticketHistoryRepository, $this->ticketStatusRepository);
    }

    public function create(TicketForm $ticketForm, TicketFileForm $ticketFileForm): Ticket
    {
        $apartment = $this->apartmentRepository->findById((int)$ticketForm->apartment_id);
        $house = $apartment->house;
        $ticketType = $this->ticketTypeRepository->findById((int)$ticketForm->type_id);
        $role = $this->roleRepository->getRoleForTicketAssignment($ticketType);
        $worker = $this->userWorkerRepository->findWorkerByHouseAndRole($house, $role);

        $ticket = Ticket::create(
            $ticketForm->description,
            $worker,
            $house->id,
            $apartment->id,
            (int)$ticketForm->type_id
        );

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->ticketHistoryService->createNew($ticket);

            if ($worker) {
                $this->ticketHistoryService->createAssign($ticket, $worker);
            }

            $this->ticketRepository->save($ticket);
            $this->saveFiles($ticket, $ticketFileForm);
            $transaction->commit();
            return $ticket;
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
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

    public function close(Ticket $ticket, TicketCloseForm $form): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($form->status_id === TicketStatus::STATUS_CLOSED_ID) {
                $ticket->close();
                $this->ticketHistoryService->createClose($ticket, $form->comment);
            } elseif ($form->status_id === TicketStatus::STATUS_CANCEL_ID) {
                $ticket->cancel();
                $this->ticketHistoryService->createCancel($ticket, $form->comment);
            } else {
                throw new RuntimeException("Неизвестный статус {$form->status_id }");
            }

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