<?php

namespace backend\forms\search;

use yii\base\Model;

/**
 * TicketHistorySearch represents the model behind the search form of `src\ticket\entities\TicketHistory`.
 */
class TicketHistorySearch extends Model
{
    public $id;
    public $reason;
    public $ticket_id;
    public $status_id;
    public $created_user_id;
    public $created_at;
    public $updated_at;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'ticket_id', 'status_id', 'created_user_id'], 'integer'],
            [['reason', 'created_at', 'updated_at'], 'safe'],
        ];
    }
}
