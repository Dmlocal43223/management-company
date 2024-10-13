<?php

declare(strict_types=1);

namespace src\location\repositories;

use backend\forms\search\ApartmentSearch;
use src\location\entities\Apartment;
use src\location\entities\House;
use src\user\entities\UserTenant;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;

class ApartmentRepository
{
    public function findById(int $id): ?Apartment
    {
        return Apartment::findOne(['id' => $id]);
    }

    public function save(Apartment $apartment): void
    {
        if (!$apartment->save()) {
            throw new Exception('Ошибка сохранения.');
        }
    }

    public function getFilteredQuery(ApartmentSearch $searchModel): ActiveQuery
    {
        return Apartment::find()->andFilterWhere([
            'apartment.id' => $searchModel->id,
            'apartment.house_id' => $searchModel->house_id,
            'apartment.deleted' => $searchModel->deleted,
            'apartment.created_at' => $searchModel->created_at,
            'apartment.updated_at' => $searchModel->updated_at
        ])->andFilterWhere(['ilike', 'apartment.number', $searchModel->number]);
    }

    public function getNoResultsQuery(): ActiveQuery
    {
        return Apartment::find()->where('0=1');
    }

    public function findAll(): array
    {
        return Apartment::find()->all();
    }

    public function findByHouse(House $house, int $isDeleted = null): array
    {
        return Apartment::find()
            ->innerJoinWith('house')
            ->andWhere(['apartment.house_id' => $house->id])
            ->andFilterWhere(['apartment.deleted' => $isDeleted])
            ->all();
    }

    public function findActiveApartmentNumbersByUser(): array
    {
        return Apartment::find()
            ->innerJoinWith('userTenants')
            ->select(['apartment.number', 'id'])
            ->andWhere(['apartment.deleted' => Apartment::STATUS_ACTIVE])
            ->andWhere(['user_tenant.user_id' => Yii::$app->user->id])
            ->andWhere(['user_tenant.is_active' => UserTenant::STATUS_NOT_ACTIVE])
            ->orderBy('apartment.number')
            ->asArray()
            ->all();
    }
}