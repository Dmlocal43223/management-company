<?php

namespace backend\forms\search;

use yii\base\Model;

/**
 * HouseSearch represents the model behind the search form of `src\location\entities\House`.
 */
class HouseSearch extends Model
{
    public $id;
    public $number;
    public $street_id;
    public $deleted;
    public $created_at;
    public $updated_at;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'street_id'], 'integer'],
            [['number', 'created_at', 'updated_at'], 'safe'],
            [['deleted'], 'boolean'],
        ];
    }
}
