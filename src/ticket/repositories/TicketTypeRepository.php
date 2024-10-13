<?php

declare(strict_types=1);

namespace src\ticket\repositories;

use backend\forms\search\TicketTypeSearch;
use src\ticket\entities\TicketType;
use yii\db\ActiveQuery;
use yii\db\Exception;

class TicketTypeRepository
{
    public function findById(int $id): ?TicketType
    {
        return TicketType::findOne($id);
    }

    public function save(TicketType $ticketType): void
    {
        if (!$ticketType->save()) {
            throw new Exception('Ошибка сохранения.');
        }
    }

    public function getFilteredQuery(TicketTypeSearch $searchModel): ActiveQuery
    {
        return TicketType::find()->andFilterWhere([
            'id' => $searchModel->id,
            'deleted' => $searchModel->deleted,
            'created_at' => $searchModel->created_at,
            'updated_at' => $searchModel->updated_at
        ])
            ->andFilterWhere(['ilike', 'name', $searchModel->name]);
    }

    public function getNoResultsQuery(): ActiveQuery
    {
        return TicketType::find()->where('0=1');
    }

    public function findActiveNameWithId(): array
    {
        return TicketType::find()
            ->select(['name', 'id'])
            ->andWhere(['deleted' => TicketType::STATUS_ACTIVE])
            ->orderBy('name')
            ->asArray()
            ->all();
    }
}