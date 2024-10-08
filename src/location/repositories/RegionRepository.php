<?php

declare(strict_types=1);

namespace src\location\repositories;

use src\location\entities\Region;

class RegionRepository
{
    public function findById(int $id): ?Region
    {
        return Region::findOne(['id' => $id]);
    }

    public function findRegionNamesIndexedById(): array
    {
        return Region::find()
            ->select(['name', 'id'])
            ->orderBy('name')
            ->indexBy('id')
            ->column();
    }
}