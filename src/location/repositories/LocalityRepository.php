<?php

declare(strict_types=1);

namespace src\location\repositories;

use backend\forms\search\LocalitySearch;
use src\location\entities\Locality;
use yii\db\ActiveQuery;
use yii\db\Exception;

class LocalityRepository
{
    public function findById(int $id): ?Locality
    {
        return Locality::findOne(['id' => $id]);
    }

    public function save(Locality $locality): void
    {
        if (!$locality->save()) {
            throw new Exception('Ошибка сохранения.');
        }
    }

    public function getFilteredQuery(LocalitySearch $searchModel): ActiveQuery
    {
        return Locality::find()->andFilterWhere([
            'id' => $searchModel->id,
            'region_id' => $searchModel->region_id,
            'deleted' => $searchModel->deleted,
            'created_at' => $searchModel->created_at,
            'updated_at' => $searchModel->updated_at
        ])->andFilterWhere(['ilike', 'name', $searchModel->name]);
    }

    public function getNoResultsQuery(): ActiveQuery
    {
        return Locality::find()->where('0=1');
    }

    public function findActiveNamesWithId(): array
    {
        return Locality::find()
            ->select(['name', 'id'])
            ->andWhere(['deleted' => Locality::STATUS_ACTIVE])
            ->orderBy('name')
            ->asArray()
            ->all();
    }

    public function findByRegionId(int $regionId): array
    {
        return Locality::find()
            ->andWhere(['region_id' => $regionId])
            ->andWhere(['deleted' => Locality::STATUS_ACTIVE])
            ->all();
    }
}