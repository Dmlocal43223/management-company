<?php

declare(strict_types=1);

namespace backend\forms;

use yii\base\Model;

class UserWorkerForm extends Model
{
    public $region_id;
    public $locality_id;
    public $street_id;
    public $house_id;

    public function rules(): array
    {
        return [
            [['region_id', 'locality_id', 'street_id', 'house_id'], 'required'],
            [['region_id', 'locality_id', 'street_id', 'house_id'], 'integer'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'region_id' => 'Регион',
            'locality_id' => 'Населенный пункт',
            'street_id' => 'Улица',
            'house_id' => 'Объект',
        ];
    }
}