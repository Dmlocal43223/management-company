<?php

declare(strict_types=1);

namespace src\ticket\repositories;

use backend\forms\search\TicketSearch;
use src\ticket\entities\Ticket;
use src\user\entities\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;

class TicketRepository
{
    public function findById(int $id): ?Ticket
    {
        return Ticket::findOne($id);
    }

    public function save(Ticket $ticket): void
    {
        if (!$ticket->save()) {
            $errors =  implode(' ', $ticket->getErrorSummary(true));
            throw new Exception("Ошибка сохранения. {$errors}");
        }

    }

    public function getFilteredQuery(TicketSearch $searchModel): ActiveQuery
    {
        return Ticket::find()->andFilterWhere([
            'id' => $searchModel->id,
            'status_id' => $searchModel->status_id,
            'house_id' => $searchModel->house_id,
            'apartment_id' => $searchModel->apartment_id,
            'type_id' => $searchModel->type_id,
            'created_user_id' => $searchModel->created_user_id,
            'deleted' => $searchModel->deleted,
            'closed_at' => $searchModel->closed_at,
            'created_at' => $searchModel->created_at,
            'updated_at' => $searchModel->updated_at
        ])
            ->andFilterWhere(['ilike', 'name', $searchModel->number])
            ->andFilterWhere(['ilike', 'description', $searchModel->description]);
    }

    public function getFilteredQueryByUser(TicketSearch $searchModel): ActiveQuery
    {
        return Ticket::find()
            ->andWhere(['created_user_id' => Yii::$app->user->id])
            ->andWhere(['deleted' => Ticket::STATUS_ACTIVE])
            ->andFilterWhere(['number' => $searchModel->number])
            ->andFilterWhere(['status_id' => $searchModel->status_id]);
    }

    public function getNoResultsQuery(): ActiveQuery
    {
        return Ticket::find()->where('0=1');
    }
}