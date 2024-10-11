<?php

declare(strict_types=1);

namespace backend\forms;

use src\location\entities\Locality;
use yii\base\Model;

class LocalityForm extends Model
{
    public $name;
    public $regionId;

    public function rules(): array
    {
        return [
            [['name', 'regionId'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['regionId'], 'integer'],
            [['name'], 'unique', 'targetClass' => Locality::class]

        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'regionId' => 'Регион',
        ];
    }
}