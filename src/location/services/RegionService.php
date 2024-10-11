<?php

declare(strict_types=1);

namespace src\location\services;

use backend\forms\RegionForm;
use Exception;
use src\location\entities\Region;
use src\location\repositories\RegionRepository;
use Yii;

class RegionService
{
    private RegionRepository $regionRepository;

    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    public function create(RegionForm $form): Region
    {
        $region = Region::create($form->name);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->regionRepository->save($region);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return $region;
    }

    public function edit(Region $region, RegionForm $form): void
    {
        $region->edit($form->name);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->regionRepository->save($region);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function remove(Region $region): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $region->remove();
            $this->regionRepository->save($region);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function restore(Region $region): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $region->restore();
            $this->regionRepository->save($region);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }
}