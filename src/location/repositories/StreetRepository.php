<?php

declare(strict_types=1);

namespace src\location\repositories;

use backend\forms\search\StreetSearch;
use src\location\entities\Street;
use yii\db\ActiveQuery;
use yii\db\Exception;

class StreetRepository
{
    public function findById(int $id): ?Street
    {
        return Street::findOne(['id' => $id]);
    }

    public function save(Street $street): void
    {
        if (!$street->save()) {
            throw new Exception('Ошибка сохранения.');
        }
    }

    public function getFilteredQuery(StreetSearch $searchModel): ActiveQuery
    {
        return Street::find()->andFilterWhere([
            'id' => $searchModel->id,
            'locality_id' => $searchModel->locality_id,
            'deleted' => $searchModel->deleted,
            'created_at' => $searchModel->created_at,
            'updated_at' => $searchModel->updated_at
            ])->andFilterWhere(['ilike', 'name', $searchModel->name]);
    }

    public function getNoResultsQuery(): ActiveQuery
    {
        return Street::find()->where('0=1');
    }

    public function findActiveNamesWithId(): array
    {
        return Street::find()
            ->select(['name', 'id'])
            ->andWhere(['deleted' => Street::STATUS_ACTIVE])
            ->orderBy('name')
            ->asArray()
            ->all();
    }

    public function findByLocalityId(int $localityId): array
    {
        return Street::find()
            ->andWhere(['locality_id' => $localityId])
            ->andWhere(['deleted' => Street::STATUS_ACTIVE])
            ->all();
    }

    public function findAll(): array
    {
        return Street::find()->all();
    }
}