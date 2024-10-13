<?php

declare(strict_types=1);

namespace backend\forms;

use src\location\entities\Street;
use yii\base\Model;

class StreetForm extends Model
{
    public $name;
    public $region_id;
    public $locality_id;

    public function rules(): array
    {
        return [
            [['name', 'region_id', 'locality_id'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['region_id', 'locality_id'], 'integer'],
            [['locality_id', 'name'], 'unique', 'targetClass' => Street::class, 'targetAttribute' => ['locality_id', 'name'],
                'message' => 'Комбинация идентификатора местоположения и имени должна быть уникальной.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'region_id' => 'Регион',
            'locality_id' => 'Населенный пункт',
        ];
    }
}