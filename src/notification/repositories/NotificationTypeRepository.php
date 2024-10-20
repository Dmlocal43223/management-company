<?php

declare(strict_types=1);

namespace src\notification\repositories;

use backend\forms\search\NotificationTypeSearch;
use src\notification\entities\NotificationType;
use yii\db\ActiveQuery;
use yii\db\Exception;

class NotificationTypeRepository
{
    public function findById(int $id): ?NotificationType
    {
        return NotificationType::findOne($id);
    }

    public function save(NotificationType $notificationType): void
    {
        if (!$notificationType->save()) {
            throw new Exception('Ошибка сохранения.');
        }
    }

    public function getFilteredQuery(NotificationTypeSearch $searchModel): ActiveQuery
    {
        return NotificationType::find()->andFilterWhere([
            'id' => $searchModel->id,
            'deleted' => $searchModel->deleted,
            'created_at' => $searchModel->created_at,
            'updated_at' => $searchModel->updated_at
        ])
            ->andFilterWhere(['ilike', 'name', $searchModel->name]);
    }

    public function getNoResultsQuery(): ActiveQuery
    {
        return NotificationType::find()->where('0=1');
    }
}