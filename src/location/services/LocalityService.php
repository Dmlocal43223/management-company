<?php

declare(strict_types=1);

namespace src\location\services;

use backend\forms\LocalityForm;
use Exception;
use src\location\entities\Locality;
use src\location\repositories\LocalityRepository;
use Yii;

class LocalityService
{
    private LocalityRepository $localityRepository;

    public function __construct(LocalityRepository $localityRepository)
    {
        $this->localityRepository = $localityRepository;
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
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }
}