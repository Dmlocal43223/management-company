<?php

declare(strict_types=1);

namespace src\user\repositories;

use src\user\entities\UserInformation;
use yii\db\Exception;

class UserInformationRepository
{
    public function findById(int $id): ?UserInformation
    {
        return UserInformation::findOne($id);
    }

    public function save(UserInformation $userInformation): void
    {
        if (!$userInformation->save()) {
            throw new Exception('Ошибка сохранения.');
        }
    }
}