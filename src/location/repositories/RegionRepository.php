<?php

declare(strict_types=1);

namespace src\location\repositories;

use backend\forms\search\RegionSearch;
use src\location\entities\Locality;
use src\location\entities\Region;
use yii\db\ActiveQuery;
use yii\db\Exception;

class RegionRepository
{
    public function findById(int $id): ?Region
    {
        return Region::findOne(['id' => $id]);
    }

    public function save(Region $region): void
    {
        if (!$region->save()) {
            throw new Exception('Ошибка сохранения.');
        }
    }

    public function findActiveNamesWithId(): array
    {
        return Region::find()
            ->select(['name', 'id'])
            ->andWhere(['deleted' => Locality::STATUS_ACTIVE])
            ->orderBy('name')
            ->asArray()
            ->all();
    }

    public function getFilteredQuery(RegionSearch $searchModel): ActiveQuery
    {
        return Region::find()->andFilterWhere([
            'id' => $searchModel->id,
            'deleted' => $searchModel->deleted,
            'created_at' => $searchModel->created_at,
            'updated_at' => $searchModel->updated_at
            ])->andFilterWhere(['ilike', 'name', $searchModel->name]);
    }

    public function getNoResultsQuery(): ActiveQuery
    {
        return Region::find()->where('0=1');
    }

    public function findAll(): array
    {
        return Region::find()->all();
    }
}