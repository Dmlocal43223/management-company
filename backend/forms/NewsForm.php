<?php

declare(strict_types=1);

namespace backend\forms;

use yii\base\Model;
class NewsForm extends Model
{
    public $title;
    public $content;
    public $deleted;

    public function rules(): array
    {
        return [
            [['title', 'content'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['content'], 'string'],
            [['deleted'], 'boolean']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'title' => 'Название',
            'content' => 'Содержание',
            'deleted' => 'Удалено',
        ];
    }
}