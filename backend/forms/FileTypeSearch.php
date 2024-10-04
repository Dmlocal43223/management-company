<?php

declare(strict_types=1);

namespace backend\forms;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use src\file\entities\FileType;

/**
 * FileTypeSearch represents the model behind the search form of `src\file\entities\FileType`.
 */
class FileTypeSearch extends Model
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
            [['deleted'], 'boolean'],
            [['name'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'date', 'format' => 'php:Y-m-d']
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = FileType::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'deleted' => $this->deleted,
            'date(created_at)' => $this->created_at,
            'date(updated_at)' => $this->updated_at
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name]);

        return $dataProvider;
    }
}
