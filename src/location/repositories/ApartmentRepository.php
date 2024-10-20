<?php

declare(strict_types=1);

namespace src\location\repositories;

use backend\forms\search\ApartmentSearch;
use src\location\entities\Apartment;
use src\location\entities\House;
use src\user\entities\User;
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

    public function findAllActiveWithRelations(): array
    {
        return Apartment::find()
            ->innerJoinWith('house.street.locality.region')
            ->andWhere(['apartment.deleted' => Apartment::STATUS_ACTIVE])
            ->all();
    }

    public function findByHouse(House $house, int $isDeleted = null): array
    {
        return Apartment::find()
            ->innerJoinWith('house')
            ->andWhere(['apartment.house_id' => $house->id])
            ->andFilterWhere(['apartment.deleted' => $isDeleted])
            ->all();
    }

    public function findActiveApartmentByUser(): array
    {
        return Apartment::find()
            ->innerJoinWith(['userTenants', 'house.street.locality.region'])
            ->andWhere(['apartment.deleted' => Apartment::STATUS_ACTIVE])
            ->andWhere(['user_tenant.user_id' => Yii::$app->user->id])
            ->andWhere(['user_tenant.is_active' => UserTenant::STATUS_ACTIVE])
            ->orderBy('apartment.number')
            ->all();
    }

    public function findTenantApartments(User $user): array
    {
        return Apartment::find()
            ->innerJoinWith(['userTenants', 'house.street.locality.region'])
            ->andWhere(['user_tenant.user_id' => $user->id])
            ->andWhere(['user_tenant.is_active' => UserTenant::STATUS_ACTIVE])
            ->andWhere(['apartment.deleted' => House::STATUS_ACTIVE])
            ->all();
    }

    public function findByHouseId(int $houseId): array
    {
        return Apartment::find()
            ->andWhere(['house_id' => $houseId])
            ->andWhere(['deleted' => Apartment::STATUS_ACTIVE])
            ->all();
    }

    public function getFormattedApartmentAddressesByUser(): array
    {
        $apartments = $this->findActiveApartmentByUser();

        $formattedApartmentAddresses = [];

        /** @var Apartment $apartment */
        foreach ($apartments as $apartment) {
            $addressParts = [
                $apartment->house->street->locality->region->name,
                $apartment->house->street->locality->name,
                $apartment->house->street->name,
                'д. ' . $apartment->house->number,
                'кв. ' . $apartment->number,
            ];

            $formattedApartmentAddresses[$apartment->id] = implode(', ', array_filter($addressParts));
        }

        return $formattedApartmentAddresses;
    }
}