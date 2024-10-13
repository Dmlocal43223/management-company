<?php

declare(strict_types=1);

namespace src\ticket\services;

use Exception;
use RuntimeException;
use src\file\repositories\FileRepository;
use src\file\services\FileService;
use src\ticket\entities\Ticket;
use src\ticket\entities\TicketFile;
use src\ticket\repositories\TicketFileRepository;
use Yii;
use yii\web\UploadedFile;

class TicketFileService
{
    private TicketFileRepository $ticketFileRepository;
    private FileRepository $fileRepository;
    private FileService $fileService;

    public function __construct(TicketFileRepository $ticketFileRepository, FileRepository $fileRepository)
    {
        $this->ticketFileRepository = $ticketFileRepository;
        $this->fileRepository = $fileRepository;
        $this->fileService = new FileService($this->fileRepository);
    }

    public function create(Ticket $ticket, UploadedFile $file, int $fileTypeId): void
    {
        $hash = $this->fileService->generateHash($file);

        if ($this->fileRepository->existsByHashAndTicket($ticket, $hash)) {
            throw new RuntimeException("Файл {$file->baseName} уже загружен.");
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $file = $this->fileService->create($file, $hash, $fileTypeId);
            $ticketFile = TicketFile::create($ticket, $file);
            $this->ticketFileRepository->save($ticketFile);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }
}