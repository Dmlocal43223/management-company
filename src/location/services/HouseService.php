<?php

declare(strict_types=1);

namespace src\location\services;

use backend\forms\HouseForm;
use Exception;
use src\location\entities\Apartment;
use src\location\entities\House;
use src\location\repositories\ApartmentRepository;
use src\location\repositories\HouseRepository;
use Yii;

class HouseService
{
    private HouseRepository $houseRepository;
    private ApartmentRepository $apartmentRepository;
    private ApartmentService $apartmentService;

    public function __construct(HouseRepository $houseRepository)
    {
        $this->houseRepository = $houseRepository;
        $this->apartmentRepository = new ApartmentRepository();
        $this->apartmentService = new ApartmentService($this->apartmentRepository);
    }

    public function create(HouseForm $form): House
    {
        $house = House::create($form->number, (int)$form->street_id);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->houseRepository->save($house);

            if ($form->is_create_apartments == HouseForm::CREATE_APARTMENTS_ENABLED) {
                $this->apartmentService->createApartments((int)$form->apartment_count, $house);
            }

            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return $house;
    }

    public function edit(House $house, HouseForm $form): void
    {
        $house->edit($form->number, (int)$form->street_id);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->houseRepository->save($house);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function remove(House $house): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $house->remove();
            $this->houseRepository->save($house);
            $apartments = $this->apartmentRepository->findByHouse($house, Apartment::STATUS_ACTIVE);
            $this->apartmentService->removeApartments($apartments);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function restore(House $house): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $house->restore();
            $this->houseRepository->save($house);
            $apartments = $this->apartmentRepository->findByHouse($house, Apartment::STATUS_DELETED);
            $this->apartmentService->restoreApartments($apartments);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function removeHouses(array $houses): void
    {
        foreach ($houses as $house) {
            $this->remove($house);
        }
    }

    public function restoreHouses(array $houses): void
    {
        foreach ($houses as $house) {
            $this->restore($house);
        }
    }
}