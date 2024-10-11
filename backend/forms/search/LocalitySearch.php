<?php

namespace backend\forms\search;

use src\location\entities\Locality;
use yii\base\Model;

/**
 * LocalitySearch represents the model behind the search form of `src\location\entities\Locality`.
 */
class LocalitySearch extends Model
{
    public $id;
    public $name;
    public $region_id;
    public $deleted;
    public $created_at;
    public $updated_at;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'region_id'], 'integer'],
            [['name', 'created_at', 'updated_at'], 'safe'],
        ];
    }
}
