<?php

namespace backend\forms\search;

use yii\base\Model;

/**
 * TicketSearch represents the model behind the search form of `src\ticket\entities\Ticket`.
 */
class TicketSearch extends Model
{
    public $id;
    public $number;
    public $status_id;
    public $house_id;
    public $apartment_id;
    public $type_id;
    public $created_user_id;
    public $description;
    public $closed_at;
    public $deleted;
    public $created_at;
    public $updated_at;


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'status_id', 'house_id', 'apartment_id', 'type_id', 'created_user_id'], 'integer'],
            [['number', 'description', 'closed_at', 'created_at', 'updated_at'], 'string'],
            [['deleted'], 'boolean'],
        ];
    }
}