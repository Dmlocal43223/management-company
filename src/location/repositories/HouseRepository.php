<?php

declare(strict_types=1);

namespace src\location\repositories;

use backend\forms\search\HouseSearch;
use Exception;
use src\location\entities\House;
use src\user\entities\User;
use src\user\entities\UserWorker;
use yii\db\ActiveQuery;

class HouseRepository
{
    public function findById(int $id): ?House
    {
        return House::findOne(['id' => $id]);
    }

    public function save(House $house): void
    {
        if (!$house->save()) {
            throw new Exception('Ошибка сохранения.');
        }
    }

    public function getFilteredQuery(HouseSearch $searchModel): ActiveQuery
    {
        return House::find()->andFilterWhere([
            'id' => $searchModel->id,
            'street_id' => $searchModel->street_id,
            'deleted' => $searchModel->deleted,
            'created_at' => $searchModel->created_at,
            'updated_at' => $searchModel->updated_at
        ])->andFilterWhere(['ilike', 'number', $searchModel->number]);
    }

    public function getNoResultsQuery(): ActiveQuery
    {
        return House::find()->where('0=1');
    }

    public function findActiveNumbersWithId(): array
    {
        return House::find()
            ->select(['number', 'id'])
            ->andWhere(['deleted' => House::STATUS_ACTIVE])
            ->orderBy('number')
            ->asArray()
            ->all();
    }

    public function findByStreetId(int $streetId): array
    {
        return House::find()
            ->andWhere(['street_id' => $streetId])
            ->andWhere(['deleted' => House::STATUS_ACTIVE])
            ->all();
    }

    public function findAll(): array
    {
        return House::find()->all();
    }

    public function findWorkerHouses(User $user): array
    {
        return House::find()
            ->innerJoinWith(['userWorkers', 'street.locality.region'])
            ->andWhere(['user_worker.user_id' => $user->id])
            ->andWhere(['user_worker.is_active' => UserWorker::STATUS_ACTIVE])
            ->andWhere(['house.deleted' => House::STATUS_ACTIVE])
            ->all();
    }

    public function findAllActiveWithRelations(): array
    {
        return House::find()
            ->innerJoinWith('street.locality.region')
            ->andWhere(['house.deleted' => House::STATUS_ACTIVE])
            ->all();
    }
}