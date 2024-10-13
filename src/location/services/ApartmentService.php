<?php

declare(strict_types=1);

namespace src\location\services;

use backend\forms\ApartmentForm;
use Exception;
use src\location\entities\Apartment;
use src\location\entities\House;
use src\location\repositories\ApartmentRepository;
use Yii;

class ApartmentService
{
    private ApartmentRepository $apartmentRepository;

    public function __construct(ApartmentRepository $apartmentRepository)
    {
        $this->apartmentRepository = $apartmentRepository;
    }

    public function create(ApartmentForm $form): Apartment
    {
        $apartment = Apartment::create($form->number, (int)$form->house_id);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->apartmentRepository->save($apartment);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return $apartment;
    }

    public function edit(Apartment $apartment, ApartmentForm $form): void
    {
        $apartment->edit($form->number, (int)$form->street_id);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->apartmentRepository->save($apartment);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function remove(Apartment $apartment): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $apartment->remove();
            $this->apartmentRepository->save($apartment);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function restore(Apartment $apartment): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $apartment->restore();
            $this->apartmentRepository->save($apartment);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function createApartments(int $apartmentNumbers, House $house): void
    {
        $street = $house?->street;
        $locality = $street?->locality;
        $region = $locality?->region;

        for ($number = 1; $number <= $apartmentNumbers; $number++) {
            $apartmentForm = new ApartmentForm();
            $apartmentForm->number = (string)$number;
            $apartmentForm->region_id = $region?->id;
            $apartmentForm->locality_id = $locality?->id;
            $apartmentForm->street_id = $street?->id;
            $apartmentForm->house_id = $house->id;

            $this->create($apartmentForm);
        }
    }

    public function removeApartments(array $apartments): void
    {
        foreach ($apartments as $apartment) {
            $this->remove($apartment);
        }
    }

    public function restoreApartments(array $apartments): void
    {
        foreach ($apartments as $apartment) {
            $this->restore($apartment);
        }
    }
}