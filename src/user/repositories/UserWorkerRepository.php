<?php

declare(strict_types=1);

namespace src\user\repositories;

use src\location\entities\House;
use src\role\entities\Role;
use src\user\entities\User;
use src\user\entities\UserWorker;
use yii\db\Exception;

class UserWorkerRepository
{
    public function save(UserWorker $userWorker): void
    {
        if (!$userWorker->save()) {
            throw new Exception('Ошибка сохранения.');
        }
    }

    public function findByUserAndHouse(User $user, House $house): ?UserWorker
    {
        return UserWorker::find()
            ->andWhere(['user_worker.user_id' => $user->id])
            ->andWhere(['user_worker.house_id' => $house->id])
            ->one();
    }

    public function findWorkersByHouseAndRole(House $house, Role $role): array
    {
        return User::find()
            ->innerJoinWith(['userWorkers'])
            ->innerJoin('auth_assignments', 'auth_assignments.user_id = "user".id::text')
            ->andWhere(['user_worker.is_active' => UserWorker::STATUS_ACTIVE])
            ->andWhere(['user_worker.house_id' => $house->id])
            ->andWhere(['auth_assignments.item_name' => $role->name])
            ->andWhere(['user.status' => User::STATUS_ACTIVE])
            ->all();
    }

    public function findWorkersByHouse(House $house): array
    {
        return User::find()
            ->innerJoinWith('userWorkers')
            ->andWhere(['user_worker.is_active' => UserWorker::STATUS_ACTIVE])
            ->andWhere(['user_worker.house_id' => $house->id])
            ->all();
    }
}