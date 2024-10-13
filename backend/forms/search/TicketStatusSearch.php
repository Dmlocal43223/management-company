<?php

namespace backend\forms\search;

use yii\base\Model;

/**
 * TicketStatusSearch represents the model behind the search form of `src\ticket\entities\TicketStatus`.
 */
class TicketStatusSearch extends Model
{
    public $id;
    public $name;
    public $deleted;
    public $created_at;
    public $updated_at;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['name', 'created_at', 'updated_at'], 'safe'],
            [['deleted'], 'boolean'],
        ];
    }
}
