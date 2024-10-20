<?php

declare(strict_types=1);

namespace src\ticket\repositories;

use backend\forms\search\TicketSearch;
use src\ticket\entities\Ticket;
use src\ticket\entities\TicketStatus;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\Expression;

class TicketRepository
{
    public function findById(int $id): ?Ticket
    {
        return Ticket::findOne($id);
    }

    public function findWithRelationById(int $id): ?Ticket
    {
        return Ticket::find()
            ->innerJoinWith(['status', 'house', 'type', 'ticketHistories.status', 'files'])
            ->joinWith('apartment')
            ->andWhere(['ticket.id' => $id])
            ->one();
    }

    public function save(Ticket $ticket): void
    {
        if (!$ticket->save()) {
            $errors = implode(' ', $ticket->getErrorSummary(true));
            throw new Exception("Ошибка сохранения. {$errors}");
        }

    }

    public function getFilteredQuery(TicketSearch $searchModel): ActiveQuery
    {
        $query = Ticket::find()->andFilterWhere([
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

        if ($searchModel->created_at_range) {
            [$start, $end] = explode(' - ', $searchModel->created_at_range);
            $query->andWhere(['between', 'created_at', $start, $end]);
        }

        if ($searchModel->updated_at_range) {
            [$start, $end] = explode(' - ', $searchModel->updated_at_range);
            $query->andWhere(['between', 'updated_at', $start, $end]);
        }

        if ($searchModel->closed_at_range) {
            [$start, $end] = explode(' - ', $searchModel->closed_at_range);
            $query->andWhere(['between', 'updated_at', $start, $end]);
        }

        return $query;
    }

    public function getFilteredQueryByUser(TicketSearch $searchModel): ActiveQuery
    {
        return Ticket::find()
            ->andWhere(['created_user_id' => Yii::$app->user->id])
            ->andWhere(['deleted' => Ticket::STATUS_ACTIVE])
            ->andFilterWhere(['number' => $searchModel->number])
            ->andFilterWhere(['status_id' => $searchModel->status_id])
            ->orderBy('id desc');
    }

    public function getNoResultsQuery(): ActiveQuery
    {
        return Ticket::find()->where('0=1');
    }

    public function getTicketsStatisticsByHouse(TicketSearch $searchModel): ActiveQuery
    {
        return $this->getFilteredQuery($searchModel)
            ->select([
            'house_id',
            new Expression('COUNT(CASE WHEN deleted = true THEN 1 END) AS deleted_count'),
            new Expression('COUNT(CASE WHEN status_id = :new THEN 1 END) AS new_count'),
            new Expression('COUNT(CASE WHEN status_id = :processed THEN 1 END) AS processed_count'),
            new Expression('COUNT(CASE WHEN status_id = :closed THEN 1 END) AS closed_count'),
            new Expression('COUNT(CASE WHEN status_id = :canceled THEN 1 END) AS canceled_count'),
            ])
            ->addParams([
                ':new' => TicketStatus::STATUS_NEW_ID,
                ':processed' => TicketStatus::STATUS_PROCESSED_ID,
                ':closed' => TicketStatus::STATUS_CLOSED_ID,
                ':canceled' => TicketStatus::STATUS_CANCELED_ID,
            ])
            ->groupBy(['house_id']);
    }
}