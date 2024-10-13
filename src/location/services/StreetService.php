<?php

declare(strict_types=1);

namespace src\location\services;

use backend\forms\StreetForm;
use Exception;
use src\location\entities\Street;
use src\location\repositories\StreetRepository;
use Yii;

class StreetService
{
    private StreetRepository $streetRepository;

    public function __construct(StreetRepository $streetRepository)
    {
        $this->streetRepository = $streetRepository;
    }

    public function create(StreetForm $form): Street
    {
        $locality = Street::create($form->name, (int)$form->locality_id);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->streetRepository->save($locality);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return $locality;
    }

    public function edit(Street $street, StreetForm $form): void
    {
        $street->edit($form->name, (int)$form->locality_id);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->streetRepository->save($street);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function remove(Street $street): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $street->remove();
            $this->streetRepository->save($street);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function restore(Street $street): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $street->restore();
            $this->streetRepository->save($street);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }
}