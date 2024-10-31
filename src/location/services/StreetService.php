<?php

declare(strict_types=1);

namespace src\location\services;

use backend\forms\StreetForm;
use Exception;
use src\location\entities\House;
use src\location\entities\Street;
use src\location\repositories\HouseRepository;
use src\location\repositories\StreetRepository;
use Yii;

class StreetService
{
    private StreetRepository $streetRepository;
    private HouseRepository $houseRepository;
    private HouseService $houseService;

    public function __construct(StreetRepository $streetRepository, HouseRepository $houseRepository, HouseService $houseService)
    {
        $this->streetRepository = $streetRepository;
        $this->houseRepository = $houseRepository;
        $this->houseService = $houseService;
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
            $houses = $this->houseRepository->findByStreet($street, House::STATUS_ACTIVE);
            $this->houseService->removeHouses($houses);
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
            $houses = $this->houseRepository->findByStreet($street, House::STATUS_DELETED);
            $this->houseService->restoreHouses($houses);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function removeStreets(array $streets): void
    {
        foreach ($streets as $street) {
            $this->remove($street);
        }
    }

    public function restoreStreets(array $streets): void
    {
        foreach ($streets as $street) {
            $this->restore($street);
        }
    }
}