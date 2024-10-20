<?php

declare(strict_types=1);

namespace src\user\repositories;

use src\location\entities\Apartment;
use src\user\entities\User;
use src\user\entities\UserTenant;
use yii\db\Exception;

class UserTenantRepository
{
    public function save(UserTenant $userTenant): void
    {
        if (!$userTenant->save()) {
            throw new Exception('Ошибка сохранения.');
        }
    }

    public function findByUserAndApartment(User $user, Apartment $apartment): ?UserTenant
    {
        return UserTenant::find()
            ->andWhere(['user_tenant.user_id' => $user->id])
            ->andWhere(['user_tenant.apartment_id' => $apartment->id])
            ->one();
    }
}