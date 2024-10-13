<?php

namespace backend\forms\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use src\ticket\entities\TicketHistory;

/**
 * TicketHistorySearch represents the model behind the search form of `src\ticket\entities\TicketHistory`.
 */
class TicketHistorySearch extends TicketHistory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ticket_id', 'status_id', 'created_user_id'], 'integer'],
            [['reason', 'created_at', 'updated_at'], 'safe'],
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
        $query = TicketHistory::find();

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
            'ticket_id' => $this->ticket_id,
            'status_id' => $this->status_id,
            'created_user_id' => $this->created_user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'reason', $this->reason]);

        return $dataProvider;
    }
}
