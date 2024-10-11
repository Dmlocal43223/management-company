<?php

declare(strict_types=1);

namespace src\file\services;

use backend\forms\FileTypeForm;
use Exception;
use src\file\entities\FileType;
use src\file\repositories\FileTypeRepository;
use Yii;

class FileTypeService
{
    private FileTypeRepository $fileTypeRepository;

    public function __construct(FileTypeRepository $fileTypeRepository)
    {
        $this->fileTypeRepository = $fileTypeRepository;
    }

    public function create(FileTypeForm $fileTypeForm): FileType
    {
        $fileType = FileType::create($fileTypeForm->name);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->fileTypeRepository->save($fileType);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return $fileType;
    }

    public function edit(FileType $fileType, FileTypeForm $form): void
    {
        $fileType->edit($form->name);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->fileTypeRepository->save($fileType);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function remove(FileType $fileType): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $fileType->remove();
            $this->fileTypeRepository->save($fileType);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function restore(FileType $fileType): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $fileType->restore();
            $this->fileTypeRepository->save($fileType);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }
}