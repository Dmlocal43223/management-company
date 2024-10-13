<?php

namespace backend\forms\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use src\ticket\entities\Ticket;

/**
 * TicketSearch represents the model behind the search form of `src\ticket\entities\Ticket`.
 */
class TicketSearch extends Ticket
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status_id', 'house_id', 'apartment_id', 'type_id'], 'integer'],
            [['number', 'description', 'closed_at', 'created_at', 'updated_at'], 'safe'],
            [['deleted'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = Ticket::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status_id' => $this->status_id,
            'house_id' => $this->house_id,
            'apartment_id' => $this->apartment_id,
            'type_id' => $this->type_id,
            'deleted' => $this->deleted,
            'closed_at' => $this->closed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'number', $this->number])
            ->andFilterWhere(['ilike', 'description', $this->description]);

        return $dataProvider;
    }
}
