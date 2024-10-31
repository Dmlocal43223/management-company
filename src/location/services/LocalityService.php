<?php

declare(strict_types=1);

namespace src\location\services;

use backend\forms\LocalityForm;
use Exception;
use src\location\entities\Locality;
use src\location\entities\Street;
use src\location\repositories\LocalityRepository;
use src\location\repositories\StreetRepository;
use Yii;

class LocalityService
{
    private LocalityRepository $localityRepository;
    private StreetRepository $streetRepository;
    private StreetService $streetService;

    public function __construct(LocalityRepository $localityRepository, StreetRepository $streetRepository, StreetService $streetService)
    {
        $this->localityRepository = $localityRepository;
        $this->streetRepository = $streetRepository;
        $this->streetService = $streetService;
    }

    public function create(LocalityForm $form): Locality
    {
        $locality = Locality::create($form->name, (int)$form->region_id);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->localityRepository->save($locality);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return $locality;
    }

    public function edit(Locality $locality, LocalityForm $form): void
    {
        $locality->edit($form->name, (int)$form->region_id);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->localityRepository->save($locality);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function remove(Locality $locality): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $locality->remove();
            $this->localityRepository->save($locality);
            $streets = $this->streetRepository->findByLocality($locality, Street::STATUS_ACTIVE);
            $this->streetService->removeStreets($streets);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function restore(Locality $locality): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $locality->restore();
            $this->localityRepository->save($locality);
            $streets = $this->streetRepository->findByLocality($locality, Street::STATUS_DELETED);
            $this->streetService->restoreStreets($streets);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function removeLocalities(array $localities): void
    {
        foreach ($localities as $locality) {
            $this->remove($locality);
        }
    }

    public function restoreLocalities(array $localities): void
    {
        foreach ($localities as $locality) {
            $this->restore($locality);
        }
    }
}