<?php

declare(strict_types=1);

namespace src\user\repositories;

use backend\forms\search\UserSearch;
use src\user\entities\User;
use yii\db\ActiveQuery;
use yii\db\Exception;

class UserRepository
{
    public function findById(int $id): ?User
    {
        return User::findOne($id);
    }

    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new Exception('Ошибка сохранения.');
        }
    }

    public function findByUsername(string $userName): ?User
    {
        return User::findOne(['username' => $userName, 'status' => User::STATUS_ACTIVE]);
    }

    public function getFilteredQuery(UserSearch $searchModel): ActiveQuery
    {
        return User::find()->andFilterWhere([
            'user.id' => $searchModel->id,
            'user.status' => $searchModel->status,
            'user.created_at' => $searchModel->created_at,
            'user.updated_at' => $searchModel->updated_at
        ])
            ->andFilterWhere(['ilike', 'user.username', $searchModel->username])
            ->andFilterWhere(['ilike', 'user.email', $searchModel->email]);
    }

    public function getNoResultsQuery(): ActiveQuery
    {
        return User::find()->where('0=1');
    }
}