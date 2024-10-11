<?php

namespace backend\forms\search;

use yii\base\Model;

/**
 * NewsSearch represents the model behind the search form of `src\news\entities\News`.
 */
class NewsSearch extends Model
{
    public $id;
    public $title;
    public $content;
    public $author_id;
    public $deleted;
    public $created_at;
    public $updated_at;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'author_id'], 'integer'],
            [['title', 'content', 'created_at', 'updated_at'], 'safe'],
            [['deleted'], 'boolean'],
        ];
    }
}
