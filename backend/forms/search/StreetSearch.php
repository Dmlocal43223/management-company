<?php

namespace backend\forms\search;

use yii\base\Model;

/**
 * StreetSearch represents the model behind the search form of `src\location\entities\Street`.
 */
class StreetSearch extends Model
{
    public $id;
    public $locality_id;
    public $deleted;
    public $created_at;
    public $updated_at;
    public $name;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'locality_id'], 'integer'],
            [['name', 'created_at', 'updated_at'], 'safe'],
            [['deleted'], 'boolean'],
        ];
    }
}
