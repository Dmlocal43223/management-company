<?php

declare(strict_types=1);

namespace backend\forms\search;

use src\file\entities\FileType;
use yii\base\Model;
use yii\data\ActiveDataProvider;

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
}
