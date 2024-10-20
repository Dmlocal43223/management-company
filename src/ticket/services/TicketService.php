<?php

declare(strict_types=1);

namespace src\ticket\services;

use backend\forms\TicketAssignForm;
use backend\forms\TicketCloseForm;
use common\forms\TicketFileForm;
use common\forms\TicketForm;
use Exception;
use RuntimeException;
use src\file\entities\FileType;
use src\file\repositories\FileRepository;
use src\location\repositories\ApartmentRepository;
use src\location\repositories\HouseRepository;
use src\notification\entities\NotificationType;
use src\notification\factories\NotificationTextGeneratorFactory;
use src\notification\repositories\NotificationTypeRepository;
use src\notification\services\NotificationSender;
use src\role\repositories\RoleRepository;
use src\ticket\entities\Ticket;
use src\ticket\entities\TicketStatus;
use src\ticket\repositories\TicketFileRepository;
use src\ticket\repositories\TicketHistoryRepository;
use src\ticket\repositories\TicketRepository;
use src\ticket\repositories\TicketStatusRepository;
use src\ticket\repositories\TicketTypeRepository;
use src\user\repositories\UserRepository;
use src\user\repositories\UserWorkerRepository;
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
    private HouseRepository $houseRepository;
    private UserRepository $userRepository;
    private NotificationTypeRepository $notificationTypeRepository;
    private TicketFileService $ticketFileService;
    private TicketHistoryService $ticketHistoryService;

    public function __construct(
        TicketRepository $ticketRepository,
        ApartmentRepository $apartmentRepository,
        UserWorkerRepository $userWorkerRepository,
        TicketTypeRepository $ticketTypeRepository,
        TicketStatusRepository $ticketStatusRepository,
        TicketHistoryRepository $ticketHistoryRepository,
        HouseRepository $houseRepository,
        NotificationTypeRepository $notificationTypeRepository,
        UserRepository $userRepository,
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
        $this->userRepository = $userRepository;
        $this->houseRepository = $houseRepository;
        $this->notificationTypeRepository = $notificationTypeRepository;
        $this->ticketFileService = new TicketFileService(new TicketFileRepository(), new FileRepository());
        $this->ticketHistoryService = new TicketHistoryService($this->ticketHistoryRepository, $this->ticketStatusRepository);
    }

    public function create(TicketForm $ticketForm, TicketFileForm $ticketFileForm): Ticket
    {
        $apartment = $this->apartmentRepository->findById((int)$ticketForm->apartment_id);
        $house = $apartment->house ?? $this->houseRepository->findById((int)$ticketForm->house_id);
        $ticketType = $this->ticketTypeRepository->findById((int)$ticketForm->type_id);
        $role = $this->roleRepository->getRoleForTicketAssignment($ticketType);
        $workers = $this->userWorkerRepository->findWorkersByHouseAndRole($house, $role);
        $worker = reset($workers);

        $ticket = Ticket::create(
            $ticketForm->description,
            $worker,
            $house->id,
            $apartment?->id,
            (int)$ticketForm->type_id
        );

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->ticketRepository->save($ticket);

            $this->ticketHistoryService->createNew($ticket);

            if ($worker) {
                $this->ticketHistoryService->createAssign($ticket, $worker);
            }

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
        $ticket->edit($form->description);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->ticketRepository->save($ticket);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function assign(Ticket $ticket, TicketAssignForm $form): void
    {
        $user = $this->userRepository->findById((int)$form->worker_id);

        if (!$user) {
            throw new RuntimeException("Пользователь {$form->worker_id} не найден");
        }

        $userWorker = $this->userWorkerRepository->findByUserAndHouse($user, $ticket->house);

        if (!$userWorker) {
            throw new RuntimeException("Работник {$form->worker_id} не найден");
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($worker = $ticket->worker) {
                $this->ticketHistoryService->createUnAssign($ticket, $worker);
                $notificationType = $this->notificationTypeRepository->findById(NotificationType::TYPE_UN_ASSIGN_TICKET_ID);
                $textGenerator = NotificationTextGeneratorFactory::create($notificationType, $ticket);
                $notificationSender = new NotificationSender($textGenerator, $worker, $notificationType);
                $notificationSender->sendSite();
                $notificationSender->sendEmail();
            }

            $ticket->assign($user);
            $this->ticketHistoryService->createAssign($ticket, $user);
            $this->ticketRepository->save($ticket);

            $notificationType = $this->notificationTypeRepository->findById(NotificationType::TYPE_ASSIGN_TICKET_ID);
            $textGenerator = NotificationTextGeneratorFactory::create($notificationType, $ticket);
            $notificationSender = new NotificationSender($textGenerator, $user, $notificationType);
            $notificationSender->sendSite();
            $notificationSender->sendEmail();

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
            if ($form->status_id == TicketStatus::STATUS_CLOSED_ID) {
                $ticket->close();
                $this->ticketHistoryService->createClose($ticket, $form->comment);

                $notificationType = $this->notificationTypeRepository->findById(NotificationType::TYPE_CLOSE_TICKET_ID);
                $textGenerator = NotificationTextGeneratorFactory::create($notificationType, $ticket, $form->comment);
                $notificationSender = new NotificationSender($textGenerator, $ticket->author, $notificationType);
                $notificationSender->sendSite();
                $notificationSender->sendEmail();
            } elseif ($form->status_id == TicketStatus::STATUS_CANCELED_ID) {
                $ticket->cancel();
                $this->ticketHistoryService->createCancel($ticket, $form->comment);

                $notificationType = $this->notificationTypeRepository->findById(NotificationType::TYPE_CANCEL_TICKET_ID);
                $textGenerator = NotificationTextGeneratorFactory::create($notificationType, $ticket, $form->comment);
                $notificationSender = new NotificationSender($textGenerator, $ticket->author, $notificationType);
                $notificationSender->sendSite();
                $notificationSender->sendEmail();
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
            $this->ticketHistoryService->createDeleted($ticket);
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
            $this->ticketHistoryService->createRestore($ticket);
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