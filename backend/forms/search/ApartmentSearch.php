<?php

namespace backend\forms\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use src\location\entities\Apartment;

/**
 * ApartmentSearch represents the model behind the search form of `src\location\entities\Apartment`.
 */
class ApartmentSearch extends Model
{
    public $id;
    public $number;
    public $house_id;
    public $deleted;
    public $created_at;
    public $updated_at;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'house_id'], 'integer'],
            [['number', 'created_at', 'updated_at'], 'safe'],
            [['deleted'], 'boolean'],
        ];
    }
}
