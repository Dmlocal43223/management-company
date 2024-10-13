<?php

declare(strict_types=1);

namespace src\ticket\repositories;

use backend\forms\search\TicketStatusSearch;
use src\ticket\entities\TicketStatus;
use yii\db\ActiveQuery;
use yii\db\Exception;

class TicketStatusRepository
{
    public function findById(int $id): ?TicketStatus
    {
        return TicketStatus::findOne($id);
    }

    public function save(TicketStatus $ticketStatus): void
    {
        if (!$ticketStatus->save()) {
            throw new Exception('Ошибка сохранения.');
        }
    }

    public function getFilteredQuery(TicketStatusSearch $searchModel): ActiveQuery
    {
        return TicketStatus::find()->andFilterWhere([
            'id' => $searchModel->id,
            'deleted' => $searchModel->deleted,
            'created_at' => $searchModel->created_at,
            'updated_at' => $searchModel->updated_at
        ])
            ->andFilterWhere(['ilike', 'name', $searchModel->name]);
    }

    public function getNoResultsQuery(): ActiveQuery
    {
        return TicketStatus::find()->where('0=1');
    }
}