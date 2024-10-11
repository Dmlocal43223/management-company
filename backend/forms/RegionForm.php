<?php

declare(strict_types=1);

namespace backend\forms;

use src\location\entities\Region;
use yii\base\Model;

class RegionForm extends Model
{
    public $name;

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique', 'targetClass' => Region::class]

        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
        ];
    }
}