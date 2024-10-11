<?php

declare(strict_types=1);

namespace backend\forms;

use src\file\entities\FileType;
use yii\base\Model;

class FileTypeForm extends Model
{
    public $name;

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique', 'targetClass' => FileType::class]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
        ];
    }
}