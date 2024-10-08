<?php

namespace backend\forms;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use src\location\entities\Locality;

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
            [['deleted'], 'boolean'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Locality::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'region_id' => $this->region_id,
            'deleted' => $this->deleted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name]);

        return $dataProvider;
    }
}
